if ( empty( $args['html_content'] ) )
				$media = self::from_html( $post_id ); // Use the post_id, which will load the content
			else
				$media = self::from_html( $args['html_content'] ); // If html_content is provided, use that
		}

		if ( !$media && $args['fallback_to_avatars'] ) {
			$media = self::from_blavatar( $post_id, $args['avatar_size'] );
			if ( !$media )
				$media = self::from_gravatar( $post_id, $args['avatar_size'], $args['gravatar_default'] );
		}

		/**
		 * Filters the array of images that would be good for a specific post.
		 * This filter is applied after options ($args) filter the original array.
		 *
		 * @since 2.0.0
		 *
		 * @param array $media Array of images that would be good for a specific post.
		 * @param int $post_id Post ID.
		 * @param array $args Array of options to get images.
		 */
		return apply_filters( 'jetpack_images_get_images', $media, $post_id, $args );
	}

	/**
	 * Takes an image URL and pixel dimensions then returns a URL for the
	 * resized and croped image.
	 *
	 * @param  string $src
	 * @param  int    $dimension
	 * @return string            Transformed image URL
	 */
	static function fit_image_url( $src, $width, $height ) {
		$width = (int) $width;
		$height = (int) $height;

		// Umm...
		if ( $width < 1 || $height < 1 ) {
			return $src;
		}

		// See if we should bypass WordPress.com SaaS resizing
		if ( has_filter( 'jetpack_images_fit_image_url_override' ) ) {
			/**
			 * Filters the image URL used after dimensions are set by Photon.
			 *
			 * @since 3.3.0
			 *
			 * @param string $src Image URL.
			 * @param int $width Image width.
			 * @param int $width Image height.
			 */
			return apply_filters( 'jetpack_images_fit_image_url_override', $src, $width, $height );
		}

		// If WPCOM hosted image use native transformations
		$img_host = parse_url( $src, PHP_URL_HOST );
		if ( '.files.wordpress.com' == substr( $img_host, -20 ) ) {
			return add_query_arg( array( 'w' => $width, 'h' => $height, 'crop' => 1 ), $src );
		}

		// Use Photon magic
		if( function_exists( 'jetpack_photon_url' ) ) {
			return jetpack_photon_url( $src, array( 'resize' => "$width,$height" ) );
		}

		// Arg... no way to resize image using WordPress.com infrastructure!
		return $src;
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php

/**
 * Used to manage Jetpack installation on Multisite Network installs
 *
 * SINGLETON: To use call Jetpack_Network::init()
 *
 * DO NOT USE ANY STATIC METHODS IN THIS CLASS!!!!!!
 *
 * @since 2.9
 */
class Jetpack_Network {

	/**
	 * Holds a static copy of Jetpack_Network for the singleton
	 *
	 * @since 2.9
	 * @var Jetpack_Network
	 */
	private static $instance = null;

	/**
	 * Name of the network wide settings
	 *
	 * @since 2.9
	 * @var string
	 */
	private $settings_name = 'jetpack-network-settings';

	/**
	 * Defaults for settings found on the Jetpack > Settings page
	 *
	 * @since 2.9
	 * @var array
	 */
	private $setting_defaults = array(
		'auto-connect'                  => 0,
		'sub-site-connection-override'  => 1,
		//'manage_auto_activated_modules' => 0,
	);

	/**
	 * Constructor
	 *
	 * @since 2.9
	 */
	private function __construct() {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' ); // For the is_plugin... check
		require_once( JETPACK__PLUGIN_DIR . 'modules/protect/shared-functions.php' ); // For managing the global whitelist
		/*
		 * Sanity check to ensure the install is Multisite and we
		 * are in Network Admin
		 */
		if ( is_multisite() && is_network_admin() ) {
			add_action( 'network_admin_menu', array( $this, 'add_network_admin_menu' ) );
			add_action( 'network_admin_edit_jetpack-network-settings', array( $this, 'save_network_settings_page' ), 10, 0 );
			add_filter( 'admin_body_class', array( $this, 'body_class' ) );

			if ( isset( $_GET['page'] ) && 'jetpack' == $_GET['page'] ) {
				add_action( 'admin_init', array( $this, 'jetpack_sites_list' ) );
			}
		}

		/*
		 * Things that should only run on multisite
		 */
		if ( is_multisite() && is_plugin_active_for_network( 'jetpack/jetpack.php' ) ) {
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_to_menubar' ) );

			/*
			 * If admin wants to automagically register new sites set the hook here
			 *
			 * This is a hacky way because xmlrpc is not available on wpmu_new_blog
			 */
			if ( $this->get_option( 'auto-connect' ) == 1 ) {
				add_action( 'wpmu_new_blog', array( $this, 'do_automatically_add_new_site' ) );
			}
		}

		// Remove the toggles for 2.9, re-evaluate how they're done and added for a 3.0 release. They don't feel quite right yet.
		// add_filter( 'jetpack_get_default_modules', array( $this, 'set_auto_activated_modules' ) );
	}

	/**
	 * Sets which modules get activated by default on subsite connection.
	 * Modules can be set in Network Admin > Jetpack > Settings
	 *
	 * @since 2.9
	 *
	 * @param array $modules
	 *
	 * @return array
	 **/
	public function set_auto_activated_modules( $modules ) {
		return $modules;

		/* Remove the toggles for 2.9, re-evaluate how they're done and added for a 3.0 release. They don't feel quite right yet.
		if( 1 == $this->get_option( 'manage_auto_activated_modules' ) ) {
			return (array) $this->get_option( 'modules' );
		} else {
			return $modules;
		}
		*/
	}

	/**
	 * Registers new sites upon creation
	 *
	 * @since 2.9
	 * @uses  wpmu_new_blog
	 *
	 * @param int $blog_id
	 **/
	public function do_automatically_add_new_site( $blog_id ) {
		$this->do_subsiteregister( $blog_id );
	}

	/**
	 * Adds .network-admin class to the body tag
	 * Helps distinguish network admin JP styles from regular site JP styles
	 *
	 * @since 2.9
	 */
	public function body_class( $classes ) {
		return trim( $classes ) . ' network-admin ';
	}

	/**
	 * Provides access to an instance of Jetpack_Network
	 *
	 * This is how the Jetpack_Network object should *always* be accessed
	 *
	 * @since 2.9
	 * @return Jetpack_Network
	 */
	public static function init() {
		if ( ! self::$instance || ! is_a( self::$instance, 'Jetpack_Network' ) ) {
			self::$instance = new Jetpack_Network;
		}

		return self::$instance;
	}

	/**
	 * Registers the Multisite admin bar menu item shortcut.
	 * This shortcut helps users quickly and easily navigate to the Jetpack Network Admin
	 * menu from anywhere in their network.
	 *
	 * @since 2.9
	 */
	public function register_menubar() {
		add_action( 'wp_before_admin_bar_render', array( $this, 'add_to_menubar' ) );
	}

	/**
	 * Runs when Jetpack is deactivated from the network admin plugins menu.
	 * Each individual site will need to have Jetpack::disconnect called on it.
	 * Site that had Jetpack individually enabled will not be disconnected as
	 * on Multisite individually activated plugins are still activated when
	 * a plugin is deactivated network wide.
	 *
	 * @since 2.9
	 **/
	public function deactivate() {
		// Only fire if in network admin
		if ( ! is_network_admin() ) {
			return;
		}

		$sites = $this->wp_get_sites();

		foreach ( $sites as $s ) {
			switch_to_blog( $s->blog_id );
			$active_plugins = get_option( 'active_plugins' );

			/*
			 * If this plugin was activated in the subsite individually
			 * we do not want to call disconnect. Plugins activated
		 	 * individually (before network activation) stay activated
		 	 * when the network deactivation occurs
		 	 */
			if ( ! in_array( 'jetpack/jetpack.php', $active_plugins ) ) {
				Jetpack::disconnect();
			}
		}
		restore_current_blog();
	}

	/**
	 * Adds a link to the Jetpack Network Admin page in the network admin menu bar.
	 *
	 * @since 2.9
	 **/
	public function add_to_menubar() {
		global $wp_admin_bar;
		// Don't show for logged out users or single site mode.
		if ( ! is_user_logged_in() || ! is_multisite() ) {
			return;
		}

		$wp_admin_bar->add_node( array(
			'parent' => 'network-admin',
			'id'     => 'network-admin-jetpack',
			'title'  => __( 'Jetpack', 'jetpack' ),
			'href'   => $this->get_url( 'network_admin_page' ),
		) );
	}

	/**
	 * Returns various URL strings. Factory like
	 *
	 * $args can be a string or an array.
	 * If $args is an array there must be an element called name for the switch statement
	 *
	 * Currently supports:
	 * - subsiteregister: Pass array( 'name' => 'subsiteregister', 'site_id' => SITE_ID )
	 * - network_admin_page: Provides link to /wp-admin/network/JETPACK
	 * - subsitedisconnect: Pass array( 'name' => 'subsitedisconnect', 'site_id' => SITE_ID )
	 *
	 * @since 2.9
	 *
	 * @param Mixed $args
	 *
	 * @return String
	 **/
	public function get_url( $args ) {
		$url = null; // Default url value

		if ( is_string( $args ) ) {
			$name = $args;
		} else {
			$name = $args['name'];
		}

		switch ( $name ) {
			case 'subsiteregister':
				if ( ! isset( $args['site_id'] ) ) {
					break; // If there is not a site id present we cannot go further
				}
				$url = network_admin_url(
					'admin.php?page=jetpack&action=subsiteregister&site_id='
					. $args['site_id']
				);
				break;

			case 'network_admin_page':
				$url = network_admin_url( 'admin.php?page=jetpack' );
				break;

			case 'subsitedisconnect':
				if ( ! isset( $args['site_id'] ) ) {
					break; // If there is not a site id present we cannot go further
				}
				$url = network_admin_url(
					'admin.php?page=jetpack&action=subsitedisconnect&site_id='
					. $args['site_id']
				);
				break;
		}

		return $url;
	}

	/**
	 * Adds the Jetpack  menu item to the Network Admin area
	 *
	 * @since 2.9
	 */
	public function add_network_admin_menu() {
		add_menu_page( __( 'Jetpack', 'jetpack' ), __( 'Jetpack', 'jetpack' ), 'jetpack_network_admin_page', 'jetpack', array( $this, 'network_admin_page' ), 'div', 3 );
		add_submenu_page( 'jetpack', __( 'Jetpack Sites', 'jetpack' ), __( 'Sites', 'jetpack' ), 'jetpack_network_sites_page', 'jetpack', array( $this, 'network_admin_page' ) );
		add_submenu_page( 'jetpack', __( 'Settings', 'jetpack' ), __( 'Settings', 'jetpack' ), 'jetpack_network_settings_page', 'jetpack-settings', array( $this, 'render_network_admin_settings_page' ) );

		/**
		 * As jetpack_register_genericons is by default fired off a hook,
		 * the hook may have already fired by this point.
		 * So, let's just trigger it manually.
		 */
		require_once( JETPACK__PLUGIN_DIR . '_inc/genericons.php' );
		jetpack_register_genericons();

		if ( ! wp_style_is( 'jetpack-icons', 'registered' ) ) {
			wp_register_style( 'jetpack-icons', plugins_url( 'css/jetpack-icons.min.css', JETPACK__PLUGIN_FILE ), false, JETPACK__VERSION );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_menu_css' ) );
	}

	/**
	 * Adds JP menu icon
	 *
	 * @since 2.9
	 **/
	function admin_menu_css() {
		wp_enqueue_style( 'jetpack-icons' );
	}

	/**
	 * Provides functionality for the Jetpack > Sites page.
	 * Does not do the display!
	 *
	 * @since 2.9
	 */
	public function jetpack_sites_list() {
		Jetpack::init();

		if ( isset( $_GET['action'] ) ) {
			switch ( $_GET['action'] ) {
				case 'subsiteregister':
					/*
					 * @todo check_admin_referer( 'jetpack-subsite-register' );
					 */
					Jetpack::log( 'subsiteregister' );

					// If !$_GET['site_id'] stop registration and error
					if ( ! isset( $_GET['site_id'] ) || empty( $_GET['site_id'] ) ) {
						// Log error to state cookie for display later
						/**
						 * @todo Make state messages show on Jetpack NA pages
						 **/
						Jetpack::state( 'missing_site_id', 'Site ID must be provided to register a sub-site' );
						break;
					}

					// Send data to register endpoint and retrieve shadow blog details
					$result = $this->do_subsiteregister();
					$url    = $this->get_url( 'network_admin_page' );

					if ( is_wp_error( $result ) ) {
						$url = add_query_arg( 'action', 'connection_failed', $url );
					} else {
						$url = add_query_arg( 'action', 'connected', $url );
					}

					wp_safe_redirect( $url );
					break;

				case 'subsitedisconnect':
					Jetpack::log( 'subsitedisconnect' );

					if ( ! isset( $_GET['site_id'] ) || empty( $_GET['site_id'] ) ) {
						Jetpack::state( 'missing_site_id', 'Site ID must be provided to disconnect a sub-site' );
						break;
					}

					$this->do_subsitedisconnect();
					break;

				case 'connected':
				case 'connection_failed':
					add_action( 'jetpack_notices', array( $this, 'show_jetpack_notice' ) );
					break;
			}
		}
	}

	public function show_jetpack_notice() {
		if ( isset( $_GET['action'] ) && 'connected' == $_GET['action'] ) {
			$notice = __( 'Site successfully connected.', 'jetpack' );
		} else if ( isset( $_GET['action'] ) && 'connection_failed' == $_GET['action'] ) {
			$notice = __( 'Site connection <strong>failed</strong>', 'jetpack' );
		}

		Jetpack::init()->load_view( 'admin/network-admin-alert.php', array( 'notice' => $notice ) );
	}

	/**
	 * Disconnect functionality for an individual site
	 *
	 * @since 2.9
	 * @see   Jetpack_Network::jetpack_sites_list()
	 */
	public function do_subsitedisconnect( $site_id = null ) {
		if ( ! current_user_can( 'jetpack_disconnect' ) ) {
			return;
		}
		$site_id = ( is_null( $site_id ) ) ? $_GET['site_id'] : $site_id;
		switch_to_blog( $site_id );
		Jetpack::disconnect();
		restore_current_blog();
	}

	/**
	 * Registers a subsite with the Jetpack servers
	 *
	 * @since 2.9
	 * @todo  Break apart into easier to manage chunks that can be unit tested
	 * @see   Jetpack_Network::jetpack_sites_list();
	 */
	public function do_subsiteregister( $site_id = null ) {
		if ( ! current_user_can( 'jetpack_disconnect' ) ) {
			return;
		}

		if ( Jetpack::is_development_mode() ) {
			return;
		}

		$jp = Jetpack::init();

		// Figure out what site we are working on
		$site_id = ( is_null( $site_id ) ) ? $_GET['site_id'] : $site_id;

		// Build secrets to sent to wpcom for verification
		$secrets = $jp->generate_secrets();

		// Remote query timeout limit
		$timeout = $jp->get_remote_query_timeout_limit();

		// The blog id on WordPress.com of the primary network site
		$network_wpcom_blog_id = Jetpack_Options::get_option( 'id' );

		/*
		 * Here we need to switch to the subsite
		 * For the registration process we really only hijack how it
		 * works for an individual site and pass in some extra data here
		 */
		switch_to_blog( $site_id );

		// Save the secrets in the subsite so when the wpcom server does a pingback it
		// will be able to validate the connection
		Jetpack_Options::update_option( 'register',
			$secrets[0] . ':' . $secrets[1] . ':' . $secrets[2]
		);

		// Gra info for gmt offset
		$gmt_offset = get_option( 'gmt_offset' );
		if ( ! $gmt_offset ) {
			$gmt_offset = 0;
		}

		/*
		 * Get the stats_option option from the db.
		 * It looks like the server strips this out so maybe it is not necessary?
		 * Does it match the Jetpack site with the old stats plugin id?
		 *
		 * @todo Find out if sending the stats_id is necessary
		 */
		$stat_options = get_option( 'stats_options' );
		$stat_id = $stat_options = isset( $stats_options['blog_id'] ) ? $stats_options['blog_id'] : null;

		$args = array(
			'method'  => 'POST',
			'body'    => array(
				'network_url'           => $this->get_url( 'network_admin_page' ),
				'network_wpcom_blog_id' => $network_wpcom_blog_id,
				'siteurl'               => site_url(),
				'home'                  => home_url(),
				'gmt_offset'            => $gmt_offset,
				'timezone_string'       => (string) get_option( 'timezone_string' ),
				'site_name'             => (string) get_option( 'blogname' ),
				'secret_1'              => $secrets[0],
				'secret_2'              => $secrets[1],
				'site_lang'             => get_locale(),
				'timeout'               => $timeout,
				'stats_id'              => $stat_id, // Is this still required?
				'user_id'               => get_current_user_id(),
			),
			'headers' => array(
				'Accept' => 'application/json',
			),
			'timeout' => $timeout,
		);

		// Attempt to retrieve shadow blog details
		$response = Jetpack_Client::_wp_remote_request(
			Jetpack::fix_url_for_bad_hosts( Jetpack::api_url( 'subsiteregister' ) ), $args, true
		);

		/*
		 * $response should either be invalid or contain:
		 * - jetpack_id	=> id
		 * - jetpack_secret => blog_token
		 * - jetpack_public
		 *
		 * Store the wpcom site details
		 */
		$valid_response = $jp->validate_remote_register_response( $response );

		if ( is_wp_error( $valid_response ) || ! $valid_response ) {
			restore_current_blog();
			return $valid_response;
		}

		// Grab the response values to work with
		$code   = wp_remote_retrieve_response_code( $response );
		$entity = wp_remote_retrieve_body( $response );
		if ( $entity ) {
			$json = json_decode( $entity );
		} else {
			$json = false;
		}

		if ( empty( $json->jetpack_secret ) || ! is_string( $json->jetpack_secret ) ) {
			restore_current_blog();
			return new Jetpack_Error( 'jetpack_secret', '', $code );
		}

		if ( isset( $json->jetpack_public ) ) {
			$jetpack_public = (int) $json->jetpack_public;
		} else {
			$jetpack_public = false;
		}

		Jetpack_Options::update_options( array(
			'id'         => (int) $json->jetpack_id,
			'blog_token' => (string) $json->jetpack_secret,
			'public'     => $jetpack_public,
		) );

		/*
		 * Update the subsiteregister method on wpcom so that it also sends back the
		 * token in this same request
		 */
		$is_master_user = ! Jetpack::is_active();
		Jetpack::update_user_token(
			get_current_user_id(),
			sprintf( '%s.%d', $json->token->secret, get_current_user_id() ),
			$is_master_user
		);

		Jetpack::activate_default_modules();

		restore_current_blog();
	}

	/**
	 * Handles the displaying of all sites on the network that are
	 * dis/connected to Jetpack
	 *
	 * @since 2.9
	 * @see   Jetpack_Network::jetpack_sites_list()
	 */
	function network_admin_page() {
		global $current_site;
		$this->network_admin_page_header();

		$jp = Jetpack::init();

		// We should be, but ensure we are on the main blog
		switch_to_blog( $current_site->blog_id );
		$main_active = $jp->is_active();
		restore_current_blog();

		// If we are in dev mode, just show the notice and bail
		if ( Jetpack::is_development_mode() ) {
			Jetpack::show_development_mode_notice();
			return;
		}

		/*
		 * Ensure the main blog is connected as all other subsite blog
		 * connections will feed off this one
		 */
		if ( ! $main_active ) {
			$url  = $this->get_url( array(
				'name'    => 'subsiteregister',
				'site_id' => 1,
			) );
			$data = array( 'url' => $jp->build_connect_url() );
			Jetpack::init()->load_view( 'admin/must-connect-main-blog.php', $data );

			return;
		}

		require_once( 'class.jetpack-network-sites-list-table.php' );
		$myListTable = new Jetpack_Network_Sites_List_Table();
		echo '<div class="wrap"><h2>' . __( 'Sites', 'jetpack' ) . '</h2>';
		echo '<form method="post">';
		$myListTable->prepare_items();
		$myListTable->display();
		echo '</form></div>';

		$this->network_admin_page_footer();
	}

	/**
	 * Stylized JP header formatting
	 *
	 * @since 2.9
	 */
	function network_admin_page_header() {
		global $current_user;

		$is_connected = Jetpack::is_active();

		$data = array(
			'is_connected' => $is_connected
		);
		Jetpack::init()->load_view( 'admin/network-admin-header.php', $data );
	}

	/**
	 * Stylized JP footer formatting
	 *
	 * @since 2.9
	 */
	function network_admin_page_footer() {
		Jetpack::init()->load_view( 'admin/network-admin-footer.php' );
	}

	/**
	 * Fires when the Jetpack > Settings page is saved.
	 *
	 * @since 2.9
	 */
	public function save_network_settings_page() {

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'jetpack-network-settings' ) ) {
			// no nonce, push back to settings page
			wp_safe_redirect(
				add_query_arg(
					array( 'page' => 'jetpack-settings' ),
					network_admin_url( 'admin.php' )
				)
			);
			exit();
		}

		// try to save the Protect whitelist before anything else, since that action can result in errors
		$whitelist = str_replace( ' ', '', $_POST['global-whitelist'] );
		$whitelist = explode( PHP_EOL, $whitelist );
		$result    = jetpack_protect_save_whitelist( $whitelist, $global = true );
		if ( is_wp_error( $result ) ) {
			wp_safe_redirect(
				add_query_arg(
					array( 'page' => 'jetpack-settings', 'error' => 'jetpack_protect_whitelist' ),
					network_admin_url( 'admin.php' )
				)
			);
			exit();
		}

		/*
		 * Fields
		 *
		 * auto-connect - Checkbox for global Jetpack connection
		 * sub-site-connection-override - Allow sub-site admins to (dis)reconnect with their own Jetpack account
		 */
		$auto_connect = 0;
		if ( isset( $_POST['auto-connect'] ) ) {
			$auto_connect = 1;
		}

		$sub_site_connection_override = 0;
		if ( isset( $_POST['sub-site-connection-override'] ) ) {
			$sub_site_connection_override = 1;
		}

		/* Remove the toggles for 2.9, re-evaluate how they're done and added for a 3.0 release. They don't feel quite right yet.
		$manage_auto_activated_modules = 0;
		if ( isset( $_POST['manage_auto_activated_modules'] ) ) {
			$manage_auto_activated_modules = 1;
		}

		$modules = array();
		if ( isset( $_POST['modules'] ) ) {
			$modules = $_POST['modules'];
		}
		*/

		$data = array(
			'auto-connect'                  => $auto_connect,
			'sub-site-connection-override'  => $sub_site_connection_override,
			//'manage_auto_activated_modules' => $manage_auto_activated_modules,
			//'modules'                       => $modules,
		);

		update_site_option( $this->settings_name, $data );
		wp_safe_redirect(
			add_query_arg(
				array( 'page' => 'jetpack-settings', 'updated' => 'true' ),
				network_admin_url( 'admin.php' )
			)
		);
		exit();
	}

	public function render_network_admin_settings_page() {
		$this->network_admin_page_header();
		$options = wp_parse_args( get_site_option( $this->settings_name ), $this->setting_defaults );

		$modules = array();
		$module_slugs = Jetpack::get_available_modules();
		foreach ( $module_slugs as $slug ) {
			$module           = Jetpack::get_module( $slug );
			$module['module'] = $slug;
			$modules[]        = $module;
		}

		usort( $modules, array( 'Jetpack', 'sort_modules' ) );

		if ( ! isset( $options['modules'] ) ) {
			$options['modules'] = $modules;
		}

		$data = array(
			'modules' => $modules,
			'options' => $options,
			'jetpack_protect_whitelist' => jetpack_protect_format_whitelist(),
		);

		Jetpack::init()->load_view( 'admin/network-settings.php', $data );
		$this->network_admin_page_footer();
	}

	/**
	 * Updates a site wide option
	 *
	 * @since 2.9
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return boolean
	 **/
	public function update_option( $key, $value ) {
		$options  = get_site_option( $this->settings_name, $this->setting_defaults );
		$options[ $key ] = $value;

		return update_site_option( $this->settings_name, $options );
	}

	/**
	 * Retrieves a site wide option
	 *
	 * @since 2.9
	 *
	 * @param string $name - Name of the option in the database
	 **/
	public function get_option( $name ) {
		$options = get_site_option( $this->settings_name, $this->setting_defaults );
		$options = wp_parse_args( $options, $this->setting_defaults );

		if ( ! isset( $options[ $name ] ) ) {
			$options[ $name ] = null;
		}

		return $options[ $name ];
	}

	/**
	 * Return an array of sites on the specified network. If no network is specified,
	 * return all sites, regardless of network.
	 *
	 * @todo REMOVE THIS FUNCTION! This function is moving to core. Use that one in favor of this. WordPress::wp_get_sites(). http://codex.wordpress.org/Function_Reference/wp_get_sites NOTE, This returns an array instead of stdClass. Be sure to update class.network-sites-list-table.php
	 * @since 2.9
	 * @deprecated 2.4.5
	 *
	 * @param array|string $args Optional. Specify the status of the sites to return.
	 *
	 * @return array An array of site data
	 */
	public function wp_get_sites( $args = array() ) {
		global $wpdb;

		if ( wp_is_large_network() ) {
			return;
		}

		$defaults = array( 'network_id' => $wpdb->siteid );
		$args     = wp_parse_args( $args, $defaults );
		$query    = "SELECT * FROM $wpdb->blogs WHERE 1=1 ";

		if ( isset( $args['network_id'] ) && ( is_array( $args['network_id'] ) || is_numeric( $args['network_id'] ) ) ) {
			$network_ids = array_map( 'intval', (array) $args['network_id'] );
			$network_ids = implode( ',', $network_ids );
			$query .= "AND site_id IN ($network_ids) ";
		}

		if ( isset( $args['public'] ) ) {
			$query .= $wpdb->prepare( "AND public = %d ", $args['public'] );
		}

		if ( isset( $args['archived'] ) ) {
			$query .= $wpdb->prepare( "AND archived = %d ", $args['archived'] );
		}

		if ( isset( $args['mature'] ) ) {
			$query .= $wpdb->prepare( "AND mature = %d ", $args['mature'] );
		}

		if ( isset( $args['spam'] ) ) {
			$query .= $wpdb->prepare( "AND spam = %d ", $args['spam'] );
		}

		if ( isset( $args['deleted'] ) ) {
			$query .= $wpdb->prepare( "AND deleted = %d ", $args['deleted'] );
		}

		if ( isset( $args['exclude_blogs'] ) ) {
			$query .= "AND blog_id NOT IN (" . implode( ',', $args['exclude_blogs'] ) . ")";
		}

		$key = 'wp_get_sites:' . md5( $query );

		if ( ! $site_results = wp_cache_get( $key, 'site-id-cache' ) ) {
			$site_results = (array) $wpdb->get_results( $query );
			wp_cache_set( $key, $site_results, 'site-id-cache' );
		}

		return $site_results;
	}
}

// end class
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php

/**
 * Jetpack just in time messaging through out the admin
 *
 * @since 3.7.0
 */
class Jetpack_JITM {

	/**
	 * @var Jetpack_JITM
	 **/
	private static $instance = null;

	/**
	 * Get user dismissed messages.
	 *
	 * @var array
	 */
	private static $jetpack_hide_jitm = null;

	/**
	 * Whether plugin auto updates are allowed in this WordPress installation or not.
	 *
	 * @var bool
	 */
	private static $auto_updates_allowed = false;

	static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Jetpack_JITM;
		}

		return self::$instance;
	}

	private function __construct() {
		if ( ! Jetpack::is_active() || self::is_jitm_dismissed() ) {
			return;
		}
		add_action( 'current_screen', array( $this, 'prepare_jitms' ) );
	}

	/**
	 * Prepare actions according to screen and post type.
	 *
	 * @since 3.8.2
	 *
	 * @uses Jetpack_Autoupdate::get_possible_failures()
	 *
	 * @param object $screen
	 */
	function prepare_jitms( $screen ) {
		if ( ! current_user_can( 'jetpack_manage_modules' ) ) {
			return;
		}

		if ( 'edit-comments' == $screen->base && ! Jetpack::is_plugin_active( 'akismet/akismet.php' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'jitm_enqueue_files' ) );
			add_action( 'admin_notices', array( $this, 'akismet_msg' ) );
		}
		elseif (
			'post' == $screen->base
			&& ( isset( $_GET['message'] ) && 6 == $_GET['message'] )
			&& ! Jetpack::is_plugin_active( 'vaultpress/vaultpress.php' )
		) {
			add_action( 'admin_enqueue_scripts', array( $this, 'jitm_enqueue_files' ) );
			add_action( 'edit_form_top', array( $this, 'backups_after_publish_msg' ) );
		}
		elseif ( 'update-core' == $screen->base && ! Jetpack::is_plugin_active( 'vaultpress/vaultpress.php' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'jitm_enqueue_files' ) );
			add_action( 'admin_notices', array( $this, 'backups_updates_msg' ) );
		}
	}

	/*
	 * Present Manage just in time activation msg on update-core.php
	 *
	 */
	function manage_msg() {
		$normalized_site_url = Jetpack::build_raw_urls( get_home_url() );
		?>
		<div class="jp-jitm">
			<a href="#" data-module="manage" class="dismiss"><span class="genericon genericon-close"></span></a>

			<div class="jp-emblem">
				<?php echo self::get_jp_emblem(); ?>
			</div>
			<p class="msg">
				<?php esc_html_e( 'Reduce security risks with automated plugin updates.', 'jetpack' ); ?>
			</p>

			<p>
				<img class="j-spinner hide" src="<?php echo esc_url( includes_url( 'images/spinner-2x.gif' ) ); ?>" alt="<?php echo esc_attr__( 'Loading...', 'jetpack' ); ?>" /><a href="#" data-module="manage" class="activate button <?php if ( Jetpack::is_module_active( 'manage' ) ) {
					echo 'hide';
				} ?>"><?php esc_html_e( 'Activate Now', 'jetpack' ); ?></a><a href="<?php echo esc_url( 'https://wordpress.com/plugins/' . $normalized_site_url ); ?>" target="_blank" title="<?php esc_attr_e( 'Go to WordPress.com to try these features', 'jetpack' ); ?>" id="jetpack-wordpressdotcom" class="button button-jetpack <?php if ( ! Jetpack::is_module_active( 'manage' ) ) {
					echo 'hide';
				} ?>"><?php esc_html_e( 'Go to WordPress.com', 'jetpack' ); ?></a>
			</p>
		</div>
		<?php
		//jitm is being viewed, track it
		$jetpack = Jetpack::init();
		$jetpack->stat( 'jitm', 'manage-viewed-' . JETPACK__VERSION );
		$jetpack->do_stats( 'server_side' );
	}

	/*
	 * Present Photon just in time activation msg
	 *
	 */
	function photon_msg() {
		?>
		<div class="jp-jitm">
			<a href="#" data-module="photon" class="dismiss"><span class="genericon genericon-close"></span></a>

			<div class="jp-emblem">
				<?php echo self::get_jp_emblem(); ?>
			</div>
			<p class="msg">
				<?php esc_html_e( 'Speed up your photos and save bandwidth costs by using a free content delivery network.', 'jetpack' ); ?>
			</p>

			<p>
				<img class="j-spinner hide" style="margin-top: 13px;" width="17" height="17" src="<?php echo esc_url( includes_url( 'images/spinner-2x.gif' ) ); ?>" alt="<?php echo esc_attr__( 'Loading...', 'jetpack' ); ?>" /><a href="#" data-module="photon" class="activate button button-jetpack"><?php esc_html_e( 'Activate Photon', 'jetpack' ); ?></a>
			</p>
		</div>
		<?php
		//jitm is being viewed, track it
		$jetpack = Jetpack::init();
		$jetpack->stat( 'jitm', 'photon-viewed-' . JETPACK__VERSION );
		$jetpack->do_stats( 'server_side' );
	}

	/**
	 * Display Photon JITM template in Media Library after user uploads an image.
	 *
	 * @since 3.9.0
	 */
	function photon_tmpl() {
		?>
		<script id="tmpl-jitm-photon" type="text/html">
			<div class="jp-jitm" data-track="photon-modal">
				<a href="#" data-module="photon" class="dismiss"><span class="genericon genericon-close"></span></a>

				<div class="jp-emblem">
					<?php echo self::get_jp_emblem(); ?>
				</div>
				<p class="msg">
					<?php esc_html_e( 'Let Jetpack deliver your images optimized and faster than ever.', 'jetpack' ); ?>
				</p>

				<p>
					<img class="j-spinner hide" style="margin-top: 13px;" width="17" height="17" src="<?php echo esc_url( includes_url( 'images/spinner-2x.gif' ) ); ?>" alt="<?php echo esc_attr__( 'Loading...', 'jetpack' ); ?>" /><a href="#" data-module="photon" class="activate button button-jetpack"><?php esc_html_e( 'Activate Photon', 'jetpack' ); ?></a>
				</p>
			</div>
		</script>
		<?php
	}

	/**
	 * Display message prompting user to enable auto-updates in WordPress.com.
	 *
	 * @since 3.8.2
	 */
	function manage_pi_msg() {
		$normalized_site_url = Jetpack::build_raw_urls( get_home_url() );
		$manage_active       = Jetpack::is_module_active( 'manage' );

		// Check if plugin has auto update already enabled in WordPress.com and don't show JITM in such case.
		$active_before = get_option( 'jetpack_temp_active_plugins_before', array() );
		delete_option( 'jetpack_temp_active_plugins_before' );
		$active_now                  = get_option( 'active_plugins', array() );
		$activated                   = array_diff( $active_now, $active_before );
		$auto_update_plugin_list     = Jetpack_Options::get_option( 'autoupdate_plugins', array() );
		$plugin_auto_update_disabled = false;
		foreach ( $activated as $plugin ) {
			if ( ! in_array( $plugin, $auto_update_plugin_list ) ) {

				// Plugin doesn't have auto updates enabled in WordPress.com yet.
				$plugin_auto_update_disabled = true;

				// We don't need to continue checking, it's ok to show JITM for this plugin.
				break;
			}
		}

		// Check if the activated plugin is in the WordPress.org repository
		$plugin_can_auto_update = false;
		$plugin_updates 		= get_site_transient( 'update_plugins' );
		if ( false === $plugin_updates ) {

			// If update_plugins doesn't exist, display message anyway
			$plugin_can_auto_update = true;
		} else {
			$plugin_updates = array_merge( $plugin_updates->response, $plugin_updates->no_update );
			foreach ( $activated as $plugin ) {
				if ( isset( $plugin_updates[ $plugin ] ) ) {

					// There's at least one plugin set cleared for auto updates
					$plugin_can_auto_update = true;

					// We don't need to continue checking, it's ok to show JITM for this round.
					break;
				}
			}
		}

		if ( ! $manage_active && $plugin_auto_update_disabled && $plugin_can_auto_update && self::$auto_updates_allowed ) :
			?>
			<div class="jp-jitm">
				<a href="#" data-module="manage-pi" class="dismiss"><span class="genericon genericon-close"></span></a>

				<div class="jp-emblem">
					<?php echo self::get_jp_emblem(); ?>
				</div>
				<?php if ( ! $manage_active ) : ?>
					<p class="msg">
						<?php esc_html_e( 'Save time with automated plugin updates.', 'jetpack' ); ?>
					</p>
					<p>
						<img class="j-spinner hide" src="<?php echo esc_url( includes_url( 'images/spinner-2x.gif' ) ); ?>" alt="<?php echo esc_attr__( 'Loading...', 'jetpack' ); ?>" /><a href="#" data-module="manage" data-module-success="<?php esc_attr_e( 'Success!', 'jetpack' ); ?>" class="activate button"><?php esc_html_e( 'Activate remote management', 'jetpack' ); ?></a>
					</p>
				<?php elseif ( $manage_active ) : ?>
					<p>
						<?php esc_html_e( 'Save time with auto updates on WordPress.com', 'jetpack' ); ?>
					</p>
				<?php endif; // manage inactive
				?>
				<p class="show-after-enable <?php echo $manage_active ? '' : 'hide'; ?>">
					<a href="<?php echo esc_url( 'https://wordpress.com/plugins/' . $normalized_site_url ); ?>" target="_blank" title="<?php esc_attr_e( 'Go to WordPress.com to enable auto-updates for plugins', 'jetpack' ); ?>" data-module="manage-pi" class="button button-jetpack launch show-after-enable"><?php if ( ! $manage_active ) : ?><?php esc_html_e( 'Enable auto-updates on WordPress.com', 'jetpack' ); ?><?php elseif ( $manage_active ) : ?><?php esc_html_e( 'Enable auto-updates', 'jetpack' ); ?><?php endif; // manage inactive ?></a>
				</p>
			</div>
			<?php
			//jitm is being viewed, track it
			$jetpack = Jetpack::init();
			$jetpack->stat( 'jitm', 'manage-pi-viewed-' . JETPACK__VERSION );
			$jetpack->do_stats( 'server_side' );
		endif; // manage inactive
	}

	/**
	 * Display message in editor prompting user to compose entry in WordPress.com.
	 *
	 * @since 3.8.2
	 */
	function editor_msg() {
		global $typenow;
		if ( current_user_can( 'manage_options' ) ) {
			$normalized_site_url = Jetpack::build_raw_urls( get_home_url() );
			$editor_dismissed = isset( self::$jetpack_hide_jitm['editor'] );
			if ( ! $editor_dismissed ) :
			?>
			<div class="jp-jitm">
				<a href="#"  data-module="editor" class="dismiss"><span class="genericon genericon-close"></span></a>
				<div class="jp-emblem">
					<?php echo self::get_jp_emblem(); ?>
				</div>
				<p class="msg">
					<?php esc_html_e( 'Try the brand new editor.', 'jetpack' ); ?>
				</p>
				<p>
					<a href="<?php echo esc_url( 'https://wordpress.com/' . $typenow . '/' . $normalized_site_url ); ?>" target="_blank" title="<?php esc_attr_e( 'Write on WordPress.com', 'jetpack' ); ?>" data-module="editor" class="button button-jetpack launch show-after-enable"><?php esc_html_e( 'Write on WordPress.com', 'jetpack' ); ?></a>
				</p>
			</div>
			<?php
			//jitm is being viewed, track it
			$jetpack = Jetpack::init();
			$jetpack->stat( 'jitm', 'editor-viewed-' . JETPACK__VERSION );
			$jetpack->do_stats( 'server_side' );
			endif; // manage or editor inactive
		}
	}

	/**
	 * Display message in editor prompting user to enable stats.
	 *
	 * @since 3.9.0
	 */
	function stats_msg() {
		$stats_active        = Jetpack::is_module_active( 'stats' );
		$normalized_site_url = Jetpack::build_raw_urls( get_home_url() );
		?>
		<div class="jp-jitm">
			<a href="#" data-module="stats" class="dismiss"><span class="genericon genericon-close"></span></a>

			<div class="jp-emblem">
				<?php echo self::get_jp_emblem(); ?>
			</div>
			<p class="msg">
				<?php esc_html_e( 'Track detailed stats on this post and the rest of your site.', 'jetpack' ); ?>
			</p>
			<?php if ( ! $stats_active ) : ?>
				<p>
					<img class="j-spinner hide" src="<?php echo esc_url( includes_url( 'images/spinner-2x.gif' ) ); ?>" alt="<?php echo esc_attr__( 'Loading...', 'jetpack' ); ?>" /><a href="#" data-module="stats" data-module-success="<?php esc_attr_e( 'Success! Jetpack Stats is now activated.', 'jetpack' ); ?>" class="activate button"><?php esc_html_e( 'Enable Jetpack Stats', 'jetpack' ); ?></a>
				</p>
			<?php endif; // stats inactive
			?>
			<p class="show-after-enable <?php echo $stats_active ? '' : 'hide'; ?>">
				<a href="<?php echo esc_url( 'https://wordpress.com/stats/insights/' . $normalized_site_url ); ?>" target="_blank" title="<?php esc_attr_e( 'Go to WordPress.com', 'jetpack' ); ?>" data-module="stats" class="button button-jetpack launch show-after-enable"><?php esc_html_e( 'Go to WordPress.com', 'jetpack' ); ?></a>
			</p>
		</div>
		<?php
		//jitm is being viewed, track it
		$jetpack = Jetpack::init();
		$jetpack->stat( 'jitm', 'post-stats-viewed-' . JETPACK__VERSION );
		$jetpack->do_stats( 'server_side' );
	}

	/**
	 * Display JITM in Updates screen prompting user to enable Backups.
	 *
	 * @since 3.9.5
	 */
	function backups_updates_msg() {
		$normalized_site_url = Jetpack::build_raw_urls( get_home_url() );
		$url = 'https://wordpress.com/plans/' . $normalized_site_url;
		$jitm_stats_url = Jetpack::build_stats_url( array( 'x_jetpack-jitm' => 'vaultpress' ) );
		?>
		<div class="jp-jitm" data-track="vaultpress-updates" data-stats_url="<?php echo esc_url( $jitm_stats_url ); ?>">
			<a href="#" data-module="vaultpress" class="dismiss"><span class="genericon genericon-close"></span></a>

			<div class="jp-emblem">
				<?php echo self::get_jp_emblem(); ?>
			</div>
			<p class="msg">
				<?php esc_html_e( 'Backups are recommended to protect your site before you make any changes.', 'jetpack' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( $url ); ?>" target="_blank" title="<?php esc_attr_e( 'Enable VaultPress Backups', 'jetpack' ); ?>" data-module="vaultpress" data-jptracks-name="nudge_click" data-jptracks-prop="jitm-vault" class="button button-jetpack launch jptracks"><?php esc_html_e( 'Enable VaultPress Backups', 'jetpack' ); ?></a>
			</p>
		</div>
		<?php
		//jitm is being viewed, track it
		$jetpack = Jetpack::init();
		$jetpack->stat( 'jitm', 'vaultpress-updates-viewed-' . JETPACK__VERSION );
		$jetpack->do_stats( 'server_side' );
	}

	/**
	 * Display JITM in Comments screen prompting user to enable Akismet.
	 *
	 * @since 3.9.5
	 */
	function akismet_msg() {
		$normalized_site_url = Jetpack::build_raw_urls( get_home_url() );
		$url = 'https://wordpress.com/plans/' . $normalized_site_url;
		$jitm_stats_url = Jetpack::build_stats_url( array( 'x_jetpack-jitm' => 'akismet' ) );
		?>
		<div class="jp-jitm" data-stats_url="<?php echo esc_url( $jitm_stats_url ); ?>">
			<a href="#" data-module="akismet" class="dismiss"><span class="genericon genericon-close"></span></a>

			<div class="jp-emblem">
				<?php echo self::get_jp_emblem(); ?>
			</div>
			<p class="msg">
				<?php esc_html_e( "Spam affects your site's legitimacy, protect your site with Akismet.", 'jetpack' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( $url ); ?>" target="_blank" title="<?php esc_attr_e( 'Automate Spam Blocking', 'jetpack' ); ?>" data-module="akismet" data-jptracks-name="nudge_click" data-jptracks-prop="jitm-akismet" class="button button-jetpack launch jptracks"><?php esc_html_e( 'Automate Spam Blocking', 'jetpack' ); ?></a>
			</p>
		</div>
		<?php
		//jitm is being viewed, track it
		$jetpack = Jetpack::init();
		$jetpack->stat( 'jitm', 'akismet-viewed-' . JETPACK__VERSION );
		$jetpack->do_stats( 'server_side' );
	}

	/**
	 * Display JITM after a post is published prompting user to enable Backups.
	 *
	 * @since 3.9.5
	 */
	function backups_after_publish_msg() {
		$normalized_site_url = Jetpack::build_raw_urls( get_home_url() );
		$url = 'https://wordpress.com/plans/' . $normalized_site_url;
		$jitm_stats_url = Jetpack::build_stats_url( array( 'x_jetpack-jitm' => 'vaultpress' ) );
		?>
		<div class="jp-jitm" data-track="vaultpress-publish" data-stats_url="<?php echo esc_url( $jitm_stats_url ); ?>">
			<a href="#" data-module="vaultpress" class="dismiss"><span class="genericon genericon-close"></span></a>

			<div class="jp-emblem">
				<?php echo self::get_jp_emblem(); ?>
			</div>
			<p class="msg">
				<?php esc_html_e( "Great job! Now let's make sure your hard work is never lost, backup everything with VaultPress.", 'jetpack' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( $url ); ?>" target="_blank" title="<?php esc_attr_e( 'Enable Backups', 'jetpack' ); ?>" data-module="vaultpress" data-jptracks-name="nudge_click" data-jptracks-prop="jitm-vault-post" class="button button-jetpack launch jptracks"><?php esc_html_e( 'Enable Backups', 'jetpack' ); ?></a>
			</p>
		</div>
		<?php
		//jitm is being viewed, track it
		$jetpack = Jetpack::init();
		$jetpack->stat( 'jitm', 'vaultpress-publish-viewed-' . JETPACK__VERSION );
		$jetpack->do_stats( 'server_side' );
	}

	/*
	* Function to enqueue jitm css and js
	*/
	function jitm_enqueue_files( $hook ) {

		$wp_styles = new WP_Styles();
		$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_style( 'jetpack-jitm-css', plugins_url( "css/jetpack-admin-jitm{$min}.css", JETPACK__PLUGIN_FILE ), false, JETPACK__VERSION . '-201243242' );
		$wp_styles->add_data( 'jetpack-jitm-css', 'rtl', true );

		//Build stats url for tracking manage button
		$jitm_stats_url = Jetpack::build_stats_url( array( 'x_jetpack-jitm' => 'wordpresstools' ) );

		// Enqueue javascript to handle jitm notice events
		wp_enqueue_script( 'jetpack-jitm-js', plugins_url( '_inc/jetpack-jitm.js', JETPACK__PLUGIN_FILE ),
			array( 'jquery' ), JETPACK__VERSION, true );
		wp_localize_script(
			'jetpack-jitm-js',
			'jitmL10n',
			array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'jitm_nonce'  => wp_create_nonce( 'jetpack-jitm-nonce' ),
				'photon_msgs' => array(
					'success' => esc_html__( 'Success! Photon is now actively optimizing and serving your images for free.', 'jetpack' ),
					'fail'    => esc_html__( 'We are sorry but unfortunately Photon did not activate.', 'jetpack' )
				),
				'manage_msgs' => array(
					'success' => esc_html__( 'Success! WordPress.com tools are now active.', 'jetpack' ),
					'fail'    => esc_html__( 'We are sorry but unfortunately Manage did not activate.', 'jetpack' )
				),
				'stats_msgs' => array(
					'success' => esc_html__( 'Success! Stats are now active.', 'jetpack' ),
					'fail'    => esc_html__( 'We are sorry but unfortunately Stats did not activate.', 'jetpack' )
				),
				'jitm_stats_url' => $jitm_stats_url
			)
		);
	}

	/**
	 * Check if a JITM was dismissed or not. Currently, dismissing one JITM will dismiss all of them.
	 *
	 * @since 3.8.2
	 *
	 * @return bool
	 */
	function is_jitm_dismissed() {
		if ( is_null( self::$jetpack_hide_jitm ) ) {

			// The option returns false when nothing was dismissed
			self::$jetpack_hide_jitm = Jetpack_Options::get_option( 'hide_jitm' );
		}

		// so if it's not an array, it means no JITM was dismissed
		return is_array( self::$jetpack_hide_jitm );
	}

	/**
	 * Return string containing the Jetpack logo.
	 *
	 * @since 3.9.0
	 *
	 * @return string
	 */
	function get_jp_emblem() {
		return '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0" y="0" viewBox="0 0 172.9 172.9" enable-background="new 0 0 172.9 172.9" xml:space="preserve">	<path d="M86.4 0C38.7 0 0 38.7 0 86.4c0 47.7 38.7 86.4 86.4 86.4s86.4-38.7 86.4-86.4C172.9 38.7 134.2 0 86.4 0zM83.1 106.6l-27.1-6.9C49 98 45.7 90.1 49.3 84l33.8-58.5V106.6zM124.9 88.9l-33.8 58.5V66.3l27.1 6.9C125.1 74.9 128.4 82.8 124.9 88.9z" /></svg>';
	}
}
/**
 * Filter to turn off all just in time messages
 *
 * @since 3.7.0
 *
 * @param bool true Whether to show just in time messages.
 */
if ( apply_filters( 'jetpack_just_in_time_msgs', false ) ) {
	Jetpack_JITM::init();
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php

class Jetpack_Debugger {

	private static function is_jetpack_support_open() {
		try {
			$response = wp_remote_request( "http://jetpack.com/is-support-open" );
			$body = wp_remote_retrieve_body( $response );
			$json = json_decode( $body );
			return ( ( bool ) $json->is_support_open );
		}
		catch ( Exception $e ) {
			return true;
		}
	}

	public static function jetpack_increase_timeout() {
		return 30; // seconds
	}

	public static function jetpack_debug_display_handler() {
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( esc_html__('You do not have sufficient permissions to access this page.', 'jetpack' ) );

		$current_user = wp_get_current_user();

		$user_id = get_current_user_id();
		$user_tokens = Jetpack_Options::get_option( 'user_tokens' );
		if ( is_array( $user_tokens ) && array_key_exists( $user_id, $user_tokens ) ) {
			$user_token = $user_tokens[$user_id];
		} else {
			$user_token = '[this user has no token]';
		}
		unset( $user_tokens );

		$debug_info = "\r\n";
		foreach ( array(
			'CLIENT_ID'   => 'id',
			'BLOG_TOKEN'  => 'blog_token',
			'MASTER_USER' => 'master_user',
			'CERT'        => 'fallback_no_verify_ssl_certs',
			'TIME_DIFF'   => 'time_diff',
			'VERSION'     => 'version',
			'OLD_VERSION' => 'old_version',
			'PUBLIC'      => 'public',
		) as $label => $option_name ) {
			$debug_info .= "\r\n" . esc_html( $label . ": " . Jetpack_Options::get_option( $option_name ) );
		}

		$debug_info .= "\r\n" . esc_html( "USER_ID: " . $user_id );
		$debug_info .= "\r\n" . esc_html( "USER_TOKEN: " . $user_token );
		$debug_info .= "\r\n" . esc_html( "PHP_VERSION: " . PHP_VERSION );
		$debug_info .= "\r\n" . esc_html( "WORDPRESS_VERSION: " . $GLOBALS['wp_version'] );
		$debug_info .= "\r\n" . esc_html( "JETPACK__VERSION: " . JETPACK__VERSION );
		$debug_info .= "\r\n" . esc_html( "JETPACK__PLUGIN_DIR: " . JETPACK__PLUGIN_DIR );
		$debug_info .= "\r\n" . esc_html( "SITE_URL: " . site_url() );
		$debug_info .= "\r\n" . esc_html( "HOME_URL: " . home_url() );
		$debug_info .= "\r\n" . esc_html( "SERVER_PORT: " . $_SERVER['SERVER_PORT'] );


		foreach ( array (
					  'GD_PHP_HANDLER',
					  'HTTP_AKAMAI_ORIGIN_HOP',
					  'HTTP_CF_CONNECTING_IP',
					  'HTTP_CLIENT_IP',
					  'HTTP_FASTLY_CLIENT_IP',
					  'HTTP_FORWARDED',
					  'HTTP_FORWARDED_FOR',
					  'HTTP_INCAP_CLIENT_IP',
					  'HTTP_TRUE_CLIENT_IP',
					  'HTTP_X_CLIENTIP',
					  'HTTP_X_CLUSTER_CLIENT_IP',
					  'HTTP_X_FORWARDED',
					  'HTTP_X_FORWARDED_FOR',
					  'HTTP_X_IP_TRAIL',
					  'HTTP_X_REAL_IP',
					  'HTTP_X_VARNISH',
					  'REMOTE_ADDR'
				  ) as $header ) {
			if( isset( $_SERVER[$header] ) ) {
				$debug_info .= "\r\n" . esc_html( 'IP HEADER: '.$header . ": " . $_SERVER[$header] );
			} else {
				$debug_info .= "\r\n" . esc_html( 'IP HEADER: '.$header . ": Not Set" );
			}
		}


		$debug_info .= "\r\n" . esc_html( "PROTECT_TRUSTED_HEADER: " . json_encode(get_site_option( 'trusted_ip_header' )));

		$debug_info .= "\r\n\r\nTEST RESULTS:\r\n\r\n";
		$debug_raw_info = '';


		$tests = array();

		$tests['HTTP']['result'] = wp_remote_get( preg_replace( '/^https:/', 'http:', JETPACK__API_BASE ) . 'test/1/' );
		$tests['HTTP']['fail_message'] = esc_html__( 'Your site isn’t reaching the Jetpack servers.', 'jetpack' );

		$tests['HTTPS']['result'] = wp_remote_get( preg_replace( '/^http:/', 'https:', JETPACK__API_BASE ) . 'test/1/' );
		$tests['HTTPS']['fail_message'] = esc_html__( 'Your site isn’t securely reaching the Jetpack servers.', 'jetpack' );

		$identity_crisis_message = '';
		if ( $identity_crisis = Jetpack::check_identity_crisis( true ) ) {
			foreach( $identity_crisis as $key => $value ) {
				$identity_crisis_message .= sprintf( __( 'Your `%1$s` option is set up as `%2$s`, but your WordPress.com connection lists it as `%3$s`!', 'jetpack' ), $key, (string) get_option( $key ), $value ) . "\r\n";
			}
			$identity_crisis = new WP_Error( 'identity-crisis', $identity_crisis_message, $identity_crisis );
		} else {
			$identity_crisis = 'PASS';
		}
		$tests['IDENTITY_CRISIS']['result'] = $identity_crisis;
		$tests['IDENTITY_CRISIS']['fail_message'] = esc_html__( 'Something has gotten mixed up in your Jetpack Connection!', 'jetpack' );

		$self_xml_rpc_url = home_url( 'xmlrpc.php' );

		$testsite_url = Jetpack::fix_url_for_bad_hosts( JETPACK__API_BASE . 'testsite/1/?url=' );

		add_filter( 'http_request_timeout', array( 'Jetpack_Debugger', 'jetpack_increase_timeout' ) );

		$tests['SELF']['result'] = wp_remote_get( $testsite_url . $self_xml_rpc_url );
		$tests['SELF']['fail_message'] = esc_html__( 'It looks like your site can not communicate properly with Jetpack.', 'jetpack' );

		remove_filter( 'http_request_timeout', array( 'Jetpack_Debugger', 'jetpack_increase_timeout' ) );

		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Jetpack Debugging Center', 'jetpack' ); ?></h2>
			<h3><?php _e( "Testing your site's compatibility with Jetpack...", 'jetpack' ); ?></h3>
			<div class="jetpack-debug-test-container">
			<?php
			ob_start();
			foreach ( $tests as $test_name => $test_info ) :
				if ( 'PASS' !== $test_info['result'] && ( is_wp_error( $test_info['result'] ) ||
					false == ( $response_code = wp_remote_retrieve_response_code( $test_info['result'] ) )  ||
					'200' != $response_code ) ) {
					$debug_info .= $test_name . ": FAIL\r\n";
					?>
					<div class="jetpack-test-error">
						<p>
							<a class="jetpack-test-heading" href="#"><?php echo $test_info['fail_message']; ?>
							<span class="noticon noticon-collapse"></span>
							</a>
						</p>
						<pre class="jetpack-test-details"><?php echo esc_html( $test_name ); ?>:
	<?php echo esc_html( is_wp_error( $test_info['result'] ) ? $test_info['result']->get_error_message() : print_r( $test_info['result'], 1 ) ); ?></pre>
					</div><?php
				} else {
					$debug_info .= $test_name . ": PASS\r\n";
				}
				$debug_raw_info .= "\r\n\r\n" . $test_name . "\r\n" . esc_html( is_wp_error( $test_info['result'] ) ? $test_info['result']->get_error_message() : print_r( $test_info['result'], 1 ) );
				?>
			<?php endforeach;
			$html = ob_get_clean();

			if ( '' == trim( $html ) ) {
				echo '<div class="jetpack-tests-succed">' . esc_html__( 'Your Jetpack setup looks a-okay!', 'jetpack' ) . '</div>';
			}
			else {
				echo '<h3>' . esc_html__( 'There seems to be a problem with your site’s ability to communicate with Jetpack!', 'jetpack' ) . '</h3>';
				echo $html;
			}
			$debug_info .= "\r\n\r\nRAW TEST RESULTS:" . $debug_raw_info ."\r\n";
			?>
			</div>
			<div class="entry-content">
				<h3><?php esc_html_e( 'Trouble with Jetpack?', 'jetpack' ); ?></h3>
				<h4><?php esc_html_e( 'It may be caused by one of these issues, which you can diagnose yourself:', 'jetpack' ); ?></h4>
				<ol>
					<li><b><em><?php esc_html_e( 'A known issue.', 'jetpack' ); ?></em></b>  <?php echo sprintf( __( 'Some themes and plugins have <a href="%1$s" target="_blank">known conflicts</a> with Jetpack – check the <a href="%2$s" target="_blank">list</a>. (You can also browse the <a href="%3$s" target="_blank">Jetpack support pages</a> or <a href="%4$s" target="_blank">Jetpack support forum</a> to see if others have experienced and solved the problem.)', 'jetpack' ), 'http://jetpack.com/support/getting-started-with-jetpack/known-issues/', 'http://jetpack.com/support/getting-started-with-jetpack/known-issues/', 'http://jetpack.com/support/', 'http://wordpress.org/support/plugin/jetpack' ); ?></li>
					<li><b><em><?php esc_html_e( 'An incompatible plugin.', 'jetpack' ); ?></em></b>  <?php esc_html_e( "Find out by disabling all plugins except Jetpack. If the problem persists, it's not a plugin issue. If the problem is solved, turn your plugins on one by one until the problem pops up again – there's the culprit! Let us know, and we'll try to help.", 'jetpack' ); ?></li>
					<li>
						<b><em><?php esc_html_e( 'A theme conflict.', 'jetpack' ); ?></em></b>
						<?php
							$default_theme = wp_get_theme( WP_DEFAULT_THEME );

							if ( $default_theme->exists() ) {
								echo esc_html( sprintf( __( "If your problem isn't known or caused by a plugin, try activating %s (the default WordPress theme).", 'jetpack' ), $default_theme->get( 'Name' ) ) );
							} else {
								esc_html_e( "If your problem isn't known or caused by a plugin, try activating the default WordPress theme.", 'jetpack' );
							}
						?>
						<?php esc_html_e( "If this solves the problem, something in your theme is probably broken – let the theme's author know.", 'jetpack' ); ?>
					</li>
					<li><b><em><?php esc_html_e( 'A problem with your XMLRPC file.', 'jetpack' ); ?></em></b>  <?php echo sprintf( __( 'Load your <a href="%s">XMLRPC file</a>. It should say “XML-RPC server accepts POST requests only.” on a line by itself.', 'jetpack' ), site_url( 'xmlrpc.php' ) ); ?>
						<ul>
							<li>- <?php esc_html_e( "If it's not by itself, a theme or plugin is displaying extra characters. Try steps 2 and 3.", 'jetpack' ); ?></li>
							<li>- <?php esc_html_e( "If you get a 404 message, contact your web host. Their security may block XMLRPC.", 'jetpack' ); ?></li>
						</ul>
					</li>
				</ol>
				<?php if ( self::is_jetpack_support_open() ): ?>
				<p class="jetpack-show-contact-form"><?php echo sprintf( __( 'If none of these help you find a solution, <a href="%s">click here to contact Jetpack support</a>. Tell us as much as you can about the issue and what steps you\'ve tried to resolve it, and one of our Happiness Engineers will be in touch to help.', 'jetpack' ), Jetpack::admin_url( array( 'page' => 'jetpack-debugger', 'contact' => true ) ) ); ?>
				</p>
				<?php endif; ?>
				<?php if ( Jetpack::is_active() ) : ?>
					<hr />
					<div id="connected-user-details">
						<p><?php printf( __( 'The primary connection is owned by <strong>%s</strong>\'s WordPress.com account.', 'jetpack' ), esc_html( Jetpack::get_master_user_email() ) ); ?></p>
					</div>
					<hr />
					<div id="sync-related-posts">
						<p><?php echo esc_html__( 'Some features of Jetpack use the WordPress.com infrastructure and require that your public content be mirrored there. If you see intermittent issues only affecting certain posts, please try requesting a reindex of your posts.', 'jetpack' ); ?></p>
						<?php echo Jetpack::init()->sync->reindex_ui() ?>
					</div>
				<?php endif; ?>
			</div>
			<div id="contact-message" <?php if( ! isset( $_GET['contact'] ) ) {?>  style="display:none" <?php } ?>>
			<?php if ( self::is_jetpack_support_open() ): ?>
				<form id="contactme" method="post" action="http://jetpack.com/contact-support/">
					<input type="hidden" name="action" value="submit">
					<input type="hidden" name="jetpack" value="needs-service">

					<input type="hidden" name="contact_form" id="contact_form" value="1">
					<input type="hidden" name="blog_url" id="blog_url" value="<?php echo esc_attr( site_url() ); ?>">
					<?php
						$subject_line = sprintf(
							/* translators: %s is the URL of the site */
							_x( 'from: %s Jetpack contact form', 'Support request email subject line', 'jetpack' ),
							esc_attr( site_url() )
						);

						if ( Jetpack::is_development_version() ) {
							$subject_line = 'BETA ' . $subject_line;
						}

						$subject_line_input = printf(
							'<input type="hidden" name="subject" id="subject" value="%s"">',
							$subject_line
						);
					?>
					<div class="formbox">
						<label for="message" class="h"><?php esc_html_e( 'Please describe the problem you are having.', 'jetpack' ); ?></label>
						<textarea name="message" cols="40" rows="7" id="did"></textarea>
					</div>

					<div id="name_div" class="formbox">
						<label class="h" for="your_name"><?php esc_html_e( 'Name', 'jetpack' ); ?></label>
			  			<span class="errormsg"><?php esc_html_e( 'Let us know your name.', 'jetpack' ); ?></span>
						<input name="your_name" type="text" id="your_name" value="<?php esc_html_e( $current_user->display_name, 'jetpack'); ?>" size="40">
					</div>

					<div id="email_div" class="formbox">
						<label class="h" for="your_email"><?php esc_html_e( 'E-mail', 'jetpack' ); ?></label>
			  			<span class="errormsg"><?php esc_html_e( 'Use a valid email address.', 'jetpack' ); ?></span>
						<input name="your_email" type="text" id="your_email" value="<?php esc_html_e( $current_user->user_email, 'jetpack'); ?>" size="40">
					</div>

					<div id="toggle_debug_info" class="formbox">
						<p><?php _e( 'The test results and some other useful debug information will be sent to the support team. Please feel free to <a href="#">review/modify</a> this information.', 'jetpack' ); ?></p>
					</div>

					<div id="debug_info_div" class="formbox" style="display:none">
						<label class="h" for="debug_info"><?php esc_html_e( 'Debug Info', 'jetpack' ); ?></label>
			  			<textarea name="debug_info" cols="40" rows="7" id="debug_info"><?php echo esc_attr( $debug_info ); ?></textarea>
					</div>

					<div style="clear: both;"></div>

					<div id="blog_div" class="formbox">
						<div id="submit_div" class="contact-support">
						<input type="submit" name="submit" value="<?php esc_html_e( 'Submit &#187;', 'jetpack' ); ?>">
						</div>
					</div>
					<div style="clear: both;"></div>
				</form>
			<?php endif; ?>
			</div>
		</div>
	<?php
	}

	public static function jetpack_debug_admin_head() {
		?>
		<style type="text/css">

			.jetpack-debug-test-container {
				margin-top: 20px;
				margin-bottom: 30px;
			}

			.jetpack-tests-succed {
				font-size: large;
				color: #8BAB3E;
			}

			.jetpack-test-details {
				margin: 4px 6px;
				padding: 10px;
				overflow: auto;
				display: none;
			}

			.jetpack-test-error {
				margin-bottom: 10px;
				background: #FFEBE8;
				border: solid 1px #C00;
				border-radius: 3px;
			}

			.jetpack-test-error p {
				margin: 0;
				padding: 0;
			}

			.jetpack-test-error a.jetpack-test-heading {
				padding: 4px 6px;
				display: block;
				text-decoration: none;
				color: inherit;
			}

			.jetpack-test-error .noticon {
				float: right;
			}

			form#contactme {
				border: 1px solid #dfdfdf;
				background: #eaf3fa;
				padding: 20px;
				margin: 10px;
				background-color: #eaf3fa;
				border-radius: 5px;
				font-size: 15px;
				font-family: "Open Sans", "Helvetica Neue", sans-serif;
			}

			form#contactme label.h {
				color: #444;
				display: block;
				font-weight: bold;
				margin: 0 0 7px 10px;
				text-shadow: 1px 1px 0 #fff;
			}

			.formbox {
				margin: 0 0 25px 0;
			}

			.formbox input[type="text"], .formbox input[type="email"], .formbox input[type="url"], .formbox textarea {
				border: 1px solid #e5e5e5;
				border-radius: 11px;
				box-shadow: inset 0 1px 1px rgba(0,0,0,0.1);
				color: #666;
				font-size: 14px;
				padding: 10px;
				width: 97%;
			}
			.formbox .contact-support input[type="submit"] {
				float: right;
				margin: 0 !important;
				border-radius: 20px !important;
				cursor: pointer;
				font-size: 13pt !important;
				height: auto !important;
				margin: 0 0 2em 10px !important;
				padding: 8px 16px !important;
				background-color: #ddd;
				border: 1px solid rgba(0,0,0,0.05);
				border-top-color: rgba(255,255,255,0.1);
				border-bottom-color: rgba(0,0,0,0.15);
				color: #333;
				font-weight: 400;
				display: inline-block;
				text-align: center;
				text-decoration: none;
			}

			.formbox span.errormsg {
				margin: 0 0 10px 10px;
				color: #d00;
				display: none;
			}

			.formbox.error span.errormsg {
				display: block;
			}

			#contact-message ul {
				margin: 0 0 20px 10px;
			}

			#contact-message li {
				margin: 0 0 10px 10px;
				list-style: disc;
				display: list-item;
			}

		</style>
		<script type="text/javascript">
		jQuery( document ).ready( function($) {

			$('#debug_info').prepend('jQuery version: ' + jQuery.fn.jquery + "\r\n");

			$( '.jetpack-test-error .jetpack-test-heading' ).on( 'click', function() {
				$( this ).parents( '.jetpack-test-error' ).find( '.jetpack-test-details' ).slideToggle();
				return false;
			} );

			$( '.jetpack-show-contact-form a' ).on( 'click', function() {
				$('#contact-message').slideToggle();
				return false;
			} );

			$( '#toggle_debug_info a' ).on( 'click', function() {
				$('#debug_info_div').slideToggle();
				return false;
			} );

			$('form#contactme').on("submit", function(e){
				var form = $(this);
				var message = form.find('#did');
				var name = form.find('#your_name');
				var email = form.find('#your_email')
				var validation_error = false;
				if( !name.val() ) {
					name.parents('.formbox').addClass('error');
					validation_error = true;
				}
				if( !email.val() ) {
					email.parents('.formbox').addClass('error');
					validation_error = true;
				}
				if ( validation_error ) {
					return false;
				}
				message.val(message.val() + "\r\n\r\n----------------------------------------------\r\n\r\nDEBUG INFO:\r\n" + $('#debug_info').val()  );
				return true;
	    	});

		} );
		</script>
		<?php
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           ���� JFIF  H H  �� Exif  II*            ��ohttp://ns.adobe.com/xap/1.0/ <?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?> <x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="Adobe XMP Core 5.5-c021 79.155772, 2014/01/13-19:44:00        "> <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"> <rdf:Description rdf:about="" xmlns:xmpMM="http://ns.adobe.com/xap/1.0/mm/" xmlns:stRef="http://ns.adobe.com/xap/1.0/sType/ResourceRef#" xmlns:xmp="http://ns.adobe.com/xap/1.0/" xmpMM:OriginalDocumentID="xmp.did:493EE041AA20681196389F7308994104" xmpMM:DocumentID="xmp.did:0D8DCC4CDC1B11E4A3F98CE0D87F77CA" xmpMM:InstanceID="xmp.iid:0D8DCC4BDC1B11E4A3F98CE0D87F77CA" xmp:CreatorTool="Adobe Photoshop CS5 Macintosh"> <xmpMM:DerivedFrom stRef:instanceID="xmp.iid:316389A1B17911E492AEE7F5E1560828" stRef:documentID="xmp.did:316389A2B17911E492AEE7F5E1560828"/> </rdf:Description> </rdf:RDF> </x:xmpmeta> <?xpacket end="r"?>�� C 


�� C		�� ,," ��               	�� A 	  !1AQa"q�2��#BR����$b3����Cr5S����             �� .      !1A"Q2aB�#3q�����   ? �СB�0P�B��B�
B
)(P� P�B��B�
B
)(P� P�B��B�
B
̒$(�#E,� R�=�V�&�!�uy(STG�� ��<�9��\�(��9�V�~��o�f�"�%�˃.���ιȊIO"\t@)��J-��Q�O�t��爴�e��X� �P��¯ے}��+�aX�ɞEضp��\}�\����q���*;����-���!~�a�=9����?Οl5�7T8����lg����|��g��<r�O�8#�jS�x��6MS��'�K��g�	�)���3���N�gO� I�K�+G≚9ˈ���l��&���_S�Q�D�XdrL��:�B�!�
�
(R(P�H@�B�!�
�
(R(P�H@�B�!�
�
(R(P�H@�B�!�
�us���J�#A�Mdo�z��-JmG�h�bȒH�Ǧ��V_�M������V�r]_fa�Pm��X�OI�sn졷=�~U?���u#Ou9p�F��� �Z�k���U����]��'*�g�W[*��w8�ɵ�wms�Va�����K��Vi�70�(�(���ԁ�)#a��.ITV%�c��8�]
$�3��@�`?��J%{m:����}�ڑ=ė���>\{��֜��bk�2H:�j�s�ȶ��Q�[�?:�7�{���w��n����r�/YUY�#f�z.q޲�֋�Rʫ�:�6�D���!F ��u��<&��+�&�g�h�YQ]NU�A�gѻ�xZ�>�oO��c� 1I� ������V��r*����СB�`P�B��B�
B
U��V�4�H�F�Y�!Ц�0����6ۏ��PI�QY���j�9t��
�Zq?���t������QR�hK�3�(�9P�b��o����jw�_@�B�8�B�;
j�x��C�5��ǜ���
B�Us'�:\���ҨS�""�4�4�A�"���F?q��"mB����r�|Ѓ�?#J�(P� W��"�.��K��F]#%G�jB1�į�~%�rֶdZ�; �Ź�Vv�8 �9�c�j��LS/�Pʼ�y�y�܃�Ϳ��G��[Ef8,FH��C|��Z�p�JXs����-�2�iXs�$�� ?�ua[D�_�ہ���5��C"��9�÷�=��S�	d
� ݘzzWi2$�e@s�Mqqq%� ��e�7�� ?:�p�]�i��X�©�UYtqJn�p�	<�$�r��Nc��# /-Hlt�l�4@�F d��ڄ���;���ƈ��V�d����=��R@�[��A��e�#9�S����N��HB^���y��̬:��W��2^Cm���3�.��C��>��O�Ҳ}�_B0�|��D�d�d#`0u �#��Ѥ�~k���X:�R
���U~x�� h�f��Z����އ��V�ZR
(R(P�]�hY�U$�Ԅqss�4�4ğJ���&:��|�|�8��z}iG�<`֖3Z�[tt!y����Z�m��i�����P�\��~4;Q��j6a�Ks&���y��r��ď_AQ��$�@΃�G�E�O�F.��]^�b�c
����@)|:/�ҫ��}�� ��m�=}��o��8��W×7��E�-��7*���Ծ�)-W�7��a!���Tw�q˴���H}#��uN�Sq4�I�2��mN�W�I8M���v<E%�$wn�����*Y����Vo����oē\E��?A=�<��?Vki�0H�\e[Ҵb�8Kk1e�kqsd�m�x�M�Cs�]�kp��v>�u'�P����SH��Qp�B�P���|�e~7�*�����'��vbr@�{(�^3RV��th�'�$�sIYQ���Tg�[#�@����)j�k��w-ìrV$,k���O�w$������7��h#��bF�Q�<q�q��Y����|�@�A�	�֕Z�=���Ͼ~3Z|'Ӧ��d��5�T�c ��YV�����"\#㦻��K�<�����ZW���L�YR�����l����� �=���Y{�<(k42Z���ڡ��\i7|�̎�� �V�y����\�đ��X8�Һ��}���kss_�k�;� ���z��o��ْ��Z���GR���!������}jIT�I��o���Q>d�o2l������Qn������:����=�훛�i]���0�Jd1G�i%|@/���72�F�G�sd���i�1�ש� =��͏�>��,m����F}�i?藺���
�0 ���+�M!�2j��Cl�'(�zz�Ԯ4�p��]Awe��nw�Uy��d���.�'��b˅����s�.�,G��ʬ;(l�$��H�lP�?*���?�x+�&�Ì�g����m�I�����V7(�چO\�rŕ7P�g�1�����$zNuB,�~�Zb���g$s�����9:F��V�u6`	�;TF�ą���9�!��ߕ �sؾM3��4ĉ��|��j��řg5(�+�Cj �q�b��;A7׽q8��H�͟Όd�6z���Q���K�-o��g�m�2�GT��ʻ�����O���cv�ֱ̣���[H�TtG<��X�����������r���$>�6c��V&S%�СB�DE��w?��~}6q-��h���H[��3t�I[�+x��q��=�F�L~��^��Gp���P\�)�B~%N�l�P�o����3��-�޴�٤��   ځ��c�K���t�T�n�� ]�M�q����* ���F:���%�T �ֵ�A|���؊�I	#"�\څRX�j��l��E)�<,�,�j��6d��zӎ�r,ѝr.J�)���2X����t��&٣B@�(��?��ݗMq�Yx��gT���'�W.�����U��(<�����n��ӓ����k�R�a���?�(��`9s-���&�e�n��W���U\�x_OZă� T���J�o$�.kO ��@�{s�S���ݲ"��~|�)���X�]���\k::6S�kĎ
[�u�4C�a����Z���oJ���OY#�
�#oJ�&�N�,�YaL�z>�-��M���et8e#������i�
���j�x��$tp=��+���J�n�����嚝x3���v7o'&�r���v�n� N�C]4%i5�����$������\Lu�?�XK�,s�(T��Bkgq����N��[���	 g �'־zjע�V�v@����zƞ|�,]؏�?c���	�{f����!vN�
�P�2sE�F�tK�jA�#'���*����e�Q��������s{�է7D S%�w
�ua��L|ZlG�U\ ;
�lgY-�'p:�2d��6��(�2m����_�i�j�G�HQ�s����}�<g���A��:T�S���������)�I��.ًs���Ry&׸K��� áC�-��z���mo,���x�i�"�g�;�`Ix����$�;liN�a�b���q��X�(r���smE���r�-���#߹���M�4�����1�S�o����7ٙa�E�ʣ���U޹�����F��� �[�G�T�ӆ\I�|�bd�^�����\ȧ�?S5�<��A�sO��|��8aD�>D֪D꣩<���ַǀCX��q��ٻ��d���~?�V��)�p��HUny��
ʸ��T�BE�B�
�P*7��S����2��%E|B'�;���v���snE���f=7G�J��~'�_>�|��5_l���߫8��Rp�Z+c�WV�r�o��2.B�����%р��F3�TR��������:��ҕ��Z�r�$���Bwj��3�j�-o^:E�4q���ʠ�����Ƕv�<����0IP@E�P	 m��aܠ��	�늪t��W��F��*��ȿ0�� t_s�>Ԕ�߹��v�m\�}?�.�ۏ��2y�� �pO�2?:����٠i��b�dt"�!�֍÷F��O�#�Fq�%ii>�x{�'m��T�sh�<��������;=>u'�<��6����u� \~�I�v�#»U[��H�*��D�Kf6��Qxh��  *�؜RMWL�^iuU ��J�ޣ0?�,���6Aa�r �w>��}�W��t���K���|�	��8뱠��n�;<�
�.)moco<s&q̌�s֠|1�Y��r鈋�U$�o]-�eي��Ԝ�|Pr�F�'�?I�[8�{��`��oQ�U�ob3ǣ�rc���Û4�Ƽu}�C$�}�]\��!c���>f�1���kq"�陷I<��Գ)� pd��I�޷P޲��6V> B�� W,�U�
��#�zavc
���>ug��o��iz�@b�b�fF�;� �:�
�9)��u+nF�m�|h8��..͚��c;��כ?4 �k�S�n<��˖Ϲ�;�8����I|��5�d�2�2��~Yƞs�=�L�3��i��f1G)�Wҥ��H��Nʣz�����:c#劚x{��`��Me���fr+'�E鶟��n�+]q���S���y���Z�]]_�y6V�]�c+(I������.����9�.��n��m�}�\�q?ٮ�w�km.�`�G>��l��R��qƸ16��3��IK���lI˖�"=��1��b��Mw�&�?UPʶcw�?Z/����qlH����uΝ��p]�;Ӗ������lo֡((�)Kw$�W�ܕ�j�<M��d�3�N޽��׸�!��a�*��l[��i��5�����[��K{�ϭ��vG���ߜ���Z�䃺�,�G2.3��}(�r9'�N�p�9b�k�3m��+g~�7��D��<P������T��2��ꤍ�a��<\�����"��j-%�.��~�O_�(�>�B�
����Ҕ��� ?S��E��nO_���Y����J���!�E�8T�I8�� �Y|Ű��DZ�̃�B9�;l:g�@<C����� ~h�p~� ��`�6�ʴ�+g8��.����qj�Y�}�I�7Egaa�\vs�G�QV�ܺ%ͩi�V@�Y���� d|�W�i�𢥼K�. �x�b��Ճr�\`��nj���Ŷ\�b�Ȓe�qEYOh�[��I"�,�,����U�F�˧]M�O���d��f��aPj���-S,X����u�B��}�w$���▔{u恹@���H5�N���!R��>��K%�U~�n��z��f�����q�K��)��	>��� :���#Vd �Y1��_>�s���X����W���Uyh�u������<�ŌBX��:�ko4H�Y"��d��������ʱ.z{�˘6���E����S"�t�dN�A�JS1�ٛ��һ�s5� 瓡����Į�bZ����C��iv����%�s�.y�`�}w���� �ӅF}Ew��9�2�m�5��!pl$7�� ��އ�o���:��zT�^X��S�2�1��|�P�̣�aY��p�9��s�Zs�مƇ|G�H���� U�n�&Հ�l��F��ߵ����L�e+#af@�pG���7B4@l�Ԙ��$��M�4���ߗ}�ϝoD[<�+6$
x��Am����q�j-<��Fph�)�	Vd?6v�*��tZ/��%"���;K�I\���� ���C�X�/n4�Ԗ����Au��6 �_�58����d�A���/��B����uaڃ��N�IG,j]}��v2��ÿ���ı^�Xq"��=vbO7�Rۍs�u;kkK�ᴑ����P�� ��S� �}{mUOx��5l`�k�vV��V������"��,$�c$����.mp�o�ζ�M]��I������?�۝2�� K�m��A``�Y�.�H��M��u������y*�pF�^�E�K����j~�� ������^�rz�6̟��d�QH�1�s���ܫ4�J&�{�Q}/E���J�+i#���Y�g.(PlK�_`7$�4�� �'��HB@���S�$�"5b S�ȧ�R;�zՊ�,����p���<JI&�!��v��;u�-[
pz�4�~�)]� rkAM-J7�P ^f'<�����V���F]�Y�"qyd��2?8��$@H9�0b	��Q�����8���k�,1Xab�B1Ն�|C����o�4^�����jOVU����'��Ħl^��
aP*����a����V��T�\y��Ϣ�(�
���c�V�\ʖ_��Al�~ge?,K8J�P�`D0�9�{_�`߮��#�8�o�T�W�88�$`9'�F}Y[Ț�~��{ZE���FbV:d��G���͆@�"�|+ĉr�*HYF��`�� 5-kk�"�H��r�� ՛���K�Dz>��/,�����O�T�b[}ծW�����Z�h���'<FXB�q�>gl��:�{Bω� �X��d�E^^r ���.��U�#&� d[��xSQ�.��IfNq����3ӽ>YZ$,P�X�EK1�osD\�\jw<���ٛ�ʏ�skge �T�ip������b�/��Kx���;/�j�����3�#>[���� �j�V�Gj@��m��֞�?U�����n?�>W0O�[��؀�d����e�,ANzw�W7�WH����ϧjd���V@y�7��H�M,+�)\�4	I�GH�jآ"�I�IS��j.m;N���P�W(�zL���VsH5�/���r���
��?�͜=v4�� xcgo}���;Hb���|s :��t�\�걷J��!$Z����h�68�t����c"t;��OV�c���{+n����	�`�� ;���S�@�Gi�	-v/���Чe'��r����f<���Qj�Hǈl�wk��9F����!����֮�]�F���~��d���9��kn���n���dr�e�$�jG���!vd �6 Q�ZX���}3�.���$��h�*'��� <����b[1�k�+�O��@�mGZ��t�I��/�I��+�LY�#�֮�"��U�F�����U O�4g�?��v�=����BW����R�ɿM�ǎˉ,#C*2�Zc�4H�X�|�ޢ�7P�."��'"��>&�L]��+2Ǒ�o��v.կ�t �+��n~����_�=�CiEVܑ�O��i�T�f�n��2轀�/�-��t�����_I���]�u��N-č��E�K�~���u���F]�����&���O1##�7ɤ]O ��$cֈOOO�ū�sc4vd6d�)ة�]�h"*�W�u�t:rEl�#�Eu��FA8-�KJҴ6=|[����_⹧w���4��s�g{)�F�L��^�1�Z.��_����C�<q�r:����|��짎�(�OQW6��Mk�z��:��?��y��$�߾{�ZqᛓS�:�yn,��x�3:x��)��U��:�i��tVkPg��?��{{�`v�f􁹠���F
t�Tt��
�5�F�o��s�q�
��� ��gcT�_�H�n� �����͚_����;Y�"#�09�?ڋ�KԹ�"���2oB1���I�<�\�HS���چ��qۉC#&���A`��I{�����ٮ��s`K���7񥦡c4��8�\�\AjU��l0���=�ծ���E*��9ݿVx�����D��tS�����iV���۪�P\���qM���Lȸeu?t�T�D��2�qq0�����&��U�q5�' b�$kv�+�Iyp�1Ź�ך���>�+v'���$�*��K�uN;��Z��<����ݫeFt��M�2���j7�r������ 4�wt֏�#�H�>](�9�)A��N>���.�b�N�#�� �UT�wf�����4�$W]FG���7O�y����2���<��R���?S�ZG�8�-B ��f^�{P,�=)��?���c�p-���ۆ���r����ZN��Z��[[��}�D ~=jSer��"T<݈�K�Ӓ@�;{��lަ��8xjI'�R�?�P9��5!�"./��IN�� #sP~#��o��ٿl�����vǖU����P��om��~UǦN� �g�^~o��'Ҭ�n�P�v��[@|��	jʶv9��4`��_Kg7������E@�ၟ�� ΋�J���~tM�GE��:�|i%��q3<`�Ԡ%�e'�=�U�&���F(<�h�i�3���Z��݅.}K8����-�1�p{��E�C�[Ao|m������١�3,�Ԍ���-UH!-������чJ����{eRwh�7��=6�m/E������ke�)�	�o�|�p���}��]���+�� �Q�lyY�S��
�IoƜ��W�;r5�cf�"F�N*!�rH�H�����w�$�2*3v���=j�1ڹ)�-��p�cT�s  1�T�q#۵Ft�5{HX<�C0=��K�~^P��ޏi���s��B���̠��mE�*Õ9l��z�5a����m]�?*%VO�Hn�,��55�r����iS����:扚�<�9��*4M5dbhd��NS��@}jy�_&��Ʒ�V�H�Ino���ɀ�I'm���TE]��Pp�� z�xu����Y�_�Aa��q𬀡ؐ�:��;��i�6�a8�)�5H�:q�u�@�[HSL���XDq�� l��ެ�����\VQ��t���E��P�cn���%�.��xzU�Ԗ� �g���(D9�$���8��k���҇�-�Arq#|��{
�M���L�DrwO��wY������N���C�u�ǟ�I.��sf�t_�H'�Z%(���v�d��h�_�O�C��5������8��;�N�I��p|���m?�9=�;��ʕi�8/�l��F������W�a���FB�G�Rm*�][���,r�������.=�kLB��w�����B }1H�?�!Y3�#c�a\r<9#���6�I��$�葏�6q֙��N[��=j�ԊF������?{)6�2�޶bT�yy(>E�������Z?PS�� �<������Op(�z5�V��B�8d`O���\v�O$^Y�W9Cީ����խ�v�~�����j˩�۹t�J[Y4��?�Ѯ��3������՛�����B��D'�f�?����{�s:&� `��E����~GJ�B��a��pJx���{h|�R�H�Ǿ�ƪ�O���=���ҏ,˜�/䩶�S��+X�̅��%�8���[�[�\JqD��0�:��O��Ԛ)3��k;$8ܮzS��GQ��}��6P����u�I�)�n֮�W$�!I�:f�%�8#�v�x���s{��'�8��Q�KgmJ+�9�����a˹ߵN4ȹl��u��Ў�� :Q\;�Fao"��̐���a�ӯ:���^C��z���%���	i��uæ9Ke��"� �X���*W�m������i���9��j�Oq�χ���EMk}�@����$��;zP�{\�PYyt�.�aR�R�I��E/l�r��Z�(���ᱶ���1D*� �F�����`J[zG�n�~��&��廌6Le���Q�r�~۲C�������{Sճ��s�M��d�S�N3��u�A(�G)��������uڰ��o�w�C`�W�cq-��\���{��To�,�����.��7�����\�~����˒&%[���7QQ�N&���K�Z'�8,�Z���m�?�G�zU�XE��4mF���C�,�����q|W�:v���Aʎ��}"��1����_G�tv>њ'b����jL $���ա�����x�r����k���M��z� <mߥ'������f���{8K �\�a���c|S���0�Y`e�G3 ��oqD�ټ�c�Q���Q��&[�F.�'=���t�䷳7��@p�����A�k�=ʾ ��7��Oć.�X]#����O�X�^�!�F�U麍��	ʎF3��������p��K�#l[�It ��9r�ulH�N5�[(��IJTRzT�V�0��=j5�M�p��M�F�M�l_ �+29�o5�}7��v烗;�T� J���	�m�Ũ�E���y�N	�Π{L�ng�'�Q<r��#$vʎ��L��~t��j�c���F�O0 �=i�D*��ޕX�y��C��N\��Z��F���[Ʈr�Hl�Ƕ��LBu���YpM�Y[J9�㯵\�T�yn��H"��-����r6O�@\��8�}�V�1뭣��0&0�nf=>��=��� ��-�����Z�����c�8�8�6CWi�%1��Gh����֟��O�6����c��� J���u�X�ZM/�<$��D�[��('�4|�.,��?|94�t�11+�0db�w���n� #q�/�j�2�\8��^{xט��NG��oz�<b��^�\ZG�+�&EQ�rw��<�]V+��]�Ͷ[$F`�� �;iLnn<�Ԏ��� ��O�l�k8d8>�	�tt��!�R���)�w�~����7�=*ƹO��W,z⠼Qdc�Dm�*QU$Bj����^N��f%Zh��o�+�3��3�K��1� ,s�ҭ���Y��3V�o.);d GГU�C�>G#%���ux`���N7>M��}�)be��/�$F� ����������N�@�-�K��\��
�7Ț 7ȟj0튵�5[�k�~Q�������}jEm�d�.09�F�ߗ�A �E��=�)�O�lo�۠�3��g��/B]:��$F�D�%s��푟����!�A܁K���06�iISCܠ�E���p�ǽ�����)C) ����w��7'ߧj��}:�<�	P� ��ߕ�y���+ԭ�
sl�6�_�#����!����.�4���9 ��)�L�Kaq`�b�pClG�?� F�c�K���Fk��X��[��l�w���b��X��+>�Ot��,̄�3b�x�~���EZ\	���q���H2�V���B=q��Ue�C�.�Y�nB>���_�Ӵ[\�:D|�߆?�m��ԧ��]� �Դ��;��F�/yT���-tx5	E��e@��r;U�o
<,̠�7�S]�bX9C��k��G,��*�4�k>!�`����Q,����lz��pҳ���5�qx��*���y|�Ќ{����_\>~�)�O��@�'�Ma�>�$�.-����2�?�
ߥ�� MK[z���{S����O�$V�N��������ߘ��䍿�Z>���P�lE0w�&�Clⷵ�u�9���H�B��?���B&�g�}�#1��
���7�NC��� 㘐��������5��^�	�4k��-��͑�(�q*�c̞�q
��Q"\�v�՚�i��C@�l���13������rO�$t��$q LqŸ'�@���[���t��4rB�ӡ�j��b�Y^Mc���H�ֱ:�m�WO��0���Vc�$2���Oס�*��c��˖#�ϯO�Z�.�@�"�Ƌ!�5|���Ek]�f���Nk։s���I	�,���B���"�_�n-nPI���������̧-s�f���@��� �j��:���g���^��2�3|��zn��� u����Z������T�+7q�7	k�Y�yA捈��z	����ǣ���w{%���\,Q��a�apI\�P��~�MĦT99��Xh&��s7�Ou=&F����?��#�l�"��6o�D����n�#�I$��J0�S������P�z��Y!~Q��J���"BpW}�����o���B�nG��`~��ʝ!xQ~(�c�R�ǰ<����<������!w.��3��ļ����]�ɕ��fq��z���������5�ԭ��b=9���R��B���#����h�۞X�T���
�I>ҿ���V�ܼrps�ޣ'E���l[��[�������A�#	>��8"�ٙ��M:tFI��2��2çj�Y���{t슌(B���A0G|����\.OR�7�f��JN.��1�v>��;=���� *�y����0�zz}���\�CrP��QގyJ�7۩���l�Z��+Ҝ9ʄP�鞴����ρ-�����3"<�Wbݪ���I"���۞dt��Ϯ=*���)&V������QR���L#Ka#s� �s�}���2��d����G�G�Į��t�jN�nR�|�<H�A���� W�`��[�.cms�e17.}���?5Mž��˷0c������ �rq�O78]���� ��Q�w+c��4(_)�T�$��kkuΛ�]�^Fb��FIcm��;�ƶo�C�662���,0C���Ԇ�8��o|9^"�ۋ4�~+�u��4����?*+�I��,	�ߎkE_����w�V����5�,�^nV��}}F�[-��U�t�?�Z�<=��=�n�+0�2?�Q�k1��j�Mm�!���[��L����~�mDy�$TKF��� �7�`�P���OJ�ġT�jTen�Z>l�}�+c��ECJ�k�{�� δ҈���|E��x�S�VD緷g������ {?��}	:fQ��:�h��h�i���0r;�?�l8����\�C#m�b��G�ڊ�|0�4E[�m�K���J��mS�;NK{x�#�8����R����H��Z,lr������G�ٗ�hУ��mDN��v"Cs������ڄ)����$u)���*Č`t��%�42�x�R���b*2��i���$��x��z�2�s�lO<q�2p�^�����&?��u?�皍!��q�L�%(�'�-��#s���0�K�}��V)x�o���,�\�>%�գ��O���]����������T�i��A@�Rw�i��Lk�ӥ�f�H�@q�]t�G4�I�}�oz��8��� ACːt��=�_�^�,c�L��U�����<���Q�����[�~cϞ���d>��� F�ݎ���qI%Y#�1"�7_Ɨ��ϷJIzd��I#aU�y-�m�b��W��]�qIT���~hM�V&������dlnWaE�^L��� �G ��N3ֈdS��/˭y�K�|�FF�6#}��!��|0� �� ��n0���q�zS]���2��s�Z�yLV�!e���6�<�NM>i��I�#��;y�vS�i���S-��<��n����8B^������V�[� �vW�OI}·[]9u넷�G�Fy�Q�	Ax�ي�S#8뷵/�M0![���+s��?ޥ!� �K�ݓ�$�u��d�H�?U��	P�����m<q�����E�z(��i�D��1�z���&�ʓ�@��D5�m9R���=�'UL��Y7�#�pיw-��~X��.:s���M�xv(����y%iB��d�=����g\I�j�il��1/	>�����3)�yQ�`0������(�8R���O6L��O!�<[�^���{���X��: q�K'ȀH�)�x?���0�Zm�\@�$��Ƿ�z��:t�E'�]s���-2��� �5�(�`[6w�������N�g�;�N��y��\��];UƭQ��*]Iu�Xoɖ?���H"S�NO��$�dM�<u2��l׍ �u�w4inU$�+�t����Ԇ���3GPM(*�Q*y�v��Q��GK��E��0ҏ)+6g��␃T�Wjܧ"� W���ƞ�����f��O8w��� ���5���=�a��Xs) ����G� ¼Isl�E����'�o���{��V?ނ�,�zo�"�lz� 捽�;�c��Q��5�a�;��P�h���'����6�Բ�R�/�n�$�$Kp��*-lM��r�t��WG��n���'%�=#��"���T��јGVW�� �7Not5��pFs����Y�Po�?��%X=���~��#�ʞ�\�3�@>���c�3��6�R�NE&�(#��A�X�9�� �J����_�7�h��(&~�&�����I�m~��N?��:5n�\
A�w�Q,���Fi,�jXs�0>�Z�n�U!d����H�ӊ�q�i��\��w���.�B	
��@��ͨ�-ğdI|��ᑐ�����q��ey]Ƽ��?�K%����9Ld���k��֚���Y��/�����;��Uh�Cc�Ar���/�����U�c}����qqGv��� ��|�~�#���7��M>�Hxt%��e`��yW=?�N� |:�o�3h�۔GFRv$��jsO<'"0p>�ld{{֊[@�G�B\:u��ڛ�t�m��/O�zP�g�CF"n�]�;�p�^՚Q-�Lna4sA<Nh[�K��*ec�~�o�k����fX�i�Y�ߵ{6����U#;U�3�����Q�p�I���D�n	V�u�u�5�چ��ctc��L9����n?7&�fc��}�����|z�S�\0&_��0�"HS���.i=�������L��3�� ��KD|�EoM5h���	e�*���4s�H��
�wr�A]K��8��%�@+��
B:'�d��4;����p؈����������z3^R.]�V�2p� �{�ך�ęT��?|~ �jȘ�Oje�J�p�#���T��ǌ�$���$���?!둚/Yx,��R�������]�����Ɓd����œz��	��~[�1�������&Ǩjr��ˑH=}i�ĺ����[�BN-5�9�N.2+�{�a��N
�O�+��+z���t���2&AS�/q�I�d���z:�� �u�]G�{G��~�u��bU�a?�;��a�ǘ��65��yr%W^��v]�L�fC�+k�{�	�M{"G9�^a�� �~e�RKؼ�T��2�=�ɂE��>��$@���^G���'jP���}EA'���� ~f��ã��G���
3�W �;���I+�ޯ&�}$��T � �~��܃V��nQ�����3���3�)�XIq}�݊2s���VE�FMŦ.�^�A���ʎYÀ�q�T��� �)�g5��g
y��Nz�k���i�Mw�:�o��w8nW��T���m<����Z��8��Ҡ! ���r&�S91�ۚՏQ5���_N�>ci�o�-��^h�$'��][�U���2bL��v5Di��gƺR�!'$�� p����l8{M��E����4k����3W/��d�����)R9��4w�ɝ�LQ�<�ͥ�G譸�[�MWE΂hG�!�OK�p�⟑����'�'��S��|R�,� �z���{���J�Fa7:,R6S�#��-�mkJ��F�tI~1�҄�G��tʮq�F2�>a*�<�MY)��KZӧ��"��q�k��$��?:vvuܐqF��i�V���9���1^JF��a����ۣ�ϷՖ��\ t'm��u��\�ĭ��r��e�ה�G�T�ٮB���bZ a�;�Hs�;��ހ�X���5�`Õ��'�"a��!\6��[ַ���yaG���ܪs�Q���S	��ΦDΜU���5ک,X������~��pz��K�1������Y��4Y4MNXpBʓ�X5�nA-.f���?(�g�/�Ԛ\S*�z���څ�L8��I�;D�-���t�[@�}�G����LԖ�Qh�3�:R]f�;�[;g|��}�f�?�5#�L��|�(z��k�~^�`�-�O��6稢.]�?��Q��]4�e�+?i�'��o��E ��"T�֔��ՙ��Co"3z�VCP�:�+ɥzx�7Ȫ;�3��ˑ5� ��5����ʵ�0��.?��F�t9�d����$�Кvf�c���
L-�ԪB����$>�J���9&�f8��NhG���Ӡ�Ͳ�F \m�(.͐rO����R"��p@�q��=� ��p\�K񃃚��� �d�E�����朋tbWl���/']>ݤl��;��q����ç�3�I���A�i�ڬ���1�M�ml����ϜXK�|��7�l-��t��2���rHa��~�o�S�����LaH��WA������ �q%S^-(�j1�"ub+��]Q��	=s�Q���[� 
���Ս͊�~lG��n>b��\��Ų�T����k.]6<��Mx�Y1t�+�7�y�l�u�a����7mr����&������EŎK(����}?�ˣ�7k�ŭǑS��2�pƛ*�A�z~uč�Ts�5���e�F�Z����� _�=4� z=��8��*��_��a��XsI�PMv%��)�Ï��d�Fڸ���j�O��Jj�O�𞽫��R��BS���~�U��Nn��Lh�nż��5h�����y�Ms���ˤ�#<��)�O���T���<EH�S�+�{I��.c�p7�z�
��5�b_ۺ:�#�_t�v�u,&#�����>3�n⋭6��%\�OC�;mM����PGB(VlN�:|�j��"���7Ɏ��$+����\gjȢ����+� ������n&�fc&h�ܝ�������3�/��Z[�aNwU�;T����i1�/���o.߁2�G�$�Q��a���)G&�{�>_*�@˲Ļ�G���)�����{Q�|R�ie�p��We]��W�[�}�5���#������AH��-�k��T��%ʱt��3t����R�:zw� ?��͔��ܜ�wc3� |>�;�Y��u���� ��!�����r���8������*���9��U��LL[
:��M:}�b��9�J�^��D
r�w�j.a��*���J���݆F
p��s�]`]�w6e������?�j��;�֧�^;�nm��.a��O�&�h$��/�/֡,�i4��M!�zQ���%�󎴥Pg��WRy�	Q��W�Ό�ݾt�ʣړΫ��zk�ZqW��JO*�U#���Jʅ\��hκ�īllD�p�6�t�mi*9��ܚC�]�?'�.��C�9�����qM�9� �\�ʜ��,���'})� 0���Cv&��KI�UН��-B7�)�����|�a�� ��Lw6�d�kV��=ҏ�4��(�E��"��7��\ƪ�8�- � ��+�?tH	?���'��
��m�[YN���������w����e	®���_RX`f������ė��3�y��:�5~:�Q��:M��ZDq���U��t�ɖ���,x���Q2��^G3H?h�?/j�AH�EV������xay�dҋ^3֬�gX��y|���[���_�3U�`��*M���Zx��Lܗ>m����	\����?Z���p	�g����`�򭑜g�]���p�������i.��ƙ�B`������R���Ikv��p� j�=迮�0�Q�[P�K�9[� ���V����[�N;Y���jQ%���,�l5�a���[�S��s c�h6\R�*gC�<sG���d�^yd)���8��N��3��W�;w�>$�IE4��.RaϙY��8Gtn)�+�)�8��t���RG�Jrm��*G\�0&{R�~]��f��;_�ɱ!�e��&R����-��E m�����8a������ 0H��y#g�W�LG�9[|m��#a�6�j痒y}�J(���� U$��)�Ӕ����CHof��"�&��>�)IZ�[�F5��Y���� �oRj�Fٞr�E�ۧ� >N��S�6�?A��Fi��������+1M��Q�6��XRB(��(�ˇ��EׇqN�y�#I���;Țs��Ga��?�En���$�`՚y8�w��Y<o���K
2����&A��֡�����M;��� T���$��o�&x��nd� t�x� ��+�\����(���@�bP������Z9�o]D��� �kR6G�h)��\]H��4�S��/�-���F��5 ������FRJ)?���R�6�Bp����~Ta�r {T�i��xӡ,-�>�z]"�� ��r�8�
$�{o>a�\�4��\`ލh�3��#ݔ�/�,|,�d��*W��Y RdbH��xS��N�*͗�#�km1�d�s�D-�ee����S��m�
i�2� �sI�M�$:m��O@Y�����XZ�6�s�^4֗d���c�ޜ���>�%����)��0#$do�⠜����4��\���x,�lu�U�-f��k�`��F��[�l|�1j|3y���[w!e��#�N���Ь�����<˥@uB1qjȯ�ѕ��Z'�_�:� �� O�5�<2��"�u�T}�����*H;�4�{uV`3�H�������5���g+��+��o.f�� ��!�q���-0(�߭y�/7zx�:CK,��3�� ��%����+`���J����	�e�;IU�TU��9����ՐMKw4W^O��                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           ���� JFIF  H H  �� Exif  II*            ��ohttp://ns.adobe.com/xap/1.0/ <?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?> <x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="Adobe XMP Core 5.5-c021 79.155772, 2014/01/13-19:44:00        "> <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"> <rdf:Description rdf:about="" xmlns:xmpMM="http://ns.adobe.com/xap/1.0/mm/" xmlns:stRef="http://ns.adobe.com/xap/1.0/sType/ResourceRef#" xmlns:xmp="http://ns.adobe.com/xap/1.0/" xmpMM:OriginalDocumentID="xmp.did:493EE041AA20681196389F7308994104" xmpMM:DocumentID="xmp.did:0D8DCC4CDC1B11E4A3F98CE0D87F77CA" xmpMM:InstanceID="xmp.iid:0D8DCC4BDC1B11E4A3F98CE0D87F77CA" xmp:CreatorTool="Adobe Photoshop CS5 Macintosh"> <xmpMM:DerivedFrom stRef:instanceID="xmp.iid:316389A1B17911E492AEE7F5E1560828" stRef:documentID="xmp.did:316389A2B17911E492AEE7F5E1560828"/> </rdf:Description> </rdf:RDF> </x:xmpmeta> <?xpacket end="r"?>�� C 


�� C		��  �," ��              	�� A    !1"AQaq��2B��#R���$b��3Cr%4DS����             �� 0        !1"AQ2aBq��#����S���   ? ��E
(��
(��
(��
(��
(��
(��
(��
(��
(��
(�&�;h�Y\$h2�� ��!��p��][W�)�� Ƌ���ӕrG�F�������\���k�?�oUO����>���C�f��q;��!���ɨ��o���H�������b����Sk_�7?� q��P��S��� ЮPG��y@d��ß@?�c}�����1�����d���=����#
`��N0��b���=jƆx�aIa�e�E��*�� ����gh��\;�t �����ۭ���š����*��LF!~��=�n�5$ȸ`�)+k���Ia�d��U��JԊ(��(��(��(��(��(��(��(��ŝPe�(�&�P�I��̠��WV�A���
(��
(�帊�I?�P�R1]C?����KPEPTڿ�[��-�->Sޠ��e8+q���ү��O���Q�v�)`�ۺ��	�* >|��Qo�����Cwb��4Mo̥�nc�=)Pؒ @P[£�-!v�o�F�G�Vihc�8;(�^8]���`l�/��:�Knewls"���Z�p��J�e
�%PN}����'��H����֫,J�:��H��4�A	�22®��~("`
�q�V�ӥ.��[��g-$T8����Cp6�t�Z�\�%���2�!OB=��]�ms��s�*M�$C�`zk斡���:������<��7����e�g| �� ˱��S�G���e�#�pν���d��d��#�)J��(�� (�� (�� (�� (�� (�� */�|Z4���̢��#n#3[>#�?�i�\e<��z|~��h���ó�H��F@P ?J�}�쾪�<��ո�p��Fŉ�'ҵ=����8��o��_���U���M�{]!�*0��*�>d����;(�{�v�1i�y�[lOũD��[¼.�Jǉ�$@�8��A?"jM�qR\Y:�l¹��8���RY/�/"_2������n4n(���9��q�d'� �[�|�����{-�qx�2�1I_j6�e�Oyq�+�I�*����8��}B��l��F�8�¹����4��iYm���c���|�7��q���x:��>��zD�®��5����X��\W7���8����iT%Q�?�L�o���k��׹�~&��>�t�C3c�7Z�f�x6U���(��� ڋ�,eCv�s� ۙ"0z�;=�J�P��߰Ӯ�<���B}9��Z�5n�t�U��%�k�����ۃ�~Z+�Bots�n��d�e@�AR2�����ݺ����9�\s��y-.�m�o$c�'�ӧN�K[�ȹ�	\�wo+gS�|��N�S�V�ĳ���Q�o����n�8�xG��_���M�#܍��d�]����3�@ T�^�EK�#b����.�z���x����v���r{��>¼��+{f�/Թ�|���F����d�4�����T�%�f�Cs�[�[O|���wd�-`��|�Գ�Ⰰ�B�<�Z�t�"�V�^�\"#*���$o���7ާ:6�p��$�/"����ڤ��7���l��h��A�e v����,pYb��� (���:�ƢfXcq�"
��5L`�%���QX���g��Z�>��]o�i^/��FǾMk�1c�o�jyWЎrﭝ��t��*�qcq/=ݐ
I�G������z��쵮���n���ax�����#����ҞL�`(����(�� (�� (�� (�� (�� �>ѼF�=�q�%��Tydt'��Ui�����j��W72���?�K~�6�Zġ�V�>}�u�����X4kD9�J/��i`�S'6�0�"��U�
x�mHB9�s��-2[F^C�POʠ�-�y4ڍ���U�p�XjF�%���D����� �,�s(��7 ���������֓ͦ^�� )"��:��5F\m�e7�/�[����c���q�#�������A��m�R�֦q�Am#s4JG1�G��IvW���$M��jX�M�1��tdW%����-
->�(�P� �1S{0�.���d�=�?Z�i��݂-�♗��@��Rx��Yc�%\p���J��"��.��n!�0T����P��Vl�ڠz��e��������W��u���Y�bG'��-���ͮ��O��Wd���L<w�F��R������ZDǁ�x ��z�n�-\@��d8�et(ޣ �Ӱ^2n�7Lgr�������#����k��nPM�΢
3qE����g�W�4��)O)<߈���p~?>g��6хF���V�چ���M̈I"5 y..��?3T��,��#=T�����t�'�2k:�2��anfc��+�4��[ �� y�Q�		�t����3|��Ҹ�������})�NVq�tZH���l�^i�I�L�����KkT������5����;�{�r(�z)�:֓.�*������P�c�Զ�F�&��O���{^e*��c�ȓ���P=KNִ��K�7@�K#�"�9�՛�:�P��4�">X9ڱ����FP�c9�_������Kz|�He�Xu��ߝ.#�n���@uRw���c�[��q�U��a$s�y�KS��Zv�k3.�V2	�9���]�\'�V��qŉ��2F�w�W�#�»����\0��*DB�(��(��(��(��(�
W�3Z�E|�����Tw��_���0�B�2��0:��o;W�f�RL������#�ջkv0\�rJم�?�$m��:t�VK�&<�,E���"hwq��\�sʰ#!�Ԏ�X]s@C̮ARQP~��H渜� ��%付fl�#��Ҧ:Z%��Y�Ue ��t<5��LT��D{)������#7jH+��o���ߥO����2H--"���JāG�Z� �M��h%I%��1��ԟ*�>�^>�tX��0�~u��12r�I�(�������raY~��=�X���ԅy� |l�� })��T5�F� ��R	��ԋ��I�@FC���xӣ#�S�6�8��f���#���$F	5�DyFv�nRO� ;Vǂ���S7s�ˤ]�����ym�Es�����5(��ʠ�=z��b��1�z��;����wD�Pd?Z�����{)���,9"(rs���V��N4���\�c��������,�����B���9�I%�c��Դ�N�-��{x��Fs�+��U;;�K��$Оe#�*s������� ��t�� ��U���n���#�M���Z롶i��y��H8�����������-��jV�L�G)����VS�m/�Rs�?ސ���`���l1�pZ�52K��W�V�G;����l�L��F�zs`~�Ϋ�ռg#o������<�[,����7@C�BPE&�-�t;�Nu�^$��Ê��&�+{�N����t8� �~�����M��48٤ :�<�_�MX���r[�;˭収���{�prq��NI ��4�v�{�B������;�9�C����u����\=��<J9� �Nh��7M� �F��]�_yGA�2�6�ӍA��n�V�-��u�yW~�.�Ǹ�����J-bv
O�$��*�R�<���F�������{'i���f\r����)��#o�+kTK����X� ,(ŋ�7wc�����'bᰧq�b���(k<���/.4�!$��[v�a����s������MT�3�Yޔ�h�<�ߙ~G#�\��c\A�|1w+Cm��ڢw-=�-1�9%���
��MM#N��6,���$䟩5dJ'���(�
(��
(��
(��
(��
�F�F>�5�#y'uk3��Rk��=��y��Fvw��5�,�D�4�AT�W��I�"c�~�'�7ԥ#���ߍ%v�E��q�G�?
��W.�L�a�H�4k��H9����'|�ً,�����񵾧y`��R�����o*�qo
���Oԥ!<��?˓��Lձ�ZL޶�K�y��=&�MB��N� �2M`t�B=j�ԣu��,�`m����w�ڥ�@�� ��r�s��+���w���7R+D�v�������՜���ӕ�m.U�Z����^�!-��>C�����+������>��G��?qB�z���Ĩ�Q0n��*ݮz|!b���'S[j�(9�9�4���r��ui���M����8�ݰ���f�v�3|���4��yn�1��R���oK+Nǆ�S��R��u}-��W�g��Ɂظ$z�&��֭]�9��*�~b�n9צ�3X�`QH眴�l��6��Z��׸�T��^m=R��+r�c%�$��t��U���eg�x_9F����B�N��99���#)�l�20+��f��M�K��Z2��=�+�-�\0���k^�;EZ���^M�I�ly�Ϯ��TN�
�w���B.����z�~v��\�vR=�C4m�]���WO���f�#�P���u�Z� Vؕ؟#R�̧�3��2�V=L��j�M�XE�6�k$yn^sѕ�MD��:�Oq;�p]�Q��{S҃�$�X� �8�� �%M ��0��NX���=Db��&�[⻞!�ivǺ�s������4#C��eN	SR.�[x]F9�9�����ZQ�mܤx��w���"U��9+u��Z}")5����P���#���G2�dS�Z�Y�Q��̄��B�OS@q��*OJ�s�H�^��ヰ��C��f�uޕs5��>����Μ���C��ei�^�1�8��!����1�6y*��Úޥº��h����)�W���0GºS�i8��w�u�DK�Mk�GL�%a��z��폣v�:V��V� �k:���( ��( ��( ��( �z�-�]����|qO)��k��I=������Q���J)�$�Y@q���b��b��}6�TA�r4�VW)�>x����9mu=VW��E.�K�C;m偊�\�g��y�V�'d�'\�7�Rqdb�[�']��b�9��G�\\'�$�:,~���%w� �UE:M��t�Wr��u g�O�1�>��&ݶ������V9$�\M�Z=��넹�\~$'����O�N��z{�$I�@�\i���&��vr�"�w����;Dw�7�h����5��x�\��8�����ywX�ȍs�
�j�ȱ���$G��EI�AH��Gx�� ��QK��V���Ӛ�� �7&�˲�Ծ� a7�#����OJ�b�jϻ`����޵�=�wF�[91�gɿ�U��q�FǚhW`��zQuj��:�����rC�h�#�~�+��'�XǤZ�*"�ڴv�Y�`e��|~<��{���p[r�����@�:���9��%ɭ��H?o��׶��sS�dӢ��U���1��ܟm�S~?�cqo�������J��	�Z��I;[��|�͓���cbލ(�*��Ezv[�!º`�uYVCn��`��d����J,8R;e�K<Ę���;g�N�[��t8Z;�Ӿ�V��uc��$���8��ji�e!�PM��ڋ�ZL٦ӭ��;���nk�Fy_ ,�|����&�Q�d����wN��cTc�G_Z�ob�8ḁ�;�\<r)�X�>�\w��]�\\�و�%�D>��~Tn��e��FŎ��Zq-ْf�$`�#�����Kz� 7�� �q0<�%oc�o#�Kh�byZGǀ���ݩ��E�6b.D�EO��'1<�2մ �����}�󜟗�)�pN$���	#��2�l��P����RƐ��g��K�(��Ӓ�ԛH���5'���c}?M�\ds+����t�^"�t�bMR}N�{�WD�+wQ���J����x�He ����ig�u��.�ƙ \��)��=��*�u	�1]���Fx��QER��QE QE QE QE����8L��r<#��v���eė�Sr�wq#(>ъ��`J�Y�#�ku�VX-AA�v,z����i$G`�1���\��j��u��s��	�ʚ��z����B����X7<*9����(����S�u+��}}+a�7�ʓd;Y��m��L�1x�}��+m���_�ȝ�����m#ArѾ��q�en��}=)ؚ;K���,����*|� ��[�=�'7�ѸZ�|�߷�X��
����e�;Ӎ�$�QީR������I# '֙���s��\3�{P�k]aK���y��������W'j|;s�j��тa�:�0 $�>�K���A��?�����1]H}�\���:�FC�]�q.�b�6�C�\�U�r=1Wd���[cr���z���EH�K-�q-�K����sk��q�s�Rz���+���E{������T�NH�-VE���e�N�������Q[����1o��N�8���$������ yR<�jZ��_O��HW%��Rv?ڣ���q�Mt'ٚ��r�{f+����\|ں8�$�svM�/r9c$�z��\���>du��9�5��O0>�O�`���X'i�:� �T�<��?o�U�l����R]M^\�ZK��uCqm�⢺ը������*Il�ι'p�y��<6�d�`Yc�6�p�SS�H�Y��]��#ȁ�ҟi�[�?3dg���S�V��\h�������rL�?J�� ��v
���i�����j瘤�ٳ���O��;R �7��e��8;�ߥ>G��|�G+'��W�V��"��Y���"����&�>	�:�B�7��#ݓ��}E&e(G�A����;�>.^1�m>��2^B������c'�0~u3�S�8�h|Z�\�� )���$���_�ue)�$=���W�h(����QE QE �\�:~�yp+G>�~������>�>ra��V�l%/�u�-�~Z(J��w#�����)Ia�2z��\�3d��f�,�9:\�e���x�RH.H��Mݻ�8��m�Ӯa tϥ#q$d���m�j����`3��4���f�PQ� 5�,�3�}�Ұ��,�ۚ�����4�/�.z�|�Y[�W�Mj{l]��d����Ѯ���BDq�p����[����-0h<��Ͻ5���'C���X�\���YV�G,j��<��U�R>}��7�X�%_���7�l:tP!���zH� �,���̽�Yi�;�F����(TH�` c��LWk�Vګ� X�ٗ؝뀻D�mK��ےI缗��y��)��/sȧQ,�`���﷑F'1�cү���V��XV8f�!d ��:t�� �\���x��6��������5-"�KIgr���%}wT߯��J�b�!r�U=�%�o	kK������j��ǀ��ڤ��"�$�v˯7M����}k����Q>Ӵ���#Z�������w�㣮/%���%���ͽ�k���	ֺ��M���g�R��f��I�D�wc���T�j�Ge��W7'���'o�k��/���P��"�0:nG�Z���?bk�-��Ú>O>{t�+�����&��)k"r�cx�������
�*��ƛ\�Gwo$2(tu*��j6Wc��Vʙn��z4�2JF��,�jVܣ���x�@�������o�Bިz}:|�L��A�s��[�bΪ6���"�X�pՕ����.���$}0�b��D m����E�dȀ�:�QQi���Nn=��اW�g����/h���[��#+�woOJibKt=��,?�9E�G� ?�6��G'�9T `��n?	��G�A�d�<��V$Hۥ�>̠�ث
p��K_� f��߼�����a�o��Q���_�c+(�s��Y��P�YbIG&�F�_N����R���"H<��>u�\?�ǯ�v�8��I@Y#�Ҿ~E6��Һ������$�273��z#�a���ڕ��|���7�ݢ�)p�(�� (�� *�������`y%�� �O���9nfnX�?�����^$�Hq좖k��u8{��ig~�Y�bD��=M`��<�Ҽ`��q^>yv�>g�\{y>�Z����Y�ʜ2�Z���a�xY���99�˨޴w�ߢ�I+/�\���Y-\vlDŵ7^h��9kI���(��rWp6���.5r6Qȫ�$��N���#����D =���k�y�W�� ��o��κ�$��]���=c;�������
%ދ0��#-m&�cD��*,�°� ��Ji�[�[��US�(��|����-��Ql���>����*-wO{1Z�JDc��2pN}������$�������8D�h1�b�3�� ~y��H�{h��	%eXcF3���X�V�|��S��ԍ����>T�C�Png3�n��!5�+��>sZIPT���n�x���$�l��� :3�O�<�~u��1�x�n���'�b�|Q���9
�pg��e<B�_�4{��ߠ��0���bi5%�uB��|�?�6���;�YZ�,/�d�+|��F٬Z0@N��j�QS��a�WIo�<Y��r�AǮ	��V����> �@�}Z]�s����*Np+�`�-�'2�0s��c�g�ep|
���]���Om<)�c��S�3s��g������3\�&c}�������"��ԫ#�br� h�(xO��-l�ͷs��>#q򥺨~49��í�[��"9���� ��:#n1S�d�8�_���ʃŏ*�Ks�y�4k4�QV=��X�'+�
�bF��ĭk����ݗ�e89�kb� ($�m]�rR�h�-���h�p��@>���J�'�5�0r����W���'�Յ"R�h�T�yOZj��Q� S�NNp~��UT�3�SU��c�� |� }���4q�?�wEʞMY�H��H�ܣ��J�U�l�|�_م�Z_p���1��9�a�˭FIJ-�q��;���NE{J�E��Ҁ2��(U�6�\?}��C��2?j�yn�{��g~V����t##��\���+��ݔ���G���� �H}3��� X�:��� ��ҵ��G"cɗ#�([��ȃ�oߥd�=\��Y-�1������}=v.�JS�ڴzč��wa{ň������S� �H����V�Uy0��ec7��]��� ��Soo�N���k���ʒ�W���t=<���tؠԣ1�ߎ\0����U�'=��z�=���e�6	����Kմ�����B�l�}�wҘS�o����(����j_�ܛI"#�X!#� v�3���#��*�)D���6S�p+��ʍ�9���E�k��"t��p�0A�V�ݞ�h�[ȫ<c2۶�(���y�TZ4%� �<�g.��lpr
���+�CQ��P�^&�`-&�����E�j�dj�D��v	0�f�sO�x�O��]��t=��L����U�*������]_8��л���\�7�AI�][��d��;����~Բx�+fr`i��������X��P3�d�� �ǚU�?�ҲzII+�����'�W]��� �k��7v�H�����3� mX��5��{���\�Њ�PS��%�II{�d�Zqk":�r�mZ�F���B|�x�$H����d���|E!�\%���jȦ�n��>�8�0y\y�e	IT2��!���"-^��&9��>U����/���lq� ӝ�kUK�!�M����Q�����zO�|�}�A�סYF9��^ ~u���2� ӱ�WBrHcc�2�Jc%$F�'�8v �������sS���x.�B����T�M�l,%{yD%X0b|Y)�V������ V܆oA��Ԉ��pw�q4|av���yd?��*��oj��7�׸,��&�D|����~9���[��L�M���)���{�XP-��( ���AG(�����	گ��;{��Ɨw��2~��[���<�Y��E|䩉��a���U<��^�և`v��.�]� m�|X�BM(9Rs��V�Qfە%�x06�;V��ź�!
���++8�t2㘶�<��ؼrNK��Zrݶ=G\�� ��c ��+,�C,�`d,ےN=+����\�x�U�b9�u�s.O�[���O�uK��ೞ�+3�-���ڡws�h�o��^�[��y�8�C�˸�7� ��Mt��59L�OK�;��Y-x��m7���s�����5hdr��)<��?z��J����5M�W���']��vhb������$��Ed�@ۜ��Iʱ\�HV
J����%e�3G���O�k>��̣*�6�|�=�N��C��P�zX��"�p�RF`!�թqc�H�* Q���*���Y[Z��]�\F�� �g_�&q֙xtq1O��t��˻;� n_�F��Cb�`�i���(޲��|��=s�Aڼ9o�?Z	3�V��<�-ĳ�D`C���1��RK��n���F>� �<�V3�>>u"#�Og�L�7���H����sɃ��uֽ�ǩ��D�͐Er������4�Px�>����Z��-�e��k��V����󭖣��N;�ޣeʰ9���^�k1c�&�5�E �HJ�t��Q�k�FHfe���Sb�%��'�S�9�=i�|B�%� ����������8��[l���E��X\/} \�R���z�zkL�8�QW=���P?�Y���r��,M�s��%@wR=i@9�U��@kI�������p�Z���w �jA���>���1�]H����iq�IA&H*��^¸���9���sm;�4�r� V��0�����͚[^|��-����5xk&C�(��P���{�&p=���@�qS���6�5]��� ���=�6�n��\��:[|��g�G)�,��Λ�+9ٻ��v�Y�Jm�t��&�0:|kW$2O'5�sw#e���
�'ک�)��b�Tiǋŀ�~;�ï�afyaz��:�30O_���ݞ�ZA�~^��nS���ݲ:�����Y�`�����g�i�e7w�2�� ����}Mt�*�d@3���ټ?ޢw,�Iwϯ�X��L6=�0u#ȍ�Z��Y�`��P�?��{C��9�/jCSѬ����,�Q��b<���r�����b3DN|����֗��тG�؏�Hd���C5��-u�)ya�<Q6��=>X����b�yO�W���eY��(y�0�:o�VO���\
̡�>Y���Ot����GZ&�.R%��cEQ͚���Sn���+RR}ո�ޕY�m�Q�X�9ID����W������,���9�r�pL�sF?�z�Z��UX�G,x����2���a�~�[7�~�G*��OA^���������{�}˺���j,x�K��\��p"b�"���s�~����1<%͸� �I��$�^ui���-k*,�:�T����p��6Ök'�~C���jߵ�mo�綸�u���b��K�E�z��L%ջ��$�[�)yGCv����2wS�+�)_=�1�X�$��1R�:�����WjyX�A=/�Q��C=&��}�M!rHސ���u�={2��SE��{��]�����J+�]��'�Z�˖�t#�n���`���O$�I�A(��"m�NY�_�y��&�I��UgV�cֳ�C�Y�VZ�$��F��h�!�Y�$��Ԥi7�l�,�.���
��Tu����Ma�M��v}�P�
�A�J��{�@kZzh����Y�,c��G��R����j�Y�{EUe�X��F���J �&���_���89��~�����o@z~��R��@� ��F67M���f�p ٫��m�K�h�MΘI�/�f|<��.q���?�'β� [ԃn������e�h�@�X����Gu��<�ig'-��[����$g�Z�s1o2��u�<&̟\�X��ť^T����;{^n����"� 6�
�k�$  ����'),��6����"][�����l�0��~X8�U�� ��MP�ga� յd���_/�W���]��Ntų��#Z�U8��� ^L�ǜ�ymH��R1,���K��P�mZ�e1���Q>>P5�67S[3wh����jo���:��@?����W)F�8�3n�*WEId��ZC��cRoar�~��M�\\�Ow0?�;��jx�'7�^���(Ӝ�W��s~e��3��#�QVO�t�hX����ů��-�5[�,��K�e#����(X� �
LX��V-�}L�����A���z|!/L ���HX�),+��#n��;!��
c3�"��������JM���IZH	�\�b������[��#�+�@6)p;�@�q����� �c�g��Z[�Ѯ
�C�Hڽ��R����֎h��嗉��Z��T�..u�1��@'$�4s�'2����.%��G,f9���v�Qw*9Nåt�;����tr�����}���r�ʈ#ܚB5w��F˞�ӈ��T�(l+t)�9�N�,X��n@<�G0a�{�P"��>���`V��lT2�p+�~J��GtifR<�y���Fs�Ջ���Q��W����xH��i��\A#E,l]N
�Њ����A�4X$�${+�9e�c.�G�RC�z~�����ʷ�LE��hu�Ĕ�]ӢNP?��                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          ���� JFIF  d d  �� C 


�� C		��  � � ��             	
�� H    !"1AQa2q�#B��	R�$br���3CSc����%&4������              �� 7     !1"AQaq�2����#BR���b�34���   ? Ꞁ  4 h ��@�  4 h ��@�  4 h ��@�  Gw����4v��[�C����g�G���c<�N�w[i�u�h��6�w�ҳ��Ub#���r�F�	�� ���1&�������m�}��fty!�:��!V���j{w�.��\1y�U)��uQ�o�Z���CRܾm��� )�l���C�����;s���T?��)�%�'őf�x�>A��^��1^p�ȅ�X]%��n[R_`�	�����C7#����^�y������ݾ
��Go���Y�a��0+Đe��/#��J5^8b�b��!����$��V�W�������J䒦+��2��E-B6ܷL���A*xD7�|@�_8�{���U�^�y��;益=�x6t�(�+%Ijq4`dGvÀxC�,�H��"�m��������[[�5��ǕC,�\I�,\\>�9'��x�ȊZβP�nREGK_oi�����Ȍ�̊
���>���*�����+����	���#yDfiӔ���ĸ�}��:1_�e���Tj��m�nXđ�e3.
xL�W���p�2<��?P���\�ܛ��vz��h��e��cB�W�Pѡb�p<0���w�t%[���*��N�R�:�[fס����h�i�Z\�!S"�������D��[�7�[ur�`5w�OY,pC�-<bi���I�8�9Uß��,4�����̋��QSYi������y�� �r����#P>�i 4 h �[��}���i����f`��_^N݆=�Rz���\��zYN;��z}�M����0�F�҉|X� ��}�m"�s�XZW��丩*௦���d���y$�0ea�z��i���N/ͥ4 ��I-}����(��*b�q1R����iW
z�������WjV��jq ��2k�!F@`}Fs�өE���?s�����<�P��P�Q��UX�ߋ>���;�>	�E�w!ϸ�O��Ws��WZ���!z�*ɂ>R�X��"�\�[$���NrK�1�%[��y��3Mn�D�z��KOB�%!�_�1�8󫷋�%�l��dZ�1��{�k�j�K]Ԩ�l��8i���9��߃����͌a�'�L�`\�/�O�p��/�j��(z����H�)�1��9c2�r�`�� ���x���t;*�V~~��檞�$�Ȝ����X��_C)��ǜ�Ӝ�m�c�:Ͳ���O�[�4�V�O|�g�#ό�0��N�U�0{&䥻h�㹑6�⒅��Q������2��KRE*���H�>PF��1��N}��ᷮ�~غ� �w��+M�2�9�قz�a8G���d��F��[A�ĔVK�mM�s���]�����,<T�@|%�$�+�C���+���G�˶7%��]]������H%��i@���T�NW��"6\�Y��X�Ei�2βT���b�j�_%����&i"c�?���D��i 4 hխ��Dl[��e�fR91���P=����{���<U���F�ܫ��ձ�U{u,��V'Őz�#�w�I�1��9I��<�t0��p�Ԩ�P��PT�F��#�;b>�`�c������J��v/���Qn�w���sWO#��>C��� +v=����*�^h���4t'knJM�a��Q�z��W�$ ���G�欎�d�[�N,vԄa�]ٴ-{��m�zsSHIb���(�{����8������c�W�n�j~qS��⁀�g�o�9�Ğ,��ڈE_F�W��g��;Go��>c�� G�FP��2�����=�ů��%���Z����+�%D2CS�Gy�H��r|��$`���kXa�0�h���T�	,�AI^�P�#���P���y�( g'��Fl�򏛺~�I��tkU5==p��9�i��L�@�v=ǡ��c�cW�3m��]}l��&����VA;3?����O
����O�,� �'��_���n{j���m�;K]*��hj��a߉��D$yH��FX�G�!|����E,��\���3B�jX���$�	�����c͜i�Z�� y�)��~�=uҍ.5sGM�Ù�r�9<a	�d �����\?�yO��wF�.���}]EumH�,\eD�NŹ��"�=�I#=�7n9�'���m�
K���r���-=���n(�rq�Dl{�Ѵ�X��'�2���u�����Ͳ����x��	^y`Cw�g����M<1��I4�C@�5'����m��d��Q�WW�f\6<5O��?�8e� YWY����M-,<�F�Z���b<|�Ӻb�i�c�����>\w��8s۲%�k�V+k\.%V� ��%����?mU�m�we��Yf�m�޷XZ���$� W?C����~���U�&VuJ�b���<m����j:�ZR��s~l3�������4��.F6�m����u|�  4 �{%���MAK3L���8=Ǩ���:\�>��66�Rѕd&ʕ�;v�>�і{].~�����Oz�%2L������$6T�<��8mI�.C_�n��M�M=gKc�9�D�j͟"��������==B?�&_��wv�����R�u$�(�ѣ
�)$+�X��%��?�~í�xn+�m��O Z��5�T0y:f%;�2~#��1�#�V1 M��k���GGG᪠����0Tue9~>K�0U��T63��%�������EKҺy�X��¼S,��4JDa�D�$�I2<�����c�&��-Z%��U-$q�i�%p���u~b?L�  ��!��m��_�鷦�g�ltd�Ѽ������u3�c,��|�A�$��A��\v�zzZ(b��JJp�H1@{������N�<���h(9��i(����Ҋ���u����1j��=����H����>��3�$�s�����*4,� �*=���g��*恢y3<p7�v_�P���}�V�Z/U_=W�z����Z+NR5C��_�o�����'s�{}Mhi#()L�:oI6�m�:
�+&�X��&(��3;.��4��&�rĬ����d�횖+%����B�s�>�]�U*࠽2��l�ߨ���j]�;Ͽ5��I�]�j�4`73�̷�K����O�h�og�S����>���h�z>��}ta��}�����r��*j����AJ���P8o�\�=Ɨl��nG�wu���5�[�v�J�jޡIˏ�p3�q߿!�Ѷ]�&�d��[E�桹�VC!$��dV%C�=��7�s�q��r<h� �4���c����d�8�f�g �`���X���@�F}���S��ר�Y���=�n['��e����gݙ��vϸ ���v��u��5�ҭ��xG?�*���m�&8�=_�Uw�L���^ߑ�$�)��)�;F>�uImѝ���qXPC5wP,q��=� Ƕ(,I�o�^�����:.���X���`2�#���'��kt����TR�-7���m^�|�¦��'�H�/��M؂[��C ���SbI��jo��Z����m�����N裈�T�*F~��w���Z��%����6>c�����G|�����*�Y��s@�ч؎�Wa8��yFm��Rp��x�yC������O��C��\���L���\�= d�/��ۇw��Z��ң�9���^����}J��[D��]�����_=|�46��Q/�+���N<%�8�߾t�ӛ]�z=�w��R���l�) Z,0\��pI'O͞�`KF>^E{��$+5:ԼR����\��"�>.��ǎTgFlކ��Z�����X�^eZu������!S0�N&@�˄� L��܋l����Gh��bR�,B�~��Y��9I�P�a�CM؇odWuGS]k�J9^�`�K�j;��� ���:��6�ԗI�~�vEk~ܴ}>�IOC�d�S�]�b=^G������ ��ڟ����O+百O�5+qA�v\w]M���N�9��H�W�"s�$���k�.Qq�s��i�C'ïKi���z۽���Z��� H�ȩ,g#UFI�N����ƾ1}�ԏSs�����~��n�ܶf��۫IEj�������1'���q���a��u�kܧ�)KW~�T�q���D�x!�e��՛�l�hž�ʚK�ܺ�O��:�h��i��a�)e~*��#������>�,W��+t��mW�~��]:��� ���-��L��d',����}s�iZ����4U�A�K�����o��5����x�F fI��|�00Ou�����/o;��:c�cݒ*�.�ȵI*�F�Щ�:rj]��'>��};d<o��	���р��N�K}E��W�&�6���P�Lj#��N���n�'RF>��\��'Q���F:K�jdP���X��������e=�&���=X.p_.օn��[h�C�u4XhA�r8)�~���� H� �c>`�J�ij�G�)�A#�^(vD$9�P�K� 0I�� ��ɷeg�����P����0D'��`���5� ��}��+��ږP��7K�r`W&(׿����?Md��K=��΋����l�������{�}R7IM�)y����'�rF�俗��G�!9��z~ga#D�~��Xu*�r�������]j�AOO2������� �uB)I�M��r{`�Y�kF���D/ww�����Y�����~RU�1�(Cd���ޒQVEer�\ڹB3t˺��5�� ��]�=Һ���4%
��	��d���ŋ$z�'[uAE(��V��'/B��i�{�.��1�[�������j��U��p|��;��E_j��:�:�����l�xi�r!���H��?FEr���_]6�xpR�����p.������}I�~P[lOd����R���Ϩ�)���S<��,����
e[�,~]ˈu�gdoE���u
G;-3F�͹�h"�$���KS\d��Kd�"�6����|ĵ@��vi��S�F�u���$z+3��ʿ�{�yu"*��.Ի*�s�X����y�H/򐑗�rn*���J�Yۈ�+�F��s/�ԯ���:����w��*�/sHH~�tu���1��A�4h�=�q$�������9 �I-O!UK3���;eU���o`p;cPJS���mw�X�o^��m�r��9\`K?��G��jQ����p�N�UI��gꕒ#"��̈pW�pua�Z�*-<��A�}���RU�Hs�c�q�ӫ�YeS�����{UU��:衫h]"�؅I
�rGq܃�Ԟ$v65�]�Z��]��Q�-u�J��+p<B�f�^����'�C�³8���|o�4��Ԡ���eJ�o�=� P8�yzj?���	�igG�w����
��ie�s��s+Qc�Pq裾W�+�P"�o�-���j7%�R�-b��w�H�� �Ƕ����ю���9�<�Ntx�O�{�g[�q�V
�
E\�C�PO��j4��G�_��,ҷ���?q|=�"�5ueSWV H�$�0�rN�cY���FY��nY��6F��v��mn�%$� �J�2��#��O�م��:V�jh��d����Zo���RQ���Qh�����o_EFf?eս�L���s�3�9Ym�u�%���)� W!�o\��~��Zr�J;�����l��X7;�u����|JeE��\��^#�=V�Fze[�E�[h�|�\iv�1U;梶�x9�r!A$�۷c�o6V�r�Kdm�M|8喦��v)i�V(�N���x��'%�1���g[zj�|r�c��n�5��� ���hc9�|�GXF�',�u6ǷS�P�ZE*�	׿����3Xd��J��J�-��ns5��H'�:����#,��xayy����{�-�y��i�OU��_���g�w��_Wn�TROz�إy�jpW�r���s�q'�c����X妖7K	��ݧ���+���V9#�!��������m���e�%.O)�,���`�U5zǎ4v�{A����j�sd9(�U����e�\���p���r�)T`2eK�g��� ���"���cSm/DluvȦ�ykz�['�q�h鲲`�V�bF7^��t�*G�W��Iij#�P�2��6^��x�}�����c�^�ef��"����uF�uN�3F��$p�x�(A�%T}�;�{M���"�"�'�Zm��7l��8�§�f,űنq���q���$VA��c�{}�fA������TT<�@�|��
 �f^Ǹ��F�S��H���d{�l��~���T_%����|3���\�����O� �J�b�c��[p^]����2�J���+�Y��y�{y�{c�I��o S�s�ש�|�co)ƘԄސ����v��Q҃��Td	�p�Ʃ_�a[�Y6t�&����{�����5-;M��$�:�c���䓞�/��n�l��d�3_����ܞ9�c?/��3�g���=E$�����29���_�z���i��<r�'K�=-�K���V�Z���*�g����42�F��S��F�����T����K�>�u���-;TZkJIk���I��_�\a��}�@�NN=W8j6z�lOF����p8q䥻�z��_U����mvS�ܾ�}1�s]Q�+�qV��x�K�ʄ�T�w���9�mi�8$��n�:h�SO?��S�V��c�v�m�w�9�c!����?�����VxP�Z�_S��y]g�����~��V��
��kS{�9̓]��5��i�O�yb��������$��g����݁cZ��[+��wkM�aS$��娅0��'�=rs�ɲݯ'K��kN9Mvd����R��f�M�#3͂�x�>����U�я(�z{-nr4������v�R�+�*�4���n��"���2�ݺD�i)j<{�i����T�"y����ֶ�6s�n�/vm���˸�>��t�--�^z�D�=��y�����=�$�U�9����� �u�ʒ_�5���X��:��pS׍�n��{�b\婅fE���F�l��pr��8?}ZM.�C��Mw��SL'��JX�V�?z��0P��P �{zw{����{>�j��}�YOK�QD�H�NYVu�I%�B��3�4��^m���"��rڦJ��Uo���!VV0�ʡ��\�`����(N�}�&v��苋~®Zb���:Ex����R|�C�\�m.%�!0�;i[�f�t�P�Kf����,���$�	�ē߿~��R�6��jA#�$}�4�ö|F�T�+��P�a�c߷���5ݞ��*��Sn���68��T1�^�*#>���?\����e�X�� 2i�h�sd?"kӽ�v�2M%m����a�0�D�b
�@$z�c]V��-�lX9m~�OF<)�������6�m^�o}���Z|�(���" �Y��'>_�k��YF{���Ϥu�R�＾��c���[$V��-�0uI�7ħ�[�T���n�|�/�t��|;���к�8k�թj�A��i�d�{��%����������#�Q��>�jv����]<ݷ6ػ�AK!�ib�)P[���q��	��=�
��K_f�L;/���6� ���o5�~�Է�B��o��)$ᾇ>���M�n����
����WO��w��}m҆K}MT*���c�%�o����s���'�cM:�q�s���/畆B��N���Z�M2��تz�v�ܶ�h������f����8f�3���'XW�c'�%��6�G(r��˿�C���٬�j�^8��bs�#�$��4�x�ksH�Ud��c'�[ܺ�[x�OMw�К�T��6 )���=�I�ƦZx��,�-d������K=2n�;���ģ�@b?����Q&��M4��?���Yv'Ek�+b	q��Zgb�"��go��>�)�O�N��k�e�6��,#$�o��-=	�ԴUUMV�R��i��&^�,�l��N������@�wYeA썽�*�l��O ���O��8��R72�Tu���4qZ�~���b5�O�8&F=ټ���r�-�.t]F5��j[4�IGD�TSF�U�5��l܀r��`{�5WO�t�u�D�Q��ۿ�5;�3H���ҵG���A}I,�>�E]\�ȕlzJ۞ԷU�;U%�t2�Kya���=���2U�<G���Q���q94p� �4݈ST7N�m��Jnp?���@��X�t��i�=Z��I�O������X�2����ܩ$�N�E��!|��=��)��9m��?���T�$Z�ˇ��j=����-{r��+o�V����S bă*�A��q���pQ��m�Y��ı�s�O5���������Yq�=;j��&_�;��� �5��|"i�H�i��y������?]s��<$�.�C�������͕�e�x`�����T �ܣV�.�����?e���}ڵ�su��G�%� D�����Q�c����U�ζ(�� K9�V���<��핻/��~@��Lƒ��e�����RAA�(�'�q���]�NK8y9��n��Q��k�~F�on��,w�:�*�#�T+YQ�,�>����9Q�]��ӯ8�:Mڪ�m2O9㜬{�}9b	�B�^�H�F>~��F�'��M���z�5t�iT�%�S�<6�Ҽ������:�o�duC���������[�ޚ�Sv�A,U5+-(1Gĺ�R	�  ��^��Z;�C���7c����۹7�ֺ�@�R�\�G%c,��	Xǐ�c=�v/lL�G-�N�Z�hn�]�!���� �ˑ�(��g�0{�ꭞ�v�/��/M��i�ԴrPKC:�,��Q^i	y
 ą�� �mlR�����%�nY�)��`����7U����*-��zvu$U%��g# �/k�|���v�&����� �aVg"0�� eG�eU��1��_�ݧ���u����-��$0�O
�ʡ��� ���<>1a�1��� J�ԲQTۭTԵu��<)��Y8��z2{�9�r{g���.���$� �5d�4�۲� Rt�iG��[f��xIViaO�t��2@q�_+}FH?���Jߛ+���R7��fX�lAa���wYm7(fs	F���� B?�u�5�4tzV�$ŝYܵ��d��Ri�$�'�ŎpGc����Qib�ܽ��r^Wش>��Q��Q�6�=�KB�f���Ic����L��<��'�ZP��|�����ߥ�%��3�6+��iFE[��S�*�{����?=CY����Ӎ]F>^�3�k�o=@L��-���e� �� S��I�=��u��ќ��4�F5J��Ο����**gf �Y7Y�j��Pw��o�T����<"V�#���s،����;�r�sU���~-������Cp��|Z����r���=���9 k_A��9R��s�Q|�k�(�����v�3jUR\�nK�w:(�1�N�,�e�*?$�l�b�4�H�����I����z�������<����-\�E�M�Fa�\�7y)�P�Hd�RKӆ��Y��~�GLm�n����	�H���>�ԐBu�),���\$��Zp�\� +K4���?��6p�: �h~$l�[�G<㒭��*� �$
]�?8� ! �9.gu?A��uv$�����U �O!�գbX�G̺�ҁ�3�0��x/�Cr��y��w�(�T���2R�g�5FeN|��2A��y��e���E܈����-L�;�_�Bd�g�i�7�|2ǹe�;��=��~�Mŧ��[���*y�I,-HP���Ѹ<I̇��-mx;i��9�A?�V��9�ⱓ�҈V��7YzK�6��n4N�����/�&�T��1�O3��[�-�f���jj�I��jy���u?pA�e[bٯ]���:�VI7]L�ө��/+Ǒ�����϶����ٝ^�-B����w�W���O� |�� ��� �U�l`�3N�4�{Wl����U�6��zU�zʖe>�1�3�׊Q��C��Y�u�����6�rX��֮T`�����_O���Ԭ��*h�.���?�%C6墢�K�)�7ܲ�l��� y}�y��`��9�E._��� QG�s�S�,���_��޿~#��=E4�/+��ڍmz$����nχ
�U��[/:�R��*��RNC���Չ��b,̣��Vf�����A@x�RwV^.�=������V�'Y�+���7���v��r�$!�@$=�#�����\���p�KN�z�&v}�i���Cl�R�Q��4J�����ZJ��a#*V�O2b�)��VD�b�B叩8���T{rr�̺pҲ�X�R�L�<�m�Ѐ#����"UqȀE9�h;���	a���5���_x�=��)��U�j�