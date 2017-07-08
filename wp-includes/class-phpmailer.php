s the checksums for the given version of WordPress.
 *
 * @since 3.7.0
 *
 * @param string $version Version string to query.
 * @param string $locale  Locale to query.
 * @return bool|array False on failure. An array of checksums on success.
 */
function get_core_checksums( $version, $locale ) {
	$url = $http_url = 'http://api.wordpress.org/core/checksums/1.0/?' . http_build_query( compact( 'version', 'locale' ), null, '&' );

	if ( $ssl = wp_http_supports( array( 'ssl' ) ) )
		$url = set_url_scheme( $url, 'https' );

	$options = array(
		'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
	);

	$response = wp_remote_get( $url, $options );
	if ( $ssl && is_wp_error( $response ) ) {
		trigger_error( __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ), headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE );
		$response = wp_remote_get( $http_url, $options );
	}

	if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) )
		return false;

	$body = trim( wp_remote_retrieve_body( $response ) );
	$body = json_decode( $body, true );

	if ( ! is_array( $body ) || ! isset( $body['checksums'] ) || ! is_array( $body['checksums'] ) )
		return false;

	return $body['checksums'];
}

/**
 *
 * @param object $update
 * @return bool
 */
function dismiss_core_update( $update ) {
	$dismissed = get_site_option( 'dismissed_update_core' );
	$dismissed[ $update->current . '|' . $update->locale ] = true;
	return update_site_option( 'dismissed_update_core', $dismissed );
}

/**
 *
 * @param string $version
 * @param string $locale
 * @return bool
 */
function undismiss_core_update( $version, $locale ) {
	$dismissed = get_site_option( 'dismissed_update_core' );
	$key = $version . '|' . $locale;

	if ( ! isset( $dismissed[$key] ) )
		return false;

	unset( $dismissed[$key] );
	return update_site_option( 'dismissed_update_core', $dismissed );
}

/**
 *
 * @param string $version
 * @param string $locale
 * @return object|false
 */
function find_core_update( $version, $locale ) {
	$from_api = get_site_transient( 'update_core' );

	if ( ! isset( $from_api->updates ) || ! is_array( $from_api->updates ) )
		return false;

	$updates = $from_api->updates;
	foreach ( $updates as $update ) {
		if ( $update->current == $version && $update->locale == $locale )
			return $update;
	}
	return false;
}

/**
 *
 * @param string $msg
 * @return string
 */
function core_update_footer( $msg = '' ) {
	if ( !current_user_can('update_core') )
		return sprintf( __( 'Version %s' ), get_bloginfo( 'version', 'display' ) );

	$cur = get_preferred_from_update_core();
	if ( ! is_object( $cur ) )
		$cur = new stdClass;

	if ( ! isset( $cur->current ) )
		$cur->current = '';

	if ( ! isset( $cur->url ) )
		$cur->url = '';

	if ( ! isset( $cur->response ) )
		$cur->response = '';

	switch ( $cur->response ) {
	case 'development' :
		return sprintf( __( 'You are using a development version (%1$s). Cool! Please <a href="%2$s">stay updated</a>.' ), get_bloginfo( 'version', 'display' ), network_admin_url( 'update-core.php' ) );

	case 'upgrade' :
		return '<strong><a href="' . network_admin_url( 'update-core.php' ) . '">' . sprintf( __( 'Get Version %s' ), $cur->current ) . '</a></strong>';

	case 'latest' :
	default :
		return sprintf( __( 'Version %s' ), get_bloginfo( 'version', 'display' ) );
	}
}

/**
 *
 * @global string $pagenow
 * @return false|void
 */
function update_nag() {
	if ( is_multisite() && !current_user_can('update_core') )
		return false;

	global $pagenow;

	if ( 'update-core.php' == $pagenow )
		return;

	$cur = get_preferred_from_update_core();

	if ( ! isset( $cur->response ) || $cur->response != 'upgrade' )
		return false;

	if ( current_user_can('update_core') ) {
		$msg = sprintf( __( '<a href="https://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> is available! <a href="%2$s" aria-label="Please update WordPress now">Please update now</a>.' ), $cur->current, network_admin_url( 'update-core.php' ) );
	} else {
		$msg = sprintf( __('<a href="https://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> is available! Please notify the site administrator.'), $cur->current );
	}
	echo "<div class='update-nag'>$msg</div>";
}

// Called directly from dashboard
function update_right_now_message() {
	$theme_name = wp_get_theme();
	if ( current_user_can( 'switch_themes' ) ) {
		$theme_name = sprintf( '<a href="themes.php">%1$s</a>', $theme_name );
	}

	$msg = '';

	if ( current_user_can('update_core') ) {
		$cur = get_preferred_from_update_core();

		if ( isset( $cur->response ) && $cur->response == 'upgrade' )
			$msg .= '<a href="' . network_admin_url( 'update-core.php' ) . '" class="button" aria-describedby="wp-version">' . sprintf( __( 'Update to %s' ), $cur->current ? $cur->current : __( 'Latest' ) ) . '</a> ';
	}

	/* translators: 1: version number, 2: theme name */
	$content = __( 'WordPress %1$s running %2$s theme.' );

	/**
	 * Filter the text displayed in the 'At a Glance' dashboard widget.
	 *
	 * Prior to 3.8.0, the widget was named 'Right Now'.
	 *
	 * @since 4.4.0
	 *
	 * @param string $content Default text.
	 */
	$content = apply_filters( 'update_right_now_text', $content );

	$msg .= sprintf( '<span id="wp-version">' . $content . '</span>', get_bloginfo( 'version', 'display' ), $theme_name );

	echo "<p id='wp-version-message'>$msg</p>";
}

/**
 * @since 2.9.0
 *
 * @return array
 */
function get_plugin_updates() {
	$all_plugins = get_plugins();
	$upgrade_plugins = array();
	$current = get_site_transient( 'update_plugins' );
	foreach ( (array)$all_plugins as $plugin_file => $plugin_data) {
		if ( isset( $current->response[ $plugin_file ] ) ) {
			$upgrade_plugins[ $plugin_file ] = (object) $plugin_data;
			$upgrade_plugins[ $plugin_file ]->update = $current->response[ $plugin_file ];
		}
	}

	return $upgrade_plugins;
}

/**
 * @since 2.9.0
 */
function wp_plugin_update_rows() {
	if ( !current_user_can('update_plugins' ) )
		return;

	$plugins = get_site_transient( 'update_plugins' );
	if ( isset($plugins->response) && is_array($plugins->response) ) {
		$plugins = array_keys( $plugins->response );
		foreach ( $plugins as $plugin_file ) {
			add_action( "after_plugin_row_$plugin_file", 'wp_plugin_update_row', 10, 2 );
		}
	}
}

/**
 *
 * @param string $file
 * @param array  $plugin_data
 * @return false|void
 */
function wp_plugin_update_row( $file, $plugin_data ) {
	$current = get_site_transient( 'update_plugins' );
	if ( !isset( $current->response[ $file ] ) )
		return false;

	$r = $current->response[ $file ];

	$plugins_allowedtags = array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array());
	$plugin_name = wp_kses( $plugin_data['Name'], $plugins_allowedtags );

	$details_url = self_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $r->slug . '&section=changelog&TB_iframe=true&width=600&height=800');

	$wp_list_table = _get_list_table('WP_Plugins_List_Table');

	if ( is_network_admin() || !is_multisite() ) {
		if ( is_network_admin() ) {
			$active_class = is_plugin_active_for_network( $file ) ? ' active': '';
		} else {
			$active_class = is_plugin_active( $file ) ? ' active' : '';
		}

		echo '<tr class="plugin-update-tr' . $active_class . '" id="' . esc_attr( $r->slug . '-update' ) . '" data-slug="' . esc_attr( $r->slug ) . '" data-plugin="' . esc_attr( $file ) . '"><td colspan="' . esc_attr( $wp_list_table->get_column_count() ) . '" class="plugin-update colspanchange"><div class="update-message">';

		if ( ! current_user_can( 'update_plugins' ) ) {
			/* translators: 1: plugin name, 2: details URL, 3: accessibility text, 4: version number */
			printf( __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox open-plugin-details-modal" aria-label="%3$s">View version %4$s details</a>.' ),
				$plugin_name,
				esc_url( $details_url ),
				/* translators: 1: plugin name, 2: version number */
				esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $plugin_name, $r->new_version ) ),
				$r->new_version
			);
		} elseif ( empty( $r->package ) ) {
			/* translators: 1: plugin name, 2: details URL, 3: accessibility text, 4: version number */
			printf( __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox open-plugin-details-modal" aria-label="%3$s">View version %4$s details</a>. <em>Automatic update is unavailable for this plugin.</em>' ),
				$plugin_name,
				esc_url( $details_url ),
				/* translators: 1: plugin name, 2: version number */
				esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $plugin_name, $r->new_version ) ),
				$r->new_version
			);
		} else {
			/* translators: 1: plugin name, 2: details URL, 3: accessibility text, 4: version number, 5: update URL, 6: accessibility text */
			printf( __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox open-plugin-details-modal" aria-label="%3$s">View version %4$s details</a> or <a href="%5$s" class="update-link" aria-label="%6$s">update now</a>.' ),
				$plugin_name,
				esc_url( $details_url ),
				/* translators: 1: plugin name, 2: version number */
				esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $plugin_name, $r->new_version ) ),
				$r->new_version,
				wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file, 'upgrade-plugin_' . $file ),
				/* translators: %s: plugin name */
				esc_attr( sprintf( __( 'Update %s now' ), $plugin_name ) )
			);
		}
		/**
		 * Fires at the end of the update message container in each
		 * row of the plugins list table.
		 *
		 * The dynamic portion of the hook name, `$file`, refers to the path
		 * of the plugin's primary file relative to the plugins directory.
		 *
		 * @since 2.8.0
		 *
		 * @param array $plugin_data {
		 *     An array of plugin metadata.
		 *
		 *     @type string $name         The human-readable name of the plugin.
		 *     @type string $plugin_uri   Plugin URI.
		 *     @type string $version      Plugin version.
		 *     @type string $description  Plugin description.
		 *     @type string $author       Plugin author.
		 *     @type string $author_uri   Plugin author URI.
		 *     @type string $text_domain  Plugin text domain.
		 *     @type string $domain_path  Relative path to the plugin's .mo file(s).
		 *     @type bool   $network      Whether the plugin can only be activated network wide.
		 *     @type string $title        The human-readable title of the plugin.
		 *     @type string $author_name  Plugin author's name.
		 *     @type bool   $update       Whether there's an available update. Default null.
	 	 * }
	 	 * @param array $r {
	 	 *     An array of metadata about the available plugin update.
	 	 *
	 	 *     @type int    $id           Plugin ID.
	 	 *     @type string $slug         Plugin slug.
	 	 *     @type string $new_version  New plugin version.
	 	 *     @type string $url          Plugin URL.
	 	 *     @type string $package      Plugin update package URL.
	 	 * }
		 */
		do_action( "in_plugin_update_message-{$file}", $plugin_data, $r );

		echo '</div></td></tr>';
	}
}

/**
 *
 * @return array
 */
function get_theme_updates() {
	$current = get_site_transient('update_themes');

	if ( ! isset( $current->response ) )
		return array();

	$update_themes = array();
	foreach ( $current->response as $stylesheet => $data ) {
		$update_themes[ $stylesheet ] = wp_get_theme( $stylesheet );
		$update_themes[ $stylesheet ]->update = $data;
	}

	return $update_themes;
}

/**
 * @since 3.1.0
 */
function wp_theme_update_rows() {
	if ( !current_user_can('update_themes' ) )
		return;

	$themes = get_site_transient( 'update_themes' );
	if ( isset($themes->response) && is_array($themes->response) ) {
		$themes = array_keys( $themes->response );

		foreach ( $themes as $theme ) {
			add_action( "after_theme_row_$theme", 'wp_theme_update_row', 10, 2 );
		}
	}
}

/**
 *
 * @param string   $theme_key
 * @param WP_Theme $theme
 * @return false|void
 */
function wp_theme_update_row( $theme_key, $theme ) {
	$current = get_site_transient( 'update_themes' );
	if ( !isset( $current->response[ $theme_key ] ) )
		return false;

	$r = $current->response[ $theme_key ];

	$theme_name = $theme['Name'];

	$details_url = add_query_arg( array( 'TB_iframe' => 'true', 'width' => 1024, 'height' => 800 ), $current->response[ $theme_key ]['url'] );

	$wp_list_table = _get_list_table('WP_MS_Themes_List_Table');

	$active = $theme->is_allowed( 'network' ) ? ' active': '';

	echo '<tr class="plugin-update-tr' . $active . '" id="' . esc_attr( $theme->get_stylesheet() . '-update' ) . '" data-slug="' . esc_attr( $theme->get_stylesheet() ) . '"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message">';
	if ( ! current_user_can('update_themes') ) {
		/* translators: 1: theme name, 2: details URL, 3: accessibility text, 4: version number */
		printf( __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox open-plugin-details-modal" aria-label="%3$s">View version %4$s details</a>.'),
			$theme_name,
			esc_url( $details_url ),
			/* translators: 1: theme name, 2: version number */
			esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $theme_name, $r['new_version'] ) ),
			$r['new_version']
		);
	} elseif ( empty( $r['package'] ) ) {
		/* translators: 1: theme name, 2: details URL, 3: accessibility text, 4: version number */
		printf( __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox open-plugin-details-modal" aria-label="%3$s">View version %4$s details</a>. <em>Automatic update is unavailable for this theme.</em>' ),
			$theme_name,
			esc_url( $details_url ),
			/* translators: 1: theme name, 2: version number */
			esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $theme_name, $r['new_version'] ) ),
			$r['new_version']
		);
	} else {
		/* translators: 1: theme name, 2: details URL, 3: accessibility text, 4: version number, 5: update URL, 6: accessibility text */
		printf( __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox open-plugin-details-modal" aria-label="%3$s">View version %4$s details</a> or <a href="%5$s" class="update-link" aria-label="%6$s">update now</a>.' ),
			$theme_name,
			esc_url( $details_url ),
			/* translators: 1: theme name, 2: version number */
			esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $theme_name, $r['new_version'] ) ),
			$r['new_version'],
			wp_nonce_url( self_admin_url( 'update.php?action=upgrade-theme&theme=' ) . $theme_key, 'upgrade-theme_' . $theme_key ),
			/* translators: %s: theme name */
			esc_attr( sprintf( __( 'Update %s now' ), $theme_name ) )
		);
	}
	/**
	 * Fires at the end of the update message container in each
	 * row of the themes list table.
	 *
	 * The dynamic portion of the hook name, `$theme_key`, refers to
	 * the theme slug as found in the WordPress.org themes repository.
	 *
	 * @since 3.1.0
	 *
	 * @param WP_Theme $theme The WP_Theme object.
	 * @param array    $r {
	 *     An array of metadata about the available theme update.
	 *
	 *     @type string $new_version New theme version.
	 *     @type string $url         Theme URL.
	 *     @type string $package     Theme update package URL.
	 * }
	 */
	do_action( "in_theme_update_message-{$theme_key}", $theme, $r );

	echo '</div></td></tr>';
}

/**
 *
 * @global int $upgrading
 * @return false|void
 */
function maintenance_nag() {
	include( ABSPATH . WPINC . '/version.php' ); // include an unmodified $wp_version
	global $upgrading;
	$nag = isset( $upgrading );
	if ( ! $nag ) {
		$failed = get_site_option( 'auto_core_update_failed' );
		/*
		 * If an update failed critically, we may have copied over version.php but not other files.
		 * In that case, if the install claims we're running the version we attempted, nag.
		 * This is serious enough to err on the side of nagging.
		 *
		 * If we simply failed to update before we tried to copy any files, then assume things are
		 * OK if they are now running the latest.
		 *
		 * This flag is cleared whenever a successful update occurs using Core_Upgrader.
		 */
		$comparison = ! empty( $failed['critical'] ) ? '>=' : '>';
		if ( version_compare( $failed['attempted'], $wp_version, $comparison ) )
			$nag = true;
	}

	if ( ! $nag )
		return false;

	if ( current_user_can('update_core') )
		$msg = sprintf( __('An automated WordPress update has failed to complete - <a href="%s">please attempt the update again now</a>.'), 'update-core.php' );
	else
		$msg = __('An automated WordPress update has failed to complete! Please notify the site administrator.');

	echo "<div class='update-nag'>$msg</div>";
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php
/**
 * WordPress Theme Administration API
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Remove a theme
 *
 * @since 2.8.0
 *
 * @global WP_Filesystem_Base $wp_filesystem Subclass
 *
 * @param string $stylesheet Stylesheet of the theme to delete
 * @param string $redirect Redirect to page when complete.
 * @return void|bool|WP_Error When void, echoes content.
 */
function delete_theme($stylesheet, $redirect = '') {
	global $wp_filesystem;

	if ( empty($stylesheet) )
		return false;

	ob_start();
	if ( empty( $redirect ) )
		$redirect = wp_nonce_url('themes.php?action=delete&stylesheet=' . urlencode( $stylesheet ), 'delete-theme_' . $stylesheet);
	if ( false === ($credentials = request_filesystem_credentials($redirect)) ) {
		$data = ob_get_clean();

		if ( ! empty($data) ){
			include_once( ABSPATH . 'wp-admin/admin-header.php');
			echo $data;
			include( ABSPATH . 'wp-admin/admin-footer.php');
			exit;
		}
		return;
	}

	if ( ! WP_Filesystem($credentials) ) {
		request_filesystem_credentials($redirect, '', true); // Failed to connect, Error and request again
		$data = ob_get_clean();

		if ( ! empty($data) ) {
			include_once( ABSPATH . 'wp-admin/admin-header.php');
			echo $data;
			include( ABSPATH . 'wp-admin/admin-footer.php');
			exit;
		}
		return;
	}

	if ( ! is_object($wp_filesystem) )
		return new WP_Error('fs_unavailable', __('Could not access filesystem.'));

	if ( is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->get_error_code() )
		return new WP_Error('fs_error', __('Filesystem error.'), $wp_filesystem->errors);

	// Get the base plugin folder.
	$themes_dir = $wp_filesystem->wp_themes_dir();
	if ( empty( $themes_dir ) ) {
		return new WP_Error( 'fs_no_themes_dir', __( 'Unable to locate WordPress theme directory.' ) );
	}

	$themes_dir = trailingslashit( $themes_dir );
	$theme_dir = trailingslashit( $themes_dir . $stylesheet );
	$deleted = $wp_filesystem->delete( $theme_dir, true );

	if ( ! $deleted ) {
		return new WP_Error( 'could_not_remove_theme', sprintf( __( 'Could not fully remove the theme %s.' ), $stylesheet ) );
	}

	$theme_translations = wp_get_installed_translations( 'themes' );

	// Remove language files, silently.
	if ( ! empty( $theme_translations[ $stylesheet ] ) ) {
		$translations = $theme_translations[ $stylesheet ];

		foreach ( $translations as $translation => $data ) {
			$wp_filesystem->delete( WP_LANG_DIR . '/themes/' . $stylesheet . '-' . $translation . '.po' );
			$wp_filesystem->delete( WP_LANG_DIR . '/themes/' . $stylesheet . '-' . $translation . '.mo' );
		}
	}

	// Force refresh of theme update information.
	delete_site_transient( 'update_themes' );

	return true;
}

/**
 * Get the Page Templates available in this theme
 *
 * @since 1.5.0
 *
 * @param WP_Post|null $post Optional. The post being edited, provided for context.
 * @return array Key is the template name, value is the filename of the template
 */
function get_page_templates( $post = null ) {
	return array_flip( wp_get_theme()->get_page_templates( $post ) );
}

/**
 * Tidies a filename for url display by the theme editor.
 *
 * @since 2.9.0
 * @access private
 *
 * @param string $fullpath Full path to the theme file
 * @param string $containingfolder Path of the theme parent folder
 * @return string
 */
function _get_template_edit_filename($fullpath, $containingfolder) {
	return str_replace(dirname(dirname( $containingfolder )) , '', $fullpath);
}

/**
 * Check if there is an update for a theme available.
 *
 * Will display link, if there is an update available.
 *
 * @since 2.7.0
 * @see get_theme_update_available()
 *
 * @param WP_Theme $theme Theme data object.
 */
function theme_update_available( $theme ) {
	echo get_theme_update_available( $theme );
}

/**
 * Retrieve the update link if there is a theme update available.
 *
 * Will return a link if there is an update available.
 *
 * @since 3.8.0
 *
 * @staticvar object $themes_update
 *
 * @param WP_Theme $theme WP_Theme object.
 * @return false|string HTML for the update link, or false if invalid info was passed.
 */
function get_theme_update_available( $theme ) {
	static $themes_update = null;

	if ( !current_user_can('update_themes' ) )
		return false;

	if ( !isset($themes_update) )
		$themes_update = get_site_transient('update_themes');

	if ( ! ( $theme instanceof WP_Theme ) ) {
		return false;
	}

	$stylesheet = $theme->get_stylesheet();

	$html = '';

	if ( isset($themes_update->response[ $stylesheet ]) ) {
		$update = $themes_update->response[ $stylesheet ];
		$theme_name = $theme->display('Name');
		$details_url = add_query_arg(array('TB_iframe' => 'true', 'width' => 1024, 'height' => 800), $update['url']); //Theme browser inside WP? replace this, Also, theme preview JS will override this on the available list.
		$update_url = wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $stylesheet ) ), 'upgrade-theme_' . $stylesheet );

		if ( !is_multisite() ) {
			if ( ! current_user_can('update_themes') ) {
				/* translators: 1: theme name, 2: theme details URL, 3: accessibility text, 4: version number */
				$html = sprintf( '<p><strong>' . __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox" aria-label="%3$s">View version %4$s details</a>.' ) . '</strong></p>',
					$theme_name,
					esc_url( $details_url ),
					/* translators: 1: theme name, 2: version number */
					esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $theme_name, $update['new_version'] ) ),
					$update['new_version']
				);
			} elseif ( empty( $update['package'] ) ) {
				/* translators: 1: theme name, 2: theme details URL, 3: accessibility text, 4: version number */
				$html = sprintf( '<p><strong>' . __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox" aria-label="%3$s">View version %4$s details</a>. <em>Automatic update is unavailable for this theme.</em>' ) . '</strong></p>',
					$theme_name,
					esc_url( $details_url ),
					/* translators: 1: theme name, 2: version number */
					esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $theme_name, $update['new_version'] ) ),
					$update['new_version']
				);
			} else {
				/* translators: 1: theme name, 2: theme details URL, 3: accessibility text, 4: version number, 5: update URL, 6: accessibility text */
				$html = sprintf( '<p><strong>' . __( 'There is a new version of %1$s available. <a href="%2$s" class="thickbox" aria-label="%3$s">View version %4$s details</a> or <a href="%5$s" aria-label="%6$s">update now</a>.' ) . '</strong></p>',
					$theme_name,
					esc_url( $details_url ),
					/* translators: 1: theme name, 2: version number */
					esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $theme_name, $update['new_version'] ) ),
					$update['new_version'],
					$update_url,
					/* translators: %s: theme name */
					esc_attr( sprintf( __( 'Update %s now' ), $theme_name ) )
				);
			}
		}
	}

	return $html;
}

/**
 * Retrieve list of WordPress theme features (aka theme tags)
 *
 * @since 3.1.0
 *
 * @param bool $api Optional. Whether try to fetch tags from the WordPress.org API. Defaults to true.
 * @return array Array of features keyed by category with translations keyed by slug.
 */
function get_theme_feature_list( $api = true ) {
	// Hard-coded list is used if api not accessible.
	$features = array(
			__( 'Colors' ) => array(
				'black'   => __( 'Black' ),
				'blue'    => __( 'Blue' ),
				'brown'   => __( 'Brown' ),
				'gray'    => __( 'Gray' ),
				'green'   => __( 'Green' ),
				'orange'  => __( 'Orange' ),
				'pink'    => __( 'Pink' ),
				'purple'  => __( 'Purple' ),
				'red'     => __( 'Red' ),
				'silver'  => __( 'Silver' ),
				'tan'     => __( 'Tan' ),
				'white'   => __( 'White' ),
				'yellow'  => __( 'Yellow' ),
				'dark'    => __( 'Dark' ),
				'light'   => __( 'Light' ),
			),

		__( 'Layout' ) => array(
			'fixed-layout'      => __( 'Fixed Layout' ),
			'fluid-layout'      => __( 'Fluid Layout' ),
			'responsive-layout' => __( 'Responsive Layout' ),
			'one-column'    => __( 'One Column' ),
			'two-columns'   => __( 'Two Columns' ),
			'three-columns' => __( 'Three Columns' ),
			'four-columns'  => __( 'Four Columns' ),
			'left-sidebar'  => __( 'Left Sidebar' ),
			'right-sidebar' => __( 'Right Sidebar' ),
		),

		__( 'Features' ) => array(
			'accessibility-ready'   => __( 'Accessibility Ready' ),
			'blavatar'              => __( 'Blavatar' ),
			'buddypress'            => __( 'BuddyPress' ),
			'custom-background'     => __( 'Custom Background' ),
			'custom-colors'         => __( 'Custom Colors' ),
			'custom-header'         => __( 'Custom Header' ),
			'custom-menu'           => __( 'Custom Menu' ),
			'editor-style'          => __( 'Editor Style' ),
			'featured-image-header' => __( 'Featured Image Header' ),
			'featured-images'       => __( 'Featured Images' ),
			'flexible-header'       => __( 'Flexible Header' ),
			'front-page-post-form'  => __( 'Front Page Posting' ),
			'full-width-template'   => __( 'Full Width Template' ),
			'microformats'          => __( 'Microformats' ),
			'post-formats'          => __( 'Post Formats' ),
			'rtl-language-support'  => __( 'RTL Language Support' ),
			'sticky-post'           => __( 'Sticky Post' ),
			'theme-options'         => __( 'Theme Options' ),
			'threaded-comments'     => __( 'Threaded Comments' ),
			'translation-ready'     => __( 'Translation Ready' ),
		),

		__( 'Subject' )  => array(
			'holiday'       => __( 'Holiday' ),
			'photoblogging' => __( 'Photoblogging' ),
			'seasonal'      => __( 'Seasonal' ),
		)
	);

	if ( ! $api || ! current_user_can( 'install_themes' ) )
		return $features;

	if ( !$feature_list = get_site_transient( 'wporg_theme_feature_list' ) )
		set_site_transient( 'wporg_theme_feature_list', array(), 3 * HOUR_IN_SECONDS );

	if ( !$feature_list ) {
		$feature_list = themes_api( 'feature_list', array() );
		if ( is_wp_error( $feature_list ) )
			return $features;
	}

	if ( !$feature_list )
		return $features;

	set_site_transient( 'wporg_theme_feature_list', $feature_list, 3 * HOUR_IN_SECONDS );

	$category_translations = array(
		'Colors'   => __( 'Colors' ),
		'Layout'   => __( 'Layout' ),
		'Features' => __( 'Features' ),
		'Subject'  => __( 'Subject' )
	);

	// Loop over the wporg canonical list and apply translations
	$wporg_features = array();
	foreach ( (array) $feature_list as $feature_category => $feature_items ) {
		if ( isset($category_translations[$feature_category]) )
			$feature_category = $category_translations[$feature_category];
		$wporg_features[$feature_category] = array();

		foreach ( $feature_items as $feature ) {
			if ( isset($features[$feature_category][$feature]) )
				$wporg_features[$feature_category][$feature] = $features[$feature_category][$feature];
			else
				$wporg_features[$feature_category][$feature] = $feature;
		}
	}

	return $wporg_features;
}

/**
 * Retrieves theme installer pages from the WordPress.org Themes API.
 *
 * It is possible for a theme to override the Themes API result with three
 * filters. Assume this is for themes, which can extend on the Theme Info to
 * offer more choices. This is very powerful and must be used with care, when
 * overriding the filters.
 *
 * The first filter, {@see 'themes_api_args'}, is for the args and gives the action
 * as the second parameter. The hook for {@see 'themes_api_args'} must ensure that
 * an object is returned.
 *
 * The second filter, {@see 'themes_api'}, allows a plugin to override the WordPress.org
 * Theme API entirely. If `$action` is 'query_themes', 'theme_information', or 'feature_list',
 * an object MUST be passed. If `$action` is 'hot_tags`, an array should be passed.
 *
 * Finally, the third filter, {@see 'themes_api_result'}, makes it possible to filter the
 * response object or array, depending on the `$action` type.
 *
 * Supported arguments per action:
 *
 * | Argument Name      | 'query_themes' | 'theme_information' | 'hot_tags' | 'feature_list'   |
 * | -------------------| :------------: | :-----------------: | :--------: | :--------------: |
 * | `$slug`            | No             |  Yes                | No         | No               |
 * | `$per_page`        | Yes            |  No                 | No         | No               |
 * | `$page`            | Yes            |  No                 | No         | No               |
 * | `$number`          | No             |  No                 | Yes        | No               |
 * | `$search`          | Yes            |  No                 | No         | No               |
 * | `$tag`             | Yes            |  No                 | No         | No               |
 * | `$author`          | Yes            |  No                 | No         | No               |
 * | `$user`            | Yes            |  No                 | No         | No               |
 * | `$browse`          | Yes            |  No                 | No         | No               |
 * | `$locale`          | Yes            |  Yes                | No         | No               |
 * | `$fields`          | Yes            |  Yes                | No         | No               |
 *
 * @since 2.8.0
 *
 * @param string       $action API action to perform: 'query_themes', 'theme_information',
 *                             'hot_tags' or 'feature_list'.
 * @param array|object $args   {
 *     Optional. Array or object of arguments to serialize for the Plugin Info API.
 *
 *     @type string  $slug     The plugin slug. Default empty.
 *     @type int     $per_page Number of themes per page. Default 24.
 *     @type int     $page     Number of current page. Default 1.
 *     @type int     $number   Number of tags to be queried.
 *     @type string  $search   A search term. Default empty.
 *     @type string  $tag      Tag to filter themes. Default empty.
 *     @type string  $author   Username of an author to filter themes. Default empty.
 *     @type string  $user     Username to query for their favorites. Default empty.
 *     @type string  $browse   Browse view: 'featured', 'popular', 'updated', 'favorites'.
 *     @type string  $locale   Locale to provide context-sensitive results. Default is the value of get_locale().
 *     @type array   $fields   {
 *         Array of fields which should or should not be returned.
 *
 *         @type bool $description        Whether to return the theme full description. Default false.
 *         @type bool $sections           Whether to return the theme readme sections: description, installation,
 *                                        FAQ, screenshots, other notes, and changelog. Default false.
 *         @type bool $rating             Whether to return the rating in percent and total number of ratings.
 *                                        Default false.
 *         @type bool $ratings            Whether to return the number of rating for each star (1-5). Default false.
 *         @type bool $downloaded         Whether to return the download count. Default false.
 *         @type bool $downloadlink       Whether to return the download link for the package. Default false.
 *         @type bool $last_updated       Whether to return the date of the last update. Default false.
 *         @type bool $tags               Whether to return the assigned tags. Default false.
 *         @type bool $homepage           Whether to return the theme homepage link. Default false.
 *         @type bool $screenshots        Whether to return the screenshots. Default false.
 *         @type int  $screenshot_count   Number of screenshots to return. Default 1.
 *         @type bool $screenshot_url     Whether to return the URL of the first screenshot. Default false.
 *         @type bool $photon_screenshots Whether to return the screenshots via Photon. Default false.
 *         @type bool $template           Whether to return the slug of the parent theme. Default false.
 *         @type bool $parent             Whether to return the slug, name and homepage of the parent theme. Default false.
 *         @type bool $versions           Whether to return the list of all available versions. Default false.
 *         @type bool $theme_url          Whether to return theme's URL. Default false.
 *         @type bool $extended_author    Whether to return nicename or nicename and display name. Default false.
 *     }
 * }
 * @return object|array|WP_Error Response object or array on success, WP_Error on failure. See the
 *         {@link https://developer.wordpress.org/reference/functions/themes_api/ function reference article}
 *         for more information on the make-up of possible return objects depending on the value of `$action`.
 */
function themes_api( $action, $args = array() ) {

	if ( is_array( $args ) ) {
		$args = (object) $args;
	}

	if ( ! isset( $args->per_page ) ) {
		$args->per_page = 24;
	}

	if ( ! isset( $args->locale ) ) {
		$args->locale = get_locale();
	}

	/**
	 * Filter arguments used to query for installer pages from the WordPress.org Themes API.
	 *
	 * Important: An object MUST be returned to this filter.
	 *
	 * @since 2.8.0
	 *
	 * @param object $args   Arguments used to query for installer pages from the WordPress.org Themes API.
	 * @param string $action Requested action. Likely values are 'theme_information',
	 *                       'feature_list', or 'query_themes'.
	 */
	$args = apply_filters( 'themes_api_args', $args, $action );

	/**
	 * Filter whether to override the WordPress.org Themes API.
	 *
	 * Passing a non-false value will effectively short-circuit the WordPress.org API request.
	 *
	 * If `$action` is 'query_themes', 'theme_information', or 'feature_list', an object MUST
	 * be passed. If `$action` is 'hot_tags`, an array should be passed.
	 *
	 * @since 2.8.0
	 *
	 * @param false|object|array $override Whether to override the WordPress.org Themes API. Default false.
	 * @param string             $action   Requested action. Likely values are 'theme_information',
	 *                                    'feature_list', or 'query_themes'.
	 * @param object             $args     Arguments used to query for installer pages from the Themes API.
	 */
	$res = apply_filters( 'themes_api', false, $action, $args );

	if ( ! $res ) {
		$url = $http_url = 'http://api.wordpress.org/themes/info/1.0/';
		if ( $ssl = wp_http_supports( array( 'ssl' ) ) )
			$url = set_url_scheme( $url, 'https' );

		$http_args = array(
			'body' => array(
				'action' => $action,
				'request' => serialize( $args )
			)
		);
		$request = wp_remote_post( $url, $http_args );

		if ( $ssl && is_wp_error( $request ) ) {
			if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
				trigger_error( __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ), headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE );
			}
			$request = wp_remote_post( $http_url, $http_args );
		}

		if ( is_wp_error($request) ) {
			$res = new WP_Error('themes_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
		} else {
			$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
			if ( ! is_object( $res ) && ! is_array( $res ) )
				$res = new WP_Error('themes_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
		}
	}

	/**
	 * Filter the returned WordPress.org Themes API response.
	 *
	 * @since 2.8.0
	 *
	 * @param array|object $res    WordPress.org Themes API response.
	 * @param string       $action Requested action. Likely values are 'theme_information',
	 *                             'feature_list', or 'query_themes'.
	 * @param object       $args   Arguments used to query for installer pages from the WordPress.org Themes API.
	 */
	return apply_filters( 'themes_api_result', $res, $action, $args );
}

/**
 * Prepare themes for JavaScript.
 *
 * @since 3.8.0
 *
 * @param array $themes Optional. Array of WP_Theme objects to prepare.
 *                      Defaults to all allowed themes.
 *
 * @return array An associative array of theme data, sorted by name.
 */
function wp_prepare_themes_for_js( $themes = null ) {
	$current_theme = get_stylesheet();

	/**
	 * Filter theme data before it is prepared for JavaScript.
	 *
	 * Passing a non-empty array will result in wp_prepare_themes_for_js() returning
	 * early with that value instead.
	 *
	 * @since 4.2.0
	 *
	 * @param array      $prepared_themes An associative array of theme data. Default empty array.
	 * @param null|array $themes          An array of WP_Theme objects to prepare, if any.
	 * @param string     $current_theme   The current theme slug.
	 */
	$prepared_themes = (array) apply_filters( 'pre_prepare_themes_for_js', array(), $themes, $current_theme );

	if ( ! empty( $prepared_themes ) ) {
		return $prepared_themes;
	}

	// Make sure the current theme is listed first.
	$prepared_themes[ $current_theme ] = array();

	if ( null === $themes ) {
		$themes = wp_get_themes( array( 'allowed' => true ) );
		if ( ! isset( $themes[ $current_theme ] ) ) {
			$themes[ $current_theme ] = wp_get_theme();
		}
	}

	$updates = array();
	if ( current_user_can( 'update_themes' ) ) {
		$updates_transient = get_site_transient( 'update_themes' );
		if ( isset( $updates_transient->response ) ) {
			$updates = $updates_transient->response;
		}
	}

	WP_Theme::sort_by_name( $themes );

	$parents = array();

	foreach ( $themes as $theme ) {
		$slug = $theme->get_stylesheet();
		$encoded_slug = urlencode( $slug );

		$parent = false;
		if ( $theme->parent() ) {
			$parent = $theme->parent()->display( 'Name' );
			$parents[ $slug ] = $theme->parent()->get_stylesheet();
		}

		$customize_action = null;
		if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) {
			$customize_action = esc_url( add_query_arg(
				array(
					'return' => urlencode( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ),
				),
				wp_customize_url( $slug )
			) );
		}

		$prepared_themes[ $slug ] = array(
			'id'           => $slug,
			'name'         => $theme->display( 'Name' ),
			'screenshot'   => array( $theme->get_screenshot() ), // @todo multiple
			'description'  => $theme->display( 'Description' ),
			'author'       => $theme->display( 'Author', false, true ),
			'authorAndUri' => $theme->display( 'Author' ),
			'version'      => $theme->display( 'Version' ),
			'tags'         => $theme->display( 'Tags' ),
			'parent'       => $parent,
			'active'       => $slug === $current_theme,
			'hasUpdate'    => isset( $updates[ $slug ] ),
			'update'       => get_theme_update_available( $theme ),
			'actions'      => array(
				'activate' => current_user_can( 'switch_themes' ) ? wp_nonce_url( admin_url( 'themes.php?action=activate&amp;stylesheet=' . $encoded_slug ), 'switch-theme_' . $slug ) : null,
				'customize' => $customize_action,
				'delete'   => current_user_can( 'delete_themes' ) ? wp_nonce_url( admin_url( 'themes.php?action=delete&amp;stylesheet=' . $encoded_slug ), 'delete-theme_' . $slug ) : null,
			),
		);
	}

	// Remove 'delete' action if theme has an active child
	if ( ! empty( $parents ) && array_key_exists( $current_theme, $parents ) ) {
		unset( $prepared_themes[ $parents[ $current_theme ] ]['actions']['delete'] );
	}

	/**
	 * Filter the themes prepared for JavaScript, for themes.php.
	 *
	 * Could be useful for changing the order, which is by name by default.
	 *
	 * @since 3.8.0
	 *
	 * @param array $prepared_themes Array of themes.
	 */
	$prepared_themes = apply_filters( 'wp_prepare_themes_for_js', $prepared_themes );
	$prepared_themes = array_values( $prepared_themes );
	return array_filter( $prepared_themes );
}

/**
 * Print JS templates for the theme-browsing UI in the Customizer.
 *
 * @since 4.2.0
 */
function customize_themes_print_templates() {
	$preview_url = esc_url( add_query_arg( 'theme', '__THEME__' ) ); // Token because esc_url() strips curly braces.
	$preview_url = str_replace( '__THEME__', '{{ data.id }}', $preview_url );
	?>
	<script type="text/html" id="tmpl-customize-themes-details-view">
		<div class="theme-backdrop"></div>
		<div class="theme-wrap wp-clearfix">
			<div class="theme-header">
				<button type="button" class="left dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show previous theme' ); ?></span></button>
				<button type="button" class="right dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show next theme' ); ?></span></button>
				<button type="button" class="close dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Close details dialog' ); ?></span></button>
			</div>
			<div class="theme-about wp-clearfix">
				<div class="theme-screenshots">
				<# if ( data.screenshot[0] ) { #>
					<div class="screenshot"><img src="{{ data.screenshot[0] }}" alt="" /></div>
				<# } else { #>
					<div class="screenshot blank"></div>
				<# } #>
				</div>

				<div class="theme-info">
					<# if ( data.active ) { #>
						<span class="current-label"><?php _e( 'Current Theme' ); ?></span>
					<# } #>
					<h2 class="theme-name">{{{ data.name }}}<span class="theme-version"><?php printf( __( 'Version: %s' ), '{{ data.version }}' ); ?></span></h2>
					<h3 class="theme-author"><?php printf( __( 'By %s' ), '{{{ data.authorAndUri }}}' ); ?></h3>
					<p class="theme-description">{{{ data.description }}}</p>

					<# if ( data.parent ) { #>
						<p class="parent-theme"><?php printf( __( 'This is a child theme of %s.' ), '<strong>{{{ data.parent }}}</strong>' ); ?></p>
					<# } #>

					<# if ( data.tags ) { #>
						<p class="theme-tags"><span><?php _e( 'Tags:' ); ?></span> {{ data.tags }}</p>
					<# } #>
				</div>
			</div>

			<# if ( ! data.active ) { #>
				<div class="theme-actions">
					<div class="inactive-theme">
						<a href="<?php echo $preview_url; ?>" target="_top" class="button button-primary"><?php _e( 'Live Preview' ); ?></a>
					</div>
				</div>
			<# } #>
		</div>
	</script>
	<?php
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php
/**
 * List Table API: WP_Comments_List_Table class
 *
 * @package WordPress
 * @subpackage Administration
 * @since 3.1.0
 */

/**
 * Core class used to implement displaying comments in a list table.
 *
 * @since 3.1.0
 * @access private
 *
 * @see WP_List_Table
 */
class WP_Comments_List_Table extends WP_List_Table {

	public $checkbox = true;

	public $pending_count = array();

	public $extra_items;

	private $user_can;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @global int $post_id
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		global $post_id;

		$post_id = isset( $_REQUEST['p'] ) ? absint( $_REQUEST['p'] ) : 0;

		if ( get_option( 'show_avatars' ) ) {
			add_filter( 'comment_author', array( $this, 'floated_admin_avatar' ), 10, 2 );
		}

		parent::__construct( array(
			'plural' => 'comments',
			'singular' => 'comment',
			'ajax' => true,
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
		) );
	}

	public function floated_admin_avatar( $name, $comment_ID ) {
		$comment = get_comment( $comment_ID );
		$avatar = get_avatar( $comment, 32, 'mystery' );
		return "$avatar $name";
	}

	/**
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can('edit_posts');
	}

	/**
	 *
	 * @global int    $post_id
	 * @global string $comment_status
	 * @global string $search
	 * @global string $comment_type
	 */
	public function prepare_items() {
		global $post_id, $comment_status, $search, $comment_type;

		$comment_status = isset( $_REQUEST['comment_status'] ) ? $_REQUEST['comment_status'] : 'all';
		if ( !in_array( $comment_status, array( 'all', 'moderated', 'approved', 'spam', 'trash' ) ) )
			$comment_status = 'all';

		$comment_type = !empty( $_REQUEST['comment_type'] ) ? $_REQUEST['comment_type'] : '';

		$search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : '';

		$post_type = ( isset( $_REQUEST['post_type'] ) ) ? sanitize_key( $_REQUEST['post_type'] ) : '';

		$user_id = ( isset( $_REQUEST['user_id'] ) ) ? $_REQUEST['user_id'] : '';

		$orderby = ( isset( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : '';
		$order = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : '';

		$comments_per_page = $this->get_per_page( $comment_status );

		$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		if ( isset( $_REQUEST['number'] ) ) {
			$number = (int) $_REQUEST['number'];
		}
		else {
			$number = $comments_per_page + min( 8, $comments_per_page ); // Grab a few extra
		}

		$page = $this->get_pagenum();

		if ( isset( $_REQUEST['start'] ) ) {
			$start = $_REQUEST['start'];
		} else {
			$start = ( $page - 1 ) * $comments_per_page;
		}

		if ( $doing_ajax && isset( $_REQUEST['offset'] ) ) {
			$start += $_REQUEST['offset'];
		}

		$status_map = array(
			'moderated' => 'hold',
			'approved' => 'approve',
			'all' => '',
		);

		$args = array(
			'status' => isset( $status_map[$comment_status] ) ? $status_map[$comment_status] : $comment_status,
			'search' => $search,
			'user_id' => $user_id,
			'offset' => $start,
			'number' => $number,
			'post_id' => $post_id,
			'type' => $comment_type,
			'orderby' => $orderby,
			'order' => $order,
			'post_type' => $post_type,
		);

		$_comments = get_comments( $args );
		if ( is_array( $_comments ) ) {
			update_comment_cache( $_comments );

			$this->items = array_slice( $_comments, 0, $comments_per_page );
			$this->extra_items = array_slice( $_comments, $comments_per_page );

			$_comment_post_ids = array_unique( wp_list_pluck( $_comments, 'comment_post_ID' ) );

			$this->pending_count = get_pending_comments_num( $_comment_post_ids );
		}

		$total_comments = get_comments( array_merge( $args, array(
			'count' => true,
			'offset' => 0,
			'number' => 0
		) ) );

		$this->set_pagination_args( array(
			'total_items' => $total_comments,
			'per_page' => $comments_per_page,
		) );
	}

	/**
	 *
	 * @param string $comment_status
	 * @return int
	 */
	public function get_per_page( $comment_status = 'all' ) {
		$comments_per_page = $this->get_items_per_page( 'edit_comments_per_page' );
		/**
		 * Filter the number of comments listed per page in the comments list table.
		 *
		 * @since 2.6.0
		 *
		 * @param int    $comments_per_page The number of comments to list per page.
		 * @param string $comment_status    The comment status name. Default 'All'.
		 */
		return apply_filters( 'comments_per_page', $comments_per_page, $comment_status );
	}

	/**
	 *
	 * @global string $comment_status
	 */
	public function no_items() {
		global $comment_status;

		if ( 'moderated' === $comment_status ) {
			_e( 'No comments awaiting moderation.' );
		} else {
			_e( 'No comments found.' );
		}
	}

	/**
	 *
	 * @global int $post_id
	 * @global string $comment_status
	 * @global string $comment_type
	 */
	protected function get_views() {
		global $post_id, $comment_status, $comment_type;

		$status_links = array();
		$num_comments = ( $post_id ) ? wp_count_comments( $post_id ) : wp_count_comments();
		//, number_format_i18n($num_comments->moderated) ), "<span class='comment-count'>" . number_format_i18n($num_comments->moderated) . "</span>"),
		//, number_format_i18n($num_comments->spam) ), "<span class='spam-comment-count'>" . number_format_i18n($num_comments->spam) . "</span>")
		$stati = array(
			/* translators: %s: all comments count */
			'all' => _nx_noop(
				'All <span class="count">(%s)</span>',
				'All <span class="count">(%s)</span>',
				'comments'
			), // singular not used

			/* translators: %s: pending comments count */
			'moderated' => _nx_noop(
				'Pending <span class="count">(%s)</span>',
				'Pending <span class="count">(%s)</span>',
				'comments'
			),

			/* translators: %s: approved comments count */
			'approved' => _nx_noop(
				'Approved <span class="count">(%s)</span>',
				'Approved <span class="count">(%s)</span>',
				'comments'
			),

			/* translators: %s: spam comments count */
			'spam' => _nx_noop(
				'Spam <span class="count">(%s)</span>',
				'Spam <span class="count">(%s)</span>',
				'comments'
			),

			/* translators: %s: trashed comments count */
			'trash' => _nx_noop(
				'Trash <span class="count">(%s)</span>',
				'Trash <span class="count">(%s)</span>',
				'comments'
			)
		);

		if ( !EMPTY_TRASH_DAYS )
			unset($stati['trash']);

		$link = admin_url( 'edit-comments.php' );
		if ( !empty($comment_type) && 'all' != $comment_type )
			$link = add_query_arg( 'comment_type', $comment_type, $link );

		foreach ( $stati as $status => $label ) {
			$class = ( $status === $comment_status ) ? ' class="current"' : '';

			if ( !isset( $num_comments->$status ) )
				$num_comments->$status = 10;
			$link = add_query_arg( 'comment_status', $status, $link );
			if ( $post_id )
				$link = add_query_arg( 'p', absint( $post_id ), $link );
			/*
			// I toyed with this, but decided against it. Leaving it in here in case anyone thinks it is a good idea. ~ Mark
			if ( !empty( $_REQUEST['s'] ) )
				$link = add_query_arg( 's', esc_attr( wp_unslash( $_REQUEST['s'] ) ), $link );
			*/
			$status_links[ $status ] = "<a href='$link'$class>" . sprintf(
				translate_nooped_plural( $label, $num_comments->$status ),
				sprintf( '<span class="%s-count">%s</span>',
					( 'moderated' === $status ) ? 'pending' : $status,
					number_format_i18n( $num_comments->$status )
				)
			) . '</a>';
		}

		/**
		 * Filter the comment status links.
		 *
		 * @since 2.5.0
		 *
		 * @param array $status_links An array of fully-formed status links. Default 'All'.
		 *                            Accepts 'All', 'Pending', 'Approved', 'Spam', and 'Trash'.
		 */
		return apply_filters( 'comment_status_links', $status_links );
	}

	/**
	 *
	 * @global string $comment_status
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		global $comment_status;

		$actions = array();
		if ( in_array( $comment_status, array( 'all', 'approved' ) ) )
			$actions['unapprove'] = __( 'Unapprove' );
		if ( in_array( $comment_status, array( 'all', 'moderated' ) ) )
			$actions['approve'] = __( 'Approve' );
		if ( in_array( $comment_status, array( 'all', 'moderated', 'approved', 'trash' ) ) )
			$actions['spam'] = _x( 'Mark as Spam', 'comment' );

		if ( 'trash' === $comment_status ) {
			$actions['untrash'] = __( 'Restore' );
		} elseif ( 'spam' === $comment_status ) {
			$actions['unspam'] = _x( 'Not Spam', 'comment' );
		}

		if ( in_array( $comment_status, array( 'trash', 'spam' ) ) || !EMPTY_TRASH_DAYS )
			$actions['delete'] = __( 'Delete Permanently' );
		else
			$actions['trash'] = __( 'Move to Trash' );

		return $actions;
	}

	/**
	 *
	 * @global string $comment_status
	 * @global string $comment_type
	 *
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {
		global $comment_status, $comment_type;
?>
		<div class="alignleft actions">
<?php
		if ( 'top' === $which ) {
?>
			<label class="screen-reader-text" for="filter-by-comment-type"><?php _e( 'Filter by comment type' ); ?></label>
			<select id="filter-by-comment-type" name="comment_type">
				<option value=""><?php _e( 'All comment types' ); ?></option>
<?php
				/**
				 * Filter the comment types dropdown menu.
				 *
				 * @since 2.7.0
				 *
				 * @param array $comment_types An array of comment types. Accepts 'Comments', 'Pings'.
				 */
				$comment_types = apply_filters( 'admin_comment_types_dropdown', array(
					'comment' => __( 'Comments' ),
					'pings' => __( 'Pings' ),
				) );

				foreach ( $comment_types as $type => $label )
					echo "\t" . '<option value="' . esc_attr( $type ) . '"' . selected( $comment_type, $type, false ) . ">$label</option>\n";
			?>
			</select>
<?php
			/**
			 * Fires just before the Filter submit button for comment types.
			 *
			 * @since 3.5.0
			 */
			do_action( 'restrict_manage_comments' );
			submit_button( __( 'Filter' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
		}

		if ( ( 'spam' === $comment_status || 'trash' === $comment_status ) && current_user_can( 'moderate_comments' ) ) {
			wp_nonce_field( 'bulk-destroy', '_destroy_nonce' );
			$title = ( 'spam' === $comment_status ) ? esc_attr__( 'Empty Spam' ) : esc_attr__( 'Empty Trash' );
			submit_button( $title, 'apply', 'delete_all', false );
		}
		/**
		 * Fires after the Filter submit button for comment types.
		 *
		 * @since 2.5.0
		 *
		 * @param string $comment_status The comment status name. Default 'All'.
		 */
		do_action( 'manage_comments_nav', $comment_status );
		echo '</div>';
	}

	/**
	 * @return string|false
	 */
	public function current_action() {
		if ( isset( $_REQUEST['delete_all'] ) || isset( $_REQUEST['delete_all2'] ) )
			return 'delete_all';

		return parent::current_action();
	}

	/**
	 *
	 * @global int $post_id
	 *
	 * @return array
	 */
	public function get_columns() {
		global $post_id;

		$columns = array();

		if ( $this->checkbox )
			$columns['cb'] = '<input type="checkbox" />';

		$columns['author'] = __( 'Author' );
		$columns['comment'] = _x( 'Comment', 'column name' );

		if ( ! $post_id ) {
			/* translators: column name or table row header */
			$columns['response'] = __( 'In Response To' );
		}

		$columns['date'] = _x( 'Submitted On', 'column name' );

		return $columns;
	}

	/**
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'author'   => 'comment_author',
			'response' => 'comment_post_ID',
			'date'     => 'comment_date'
		);
	}

	/**
	 * Get the name of the default primary column.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @return string Name of the default primary column, in this case, 'comment'.
	 */
	protected function get_default_primary_column_name() {
		return 'comment';
	}

	/**
	 * @access public
	 */
	public function display() {
		wp_nonce_field( "fetch-list-" . get_class( $this ), '_ajax_fetch_list_nonce' );

		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );

?>
<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
	<thead>
	<tr>
		<?php $this->print_column_headers(); ?>
	</tr>
	</thead>

	<tbody id="the-comment-list" data-wp-lists="list:comment">
		<?php $this->display_rows_or_placeholder(); ?>
	</tbody>

	<tbody id="the-extra-comment-list" data-wp-lists="list:comment" style="display: none;">
		<?php
			$this->items = $this->extra_items;
			$this->display_rows_or_placeholder();
		?>
	</tbody>

	<tfoot>
	<tr>
		<?php $this->print_column_headers( false ); ?>
	</tr>
	</tfoot>

</table>
<?php

		$this->display_tablenav( 'bottom' );
	}

	/**
	 * @global WP_Post    $post
	 * @global WP_Comment $comment
	 *
	 * @param WP_Comment $item
	 */
	public function single_row( $item ) {
		global $post, $comment;

		$comment = $item;

		$the_comment_class = wp_get_comment_status( $comment );
		if ( ! $the_comment_class ) {
			$the_comment_class = '';
		}
		$the_comment_class = join( ' ', get_comment_class( $the_comment_class, $comment, $comment->comment_post_ID ) );

		if ( $comment->comment_post_ID > 0 ) {
			$post = get_post( $comment->comment_post_ID );
		}
		$this->user_can = current_user_can( 'edit_comment', $comment->comment_ID );

		echo "<tr id='comment-$comment->comment_ID' class='$the_comment_class'>";
		$this->single_row_columns( $comment );
		echo "</tr>\n";

		unset( $GLOBALS['post'], $GLOBALS['comment'] );
	}

 	/**
 	 * Generate and display row actions links.
 	 *
 	 * @since 4.3.0
 	 * @access protected
 	 *
 	 * @global string $comment_status Status for the current listed comments.
 	 *
 	 * @param WP_Comment $comment     The comment object.
 	 * @param string     $column_name Current column name.
 	 * @param string     $primary     Primary column name.
 	 * @return string|void Comment row actions output.
 	 */
 	protected function handle_row_actions( $comment, $column_name, $primary ) {
 		global $comment_status;

		if ( $primary !== $column_name ) {
			return '';
		}

 		if ( ! $this->user_can ) {
 			return;
		}

		$the_comment_status = wp_get_comment_status( $comment );

		$out = '';

		$del_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "delete-comment_$comment->comment_ID" ) );
		$approve_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "approve-comment_$comment->comment_ID" ) );

		$url = "comment.php?c=$comment->comment_ID";

		$approve_url = esc_url( $url . "&action=approvecomment&$approve_nonce" );
		$unapprove_url = esc_url( $url . "&action=unapprovecomment&$approve_nonce" );
		$spam_url = esc_url( $url . "&action=spamcomment&$del_nonce" );
		$unspam_url = esc_url( $url . "&action=unspamcomment&$del_nonce" );
		$trash_url = esc_url( $url . "&action=trashcomment&$del_nonce" );
		$untrash_url = esc_url( $url . "&action=untrashcomment&$del_nonce" );
		$delete_url = esc_url( $url . "&action=deletecomment&$del_nonce" );

		// Preorder it: Approve | Reply | Quick Edit | Edit | Spam | Trash.
		$actions = array(
			'approve' => '', 'unapprove' => '',
			'reply' => '',
			'quickedit' => '',
			'edit' => '',
			'spam' => '', 'unspam' => '',
			'trash' => '', 'untrash' => '', 'delete' => ''
		);

		// Not looking at all comments.
		if ( $comment_status && 'all' != $comment_status ) {
			if ( 'approved' === $the_comment_status ) {
				$actions['unapprove'] = "<a href='$unapprove_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:e7e7d3:action=dim-comment&amp;new=unapproved' class='vim-u vim-destructive' aria-label='" . esc_attr__( 'Unapprove this comment' ) . "'>" . __( 'Unapprove' ) . '</a>';
			} elseif ( 'unapproved' === $the_comment_status ) {
				$actions['approve'] = "<a href='$approve_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:e7e7d3:action=dim-comment&amp;new=approved' class='vim-a vim-destructive' aria-label='" . esc_attr__( 'Approve this comment' ) . "'>" . __( 'Approve' ) . '</a>';
			}
		} else {
			$actions['approve'] = "<a href='$approve_url' data-wp-lists='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=approved' class='vim-a' aria-label='" . esc_attr__( 'Approve this comment' ) . "'>" . __( 'Approve' ) . '</a>';
			$actions['unapprove'] = "<a href='$unapprove_url' data-wp-lists='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=unapproved' class='vim-u' aria-label='" . esc_attr__( 'Unapprove this comment' ) . "'>" . __( 'Unapprove' ) . '</a>';
		}

		if ( 'spam' !== $the_comment_status ) {
			$actions['spam'] = "<a href='$spam_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::spam=1' class='vim-s vim-destructive' aria-label='" . esc_attr__( 'Mark this comment as spam' ) . "'>" . /* translators: mark as spam link */ _x( 'Spam', 'verb' ) . '</a>';
		} elseif ( 'spam' === $the_comment_status ) {
			$actions['unspam'] = "<a href='$unspam_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:66cc66:unspam=1' class='vim-z vim-destructive' aria-label='" . esc_attr__( 'Restore this comment from the spam' ) . "'>" . _x( 'Not Spam', 'comment' ) . '</a>';
		}

		if ( 'trash' === $the_comment_status ) {
			$actions['untrash'] = "<a href='$untrash_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID:66cc66:untrash=1' class='vim-z vim-destructive' aria-label='" . esc_attr__( 'Restore this comment from the Trash' ) . "'>" . __( 'Restore' ) . '</a>';
		}

		if ( 'spam' === $the_comment_status || 'trash' === $the_comment_status || !EMPTY_TRASH_DAYS ) {
			$actions['delete'] = "<a href='$delete_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::delete=1' class='delete vim-d vim-destructive' aria-label='" . esc_attr__( 'Delete this comment permanently' ) . "'>" . __( 'Delete Permanently' ) . '</a>';
		} else {
			$actions['trash'] = "<a href='$trash_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::trash=1' class='delete vim-d vim-destructive' aria-label='" . esc_attr__( 'Move this comment to the Trash' ) . "'>" . _x( 'Trash', 'verb' ) . '</a>';
		}

		if ( 'spam' !== $the_comment_status && 'trash' !== $the_comment_status ) {
			$actions['edit'] = "<a href='comment.php?action=editcomment&amp;c={$comment->comment_ID}' aria-label='" . esc_attr__( 'Edit this comment' ) . "'>". __( 'Edit' ) . '</a>';

			$format = '<a data-comment-id="%d" data-post-id="%d" data-action="%s" class="%s" aria-label="%s" href="#">%s</a>';

			$actions['quickedit'] = sprintf( $format, $comment->comment_ID, $comment->comment_post_ID, 'edit', 'vim-q comment-inline', esc_attr__( 'Quick edit this comment inline' ), __( 'Quick&nbsp;Edit' ) );

			$actions['reply'] = sprintf( $format, $comment->comment_ID, $comment->comment_post_ID, 'replyto', 'vim-r comment-inline', esc_attr__( 'Reply to this comment' ), __( 'Reply' ) );
		}

		/** This filter is documented in wp-admin/includes/dashboard.php */
		$actions = apply_filters( 'comment_row_actions', array_filter( $actions ), $comment );

		$i = 0;
		$out .= '<div class="row-actions">';
		foreach ( $actions as $action => $link ) {
			++$i;
			( ( ( 'approve' === $action || 'unapprove' === $action ) && 2 === $i ) || 1 === $i ) ? $sep = '' : $sep = ' | ';

			// Reply and quickedit need a hide-if-no-js span when not added with ajax
			if ( ( 'reply' === $action || 'quickedit' === $action ) && ! defined('DOING_AJAX') )
				$action .= ' hide-if-no-js';
			elseif ( ( $action === 'untrash' && $the_comment_status === 'trash' ) || ( $action === 'unspam' && $the_comment_status === 'spam' ) ) {
				if ( '1' == get_comment_meta( $comment->comment_ID, '_wp_trash_meta_status', true ) )
					$action .= ' approve';
				else
					$action .= ' unapprove';
			}

			$out .= "<span class='$action'>$sep$link</span>";
		}
		$out .= '</div>';

		$out .= '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details' ) . '</span></button>';

		return $out;
	}

	/**
	 *
	 * @param WP_Comment $comment The comment object.
	 */
	public function column_cb( $comment ) {
		if ( $this->user_can ) { ?>
		<label class="screen-reader-text" for="cb-select-<?php echo $comment->comment_ID; ?>"><?php _e( 'Select comment' ); ?></label>
		<input id="cb-select-<?php echo $comment->comment_ID; ?>" type="checkbox" name="delete_comments[]" value="<?php echo $comment->comment_ID; ?>" />
		<?php
		}
	}

	/**
	 * @param WP_Comment $comment The comment object.
	 */
	public function column_comment( $comment ) {
		echo '<div class="comment-author">';
			$this->column_author( $comment );
		echo '</div>';

		if ( $comment->comment_parent ) {
			$parent = get_comment( $comment->comment_parent );
			if ( $parent ) {
				$parent_link = esc_url( get_comment_link( $parent ) );
				$name = get_comment_author( $parent );
				printf(
					/* translators: %s: comment link */
					__( 'In reply to %s.' ),
					'<a href="' . $parent_link . '">' . $name . '</a>'
				);
			}
		}

		comment_text( $comment );
		if ( $this->user_can ) { ?>
		<div id="inline-<?php echo $comment->comment_ID; ?>" class="hidden">
		<textarea class="comment" rows="1" cols="1"><?php
			/** This filter is documented in wp-admin/includes/comment.php */
			echo esc_textarea( apply_filters( 'comment_edit_pre', $comment->comment_content ) );
		?></textarea>
		<div class="author-email"><?php echo esc_attr( $comment->comment_author_email ); ?></div>
		<div class="author"><?php echo esc_attr( $comment->comment_author ); ?></div>
		<div class="author-url"><?php echo esc_attr( $comment->comment_author_url ); ?></div>
		<div class="comment_status"><?php echo $comment->comment_approved; ?></div>
		</div>
		<?php
		}
	}

	/**
	 *
	 * @global string $comment_status
	 *
	 * @param WP_Comment $comment The comment object.
	 */
	public function column_author( $comment ) {
		global $comment_status;

		$author_url = get_comment_author_url( $comment );

		$author_url_display = untrailingslashit( preg_replace( '|^http(s)?://(www\.)?|i', '', $author_url ) );
		if ( strlen( $author_url_display ) > 50 ) {
			$author_url_display = wp_html_excerpt( $author_url_display, 49, '&hellip;' );
		}

		echo "<strong>"; comment_author( $comment ); echo '</strong><br />';
		if ( ! empty( $author_url_display ) ) {
			printf( '<a href="%s">%s</a><br />', esc_url( $author_url ), esc_html( $author_url_display ) );
		}

		if ( $this->user_can ) {
			if ( ! empty( $comment->comment_author_email ) ) {
				/* This filter is documented in wp-includes/comment-template.php */
				$email = apply_filters( 'comment_email', $comment->comment_author_email, $comment );

				if ( ! empty( $email ) && '@' !== $email ) {
					printf( '<a href="%1$s">%2$s</a><br />', esc_url( 'mailto:' . $email ), esc_html( $email ) );
				}
			}

			$author_ip = get_comment_author_IP( $comment );
			if ( $author_ip ) {
				$author_ip_url = add_query_arg( array( 's' => $author_ip, 'mode' => 'detail' ), admin_url( 'edit-comments.php' ) );
				if ( 'spam' === $comment_status ) {
					$author_ip_url = add_query_arg( 'comment_status', 'spam', $author_ip_url );
				}
				printf( '<a href="%1$s">%2$s</a>', esc_url( $author_ip_url ), esc_html( $author_ip ) );
			}
		}
	}

	/**
	 * @access public
	 *
	 * @param WP_Comment $comment The comment object.
	 */
	public function column_date( $comment ) {
		/* translators: 1: comment date, 2: comment time */
		$submitted = sprintf( __( '%1$s at %2$s' ),
			/* translators: comment date format. See http://php.net/date */
			get_comment_date( __( 'Y/m/d' ), $comment ),
			get_comment_date( __( 'g:i a' ), $comment )
		);

		echo '<div class="submitted-on">';
		if ( 'approved' === wp_get_comment_status( $comment ) && ! empty ( $comment->comment_post_ID ) ) {
			printf(
				'<a href="%s">%s</a>',
				esc_url( get_comment_link( $comment ) ),
				$submitted
			);
		} else {
			echo $submitted;
		}
		echo '</div>';
	}

	/**
	 * @access public
	 *
	 * @param WP_Comment $comment The comment object.
	 */
	public function column_response( $comment ) {
		$post = get_post();

		if ( ! $post ) {
			return;
		}

		if ( isset( $this->pending_count[$post->ID] ) ) {
			$pending_comments = $this->pending_count[$post->ID];
		} else {
			$_pending_count_temp = get_pending_comments_num( array( $post->ID ) );
			$pending_comments = $this->pending_count[$post->ID] = $_pending_count_temp[$post->ID];
		}

		if ( current_user_can( 'edit_post', $post->ID ) ) {
			$post_link = "<a href='" . get_edit_post_link( $post->ID ) . "' class='comments-edit-item-link'>";
			$post_link .= esc_html( get_the_title( $post->ID ) ) . '</a>';
		} else {
			$post_link = esc_html( get_the_title( $post->ID ) );
		}

		echo '<div class="response-links">';
		if ( 'attachment' === $post->post_type && ( $thumb = wp_get_attachment_image( $post->ID, array( 80, 60 ), true ) ) ) {
			echo $thumb;
		}
		echo $post_link;
		$post_type_object = get_post_type_object( $post->post_type );
		echo "<a href='" . get_permalink( $post->ID ) . "' class='comments-view-item-link'>" . $post_type_object->labels->view_item . '</a>';
		echo '<span class="post-com-count-wrapper post-com-count-', $post->ID, '">';
		$this->comments_bubble( $post->ID, $pending_comments );
		echo '</span> ';
		echo '</div>';
	}

	/**
	 *
	 * @param WP_Comment $comment     The comment object.
	 * @param string     $column_name The custom column's name.
	 */
	public function column_default( $comment, $column_name ) {
		/**
		 * Fires when the default column output is displayed for a single row.
		 *
		 * @since 2.8.0
		 *
		 * @param string $column_name         The custom column's name.
		 * @param int    $comment->comment_ID The custom column's unique ID number.
		 */
		do_action( 'manage_comments_custom_column', $column_name, $comment->comment_ID );
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
/**
 * PemFTP - A Ftp implementation in pure PHP
 *
 * @package PemFTP
 * @since 2.5
 *
 * @version 1.0
 * @copyright Alexey Dotsenko
 * @author Alexey Dotsenko
 * @link http://www.phpclasses.org/browse/package/1743.html Site
 * @license LGPL http://www.opensource.org/licenses/lgpl-license.html
 */

/**
 * Defines the newline characters, if not defined already.
 *
 * This can be redefined.
 *
 * @since 2.5
 * @var string
 */
if(!defined('CRLF')) define('CRLF',"\r\n");

/**
 * Sets whatever to autodetect ASCII mode.
 *
 * This can be redefined.
 *
 * @since 2.5
 * @var int
 */
if(!defined("FTP_AUTOASCII")) define("FTP_AUTOASCII", -1);

/**
 *
 * This can be redefined.
 * @since 2.5
 * @var int
 */
if(!defined("FTP_BINARY")) define("FTP_BINARY", 1);

/**
 *
 * This can be redefined.
 * @since 2.5
 * @var int
 */
if(!defined("FTP_ASCII")) define("FTP_ASCII", 0);

/**
 * Whether to force FTP.
 *
 * This can be redefined.
 *
 * @since 2.5
 * @var bool
 */
if(!defined('FTP_FORCE')) define('FTP_FORCE', true);

/**
 * @since 2.5
 * @var string
 */
define('FTP_OS_Unix','u');

/**
 * @since 2.5
 * @var string
 */
define('FTP_OS_Windows','w');

/**
 * @since 2.5
 * @var string
 */
define('FTP_OS_Mac','m');

/**
 * PemFTP base class
 *
 */
class ftp_base {
	/* Public variables */
	var $LocalEcho;
	var $Verbose;
	var $OS_local;
	var $OS_remote;

	/* Private variables */
	var $_lastaction;
	var $_errors;
	var $_type;
	var $_umask;
	var $_timeout;
	var $_passive;
	var $_host;
	var $_fullhost;
	var $_port;
	var $_datahost;
	var $_dataport;
	var $_ftp_control_sock;
	var $_ftp_data_sock;
	var $_ftp_temp_sock;
	var $_ftp_buff_size;
	var $_login;
	var $_password;
	var $_connected;
	var $_ready;
	var $_code;
	var $_message;
	var $_can_restore;
	var $_port_available;
	var $_curtype;
	var $_features;

	var $_error_array;
	var $AuthorizedTransferMode;
	var $OS_FullName;
	var $_eol_code;
	var $AutoAsciiExt;

	/* Constructor */
	function __construct($port_mode=FALSE, $verb=FALSE, $le=FALSE) {
		$this->LocalEcho=$le;
		$this->Verbose=$verb;
		$this->_lastaction=NULL;
		$this->_error_array=array();
		$this->_eol_code=array(FTP_OS_Unix=>"\n", FTP_OS_Mac=>"\r", FTP_OS_Windows=>"\r\n");
		$this->AuthorizedTransferMode=array(FTP_AUTOASCII, FTP_ASCII, FTP_BINARY);
		$this->OS_FullName=array(FTP_OS_Unix => 'UNIX', FTP_OS_Windows => 'WINDOWS', FTP_OS_Mac => 'MACOS');
		$this->AutoAsciiExt=array("ASP","BAT","C","CPP","CSS","CSV","JS","H","HTM","HTML","SHTML","INI","LOG","PHP3","PHTML","PL","PERL","SH","SQL","TXT");
		$this->_port_available=($port_mode==TRUE);
		$this->SendMSG("Staring FTP client class".($this->_port_available?"":" without PORT mode support"));
		$this->_connected=FALSE;
		$this->_ready=FALSE;
		$this->_can_restore=FALSE;
		$this->_code=0;
		$this->_message="";
		$this->_ftp_buff_size=4096;
		$this->_curtype=NULL;
		$this->SetUmask(0022);
		$this->SetType(FTP_AUTOASCII);
		$this->SetTimeout(30);
		$this->Passive(!$this->_port_available);
		$this->_login="anonymous";
		$this->_password="anon@ftp.com";
		$this->_features=array();
	    $this->OS_local=FTP_OS_Unix;
		$this->OS_remote=FTP_OS_Unix;
		$this->features=array();
		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $this->OS_local=FTP_OS_Windows;
		elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'MAC') $this->OS_local=FTP_OS_Mac;
	}

	function ftp_base($port_mode=FALSE) {
		$this->__construct($port_mode);
	}

// <!-- --------------------------------------------------------------------------------------- -->
// <!--       Public functions                                                                  -->
// <!-- --------------------------------------------------------------------------------------- -->

	function parselisting($line) {
		$is_windows = ($this->OS_remote == FTP_OS_Windows);
		if ($is_windows && preg_match("/([0-9]{2})-([0-9]{2})-([0-9]{2}) +([0-9]{2}):([0-9]{2})(AM|PM) +([0-9]+|<DIR>) +(.+)/",$line,$lucifer)) {
			$b = array();
			if ($lucifer[3]<70) { $lucifer[3]+=2000; } else { $lucifer[3]+=1900; } // 4digit year fix
			$b['isdir'] = ($lucifer[7]=="<DIR>");
			if ( $b['isdir'] )
				$b['type'] = 'd';
			else
				$b['type'] = 'f';
			$b['size'] = $lucifer[7];
			$b['month'] = $lucifer[1];
			$b['day'] = $lucifer[2];
			$b['year'] = $lucifer[3];
			$b['hour'] = $lucifer[4];
			$b['minute'] = $lucifer[5];
			$b['time'] = @mktime($lucifer[4]+(strcasecmp($lucifer[6],"PM")==0?12:0),$lucifer[5],0,$lucifer[1],$lucifer[2],$lucifer[3]);
			$b['am/pm'] = $lucifer[6];
			$b['name'] = $lucifer[8];
		} else if (!$is_windows && $lucifer=preg_split("/[ ]/",$line,9,PREG_SPLIT_NO_EMPTY)) {
			//echo $line."\n";
			$lcount=count($lucifer);
			if ($lcount<8) return '';
			$b = array();
			$b['isdir'] = $lucifer[0]{0} === "d";
			$b['islink'] = $lucifer[0]{0} === "l";
			if ( $b['isdir'] )
				$b['type'] = 'd';
			elseif ( $b['islink'] )
				$b['type'] = 'l';
			else
				$b['type'] = 'f';
			$b['perms'] = $lucifer[0];
			$b['number'] = $lucifer[1];
			$b['owner'] = $lucifer[2];
			$b['group'] = $lucifer[3];
			$b['size'] = $lucifer[4];
			if ($lcount==8) {
				sscanf($lucifer[5],"%d-%d-%d",$b['year'],$b['month'],$b['day']);
				sscanf($lucifer[6],"%d:%d",$b['hour'],$b['minute']);
				$b['time'] = @mktime($b['hour'],$b['minute'],0,$b['month'],$b['day'],$b['year']);
				$b['name'] = $lucifer[7];
			} else {
				$b['month'] = $lucifer[5];
				$b['day'] = $lucifer[6];
				if (preg_match("/([0-9]{2}):([0-9]{2})/",$lucifer[7],$l2)) {
					$b['year'] = date("Y");
					$b['hour'] = $l2[1];
					$b['minute'] = $l2[2];
				} else {
					$b['year'] = $lucifer[7];
					$b['hour'] = 0;
					$b['minute'] = 0;
				}
				$b['time'] = strtotime(sprintf("%d %s %d %02d:%02d",$b['day'],$b['month'],$b['year'],$b['hour'],$b['minute']));
				$b['name'] = $lucifer[8];
			}
		}

		return $b;
	}

	function SendMSG($message = "", $crlf=true) {
		if ($this->Verbose) {
			echo $message.($crlf?CRLF:"");
			flush();
		}
		return TRUE;
	}

	function SetType($mode=FTP_AUTOASCII) {
		if(!in_array($mode, $this->AuthorizedTransferMode)) {
			$this->SendMSG("Wrong type");
			return FALSE;
		}
		$this->_type=$mode;
		$this->SendMSG("Transfer type: ".($this->_type==FTP_BINARY?"binary":($this->_type==FTP_ASCII?"ASCII":"auto ASCII") ) );
		return TRUE;
	}

	function _settype($mode=FTP_ASCII) {
		if($this->_ready) {
			if($mode==FTP_BINARY) {
				if($this->_curtype!=FTP_BINARY) {
					if(!$this->_exec("TYPE I", "SetType")) return FALSE;
					$this->_curtype=FTP_BINARY;
				}
			} elseif($this->_curtype!=FTP_ASCII) {
				if(!$this->_exec("TYPE A", "SetType")) return FALSE;
				$this->_curtype=FTP_ASCII;
			}
		} else return FALSE;
		return TRUE;
	}

	function Passive($pasv=NULL) {
		if(is_null($pasv)) $this->_passive=!$this->_passive;
		else $this->_passive=$pasv;
		if(!$this->_port_available and !$this->_passive) {
			$this->SendMSG("Only passive connections available!");
			$this->_passive=TRUE;
			return FALSE;
		}
		$this->SendMSG("Passive mode ".($this->_passive?"on":"off"));
		return TRUE;
	}

	function SetServer($host, $port=21, $reconnect=true) {
		if(!is_long($port)) {
	        $this->verbose=true;
    	    $this->SendMSG("Incorrect port syntax");
			return FALSE;
		} else {
			$ip=@gethostbyname($host);
	        $dns=@gethostbyaddr($host);
	        if(!$ip) $ip=$host;
	        if(!$dns) $dns=$host;
	        // Validate the IPAddress PHP4 returns -1 for invalid, PHP5 false
	        // -1 === "255.255.255.255" which is the broadcast address which is also going to be invalid
	        $ipaslong = ip2long($ip);
			if ( ($ipaslong == false) || ($ipaslong === -1) ) {
				$this->SendMSG("Wrong host name/address \"".$host."\"");
				return FALSE;
			}
	        $this->_host=$ip;
	        $this->_fullhost=$dns;
	        $this->_port=$port;
	        $this->_dataport=$port-1;
		}
		$this->SendMSG("Host \"".$this->_fullhost."(".$this->_host."):".$this->_port."\"");
		if($reconnect){
			if($this->_connected) {
				$this->SendMSG("Reconnecting");
				if(!$this->quit(FTP_FORCE)) return FALSE;
				if(!$this->connect()) return FALSE;
			}
		}
		return TRUE;
	}

	function SetUmask($umask=0022) {
		$this->_umask=$umask;
		umask($this->_umask);
		$this->SendMSG("UMASK 0".decoct($this->_umask));
		return TRUE;
	}

	function SetTimeout($timeout=30) {
		$this->_timeout=$timeout;
		$this->SendMSG("Timeout ".$this->_timeout);
		if($this->_connected)
			if(!$this->_settimeout($this->_ftp_control_sock)) return FALSE;
		return TRUE;
	}

	function connect($server=NULL) {
		if(!empty($server)) {
			if(!$this->SetServer($server)) return false;
		}
		if($this->_ready) return true;
	    $this->SendMsg('Local OS : '.$this->OS_FullName[$this->OS_local]);
		if(!($this->_ftp_control_sock = $this->_connect($this->_host, $this->_port))) {
			$this->SendMSG("Error : Cannot connect to remote host \"".$this->_fullhost." :".$this->_port."\"");
			return FALSE;
		}
		$this->SendMSG("Connected to remote host \"".$this->_fullhost.":".$this->_port."\". Waiting for greeting.");
		do {
			if(!$this->_readmsg()) return FALSE;
			if(!$this->_checkCode()) return FALSE;
			$this->_lastaction=time();
		} while($this->_code<200);
		$this->_ready=true;
		$syst=$this->systype();
		if(!$syst) $this->SendMSG("Can't detect remote OS");
		else {
			if(preg_match("/win|dos|novell/i", $syst[0])) $this->OS_remote=FTP_OS_Windows;
			elseif(preg_match("/os/i", $syst[0])) $this->OS_remote=FTP_OS_Mac;
			elseif(preg_match("/(li|u)nix/i", $syst[0])) $this->OS_remote=FTP_OS_Unix;
			else $this->OS_remote=FTP_OS_Mac;
			$this->SendMSG("Remote OS: ".$this->OS_FullName[$this->OS_remote]);
		}
		if(!$this->features()) $this->SendMSG("Can't get features list. All supported - disabled");
		else $this->SendMSG("Supported features: ".implode(", ", array_keys($this->_features)));
		return TRUE;
	}

	function quit($force=false) {
		if($this->_ready) {
			if(!$this->_exec("QUIT") and !$force) return FALSE;
			if(!$this->_checkCode() and !$force) return FALSE;
			$this->_ready=false;
			$this->SendMSG("Session finished");
		}
		$this->_quit();
		return TRUE;
	}

	function login($user=NULL, $pass=NULL) {
		if(!is_null($user)) $this->_login=$user;
		else $this->_login="anonymous";
		if(!is_null($pass)) $this->_password=$pass;
		else $this->_password="anon@anon.com";
		if(!$this->_exec("USER ".$this->_login, "login")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		if($this->_code!=230) {
			if(!$this->_exec((($this->_code==331)?"PASS ":"ACCT ").$this->_password, "login")) return FALSE;
			if(!$this->_checkCode()) return FALSE;
		}
		$this->SendMSG("Authentication succeeded");
		if(empty($this->_features)) {
			if(!$this->features()) $this->SendMSG("Can't get features list. All supported - disabled");
			else $this->SendMSG("Supported features: ".implode(", ", array_keys($this->_features)));
		}
		return TRUE;
	}

	function pwd() {
		if(!$this->_exec("PWD", "pwd")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return preg_replace("/^[0-9]{3} \"(.+)\".*$/s", "\\1", $this->_message);
	}

	function cdup() {
		if(!$this->_exec("CDUP", "cdup")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return true;
	}

	function chdir($pathname) {
		if(!$this->_exec("CWD ".$pathname, "chdir")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return TRUE;
	}

	function rmdir($pathname) {
		if(!$this->_exec("RMD ".$pathname, "rmdir")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return TRUE;
	}

	function mkdir($pathname) {
		if(!$this->_exec("MKD ".$pathname, "mkdir")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return TRUE;
	}

	function rename($from, $to) {
		if(!$this->_exec("RNFR ".$from, "rename")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		if($this->_code==350) {
			if(!$this->_exec("RNTO ".$to, "rename")) return FALSE;
			if(!$this->_checkCode()) return FALSE;
		} else return FALSE;
		return TRUE;
	}

	function filesize($pathname) {
		if(!isset($this->_features["SIZE"])) {
			$this->PushError("filesize", "not supported by server");
			return FALSE;
		}
		if(!$this->_exec("SIZE ".$pathname, "filesize")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return preg_replace("/^[0-9]{3} ([0-9]+).*$/s", "\\1", $this->_message);
	}

	function abort() {
		if(!$this->_exec("ABOR", "abort")) return FALSE;
		if(!$this->_checkCode()) {
			if($this->_code!=426) return FALSE;
			if(!$this->_readmsg("abort")) return FALSE;
			if(!$this->_checkCode()) return FALSE;
		}
		return true;
	}

	function mdtm($pathname) {
		if(!isset($this->_features["MDTM"])) {
			$this->PushError("mdtm", "not supported by server");
			return FALSE;
		}
		if(!$this->_exec("MDTM ".$pathname, "mdtm")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		$mdtm = preg_replace("/^[0-9]{3} ([0-9]+).*$/s", "\\1", $this->_message);
		$date = sscanf($mdtm, "%4d%2d%2d%2d%2d%2d");
		$timestamp = mktime($date[3], $date[4], $date[5], $date[1], $date[2], $date[0]);
		return $timestamp;
	}

	function systype() {
		if(!$this->_exec("SYST", "systype")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		$DATA = explode(" ", $this->_message);
		return array($DATA[1], $DATA[3]);
	}

	function delete($pathname) {
		if(!$this->_exec("DELE ".$pathname, "delete")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return TRUE;
	}

	function site($command, $fnction="site") {
		if(!$this->_exec("SITE ".$command, $fnction)) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return TRUE;
	}

	function chmod($pathname, $mode) {
		if(!$this->site( sprintf('CHMOD %o %s', $mode, $pathname), "chmod")) return FALSE;
		return TRUE;
	}

	function restore($from) {
		if(!isset($this->_features["REST"])) {
			$this->PushError("restore", "not supported by server");
			return FALSE;
		}
		if($this->_curtype!=FTP_BINARY) {
			$this->PushError("restore", "can't restore in ASCII mode");
			return FALSE;
		}
		if(!$this->_exec("REST ".$from, "resore")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return TRUE;
	}

	function features() {
		if(!$this->_exec("FEAT", "features")) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		$f=preg_split("/[".CRLF."]+/", preg_replace("/[0-9]{3}[ -].*[".CRLF."]+/", "", $this->_message), -1, PREG_SPLIT_NO_EMPTY);
		$this->_features=array();
		foreach($f as $k=>$v) {
			$v=explode(" ", trim($v));
			$this->_features[array_shift($v)]=$v;
		}
		return true;
	}

	function rawlist($pathname="", $arg="") {
		return $this->_list(($arg?" ".$arg:"").($pathname?" ".$pathname:""), "LIST", "rawlist");
	}

	function nlist($pathname="", $arg="") {
		return $this->_list(($arg?" ".$arg:"").($pathname?" ".$pathname:""), "NLST", "nlist");
	}

	function is_exists($pathname) {
		return $this->file_exists($pathname);
	}

	function file_exists($pathname) {
		$exists=true;
		if(!$this->_exec("RNFR ".$pathname, "rename")) $exists=FALSE;
		else {
			if(!$this->_checkCode()) $exists=FALSE;
			$this->abort();
		}
		if($exists) $this->SendMSG("Remote file ".$pathname." exists");
		else $this->SendMSG("Remote file ".$pathname." does not exist");
		return $exists;
	}

	function fget($fp, $remotefile,$rest=0) {
		if($this->_can_restore and $rest!=0) fseek($fp, $rest);
		$pi=pathinfo($remotefile);
		if($this->_type==FTP_ASCII or ($this->_type==FTP_AUTOASCII and in_array(strtoupper($pi["extension"]), $this->AutoAsciiExt))) $mode=FTP_ASCII;
		else $mode=FTP_BINARY;
		if(!$this->_data_prepare($mode)) {
			return FALSE;
		}
		if($this->_can_restore and $rest!=0) $this->restore($rest);
		if(!$this->_exec("RETR ".$remotefile, "get")) {
			$this->_data_close();
			return FALSE;
		}
		if(!$this->_checkCode()) {
			$this->_data_close();
			return FALSE;
		}
		$out=$this->_data_read($mode, $fp);
		$this->_data_close();
		if(!$this->_readmsg()) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return $out;
	}

	function get($remotefile, $localfile=NULL, $rest=0) {
		if(is_null($localfile)) $localfile=$remotefile;
		if (@file_exists($localfile)) $this->SendMSG("Warning : local file will be overwritten");
		$fp = @fopen($localfile, "w");
		if (!$fp) {
			$this->PushError("get","can't open local file", "Cannot create \"".$localfile."\"");
			return FALSE;
		}
		if($this->_can_restore and $rest!=0) fseek($fp, $rest);
		$pi=pathinfo($remotefile);
		if($this->_type==FTP_ASCII or ($this->_type==FTP_AUTOASCII and in_array(strtoupper($pi["extension"]), $this->AutoAsciiExt))) $mode=FTP_ASCII;
		else $mode=FTP_BINARY;
		if(!$this->_data_prepare($mode)) {
			fclose($fp);
			return FALSE;
		}
		if($this->_can_restore and $rest!=0) $this->restore($rest);
		if(!$this->_exec("RETR ".$remotefile, "get")) {
			$this->_data_close();
			fclose($fp);
			return FALSE;
		}
		if(!$this->_checkCode()) {
			$this->_data_close();
			fclose($fp);
			return FALSE;
		}
		$out=$this->_data_read($mode, $fp);
		fclose($fp);
		$this->_data_close();
		if(!$this->_readmsg()) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return $out;
	}

	function fput($remotefile, $fp) {
		if($this->_can_restore and $rest!=0) fseek($fp, $rest);
		$pi=pathinfo($remotefile);
		if($this->_type==FTP_ASCII or ($this->_type==FTP_AUTOASCII and in_array(strtoupper($pi["extension"]), $this->AutoAsciiExt))) $mode=FTP_ASCII;
		else $mode=FTP_BINARY;
		if(!$this->_data_prepare($mode)) {
			return FALSE;
		}
		if($this->_can_restore and $rest!=0) $this->restore($rest);
		if(!$this->_exec("STOR ".$remotefile, "put")) {
			$this->_data_close();
			return FALSE;
		}
		if(!$this->_checkCode()) {
			$this->_data_close();
			return FALSE;
		}
		$ret=$this->_data_write($mode, $fp);
		$this->_data_close();
		if(!$this->_readmsg()) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return $ret;
	}

	function put($localfile, $remotefile=NULL, $rest=0) {
		if(is_null($remotefile)) $remotefile=$localfile;
		if (!file_exists($localfile)) {
			$this->PushError("put","can't open local file", "No such file or directory \"".$localfile."\"");
			return FALSE;
		}
		$fp = @fopen($localfile, "r");

		if (!$fp) {
			$this->PushError("put","can't open local file", "Cannot read file \"".$localfile."\"");
			return FALSE;
		}
		if($this->_can_restore and $rest!=0) fseek($fp, $rest);
		$pi=pathinfo($localfile);
		if($this->_type==FTP_ASCII or ($this->_type==FTP_AUTOASCII and in_array(strtoupper($pi["extension"]), $this->AutoAsciiExt))) $mode=FTP_ASCII;
		else $mode=FTP_BINARY;
		if(!$this->_data_prepare($mode)) {
			fclose($fp);
			return FALSE;
		}
		if($this->_can_restore and $rest!=0) $this->restore($rest);
		if(!$this->_exec("STOR ".$remotefile, "put")) {
			$this->_data_close();
			fclose($fp);
			return FALSE;
		}
		if(!$this->_checkCode()) {
			$this->_data_close();
			fclose($fp);
			return FALSE;
		}
		$ret=$this->_data_write($mode, $fp);
		fclose($fp);
		$this->_data_close();
		if(!$this->_readmsg()) return FALSE;
		if(!$this->_checkCode()) return FALSE;
		return $ret;
	}

	function mput($local=".", $remote=NULL, $continious=false) {
		$local=realpath($local);
		if(!@file_exists($local)) {
			$this->PushError("mput","can't open local folder", "Cannot stat folder \"".$local."\"");
			return FALSE;
		}
		if(!is_dir($local)) return $this->put($local, $remote);
		if(empty($remote)) $remote=".";
		elseif(!$this->file_exists($remote) and !$this->mkdir($remote)) return FALSE;
		if($handle = opendir($local)) {
			$list=array();
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") $list[]=$file;
			}
			closedir($handle);
		} else {
			$this->PushError("mput","can't open local folder", "Cannot read folder \"".$local."\"");
			return FALSE;
		}
		if(empty($list)) return TRUE;
		$ret=true;
		foreach($list as $el) {
			if(is_dir($local."/".$el)) $t=$this->mput($local."/".$el, $remote."/".$el);
			else $t=$this->put($local."/".$el, $remote."/".$el);
			if(!$t) {
				$ret=FALSE;
				if(!$continious) break;
			}
		}
		return $ret;

	}

	function mget($remote, $local=".", $continious=false) {
		$list=$this->rawlist($remote, "-lA");
		if($list===false) {
			$this->PushError("mget","can't read remote folder list", "Can't read remote folder \"".$remote."\" contents");
			return FALSE;
		}
		if(empty($list)) return true;
		if(!@file_exists($local)) {
			if(!@mkdir($local)) {
				$this->PushError("mget","can't create local folder", "Cannot create folder \"".$local."\"");
				return FALSE;
			}
		}
		foreach($list as $k=>$v) {
			$list[$k]=$this->parselisting($v);
			if( ! $list[$k] or $list[$k]["name"]=="." or $list[$k]["name"]=="..") unset($list[$k]);
		}
		$ret=true;
		foreach($list as $el) {
			if($el["type"]=="d") {
				if(!$this->mget($remote."/".$el["name"], $local."/".$el["name"], $continious)) {
					$this->PushError("mget", "can't copy folder", "Can't copy remote folder \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"");
					$ret=false;
					if(!$continious) break;
				}
			} else {
				if(!$this->get($remote."/".$el["name"], $local."/".$el["name"])) {
					$this->PushError("mget", "can't copy file", "Can't copy remote file \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"");
					$ret=false;
					if(!$continious) break;
				}
			}
			@chmod($local."/".$el["name"], $el["perms"]);
			$t=strtotime($el["date"]);
			if($t!==-1 and $t!==false) @touch($local."/".$el["name"], $t);
		}
		return $ret;
	}

	function mdel($remote, $continious=false) {
		$list=$this->rawlist($remote, "-la");
		if($list===false) {
			$this->PushError("mdel","can't read remote folder list", "Can't read remote folder \"".$remote."\" contents");
			return false;
		}

		foreach($list as $k=>$v) {
			$list[$k]=$this->parselisting($v);
			if( ! $list[$k] or $list[$k]["name"]=="." or $list[$k]["name"]=="..") unset($list[$k]);
		}
		$ret=true;

		foreach($list as $el) {
			if ( empty($el) )
				continue;

			if($el["type"]=="d") {
				if(!$this->mdel($remote."/".$el["name"], $continious)) {
					$ret=false;
					if(!$continious) break;
				}
			} else {
				if (!$this->delete($remote."/".$el["name"])) {
					$this->PushError("mdel", "can't delete file", "Can't delete remote file \"".$remote."/".$el["name"]."\"");
					$ret=false;
					if(!$continious) break;
				}
			}
		}

		if(!$this->rmdir($remote)) {
			$this->PushError("mdel", "can't delete folder", "Can't delete remote folder \"".$remote."/".$el["name"]."\"");
			$ret=false;
		}
		return $ret;
	}

	function mmkdir($dir, $mode = 0777) {
		if(empty($dir)) return FALSE;
		if($this->is_exists($dir) or $dir == "/" ) return TRUE;
		if(!$this->mmkdir(dirname($dir), $mode)) return false;
		$r=$this->mkdir($dir, $mode);
		$this->chmod($dir,$mode);
		return $r;
	}

	function glob($pattern, $handle=NULL) {
		$path=$output=null;
		if(PHP_OS=='WIN32') $slash='\\';
		else $slash='/';
		$lastpos=strrpos($pattern,$slash);
		if(!($lastpos===false)) {
			$path=substr($pattern,0,-$lastpos-1);
			$pattern=substr($pattern,$lastpos);
		} else $path=getcwd();
		if(is_array($handle) and !empty($handle)) {
			while($dir=each($handle)) {
				if($this->glob_pattern_match($pattern,$dir))
				$output[]=$dir;
			}
		} else {
			$handle=@opendir($path);
			if($handle===false) return false;
			while($dir=readdir($handle)) {
				if($this->glob_pattern_match($pattern,$dir))
				$output[]=$dir;
			}
			closedir($handle);
		}
		if(is_array($output)) return $output;
		return false;
	}

	function glob_pattern_match($pattern,$string) {
		$out=null;
		$chunks=explode(';',$pattern);
		foreach($chunks as $pattern) {
			$escape=array('$','^','.','{','}','(',')','[',']','|');
			while(strpos($pattern,'**')!==false)
				$pattern=str_replace('**','*',$pattern);
			foreach($escape as $probe)
				$pattern=str_replace($probe,"\\$probe",$pattern);
			$pattern=str_replace('?*','*',
				str_replace('*?','*',
					str_replace('*',".*",
						str_replace('?','.{1,1}',$pattern))));
			$out[]=$pattern;
		}
		if(count($out)==1) return($this->glob_regexp("^$out[0]$",$string));
		else {
			foreach($out as $tester)
				if($this->my_regexp("^$tester$",$string)) return true;
		}
		return false;
	}

	function glob_regexp($pattern,$probe) {
		$sensitive=(PHP_OS!='WIN32');
		return ($sensitive?
			preg_match( '/' . preg_quote( $pattern, '/' ) . '/', $probe ) : 
			preg_match( '/' . preg_quote( $pattern, '/' ) . '/i', $probe )
		);
	}

	function dirlist($remote) {
		$list=$this->rawlist($remote, "-la");
		if($list===false) {
			$this->PushError("dirlist","can't read remote folder list", "Can't read remote folder \"".$remote."\" contents");
			return false;
		}

		$dirlist = array();
		foreach($list as $k=>$v) {
			$entry=$this->parselisting($v);
			if ( empty($entry) )
				continue;

			if($entry["name"]=="." or $entry["name"]=="..")
				continue;

			$dirlist[$entry['name']] = $entry;
		}

		return $dirlist;
	}
// <!-- --------------------------------------------------------------------------------------- -->
// <!--       Private functions                                                                 -->
// <!-- --------------------------------------------------------------------------------------- -->
	function _checkCode() {
		return ($this->_code<400 and $this->_code>0);
	}

	function _list($arg="", $cmd="LIST", $fnction="_list") {
		if(!$this->_data_prepare()) return false;
		if(!$this->_exec($cmd.$arg, $fnction)) {
			$this->_data_close();
			return FALSE;
		}
		if(!$this->_checkCode()) {
			$this->_data_close();
			return FALSE;
		}
		$out="";
		if($this->_code<200) {
			$out=$this->_data_read();
			$this->_data_close();
			if(!$this->_readmsg()) return FALSE;
			if(!$this->_checkCode()) return FALSE;
			if($out === FALSE ) return FALSE;
			$out=preg_split("/[".CRLF."]+/", $out, -1, PREG_SPLIT_NO_EMPTY);
//			$this->SendMSG(implode($this->_eol_code[$this->OS_local], $out));
		}
		return $out;
	}

// <!-- --------------------------------------------------------------------------------------- -->
// <!-- Partie : gestion des erreurs                                                            -->
// <!-- --------------------------------------------------------------------------------------- -->
// Gnre une erreur pour traitement externe  la classe
	function PushError($fctname,$msg,$desc=false){
		$error=array();
		$error['time']=time();
		$error['fctname']=$fctname;
		$error['msg']=$msg;
		$error['desc']=$desc;
		if($desc) $tmp=' ('.$desc.')'; else $tmp='';
		$this->SendMSG($fctname.': '.$msg.$tmp);
		return(array_push($this->_error_array,$error));
	}

// Rcupre une erreur externe
	function PopError(){
		if(count($this->_error_array)) return(array_pop($this->_error_array));
			else return(false);
	}
}

$mod_sockets = extension_loaded( 'sockets' );
if ( ! $mod_sockets && function_exists( 'dl' ) && is_callable( 'dl' ) ) {
	$prefix = ( PHP_SHLIB_SUFFIX == 'dll' ) ? 'php_' : '';
	@dl( $prefix . 'sockets.' . PHP_SHLIB_SUFFIX );
	$mod_sockets = extension_loaded( 'sockets' );
}

require_once dirname( __FILE__ ) . "/class-ftp-" . ( $mod_sockets ? "sockets" : "pure" ) . ".php";
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         �PNG

   IHDR     �   �;w   PLTELiq#&26=a��@R^GQZ|��J��<o�P��AW�T��&8K_��M��V��E��R��?a�=e�_ge@y�?c�mYGqq^S��*>B|X],@Q�\T@^�A[sS��(>KXK%#JJ^X1urQAI:]V4'>P)BJ�zz�A!3F0NW`~k^G��XE*���g1���}gNWN.B}�c���on[P-���|���~~�_\���/#q�aa�4t|����P�����������r�����������{Y���������Ҝ�ժ��b��Y����������������������G�������������������ٌ�ε����ܿ��������^��unL���渹���j�������%.K��Є��������[0G��̓����������Š�׹�鳿∺����������ꮙ�Ի�칟�v����⛙��ŎB+/��[{�����������Ī�g`@��ƣ��������6^2�ݤ������l����ת��ssq������Ļ����ѵ����ԙ���n����������Ӛ4G1��������___���͓}�ڷ�����������;������,=`XKP׹ہ}�㯪���������.@m��?n�h�����ߞ���j<�έ��tګ�V�����`w�ȧC����������ښ�c���״H�|�Uo�ʊ��������g�����M��{��vޝ7ЁM���ޒ_�7Ō1~{J��q�|��^���Ү��oP�Ĳx�r����ځioX~TZ���Ш��Γ��oB8�����W{,��   GtRNS $1F%����Z�?�s谻K6��K������i�������k��S̀�m��q�S������\���~������Q�  -IDATx����OSY�q0][c��2%�H�����������&�0&;k2J�!ҡ�61m%RE2��$����Ȋ�D(�y���[ bx����s[�+��[wܝ�~K�K�h?=%!��������������������������������������������u��R�H��������*��{�����ES�Р�s8��k�Ph3w����I2/�[���� (�H��%�X##��<i�� ݔ��h�n��o���#�-��֥|�w�v��L_�R(�Q!ۻ/*��%��2=����r�R����^���&V|E�WDӎD�z��];�])���ȋ#aa0d����!А�i0�� b�������36]<����B��:]1�������,-ܽ�Ná��'���*��g���ٞJ�'#�;QzRVz"�_t����`Y�_���l�����cnv}�&�����\�N��W_�
��,ddT�,���P���\M>�����h(4��l]G���b.�D��M!nu��ih�_x�r���YiH����IxL�����������l�>�@��qL'se������d��hv���y%'&E�=0J�|����~M������,-ĭ�8c&�KV[h����`��X5C����j�)d�3��ЪS*��V�ۭ�>OQg�>�ۍ0�(�P�V��a@VW�h�:�zg0/!�Ty����ޞ�''M�C��M%%�|���MO�݃d�F��i4:z�JY��\��n�'�2�=�j4�LGi4��,��aUנ*V�A�w,H��MA�}�M&ó��a�J�d�v'��=2����g�t��=�\�V��)A�ۭ�h�.�����z��$.���&dz��%$T��.�z�W����v�Eȇ�U{���+wmSX{Z�,�A=hh��Ġ<0f��9M�(s�|6�ڰ�kh�r����p�M�B� �}p�a4kp�p��IQ�a^�E�
jα��,,�kX�!�S��f���!��.����|V�CJy�j[8�́�rt�/},lo�|Iz�,���X���Ǘ����X�dW�T��<^r'X�����e���4��|�A�8�� e11��C�n�c���Cm����*4;c�!��{%C$��{K&�PK�� ��������!4M2<����$laiɃ-X[Kh����x6��X,�ũ�.Ɛ���\	d�~�����|�`omn��������p?!49�>!�gQ������0�V��*��480���p1F�$jM�O�8�/�a@���[ؤ6��l1�F�S"�ݾP K���7�	C��C59�ݐA��od@&��R����)�����Һ���"��$�OI�A������PS�z6'��-B2=X�X�&�c��v��}j��-B�����1a0��و�IFXF7��ca�?0f�;4��(�d�Zx�, o���&��������L�d���qo�!ʀ����[%>d=2�5CX�92b�p4��>_�ubboOIǰ�.�"Z��.�� �U�_|tUnV�lJ3��^:<�����ݭ�D7�	:V66Z���pDx`��(loo��ZZT8&�A���<�0=�4�z��2��/�![ �o�'>��g���N�i҂����_�H2�U�fD�����Q65)Gn���qd�ѻI��0]�a���c�n�|~넇|O�墧QC��1h�1tuE04i�fmk����|���i�\���aN�ca�1^"Z@��C�Dޱ$���4�NZ˂5<��10L���?�����^M��'p{�;s$��KTdiE]��a @�M$�B�L;�{6Ӵ˛��ڴM���g��N7��gd([��A��[=�Ȟ��U�˗/��1���1<y�72�h��R�E�0C���� %G��%+K$��v/�g	v��;-�B���UT�!��K'�+�&�i�4�4	cpꍎ���ju45���9 ��:��2����4��Z��<��2�B(���1 ê	�Ҕ�H�2m���Ȁ7U#�0��=�A�YZ����- �W�'�0��	0�@��n��э6zk��a(�i�����m40�R	�77�N�#x37�T�,�a�����xj��bET�vЀ�L�$����<O��N'�'�ak��B������V+H�Iu��9�"�ٙ��k5�DL��d�S��Pq(�[�w��S��Y���ff&C�8�l�5�ǡ�ZZ���K&z��C͐�<��ƌA����XE���D�����Ơ��1�ʙ�pC���!����&�S�����Y��H�X�;R��5�.~���~�3�NP=� 
�`y�����ދ�e�O����u�3ӛn����;�
���B�8�W\7��Ib��jll�����T#�~��T����;^��G��ð
�L�4��ɦIC�!��!����Ҁ-�=%11�ׯ'�0$�W�/���l��Ta`H/�R|�h\(lu��	��~�pyy���w�A�d>ڧjQ����W��N	�S��A��n�������N���í�e�t`F�F?6���0ԑl��j�!*�T���%Nע?��������i2�x[*��Z��`ˏ��R�$�����N�l�%��av��ۆ�`��N W؍b���ĤW(�/ϲ˭�Gb�q�7�Vi�+�` �X�rl�>�[��������E���������y��C�O�P� NJ����1U�.����P���ePVC������J>2�I�,4m͐8?5`�L)������҃6�A�i�#��H� 2\di �4{H)3���dju��#4^��N5$'Ź�����j2�ݯ�H�K$ۥ-�a4Y���$I�8+D��3�ڠ`�P��#
�<�tj�fX%����{��':B���y&��(i ����A�5��g��9�w!IuR��CՎ엡SYw�ޕ�|�$��I�hG�2nܘ���S
[��xHm�qa� �4�`��fL����9�d��S*�v�*�0y��������B�1R�f0�r�!k�M&��n���<�|��B��Y1�~�� Q݆��2���1��J>-Y�OK87�>U�͒�s�O��_�������2"��$���� /�
��;�t��>1<��%�K�/	D�/Y�H1������$)��J�62���.{%;=R٥L��A,mv��Q<���L=�V0�8g���H���P�)ó�dx�-� �������"X��g�.�;�EW�&���_�3����xP���oظ O�}q��A�n|�'����)����e���b�m���/��dϝ���sze�Hw�BYYw�=9�VM��� ���!a����>��he(_I���e(����p�֬�����k�>����Aۂ�2��{l�zA�>�i�S��n��?ޤMʘ0l�u�.�ѤǏy����KFS�%�=��s�C'C��r;���c�V�D#�@�+���ee�֤��J�lk%M25�8g�M<ҤL I���,����2Hv�U��:`©[�iT��Wd�B'C�X��3����}�޻��1����R0ch�&��D�=Ȱ�~.��aʰ2��	�-�/w1�m�{��66c������Ё4�V�b1w��3�4C\JJ�A�1�4�4dI�W�WW����Q�p�|e4i��hd�*WM/ɵn��>o׸Tt�g��Ae�ou%I�\�~E�T��0T�Vw���I��՟��&��x�~.��d0[��2tt�N�`::hd�Cq�y���;?�#� vw�~�ac��p���q���k�ЪBA	�ؘUCC|�Z-p�_w�W�T�n�I��y/q�y�P)2)�2�'����u��/w%�d��-f^iR��Z�{������&Azt�zS�E�&IJ�V���F��0d��Y�T�CX���J�}�=W��'������X$ܕ�_}�̲�j�����(�BN�9Z�Iۧ5�0h�դ۞'�0���$����d��Rr���*�P�\�����VZ<���p�Y-����e�z� }��k��G���9e�d͖���ׅ�d0s�p����T��p����e��Z[u�$���~�
+}�Ud	��3��ph�!h��&��D���0R�cH
���q�S��R˰v��O��
�H�2�l����X>�y,�m` ���swpaj�g������i���@-��S��\��v��5Z��T���f{�rAP|�^92���Hg�<i�Ac�J`C$H���9H�YCѨ'�?�t�;�3��Ij}[=�~��"��M��ѣGj�#��ܿ�Nȓ .xl��o#Rdg{8��7y�.LM��T�pc^���-M���@�t2\�����[]C	=��e�o��5Δ�v{ee%�C	�J``/y��-ܐyњh[���n��龺_�_�ڥ X��#CMZ�`cd�����L���1z(�Tׯ���>]d8�M�����&b�ą����R��DiF�\�����A/�0f5��p��,۩��;~��2ț��#��Ki�#^*��+��R3CT��@��G�f\��zӤ�Fnd�k�/���������4�Si�� P�@�V��M�����~�>�uQ,>���w��4���M�
��,�g�&I�0�%�� �=a"÷~t206��a�	�b�ڛ��0І�2�Nx'�4#Y��l4j4Գ�y�npa /h(��F����@�WP���� Ir-��IZ"qA����/^�`�����2 �,u���u�W)k�u!��*Lր�Khګ#wA !6T�@�
��p��bv8�V�!�U�pPoP0.�����@#�е��]�U�,S�q2$9�f�0&�h�a0 �c@�!���f1G�>|��v��9��0;�/�`0����Il/)�qn�D���v_�t&����8R7�[ŐJ�v���t�a� ��"�xX,F)� ���cZ(��tf6��
������N�T9���^�Vy4!66���S,�~�EE�ahր�ge��D1���0h�%�zχ����!n�b�-8���$d�ko��={8o8c�6�'����YXL5v ��}Zv1m��z1�z�=��d�d8'漓7����ߗ�f [|�"���/J�(.�Fm,��5=��!Zj���h�GU��/pԆ��E��{��,uv%+�������(+}tu�R�o�����X�";[��D��$8����
��N����ᖖ��|��{��Ҹ�ѯ�)?CD�$f(���JeV�/b�'y���LDb��=w��mʃO�=����j�:]��=� � � � � � � � � � � � � � � � � � � � � � ���D���曛wD�^g���-��!�Q$;���=��c?�!y�\x�G�D���*=T���:�A�B�l��e��m��}��M��w?<���o�>|�%>�����p���&�lzE�8�jEvB���f&�H�^_�Է�k�Dś��A |	و�>X���_*iHz2�S����RHv(�����T��/���0��ό�+t�����M֤�ޓ���=�!���؃7�_P�����k���ڻ��.~���k%jp ��'�-���0�����|�gm�ɩ�|��ٱ��wd!M��Л�ǳ^�Lʍ l�nk��xWmcu�������×�?���Y�]ή�r�Y�7$�)�8�"�v��.�"�td����'~A�뾦�l5�Q��b���m��Ab%]���t���;�������_bΏ�����<{��㌌x�Is�ԊmzE`ё :�Л����ɜ��G�%ހ���L�'�Tk5�?��C�E+%~�*<Z��
����������+>���G�����BW,���Y̲.Z�z�CM@�Hc�9�"�/*YV9V�|Ea��`����i�,�'Z� NLP�0��'��ޫ|E�`�X�F�@�]��f�����ږ���n\F�e\��9�fT�;m^w{�����b'sa`��\Օ���MQ�0���ѥ�����N,,�5Y��2pò��:W'��8���_Eiv/�0-�CL��e�ց����ʏŠlX��)Md]7,�'0 �RN���Sgr��-y��u�
�I4'X�g�)��rئ�_T�@/����R�O�Pβ�� ßTd����_��� ��v�c�K���X�G�~⸦!�}W��:jX�p�����Ϲ���������讶�����
۾�Br\6'����@X��	b�Y�/m5��"�e�a�����L� TJOy�S�EOU��X߬�jKxާ��!K"�exV��:tN�G�l�,eJ�Xh�W}��@�v����Ճ���hT�z�gWaP�T;C������7��X(���i c�:M�\�F��j��#��m�!��Ε� �E���!�<�|�¤�sr(��gyBa��	}��1f`SSJ�mKM����p4k�u��j>0���o��0qZ�:B�(˞�v7~
M�Q�� ˺ب��ha�ߌm{%��Q\٘��w��S�~������<E�)G����I_��Za�L���,S���,d��d5$!�4A����!I����6�M&�5��%G��!Պ*� ��1�%�Q�L��e�IU5��ה䖚�ku&�>����w5�9&0x�6p��u˃҅�PP�u���P��S��$�\����w���ǩD��G�:��'4���)���r��_k!"�ޔن?�" ��`��T;cVڛ�O,H�^r��,�Ae����e�����ͱ����l�o����@�w0 	_�r�,�nF�.�Y�X����� �0a����C��=����4(�py}<���F�0��,ǰn���j�P�;�p3śSbt�������$L�3Q
nǃ���i��h����p0�t�E�V�2	��K������t�+��V���`�$R��I�h�в �,rM��-L�4��9��͞���=��`pM����e�K ~yfYNh��3yY�ÏE�2r����0��3��h��3d��Ԕ���6�=O/^E�L�S�T�`Q��@� ���7_��I�	.�hAo�Ӫ��j�Μ�{%Ιi� �y.��e�b:�qF}�+Q|���2^��x�(:��7��0�|^>��Vs�.H7���[ 0�0�,(A��@�i��W�&�󜸃W�.�*�P������if���':+h��m��Z���1	�ܽ���"��e���m�a���k�Pה�X��W�A`�
���Q�P�B��)��j��]]"����?~������J��BH���d8��~�#��d���4[��&Y�t�ˢ�xrYQ�Hl8��G�?����Y�f�L,E�Wa8*/�_(��1�c;k��2���x��5^����[v|�0�X �����1�3��0.��J�V��4�S�ǖ.h6%�ʬ��ӑa�D�~0�N��m�/4,��Ԕ���Q����sq(`H*!(˰n%�]��/�U��{?�����.%�d��#<'؎d8��m�6��]�?	�A��7�ê�q��q������Y�=��'�YLD��=p$	�C�����/k��&]֎/��̣	�VQb��A��^Y<;L����bA�*LJM+�TZ��T �dCg�bк��pRU�8"�4����r>��2��i֭vM[��6�e����ɞ� �	�	Q��!x�������'��S�I�h�M�2���P�&V�ϯ�0dXхU�yj�85g���[�*���R�.ڞ׫jJ�(b'C߬I��������Zf8VU�ݒ#���nLC7 ��˜���h@������8:����������C��M�؞�\]%���0u�K�v��}��@���hm���؆��{Ġ`C� 
d	��0�܃E�0qd٧a!F��z�i���!��hh?lC-F�W�H�"���70lb�b�����R��$�D�9��(r`�.r6��U��=��J�-\nO^	y�N��%������d���@�Θ�IC����I'��3�M*�ص����q A()V���U0t�r�����e?�K����ғv�/[���w/�u���È��������g1Ei5��FA������XÚ�$�Rgd�R
�/�%Ea��$�gHf��]��ʒN`0M�m�)��E��8j`�l�^�M�=[���t�r�.�i�Ԕ��r��4�����j��Y�F���8&�aW�$��Uvh`PBH��4ni��oV�	�R�H���cg4�\]S���[��� 9��9Fo8qtK�kh��|�qP����ّ��1�ג���!�h��mhB�mk��dӘuÚe ƈi�qz��O�2XX�Ih)�,�<��:%�%axY晤܇I<��eY�2f6/�,®x^n�̤�6�x�!d��Ԕ@Do�zO���}j��*���]��cq����S�I
I>M��	����֟ X8d��1q�U\K�-I1�)`8�#5tΈ}� [���o7KY�J������O��4�g#f�vZR�1������"8>SU��7-�ƞ�w�i���S�ek��㚅S$�ę�ar��H` N4��	���x��/�v�_���W�w�#v5%/b�S`O�F�A�M0X��#0X��l�Ɣ	�2�#�rC�����9��"��9�x���A@���$0���&N����b���������.ґ��J����e�N�Q��d�	(����/�c���ӈ&�������}�&�X�@>Y��-�RQГ�@@��n��@jJ�	,T0q�p^#�E�`�]`�T��!ei�) oJ�)��ՙ���j9�@"�$Sבͦ�
�D D��<}����V$x�a�-�}�^���zI��o�Q����0����;���]�;4�~��|�3hj��dmL�c�/\�-����f|1P�@e[��0$��a&7�pM��4����P����<�0��L+�m���		�Ȥ���n3��azw��E������ن���8N���ټ�kN����y�~���y��#pm�K7Se]r����R��4�`���Ƙq�eY�&��ui4��UO�}��>�n��Ə���>�C�	���"j����kUu$*�U���{��>�����0(xG�F8��֨�s�͑�a "�i�T�À-S�pɪ�6�M�'�e8Yَ��8&3��s�Fr]��rS��5����m9��!A�xVDp��mޤ*�v�37�Gu�e�y
�x�۸�G�YA��1v/�M�7r���'��2�a���Y�p��8F�_�[���u%�O���P��T�$�0icϯ-��C�y���fz�?���0>��ֈ�
���6�@�1{�j%�`�I_���e;�s���C��Og-����<2�f��?�uz<>�Hz���S'7�R�!�A����]E�q��yx0P�0���MC��{:����ܣ��`�;
޼�7�3f�J��{�W��N
�J����Jb)h	'�<�2�S�QF��f�^[O&BqL��Dp�bY�1�#6���y�W��h�?�<
�Y)
�����Ͼ��������묔������_��-?$�i$������Q�CA��c|�S%�V�_��C	H�0��1)������vҽm�.��a�ϚT��	��)���eZ�I� �Go�N��?�ߴ0hkeNT������vc��g��Ǿ�Tp��;���6A3�'�{�ju�=�Sr�\��fڃoI��;[�գ1�d�8���zl2���9+�N�
���K��i����v��)�?�Ǜ �40x���!�`�N�A������w9��]���=z�8�U,l}����ף�X�=)`�8�@=�S�z( E�LH��6Qf���tI�0��\R�a�yM�W�V���`����0<D^������	6��/���t��vuq}��?���s�M\�0�Ą�E数�6	�M�J��E�0?d�~`و�@�1RD,�HT��S�Iw���b�pWwW�|�]��7g�3��;����ܣ=�}��U�}s�<�^:'"�Bh���B�q)r�lIA��a�춂;����9i�����c͘�����:&O20x�F��,O�qT���h��\O����4ˑ�����R^dX�W��Ru�>��`��݇��*�e��E�1�i����hN�b�(J8��������Sr�d��	�2��&�����l��/ɰ���l���!�)E��*�Fn���X�ժ�
�Dү՛�{|�fV{�	���U��r�^�K���,�%{^�kx��&��ؙ*c��4�J6ǰ�B�.�V,�Ë�{~2#� ��X�O����k�z�.H"Iv&��;��z�~S%�i�SMW���Xѵi�r�m�\3S7��{"žc�e�sb��Y#��.�Do����z��9�Ѫ��8Q�,&��Jo?l8f����%���lʓ1��|�WΪ}��$_��)�O�8��zZg+�8W��28N�4�W�#��dH���YlG~�N�m*d��#�p����v$�6CY�ؿ�5/�Y~bs=�G��Rp>E���ab-.h���1t'Ӟ�T�����X����~%;������s��~���0���j5��[����0$ì�_SL�p���Jq��[�Ghk���مX�'�y�$�
�~}��G\�R1K�q4�R1Ǽ�����S��G��YJ�����cX�,��kh5&�2�Ijje�B�e5����1׿���A�'|�?��wu���i�7��2I�l�����͖�ƙ;˻V�_����\{�&�B/����jPD�ʵg�E�Nlej��V�Ǯ@#C������,<��O��@��"�Z���O8H��ڈa�\:_*���r�\�b�Fh�WX��*��a�D�ډ�Ygh�Q >o���_�� ��=<��3\��yB�f��dٍ��5;x!x{���d���We���k~!�k�Y��BS�K������9o�_�Ed�ɰO����w�y0eUQT�` c�K�d2�ոu,1��~���/l74}���d�w�2����|Ui��u����n��1޳�'E,a�x�UD"�<l�X�����2Q��1�[���ǖ�;gqd��4�^���|t����) ����w���ukܯqE�_qUl�W�����=��;��9~�#���ރn                                  ��?�7��r��    IEND�B`�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           �PNG

   IHDR     �   �;w   PLTELiq���}~�	!;=9~~~omp$@J���}yuda^___omj���}{{T[p�~sss��lfg���YN'ENE*>B���@T�Aa�EX�xnO�[[|||���[Z[WSC.@NjQ8(@L�gZYU1ris1BXEW�Vn�qqq�? wtPmIY���3F0j1$<K���BY�oin����dd&4K���EN]ZR+aaaYD*�U=y���O>heV��������������򀀀������������������������{Y�����������������������������ی����������������ċ��������uoK���rrr��������߱�����䷹���������%.J����������������񣣣��������𐐐[0G��м�����?*,uut��ɇ��yyxe^=������ܬ��������ê�����X��Φ����+<jȿ��՜���6^2����ȑ��Ϥ�����������������Ӹ�ؿ���������������4G1��ƽ��������������������ƫ�Ą9�����@����t����j<��TNOբ��ঙ���ذ������Д���gάE�����޶��_w�m�g��Ɵ|����w����ҳ�������<��;��gׇS��vޝ7��Mhz�ˑ������ȍu��ԕ~7��t�}d���FYq��]ϲ�濭�d�㕕ڷJ�ξ��^�ju�ss���h��s�m�ҺX~TV�����{�u����Ш������oB8���{�`NbA�   GtRNS *G&�3�Si~�;�N����t�f����ڛ��?���I�����q������횎ݴƴ��r���������*|  -�IDATx���L�g�F��Tm]����vX(�F� :�R'hV�
2���D���!E��e�Զ��O�I�Q��bG9��vI�s@9�n�g1b.��y����>o�,�����c�x������[�
*T�P�B�
*T�P�B�
*T�P�B�
*T�P�B�
*T�P�B=���L�o���f���rj�Q������[]m�����,����Rj��e�G���q_�x�s�{p����9����*��t�i��^���q1���o�S�X,���׉dٷ
r���"L [An�
``Ǎ�Uo���^�ii�^D��kD�p�DF ����UEs�z�'[��{��i�Xu�Z;��A���/_2��;w*V�|s������󽉾��o�;ȷ����JN�����ꪰIYm�l2��n�Y�� �k9䏒k6n�n\�J�W������<�#�-��x�wtg^-*ʛ����Lw�>��Yt0/T�L�cc/ ��0o�!�|f&v���1 ��@����$�` �_�����o�.!� rKLt#����;�~1I���4�K,�e�&�������a��<�^���Ax:U��� ��ܹ}�m?5s;2�T��
�9���.ܼh�����sǫ0?���ആ��Be�?ߕ�񢺺�����2˟��c��L8@� -��4���w|���|����@b�Va̮q"��~\(<�N�ᒃ�� -�K�Hh��?���Z���f��
�W�*���qq�X�@�#��>nkk|����dܨP(�F�Ba4{9z��?�����yy9�:w�?>w'ea�jLC1Rՠ/��7�]������l,\ }�ޭ��g`�\KKKYԾ���TjO���g�I_'���D���1�R?;�*"��:�\���!`A��Få��Jkk��R��V����Z��	ҮS�z���?5���-6j�J�ŢQ�5��C	��ݷ!/���C�/����iLC1\��°>�Ti�-��c���Y�ނ�q�(n�O�ܶbȴs8��f�3'v�1P{J����3QG� �?߳� �8�#CH��
LܭW�gm��19�MO���OF�5p��Xa����V�Z �xjuZ��V��t!���s۠�Gw���Y1h�a[ ��C;1��E9����"ԩ� b������n2��3���R�C���ײ� CYYYMM9,��k��ێg�5�en���TjJ¾3+0G�	zJ���S��zl��r=k���:�Ģ�a�;!O�1�>|�`�n'0�ʞ Od��J��<1�_+�	8N��<�^�S��j�\�t��C�#��ˊ5�||,�``v�ᘃb�ʳ�,�-��݂L(Wlnؾ};)G�z;�Zm�2����I��11����D�˧�ꜛ�	Z���L�lO� AOi�h���q����u�X�ӑ1�'&�v�ѱ���N`�d���0�1j�,��12x� �%�V=��G��z.q	�.��z�Q#Ā�`�WMitG�.b�96p9�����`�u���0�!;�$`�ڵ�j_���IG�[-�W�L��L�`C�3�f�/����,���vN]WѰ��%���p�}�B㿙{�����H|��=�N�f8&���I&3����!��`�bddd,���� ^�o0P0p��O�U���yQ�<���ú,�A@�dTh�����A�R�h�p����!��̳�lg�䜝���Q�D�0qH���CP�6reR s�ƍk��L�������
0��s)$i۶��K�� ���`���I���@�n�\�$��H�0������S`{J�)q�	��,{����z1���v��������M��0)�O�C�h��X��6��o
���r(��r-� ��z�J�]KC�0W��(�(5De����9}�¼ a(객] ���X��@
�/� 4�öR�Qg�p�­��R�L�=��P�g�������^i�5tGEe�4�{J	���U�A��<�� `�&Z|��}'��'�b�!b
b�m�*�֡�!`{]	�[�0���
�PJ�MZ��bP�7@pP�u�U��=���2��V���ʹ�A�p��>V��*��s rs0`�B�l�R���P�昩��r��� �D�]B�P�4e���� ���0W�uFC]s3�S�KO��~*)���?�|{��Dt�.�1\�[�W�1�
F[����Fa�,x��A�TXJV�~7Y:��t�-�j�Z��j�wu�x�]@��V�nG��4����w�X	b���q�cU�_\������\�B���{2�x�)������<��D��8"00����o��T��:q.�'�
��y�0SxH��Ihf�I0�j�'ވ1���6���p&5a�����_�|g�,!�[0��>��s@��f#aj�
{���0$��o�zp���"͏!�*ퟚ�/�B8��Y��j���{=�t:�S+6��.+/ ���g� P'U@X�#�O�;���(膓'!�W��k \@c$B�D�8�c0J�4�ϩ�wu�Y�5����������hi�|	Cw�t��33�Ҋ��s��SJ9�v�����1@R@�m2��)t�:<lo�F��:�m��u�5u��:@z7�Qn�4���9�(��NZ�v��
C�	���I��u�����^����KZ�����%��{&������}?����Ν;���o����dIL�72��'�p�s�_xæ�����2͡��^�LMY3�R�]��Ai�8!�Ύ�C	�c��7�u'��=��#����6"Q���f��P��ȠR1eP�J�U�*L�򪡡�J
p�p+n���!8�xk!����1t��*�BVooW֛��O�'�b�����a����j)�z� �;>x�aCQџ~��<���$���3�9����H6na��(�m߳��e��s*0lJ�����C�	����2��XP��8"�ݎ����0�I�5|�M\���?�t@ѡ�@����FcYJ�X�2$ޯl����wXݐ�.܂���i�bz�E�Hܛ���\����Lˏ�|H>�����VsK�N2�H��&e�={�g×E%��=�q2�%��09�M��"�y�ق'���&���wq`�� Xv�#B���L69�BO�V��e0$���dG2F���N����@�$2(#�dha\�~2�샴ctWUU5����fo������B��Ĕc^�4�d(��عy�N��b,�]��t�t�!L9�g]SBiҪ�dH����d��e�����(Pd�#�����'��0zaB6�V��jRq�zJ�n�&����<,C^��h�&��8;�G�#���x��	ȀCC�<+-�n��
������_��ը���p�B��&N��a��oˆ����&y���b'.ϱה���<�fʀ&M6��U�z�X�z�;1����z�Ck~�!�fB�i*&���MMM.Y,hm1����u���:�Now���ٺ��'�B�9�9��e�4���)CY7@�3�K���/�5��އܭ�/Ki5.S�5�eJ���/ȃ���&���F�p�;	I���/���1G���2A|pWS/~��G�dt&Cb����:+��m��ô
�)����Y��u��ɂB���;4ԋ~����*�&��Њ��v�8�'"�����nU����A�]Yz�#C;L���ɜ)ˍv��ztO�n2T��1*�	�]��n\"C��0�n.`nϑ�&��S5(��/��IV�]�E�}ʐ&P\hj�6��U�Bs�Kx��n�M:��d6��Qg���H�����due���SM�#�g�<�
�R�P�}�Te�a�}ze8C � �^�J³�\S�&���Z�_Q�e�-�Q=z�K��ʳ���ͅ�e�����x�ө�/Cc#D���ڃ�kwܼ�Q��TkF;���I" ��**���������d�Xtf�a2�,��`��A�����N��sft<!�D��J��4�@.M�r��!�j}@:�����l�.Ni�ӊ��Q<����v��{��{� -�����sl���ᶿ�@�NUߜ�� �!�h���j�k�� ���Wo�?0�F"4�TP�	CG�J��b��t	�Gh`v�Lr�F�\$C�s �w'�P���@�Z�*���Gp�nC�a�����6��� )��������1�z�!pэn��E�?dm�gh���6puA���U}]�2#���T%2�jR}=�����v�:]����5�ₗg'՞�@D��(�h��<��Z�(9�љ�nq��������ǂ�����P^'
����4Ik5}�@�@�i�0�0<�n�v�@.�{mĝ\]�2\�ʔa$�1�NURO�rj��SX�c ~���o�R+8�A� X^��ij�E@&67�Ը�\O���A��l�~�G�q���t;G�I��OA2�^���/J�׮]�H�A'��qi��X���99��!���2 ��+5g/#Ύk4�2]�]�}�t�*%ö7��e���owՆ�g�̥F��4��B�:� �2��@r;a6��(I�Ӊ\�U9�PΔ�AY9��OF��L�ո���,���S]��3s��1�1d�&���%�M��H�R3>�̎��^)QGWO�4D�c��P������.�ʐ�֖���eS(!�� X��6�	��� B�t��NoY�N� 2l�W�J+���4Ѫ�pAH2 �2�ڪ�jz��Ik��.�t��W��"�f�N^�����<QPf�c��	���ޫg�p���0�"�o�-�ȲI�� 9�J4o�S[Š\ɰx��V'ra������AZZ� �R�wIqZ���ҧ� 9�z���#ҐDs`2h~Q9T�ʅ�.��28zzi����K`������5��l����I:j1.&���$"���֦oc�A�U��v �Y�Y@P.+�@���@:�z��C���(C~�⋿~�խ[���$C�r<t��0��굱Ȱ�h��%��^2e��_	�����gJ.�v�����ȅ�T??&εf�!�#��(iB$䐲�DQ� <J@��������嫗�@,F���������GF¯8�~�mF�?,�<���XP��?"
!��&�۞.sΐ7�ק��,B%����ڀW��ߔ�{��N�����+d�$4�֬g߉_!D�UTx>90�A�	E�'��J��.C9�p����@�f$@����Y�t+���7�ީ۷����%�lCIa췪�x�_�2��y�p�kJ&���&̨n����Nt "��-��qҜ��ń���i-��3/���KY��w~�DCc��⾛��wAB��V�D���U(VǢ �¢�[<�0<�y��@7�(�!�/{���w~�i#k o��0dS�VG\���䢗��U�l�cc�-�ŘF�H�d[��F\E�)�<��D��M��4<N�V���"+C����o>��B�P(
�B�P(
�B�P(
�B�P(
�B�P(
�B�P(����V�v����z�mо��O��nt4eeܪ�T.����v���>/��3����^��0����p���k�l�s��glH�'��/V����u�����P�{��9m��??y�jv�۬�A�Z�V,�֏�E}�� �镽�3�c��3���Y;�e��U�l'��nhY/�����,_X����� ��-�xe�؅DP7�����1'�ݝϻe��D�	�NE6�j6��u:�b �kg� �Y�°n���bϫ�f����~����O�]�z����N�r�Y�g��gl2.;������S�e�]`ٗ�Pk��i�n�m ��<L�I_n��ׯ+Ȓ�i|h���<k�?�0��If���TuW������;�ˠ��]������FED��gK��Z?��I����@��|�>���_q����,����[M,k�԰}�\�ܳ���3�n%�{�G��s^lC�0�%4잽j�����ay��A����i~$��&R��c=1,+wo���\o�,�nt����f�u�T��	�ɐ�P͆��J�:�z�!���8q�l۲������v�Z7�v��u����W�H"_��ީ�\�����H���dN��iҨ��pXؐ.1���Gƌ�M/7Ca�~I=�[sg�~e�ʞi+
ćbڶ�ʀ]��'����E����\kZ�T�aE���2(������;�E�Iޮ(C���T��Qc���6������C�~��M�%ղ��r��i�{U���e6�5�8)��,�2�:H��b�kU\X.�Qi��3�4ts�n^��S�;�3-�k�j4&;�*�Z��2�6T|@S!�� h�����NZSj肕-S8��"�܆G��F��2�9��L��XED7y�����o	�Ήmس!/�}�Wq�`J:)��^����Y�NlxYi��گx��������Y��"S��{'eП�\�f��#~�,�ȅ����ޡJ2t,��<����Uv��QU^�Y�1*�~��-�$������W�$2`p���d��=h.l�NIM�>�n㢇�: t[=0+dx�_�J����`W�B�����y��7��]��Xσ6�exHǏ�^�ε7W�@$I�u�&ߍ�4���!�ϓ���_��J �Y��Na���2��T�tOm�������ia^�V�a�*���V�ƫZw�ZI���32'���
�2l��gbAAFv��d�S(�QrcYc=Ԕ�%5�+��v+dgߪI�0 �a�c�����iB�xT5M�.�}1�{'��9jq�H�~���͕��1�|����f�Ox����|������J�U�Yj��$`A��g)��?�9Y��G��X����y��8�$õ��|��/��P3Y�U&� �2�8}���0�qC%�A['�g@ d�X|��D��8� ����J��5%��%ᮝ��Uv5�`�`�E�ެ��v%�8#<삆R2��4e�l��+Q�4������b	��Ir�")����N	ע_}�����_+udǂ�<!�G��I��5��p�rw2��2 gO���?���.l���I�؞�|-�7'�7ge2H�����`��,���/��[2#�2D�@��4��f�̣r���)�0�\C��)e����W;��F�f3�q-fh2�9�2P�|GXc�xTE��#ͳ'��]0aFd�Gf���1�#�I�B�f�N$��݁��A�&�"\ʒB���:���l�g���X�ũ��D�2M�n�w|k��p�B�=����^�����^�PI�/c[d�w��d�(��2��"*��eŲyh�0��,9�Ck��  J���y��4la���|�� ����jJnF^_]�~�|x$���P�B"�$�XFy��6����:�T$-�w6£K!��O�z��p�� ���6*/��c��f�o[�G�k��s��$���<M
}?L�me�X�0Z'�s���B�CId)&��K�BdOF2.����lX���,;�F�#|V�uF� Nc�۩ps8l���4a�O�$2�4K3 ���a�4.B_.Cw��۰DrQS:�5%2E��=��0�A`�Ek�-J���89��đ(g���r�N'�V+�tZ��"�(Y�ze�v�˶|��.�
ɖp�� b���x�y	�h�lhH�'Ȗǉ���:��T���IY(��Jbj�,��2ue�O�L���ݼ��8�BY�5՝���\��r�/O�:X'˰�(�&��$�$4����oϿ��S�˝�ƫ�b��E!���h�uё�xQ�w�ł�*D�Ng��]^�9ի��g�Y�&��G��j�$Ė��B�����<�&�
{iS�H���,&%�����l��O�kJA0h*���j�L���6�*�������k����hI��D�@P.�.~{�� �u���!���ܲ.����;�F2�݋�F�D�.��0�o��6��S4*�8p'��44�_�T3����70��.1d�p�u�. ;�:@�� �����	g��&3D\��hh|X$.��,$@�A���+t!�kȷ��l6��H� kU��e�$5�û�VnhXה��֔�ۓ�T���,s���	�&R@m-e�\�<0о3��]�.n�������D��D^..��O�u��K�4�/ ���e��q��I+d\�s�N^I�������H����*�V/rg����Cl��n�XC�,��h�|lQye��C��(�AR��F�1�����Ima�{��B�u(�i*��0M�$)(��ֳI�����a�[�R��9�)�p�@�o��-�LTy|qȂA�fR��TŶ�>�3��*����i�*�&:$2��ãϗ���g�A���(�����>ǚkȐ��ָ�Q9&8d#W9"GX�3U�;[�O0�M�I�Ů᏶5�d��\��@�dK�u�ޯn �����	6�|����˗���W����a5$�%a6��$���*�"Q�(�zb���{a�;�uliH��Z��Hǀ��{E$2^�g��P�(Nm�Bt^��ɤ��%MSj�������Q+'���c��ȼ��[���� MM���p��L��lbO�L����X`�g/�[[naXB�1�q���e�F��F#�3�c}l��OFhx��ƕ-�Z>�x�������!�� x�8�0� �b�Y�N��W�p�Z�HP���`F�w��;4�kJ*P�>0���v#VetX0п,�`�k��$Y��	tF4�J����������T,I�BCO�F�laHdR� 7�{
���́��ˤoK1Hf�>��7���/����d �L#����z�vۙN�N����}�~bą�k��k΢$�MY�:�0�ؠ(�UP�@3����UF` �2��j��Ç�����֔�=X�`0�0�A7JU&Un�)�y�V�j���z_�d�z�G`8���F���8�"�i&I��`�s̑@�Ԉ<Q�<~��8E/2R��@�7�ܚL��{c]Ԯ��,����})�nբ�F"+ϕ�����
��0�-V���u?�#�����ᇭe�Ɔ�	�ώ,�Y�<�Ltre�U�q�������S��U� ����j�p@��AH}S�`T��Ñ�(����O{��yȰK@ K��I���.m3t�<դ�#�2%�=�iq��@�G�L��ꗎ���4U�����Ta΄!k���A����8��&�E�AQ��|v�'<�����i��w9�kK��u�A��:���ф��n{wV=��H\0���.�sO5$�����Lh�D�n���A�]��c�@+��zR��
�.�*V�H��zp��YZYu\+I������N�IE���7�z�$�z��=�Z�E��tS���n'���WlT�w,H�����څq�!��q���쯠]�`��g\��뚆_gs~}'2`�L��j�ztV ����&q��#Z0)BO�ɠGWȥJ=f}�8m�5��X8���waE]6t<f3�Zi�5Qx<0�SP�-�2utd�s���$��|iI��H>5�)������n�b���{N9������s��o`0=ݬ��X���`��`8hߗe�~"v��{�����z�04{BQM =t��\�{ɤ�����+0!��"K�.H@�$k1aht�m��=Y K���0XQ����Cs3��N�	��r�p�� ^��y�Λz��������N��I����j}��34����n�m�t pL�x㻋$�;4�m�������9���b��Fw{R��z�wC}�ԛ���N��~n��^��+�����"Z�UT�ɪ�mEhKR�D�^-M3X04:�,^Ѱ/D�9N��a=f� ����꺴Z�J��X��04z��������$����
Bt�"f�W�f/��ˍ&��U迴�
CH��M1�2٧�| _b��̴
�+3��'{r����L��曯������Jc�a0���G�+���L�/U�N�T$?��MS� }�3�M�����OM��P�P�<�/d�đ�Q�O���ǜ�Q��'-�*�s��06D�/Uz�?�^�e0J8ڿ��1W�t���{=��yJ�Ό�. e٘ʠ�M�,���O;U����iݸ@�@�aK�����eb����X�L�E��CT���N>:�S�d��,^����͓����r}��~`�2�)�d��ȠtNbJ[�^�atݰ�;� ñ�	z}7�쌻by�/^�1y�!_�שd���tbˀ\�՟=��,�7���
�k���My*�ms$��2(B%��N��H��7��*(Dv��3^�f�Z��L�Ep�Ł@��ӫlY��x\�}�L��m�%v�FW;I����\
�ƌ34zr�Y܌��l%<R,bF#Î���K���W��aW�R�S};3,\��$`�zxI!����~�?}�?O���T�r�J?�ݎN���͝�L"��ƌ=M��2�(48�Nզ1���K��4��u��pQ�p7���[�=6n����A���јyPE���~�t�6�2��2��aJc�Đ�(~���lw*^&Ew.i��q���u[��vf�i���*��E�4��㠕R�I�i�=���n���R6]Ool�/^�ؙ��u{��2�8�Q_ Vy�%ɐ	��0�$_5�u��Iq*x[E}�R1#�W��ŢfY�P�ُ�<�jX�G,���&�!������jȓѝE�!��+��D�_D��\�2D�2�]�����|Ȼ�2t���+�l�ͩÞ~N�L�G�^W�C�wײ2���.$�_}�E�2�p!SV5*"6��a��>�eC�?��Β�20��V9 A���r�V�cCO2<e+��{<vx�S���e���<f!�_R����퉖^;_��@�F�o�M� ��`��Jv�g:���̘�r/遙�8���2�U��t���N�E�%�����gO.�����3�(C"�������j2���E����(�ʮ�o��M��Ly�����_��v�R��0��3��w�ߺkx��K�����>꺎QD�M��u�=U����L����^X���$���L�F�G�Nǖ��}o �B$�u��S��[M�q�]9¶iB�Õ��=���#ɰ��Kb���LA�e-<l�x��#E�t-<�q�l[b4�Á��;��T����=��E�e�S������Z��7�_�vsc����Ǘk���#G��-(����z�F�X,n����4�.��3(~ܱa4���m��]~6�#!S٭�b1�;99z��;ҿ����_�AdM���xwD�=                                     �)�'��A�~�    IEND�B`�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           