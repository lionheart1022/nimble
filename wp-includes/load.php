o implode( ' | ', $theme_meta );

		echo '</div>';
	}

	/**
	 * Handles default column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Theme $theme       The current WP_Theme object.
	 * @param string   $column_name The current column name.
	 */
	public function column_default( $theme, $column_name ) {
		$stylesheet = $theme->get_stylesheet();

		/**
		 * Fires inside each custom column of the Multisite themes list table.
		 *
		 * @since 3.1.0
		 *
		 * @param string   $column_name Name of the column.
		 * @param string   $stylesheet  Directory name of the theme.
		 * @param WP_Theme $theme       Current WP_Theme object.
		 */
		do_action( 'manage_themes_custom_column', $column_name, $stylesheet, $theme );
	}

	/**
	 * Handles the output for a single table row.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Theme $item The current WP_Theme object.
	 */
	public function single_row_columns( $item ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$extra_classes = '';
			if ( in_array( $column_name, $hidden ) ) {
				$extra_classes .= ' hidden';
			}

			switch ( $column_name ) {
				case 'cb':
					echo '<th scope="row" class="check-column">';

					$this->column_cb( $item );

					echo '</th>';
					break;

				case 'name':
					echo "<td class='theme-title column-primary{$extra_classes}'><strong>" . $item->display('Name') . "</strong>";

					$this->column_name( $item );

					echo "</td>";
					break;

				case 'description':
					echo "<td class='column-description desc{$extra_classes}'>";

					$this->column_description( $item );

					echo '</td>';
					break;

				default:
					echo "<td class='$column_name column-$column_name{$extra_classes}'>";

					$this->column_default( $item, $column_name );

					echo "</td>";
					break;
			}
		}
	}

	/**
	 * @global string $status
	 * @global array  $totals
	 *
	 * @param WP_Theme $theme
	 */
	public function single_row( $theme ) {
		global $status, $totals;

		if ( $this->is_site_themes ) {
			$allowed = $theme->is_allowed( 'site', $this->site_id );
		} else {
			$allowed = $theme->is_allowed( 'network' );
		}

		$stylesheet = $theme->get_stylesheet();

		$class = ! $allowed ? 'inactive' : 'active';

		$id = sanitize_html_class( $theme->get_stylesheet() );

		if ( ! empty( $totals['upgrade'] ) && ! empty( $theme->update ) ) {
			$class .= ' update';
		}

		echo "<tr id='$id' class='$class'>";

		$this->single_row_columns( $theme );

		echo "</tr>";

		if ( $this->is_site_themes )
			remove_action( "after_theme_row_$stylesheet", 'wp_theme_update_row' );

		/**
		 * Fires after each row in the Multisite themes list table.
		 *
		 * @since 3.1.0
		 *
		 * @param string   $stylesheet Directory name of the theme.
		 * @param WP_Theme $theme      Current WP_Theme object.
		 * @param string   $status     Status of the theme.
		 */
		do_action( 'after_theme_row', $stylesheet, $theme, $status );

		/**
		 * Fires after each specific row in the Multisite themes list table.
		 *
		 * The dynamic portion of the hook name, `$stylesheet`, refers to the
		 * directory name of the theme, most often synonymous with the template
		 * name of the theme.
		 *
		 * @since 3.5.0
		 *
		 * @param string   $stylesheet Directory name of the theme.
		 * @param WP_Theme $theme      Current WP_Theme object.
		 * @param string   $status     Status of the theme.
		 */
		do_action( "after_theme_row_$stylesheet", $stylesheet, $theme, $status );
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php
/**
 * Sites List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WP_MS_Sites_List_Table extends WP_List_Table {

	/**
	 * Site status list.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var array
	 */
	public $status_list;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		$this->status_list = array(
			'archived' => array( 'site-archived', __( 'Archived' ) ),
			'spam'     => array( 'site-spammed', _x( 'Spam', 'site' ) ),
			'deleted'  => array( 'site-deleted', __( 'Deleted' ) ),
			'mature'   => array( 'site-mature', __( 'Mature' ) )
		);

		parent::__construct( array(
			'plural' => 'sites',
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
		) );
	}

	/**
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can( 'manage_sites' );
	}

	/**
	 *
	 * @global string $s
	 * @global string $mode
	 * @global wpdb   $wpdb
	 */
	public function prepare_items() {
		global $s, $mode, $wpdb;

		$current_site = get_current_site();

		$mode = ( empty( $_REQUEST['mode'] ) ) ? 'list' : $_REQUEST['mode'];

		$per_page = $this->get_items_per_page( 'sites_network_per_page' );

		$pagenum = $this->get_pagenum();

		$s = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST[ 's' ] ) ) : '';
		$wild = '';
		if ( false !== strpos($s, '*') ) {
			$wild = '%';
			$s = trim($s, '*');
		}

		/*
		 * If the network is large and a search is not being performed, show only
		 * the latest blogs with no paging in order to avoid expensive count queries.
		 */
		if ( !$s && wp_is_large_network() ) {
			if ( !isset($_REQUEST['orderby']) )
				$_GET['orderby'] = $_REQUEST['orderby'] = '';
			if ( !isset($_REQUEST['order']) )
				$_GET['order'] = $_REQUEST['order'] = 'DESC';
		}

		$query = "SELECT * FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}' ";

		if ( empty($s) ) {
			// Nothing to do.
		} elseif ( preg_match( '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $s ) ||
					preg_match( '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.?$/', $s ) ||
					preg_match( '/^[0-9]{1,3}\.[0-9]{1,3}\.?$/', $s ) ||
					preg_match( '/^[0-9]{1,3}\.$/', $s ) ) {
			// IPv4 address
			$sql = $wpdb->prepare( "SELECT blog_id FROM {$wpdb->registration_log} WHERE {$wpdb->registration_log}.IP LIKE %s", $wpdb->esc_like( $s ) . $wild );
			$reg_blog_ids = $wpdb->get_col( $sql );

			if ( !$reg_blog_ids )
				$reg_blog_ids = array( 0 );

			$query = "SELECT *
				FROM {$wpdb->blogs}
				WHERE site_id = '{$wpdb->siteid}'
				AND {$wpdb->blogs}.blog_id IN (" . implode( ', ', $reg_blog_ids ) . ")";
		} else {
			if ( is_numeric($s) && empty( $wild ) ) {
				$query .= $wpdb->prepare( " AND ( {$wpdb->blogs}.blog_id = %s )", $s );
			} elseif ( is_subdomain_install() ) {
				$blog_s = str_replace( '.' . $current_site->domain, '', $s );
				$blog_s = $wpdb->esc_like( $blog_s ) . $wild . $wpdb->esc_like( '.' . $current_site->domain );
				$query .= $wpdb->prepare( " AND ( {$wpdb->blogs}.domain LIKE %s ) ", $blog_s );
			} else {
				if ( $s != trim('/', $current_site->path) ) {
					$blog_s = $wpdb->esc_like( $current_site->path . $s ) . $wild . $wpdb->esc_like( '/' );
				} else {
					$blog_s = $wpdb->esc_like( $s );
				}
				$query .= $wpdb->prepare( " AND  ( {$wpdb->blogs}.path LIKE %s )", $blog_s );
			}
		}

		$order_by = isset( $_REQUEST['orderby'] ) ? $_REQUEST['orderby'] : '';
		if ( $order_by == 'registered' ) {
			$query .= ' ORDER BY registered ';
		} elseif ( $order_by == 'lastupdated' ) {
			$query .= ' ORDER BY last_updated ';
		} elseif ( $order_by == 'blogname' ) {
			if ( is_subdomain_install() ) {
				$query .= ' ORDER BY domain ';
			} else {
				$query .= ' ORDER BY path ';
			}
		} elseif ( $order_by == 'blog_id' ) {
			$query .= ' ORDER BY blog_id ';
		} else {
			$order_by = null;
		}

		if ( isset( $order_by ) ) {
			$order = ( isset( $_REQUEST['order'] ) && 'DESC' == strtoupper( $_REQUEST['order'] ) ) ? "DESC" : "ASC";
			$query .= $order;
		}

		// Don't do an unbounded count on large networks
		if ( ! wp_is_large_network() )
			$total = $wpdb->get_var( str_replace( 'SELECT *', 'SELECT COUNT( blog_id )', $query ) );

		$query .= " LIMIT " . intval( ( $pagenum - 1 ) * $per_page ) . ", " . intval( $per_page );
		$this->items = $wpdb->get_results( $query, ARRAY_A );

		if ( wp_is_large_network() )
			$total = count($this->items);

		$this->set_pagination_args( array(
			'total_items' => $total,
			'per_page' => $per_page,
		) );
	}

	/**
	 * @access public
	 */
	public function no_items() {
		_e( 'No sites found.' );
	}

	/**
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		$actions = array();
		if ( current_user_can( 'delete_sites' ) )
			$actions['delete'] = __( 'Delete' );
		$actions['spam'] = _x( 'Mark as Spam', 'site' );
		$actions['notspam'] = _x( 'Not Spam', 'site' );

		return $actions;
	}

	/**
	 * @global string $mode
	 *
	 * @param string $which
	 */
	protected function pagination( $which ) {
		global $mode;

		parent::pagination( $which );

		if ( 'top' == $which )
			$this->view_switcher( $mode );
	}

	/**
	 * @return array
	 */
	public function get_columns() {
		$sites_columns = array(
			'cb'          => '<input type="checkbox" />',
			'blogname'    => __( 'URL' ),
			'lastupdated' => __( 'Last Updated' ),
			'registered'  => _x( 'Registered', 'site' ),
			'users'       => __( 'Users' ),
		);

		if ( has_filter( 'wpmublogsaction' ) ) {
			$sites_columns['plugins'] = __( 'Actions' );
		}

		/**
		 * Filter the displayed site columns in Sites list table.
		 *
		 * @since MU
		 *
		 * @param array $sites_columns An array of displayed site columns. Default 'cb',
		 *                             'blogname', 'lastupdated', 'registered', 'users'.
		 */
		return apply_filters( 'wpmu_blogs_columns', $sites_columns );
	}

	/**
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'blogname'    => 'blogname',
			'lastupdated' => 'lastupdated',
			'registered'  => 'blog_id',
		);
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param array $blog Current site.
	 */
	public function column_cb( $blog ) {
		if ( ! is_main_site( $blog['blog_id'] ) ) :
			$blogname = untrailingslashit( $blog['domain'] . $blog['path'] );
		?>
			<label class="screen-reader-text" for="blog_<?php echo $blog['blog_id']; ?>"><?php
				printf( __( 'Select %s' ), $blogname );
			?></label>
			<input type="checkbox" id="blog_<?php echo $blog['blog_id'] ?>" name="allblogs[]" value="<?php echo esc_attr( $blog['blog_id'] ) ?>" />
		<?php endif;
	}

	/**
	 * Handles the blogname column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @global string $mode
	 *
	 * @param array $blog Current blog.
	 */
	public function column_blogname( $blog ) {
		global $mode;

		$blogname = untrailingslashit( $blog['domain'] . $blog['path'] );
		$blog_states = array();
		reset( $this->status_list );

		foreach ( $this->status_list as $status => $col ) {
			if ( $blog[ $status ] == 1 ) {
				$blog_states[] = $col[1];
			}
		}
		$blog_state = '';
		if ( ! empty( $blog_states ) ) {
			$state_count = count( $blog_states );
			$i = 0;
			$blog_state .= ' - ';
			foreach ( $blog_states as $state ) {
				++$i;
				$sep = ( $i == $state_count ) ? '' : ', ';
				$blog_state .= "<span class='post-state'>$state$sep</span>";
			}
		}

		?>
		<a href="<?php echo esc_url( network_admin_url( 'site-info.php?id=' . $blog['blog_id'] ) ); ?>" class="edit"><?php echo $blogname . $blog_state; ?></a>
		<?php
		if ( 'list' !== $mode ) {
			switch_to_blog( $blog['blog_id'] );
			/* translators: 1: site name, 2: site tagline. */
			echo '<p>' . sprintf( __( '%1$s &#8211; <em>%2$s</em>' ), get_option( 'blogname' ), get_option( 'blogdescription ' ) ) . '</p>';
			restore_current_blog();
		}
	}

	/**
	 * Handles the lastupdated column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param array $blog Current site.
	 */
	public function column_lastupdated( $blog ) {
		global $mode;

		if ( 'list' == $mode ) {
			$date = __( 'Y/m/d' );
		} else {
			$date = __( 'Y/m/d g:i:s a' );
		}

		echo ( $blog['last_updated'] == '0000-00-00 00:00:00' ) ? __( 'Never' ) : mysql2date( $date, $blog['last_updated'] );
	}

	/**
	 * Handles the registered column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param array $blog Current site.
	 */
	public function column_registered( $blog ) {
		global $mode;

		if ( 'list' == $mode ) {
			$date = __( 'Y/m/d' );
		} else {
			$date = __( 'Y/m/d g:i:s a' );
		}

		if ( $blog['registered'] == '0000-00-00 00:00:00' ) {
			echo '&#x2014;';
		} else {
			echo mysql2date( $date, $blog['registered'] );
		}
	}

	/**
	 * Handles the users column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param array $blog Current site.
	 */
	public function column_users( $blog ) {
		$user_count = wp_cache_get( $blog['blog_id'] . '_user_count', 'blog-details' );
		if ( ! $user_count ) {
			$blog_users = get_users( array( 'blog_id' => $blog['blog_id'], 'fields' => 'ID' ) );
			$user_count = count( $blog_users );
			unset( $blog_users );
			wp_cache_set( $blog['blog_id'] . '_user_count', $user_count, 'blog-details', 12 * HOUR_IN_SECONDS );
		}

		printf(
			'<a href="%s">%s</a>',
			esc_url( network_admin_url( 'site-users.php?id=' . $blog['blog_id'] ) ),
			number_format_i18n( $user_count )
		);
	}

	/**
	 * Handles the plugins column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param array $blog Current site.
	 */
	public function column_plugins( $blog ) {
		if ( has_filter( 'wpmublogsaction' ) ) {
			/**
			 * Fires inside the auxiliary 'Actions' column of the Sites list table.
			 *
			 * By default this column is hidden unless something is hooked to the action.
			 *
			 * @since MU
			 *
			 * @param int $blog_id The site ID.
			 */
			do_action( 'wpmublogsaction', $blog['blog_id'] );
		}
	}

	/**
	 * Handles output for the default column.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param array  $blog        Current site.
	 * @param string $column_name Current column name.
	 */
	public function column_default( $blog, $column_name ) {
		/**
		 * Fires for each registered custom column in the Sites list table.
		 *
		 * @since 3.1.0
		 *
		 * @param string $column_name The name of the column to display.
		 * @param int    $blog_id     The site ID.
		 */
		do_action( 'manage_sites_custom_column', $column_name, $blog['blog_id'] );
	}

	/**
	 *
	 * @global string $mode
	 */
	public function display_rows() {
		foreach ( $this->items as $blog ) {
			$class = '';
			reset( $this->status_list );

			foreach ( $this->status_list as $status => $col ) {
				if ( $blog[ $status ] == 1 ) {
					$class = " class='{$col[0]}'";
				}
			}

			echo "<tr{$class}>";

			$this->single_row_columns( $blog );

			echo '</tr>';
		}
	}

	/**
	 * Gets the name of the default primary column.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @return string Name of the default primary column, in this case, 'blogname'.
	 */
	protected function get_default_primary_column_name() {
		return 'blogname';
	}

	/**
	 * Generates and displays row action links.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @param object $blog        Blog being acted upon.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 * @return string Row actions output.
	 */
	protected function handle_row_actions( $blog, $column_name, $primary ) {
		if ( $primary !== $column_name ) {
			return;
		}

		$blogname = untrailingslashit( $blog['domain'] . $blog['path'] );

		// Preordered.
		$actions = array(
			'edit' => '', 'backend' => '',
			'activate' => '', 'deactivate' => '',
			'archive' => '', 'unarchive' => '',
			'spam' => '', 'unspam' => '',
			'delete' => '',
			'visit' => '',
		);

		$actions['edit']	= '<span class="edit"><a href="' . esc_url( network_admin_url( 'site-info.php?id=' . $blog['blog_id'] ) ) . '">' . __( 'Edit' ) . '</a></span>';
		$actions['backend']	= "<span class='backend'><a href='" . esc_url( get_admin_url( $blog['blog_id'] ) ) . "' class='edit'>" . __( 'Dashboard' ) . '</a></span>';
		if ( get_current_site()->blog_id != $blog['blog_id'] ) {
			if ( $blog['deleted'] == '1' ) {
				$actions['activate']   = '<span class="activate"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=activateblog&amp;id=' . $blog['blog_id'] ), 'activateblog_' . $blog['blog_id'] ) ) . '">' . __( 'Activate' ) . '</a></span>';
			} else {
				$actions['deactivate'] = '<span class="activate"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=deactivateblog&amp;id=' . $blog['blog_id'] ), 'deactivateblog_' . $blog['blog_id'] ) ) . '">' . __( 'Deactivate' ) . '</a></span>';
			}

			if ( $blog['archived'] == '1' ) {
				$actions['unarchive'] = '<span class="archive"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=unarchiveblog&amp;id=' . $blog['blog_id'] ), 'unarchiveblog_' . $blog['blog_id'] ) ) . '">' . __( 'Unarchive' ) . '</a></span>';
			} else {
				$actions['archive']   = '<span class="archive"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=archiveblog&amp;id=' . $blog['blog_id'] ), 'archiveblog_' . $blog['blog_id'] ) ) . '">' . _x( 'Archive', 'verb; site' ) . '</a></span>';
			}

			if ( $blog['spam'] == '1' ) {
				$actions['unspam'] = '<span class="spam"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=unspamblog&amp;id=' . $blog['blog_id'] ), 'unspamblog_' . $blog['blog_id'] ) ) . '">' . _x( 'Not Spam', 'site' ) . '</a></span>';
			} else {
				$actions['spam']   = '<span class="spam"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=spamblog&amp;id=' . $blog['blog_id'] ), 'spamblog_' . $blog['blog_id'] ) ) . '">' . _x( 'Spam', 'site' ) . '</a></span>';
			}

			if ( current_user_can( 'delete_site', $blog['blog_id'] ) ) {
				$actions['delete'] = '<span class="delete"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=deleteblog&amp;id=' . $blog['blog_id'] ), 'deleteblog_' . $blog['blog_id'] ) ) . '">' . __( 'Delete' ) . '</a></span>';
			}
		}

		$actions['visit']	= "<span class='view'><a href='" . esc_url( get_home_url( $blog['blog_id'], '/' ) ) . "' rel='permalink'>" . __( 'Visit' ) . '</a></span>';

		/**
		 * Filter the action links displayed for each site in the Sites list table.
		 *
		 * The 'Edit', 'Dashboard', 'Delete', and 'Visit' links are displayed by
		 * default for each site. The site's status determines whether to show the
		 * 'Activate' or 'Deactivate' link, 'Unarchive' or 'Archive' links, and
		 * 'Not Spam' or 'Spam' link for each site.
		 *
		 * @since 3.1.0
		 *
		 * @param array  $actions  An array of action links to be displayed.
		 * @param int    $blog_id  The site ID.
		 * @param string $blogname Site path, formatted depending on whether it is a sub-domain
		 *                         or subdirectory multisite install.
		 */
		$actions = apply_filters( 'manage_sites_action_links', array_filter( $actions ), $blog['blog_id'], $blogname );
		return $this->row_actions( $actions );
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php
/**
 * Media Library List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WP_Media_List_Table extends WP_List_Table {

	private $detached;

	private $is_trash;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		$this->detached = ( isset( $_REQUEST['attachment-filter'] ) && 'detached' === $_REQUEST['attachment-filter'] );

		$this->modes = array(
			'list' => __( 'List View' ),
			'grid' => __( 'Grid View' )
		);

		parent::__construct( array(
			'plural' => 'media',
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
		) );
	}

	/**
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can('upload_files');
	}

	/**
	 *
	 * @global WP_Query $wp_query
	 * @global array    $post_mime_types
	 * @global array    $avail_post_mime_types
	 * @global string   $mode
	 */
	public function prepare_items() {
		global $wp_query, $post_mime_types, $avail_post_mime_types, $mode;

		list( $post_mime_types, $avail_post_mime_types ) = wp_edit_attachments_query( $_REQUEST );

 		$this->is_trash = isset( $_REQUEST['attachment-filter'] ) && 'trash' == $_REQUEST['attachment-filter'];

 		$mode = empty( $_REQUEST['mode'] ) ? 'list' : $_REQUEST['mode'];

		$this->set_pagination_args( array(
			'total_items' => $wp_query->found_posts,
			'total_pages' => $wp_query->max_num_pages,
			'per_page' => $wp_query->query_vars['posts_per_page'],
		) );
	}

	/**
	 *
	 * @global wpdb  $wpdb
	 * @global array $post_mime_types
	 * @global array $avail_post_mime_types
	 * @return array
	 */
	protected function get_views() {
		global $wpdb, $post_mime_types, $avail_post_mime_types;

		$type_links = array();
		$_num_posts = (array) wp_count_attachments();
		$_total_posts = array_sum($_num_posts) - $_num_posts['trash'];
		$total_orphans = $wpdb->get_var( "SELECT COUNT( * ) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent < 1" );
		$matches = wp_match_mime_types(array_keys($post_mime_types), array_keys($_num_posts));
		$num_posts = array();
		foreach ( $matches as $type => $reals ) {
			foreach ( $reals as $real ) {
				$num_posts[$type] = ( isset( $num_posts[$type] ) ) ? $num_posts[$type] + $_num_posts[$real] : $_num_posts[$real];
			}
		}
		$selected = empty( $_GET['attachment-filter'] ) ? ' selected="selected"' : '';
		$type_links['all'] = "<option value=''$selected>" . sprintf( _nx( 'All (%s)', 'All (%s)', $_total_posts, 'uploaded files' ), number_format_i18n( $_total_posts ) ) . '</option>';
		foreach ( $post_mime_types as $mime_type => $label ) {
			if ( !wp_match_mime_types($mime_type, $avail_post_mime_types) )
				continue;

			$selected = '';
			if ( !empty( $_GET['attachment-filter'] ) && strpos( $_GET['attachment-filter'], 'post_mime_type:' ) === 0 && wp_match_mime_types( $mime_type, str_replace( 'post_mime_type:', '', $_GET['attachment-filter'] ) ) )
				$selected = ' selected="selected"';
			if ( !empty( $num_posts[$mime_type] ) )
				$type_links[$mime_type] = '<option value="post_mime_type:' . esc_attr( $mime_type ) . '"' . $selected . '>' . sprintf( translate_nooped_plural( $label[2], $num_posts[$mime_type] ), number_format_i18n( $num_posts[$mime_type] )) . '</option>';
		}
		$type_links['detached'] = '<option value="detached"' . ( $this->detached ? ' selected="selected"' : '' ) . '>' . sprintf( _nx( 'Unattached (%s)', 'Unattached (%s)', $total_orphans, 'detached files' ), number_format_i18n( $total_orphans ) ) . '</option>';

		if ( !empty($_num_posts['trash']) )
			$type_links['trash'] = '<option value="trash"' . ( (isset($_GET['attachment-filter']) && $_GET['attachment-filter'] == 'trash' ) ? ' selected="selected"' : '') . '>' . sprintf( _nx( 'Trash (%s)', 'Trash (%s)', $_num_posts['trash'], 'uploaded files' ), number_format_i18n( $_num_posts['trash'] ) ) . '</option>';

		return $type_links;
	}

	/**
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		$actions = array();
		if ( MEDIA_TRASH ) {
			if ( $this->is_trash ) {
				$actions['untrash'] = __( 'Restore' );
				$actions['delete'] = __( 'Delete Permanently' );
			} else {
				$actions['trash