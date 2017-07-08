eak;
				case 'enabled':
					$text = _n( 'Enabled <span class="count">(%s)</span>', 'Enabled <span class="count">(%s)</span>', $count );
					break;
				case 'disabled':
					$text = _n( 'Disabled <span class="count">(%s)</span>', 'Disabled <span class="count">(%s)</span>', $count );
					break;
				case 'upgrade':
					$text = _n( 'Update Available <span class="count">(%s)</span>', 'Update Available <span class="count">(%s)</span>', $count );
					break;
				case 'broken' :
					$text = _n( 'Broken <span class="count">(%s)</span>', 'Broken <span class="count">(%s)</span>', $count );
					break;
			}

			if ( $this->is_site_themes )
				$url = 'site-themes.php?id=' . $this->site_id;
			else
				$url = 'themes.php';

			if ( 'search' != $type ) {
				$status_links[$type] = sprintf( "<a href='%s' %s>%s</a>",
					esc_url( add_query_arg('theme_status', $type, $url) ),
					( $type == $status ) ? ' class="current"' : '',
					sprintf( $text, number_format_i18n( $count ) )
				);
			}
		}

		return $status_links;
	}

	/**
	 * @global string $status
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		global $status;

		$actions = array();
		if ( 'enabled' != $status )
			$actions['enable-selected'] = $this->is_site_themes ? __( 'Enable' ) : __( 'Network Enable' );
		if ( 'disabled' != $status )
			$actions['disable-selected'] = $this->is_site_themes ? __( 'Disable' ) : __( 'Network Disable' );
		if ( ! $this->is_site_themes ) {
			if ( current_user_can( 'update_themes' ) )
				$actions['update-selected'] = __( 'Update' );
			if ( current_user_can( 'delete_themes' ) )
				$actions['delete-selected'] = __( 'Delete' );
		}
		return $actions;
	}

	/**
	 * @access public
	 */
	public function display_rows() {
		foreach ( $this->items as $theme )
			$this->single_row( $theme );
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Theme $theme The current WP_Theme object.
	 */
	public function column_cb( $theme ) {
		$checkbox_id = 'checkbox_' . md5( $theme->get('Name') );
		?>
		<input type="checkbox" name="checked[]" value="<?php echo esc_attr( $theme->get_stylesheet() ) ?>" id="<?php echo $checkbox_id ?>" />
		<label class="screen-reader-text" for="<?php echo $checkbox_id ?>" ><?php _e( 'Select' ) ?>  <?php echo $theme->display( 'Name' ) ?></label>
		<?php
	}

	/**
	 * Handles the name column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @global string $status
	 * @global int    $page
	 * @global string $s
	 *
	 * @param WP_Theme $theme The current WP_Theme object.
	 */
	public function column_name( $theme ) {
		global $status, $page, $s;

		$context = $status;

		if ( $this->is_site_themes ) {
			$url = "site-themes.php?id={$this->site_id}&amp;";
			$allowed = $theme->is_allowed( 'site', $this->site_id );
		} else {
			$url = 'themes.php?';
			$allowed = $theme->is_allowed( 'network' );
		}

		// Pre-order.
		$actions = array(
			'enable' => '',
			'disable' => '',
			'edit' => '',
			'delete' => ''
		);

		$stylesheet = $theme->get_stylesheet();
		$theme_key = urlencode( $stylesheet );

		if ( ! $allowed ) {
			if ( ! $theme->errors() ) {
				$actions['enable'] = '<a href="' . esc_url( wp_nonce_url($url . 'action=enable&amp;theme=' . $theme_key . '&amp;paged=' . $page . '&amp;s=' . $s, 'enable-theme_' . $stylesheet ) ) . '" title="' . esc_attr__('Enable this theme') . '" class="edit">' . ( $this->is_site_themes ? __( 'Enable' ) : __( 'Network Enable' ) ) . '</a>';
			}
		} else {
			$actions['disable'] = '<a href="' . esc_url( wp_nonce_url($url . 'action=disable&amp;theme=' . $theme_key . '&amp;paged=' . $page . '&amp;s=' . $s, 'disable-theme_' . $stylesheet ) ) . '" title="' . esc_attr__('Disable this theme') . '">' . ( $this->is_site_themes ? __( 'Disable' ) : __( 'Network Disable' ) ) . '</a>';
		}

		if ( current_user_can('edit_themes') ) {
			$actions['edit'] = '<a href="' . esc_url('theme-editor.php?theme=' . $theme_key ) . '" title="' . esc_attr__('Open this theme in the Theme Editor') . '" class="edit">' . __('Edit') . '</a>';
		}

		if ( ! $allowed && current_user_can( 'delete_themes' ) && ! $this->is_site_themes && $stylesheet != get_option( 'stylesheet' ) && $stylesheet != get_option( 'template' ) ) {
			$actions['delete'] = '<a href="' . esc_url( wp_nonce_url( 'themes.php?action=delete-selected&amp;checked[]=' . $theme_key . '&amp;theme_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'bulk-themes' ) ) . '" title="' . esc_attr__( 'Delete this theme' ) . '" class="delete">' . __( 'Delete' ) . '</a>';
		}
		/**
		 * Filter the action links displayed for each theme in the Multisite
		 * themes list table.
		 *
		 * The action links displayed are determined by the theme's status, and
		 * which Multisite themes list table is being displayed - the Network
		 * themes list table (themes.php), which displays all installed themes,
		 * or the Site themes list 