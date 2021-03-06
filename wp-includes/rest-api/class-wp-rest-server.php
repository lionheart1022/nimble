item->type && $wp_query->is_singular ) ||
				( 'taxonomy' == $menu_item->type && ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ) && $queried_object->taxonomy == $menu_item->object )
			)
		) {
			$classes[] = 'current-menu-item';
			$menu_items[$key]->current = true;
			$_anc_id = (int) $menu_item->db_id;

			while(
				( $_anc_id = get_post_meta( $_anc_id, '_menu_item_menu_item_parent', true ) ) &&
				! in_array( $_anc_id, $active_ancestor_item_ids )
			) {
				$active_ancestor_item_ids[] = $_anc_id;
			}

			if ( 'post_type' == $menu_item->type && 'page' == $menu_item->object ) {
				// Back compat classes for pages to match wp_page_menu()
				$classes[] = 'page_item';
				$classes[] = 'page-item-' . $menu_item->object_id;
				$classes[] = 'current_page_item';
			}
			$active_parent_item_ids[] = (int) $menu_item->menu_item_parent;
			$active_parent_object_ids[] = (int) $menu_item->post_parent;
			$active_object = $menu_item->object;

		// if the menu item corresponds to the currently-requested URL
		} elseif ( 'custom' == $menu_item->object ) {
			$_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );
			$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_root_relative_current );
			$raw_item_url = strpos( $menu_item->url, '#' ) ? substr( $menu_item->url, 0, strpos( $menu_item->url, '#' ) ) : $menu_item->url;
			$item_url = set_url_scheme( untrailingslashit( $raw_item_url ) );
			$_indexless_current = untrailingslashit( preg_replace( '/' . preg_quote( $wp_rewrite->index, '/' ) . '$/', '', $current_url ) );

			if ( $raw_item_url && in_array( $item_url, array( $current_url, $_indexless_current, $_root_relative_current ) ) ) {
				$classes[] = 'current-menu-item';
				$menu_items[$key]->current = true;
				$_anc_id = (int) $menu_item->db_id;

				while(
					( $_anc_id = get_post_meta( $_anc_id, '_menu_item_menu_item_parent', true ) ) &&
					! in_array( $_anc_id, $active_ancestor_item_ids )
				) {
					$active_ancestor_item_ids[] = $_anc_id;
				}

				if ( in_array( home_url(), array( untrailingslashit( $current_url ), untrailingslashit( $_indexless_current ) ) ) ) {
					// Back compat for home link to match wp_page_menu()
					$classes[] = 'current_page_item';
				}
				$active_parent_item_ids[] = (int) $menu_item->menu_item_parent;
				$active_parent_object_ids[] = (int) $menu_item->post_parent;
				$active_object = $menu_item->object;

			// give front page item current-menu-item class when extra query arguments involved
			} elseif ( $item_url == $front_page_url && is_front_page() ) {
				$classes[] = 'current-menu-item';
			}

			if ( untrailingslashit($item_url) == home_url() )
				$classes[] = 'menu-item-home';
		}

		// back-compat with wp_page_menu: add "current_page_parent" to static home page link for any non-page query
		if ( ! empty( $home_page_id ) && 'post_type' == $menu_item->type && empty( $wp_query->is_page ) && $home_page_id == $menu_item->object_id )
			$classes[] = 'current_page_parent';

		$menu_items[$key]->classes = array_unique( $classes );
	}
	$active_ancestor_item_ids = array_filter( array_unique( $active_ancestor_item_ids ) );
	$active_parent_item_ids = array_filter( array_unique( $active_parent_item_ids ) );
	$active_parent_object_ids = array_filter( array_unique( $active_parent_object_ids ) );

	// set parent's class
	foreach ( (array) $menu_items as $key => $parent_item ) {
		$classes = (array) $parent_item->classes;
		$menu_items[$key]->current_item_ancestor = false;
		$menu_items[$key]->current_item_parent = false;

		if (
			isset( $parent_item->type ) &&
			(
				// ancestral post object
				(
					'post_type' == $parent_item->type &&
					! empty( $queried_object->post_type ) &&
					is_post_type_hierarchical( $queried_object->post_type ) &&
					in_array( $parent_item->object_id, $queried_object->ancestors ) &&
					$parent_item->object != $queried_object->ID
				) ||

				// ancestral term
				(
					'taxonomy' == $parent_item->type &&
					isset( $possible_taxonomy_ancestors[ $parent_item->object ] ) &&
					in_array( $parent_item->object_id, $possible_taxonomy_ancestors[ $parent_item->object ] ) &&
					(
						! isset( $queried_object->term_id ) ||
						$parent_item->object_id != $queried_object->term_id
					)
				)
			)
		) {
			$classes[] = empty( $queried_object->taxonomy ) ? 'current-' . $queried_object->post_type . '-ancestor' : 'current-' . $queried_object->taxonomy . '-ancestor';
		}

		if ( in_array(  intval( $parent_item->db_id ), $active_ancestor_item_ids ) ) {
			$classes[] = 'current-menu-ancestor';
			$menu_items[$key]->current_item_ancestor = true;
		}
		if ( in_array( $parent_item->db_id, $active_parent_item_ids ) ) {
			$classes[] = 'current-menu-parent';
			$menu_items[$key]->current_item_parent = true;
		}
		if ( in_array( $parent_item->object_id, $active_parent_object_ids ) )
			$classes[] = 'current-' . $active_object . '-parent';

		if ( 'post_type' == $parent_item->type && 'page' == $parent_item->object ) {
			// Back compat classes for pages to match wp_page_menu()
			if ( in_array('current-menu-parent', $classes) )
				$classes[] = 'current_page_parent';
			if ( in_array('current-menu-ancestor', $classes) )
				$classes[] = 'current_page_ancestor';
		}

		$menu_items[$key]->classes = array_unique( $classes );
	}
}

/**
 * Retrieve the HTML list content for nav menu items.
 *
 * @uses Walker_Nav_Menu to create HTML list content.
 * @since 3.0.0
 *
 * @param array  $items
 * @param int    $depth
 * @param object $r
 * @return string
 */
function walk_nav_menu_tree( $items, $depth, $r ) {
	$walker = ( empty($r->walker) ) ? new Walker_Nav_Menu : $r->walker;
	$args = array( $items, $depth, $r );

	return call_user_func_array( array( $walker, 'walk' ), $args );
}

/**
 * Prevents a menu item ID from being used more than once.
 *
 * @since 3.0.1
 * @access private
 *
 * @staticvar array $used_ids
 * @param string $id
 * @param object $item
 * @return string
 */
function _nav_menu_item_id_use_once( $id, $item ) {
	static $_used_ids = array();
	if ( in_array( $item->ID, $_used_ids ) ) {
		return '';
	}
	$_used_ids[] = $item->ID;
	return $id;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php
/**
 * These functions are needed to load Multisite.
 *
 * @since 3.0.0
 *
 * @package WordPress
 * @subpackage Multisite
 */

/**
 * Whether a subdomain configuration is enabled.
 *
 * @since 3.0.0
 *
 * @return bool True if subdomain configuration is enabled, false otherwise.
 */
function is_subdomain_install() {
	if ( defined('SUBDOMAIN_INSTALL') )
		return SUBDOMAIN_INSTALL;

	return ( defined( 'VHOST' ) && VHOST == 'yes' );
}

/**
 * Returns array of network plugin files to be included in global scope.
 *
 * The default directory is wp-content/plugins. To change the default directory
 * manually, define `WP_PLUGIN_DIR` and `WP_PLUGIN_URL` in `wp-config.php`.
 *
 * @access private
 * @since 3.1.0
 *
 * @return array Files to include.
 */
function wp_get_active_network_plugins() {
	$active_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );
	if ( empty( $active_plugins ) )
		return array();

	$plugins = array();
	$active_plugins = array_keys( $active_plugins );
	sort( $active_plugins );

	foreach ( $active_plugins as $plugin ) {
		if ( ! validate_file( $plugin ) // $plugin must validate as file
			&& '.php' == substr( $plugin, -4 ) // $plugin must end with '.php'
			&& file_exists( WP_PLUGIN_DIR . '/' . $plugin ) // $plugin must exist
			)
		$plugins[] = WP_PLUGIN_DIR . '/' . $plugin;
	}
	return $plugins;
}

/**
 * Checks status of current blog.
 *
 * Checks if the blog is deleted, inactive, archived, or spammed.
 *
 * Dies with a default message if the blog does not pass the check.
 *
 * To change the default message when a blog does not pass the check,
 * use the wp-content/blog-deleted.php, blog-inactive.php and
 * blog-suspended.php drop-ins.
 *
 * @since 3.0.0
 *
 * @return true|string Returns true on success, or drop-in file to include.
 */
function ms_site_check() {
	$blog = get_blog_details();

	/**
	 * Filter checking the status of the current blog.
	 *
	 * @since 3.0.0
	 *
	 * @param bool null Whether to skip the blog status check. Default null.
	*/
	$check = apply_filters( 'ms_site_check', null );
	if ( null !== $check )
		return true;

	// Allow super admins to see blocked sites
	if ( is_super_admin() )
		return true;

	if ( '1' == $blog->deleted ) {
		if ( file_exists( WP_CONTENT_DIR . '/blog-deleted.php' ) )
			return WP_CONTENT_DIR . '/blog-deleted.php';
		else
			wp_die( __( 'This site is no longer available.' ), '', array( 'response' => 410 ) );
	}

	if ( '2' == $blog->deleted ) {
		if ( file_exists( WP_CONTENT_DIR . '/blog-inactive.php' ) )
			return WP_CONTENT_DIR . '/blog-inactive.php';
		else
			wp_die( sprintf( __( 'This site has not been activated yet. If you are having problems activating your site, please contact <a href="mailto:%1$s">%1$s</a>.' ), str_replace( '@', ' AT ', get_site_option( 'admin_email', 'support@' . get_current_site()->domain ) ) ) );
	}

	if ( $blog->archived == '1' || $blog->spam == '1' ) {
		if ( file_exists( WP_CONTENT_DIR . '/blog-suspended.php' ) )
			return WP_CONTENT_DIR . '/blog-suspended.php';
		else
			wp_die( __( 'This site has been archived or suspended.' ), '', array( 'response' => 410 ) );
	}

	return true;
}

/**
 * Retrieve a network object by its domain and path.
 *
 * @since 3.9.0
 *
 * @global wpdb $wpdb
 *
 * @param string   $domain   Domain to check.
 * @param string   $path     Path to check.
 * @param int|null $segments Path segments to use. Defaults to null, or the full path.
 * @return object|false Network object if successful. False when no network is found.
 */
function get_network_by_path( $domain, $path, $segments = null ) {
	global $wpdb;

	$domains = array( $domain );
	$pieces = explode( '.', $domain );

	/*
	 * It's possible one domain to search is 'com', but it might as well
	 * be 'localhost' or some other locally mapped domain.
	 */
	while ( array_shift( $pieces ) ) {
		if ( $pieces ) {
			$domains[] = implode( '.', $pieces );
		}
	}

	/*
	 * If we've gotten to this function during normal execution, there is
	 * more than one network installed. At this point, who knows how many
	 * we have. Attempt to optimize for the situation where networks are
	 * only domains, thus meaning paths never need to be considered.
	 *
	 * This is a very basic optimization; anything further could have drawbacks
	 * depending on the setup, so this is best done per-install.
	 */
	$using_paths = true;
	if ( wp_using_ext_object_cache() ) {
		$using_paths = wp_cache_get( 'networks_have_paths', 'site-options' );
		if ( false === $using_paths ) {
			$using_paths = (bool) $wpdb->get_var( "SELECT id FROM $wpdb->site WHERE path <> '/' LIMIT 1" );
			wp_cache_add( 'networks_have_paths', (int) $using_paths, 'site-options'  );
		}
	}

	$paths = array();
	if ( $using_paths ) {
		$path_segments = array_filter( explode( '/', trim( $path, "/" ) ) );

		/**
		 * Filter the number of path segments to consider when searching for a site.
		 *
		 * @since 3.9.0
		 *
		 * @param int|null $segments The number of path segments to consider. WordPress by default looks at
		 *                           one path segment. The function default of null only makes sense when you
		 *                           know the requested path should match a network.
		 * @param string   $domain   The requested domain.
		 * @param string   $path     The requested path, in full.
		 */
		$segments = apply_filters( 'network_by_path_segments_count', $segments, $domain, $path );

		if ( null !== $segments && count($path_segments ) > $segments ) {
			$path_segments = array_slice( $path_segments, 0, $segments );
		}

		while ( count( $path_segments ) ) {
			$paths[] = '/' . implode( '/', $path_segments ) . '/';
			array_pop( $path_segments );
		}

		$paths[] = '/';
	}

	/**
	 * Determine a network by its domain and path.
	 *
	 * This allows one to short-circuit the default logic, perhaps by
	 * replacing it with a routine that is more optimal for your setup.
	 *
	 * Return null to avoid the short-circuit. Return false if no network
	 * can be found at the requested domain and path. Otherwise, return
	 * an object from wp_get_network().
	 *
	 * @since 3.9.0
	 *
	 * @param null|bool|object $network  Network value to return by path.
	 * @param string           $domain   The requested domain.
	 * @param string           $path     The requested path, in full.
	 * @param int|null         $segments The suggested number of paths to consult.
	 *                                   Default null, meaning the entire path was to be consulted.
	 * @param array            $paths    The paths to search for, based on $path and $segments.
	 */
	$pre = apply_filters( 'pre_get_network_by_path', null, $domain, $path, $segments, $paths );
	if ( null !== $pre ) {
		return $pre;
	}

	// @todo Consider additional optimization routes, perhaps as an opt-in for plugins.
	// We already have paths covered. What about how far domains should be drilled down (including www)?

	$search_domains = "'" . implode( "', '", $wpdb->_escape( $domains ) ) . "'";

	if ( ! $using_paths ) {
		$network = $wpdb->get_row( "SELECT id, domain, path FROM $wpdb->site
			WHERE domain IN ($search_domains) ORDER BY CHAR_LENGTH(domain) DESC LIMIT 1" );
		if ( $network ) {
			return wp_get_network( $network );
		}
		return false;

	} else {
		$search_paths = "'" . implode( "', '", $wpdb->_escape( $paths ) ) . "'";
		$networks = $wpdb->get_results( "SELECT id, domain, path FROM $wpdb->site
			WHERE domain IN ($search_domains) AND path IN ($search_paths)
			ORDER BY CHAR_LENGTH(domain) DESC, CHAR_LENGTH(path) DESC" );
	}

	/*
	 * Domains are sorted by length of domain, then by length of path.
	 * The domain must match for the path to be considered. Otherwise,
	 * a network with the path of / will suffice.
	 */
	$found = false;
	foreach ( $networks as $network ) {
		if ( $network->domain === $domain || "www.$network->domain" === $domain ) {
			if ( in_array( $network->path, $paths, true ) ) {
				$found = true;
				break;
			}
		}
		if ( $network->path === '/' ) {
			$found = true;
			break;
		}
	}

	if ( $found ) {
		return wp_get_network( $network );
	}

	return false;
}

/**
 * Retrieve an object containing information about the requested network.
 *
 * @since 3.9.0
 *
 * @global wpdb $wpdb
 *
 * @param object|int $network The network's database row or ID.
 * @return object|false Object containing network information if found, false if not.
 */
function wp_get_network( $network ) {
	global $wpdb;

	if ( ! is_object( $network ) ) {
		$network = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->site WHERE id = %d", $network ) );
		if ( ! $network ) {
			return false;
		}
	}

	return $network;
}

/**
 * Retrieve a site object by its domain and path.
 *
 * @since 3.9.0
 *
 * @global wpdb $wpdb
 *
 * @param string   $domain   Domain to check.
 * @param string   $path     Path to check.
 * @param int|null $segments Path segments to use. Defaults to null, or the full path.
 * @return object|false Site object if successful. False when no site is found.
 */
function get_site_by_path( $domain, $path, $segments = null ) {
	global $wpdb;

	$path_segments = array_filter( explode( '/', trim( $path, '/' ) ) );

	/**
	 * Filter the number of path segments to consider when searching for a site.
	 *
	 * @since 3.9.0
	 *
	 * @param int|null $segments The number of path segments to consider. WordPress by default looks at
	 *                           one path segment following the network path. The function default of
	 *                           null only makes sense when you know the requested path should match a site.
	 * @param string   $domain   The requested domain.
	 * @param string   $path     The requested path, in full.
	 */
	$segments = apply_filters( 'site_by_path_segments_count', $segments, $domain, $path );

	if ( null !== $segments && count( $path_segments ) > $segments ) {
		$path_segments = array_slice( $path_segments, 0, $segments );
	}

	$paths = array();

	while ( count( $path_segments ) ) {
		$paths[] = '/' . implode( '/', $path_segments ) . '/';
		array_pop( $path_segments );
	}

	$paths[] = '/';

	/**
	 * Determine a site by its domain and path.
	 *
	 * This allows one to short-circuit the default logic, perhaps by
	 * replacing it with a routine that is more optimal for your setup.
	 *
	 * Return null to avoid the short-circuit. Return false if no site
	 * can be found at the requested domain and path. Otherwise, return
	 * a site object.
	 *
	 * @since 3.9.0
	 *
	 * @param null|bool|object $site     Site value to return by path.
	 * @param string           $domain   The requested domain.
	 * @param string           $path     The requested path, in full.
	 * @param int|null         $segments The suggested number of paths to consult.
	 *                                   Default null, meaning the entire path was to be consulted.
	 * @param array            $paths    The paths to search for, based on $path and $segments.
	 */
	$pre = apply_filters( 'pre_get_site_by_path', null, $domain, $path, $segments, $paths );
	if ( null !== $pre ) {
		return $pre;
	}

	/*
	 * @todo
	 * get_blog_details(), caching, etc. Consider alternative optimization routes,
	 * perhaps as an opt-in for plugins, rather than using the pre_* filter.
	 * For example: The segments filter can expand or ignore paths.
	 * If persistent caching is enabled, we could query the DB for a path <> '/'
	 * then cache whether we can just always ignore paths.
	 */

	// Either www or non-www is supported, not both. If a www domain is requested,
	// query for both to provide the proper redirect.
	$domains = array( $domain );
	if ( 'www.' === substr( $domain, 0, 4 ) ) {
		$domains[] = substr( $domain, 4 );
		$search_domains = "'" . implode( "', '", $wpdb->_escape( $domains ) ) . "'";
	}

	if ( count( $paths ) > 1 ) {
		$search_paths = "'" . implode( "', '", $wpdb->_escape( $paths ) ) . "'";
	}

	if ( count( $domains ) > 1 && count( $paths ) > 1 ) {
		$site = $wpdb->get_row( "SELECT * FROM $wpdb->blogs WHERE domain IN ($search_domains) AND path IN ($search_paths) ORDER BY CHAR_LENGTH(domain) DESC, CHAR_LENGTH(path) DESC LIMIT 1" );
	} elseif ( count( $domains ) > 1 ) {
		$sql = $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE path = %s", $paths[0] );
		$sql .= " AND domain IN ($search_domains) ORDER BY CHAR_LENGTH(domain) DESC LIMIT 1";
		$site = $wpdb->get_row( $sql );
	} elseif ( count( $paths ) > 1 ) {
		$sql = $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain = %s", $domains[0] );
		$sql .= " AND path IN ($search_paths) ORDER BY CHAR_LENGTH(path) DESC LIMIT 1";
		$site = $wpdb->get_row( $sql );
	} else {
		$site = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain = %s AND path = %s", $domains[0], $paths[0] ) );
	}

	if ( $site ) {
		// @todo get_blog_details()
		return $site;
	}

	return false;
}

/**
 * Displays a failure message.
 *
 * Used when a blog's tables do not exist. Checks for a missing $wpdb->site table as well.
 *
 * @access private
 * @since 3.0.0
 *
 * @global wpdb   $wpdb
 * @global string $domain
 * @global string $path
 */
function ms_not_installed() {
	global $wpdb, $domain, $path;

	if ( ! is_admin() ) {
		dead_db();
	}

	wp_load_translations_early();

	$title = __( 'Error establishing a database connection' );

	$msg  = '<h1>' . $title . '</h1>';
	$msg .= '<p>' . __( 'If your site does not display, please contact the owner of this network.' ) . '';
	$msg .= ' ' . __( 'If you are the owner of this network please check that MySQL is running properly and all tables are error free.' ) . '</p>';
	$query = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( $wpdb->site ) );
	if ( ! $wpdb->get_var( $query ) ) {
		$msg .= '<p>' . sprintf(
			/* translators: %s: table name */
			__( '<strong>Database tables are missing.</strong> This means that MySQL is not running, WordPress was not installed properly, or someone deleted %s. You really should look at your database now.' ),
			'<code>' . $wpdb->site . '</code>'
		) . '</p>';
	} else {
		$msg .= '<p>' . sprintf(
			/* translators: 1: site url, 2: table name, 3: database name */
			__( '<strong>Could not find site %1$s.</strong> Searched for table %2$s in database %3$s. Is that right?' ),
			'<code>' . rtrim( $domain . $path, '/' ) . '</code>',
			'<code>' . $wpdb->blogs . '</code>',
			'<code>' . DB_NAME . '</code>'
		) . '</p>';
	}
	$msg .= '<p><strong>' . __( 'What do I do now?' ) . '</strong> ';
	$msg .= __( 'Read the <a target="_blank" href="https://codex.wordpress.org/Debugging_a_WordPress_Network">bug report</a> page. Some of the guidelines there may help you figure out what went wrong.' );
	$msg .= ' ' . __( 'If you&#8217;re still stuck with this message, then check that your database contains the following tables:' ) . '</p><ul>';
	foreach ( $wpdb->tables('global') as $t => $table ) {
		if ( 'sitecategories' == $t )
			continue;
		$msg .= '<li>' . $table . '</li>';
	}
	$msg .= '</ul>';

	wp_die( $msg, $title, array( 'response' => 500 ) );
}

/**
 * This deprecated function formerly set the site_name property of the $current_site object.
 *
 * This function simply returns the object, as before.
 * The bootstrap takes care of setting site_name.
 *
 * @access private
 * @since 3.0.0
 * @deprecated 3.9.0 Use get_current_site() instead.
 *
 * @param object $current_site
 * @return object
 */
function get_current_site_name( $current_site ) {
	_deprecated_function( __FUNCTION__, '3.9', 'get_current_site()' );
	return $current_site;
}

/**
 * This deprecated function managed much of the site and network loading in multisite.
 *
 * The current bootstrap code is now responsible for parsing the site and network load as
 * well as setting the global $current_site object.
 *
 * @access private
 * @since 3.0.0
 * @deprecated 3.9.0
 *
 * @global object $current_site
 *
 * @return object
 */
function wpmu_current_site() {
	global $current_site;
	_deprecated_function( __FUNCTION__, '3.9' );
	return $current_site;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php

/**
 * Site/blog functions that work with the blogs table and related data.
 *
 * @package WordPress
 * @subpackage Multisite
 * @since MU
 */

/**
 * Update the last_updated field for the current blog.
 *
 * @since MU
 *
 * @global wpdb $wpdb
 */
function wpmu_update_blogs_date() {
	global $wpdb;

	update_blog_details( $wpdb->blogid, array('last_updated' => current_time('mysql', true)) );
	/**
	 * Fires after the blog details are updated.
	 *
	 * @since MU
	 *
	 * @param int $blog_id Blog ID.
	 */
	do_action( 'wpmu_blog_updated', $wpdb->blogid );
}

/**
 * Get a full blog URL, given a blog id.
 *
 * @since MU
 *
 * @param int $blog_id Blog ID
 * @return string Full URL of the blog if found. Empty string if not.
 */
function get_blogaddress_by_id( $blog_id ) {
	$bloginfo = get_blog_details( (int) $blog_id, false ); // only get bare details!
	return ( $bloginfo ) ? esc_url( 'http://' . $bloginfo->domain . $bloginfo->path ) : '';
}

/**
 * Get a full blog URL, given a blog name.
 *
 * @since MU
 *
 * @param string $blogname The (subdomain or directory) name
 * @return string
 */
function get_blogaddress_by_name( $blogname ) {
	if ( is_subdomain_install() ) {
		if ( $blogname == 'main' )
			$blogname = 'www';
		$url = rtrim( network_home_url(), '/' );
		if ( !empty( $blogname ) )
			$url = preg_replace( '|^([^\.]+://)|', "\${1}" . $blogname . '.', $url );
	} else {
		$url = network_home_url( $blogname );
	}
	return esc_url( $url . '/' );
}

/**
 * Given a blog's (subdomain or directory) slug, retrieve its id.
 *
 * @since MU
 *
 * @global wpdb $wpdb
 *
 * @param string $slug
 * @return int A blog id
 */
function get_id_from_blogname( $slug ) {
	global $wpdb;

	$current_site = get_current_site();
	$slug = trim( $slug, '/' );

	$blog_id = wp_cache_get( 'get_id_from_blogname_' . $slug, 'blog-details' );
	if ( $blog_id )
		return $blog_id;

	if ( is_subdomain_install() ) {
		$domain = $slug . '.' . $current_site->domain;
		$path = $current_site->path;
	} else {
		$domain = $current_site->domain;
		$path = $current_site->path . $slug . '/';
	}

	$blog_id = $wpdb->get_var( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE domain = %s AND path = %s", $domain, $path) );
	wp_cache_set( 'get_id_from_blogname_' . $slug, $blog_id, 'blog-details' );
	return $blog_id;
}

/**
 * Retrieve the details for a blog from the blogs table and blog options.
 *
 * @since MU
 *
 * @global wpdb $wpdb
 *
 * @param int|string|array $fields  Optional. A blog ID, a blog slug, or an array of fields to query against.
 *                                  If not specified the current blog ID is used.
 * @param bool             $get_all Whether to retrieve all details or only the details in the blogs table.
 *                                  Default is true.
 * @return object|false Blog details on success. False on failure.
 */
function get_blog_details( $fields = null, $get_all = true ) {
	global $wpdb;

	if ( is_array($fields ) ) {
		if ( isset($fields['blog_id']) ) {
			$blog_id = $fields['blog_id'];
		} elseif ( isset($fields['domain']) && isset($fields['path']) ) {
			$key = md5( $fields['domain'] . $fields['path'] );
			$blog = wp_cache_get($key, 'blog-lookup');
			if ( false !== $blog )
				return $blog;
			if ( substr( $fields['domain'], 0, 4 ) == 'www.' ) {
				$nowww = substr( $fields['domain'], 4 );
				$blog = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain IN (%s,%s) AND path = %s ORDER BY CHAR_LENGTH(domain) DESC", $nowww, $fields['domain'], $fields['path'] ) );
			} else {
				$blog = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain = %s AND path = %s", $fields['domain'], $fields['path'] ) );
			}
			if ( $blog ) {
				wp_cache_set($blog->blog_id . 'short', $blog, 'blog-details');
				$blog_id = $blog->blog_id;
			} else {
				return false;
			}
		} elseif ( isset($fields['domain']) && is_subdomain_install() ) {
			$key = md5( $fields['domain'] );
			$blog = wp_cache_get($key, 'blog-lookup');
			if ( false !== $blog )
				return $blog;
			if ( substr( $fields['domain'], 0, 4 ) == 'www.' ) {
				$nowww = substr( $fields['domain'], 4 );
				$blog = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain IN (%s,%s) ORDER BY CHAR_LENGTH(domain) DESC", $nowww, $fields['domain'] ) );
			} else {
				$blog = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain = %s", $fields['domain'] ) );
			}
			if ( $blog ) {
				wp_cache_set($blog->blog_id . 'short', $blog, 'blog-details');
				$blog_id = $blog->blog_id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		if ( ! $fields )
			$blog_id = get_current_blog_id();
		elseif ( ! is_numeric( $fields ) )
			$blog_id = get_id_from_blogname( $fields );
		else
			$blog_id = $fields;
	}

	$blog_id = (int) $blog_id;

	$all = $get_all == true ? '' : 'short';
	$details = wp_cache_get( $blog_id . $all, 'blog-details' );

	if ( $details ) {
		if ( ! is_object( $details ) ) {
			if ( $details == -1 ) {
				return false;
			} else {
				// Clear old pre-serialized objects. Cache clients do better with that.
				wp_cache_delete( $blog_id . $all, 'blog-details' );
				unset($details);
			}
		} else {
			return $details;
		}
	}

	// Try the other cache.
	if ( $get_all ) {
		$details = wp_cache_get( $blog_id . 'short', 'blog-details' );
	} else {
		$details = wp_cache_get( $blog_id, 'blog-details' );
		// If short was requested and full cache is set, we can return.
		if ( $details ) {
			if ( ! is_object( $details ) ) {
				if ( $details == -1 ) {
					return false;
				} else {
					// Clear old pre-serialized objects. Cache clients do better with that.
					wp_cache_delete( $blog_id, 'blog-details' );
					unset($details);
				}
			} else {
				return $details;
			}
		}
	}

	if ( empty($details) ) {
		$details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE blog_id = %d /* get_blog_details */", $blog_id ) );
		if ( ! $details ) {
			// Set the full cache.
			wp_cache_set( $blog_id, -1, 'blog-details' );
			return false;
		}
	}

	if ( ! $get_all ) {
		wp_cache_set( $blog_id . $all, $details, 'blog-details' );
		return $details;
	}

	switch_to_blog( $blog_id );
	$details->blogname		= get_option( 'blogname' );
	$details->siteurl		= get_option( 'siteurl' );
	$details->post_count	= get_option( 'post_count' );
	restore_current_blog();

	/**
	 * Filter a blog's details.
	 *
	 * @since MU
	 *
	 * @param object $details The blog details.
	 */
	$details = apply_filters( 'blog_details', $details );

	wp_cache_set( $blog_id . $all, $details, 'blog-details' );

	$key = md5( $details->domain . $details->path );
	wp_cache_set( $key, $details, 'blog-lookup' );

	return $details;
}

/**
 * Clear the blog details cache.
 *
 * @since MU
 *
 * @param int $blog_id Optional. Blog ID. Defaults to current blog.
 */
function refresh_blog_details( $blog_id = 0 ) {
	$blog_id = (int) $blog_id;
	if ( ! $blog_id ) {
		$blog_id = get_current_blog_id();
	}

	$details = get_blog_details( $blog_id, false );
	if ( ! $details ) {
		// Make sure clean_blog_cache() gets the blog ID
		// when the blog has been previously cached as
		// non-existent.
		$details = (object) array(
			'blog_id' => $blog_id,
			'domain' => null,
			'path' => null
		);
	}

	clean_blog_cache( $details );

	/**
	 * Fires after the blog details cache is cleared.
	 *
	 * @since 3.4.0
	 *
	 * @param int $blog_id Blog ID.
	 */
	do_action( 'refresh_blog_details', $blog_id );
}

/**
 * Update the details for a blog. Updates the blogs table for a given blog id.
 *
 * @since MU
 *
 * @global wpdb $wpdb
 *
 * @param int   $blog_id Blog ID
 * @param array $details Array of details keyed by blogs table field names.
 * @return bool True if update succeeds, false otherwise.
 */
function update_blog_details( $blog_id, $details = array() ) {
	global $wpdb;

	if ( empty($details) )
		return false;

	if ( is_object($details) )
		$details = get_object_vars($details);

	$current_details = get_blog_details($blog_id, false);
	if ( empty($current_details) )
		return false;

	$current_details = get_object_vars($current_details);

	$details = array_merge($current_details, $details);
	$details['last_updated'] = current_time('mysql', true);

	$update_details = array();
	$fields = array( 'site_id', 'domain', 'path', 'registered', 'last_updated', 'public', 'archived', 'mature', 'spam', 'deleted', 'lang_id');
	foreach ( array_intersect( array_keys( $details ), $fields ) as $field ) {
		if ( 'path' === $field ) {
			$details[ $field ] = trailingslashit( '/' . trim( $details[ $field ], '/' ) );
		}

		$update_details[ $field ] = $details[ $field ];
	}

	$result = $wpdb->update( $wpdb->blogs, $update_details, array('blog_id' => $blog_id) );

	if ( false === $result )
		return false;

	// If spam status changed, issue actions.
	if ( $details['spam'] != $current_details['spam'] ) {
		if ( $details['spam'] == 1 ) {
			/**
			 * Fires when the blog status is changed to 'spam'.
			 *
			 * @since MU
			 *
			 * @param int $blog_id Blog ID.
			 */
			do_action( 'make_spam_blog', $blog_id );
		} else {
			/**
			 * Fires when the blog status is changed to 'ham'.
			 *
			 * @since MU
			 *
			 * @param int $b