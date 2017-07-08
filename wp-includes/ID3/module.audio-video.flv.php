n array(
			'jetpack.verifyRegistration' => array( $this, 'verify_registration' ),
			'jetpack.remoteAuthorize' => array( $this, 'remote_authorize' ),
		);
	}

	function authorize_xmlrpc_methods() {
		return array( 'jetpack.remoteAuthorize' => array( $this, 'remote_authorize' ) );
	}

	function remote_authorize( $request ) {
		foreach( array( 'secret', 'state', 'redirect_uri', 'code' ) as $required ) {
			if ( ! isset( $request[ $required ] ) || empty( $request[ $required ] ) ) {
				return $this->error( new Jetpack_Error( 'missing_parameter', 'One or more parameters is missing from the request.', 400 ) );
			}
		}

		if ( ! get_user_by( 'id', $request['state'] ) ) {
			return $this->error( new Jetpack_Error( 'user_unknown', 'User not found.', 404 ) );
		}

		if ( Jetpack::is_active() && Jetpack::is_user_connected( $request['state'] ) ) {
			return $this->error( new Jetpack_Error( 'already_connected', 'User already connected.', 400 ) );
		}

		$verified = $this->verify_action( array( 'authorize', $request['secret'], $request['state'] ) );

		if ( is_a( $verified, 'IXR_Error' ) ) {
			return $verified;
		}

		wp_set_current_user( $request['state'] );

		$client_server = new Jetpack_Client_Server;
		$result = $client_server->authorize( $request );

		if ( is_wp_error( $result ) ) {
			return $this->error( $result );
		}
		
		return $result;
	}

	/**
	* Verifies that Jetpack.WordPress.com received a registration request from this site
	*/
	function verify_registration( $data ) {
		return $this->verify_action( array( 'register', $data[0], $data[1] ) );
	}

	/**
	 * @return WP_Error|string secret_2 on success, WP_Error( error_code => error_code, error_message => error description, error_data => status code ) on failure
	 *
	 * Possible error_codes:
	 *
	 * verify_secret_1_missing
	 * verify_secret_1_malformed
	 * verify_secrets_missing: No longer have verification secrets stored
	 * verify_secrets_mismatch: stored secret_1 does not match secret_1 sent by Jetpack.WordPress.com
	 *
	 * The 'authorize' and 'register' actions have additional error codes
	 *
	 * state_missing: a state ( user id ) was not supplied
	 * state_malformed: state is not the correct data type
	 * invalid_state: supplied state does not match the stored state
	 */
	function verify_action( $params ) {
		$action = $params[0];
		$verify_secret = $params[1];
		$state = isset( $params[2] ) ? $params[2] : '';

		if ( empty( $verify_secret ) ) {
			return $this->error( new Jetpack_Error( 'verify_secret_1_missing', sprintf( 'The required "%s" parameter is missing.', 'secret_1' ), 400 ) );
		} else if ( ! is_string( $verify_secret ) ) {
			return $this->error( new Jetpack_Error( 'verify_secret_1_malformed', sprintf( 'The required "%s" parameter is malformed.', 'secret_1' ), 400 ) );
		}

		$secrets = Jetpack_Options::get_option( $action );
		if ( !$secrets || is_wp_error( $secrets ) ) {
			Jetpack_Options::delete_option( $action );
			return $this->error( new Jetpack_Error( 'verify_secrets_missing', 'Verification took too long', 400 ) );
		}

		@list( $secret_1, $secret_2, $secret_eol, $user_id ) = explode( ':', $secrets );

		if ( empty( $secret_1 ) || empty( $secret_2 ) || empty( $secret_eol ) || $secret_eol < time() ) {
			Jetpack_Options::delete_option( $action );
			return $this->error( new Jetpack_Error( 'verify_secrets_missing', 'Verification took too long', 400 ) );
		}

		if ( ! hash_equals( $verify_secret, $secret_1 ) ) {
			Jetpack_Options::delete_option( $action );
			return $this->error( new Jetpack_Error( 'verify_secrets_mismatch', 'Secret mismatch', 400 ) );
		}

		if ( in_array( $action, array( 'authorize', 'register' ) ) ) {
			// 'authorize' and 'register' actions require further testing
			if ( empty( $state ) ) {
				return $this->error( new Jetpack_Error( 'state_missing', sprintf( 'The required "%s" parameter is missing.', 'state' ), 400 ) );
			} else if ( ! ctype_digit( $state ) ) {
				return $this->error( new Jetpack_Error( 'state_malformed', sprintf( 'The required "%s" parameter is malformed.', 'state' ), 400 ) );
			}
			if ( empty( $user_id ) || $user_id !== $state ) {
				Jetpack_Options::delete_option( $action );
				return $this->error( new Jetpack_Error( 'invalid_state', 'State is invalid', 400 ) );
			}
		}

		Jetpack_Options::delete_option( $action );

		return $secret_2;
	}

	/**
	 * Wrapper for wp_authenticate( $username, $password );
	 *
	 * @return WP_User|IXR_Error
	 */
	function login() {
		Jetpack::init()->require_jetpack_authentication();
		$user = wp_authenticate( 'username', 'password' );
		if ( is_wp_error( $user ) ) {
			if ( 'authentication_failed' == $user->get_error_code() ) { // Generic error could mean most anything.
				$this->error = new Jetpack_Error( 'invalid_request', 'Invalid Request', 403 );
			} else {
				$this->error = $user;
			}
			return false;
		} else if ( !$user ) { // Shouldn't happen.
			$this->error = new Jetpack_Error( 'invalid_request', 'Invalid Request', 403 );
			return false;
		}

		return $user;
	}

	/**
	 * Returns the current error as an IXR_Error
	 *
	 * @return null|IXR_Error
	 */
	function error( $error = null ) {
		if ( !is_null( $error ) ) {
			$this->error = $error;
		}

		if ( is_wp_error( $this->error ) ) {
			$code = $this->error->get_error_data();
			if ( !$code ) {
				$code = -10520;
			}
			$message = sprintf( 'Jetpack: [%s] %s', $this->error->get_error_code(), $this->error->get_error_message() );
			return new IXR_Error( $code, $message );
		} else if ( is_a( $this->error, 'IXR_Error' ) ) {
			return $this->error;
		}

		return false;
	}

/* API Methods */

	/**
	 * Just authenticates with the given Jetpack credentials.
	 *
	 * @return bool|IXR_Error
	 */
	function test_connection() {
		return JETPACK__VERSION;
	}

	function test_api_user_code( $args ) {
		$client_id = (int) $args[0];
		$user_id   = (int) $args[1];
		$nonce     = (string) $args[2];
		$verify    = (string) $args[3];

		if ( !$client_id || !$user_id || !strlen( $nonce ) || 32 !== strlen( $verify ) ) {
			return false;
		}

		$user = get_user_by( 'id', $user_id );
		if ( !$user || is_wp_error( $user ) ) {
			return false;
		}

		/* debugging
		error_log( "CLIENT: $client_id" );
		error_log( "USER:   $user_id" );
		error_log( "NONCE:  $nonce" );
		error_log( "VERIFY: $verify" );
		*/

		$jetpack_token = Jetpack_Data::get_access_token( JETPACK_MASTER_USER );

		$api_user_code = get_user_meta( $user_id, "jetpack_json_api_$client_id", true );
		if ( !$api_user_code ) {
			return false;
		}

		$hmac = hash_hmac( 'md5', json_encode( (object) array(
			'client_id' => (int) $client_id,
			'user_id'   => (int) $user_id,
			'nonce'     => (string) $nonce,
			'code'      => (string) $api_user_code,
		) ), $jetpack_token->secret );

		if ( $hmac !== $verify ) {
			return false;
		}

		return $user_id;
	}

	/**
	* Disconnect this blog from the connected wordpress.com account
	* @return boolean
	*/
	function disconnect_blog() {
		Jetpack::log( 'disconnect' );
		Jetpack::disconnect();

		return true;
	}

	/**
	 * Unlink a user from WordPress.com
	 *
	 * This will fail if called by the Master User.
	 */
	function unlink_user() {
		Jetpack::log( 'unlink' );
		return Jetpack::unlink_user();
	}

	/**
	 * Returns what features are available. Uses the slug of the module files.
	 *
	 * @return array|IXR_Error
	 */
	function features_available() {
		$raw_modules = Jetpack::get_available_modules();
		$modules = array();
		foreach ( $raw_modules as $module ) {
			$modules[] = Jetpack::get_module_slug( $module );
		}

		return $modules;
	}

	/**
	 * Returns what features are enabled. Uses the slug of the modules files.
	 *
	 * @return array|IXR_Error
	 */
	function features_enabled() {
		$raw_modules = Jetpack::get_active_modules();
		$modules = array();
		foreach ( $raw_modules as $module ) {
			$modules[] = Jetpack::get_module_slug( $module );
		}

		return $modules;
	}

	function get_post( $id ) {
		if ( !$id = (int) $id ) {
			return false;
		}

		$jetpack = Jetpack::init();

		$post = $jetpack->sync->get_post( $id );
		return $post;
	}

	function get_posts( $args ) {
		list( $post_ids ) = $args;
		$post_ids = array_map( 'intval', (array) $post_ids );
		$jp = Jetpack::init();
		$sync_data = $jp->sync->get_content( array( 'posts' => $post_ids ) );

		return $sync_data;
	}

	function get_comment( $id ) {
		if ( !$id = (int) $id ) {
			return false;
		}

		$jetpack = Jetpack::init();

		$comment = $jetpack->sync->get_comment( $id );
		if ( !is_array( $comment ) )
			return false;

		$post = $jetpack->sync->get_post( $comment['comment_post_ID'] );
		if ( !$post ) {
			return false;
		}

		return $comment;
	}

	function get_comments( $args ) {
		list( $comment_ids ) = $args;
		$comment_ids = array_map( 'intval', (array) $comment_ids );
		$jp = Jetpack::init();
		$sync_data = $jp->sync->get_content( array( 'comments' => $comment_ids ) );

		return $sync_data;
	}

	function update_attachment_parent( $args ) {
		$attachment_id = (int) $args[0];
		$parent_id     = (int) $args[1];

		return wp_update_post( array(
			'ID'          => $attachment_id,
			'post_parent' => $parent_id,
		) );
	}

	function json_api( $args = array() ) {
		$json_api_args = $args[0];
		$verify_api_user_args = $args[1];

		$method       = (string) $json_api_args[0];
		$url          = (string) $json_api_args[1];
		$post_body    = is_null( $json_api_args[2] ) ? null : (string) $json_api_args[2];
		$user_details = (array) $json_api_args[4];
		$locale       = (string) $json_api_args[5];

		if ( !$verify_api_user_args ) {
			$user_id = 0;
		} elseif ( 'internal' === $verify_api_user_args[0] ) {
			$user_id = (int) $verify_api_user_args[1];
			if ( $user_id ) {
				$user = get_user_by( 'id', $user_id );
				if ( !$user || is_wp_error( $user ) ) {
					return false;
				}
			}
		} else {
			$user_id = call_user_func( array( $this, 'test_api_user_code' ), $verify_api_user_args );
			if ( !$user_id ) {
				return false;
			}
		}

		/* debugging
		error_log( "-- begin json api via jetpack debugging -- " );
		error_log( "METHOD: $method" );
		error_log( "URL: $url" );
		error_log( "POST BODY: $post_body" );
		error_log( "VERIFY_ARGS: " . print_r( $verify_api_user_args, 1 ) );
		error_log( "VERIFIED USER_ID: " . (int) $user_id );
		error_log( "-- end json api via jetpack debugging -- " );
		*/

		if ( 'en' !== $locale ) {
			// .org mo files are named slightly different from .com, and all we have is this the locale -- try to guess them.
			$new_locale = $locale;
			if ( strpos( $locale, '-' ) !== false ) {
				$pieces = explode( '-', $locale );
				$new_locale = $locale_pieces[0];
				$new_locale .= ( ! empty( $locale_pieces[1] ) ) ? '_' . strtoupper( $locale_pieces[1] ) : '';
			} else {
				// .com might pass 'fr' because thats what our language files are named as, where core seems
				// to do fr_FR - so try that if we don't think we can load the file.
				if ( ! file_exists( WP_LANG_DIR . '/' . $locale . '.mo' ) ) {
					$new_locale =  $locale . '_' . strtoupper( $locale );
				}
			}

			if ( file_exists( WP_LANG_DIR . '/' . $new_locale . '.mo' ) ) {
				unload_textdomain( 'default' );
				load_textdomain( 'default', WP_LANG_DIR . '/' . $new_locale . '.mo' );
			}
		}

		$old_user = wp_get_current_user();
		wp_set_current_user( $user_id );

		$token = Jetpack_Data::get_access_token( get_current_user_id() );
		if ( !$token || is_wp_error( $token ) ) {
			return false;
		}

		define( 'REST_API_REQUEST', true );
		define( 'WPCOM_JSON_API__BASE', 'public-api.wordpress.com/rest/v1' );

		// needed?
		require_once ABSPATH . 'wp-admin/includes/admin.php';

		require_once JETPACK__PLUGIN_DIR . 'class.json-api.php';
		$api = WPCOM_JSON_API::init( $method, $url, $post_body );
		$api->token_details['user'] = $user_details;
		require_once JETPACK__PLUGIN_DIR . 'class.json-api-endpoints.php';

		$display_errors = ini_set( 'display_errors', 0 );
		ob_start();
		$content_type = $api->serve( false );
		$output = ob_get_clean();
		ini_set( 'display_errors', $display_errors );

		$nonce = wp_generate_password( 10, false );
		$hmac  = hash_hmac( 'md5', $nonce . $output, $token->secret );

		wp_set_current_user( isset( $old_user->ID ) ? $old_user->ID : 0 );

		return array(
			(string) $output,
			(string) $nonce,
			(string) $hmac,
		);
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php

/**
 * Useful for finding an image to display alongside/in representation of a specific post.
 *
 * Includes a few different methods, all of which return a similar-format array containing
 * details of any images found. Everything can (should) be called statically, it's just a
 * function-bucket. You can also call Jetpack_PostImages::get_image() to cycle through all of the methods until
 * one of them finds something useful.
 *
 * This file is included verbatim in Jetpack
 */
class Jetpack_PostImages {
	/**
	 * If a slideshow is embedded within a post, then parse out the images involved and return them
	 */
	static function from_slideshow( $post_id, $width = 200, $height = 200 ) {
		$images = array();

		$post = get_post( $post_id );
		if ( !empty( $post->post_password ) )
			return $images;

		if ( false === has_shortcode( $post->post_content, 'slideshow' ) ) {
			return false; // no slideshow - bail
		}

		$permalink = get_permalink( $post->ID );

		// Mechanic: Somebody set us up the bomb
		$old_post = $GLOBALS['post'];
		$GLOBALS['post'] = $post;
		$old_shortcodes = $GLOBALS['shortcode_tags'];
		$GLOBALS['shortcode_tags'] = array( 'slideshow' => $old_shortcodes['slideshow'] );

		// Find all the slideshows
		preg_match_all( '/' . get_shortcode_regex() . '/sx', $post->post_content, $slideshow_matches, PREG_SET_ORDER );

		ob_start(); // The slideshow shortcode handler calls wp_print_scripts and wp_print_styles... not too happy about that

		foreach ( $slideshow_matches as $slideshow_match ) {
			$slideshow = do_shortcode_tag( $slideshow_match );
			if ( false === $pos = stripos( $slideshow, 'slideShow.images' ) ) // must be something wrong - or we changed the output format in which case none of the following will work
				continue;
			$start = strpos( $slideshow, '[', $pos );
			$end = strpos( $slideshow, ']', $start );
			$post_images = json_decode( str_replace( "'", '"', substr( $slideshow, $start, $end - $start + 1 ) ) ); // parse via JSON
			foreach ( $post_images as $post_image ) {
				if ( !$post_image_id = absint( $post_image->id ) )
					continue;

				$meta = wp_get_attachment_metadata( $post_image_id );

				// Must be larger than 200x200 (or user-specified)
				if ( !isset( $meta['width'] ) || $meta['width'] < $width )
					continue;
				if ( !isset( $meta['height'] ) || $meta['height'] < $height )
					continue;

				$url = wp_get_attachment_url( $post_image_id );

				$images[] = array(
					'type'       => 'image',
					'from'       => 'slideshow',
					'src'        => $url,
					'src_width'  => $meta['width'],
					'src_height' => $meta['height'],
					'href'       => $permalink,
				);
			}
		}
		ob_end_clean();

		// Operator: Main screen turn on
		$GLOBALS['shortcode_tags'] = $old_shortcodes;
		$GLOBALS['post'] = $old_post;

		return $images;
	}

	/**
	 * If a gallery is detected, then get all the images from it.
	 */
	static function from_gallery( $post_id ) {
		$images = array();

		$post = get_post( $post_id );
		if ( ! empty( $post->post_password ) ) {
			return $images;
		}

		$permalink = get_permalink( $post->ID );

		$gallery_images = get_post_galleries_images( $post->ID, false );

		foreach ( $gallery_images as $galleries ) {
			foreach ( $galleries as $src ) {
				list( $raw_src ) = explode( '?', $src ); // pull off any Query string (?w=250)
				$raw_src = wp_specialchars_decode( $raw_src ); // rawify it
				$raw_src = esc_url_raw( $raw_src ); // clean it
				$images[] = array(
					'type'  => 'image',
					'from'  => 'gallery',
					'src'   => $raw_src,
					'href'  => $permalink,
				);
			}
		}

		return $images;
	}

	/**
	 * Get attachment images for a specified post and return them. Also make sure
	 * their dimensions are at or above a required minimum.
	 */
	static function from_attachment( $post_id, $width = 200, $height = 200 ) {
		$images = array();

		$post = get_post( $post_id );
		if ( !empty( $post->post_password ) )
			return $images;

		$post_images = get_posts( array(
			'post_parent' => $post_id,   // Must be children of post
			'numberposts' => 5,          // No more than 5
			'post_type' => 'attachment', // Must be attachments
			'post_mime_type' => 'image', // Must be images
		) );

		if ( !$post_images )
			return false;

		$permalink = get_permalink( $post_id );

		foreach ( $post_images as $post_image ) {
			$meta = wp_get_attachment_metadata( $post_image->ID );
			// Must be larger than 200x200
			if ( !isset( $meta['width'] ) || $meta['width'] < $width )
				continue;
			if ( !isset( $meta['height'] ) || $meta['height'] < $height )
				continue;

			$url = wp_get_attachment_url( $post_image->ID );

			$images[] = array(
				'type'       => 'image',
				'from'       => 'attachment',
				'src'        => $url,
				'src_width'  => $meta['width'],
				'src_height' => $meta['height'],
				'href'       => $permalink,
			);
		}

		/*
		* We only want to pass back attached images that were actually inserted.
		* We can load up all the images found in the HTML source and then
		* compare URLs to see if an image is attached AND inserted.
		*/
		$html_images = self::from_html( $post_id );
		$inserted_images = array();

		foreach( $html_images as $html_image ) {
			$src = parse_url( $html_image['src'] );
			// strip off any query strings from src
			if( ! empty( $src['scheme'] ) && ! empty( $src['host'] ) ) {
				$inserted_images[] = $src['scheme'] . '://' . $src['host'] . $src['path'];
			} elseif( ! empty( $src['host'] ) ) {
				$inserted_images[] = set_url_scheme( 'http://' . $src['host'] . $src['path'] );
			} else {
				$inserted_images[] = site_url( '/' ) . $src['path'];
			}
		}
		foreach( $images as $i => $image ) {
			if ( !in_array( $image['src'], $inserted_images ) )
				unset( $images[$i] );
		}

		return $images;
	}

	/**
	 * Check if a Featured Image is set for this post, and return it in a similar
	 * format to the other images?_from_*() methods.
	 * @param  int $post_id The post ID to check
	 * @return Array containing details of the Featured Image, or empty array if none.
	 */
	static function from_thumbnail( $post_id, $width = 200, $height = 200 ) {
		$images = array();

		$post = get_post( $post_id );
		if ( !empty( $post->post_password ) )
			return $images;

		if ( !function_exists( 'get_post_thumbnail_id' ) )
			return $images;

		$thumb = get_post_thumbnail_id( $post_id );

		if ( $thumb ) {
			$meta = wp_get_attachment_metadata( $thumb );

			// Must be larger than requested minimums
			if ( !isset( $meta['width'] ) || $meta['width'] < $width )
				return $images;
			if ( !isset( $meta['height'] ) || $meta['height'] < $height )
				return $images;

			$too_big = ( ( ! empty( $meta['width'] ) && $meta['width'] > 1200 ) || ( ! empty( $meta['height'] ) && $meta['height'] > 1200 ) );

			if (
				$too_big &&
				(
					( method_exists( 'Jetpack', 'is_module_active' ) && Jetpack::is_module_active( 'photon' ) ) ||
					( defined( 'WPCOM' ) && IS_WPCOM )
				)
			) {
				$img_src = wp_get_attachment_image_src( $thumb, array( 1200, 1200 ) );
			} else {
				$img_src = wp_get_attachment_image_src( $thumb, 'full' );
			}

			$url = $img_src[0];

			$images = array( array( // Other methods below all return an array of arrays
				'type'       => 'image',
				'from'       => 'thumbnail',
				'src'        => $url,
				'src_width'  => $img_src[1],
				'src_height' => $img_src[2],
				'href'       => get_permalink( $thumb ),
			) );
		}

		if ( empty( $images ) && ( defined( 'IS_WPCOM' ) && IS_WPCOM ) ) {
			$meta_thumbnail = get_post_meta( $post_id, '_jetpack_post_thumbnail', true );
			if ( ! empty( $meta_thumbnail ) ) {
				if ( ! isset( $meta_thumbnail['width'] ) || $meta_thumbnail['width'] < $width ) {
					return $images;
				}

				if ( ! isset( $meta_thumbnail['height'] ) || $meta_thumbnail['height'] < $height ) {
					return $images;
				}

				$images = array( array( // Other methods below all return an array of arrays
					'type'       => 'image',
					'from'       => 'thumbnail',
					'src'        => $meta_thumbnail['URL'],
					'src_width'  => $meta_thumbnail['width'],
					'src_height' => $meta_thumbnail['height'],
					'href'       => $meta_thumbnail['URL'],
				) );
			}
		}

		return $images;
	}

	/**
	 * Very raw -- just parse the HTML and pull out any/all img tags and return their src
	 * @param  mixed $html_or_id The HTML string to parse for images, or a post id
	 * @return Array containing images
	 */
	static function from_html( $html_or_id ) {
		$images = array();

		if ( is_numeric( $html_or_id ) ) {
			$post = get_post( $html_or_id );
			if ( empty( $post ) || !empty( $post->post_password ) )
				return $images;

			$html = $post->post_content; // DO NOT apply the_content filters here, it will cause loops
		} else {
			$html = $html_or_id;
		}

		if ( !$html )
			return $images;

		preg_match_all( '!<img.*src=[\'"]([^"]+)[\'"].*/?>!iUs', $html, $matches );
		if ( !empty( $matches[1] ) ) {
			foreach ( $matches[1] as $match ) {
				if ( stristr( $match, '/smilies/' ) )
					continue;

				$images[] = array(
					'type'  => 'image',
					'from'  => 'html',
					'src'   => html_entity_decode( $match ),
					'href'  => '', // No link to apply to these. Might potentially parse for that as well, but not for now
				);
			}
		}

		return $images;
	}

	/**
	 * @param    int $post_id The post ID to check
	 * @param    int $size
	 * @return Array containing details of the image, or empty array if none.
	 */
	static function from_blavatar( $post_id, $size = 96 ) {

		$permalink = get_permalink( $post_id );

		if ( function_exists( 'blavatar_domain' ) && function_exists( 'blavatar_exists' ) && function_exists( 'blavatar_url' ) ) {
			$domain = blavatar_domain( $permalink );

			if ( ! blavatar_exists( $domain ) ) {
				return array();
			}

			$url = blavatar_url( $domain, 'img', $size );
		} elseif ( function_exists( 'jetpack_has_site_icon' ) && jetpack_has_site_icon() ) {
			$url = jetpack_site_icon_url( null, $size, $default = false );
		} else {
			return array();
		}

		return array( array(
			'type'       => 'image',
			'from'       => 'blavatar',
			'src'        => $url,
			'src_width'  => $size,
			'src_height' => $size,
			'href'       => $permalink,
		) );
	}

	/**
	 * @param    int $post_id The post ID to check
	 * @param    int $size
	 * @param string $default The default image to use.
	 * @return Array containing details of the image, or empty array if none.
	 */
	static function from_gravatar( $post_id, $size = 96, $default = false ) {
		$post = get_post( $post_id );
		$permalink = get_permalink( $post_id );

		if ( function_exists( 'wpcom_get_avatar_url' ) ) {
			$url = wpcom_get_avatar_url( $post->post_author, $size, $default, true );
			if ( $url && is_array( $url ) ) {
				$url = $url[0];
			}
		} else {
			$has_filter = has_filter( 'pre_option_show_avatars', '__return_true' );
			if ( !$has_filter ) {
				add_filter( 'pre_option_show_avatars', '__return_true' );
			}
			$avatar = get_avatar( $post->post_author, $size, $default );
			if ( !$has_filter ) {
				remove_filter( 'pre_option_show_avatars', '__return_true' );
			}

			if ( !$avatar ) {
				return array();
			}

			if ( !preg_match( '/src=["\']([^"\']+)["\']/', $avatar, $matches ) ) {
				return array();
			}

			$url = wp_specialchars_decode( $matches[1], ENT_QUOTES );
		}

		return array( array(
			'type'       => 'image',
			'from'       => 'gravatar',
			'src'        => $url,
			'src_width'  => $size,
			'src_height' => $size,
			'href'       => $permalink,
		) );
	}

	/**
	 * Run through the different met