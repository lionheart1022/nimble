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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ‰PNG

   IHDR     €   ‘;w   PLTELiq#&26=a©@R^GQZ| ²JŒ­<oP’³AWŒT“³&8K_—³M²V–¶E„£R”´?a‹=eŒ_ge@y•?c‘mYGqq^S”µ*>B|X],@Qš\T@^ŠA[sS”µ(>KXK%#JJ^X1urQAI:]V4'>P)BJ‚zz A!3F0NW`~k^G†¦XE*g1Œ}gNWN.B}›c¼ªon[P-ºÁÄ|†–“~~¢_\šÀÔ/#q§Â‘aa€4t|œÿÿÿP“´ÿĞÛâíóÔåír¨Ãéòöóøúîõø{Y±ÏŞÆÜçßëñ•¾ÒœÂÕªËÛb¼Y˜¸ÛèïÑâëÜéğ¼ÕâÍàêÃÚåıöÓG†¦øúüæïôïéÊÊŞèûüı¥ÈÙŒ¸Îµ½õ®ÌÜ¿×ãúÙşµÑß^›ºunLÅûşæ¸¹úğñj£À¸ÓášÈÊ%.K‘»Ğ„®°Øçî×æï²Ê[0G‡µÌ“ÃÄû¥¥ıÌÍÆÄÅ Å×¹éé³¿âˆººÀØä²ññìëíıê®™¿Ô»Çì¹ŸºvªÅáàâ›™“ĞÅB+/ƒ[{®ÇôöÀ«–­©§ ÄªÇg`@œ§Æ£¦³ëëÉŞè6^2ïİ¤ÀççíÊÉl«×Ö×ª·Ùssq¨­²¥±ÏÄ»†‹Š‹ÑµÒäÊçÔ™’¿€nûÀÀßÂáïĞòßÓš4G1“¹¸ııÅÀ©___ÑĞĞÍ“}ÛÚ·•±ÖÇÓú´Öş©;·¸¸³¯,=`XKP×¹Û}€ã¯ªØéş¿ÀŒ¡Å.@m¹›?nhş¯¯õâßˆ ƒj<ĞÎ­ÊÎtÚ«œV—·¾¹¢`w™È§C¯ââ£ÂëïŸŸ˜ÑÑ×ÃÚš“cÁÎó×´HŸ|…Uo„ÊŠŠçèºèÂÆÁÅgöû‰êÃM¶¯{ØİvŞ7ĞMôØĞŞ’_–7ÅŒ1~{J§¢qå¦|¨¬^¬ÊîÒ®´°oPéÄ²x­r¤Àá¤ÙÚioX~TZŒŒ«Ğ¨ ÌÎ“ÁoB8ö»“¡W{,¤ù   GtRNS $1F%ş…ÈæZı?âsè°»K6ûıKŒ“®ıÉıişïáüûûÕk¶”SÍ€í·m«Åq›S¤‹ÄŞıà\ş”Ì~ş¿ê‡ÌêËQÎ  -IDATxÚìÚíOSYÀq0][cš¾2%¥H€ò¼ÖÁƒ„ÌäÆ£ı&†0&;k2J‚!Ò¡Ù61m%RE2µô$ µ˜ªÈŠ²D(°yØ‘€[ bx±“ıs[¬+ô[wÜä~KÓK¥h?=%!‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹‹u‰ùR‘HšŸøëú®œ*üœ{ì¸ş«ñóESŞĞ ·s8Ÿÿk²Ph3wæ¾ÿ£I2/ï[¦äÙÿ (H³ß%•X##ñÿ<iÆÌ İ”ôÓhønŸˆoœš“#¦-œ©Ö¥|Äwáv¹ÜL_“R(Q!Û»/*Šÿ%«¤2=½²äòr•R–³¾¾^ÆôÓ&V|EĞWDÓDé®z§ÿ];ê])ÛùÊÈ‹#aa0d±·Ûí!Ğÿi0œû b©­¶–€ÃÙ36]<Ï±ËåB—Å:]1Ó×Êº£Ÿä,-Ü½ËNÃ¡¼“'óğ‹*¯²göñãÙJŞ'#À;QzRVz"…_t››ú`Yì_·¤úlÓ­–Ücnv}¿&ºşşúì\–NŸW_š
©Ş,ddTØ,ƒƒÃP‹¡æ\M>ÓÄÀÂÒh(4˜›l]GˆØÜb.çD±ËM!nuµŸih_x£r–ÆÇYiH‘õúàIxLø•‹½«½‹•lü>Ê@¼£qL'se÷¦ççÇÖd¿İhvê½³y%'&E¥=0JÄ|ù»~M‡æıú»ù,-Ä­¡8c&òKV[h°“à`ØûX5Cƒ‡jĞ)dÁ3±ôĞªS*äÄV›Û­­>OQgÜ>¥Û0”(ÊPÂˆáVŒØa@VWÙhÈ:õzg0/!áTy¯ÉÔ§Şâ›''MõCõM%%³|Œ¯MOİƒd­F½Ãi4:zãJY¬›\¦Ünó'ê2ã=ìj4èLGi4»¬,¤¥aU× *V³A‘w,HĞËMA…}ğM&Ã³ª´aŸJÅd÷v'“Å=2€…ëgÂt´’=¥\ŸVéó)AÛ­ôhî.­¶ËÍÃz¹ğ$.«««&dzÈá%$TÎö.ãzïW’†ÂÎv•EÈ‡„U{§Õ+wmSX{Z…,èƒA=hh¦ÄÄ <0füÌ9Mƒ(s™|6ÚÚ°†kh­rÕÀ°påMØB‚ £}pa4kp×î“›p ¸IQça^©EÂ
jÎ±À€,,kXğ!ÜSˆ•fÕê·!¶®.²Ú|VŠCJyãj[8¹ÍÍrtÌ/},lo÷|Iz‹,§ÃáXÑ†ôÇ—Âõ¦ŞXdW©TÉ<^r'X’ØìËğeô°ğ4ëÚ|ÍA±8Øì e11øŒCÁn cŸı¤Cm¢°†*4;c…!³Ï{%C$’¢{K&ÀPKÿ ª–ÃÄÀÀ³¡!4M2<ó ³Ï„Ó$laiÉƒ-X[KhÕçö”x6³µX,¦Å©Å.Æàòù\	dÜ~¹ÜÁà³Ù|ñ`omnƒ…û“Ä†•Æp?!49–>!ÔgQ©ğ„‰åÀ0¶Vú›*›ô480æÄÂp1FŒ$jMàO¨8…/ğa@£–[Ø¤6ál1ˆF½S"›İ¾P Kéû 7“	Cõ‡C59†İA§‹od@&¿RÙå÷ï)¥º­©èÒºµõê"áİ$—OIŒAî÷ËÃšÌPSŠz6'ïƒ…Éî-B2=XØXÁ&Ãcàó„v»½}j·Û-BŸ†¼±é1a0ÂÒÙˆ¦IFXF7¯óca¨?0f™;4†ÿ(èd’Zx‹, o‘†¦&–ÀÂ×ßüáÛL¾dø…qoµ!Ê€òíÈÈ[%>d=2à5CXÄ92b°p4øı>_—ubboOIÇ°.ö"Z£Ö.¥™ ÃUÔ_|tUnVÚlJ3şŒ^:<•»Ñùîİ­ãD7É	:V66Zó†îpDx`Ÿ(loo·¨ZZT8&òAéºáä<²0=ö4–z£±2ÂÑ/ˆ![ ·o¿'>½ôg³³ğN»iÒ‚Èöà_ó­H2ìU…fD‰ŒÆÃİQ65)Gn†qdÑ»IƒÎ0]ªaÆÀûc¸nğ|~ë„‡|OÉå¢§QCùï¢1hÉ1tuE04iÍfmk¼ÊÉû|ôôiø\‚²ŒaNĞcaÒ1^"Z@·„CèDŞ±$¡€Ã4ÚNZË‚5<¶€10L“Ôõ?„«¯ª^M€¡'p{Ÿ;s$°…KTdiE]ÂØa @ƒM$ò†BƒL;«{6Ó´Ë››ËÚ´MĞĞğg†N7¤‹gd([ÿ­Aç÷[=È‹ıUëË—/·¢1¸›È1<y²72ĞhŠŠRÄEé“0CÂàƒƒ %G–•%+K$”Ïv/âºg	v“’;-B°€ôUTô!íèK'á+š&†iÀ4Â4	cpê²ØÔju45†º¹9 …º:ØÂ2µ¼µŒ4°ÙZÍÅ<°‰2ÀB(ƒñÏ1 Ãª	ŸÒ”ËHà²2m•†ÈÈ€7U#ˆ0ä¬ÿ=¢AçYZŠì¯ú‰- †W¯'£0˜«	0Ü@™å·nÉñÑ6zkµ“a(‚iÑÖİíím40€R	©77ôN§#x37áTù,a¶üó“¼xjÔÒbETí£vĞ€¦LŞ$òôØô<O«ŒN'š'á’akõ¢BııçÃï÷V+H¦IuŠº9ğ°"ØÙ™›ƒk5ÙDL”ÉdºS±°Pq(Ó[Âwßè¿S•îY˜ºòff&CÊ8¥l¨5…Ç¡†ZZà™†K&zšÄCÍò<¿ñÆŒAÜú·ˆXE¿¿§DˆáÕëŸÆ ¥È1ÀÊ™ÆpC‹Öô!†¢»ã&ÚS¥†­ô¥Yàç¬HïXÏ;R¾ˆ5Ì.~Á¼ç~ø3¼NP=· 
â`y®Âë‡ÏÈŞ‹æeÍOß£‡†u£3Ó›nŠ…âÑ;
’ôœB8¼W\7·“Ib¡‘jll¼³€»‡T#‘~şğTßèèÂ;^ï•ÑGÌŞÃ°
üL«4ø§É¦IC!‘!¥õÿŒÒ€-à=%11†×¯'£0$»Wò/¿ïl¢÷Ta`H/ıR|œh\(luêÑ	½í¶~âpyy÷÷ßw—Ağd>Ú§jQÙíğôWµŒN	…S£ AõÜn‡«û’ı²N¬ÍßÃ­­eİt`FãF?6†ºƒ0Ô‘l­ÔjÄ!*øT¡†ë%N×¢?Äè›Âõı›ói2½x[*…ÄZ—ˆ`Ë´üR¨$¨üÃâ‘İN³lÙ%ËıavËÅÛ†˜`ÈîN WØb³ ÄÄ¤W(È/Ï²Ë­ÀGbÏq¢7ÁVi­+Ş` ÄX‡rlû>ï[ú¡ïó¶ÓìûõEùñúöı¼ßï÷y¾ÏCO”PØ NJµœ¶1U¯.«Õì’åPÌóùePVCáĞğõ×ÕJ>2¦Iì,4mÍ8?5`ƒL)ƒ‡ÿÙî»Òƒ6ÛA®iÙ#¿¼Hè 2\di Å4{H)3ÕÆ†dju÷#4^¹ÂN5$'Å¹½Ğâ©Áj2Âİ¯µH·K$Û¥-¸a4YÒºñ$IŞ8+D‡Ñ3çÚ `¸Pÿ#
Ÿ<¾tj¥fX%Ã·™Ï{áÆ':Bş¦÷y&§ÿ(i ÷‰ÁóAü5‡¤gÛã9íw!IuR–”CÕì—¡SYw¢Ş•|Ó$ÿ¤I‘hG“2nÜ˜ôÛàS
[†¸xHmñqaÈ Å4½`‘áfL•ŞÁî9ˆd˜›S*¡v†*º0yçí÷³ÖÁBÚ1RÚf0ƒr®!kÆM&İÜnçÕÑ<‘|¶şBã…úY1·~øÊ Qİ†Ğğ2·¸Û1††J>-YƒOK87>UÍ’êsO‹¬_†²»ìĞêİ2"ŸÑ$ÿ¤È /
²Á;¦tÿŠ>1<â­àÂ‚%K†/	D†/YêH1ÍÒĞ¾éì„$)şJ—62æôÊ.{%;=RÙ¥Là–A,mvâÅQ<‘ÁÑL=ñV0Ì8gÔû³H½ÆÈPÊ)Ã³õdxÆ-ƒ ÓÕû¬¢¢¦"X…Šg½.î;´EW²&º–Ğ_·3µßãùxPÖúîoØ¸ Oˆ}q—A“n|õ'¬²Œ)ı‡«e€¢Éb±m€‚¬/Øé‹dÏôÏæszeçHwãBYYw·=9—VM¼œÈ ×ğ’!aÔíßÏ>’æhe(_I“ÊËe(§‘´p×Ö¬š¬«©¥ká>³¶óòAÛ‚Ñ2¨{l€zA®>¼iÒS“nŞî?Ş¤MÊ˜0lÃu.Ñ¤ÇyËĞœ½KFSï%Ç=¤ãsÔC'Cîğr;ªºÌcÕVÆD#¨@Ğ+íİİeeŞÖ¤îªîJlk%M25æ8gÖM<Ò¤L Iõşœ,¨Úşû2Hv¦UôŞ:`Â©[½iT‹‘WdõB'CÎX¿É3¨¨}ğŞ»­²1«ŒŸÌR0ch‚&İøD†=È°Ê~.€ÓaÊ°2Ñ–	Ã-Ÿ/w1ÓmÉ{èÏ66c¶±„ˆİĞ4­Vöb1w­é3š4C\JJœA£1û4Ô4dIãWWW²ºıŸQÊp«|e4i•·hd„*WM/Éµn³Ë>o×¸TtŸg ”Aeéou%I»\Ç~EÆTùÉ0T½Vwõ­£IŞÑÕŸÓ­&”ŞxÉ~.¦ƒd0[¬Ü2ttØNÕ`::hdCqóy»Ì;?á#ƒ vwº~îac›¾p¤ªÛq¡ª»kÇĞªBA	ëØ˜UCC|ä Z-p_w«WÆT²nI¦ây/qáyšP)2)—2„'ƒ¬¿¿uü/w%Éd©§-f^iRÓĞZ‹{†šøÈÀ&AztızSùE“&IJõVÙÀÓF†é0dèèYéT½CX‘¡ƒJñ°®}¸=Wğı'´óÎŞÿ¯X$Ü•µ_}ùÌ²½jÅæ÷ª’(‰BNº9ZIÛ§5½0h´Õ¤Û'¸0³Çÿ$¢”á“õdø„Rrƒö*ï­Pñ\ë¾©ìõVZ<§ùíp€Y-æçòâe²zì }šäkáöG†û÷9edÍ–ÎÛÀ×…µd0sËp‰ÈàíTõÊpí—¸eèZ[uí$õŞó„~Š
+}Ud	½ø3Îèphš!hŒ&£ÂD³Æá0R´cH
ÔÎñq§Sí¿óRË°vÍÀO†Ú
•H°2¨lı¦ƒ»X>¶y,ƒm` ¯ÈÀswpajŞgÃÔéÌàëiÔ–Á@-ƒ·S•™\¨»väÈ5ZŠşTİÔÎf{ÂrAP|Ï^92ÒìÂHgÈ<i»Ac–J`C$Hõõ9HëYCÑ¨'ß?ãt;İ3™Ij}[=•~Â—¡¶"÷Mé“áÑ£Gjõ#ÚÚÜ¿ NÈ“ .xl©áo#Rdg{8ä”á7yË.LMş«Tãpc^¯Ÿ-Mğ—áÛ@Ìt2\êğĞïÀ[]C	=—¸eÈoı´5ÂÎ”v{ee%£C	¤J``/y½¼-ÜyÑšh[¸£Ôn·Óé¾º_Â_†Ú¥ Xªå#CMZ”`cdÕßïÑLıÊå1z(úT×¯ü³Ÿ>]d8äMú³®‰ï&b™Ä…ÉùÙÒRıüDiF¢\±ÿşA/É0f5ĞÈp©ã,Û©úÛ;~ˆœ2È›šÚ#ÜöKiˆ#^*•¡+ïâR3CT€ğ@½¸G¬f\ğ«zÓ¤úFndÇkÏ/•¯ÁÒùÚãÔã4²Si»É PÊ@ÆVûûM­­­¦…~Š>ÕuQ,>ıîÅwŒ‡4‘áæMİ
Õê,¡g­&I¡0›%ÉÍ Â=a"Ã·~t206øèaŞ	ÄbÛÚ›²»0Ğ†ò2„Nx'Ş4#YäÖl4j4Ô³Ïyãnpa /h(¯FÅùõ—@ŸWPËáÊ Ir-°»IZ"qAı—§/^¼`¦½‡Ù2 ¬,uéÌÚuW)k’u!²Û*LÖ€üKhÚ«#wA !6TØ@Ş
÷ÅpÊ“bv8´V²!€UëpPoP0.¸ÕÂàÀ@#ƒĞµ®ç]ÔU€,S°q2$9ƒf¨0&‹h£a0 Âc@À!‡‡Èf1Gß>|øóvøğ9š•0;¹/É`0˜­œ½Il/)×qnËD”÷ìv_¢t&ï£ø²˜8R7³[ÅJšv«˜„ta¦ Èı"ßxX,F)¢ ¡ˆÇcZ(ŒÚtf6Á÷
»££·ææ¨NÊT9‰ùÀ^ÂVy4!66–şS,’~¤EEğahÖ€êgeæåD1‰´0h¤%“zÏ‡–éÀš!nÌb±-8†„$d¯koÚä‡={8o8c6ä'ßóÛĞYXL5v –Œ}Zv1mŸ‘z1‰zÆ=®şd°d8'æ¼“7­¿»ûß—ªf [|â"ÃÅÊ/J¢(.ÒFm,İÒ5=İå“!ZjğØÒh®GUÔú/pÔ†½ÀEÊä®{,uv%+©×ö…¹½¤(+}tuªR oã”áÿ˜ØX¹";[‘ÀD–Ü$8”“Èò
©NÂŞÖüá–––¢|–½{óã¤Ò¸­Ñ¯ä)?CD$f(•ËËJeV‰/b®'yòÁãLDbşÒ=w ğmÊƒOõ=Åğ¢jµ:]ˆ·=‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚ ‚¼DïØüæ››wD¿^gûşæ-øÚ!ÿQ$;şú“=ô³c?İ!y­\xÿGïDãË÷*=Tßøõ:¼AùBÅlÙÏe›·m‰á}ï¼ÁMµùw?<Äğ‹oÛ>|ë%>¤şâ¾Çpáí£ÿ&îlzEÒ8ŞjEvB¼–ßf&‡H£^_çÔ·îkªDÅ›ƒ²A |	Ùˆ‹>XâÄÕ_*iHz2İS¤¥İ‚RHv(ªê÷¼T‹Ñ/ôâÄ0§¶ÏŒ‹+t½üııûMÖ¤òŞ“ÿ‹¹=!œÏÚî¶·Øƒ7´_PÑĞä¶ùêk¦ºÚ»¥ª.~…­k%jp ÿ'ƒ-êş‡0ôà°|¿gm‰É©ş|ĞöÙ±®Èwd!M»ÑĞ›Ç³^³LÊ lÊnk£÷xWmcu÷®øæşËÃÃ—û?ú‹Y¼]Î®ïrôY¸7$Ÿ)®8"¨v¿ü.Ş"œtd€÷ÒĞ'~AÚë¾¦él5ŠQËÈbÊŞÂmÿîAb%]¯“t“‹»;ªÿ¬äÁ‰_bÎ©· º›<{ßñãŒŒxÃIsíÔŠmzE`Ñ‘ :ÑĞ›«‚ ÎÉœôÇG°%Ş€ÿÑL“'“Tk5ë?éÓCáE+%~˜*<Z©ê
ñÇÁÕŸ‚ı›»ö+>ÊîöGœ„­ÛÉBW,¼›†YÌ².ZÎz·CM@ğHc×9ş"–/*YV9V²|EaÁ½`éèªšiÔ,ç¹'ZÎ NLPÃ0‘‰'œ´Ş«|Eİ`¨XèFÃ@å]—÷f¤³¥Ã×Ú–´†òn\Fæe\íö9ÆfT;m^w{«ªìçÙb'sa`‚„\Õ•·µøMQÀ0ÌÁıÑ¥¸®àäÃN,,—5Y·è»2pÃ²›§:W'À¶8ş‘ö_Eiv/‰0-ËCL“eÛÖÂ‡¬ËÊÅ lX ©)Md]7,–'0 ÁRN…‰ŠSgr­º-y˜u 
’I4'X g“) írØ¦¾_Tû@/öº‰RÑO‹PÎ²¬Ô ÃŸTd—¥Ô_®Â ¾©vúcÄK¬û£X‰Gã~â¸¦!î}Wú–:jXøp»À¶ÅäÏ¹­³­×ÔëÈÎĞè®¶®¦ÖÔõ
Û¾œBr\6'ÛÚÓÁ@Xô²	bŸYğ/m5¥¡"¨eœaœÊÇ°L´Â TJOyÖSEOU»ÛXß¬ÍjKxŞ§¦!K"½exVªÅ:tN™GÃl,eJ¤XhïW}ôü@¼v™åë¯ÕƒšğÅhTò‰z°gWaPŞT;CÛû‘–Ü7ğ‡X(‚ ¨i c‡:MĞ\ÅF±ÓjãÚ#ÿ«m¨!¿åÎ•ê öE€§†!ğ<å|®Â¤Ìsr(ègyBa¬	}ãï1f`SSJmKMéÎçŠÁp4kŠu“ôj>0²×Úo•0qZİ:Bë(Ë«v7~
MÂ‚ÀQÓÀ ËºØ¨‚haèßŒm{%ŞQ\Ù˜¹éw€áSù~îşüëÁ<EØ)G£ÌÁÑI_…áŠZaøLÉş,SÂÏô,dÁùd5$!í4AŒµ³!I¥ıèã6ÇM&“5÷€%G †!ÕŠ*Ì ¬1ù%‡QµLÎÅeÿIU5ˆ¾×”ä–šÒku&´>Ÿ…²÷w5‰9&0x•6p¹„uËƒÒ…¬PPŸu“ğêPŸ”SÒÀ$˜\…°Á¢w„…şÇ©D‘”G:ıØ'4Ğæ¿)ŸÊrüç_k!"½Ş”Ù†?¬" û×`ğßT;cVÚ›ÒO,Hæ^rÇÔ,øAe¥¿¦e»Á ¦šÍ±ûË×şl©o€òØƒ@ºw0 	_ír¹,íºnFƒ.ûY±Xˆû·äØ ±0a¸„‹äCÅÛ=„˜º¦4(›py}<²¯³FÅ0¨•,Ç°n©ĞòjÏPµ;Áp3Å›Sbt”ÑÑğÕÓÃ$Lß3Q
nÇƒáÍÇi —hÅôãÍp0¦t¿EŠV2	“„K‰Œ·‚„ğt†+µ†V¦üá`š$RúÇIšhËĞ² Í,rMƒÛ-LÊ4Ûá9ÖØÍŒ½Ë=õÚ`pM¤°€eÏK ~yfYNhÒè3yY”ÃEÌ2r¤‰ïí0ÜÍ3•„h‚ó3d°ÕÔ”ŠšÒ6¬=O/^EŸL¢SÀT²`QÀº@É ƒú¬7_İÂI€	.©hAoÓªû®j…Îœâ{%Î™i§ Êy.‹Èe£b:ÎqF}ÿ+Q|âú”2^£–x’(:¼„7Üõ0é|^>ëïVsÖ.H7«´è•[ 0ì­0”,(Aô¬@©ièÃWÍ&Ìóœ¸ƒWÚ.Ú*«P±Á§­if¨ª¡':+h’ÜmëÒZïı„1	“Ü½ĞŞ"‰eèÆÁm‡a¦™„këP×”¢XÒ×W×A`ä¯
“ŒÍQÀP×B˜ç°)‹ìŒj–Œ]]"¡ëûû?~¿Ÿ†¬‰ğJôÂBHÃíèd8ëİ~˜#»™d›¦š4[±Ò&Y÷tŒË¢èxrYQ½Hl8£ôGÏ?—ÇÌYÂfãL,EWa8*/ï_(Êë·1”c;k„2¥ÈÜx•€5^°ú®À[v|Ş0©X À‰´‚Ì1É3û­0.ø´J¶VÌã4SÌÇ–.h6%ÍÊ¬„€Ó‘aÈD†~0NñĞm°/4,ÔÔ”¢—šQ¨›¯–sq(`H*!(Ë°n%ç]•‡/ÏU›†{?…±ìŸŠ.% dáÃ#<'Ød8ùğmÁ6É]´?	êA—Õ7âÃªÎq†ÁqºŠ¹ƒ¸ÑYÒ=‹ş'éYLD¥Ü=p$	CÌàáúû/kàµ&]Ö/­æÌ£	“VQbôÊA˜º^Y<;L¢•³¦bAÈ*LJM+ÒTZš÷T ÒdCgÙbĞº² pRUï¨8"«4ËÒÿr>¿‰2÷»iÖ­vM[·İ6éeŸş½µÉö „	¿	Q‚!x„€ñâÁ‰'¯şSşIÏhµMŸ2î÷“P‡&VóšÏ¯÷0dXÑ…Uí½yj85g³Ëá[è*óá¿Rô.Ú×«jJº(b'Cß¬Iø¦­Œ‹¡€¡Zf8VUÙİ’#³ÓénLC7 ÈËœ‰óíh@ÅÂùËÀæ¢8:›äùü±¤ÔÇæšûCÉÎM¡Ø–\]%š‡‡0uñ¯KºvîÙ}ÌÃ@ÔÖÆhm¿àèØ†Ÿ¿{Ä `CÃ 
d	‰ñ0ŠÜƒEÑ0qdÙ§a!F¾ï§z¨i¡â!Š©hh?lC-F³WÂH²"½™…70lb„b©†¡ªºRÀ $ëDÇ9´ó¢(r`À.r6ÃğU·Ã=»šJêš-\nO^	y¡N¯†%±‚À°¬dºÒÀ@úÎ˜†ICÊŞüúI'×û3ìM*¢ØµŠäéòq A()Vöóë«U0tàrèû—íöe?ò†Kè¤ÁêêšÒ“vî/[³•®w/ãušÙç‘Ãˆ‰µ¤ş‰¶Õg1Ei5¶ãFAìø‰‚…áXÃš©$ºRgdŒR
Î/%EaÍÂ$ÖgHfÉê]’ÉÊ’N`0M“m®)¬E©„8j` lÚ^­M²=[¦€áºt´rÈ.‚i‡Ô”¨År£Ü4™×ôóüjíÁY“F­«8&aWÎ$™ŞUvh`PBH˜´4ni¿íoV°	éRÚH£ŞÃcg4ê\]SÀğí[û¶ 9°ê9Fo8qtKÛkhßé|†qP½¿‹Ù‘™òº1ê×’½ƒ!¥hº­mhB¯mk„ÈdÓ˜uÃše Æˆi²qz±O«2XXãIh)¯,¼<Ø:%×%axYæ™¤Ü‡I<šeYÈ2f6/¥,Â®x^n²Ì¤È6‘x !dçê–Ô”@DoòzOØÂƒ€}j‚¡*îÛ†]¡ßcqœ·«ùS…I
I>M“Ç	ô–š…ÖŸ X8d®ã1qºU\K–-I1Î)`8ë#5tÎˆ}é [ƒõ©o7KY¯J‹‹ÊÎÍÙO–À4åg#f‰vZRÈ1ú–©ª‹Å"8>SUÓê7-ÓÆ…w†iøœ¢SÍek¥»ãš…S$²Ä™ar™H` N4Ãà¨	©Á²x©”/ó¤v†_ĞÃW–wÓ#v5%/b£S`OíFàAéM0X•ÿ#0XµñlîÆ”	´2ş#¦rC¥·¶´‚9«ò¹›"”º9¿x¼¤ƒA@¦Íş$0üÜÚ&N€¡›äb˜¬¥Áı÷Ìïí”.Ò‘é‘J¨·šøeùNÆQ–şdÕ	(ı†´Á/´cËö·Óˆ&„…‹ÖßÁ€}Ë&ŠXÁ@>Y¦€-ÊRQĞ“«@@ÃõnŞÒ@jJü	,T0qèp^#ÕE­`Ø]`T–ê!eiõ) oJÒ)¡Õ™¯j9Ø@"™$S×‘Í¦å«
“D D–©<}Áö¤˜V$xÔaÒ-†}±^¡Å÷zIîÎoèQÀàˆû0é„»;ÇŞì]³;4ª~½á|ƒ3hjäğ¶dmLÀcœ/\ı-¯¶“ìf|1PÕ@e[‘´0$‡â¨a&7ÂpM¬·4¸€§°Påğ†Ç<0à¬ÆL+¹m’’±		ÕÈ¤ƒ¡Çn3ì­azwÂ›EÖğÂˆçÃÊÀÙ†„³‡8N‡’ÅÙ¼ŞkN —œà¸y¾~şõëyç®#pmıK7Se]rìŞ‘ñRé4Â`ıíîÆ˜q™eYÄ&£İui4«†UOÿ}›¯>³nĞ•Æö‹”>ÖCí¤­	¬àà»"j“£ú—kUu$*‚U²Ê–{>ğÀóÆÍ0(xGÃF8‰…Ö¨ÈsïÍ‘ša "êi­TÃ€-SÁpÉªÙ6”MÎ'ìe8YÙ¦†8&3sÎFr]ÉçrSöç5¥‡Òäm9†!A’xVDp¼ômŞ¤*­vÍ37ÛGuße†y
Šx°Û¸ïGõYA†Ÿ1v/¢Mª7rñàù'Õå2òº¦aø•õYŞpƒ®8F_´[½ùÆu%ŠO‚ÁøPÂíTÕ$Š0icÏ¯-¼ÀCèyäğøfz¬?ÉßÒ0>…Öˆã
ù†À6Ã@ä1{¥j%­`ÈI_ĞÀğ•e;˜s”CëüOg-º¹•¨<2Ûf¿?Ñuz<>ÿHzÜŒå¥S'7ÎRƒ!çAªéÖ]E‹q÷ûyx0P„0ØÿƒMC«İ{Â‚:ºÃöüÜ£ÜÊ`È;
Ş¼È7¨3f²JÔ{ÑW–„N
“JƒıÀ¢Jb)h	'<Å2…S‡QFø•f¡^[O&BqLÃäDpÆbYæ1ª#6Ãàáy¸Wªîh’?¶<
ÎY)
¶—­îŒ¶ï¼ûÏ¾üïöùîşŸîˆë¬”ı€¨ı«í_ğó-?$æi$Œäúã»QšCA¢‘c|ë®S%°V¯_÷¶C	H÷0¤é1) „ÛƒÒñ¢vÒ½m .ğía¨ÏšT«	£Ô)ö·†eZáIÏ øGoìN»Ã?ãß´0hkeNT«ó‚À“ØvcİÖgŠÂÇ¾áTpæ;Ö†Ê6A3¼'ç{¥ju=¢Sr¬\öøfÚƒoIù§;[ÿÕ£1şd8ƒÃÎzl2ü¼ä9+²Næ
Áô—KßÄiÖï„ÍÊv÷Ş)½?ÌÇ› Æ40xéşÁ!ï`ğNA´şÉÏÄàw9Ã†]±£á=zŒ8èU,l}Ùô·ÿ×£‡X=)`Š8á@=ãSÚz( E™LHŞù6QfœÊştIò0¨Ú\R›aÀyM±WªVÀÙô`ã€‡Í0<D^º»Âù˜˜	6©¿/›Àÿt…ÿvuq}ö¥?û·sçM\‰0„Ä„„Eæ•°Ò6	åM±J·ÛE·0?dƒ~`Ùˆ‡@¤1RD,šHT´ùSùIwÆ‰óbÆpWwW÷|•]ÙÏ7gÆ3çÌ;ëü«ÎÜ£=Â}úÅUæ}sÚ<ı^:'"…Bhóø•B¤q)r§lIAî–a¹ì¶‚;‰´€–9iÙÌÄØôcÍ˜Öùí‹Ãà:&O20xó€F†‘,OäqT‘‚ÖhŒº\OºªëŞ4Ë‘«éÙÖÜR^dXôW£…Ruº>©È`Üéİ‡ö´*‘eÈõEù1Ûi¸ÁÛôhN†b‚(J8Ë­İëÒæÉíSrÏd®Ç	†2ŸĞ&÷œŒšlÖÔ/É°ÎÄál¢’’!„)E‘Á*Fn¦–è§X†ÕªÛ
îDÒ¯Õ›§{|ófV{¨	«ôÖU©˜r¿^ŸKßÿ’,ƒ%{^ßkxï—Ş&šØ™*c—¯4«J6Ç°éBô.†V,æÃ‹{~2#Ë ¢‘XöOªŞô»kÚz­.H"Iv&©;–ızÎ~S%åªiŸSMWÆãÉXÑµi–rÃmÿ\3S7Â{"Å¾c’eÈsbÕ€Y#ÈĞ.Do¦–´ÌzšÜ9¤ÑªĞà8Q¾,&†³Jo?l8fø«ØÁ%…£±lÊ“1ş“|‹WÎª}Ú$_¯Ş)ò’O¢8ŸÛzZg+ò8WöÈ28NÔ4´Wø#µãdH¹õÇYlG~ªNçm*dÇé#Êp˜¹¥Îv$ñ6CY Ø¿µ5/¼Y~bs=³G”áRp>E¸¤–ab-.hšµÁ1t'ÓôT¼ÏİÁ…XÙÂùÏ~%;¸¤Á²æs¼ğ~ÎÁ¨0ˆ”Ïj5»°[·²­á0$Ã¬Á_SL“pŠ×ûJq¸Ø[¯Ghk÷ÚİÙ…Xò§'¾y¼$Î
ä~}˜ÖG\âR1KÑq4ÚR1Ç¼ÒÕôóğ˜S¼ç¸G”YJŸ•–ÔcXé,¶•kh5&2šIjje§BÃe5±¨”ı1×¿¤’áA¸'|§?¹æwuÁ¡i’7ğú2IàlŸßê½éüÍ–öÆ™;Ë»Vì_àï–ŞÕ\{µ&ÍB/•µ¹jPD¬Êµg´EÄNlej¸¯VûÇ®@#CŒ¹ÌÛ’§,<¼¿Oü•@Ä÷"üZÇ­O8HÄãÚˆaØ\:_*åÓßr¹\ÁbFhúWXà¿î*ş­a—DøÚ‰êYghæ·Q >oãÇÄ_šº Š­=<¼ì3\ò–ÒyBôf³•dÙ¡5;x!x{ôŞÉdÔÂÒWe·‹¦k~!‡kóYÊ™BSÊKªÍõÿ†ä9oÏ_÷EdÃÉ°OìÉŸ­wÿy0eUQTÿ` cãK›d2ÈÕ¸u,1èÜ~ÇşÅ/l74}ñœÜÃd—wÆ2ËÄş„|UiŞëu«ê÷n™æ”1Ş³‹'E,aâ xUD"Ò<l¯Xª¼ª»2Q†ÿ1©[ÈçÓÇ–Ş;gqdùß4‰^‹Áñ°|t”ÇÑğ) şÈçàwş½ÛukÜ¯qEì¯_qUl¿Wïÿ²‘ã=ğâ;òæ9~í#«ßñŞƒn                                  ÀÎ?ò7‹÷rÑÕ    IEND®B`‚                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           ‰PNG

   IHDR     €   ‘;w   PLTELiqÆÆÅ}~	!;=9~~~omp$@J††„}yuda^___omj‹‡‰}{{T[p€~sss€€lfg‘YN'ENE*>B€€€@TŠAa”EX‘xnO[[|||œœ[Z[WSC.@NjQ8(@L¤gZYU1ris1BXEW’Vn„qqqœ? wtPmIY¨š©3F0j1$<KBYoin™““dd&4KºººEN]ZR+aaaYD*œU=y„”‘O>heVÿÿÿáááÿĞÛïïïòòò€€€ÿşşìëìêêêæææĞĞĞÖÕÖöõõäãã{Y···™™™ÙØØÂÁÁÓÓÓüüü†††ÆÆÆËËËÛÛÛŒŒŒÎÍÍùùù¾¾¾«««ÄÃÄ‹ŠŠµ½õ–––uoKÉÉÉrrrúÙşïèÊßßß±±±ÿøÔä·¹»»»İÜİèèè%.J®®®¨¨¨µêê›ÈÊÆüÿ±ğñ£££µ´´ùğğ[0GúÑĞ¼úû“ÄÅ?*,uutêÆÉ‡³´yyxe^=¿ççôöÀÜ¬§ö££şªªÈÃª®¬Ÿ€XöïÎ¦Äê»Èì+<jÈ¿‰ãÕœµÁä6^2°½İÓÈ‘¤°Ï¤¦„„„¬”¯‚­®©¶ØŞÂáÓ¸ÖØ¿ÛÈÕ÷‹½½ïĞò´ÕıäÉç4G1›¦Æ½¸¢‰Ÿù¼¼×èı””“ıÇÇöâŞÆ«ÈÄ„9šÑÒÀ @ÿì¯ÊÎt¿À‘ƒj<ääºTNOÕ¢’òà¦™·ÜÖØ°ÁÎò»³¾¤ÁĞ”–gÎ¬E€•»¥ŞŞ¶¹_w˜mgŠ¡ÆŸ|…œw‰±’­Ò³³³öû‰±•<¦Œ;ÁÅg×‡SØİvŞ7êÃMhz«Ë‘‘¥®¹úç«ÈuÒÔÔ•~7¬§t¿}dµáâFYq¨«]Ï²Îæ¿­á–då¨ã••Ú·JîÎ¾‘†^ƒju´ssÁ†‚hŠ¢s¥mÖÒºX~TV‡‡•Ÿµ{²u¯¢™«Ğ¨ôº““ÁoB8€‡{‘`NbA³   GtRNS *G&ş3şSi~ı;ŒNÍïë¾ştûf­¨íşÚ›üÛ?ÀÆIÛş¶àşqşª€Öüşíšİ´Æ´Øñr©àãÇùıÀş»*|  -èIDATxÚìÚL“gğF…ÒTm]—ÌÂÊvX(ìFô :åR'hV°
2¦òïD§ ‘!E¬¢e€Ô¶¶õO“IÈQàÛbG9îävIùs@9İnòg1b.¹çyßòÇú>o½,·äıößc¡xßÏûüß[
*T¨P¡B…
*T¨P¡B…
*T¨P¡B…
*T¨P¡B…
*T¨P¡B=®¡¾L¦o¨ë¯ë§f¼õÎrjÛQùŸ†êßÙ[]mêñ¥ÿš,¼ıùRjóıe‰Gø‘î±q_äxğ™sá{pÈï’÷î9ÿ¸ğÇ*«ñtòiùÅ^ÁÁËq1‰ûØoğSˆX,Ñ÷°×‰dÙ·
rşµ"L [An­
``Ç€Uo´½Ø^ÁiiÁ^D¿­kD¤p‘DF •®üçUEsé¨zÎ'[¯Ü{øĞişXu¥Z; ÕA¡¿†/_2¯Š;w*VĞ|s´Î¼‹½óó½‰¾÷Ão÷;È·’´ĞÚJNÃ¿ü°ƒêª°IYm­l2ŒÄnÍYÛÙ ík9ä’k6nàn\ãJ÷W›šŒ‹Ãá<â#·-’Èx„wtg^-*Ê›Ÿ¢¢«Lw’>ıÔYt0/TªLcc/ ‡…0oŠ!ó|f&vËü1 ÍÍ@Ãù»¶$Ÿ` “_éÍÂòÉoÖ.!Ä rKLt#šÄû¯;È~1Iõõ¤4°K,eØ&ô°ş››ıa¨ş<«^¥ãÑAx:U¯•Ç ·‘Ü¹}m?5s;2¨T˜Í
å 9Øáÿ.Ü¼h„áÄïúsÇ«0?‡’´à´†õÀBe¯?ß•îñ¢ººáÁ´÷¦2ËŸ••c·òL8@Å -´´4ßÉØw|òæå|’ü…è@büVaÌ®q"Äà~\(<îNˆá’ƒÃ -ÔK†Hhğ€?íİÉZ€á¦äfíä
ÔWó´*•ÊÊqqáXÁ@Ç#·>nkk|ô¨¯¯dÜ¨P(F¥Ba4{9zÉá?ğ±¾çyy9ğ:w‡?>w'eaõjLC1RÕ /´à7œ]µÉ×ñ÷ûl,\ }ĞŞ­Ä§g`á\KKKYÔ¾„”üTjOÉığgéI_'¥ï‰DéÉÇ1ˆR?;*"á:¾\¸î†!`Aò”„®FÃ¥ÑÂJkk¥µR©´V†úê€•Z×î	Ò®S«zÈÍÃ?5öß-6jÌJÅ¢Qš5ƒ¶C	‹†İ·!/ä¿îÀCƒ/Œ…éiLC1\«Â°>ĞTiÂ-ĞècÕÕ³YáŞ‚²q¢(nOŒÜ¶bÈ´s8´Ğf†3'vº1P{Jœ¸èü3QG¿ ’?ß³Ç Ì8º#CHˆá
LÜ­WìgmÉÃ19ŒMO…§OF‘5pÁÑXa†ß²§V€Z »xjuZı½V§ót!³¾¢sÛ …Gw¹Å¥Y1hña[ …—C;1÷çE9ö½˜"Ô©· bŠ€Âàßn2…3ùËàRŞCÁÖø×²µ CYYYMM9,“ÊkÁÛgÍ5¨en¡¹åTjJÂ¾3+0Gã	zJŒäØSŞÎzlÍà½r=k†Æ:˜Ä¢¡aØ;!OØ1ì>|ğ`Òn'0øÊ OdÈ¸J³Ù<1¼_+³	8NĞé®<½^¯SËåjğ\étìˆCÛ#áËŠ5˜||,ƒ``v„á˜ƒbğ¨Ê³å,›-¯Êİ‚L(WlnØ¾};)Gºz;™Zmà2°”Ô–I…‰11ö‚˜DºË§ëêœ›°	Z¸·çLólOé AOi¥hõø˜qûö—Îu“X‡Ó‘1ˆ'&ÄvÙÑ±±ÑÙN`’dÀ‚Á0ú1j™,Œ˜12x¼ —%V=€G­·z.q	à¡.üÁzáQ#Ä€­`™WMitG®.b¾96p9›®Ø¼æ`Ïuø’²0«!;›$`áÚµûj_º‡¿IGØ[-œW¥L„„L¤`CÒ3¶f°/À¥µê,œ¢ÜvN]WÑ°§½%¹àpç}ï Bã¿™{€áŒH|ù²=ïNÂf8&…±I&3ô€…Ö!š†`‹bddd, ßÿÆ ^Ûo0P0p¬ºOU¼èéyQè­<Ï±Ãº,A@™dTh”ÆÁ¦¦A£R£hªpˆáô¢!ÆÀÌ³Ùlgñäœ‹ÍÖQÄD´0qHˆ“ÂCPÃ6reR sàÆk×ÔL¾¯ª²’¨µ
0ÔÛs)$iÛ¶¤Kõõ Ã± `¨±—IÏÊÊ@n°\Ê$ÆÀHÃ0œ›úêë¨S`{Jù)qÇ	û«,{Ã÷ãóz1¬ôí¨v‰÷ì™Á›•Mã÷0)ôOµC£h–—Xæñ6ÄĞo
†µ½r(är-æ ˆĞzJ®]KCß0W£Ñ(±(5DeÒéÓí9}úÂ¼ a(ê°] ¶¯X˜Õ@
ƒ/´ 4„Ã¶RõQgµpÖÂ­ÍR™Lº=äĞPøgİİİåİÎÌ^iÂ5tGEe´4Û{J	ùèıUˆAğî<»¶ `ø&Z|ıº}'…'‘b±!b
bûmÂ*¤Ö¡Ñ!`{]	—[â0„ı«
ÇPJÜMZúbPİ7@pPéu÷Uƒü=´°À2©öV‡¹ãÊ¹™A©p¸€>Vµ†*¹¯s rs0`¤Bél„R¨†PÜÂæ˜©²²rŒğã ƒDŠ]Bâ¤P 4eµ„ûÌ ×³0WüuFC]s3ÖSªKOØ…~*)ãö÷?¾|{†èDt—.Ù1\Á[«WĞ1µ
F[ÁÒ«FaĞ,x‰A©TXJVÒ~7Y:…¥tò-âj»Z®ÖjÁî¯’wuòx]@ƒê¾VnGë°Â4ÀĞÖØw·X	b”ÊÁq¶cUñ_\¬ªºğ‡Ù\¨BÁ—{2·x˜)–À £¡¡<›ÇD·ğ8"00âñ¬”³oøçTù³:q.„'Ü
¤öy¨0SxHŠ—Ihf»I0Ïjì'Şˆ1°Çÿ6£¡Ãp&5a¦§„ˆá‡_¾|g†,!†[0Ñâ>ßÂs@ìæf#aj­
{ªøÄ0$Øôo’zp…î‡"Í!˜*íŸšê/B8ÍàY»´j°÷Ë{=Ùt:ÛS+6ÔÚ.+/ íàáÂ…gÜ P'U@XŒ#Oº;¹†“(è†“'!‡W’k \@c$B‰Dò8Ëc0J4ÀÏ©¶wuÎYè5™ºÆøÄ¨ù²¤Òhi|	Cwùt÷´33ÃÒŠ¿ÿsØSJ9ñvÎ¨©ûà1@R@Äm2Ğ‘)t¬:<loåF­:Ïm×İuê5u·ë:@z7ÑQn·4¹˜‹9(ÁàNZv¦¡
CÃ	Šë«I ¨u¥·ßï÷^’„¼ïKZ¯»½ø%½Ş{&ïó¾ßß÷÷}?ª¦•áÎ;ÿù±o–¹¤¤dIL†72¼€'ÓpğºsÅ_xÃ¦‚¥ç‚âÂ2Í¡Ã½^¯LMY3éR«]“ëAi8!é„ÎæC	¸cİš7Øu'’à= ©#îş²ê6"Qê‰çf‹ËP¦òÈ R1eP•J«Uİ*L™òª¡¡»J
p¡p+nÄèê!8ğxk!À†ˆ´1tñãš*íBVooWÖ›ù€OÊ'ƒb÷ûÛğÜa·‚“Şj)«zŒ È;>x…aCQÑŸ~·ç<‹Îæ$Ãïí3é9­ö¹¤H6naˆ·(¶mß³‡Ãeøüs*0lJ‡íâêC´	ˆêô¥2ÌúXP£8"é„İ²¤äŒ0åIè5|áM\ĞÚÙ?€t@Ñ¡ı@µ¾ÍÚFcYJ“Xî2$Ş¯lÀ¨˜wXİÿ.Ü‚¯ÿÂiòbz›E†HÜ›§µù\ËßÌLËƒ|H>†›ÿºıVsK“N2İHŠ¬&eŸ={ÅgÃ—E%¿¬=øq2ä%ãÌ09M†£"úyıÙ‚'ÓÔ˜&áéİwq`€» XvÁ#BÇèè¨L69ìBOÊVÅÃe0$‹°¢dG2Fœ­­N§»µ½@§$2(#Ùdha\Ç~2´ìƒ´ctWUU5øƒŞéfoÇøè£Â—çáåB–‡Ä”c^¸4úd(¾¨Ø¹yóNÅÅb,Ã]°štét‘!L9èg]SBiÒªàdHîÕâÅd eÀü™–á(Pd•#ıóüÍø'­ôŠ0zaB6©V«ñjRqzJÆn•&™Œ†®<,C^—Áhâ’&¥8;ûGÜ#’éx‰ç	È€CCË<+-nŒå§
çåÔòÀÿ_ş˜Õ¨íËÜpİB¹&N†âaÙÛoË†‹¹Éà­&yõŞÍb'.Ï±×”şÎÁ<fÊ€&M6›–U†zÌX†zŠÍ¾1°´úüzò¼‰Ck~õ!âfBæªi*&ëÏÅMMM.Y,hm1š†¡¼uëò†ò:NowâÀĞÙº‘’'õBÛ9ı9€İe4©¬Œ)CY7@Á3÷KËêªğ/Ô5”—Ş‡Ü­Ì/Ki5.S«5÷eJ£™/Èƒ•Á&İå¸èF·pß;	I“²Ç/úÛğ1GÈÎ2A|pWS/~€ÇGˆdt&CbÑìªª:+„Ÿmö…Ã´
™)à•¡¹YÍÖuì³É‚BƒÑØ;4Ô‹~˜ì“½*­&¢ÀĞŠ’¤vú8ú'"ƒø™èºÊnU•·‡£AÕ]YzÙ#C;L†ü«Éœ)Ëv‹åztOïn2Tüí1*¨	ô]®‹n\"C¶É0Çn.`nÏ‘¡&ƒ§S5(Š–/™½IV€]„EÙ}Ê&P\hjÆ6UäBs³KxÑÍn×M:‹Îd6Ìä…Qg·ƒİH–Ôïî÷dueø¬ÌSMš#Ãg‘<«
é RµP}¶TeÉa}ze8C Ê ï³^ÏJÂ³Œ\Så&ƒâ•ùZ¸_Q€eğ-ºQ=zïK«éÊ³ÙÀÍ…Çeè‚ÈĞØØxÄÓ©ê/Cc#D†¸¢ÚƒËkwÜ¼ÁQÁ¢TkF;ôú‰I" ö**š›¦ÿÃóÌ»Ád²Xtf“a2ë,“É`·˜Aí©îÖşN§Äsft<!âDéıJìÂ4Ù@.MÎr‚“!Új}@:ÂåÑÆl¶.NiÒÓŠùîQ<—Á»èvïä½{èï{ÿ -º‰•šËslàè‘á¶¿½@èNUßœ¸ ‘!¾hÇş§j¹k¸¹ ŠÒWo”?0«F"4ÓTPÖ	CGÒJ‡Ùb± t	¥Gh`v¬Lr F½\$C§s ×w'ÊPº¥@ğZ©*«¬“GpúnCaŒú¼¥™6­Û )¯¿äÇñ™ãøé1Üzü!pÑnáöE†?dmágh”ãş6puA°÷êU}]ì2#‘êT%2jR}=’á»â×ví:]‹¯ÀÜ5œâ‚—g'Õ@D¨¨(¬hª™<‡¦Z¸(9¢Ñ™ànqªÄİÙêî”ø®Ç‚°óÏ¸ÉP^'
‚–á‡4Ik5}@¹@ùië0†0<‹nÜvÇ@.{mÄ\]À2\½Ê”a$Ã1¨NUROİrjûöSX†c ~øÇİoÕR+8¹A¹ X^­®ij¢E@&67ÕÔ¸\O¢îÁAÍñlÚ~¸G´qÄíît;G™Iê§úOA2ø^†òºœ/J¯×®]“H®A'Ğ«îµ§qiØíXğ›Ã¥99´Œ!«ÈÎ2 ¯Œ+5g/#Îk4¥2]¢]Ó} t§*%Ã¶7ÔeˆùËowÕ†¶g‘Ì¥FÔÔ4áÙB:Ô Ô2ÈÂ›Á@r;a6Ğ·(I¿Ó‰\ğ”U9ÉPÎ”AY9¢£OF†¸L«Õ¸ãë±Ì,›ÙèS]ø’3s÷î1€1d¯&¼ô%×MÄ±H¥R3>¡Ì‹Ë^)QGWOï4D†cŸPªŸÔûÀ.°ÊòÖ–’Ú÷eS(!°Ë Xç°ã…6Ä	İİ BâtºİNoY•N“ 2lØWÉJ+÷ë4ÑªèpAH2 €2àÚªÕjzğàºIkô©.ˆtæáW¾"³fÆN^òğºä§Ã<QPfˆc²‘	ÁğŞ«gÎp—Ø0Ø"÷o«-„È²I¯¦ 9âJ4o¶S[Å \É°xÀ‚V'ra À¯”×‘AZZ¹ ¥R°wIqZ½¤Ò§º 9ÿzøèÑ#ÒDs`2h~Q9TÆÊ…Ğ.«¹28zziÒï‰ó€ÿK`öïÜ²ñú5®Çl˜¸‰ËI:j1.&¿ëÔ$"¿ÀĞÖ¦oc•A”UºĞv ¥YàY@P.+ƒ@œŸé@:Øz¢óC¹¼(C~öâ‹¿~õÕ­[·şœ$Cºr<tæÊ0í˜îêµ±È°öh í%Ü^2eÇş_	¾–ÉÎûgJ.¬vÜö’éÕÈ…‘T??&Îµf—!ô‡#„¢(iB$ä²ˆDQá <J@ÃÈø°”˜Õè¤å«—®@,F¤¤¤„……ÅÇÇGFÂ¯8é~ºmFê?,Ã<€•XPŠ?"
!†ô&ãÛ.sÎ7Ô×§µ±,B%¬´ñğÚ€WØéß”„{‰¢N·˜³Ô+d´$4¸Ö¬gß‰_!D×UTx>90ùA	E’'³¬JÛÀ.C9â¼p¼ºÿ÷@³f$@ÈøûY±t+€ßĞ7ÁŞ©Û·§¼÷„%ÙlCIaì·ªğx‚_ğ³2ÙäyüpkJ&‹ù–&Ì¨nŸ›ª¤Nt "Ãÿ-‘ñqÒœœ´Å„˜´œi-ßÁ3/ŠŒKYñıw~DCcò’’òâ¾›§ìwABŠ¢VıD¡˜U(VÇ¢ ƒÂ¢ø[< 0<ƒy‹Š@7ü(©!â/{ÿ²w~»i#k o›­0dSVG\ÑîŞä¢—ÕîU¯lÙcc-ÿÅ˜FÈHƒd[øÆF\Eâ)ò<À¾DèŒM’æ4<NV§Òü"+C±ñÌï›o>†B¡P(
…B¡P(
…B¡P(
…B¡P(
…B¡P(
…B¡P(”Ÿ‘³V€vëìçºêzÒmĞ¾£üO©µnt4eeÜªıT.ÌáğŒvßÿï>/á3éş¿^›÷0íŞÂİpùƒªkğlsÛıglH'¤Ä/VúíÆÑuº¼ø«Pö{¥ì9mãíª§??yÈjv¦Û¬ÖAïZÅV,õÖíE}Öê Ğé•½Û3ƒcŸ3ˆÂÖY;±eñáU¢l'íªñnhY/¶¡ŞÎÉ,_Xö¶õÈ Ş-ƒxešØ…DP7‡—ÈÛÀ1'ÿİÏ»eÏíDâ	¢NE6›j6¼ëu:½b ¿kg¦ ˜Y»Â°nôÆëbÏ«æfü‚Œ²~şéëûO¿]ÔzËªõNïrôYøg„Ïgl2.;ı¶ù¥ ªSÖeš]`Ù—ÚPk¼ÌiÉn—m ëì<L†I_nÂë×¯+È’¬i|h¼¬É<kà?º0ˆ±IfóóñTuW‹™·ßëâ;Ë ì¦Ó]ÙÔÀˆ›ˆFED±’gK¬î“Z?Œ¶Iı¯÷³@†ı|È>”ƒ¬_q¿®æ¯Ù,˜è»÷—[M,k‚Ô°}ò\²Ü³°‹ò3¶n%ç{Gª¡s^lCï0ˆ%4ì½jŒ×ëœ˜ayùA¼¹«§i~$¶&R°´c=1,+wo£“Ì\oêˆ,ïnt¶¡¹fÙu³T†õ	ªÉ»PÍ†®¥J’:ë½zõ!Ë÷Æ8q lÛ²Æù¦è§Év»Z7ıv°¬u’¤Æğ–WüH"_áÃŞ©—\³ö³ÈĞH¦ªôdN‘ÔiÒ¨äÂpXØ.1•‚öGÆŒæM/7Caã~I=ş[sgã~eÄÊi+
Ä‡bÚ¶ÇÊ€]à‘'ÉÒõ³E´¦Ô\kZäTÇaEÁ´2(†ŒŒò™áö;’EşIŞ®(C’ËàT°Qcƒ“±6º™ê‡ö”C¦~‰ÑMÄ%Õ²ó¥r›¦i¶{U±e6²5»8)ƒû,å2Ô:HÒbõkU\X.ùQi†ø3‰4ts·n^ø¬Sò;¼3-¦káj4&;Û*ïZœå2Ø6T|@S!–» h²“ë NZSjè‚•-S8Š"ÜÜ†GØáF³¥2è9£ÑL¿ã¦XED7y»šõ•–o	¯Î‰mØ³!/Æ}²WqÁ`J:)×Ş^ÚöÄÃYèNlxYiƒĞÚ¯xÖÎÒäú¯•ÅY–à"S‹³{'eĞŸ¥\†fÂÊ#~ß,©È…†ï…øŞ¡J2t,‹ñ—<¸áÿUv«ÕQU^ŠYç1*±~êÛ-Í$»ù¡ûøWˆ$2`p¨ÔÌdá»Ë=h.l¦NIM©>çnã¢‡—: t[=0+dxµ_ÏJ¯›Ã¸`W´Bà…ëõÌy»š7Š†]øäœXÏƒ6ÊexHÇ×^æÎµ7W–@$I¾uõ&ß4±ü÷!˜Ï“Íâ÷_ŞËJ §Y£‘Na “2œ T†tOmà”¹©¿İúia^V’aï*ÛñÄVÒÆ«Zw°ZIšÄ¸32'ª˜
Ä2lÆÆgbAAFv”·d°S(ÅQrcYc=Ô”Ö%5¥+¡Õv+dgßªIû0 a–c‚á­ˆ¡iB±xT5M‚.¿}1©{'“…9jq„H†~»ùöÍ•á£û1…|ãêÍÛf»Ox­ŸôÙ|³¾İışËJğU›YjªÜ$`Aí”óg)—¡?ˆ9Y¿G–¹Xê»øy”ò“Â†8®$ÃµµÛ|ÌÁ/µîP3Y½U&ƒ Å2‰8}µ³á0³qC%–A['ùg@ dšX|˜ÈD 8ª å„ÑÃÅJ±5%ÈÏ%á®ÓìUv5È`å˜`·EËŞ¬˜òv%ê—8#<ì‚†R2¾Ì4e¤lº¹+Q‚4©‘ÁäòÊb	¹·Irë")Œ«ËÄN	×¢_}¾ÕõÛÕ_+udÇ‚ƒ<!¶GìÍI¯5”Ëp©rw2ıä2 gOêÂÚ?ö’¿.lª¥IéØª|-º7'ñ7ge2H²†—°Î`°ª,«·ƒ/—Ñ[2#Ã2Dó@×İ4ÅÇf”Ì£rêÔÂ)š0É\C¯¨)e¾•–ÖW;à˜F­f3íq-fh2ø9È2P´|GXc±xTE†ú#Í³'¾]0aFdÃGfÆÁó1Ÿ#”IB²fèN$Ùôİ¤„A&¡"\Ê’B¸üş:Òçál¦g‡¾ÚXÅÅ©ŠçD¾2MÚn‡w|k•ËpÍB¤=™°Â^º ûÁ¾^ØPI†/c[d•wÀ”dÇ(«¬2‚Œ"*ŒeÅ²yh±0ö,9¥CkÅÍ  Jœ†çyøˆ4la¹½|´Á àÚãîjJnF^_]ù~”|x$ƒÍÈPÔB"$àXFyÚó6¡ççÿ:¿T$-‚w6Â£K!”áOæzÏôp€ê ûØÉ6*/‘¾c¤fºo[ÏGÏk–Ís®©$†ìİ<M
}?LûmeµXÊ0Z'‡s¨ßÿB×CId)&’ÿK¬BdOF2.ÌüÉşlXñó†¹,;ÆF„#|VúuFà NcÇÛ©ps8l ºó4aœOà$2è›4K3 òŠ¢a³4.B_.CwÈŞÛ°DrQS:÷5%2E“¥=œÈ0ÊA`½Ek´-J«Ãí89—İÄ‘(g¦Õ‘r›N'éV+«tZ³È"Ë(YŒzeËvŒË¶|ÃÀ.„
É–pÉŞ b‰§àx€y	hªlhH«'UÌ‘Ç‰¢«¾:ÿ¹T¨ÏÃIY(³•Jbjê,ÈËÂ“2ueÍOµLªı™İ¼š¦8õBY‹5ÕİÜÌ\•¸rè‘/Où:X'Ë°˜(°&ı¡$¦$4ÀŠoÏ¿¼ñ¨S§ËÑÆ«ïb™ÔE!ĞÉşhšuÑ‘îxQ·wÁÅ‚É*D“Ng‚ˆ]^ê9Õ«š…gÔYå&ÆöGæÊjÊ$Ä–ÕšB·‹®Ğ<™&Å
{iS«HÆã±ò,&%±ÄËÊîˆ¼‹lÎî†O­kJA0h*üåó¡jòL˜çÕ6“*–¥¬·¬ôûkŸ†“ÂhIÏÏDŒ@P.†.~{°µ Ôuôåã!‘£´Ü².ÙÒÃã;îF2èªİ‹³FãŒD¥.£0¶où6ÃS4*“8p'ş°44ª_ÌT3¦Œ¦Œ70Äñ.1dÃp‡u . ;­:@öİ êø‡Ç	g‰ë&3D\ˆ¸hh|X$.‚ã,$@ÏA‡ÍÂ+t!ÒkÈ·ƒ†l6™ÍHÎ kU°eŠ$5™Ã»™VnhX×”àŒÖ”¸Û“ÃTÔÚÛ,sáüˆ	 &R@m-e¹\û<0Ğ¾3¡¡]Ã.nÏøÆõÆD—„D^..ï¿úO–uû’Kæ4Î/ –ƒ´eŸÓqŒãI+d\œs÷N^I¶­±İéÏíH¢û´Ô*ŞV/rgõòŸ¹¯¾Clî‘»nÜXC÷,„h|lQye©C¯ì(×AR³ĞF1âñüšãImaè{˜şBìu(¦i*ìšÒ0M³$)(¦¦Ö³IšªñÀğaê¸[´RÅı9­)İpÃ@âo»¿-îLTy|qÈ‚A§fRôÚTÅ¶×>Ù3¡·*“€Îûi¿*†&:$2é ëÃ£Ï—ó——ùåg®A½Æû(®†çï©ı>ÇškÈ¿ñÖ¸Q9&8d#W9"GX˜3U¿;[ÛO0„M·IÛÅ®á¶5„d±\æŠ@ÚdKûuÆŞ¯n ğè¿ÍÂÇ	6Í|õƒ…‡Ë—æÃÓW‡´…a5$Œ%a6İÈ$‰†Ø*Š"Q³(§zb¢•È{a²;ĞuliH”ÒZÌõHÇ€Ş†{E$2^¹gÁàPÓ(NmªBt^íòÉ¤Œ–%MSj™ù‚›…ƒ§Q+'’¹Öc¿È¼ãÜ[ù×òÂ MMùƒÂp¶ĞL¸òlbOóLç·ïÏëX`åg/€[[naXBÀ1q—›–eFñÖF#ò3¿c}lÃÂOFhx›…Æ•-‰Z>éx½š…Ïü¶…!û xş8 0Ğ ÑbÃYƒN§ÓWˆpĞZ–HP ïØ`F†wŠä…;4¬kJ*PÀ>0ôúÛv#VetX0Ğ¿,¯`Èk“”$Yûœ	tF4’J‚¨¹ºåÒÍÛ…¸T,IôBCO”F—laHdR  7³{
ÃıÊÌ¡òË¤oK1HfÏ>ış7á÷¼/´¼–ĞdÂ ïL#íÂèƒzÇvÛ™Nã˜N¶ÍÎ}ˆ~bÄ…ƒk»—kÎ¢$£MY¸:ø0Ø (³UPÁ@3æ€¦ÓUF` »2Æôj‘•Ã‡‰¬¿¦Ö”¤=X¨`0¶0ÄA7JU&Unƒ)ëy‰V–j—³´z_²dêzºG`8˜·©FˆÉÖ8µ"Öi&Iµç`³sÌ‘@ƒÔˆ<Qœ<~ùò8E/2RÀ›@Ÿ7óÜšLàè{c]Ô®Êƒ,×öÏ÷})ËnÕ¢«F"+Ï•åò§åæü
‡‘0-VÓóÅu?Â’Ñ#ùÂÍÿ…á‡­e’Æ†Á	ÈÏ,åYË<·Ltre‘UìqŒ ¿¦ÁS±¼UÎ ïÂ€¢ˆÉjÌp@ÿ¾AH}S¦`TÉÃ‘²(¤ºİO{œÌyÈ°K@ KÂöI¶¡ƒ.m3tõ<Õ¤Î#½2%Í=äiqšæ@îG“L®Òê—‰²É4UÖğŞ‚ŠTaÎ„!k‚Á†AóÀĞ8ºÏ&šEÅAQÄÈ|v‡'<áÌÏëå¿ióÕw9†kKèŠşuãA±„:‰»×Ñ„¦n{wV=¯êH\0ÄöÒ.ÊsO5$µºª—†LhıDƒnìÅAÕ]ÜÈcÃ@+ŞızRµÿ
ò.¾*V±H°™zp°ÇYZYu\+I’¦‰µªäNÌIE·´Ë7©z×$ÙzÚÅ=¢ZÏE€ÜtS¢¹šn'Ë–…WlTİw,HÄƒˆÖÚ…qî!Ÿüq€„ˆì¯ ]ı`Šg\Ëëš†_gs~}'2`¸L«¼j•ztV íƒÿËî¨ª&qÀ #Z0)BO•É GWÈ¥J=f}¤8mñ5½ıX8¦©ˆwaE]6t<f3©Zi£5Qx<0¼SPœ-ì2utdïsÌí·§ù$ğÄ|iIõğ¦H>5×)ÉñÛûû•nÈbŒª{N9®öŠ²¡s‰âo`0=İ¬ÃÛX™í¿`Ğø`8hß—eÁ~"v÷È{èÛ×Ö¼zÓ04{BQM =tú“\‡{É¤•ßü…ù+0!¬­"K­.H@®$k1aht–mãí=Y K›»0XQÀ†¬Cs3©ºN 	Õ­rÀp¨è ^œŒyûÎ›zĞïüõøıôŸN«øI§™‰©j}úË34ÅâânìmÃt pLâxã»‹$å;4öm®âÜşö£á9ç„ÿ˜bŸöFw{RõÕz¨wC}ÇÔ›·…¬N±×~næÉ^ÿà+¯ì“û‰¼"Z–UTÜÉª´mEhKRD‰^-M3X04:ã,^Ñ°/D¤9N¾…a=fÃ ©†õ¤êº´Z–J”›X•Ø04zŠôñüÛÎùí$®„œ¥
Bt×"fW²f/öÂË&»ñª¤Uè¿´µ
CH„ÈM1½2Ù§à| _bŸèÌ´
¨+3…³'{r¾ßíÕLÊüæ›¯ùŞû£…Jcßa0˜œ¬GÂ+ÿıíLò‹/UNœT$?ÜÂMSÁ }3›M³–›ÇOM¥£PÈPé<å/d¨Ä‘ÁQÎOâ–ó°ùÇœáQ†è'-“*œsµº06DÕ/Uzè”?^’e0J8Ú¿ª1W†t½¥´{=ËÂyJñÎŒ¸. eÙ˜Ê M,ƒÀÊO;UÃ©Š²iİ¸@—@–aKªÔŸğ‚eb¾šƒ²X­LêE ÙCTÆüåN>:ÜS“d‘·,^”¥õáÍ“À”šr}¦¿~`îˆ2ğ)ÏdàãÈ tNbJ[ˆ^ğatİ°£; Ã±Ã	z}7ÁìŒ»by /^×1y”!_¯×©d§ætbË€\àÕŸ=Éà,7´àÊ
±kŸ ÖMy*Ãms$µÈ2(B%ĞáNÕËH±Ü7äµ*(Dv›Š3^®fÑZ¦áLEp Å@âÓ«lYíËx\Ê}µLûÁmó¡%vFW;IÏÊàÖ\
„ÆŒ34zrœYÜŒı˜l%<R,bF#Ãî‚¥K‹ëûWõËaWîRìS};3,\‹¢$`˜zxI!ƒãû×~Ï?}…?O†º¯TÊrçJ?íİN÷·˜Í½L"¾µÆŒ=M¯É2¸(48ÑNÕ¦1¡ƒK’¡4´ñ’uÙîpQ€p7ó”º[¯=6n¡ÒÆÚA¹¡ÜÑ˜yPE¹ÑÌ~ùtí6È2°œ2˜±aJc ÄÁ(~ˆÿ˜lw*^&Ew.i›Şqãç¦u[İÑvfái¯‡§*ËàE—4‘¡ã •R¿I¾i‡=Íûñnú R6]Oolï/^©Ø™‘¡u{ïø2¸8«Q_ VyÅ%É	Äñ0±$_5÷uÅ·Iq*x[E}áR1#ÔW•¶TÌ§fYºPıÙå<ÎjXœG,‡¼û&ü!µ‹¸°­jÈ“ÑE’!½í+ÑÒD¼_Â…DÁÄ\„2D—2à]¹˜ÉŒµ|È»ù2t»¡Ë+Ïl·Í©Ã~N±LÂG¼^WŠC«w×²2çÁÒ.$Ò_}åEó‚2¤p!SV5*"6°Œaò¿>ĞeCí?¤Î’È20ºğV9 A§ÃråVöcCO2<e+ÄÇ{<vxSµµ½e†’Í<f!’_RÉğšãí‰–^;_Ú”@ÏFÖoùMÇ ô¹`œáJv¯g:ü­åÌ˜»r/é™ø8¶û–2óUÇêt‰ŞúN Eå%µ€¾¼ägO.‹÷Ïüø3éŒ(C"ÈŞı–²ğğj2•ıE¤’«ô(•Ê®Øo°’M¥æLy¦´»ÍæŠ_¶vëˆR©Ä0š 3ú·w‹ßºkxò¢K¢º÷ª¶>êºQDîMÆÓu=U–§ß¯L¹¢è„è½^X›Ãü$şŞ®LÈFİGıNÇ–ú›}o ÃB$®u¡ïSìÔ[M¢q•]9Â¶iBÑÃ•ØÎ=‹È‰#É°¶øKbîÿLA“e-<lÂxøÒ#EÂt-<«qıl[b4¸Ãı›;ìıTû·“Ã=«—EeÈSÕÊü‹ğñZ õ7è_ßvscÉïŞéÇ—k§àœ#G†ÿ-(Âì°ìÎzÈF®X,nâĞòö4ı.“Ï3(~Ü±a4âÆËmş™]~6Ç#!SÙ­£b1—;99zš;Ò¿±ÁäÊ_³AdMøÙïxwD†=                                     ü)ü'ôA«~®    IEND®B`‚                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           