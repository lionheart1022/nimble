g);
			} else {
				$key = array_keys( $installed_plugin );
				$key = reset( $key ); //Use the first plugin regardless of the name, Could have issues for multiple-plugins in one directory if they share different version numbers
				$update_file = $api->slug . '/' . $key;
				if ( version_compare($api->version, $installed_plugin[ $key ]['Version'], '=') ){
					$status = 'latest_installed';
				} elseif ( version_compare($api->version, $installed_plugin[ $key ]['Version'], '<') ) {
					$status = 'newer_installed';
					$version = $installed_plugin[ $key ]['Version'];
				} else {
					//If the above update check failed, Then that probably means that the update checker has out-of-date information, force a refresh
					if ( ! $loop ) {
						delete_site_transient('update_plugins');
						wp_update_plugins();
						return install_plugin_install_status($api, true);
					}
				}
			}
		} else {
			// "install" & no directory with that slug
			if ( current_user_can('install_plugins') )
				$url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $api->slug), 'install-plugin_' . $api->slug);
		}
	}
	if ( isset($_GET['from']) )
		$url .= '&amp;from=' . urlencode( wp_unslash( $_GET['from'] ) );

	$file = $update_file;
	return compact( 'status', 'url', 'version', 'file' );
}

/**
 * Display plugin information in dialog box form.
 *
 * @since 2.7.0
 *
 * @global string $tab
 * @global string $wp_version
 */
function install_plugin_information() {
	global $tab;

	if ( empty( $_REQUEST['plugin'] ) ) {
		return;
	}

	$api = plugins_api( 'plugin_information', array(
		'slug' => wp_unslash( $_REQUEST['plugin'] ),
		'is_ssl' => is_ssl(),
		'fields' => array(
			'banners' => true,
			'reviews' => true,
			'downloaded' => false,
			'active_installs' => true
		)
	) );

	if ( is_wp_error( $api ) ) {
		wp_die( $api );
	}

	$plugins_allowedtags = array(
		'a' => array( 'href' => array(), 'title' => array(), 'target' => array() ),
		'abbr' => array( 'title' => array() ), 'acronym' => array( 'title' => array() ),
		'code' => array(), 'pre' => array(), 'em' => array(), 'strong' => array(),
		'div' => array( 'class' => array() ), 'span' => array( 'class' => array() ),
		'p' => array(), 'ul' => array(), 'ol' => array(), 'li' => array(),
		'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
		'img' => array( 'src' => array(), 'class' => array(), 'alt' => array() )
	);

	$plugins_section_titles = array(
		'description'  => _x( 'Description',  'Plugin installer section title' ),
		'installation' => _x( 'Installation', 'Plugin installer section title' ),
		'faq'          => _x( 'FAQ',          'Plugin installer section title' ),
		'screenshots'  => _x( 'Screenshots',  'Plugin installer section title' ),
		'changelog'    => _x( 'Changelog',    'Plugin installer section title' ),
		'reviews'     