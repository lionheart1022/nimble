headers of the response. Empty array if incorrect parameter given.
 */
function wp_remote_retrieve_headers( $response ) {
	if ( is_wp_error($response) || ! isset($response['headers']) || ! is_array($response['headers']))
		return array();

	return $response['headers'];
}

/**
 * Retrieve a single header by name from the raw response.
 *
 * @since 2.7.0
 *
 * @param array  $response
 * @param string $header Header name to retrieve value from.
 * @return string The header value. Empty string on if incorrect parameter given, or if the header doesn't exist.
 */
function wp_remote_retrieve_header( $response, $header ) {
	if ( is_wp_error($response) || ! isset($response['headers']) || ! is_array($response['headers']))
		return '';

	if ( array_key_exists($header, $response['headers']) )
		return $response['headers'][$header];

	return '';
}

/**
 * Retrieve only the response code from the raw response.
 *
 * Will return an empty array if incorrect parameter value is given.
 *
 * @since 2.7.0
 *
 * @param array $response HTTP response.
 * @return int|string The response code as an integer. Empty string on incorrect parameter given.
 */
function wp_remote_retrieve_response_code( $response ) {
	if ( is_wp_error($response) || ! isset($response['response']) || ! is_array($response['response']))
		return '';

	return $response['response']['code'];
}

/**
 * Retrieve only the response message from the raw response.
 *
 * Will return an empty array if incorrect parameter value is given.
 *
 * @since 2.7.0
 *
 * @param array $response HTTP response.
 * @return string The response message. Empty string on incorrect parameter given.
 */
function wp_remote_retrieve_response_message( $response ) {
	if ( is_wp_error($response) || ! isset($response['response']) || ! is_array($response['response']))
		return '';

	return $response['response']['message'];
}

/**
 * Retrieve only the body from the raw response.
 *
 * @since 2.7.0
 *
 * @param array $response HTTP response.
 * @return string The body of the response. Empty string if no body or incorrect parameter given.
 */
function wp_remote_retrieve_body( $response ) {
	if ( is_wp_error($response) || ! isset($response['body']) )
		return '';

	return $response['body'];
}

/**
 * Determines if there is an HTTP Transport that can process this request.
 *
 * @since 3.2.0
 *
 * @param array  $capabilities Array of capabilities to test or a wp_remote_request() $args array.
 * @param string $url          Optional. If given, will check if the URL requires SSL and adds
 *                             that requirement to the capabilities array.
 *
 * @return bool
 */
function wp_http_supports( $capabilities = array(), $url = null ) {
	$http = _wp_http_get_object();

	$capabilities = wp_parse_args( $capabilities );

	$count = count( $capabilities );

	// If we have a numeric $capabilities array, spoof a wp_remote_request() associative $args array
	if ( $count && count( array_filter( array_keys( $capabilities ), 'is_numeric' ) ) == $count ) {
		$capabilities = array_combine( array_values( $capabilities ), array_fill( 0, $count, true ) );
	}

	if ( $url && !isset( $capabilities['ssl'] ) ) {
		$scheme = parse_url( $url, PHP_URL_SCHEME );
		if ( 'https' == $scheme || 'ssl' == $scheme ) {
			$capabilities['ssl'] = true;
		}
	}

	return (bool) $http->_get_first_available_transport( $capabilities );
}

/**
 * Get the HTTP Origin of the current request.
 *
 * @since 3.4.0
 *
 * @return string URL of the origin. Empty string if no origin.
 */
function get_http_origin() {
	$origin = '';
	if ( ! empty ( $_SERVER[ 'HTTP_ORIGIN' ] ) )
		$origin = $_SERVER[ 'HTTP_ORIGIN' ];

	/**
	 * Change the origin of an HTTP request.
	 *
	 * @since 3.4.0
	 *
	 * @param string $origin The original origin for the request.
	 */
	return apply_filters( 'http_origin', $origin );
}

/**
 * Retrieve list of allowed HTTP origins.
 *
 * @since 3.4.0
 *
 * @return array Array of origin URLs.
 */
function get_allowed_http_origins() {
	$admin_origin = parse_url( admin_url() );
	$home_origin = parse_url( home_url() );

	// @todo preserve port?
	$allowed_origins = array_unique( array(
		'http://' . $admin_origin[ 'host' ],
		'https://' . $admin_origin[ 'host' ],
		'http://' . $home_origin[ 'host' ],
		'https://' . $home_origin[ 'host' ],
	) );

	/**
	 * Change the origin types allowed for HTTP requests.
	 *
	 * @since 3.4.0
	 *
	 * @param array $allowed_origins {
	 *     Default allowed HTTP origins.
	 *     @type string Non-secure URL for admin origin.
	 *     @type string Secure URL for admin origin.
	 *     @type string Non-secure URL for home origin.
	 *     @type string Secure URL for home origin.
	 * }
	 */
	return apply_filters( 'allowed_http_origins' , $allowed_origins );
}

/**
 * Determines if the HTTP origin is an authorized one.
 *
 * @since 3.4.0
 *
 * @param null|string $origin Origin URL. If not provided, the value of get_http_origin() is used.
 * @return string True if the origin is allowed. False otherwise.
 */
function is_allowed_http_origin( $origin = null ) {
	$origin_arg = $origin;

	if ( null === $origin )
		$origin = get_http_origin();

	if ( $origin && ! in_array( $origin, get_allowed_http_origins() ) )
		$origin = '';

	/**
	 * Change the allowed HTTP origin result.
	 *
	 * @since 3.4.0
	 *
	 * @param string $origin     Result of check for allowed origin.
	 * @param string $origin_arg Original origin string passed into is_allowed_http_origin function.
	 */
	return apply_filters( 'allowed_http_origin', $origin, $origin_arg );
}

/**
 * Send Access-Control-Allow-Origin and related headers if the current request
 * is from an allowed origin.
 *
 * If the request is an OPTIONS request, the script exits with either access
 * control headers sent, or a 403 response if the origin is not allowed. For
 * other request methods, you will receive a return value.
 *
 * @since 3.4.0
 *
 * @return string|false Returns the origin URL if headers are sent. Returns false
 *                      if headers are not sent.
 */
function send_origin_headers() {
	$origin = get_http_origin();

	if ( is_allowed_http_origin( $origin ) ) {
		@header( 'Access-Control-Allow-Origin: ' .  $origin );
		@header( 'Access-Control-Allow-Credentials: true' );
		if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] )
			exit;
		return $origin;
	}

	if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
		status_header( 403 );
		exit;
	}

	return false;
}

/**
 * Validate a URL for safe use in the HTTP API.
 *
 * @since 3.5.2
 *
 * @param string $url
 * @return false|string URL or false on failure.
 */
function wp_http_validate_url( $url ) {
	$original_url = $url;
	$url = wp_kses_bad_protocol( $url, array( 'http', 'https' ) );
	if ( ! $url || strtolower( $url ) !== strtolower( $original_url ) )
		return false;

	$parsed_url = @parse_url( $url );
	if ( ! $parsed_url || empty( $parsed_url['host'] ) )
		return false;

	if ( isset( $parsed_url['user'] ) || isset( $parsed_url['pass'] ) )
		return false;

	if ( false !== strpbrk( $parsed_url['host'], ':#?[]' ) )
		return false;

	$parsed_home = @parse_url( get_option( 'home' ) );

	$same_host = strtolower( $parsed_home['host'] ) === strtolower( $parsed_url['host'] );

	if ( ! $same_host ) {
		$host = trim( $parsed_url['host'], '.' );
		if ( preg_match( '#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $host ) ) {
			$ip = $host;
		} else {
			$ip = gethostbyname( $host );
			if ( $ip === $host ) // Error condition for gethostbyname()
				$ip = false;
		}
		if ( $ip ) {
			$parts = array_map( 'intval', explode( '.', $ip ) );
			if ( 127 === $parts[0] || 10 === $parts[0]
				|| ( 172 === $parts[0] && 16 <= $parts[1] && 31 >= $parts[1] )
				|| ( 192 === $parts[0] && 168 === $parts[1] )
			) {
				// If host appears local, reject unless specifically allowed.
				/**
				 * Check if HTTP request is external or not.
				 *
				 * Allows to change and allow external requests for the HTTP request.
				 *
				 * @since 3.6.0
				 *
				 * @param bool   false Whether HTTP request is external or not.
				 * @param string $host IP of the requested host.
				 * @param string $url  URL of the requested host.
				 */
				if ( ! apply_filters( 'http_request_host_is_external', false, $host, $url ) )
					return false;
			}
		}
	}

	if ( empty( $parsed_url['port'] ) )
		return $url;

	$port = $parsed_url['port'];
	if ( 80 === $port || 443 === $port || 8080 === $port )
		return $url;

	if ( $parsed_home && $same_host && isset( $parsed_home['port'] ) && $parsed_home['port'] === $port )
		return $url;

	return false;
}

/**
 * Whitelists allowed redirect hosts for safe HTTP requests as well.
 *
 * Attached to the http_request_host_is_external filter.
 *
 * @since 3.6.0
 *
 * @param bool   $is_external
 * @param string $host
 * @return bool
 */
function allowed_http_request_hosts( $is_external, $host ) {
	if ( ! $is_external && wp_validate_redirect( 'http://' . $host ) )
		$is_external = true;
	return $is_external;
}

/**
 * Whitelists any domain in a multisite installation for safe HTTP requests.
 *
 * Attached to the http_request_host_is_external filter.
 *
 * @since 3.6.0
 *
 * @global wpdb $wpdb
 * @staticvar array $queried
 *
 * @param bool   $is_external
 * @param string $host
 * @return bool
 */
function ms_allowed_http_request_hosts( $is_external, $host ) {
	global $wpdb;
	static $queried = array();
	if ( $is_external )
		return $is_external;
	if ( $host === get_current_site()->domain )
		return true;
	if ( isset( $queried[ $host ] ) )
		return $queried[ $host ];
	$queried[ $host ] = (bool) $wpdb->get_var( $wpdb->prepare( "SELECT domain FROM $wpdb->blogs WHERE domain = %s LIMIT 1", $host ) );
	return $queried[ $host ];
}
                                                    <?php
/**
 * WordPress Feed API
 *
 * Many of the functions used in here belong in The Loop, or The Loop for the
 * Feeds.
 *
 * @package WordPress
 * @subpackage Feed
 */

/**
 * RSS container for the bloginfo function.
 *
 * You can retrieve anything that you can using the get_bloginfo() function.
 * Everything will be stripped of tags and characters converted, when the values
 * are retrieved for use in the feeds.
 *
 * @since 1.5.1
 * @see get_bloginfo() For the list of possible values to display.
 *
 * @param string $show See get_bloginfo() for possible values.
 * @return string
 */
function get_bloginfo_rss($show = '') {
	$info = strip_tags(get_bloginfo($show));
	/**
	 * Filter the bloginfo for use in RSS feeds.
	 *
	 * @since 2.2.0
	 *
	 * @see convert_chars()
	 * @see get_bloginfo()
	 *
	 * @param string $info Converted string value of the blog information.
	 * @param string $show The type of blog information to retrieve.
	 */
	return apply_filters( 'get_bloginfo_rss', convert_chars( $info ), $show );
}

/**
 * Display RSS container for the bloginfo function.
 *
 * You can retrieve anything that you can using the get_bloginfo() function.
 * Everything will be stripped of tags and characters converted, when the values
 * are retrieved for use in the feeds.
 *
 * @since 0.71
 * @see get_bloginfo() For the list of possible values to display.
 *
 * @param string $show See get_bloginfo() for possible values.
 */
function bloginfo_rss($show = '') {
	/**
	 * Filter the bloginfo for display in RSS feeds.
	 *
	 * @since 2.1.0
	 *
	 * @see get_bloginfo()
	 *
	 * @param string $rss_container RSS container for the blog information.
	 * @param string $show          The type of blog information to retrieve.
	 */
	echo apply_filters( 'bloginfo_rss', get_bloginfo_rss( $show ), $show );
}

/**
 * Retrieve the default feed.
 *
 * The default feed is 'rss2', unless a plugin changes it through the
 * 'default_feed' filter.
 *
 * @since 2.5.0
 *
 * @return string Default feed, or for example 'rss2', 'atom', etc.
 */
function get_default_feed() {
	/**
	 * Filter the default feed type.
	 *
	 * @since 2.5.0
	 *
	 * @param string $feed_type Type of default feed. Possible values include 'rss2', 'atom'.
	 *                          Default 'rss2'.
	 */
	$default_feed = apply_filters( 'default_feed', 'rss2' );
	return 'rss' == $default_feed ? 'rss2' : $default_feed;
}

/**
 * Retrieve the blog title for the feed title.
 *
 * @since 2.2.0
 *
 * @param string $sep Optional. How to separate the title. See wp_title() for more info.
 * @return string Error message on failure or blog title on success.
 */
function get_wp_title_rss( $sep = '&#187;' ) {
	$title = wp_title( $sep, false );

	if ( is_wp_error( $title ) ) {
		return $title->get_error_message();
	}

	if ( $title && $sep && ' ' !== substr( $title, 0, 1 ) ) {
		$title = " $sep " . $title;
	}

	/**
	 * Filter the blog title for use as the feed title.
	 *
	 * @since 2.2.0
	 *
	 * @param string $title The current blog title.
	 * @param string $sep   Separator used by wp_title().
	 */
	$title = apply_filters( 'get_wp_title_rss', $title, $sep );
	return $title;
}

/**
 * Display the blog title for display of the feed title.
 *
 * @since 2.2.0
 * @see wp_title() $sep parameter usage.
 *
 * @param string $sep Optional.
 */
function wp_title_rss( $sep = '&#187;' ) {
	/**
	 * Filter the blog title for display of the feed title.
	 *
	 * @since 2.2.0
	 *
	 * @see get_wp_title_rss()
	 *
	 * @param string $wp_title The current blog title.
	 * @param string $sep      Separator used by wp_title().
	 */
	echo apply_filters( 'wp_title_rss', get_wp_title_rss( $sep ), $sep );
}

/**
 * Retrieve the current post title for the feed.
 *
 * @since 2.0.0
 *
 * @return string Current post title.
 */
function get_the_title_rss() {
	$title = get_the_title();
	/**
	 * Filter the post title for use in a feed.
	 *
	 * @since 1.2.0
	 *
	 * @param string $title The current post title.
	 */
	$title = apply_filters( 'the_title_rss', $title );
	return $title;
}

/**
 * Display the post title in the feed.
 *
 * @since 0.71
 */
function the_title_rss() {
	echo get_the_title_rss();
}

/**
 * Retrieve the post content for feeds.
 *
 * @since 2.9.0
 * @see get_the_content()
 *
 * @param string $feed_type The type of feed. rss2 | atom | rss | rdf
 * @return string The filtered content.
 */
function get_the_content_feed($feed_type = null) {
	if ( !$feed_type )
		$feed_type = get_default_feed();

	/** This filter is documented in wp-includes/post-template.php */
	$content = apply_filters( 'the_content', get_the_content() );
	$content = str_replace(']]>', ']]&gt;', $content);
	/**
	 * Filter the post content for use in feeds.
	 *
	 * @since 2.9.0
	 *
	 * @param string $content   The current post content.
	 * @param string $feed_type Type of feed. Possible values include 'rss2', 'atom'.
	 *                          Default 'rss2'.
	 */
	return apply_filters( 'the_content_feed', $content, $feed_type );
}

/**
 * Display the post content for feeds.
 *
 * @since 2.9.0
 *
 * @param string $feed_type The type of feed. rss2 | atom | rss | rdf
 */
function the_content_feed($feed_type = null) {
	echo get_the_content_feed($feed_type);
}

/**
 * Display the post excerpt for the feed.
 *
 * @since 0.71
 */
function the_excerpt_rss() {
	$output = get_the_excerpt();
	/**
	 * Filter the post excerpt for a feed.
	 *
	 * @since 1.2.0
	 *
	 * @param string $output The current post excerpt.
	 */
	echo apply_filters( 'the_excerpt_rss', $output );
}

/**
 * Display the permalink to the post for use in feeds.
 *
 * @since 2.3.0
 */
function the_permalink_rss() {
	/**
	 * Filter the permalink to the post for use in feeds.
	 *
	 * @since 2.3.0
	 *
	 * @param string $post_permalink The current post permalink.
	 */
	echo esc_url( apply_filters( 'the_permalink_rss', get_permalink() ) );
}

/**
 * Outputs the link to the comments for the current post in an xml safe way
 *
 * @since 3.0.0
 * @return none
 */
function comments_link_feed() {
	/**
	 * Filter the comments permalink for the current post.
	 *
	 * @since 3.6.0
	 *
	 * @param string $comment_permalink The current comment permalink with
	 *                                  '#comments' appended.
	 */
	echo esc_url( apply_filters( 'comments_link_feed', get_comments_link() ) );
}

/**
 * Display the feed GUID for the current comment.
 *
 * @since 2.5.0
 *
 * @param int|object $comment_id Optional comment object or id. Defaults to global comment object.
 */
function comment_guid($comment_id = null) {
	echo esc_url( get_comment_guid($comment_id) );
}

/**
 * Retrieve the feed GUID for the current comment.
 *
 * @since 2.5.0
 *
 * @param int|object $comment_id Optional comment object or id. Defaults to global comment object.
 * @return false|string false on failure or guid for comment on success.
 */
function get_comment_guid($comment_id = null) {
	$comment = get_comment($comment_id);

	if ( !is_object($comment) )
		return false;

	return get_the_guid($comment->comment_post_ID) . '#comment-' . $comment->comment_ID;
}

/**
 * Display the link to the comments.
 *
 * @since 1.5.0
 */
function comment_link() {
	/**
	 * Filter the current comment's permalink.
	 *
	 * @since 3.6.0
	 *
	 * @see get_comment_link()
	 *
	 * @param string $comment_permalink The current comment permalink.
	 */
	echo esc_url( apply_filters( 'comment_link', get_comment_link() ) );
}

/**
 * Retrieve the current comment author for use in the feeds.
 *
 * @since 2.0.0
 *
 * @return string Comment Author
 */
function get_comment_author_rss() {
	/**
	 * Filter the current comment author for use in a feed.
	 *
	 * @since 1.5.0
	 *
	 * @see get_comment_author()
	 *
	 * @param string $comment_author The current comment author.
	 */
	return apply_filters( 'comment_author_rss', get_comment_author() );
}

/**
 * Display the current comment author in the feed.
 *
 * @since 1.0.0
 */
function comment_author_rss() {
	echo get_comment_author_rss();
}

/**
 * Display the current comment content for use in the feeds.
 *
 * @since 1.0.0
 */
function comment_text_rss() {
	$comment_text = get_comment_text();
	/**
	 * Filter the current comment content for use in a feed.
	 *
	 * @since 1.5.0
	 *
	 * @param string $comment_text The content of the current comment.
	 */
	$comment_text = apply_filters( 'comment_text_rss', $comment_text );
	echo $comment_text;
}

/**
 * Retrieve all of the post categories, formatted for use in feeds.
 *
 * All of the categories for the current post in the feed loop, will be
 * retrieved and have feed markup added, so that they can easily be added to the
 * RSS2, Atom, or RSS1 and RSS0.91 RDF feeds.
 *
 * @since 2.1.0
 *
 * @param string $type Optional, default is the type returned by get_default_feed().
 * @return string All of the post categories for displaying in the feed.
 */
function get_the_category_rss($type = null) {
	if ( empty($type) )
		$type = get_default_feed();
	$categories = get_the_category();
	$tags = get_the_tags();
	$the_list = '';
	$cat_names = array();

	$filter = 'rss';
	if ( 'atom' == $type )
		$filter = 'raw';

	if ( !empty($categories) ) foreach ( (array) $categories as $category ) {
		$cat_names[] = sanitize_term_field('name', $category->name, $category->term_id, 'category', $filter);
	}

	if ( !empty($tags) ) foreach ( (array) $tags as $tag ) {
		$cat_names[] = sanitize_term_field('name', $tag->name, $tag->term_id, 'post_tag', $filter);
	}

	$cat_names = array_unique($cat_names);

	foreach ( $cat_names as $cat_name ) {
		if ( 'rdf' == $type )
			$the_list .= "\t\t<dc:subject><![CDATA[$cat_name]]></dc:subject>\n";
		elseif ( 'atom' == $type )
			$the_list .= sprintf( '<category scheme="%1$s" term="%2$s" />', esc_attr( get_bloginfo_rss( 'url' ) ), esc_attr( $cat_name ) );
		else
			$the_list .= "\t\t<category><![CDATA[" . @html_entity_decode( $cat_name, ENT_COMPAT, get_option('blog_charset') ) . "]]></category>\n";
	}

	/**
	 * Filter all of the post categories for display in a feed.
	 *
	 * @since 1.2.0
	 *
	 * @param string $the_list All of the RSS post categories.
	 * @param string $type     Type of feed. Possible values include 'rss2', 'atom'.
	 *                         Default 'rss2'.
	 */
	return apply_filters( 'the_category_rss', $the_list, $type );
}

/**
 * Display the post categories in the feed.
 *
 * @since 0.71
 * @see get_the_category_rss() For better explanation.
 *
 * @param string $type Optional, default is the type returned by get_default_feed().
 */
function the_category_rss($type = null) {
	echo get_the_category_rss($type);
}

/**
 * Display the HTML type based on the blog setting.
 *
 * The two possible values are either 'xhtml' or 'html'.
 *
 * @since 2.2.0
 */
function html_type_rss() {
	$type = get_bloginfo('html_type');
	if (strpos($type, 'xhtml') !== false)
		$type = 'xhtml';
	else
		$type = 'html';
	echo $type;
}

/**
 * Display the rss enclosure for the current post.
 *
 * Uses the global $post to check whether the post requires a password and if
 * the user has the password for the post. If not then it will return before
 * displaying.
 *
 * Also uses the function get_post_custom() to get the post's 'enclosure'
 * metadata field and parses the value to display the enclosure(s). The
 * enclosure(s) consist of enclosure HTML tag(s) with a URI and other
 * attributes.
 *
 * @since 1.5.0
 */
function rss_enclosure() {
	if ( post_password_required() )
		return;

	foreach ( (array) get_post_custom() as $key => $val) {
		if ($key == 'enclosure') {
			foreach ( (array) $val as $enc ) {
				$enclosure = explode("\n", $enc);

				// only get the first element, e.g. audio/mpeg from 'audio/mpeg mpga mp2 mp3'
				$t = preg_split('/[ \t]/', trim($enclosure[2]) );
				$type = $t[0];

				/**
				 * Filter the RSS enclosure HTML link tag for the current post.
				 *
				 * @since 2.2.0
				 *
				 * @param string $html_link_tag The HTML link tag with a URI and other attributes.
				 */
				echo apply_filters( 'rss_enclosure', '<enclosure url="' . trim( htmlspecialchars( $enclosure[0] ) ) . '" length="' . trim( $enclosure[1] ) . '" type="' . $type . '" />' . "\n" );
			}
		}
	}
}

/**
 * Display the atom enclosure for the current post.
 *
 * Uses the global $post to check whether the post requires a password and if
 * the user has the password for the post. If not then it will return before
 * displaying.
 *
 * Also uses the function get_post_custom() to get the post's 'enclosure'
 * metadata field and parses the value to display the enclosure(s). The
 * enclosure(s) consist of link HTML tag(s) with a URI and other attributes.
 *
 * @since 2.2.0
 */
function atom_enclosure() {
	if ( post_password_required() )
		return;

	foreach ( (array) get_post_custom() as $key => $val ) {
		if ($key == 'enclosure') {
			foreach ( (array) $val as $enc ) {
				$enclosure = explode("\n", $enc);
				/**
				 * Filter the atom enclosure HTML link tag for the current post.
				 *
				 * @since 2.2.0
				 *
				 * @param string $html_link_tag The HTML link tag with a URI and other attributes.
				 */
				echo apply_filters( 'atom_enclosure', '<link href="' . trim( htmlspecialchars( $enclosure[0] ) ) . '" rel="enclosure" length="' . trim( $enclosure[1] ) . '" type="' . trim( $enclosure[2] ) . '" />' . "\n" );
			}
		}
	}
}

/**
 * Determine the type of a string of data with the data formatted.
 *
 * Tell whether the type is text, html, or xhtml, per RFC 4287 section 3.1.
 *
 * In the case of WordPress, text is defined as containing no markup,
 * xhtml is defined as "well formed", and html as tag soup (i.e., the rest).
 *
 * Container div tags are added to xhtml values, per section 3.1.1.3.
 *
 * @link http://www.atomenabled.org/developers/syndication/atom-format-spec.php#rfc.section.3.1
 *
 * @since 2.5.0
 *
 * @param string $data Input string
 * @return array array(type, value)
 */
function prep_atom_text_construct($data) {
	if (strpos($data, '<') === false && strpos($data, '&') === false) {
		return array('text', $data);
	}

	$parser = xml_parser_create();
	xml_parse($parser, '<div>' . $data . '</div>', true);
	$code = xml_get_error_code($parser);
	xml_parser_free($parser);

	if (!$code) {
		if (strpos($data, '<') === false) {
			return array('text', $data);
		} else {
			$data = "<div xmlns='http://www.w3.org/1999/xhtml'>$data</div>";
			return array('xhtml', $data);
		}
	}

	if (strpos($data, ']]>') === false) {
		return array('html', "<![CDATA[$data]]>");
	} else {
		return array('html', htmlspecialchars($data));
	}
}

/**
 * Displays Site Icon in atom feeds.
 *
 * @since 4.3.0
 *
 * @see get_site_icon_url()
 */
function atom_site_icon() {
	$url = get_site_icon_url( 32 );
	if ( $url ) {
		echo "<icon>$url</icon>\n";
	}
}

/**
 * Displays Site Icon in RSS2.
 *
 * @since 4.3.0
 */
function rss2_site_icon() {
	$rss_title = get_wp_title_rss();
	if ( empty( $rss_title ) ) {
		$rss_title = get_bloginfo_rss( 'name' );
	}

	$url = get_site_icon_url( 32 );
	if ( $url ) {
		echo '
<image>
	<url>' . convert_chars( $url ) . '</url>
	<title>' . $rss_title . '</title>
	<link>' . get_bloginfo_rss( 'url' ) . '</link>
	<width>32</width>
	<height>32</height>
</image> ' . "\n";
	}
}

/**
 * Display the link for the currently displayed feed in a XSS safe way.
 *
 * Generate a correct link for the atom:self element.
 *
 * @since 2.5.0
 */
function self_link() {
	$host = @parse_url(home_url());
	/**
	 * Filter the current feed URL.
	 *
	 * @since 3.6.0
	 *
	 * @see set_url_scheme()
	 * @see wp_unslash()
	 *
	 * @param string $feed_link The link for the feed with set URL scheme.
	 */
	echo esc_url( apply_filters( 'self_link', set_url_scheme( 'http://' . $host['host'] . wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
}

/**
 * Return the content type for specified feed type.
 *
 * @since 2.8.0
 */
function feed_content_type( $type = '' ) {
	if ( empty($type) )
		$type = get_default_feed();

	$types = array(
		'rss'  => 'application/rss+xml',
		'rss2' => 'application/rss+xml',
		'rss-http'  => 'text/xml',
		'atom' => 'application/atom+xml',
		'rdf'  => 'application/rdf+xml'
	);

	$content_type = ( !empty($types[$type]) ) ? $types[$type] : 'application/octet-stream';

	/**
	 * Filter the content type for a specific feed type.
	 *
	 * @since 2.8.0
	 *
	 * @param string $content_type Content type indicating the type of data that a feed contains.
	 * @param string $type         Type of feed. Possible values include 'rss2', 'atom'.
	 *                             Default 'rss2'.
	 */
	return apply_filters( 'feed_content_type', $content_type, $type );
}

/**
 * Build SimplePie object based on RSS or Atom feed from URL.
 *
 * @since 2.8.0
 *
 * @param mixed $url URL of feed to retrieve. If an array of URLs, the feeds are merged
 * using SimplePie's multifeed feature.
 * See also {@link ​http://simplepie.org/wiki/faq/typical_multifeed_gotchas}
 *
 * @return WP_Error|SimplePie WP_Error object on failure or SimplePie object on success
 */
function fetch_feed( $url ) {
	require_once( ABSPATH . WPINC . '/class-feed.php' );

	$feed = new SimplePie();

	$feed->set_sanitize_class( 'WP_SimplePie_Sanitize_KSES' );
	// We must manually overwrite $feed->sanitize because SimplePie's
	// constructor sets it before we have a chance to set the sanitization class
	$feed->sanitize = new WP_SimplePie_Sanitize_KSES();

	$feed->set_cache_class( 'WP_Feed_Cache' );
	$feed->set_file_class( 'WP_SimplePie_File' );

	$feed->set_feed_url( $url );
	/** This filter is documented in wp-includes/class-feed.php */
	$feed->set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', 12 * HOUR_IN_SECONDS, $url ) );
	/**
	 * Fires just before processing the SimplePie feed object.
	 *
	 * @since 3.0.0
	 *
	 * @param object &$feed SimplePie feed object, passed by reference.
	 * @param mixed  $url   URL of feed to retrieve. If an array of URLs, the feeds are merged.
	 */
	do_action_ref_array( 'wp_feed_options', array( &$feed, $url ) );
	$feed->init();
	$feed->set_output_encoding( get_option( 'blog_charset' ) );
	$feed->handle_content_type();

	if ( $feed->error() )
		return new WP_Error( 'simplepie-error', $feed->error() );

	return $feed;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/**
 * Sets up the default filters and actions for most
 * of the WordPress hooks.
 *
 * If you need to remove a default hook, this file will
 * give you the priority for which to use to remove the
 * hook.
 *
 * Not all of the default hooks are found in default-filters.php
 *
 * @package WordPress
 */

// Strip, trim, kses, special chars for string saves
foreach ( array( 'pre_term_name', 'pre_comment_author_name', 'pre_link_name', 'pre_link_target', 'pre_link_rel', 'pre_user_display_name', 'pre_user_first_name', 'pre_user_last_name', 'pre_user_nickname' ) as $filter ) {
	add_filter( $filter, 'sanitize_text_field'  );
	add_filter( $filter, 'wp_filter_kses'       );
	add_filter( $filter, '_wp_specialchars', 30 );
}

// Strip, kses, special chars for string display
foreach ( array( 'term_name', 'comment_author_name', 'link_name', 'link_target', 'link_rel', 'user_display_name', 'user_first_name', 'user_last_name', 'user_nickname' ) as $filter ) {
	if ( is_admin() ) {
		// These are expensive. Run only on admin pages for defense in depth.
		add_filter( $filter, 'sanitize_text_field'  );
		add_filter( $filter, 'wp_kses_data'       );
	}
	add_filter( $filter, '_wp_specialchars', 30 );
}

// Kses only for textarea saves
foreach ( array( 'pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description' ) as $filter ) {
	add_filter( $filter, 'wp_filter_kses' );
}

// Kses only for textarea admin displays
if ( is_admin() ) {
	foreach ( array( 'term_description', 'link_description', 'link_notes', 'user_description' ) as $filter ) {
		add_filter( $filter, 'wp_kses_data' );
	}
	add_filter( 'comment_text', 'wp_kses_post' );
}

// Email saves
foreach ( array( 'pre_comment_author_email', 'pre_user_email' ) as $filter ) {
	add_filter( $filter, 'trim'           );
	add_filter( $filter, 'sanitize_email' );
	add_filter( $filter, 'wp_filter_kses' );
}

// Email admin display
foreach ( array( 'comment_author_email', 'user_email' ) as $filter ) {
	add_filter( $filter, 'sanitize_email' );
	if ( is_admin() )
		add_filter( $filter, 'wp_kses_data' );
}

// Save URL
foreach ( array( 'pre_comment_author_url', 'pre_user_url', 'pre_link_url', 'pre_link_image',
	'pre_link_rss', 'pre_post_guid' ) as $filter ) {
	add_filter( $filter, 'wp_strip_all_tags' );
	add_filter( $filter, 'esc_url_raw'       );
	add_filter( $filter, 'wp_filter_kses'    );
}

// Display URL
foreach ( array( 'user_url', 'link_url', 'link_image', 'link_rss', 'comment_url', 'post_guid' ) as $filter ) {
	if ( is_admin() )
		add_filter( $filter, 'wp_strip_all_tags' );
	add_filter( $filter, 'esc_url'           );
	if ( is_admin() )
		add_filter( $filter, 'wp_kses_data'    );
}

// Slugs
add_filter( 'pre_term_slug', 'sanitize_title' );

// Keys
foreach ( array( 'pre_post_type', 'pre_post_status', 'pre_post_comment_status', 'pre_post_ping_status' ) as $filter ) {
	add_filter( $filter, 'sanitize_key' );
}

// Mime types
add_filter( 'pre_post_mime_type', 'sanitize_mime_type' );
add_filter( 'post_mime_type', 'sanitize_mime_type' );

// Places to balance tags on input
foreach ( array( 'content_save_pre', 'excerpt_save_pre', 'comment_save_pre', 'pre_comment_content' ) as $filter ) {
	add_filter( $filter, 'convert_invalid_entities' );
	add_filter( $filter, 'balanceTags', 50 );
}

// Format strings for display.
foreach ( array( 'comment_author', 'term_name', 'link_name', 'link_description', 'link_notes', 'bloginfo', 'wp_title', 'widget_title' ) as $filter ) {
	add_filter( $filter, 'wptexturize'   );
	add_filter( $filter, 'convert_chars' );
	add_filter( $filter, 'esc_html'      );
}

// Format WordPress
foreach ( array( 'the_content', 'the_title', 'wp_title' ) as $filter )
	add_filter( $filter, 'capital_P_dangit', 11 );
add_filter( 'comment_text', 'capital_P_dangit', 31 );

// Format titles
foreach ( array( 'single_post_title', 'single_cat_title', 'single_tag_title', 'single_month_title', 'nav_menu_attr_title', 'nav_menu_description' ) as $filter ) {
	add_filter( $filter, 'wptexturize' );
	add_filter( $filter, 'strip_tags'  );
}

// Format text area for display.
foreach ( array( 'term_description' ) as $filter ) {
	add_filter( $filter, 'wptexturize'      );
	add_filter( $filter, 'convert_chars'    );
	add_filter( $filter, 'wpautop'          );
	add_filter( $filter, 'shortcode_unautop');
}

// Format for RSS
add_filter( 'term_name_rss', 'convert_chars' );

// Pre save hierarchy
add_filter( 'wp_insert_post_parent', 'wp_check_post_hierarchy_for_loops', 10, 2 );
add_filter( 'wp_update_term_parent', 'wp_check_term_hierarchy_for_loops', 10, 3 );

// Display filters
add_filter( 'the_title', 'wptexturize'   );
add_filter( 'the_title', 'convert_chars' );
add_filter( 'the_title', 'trim'          );

add_filter( 'the_content', 'wptexturize'        );
add_filter( 'the_content', 'convert_smilies'    );
add_filter( 'the_content', 'convert_chars'      );
add_filter( 'the_content', 'wpautop'            );
add_filter( 'the_content', 'shortcode_unautop'  );
add_filter( 'the_content', 'prepend_attachment' );

add_filter( 'the_excerpt',     'wptexturize'      );
add_filter( 'the_excerpt',     'convert_smilies'  );
add_filter( 'the_excerpt',     'convert_chars'    );
add_filter( 'the_excerpt',     'wpautop'          );
add_filter( 'the_excerpt',     'shortcode_unautop');
add_filter( 'get_the_excerpt', 'wp_trim_excerpt'  );

add_filter( 'comment_text', 'wptexturize'            );
add_filter( 'comment_text', 'convert_chars'          );
add_filter( 'comment_text', 'make_clickable',      9 );
add_filter( 'comment_text', 'force_balance_tags', 25 );
add_filter( 'comment_text', 'convert_smilies',    20 );
add_filter( 'comment_text', 'wpautop',            30 );

add_filter( 'comment_excerpt', 'convert_chars' );

add_filter( 'list_cats',         'wptexturize' );

add_filter( 'wp_sprintf', 'wp_sprintf_l', 10, 2 );

// RSS filters
add_filter( 'the_title_rss',      'strip_tags'                    );
add_filter( 'the_title_rss',      'ent2ncr',                    8 );
add_filter( 'the_title_rss',      'esc_html'                      );
add_filter( 'the_content_rss',    'ent2ncr',                    8 );
add_filter( 'the_content_feed',   'wp_staticize_emoji'            );
add_filter( 'the_excerpt_rss',    'convert_chars'                 );
add_filter( 'the_excerpt_rss',    'ent2ncr',                    8 );
add_filter( 'comment_author_rss', 'ent2ncr',                    8 );
add_filter( 'comment_text_rss',   'ent2ncr',                    8 );
add_filter( 'comment_text_rss',   'esc_html'                      );
add_filter( 'comment_text_rss',   'wp_staticize_emoji'            );
add_filter( 'bloginfo_rss',       'ent2ncr',                    8 );
add_filter( 'the_author',         'ent2ncr',                    8 );
add_filter( 'the_guid',           'esc_url'                       );

// Email filters
add_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

// Misc filters
add_filter( 'option_ping_sites',        'privacy_ping_filter'                 );
add_filter( 'option_blog_charset',      '_wp_specialchars'                    ); // IMPORTANT: This must not be wp_specialchars() or esc_html() or it'll cause an infinite loop
add_filter( 'option_blog_charset',      '_canonical_charset'                  );
add_filter( 'option_home',              '_config_wp_home'                     );
add_filter( 'option_siteurl',           '_config_wp_siteurl'                  );
add_filter( 'tiny_mce_before_init',     '_mce_set_direction'                  );
add_filter( 'teeny_mce_before_init',    '_mce_set_direction'                  );
add_filter( 'pre_kses',                 'wp_pre_kses_less_than'               );
add_filter( 'sanitize_title',           'sanitize_title_with_dashes',   10, 3 );
add_action( 'check_comment_flood',      'check_comment_flood_db',       10, 3 );
add_filter( 'comment_flood_filter',     'wp_throttle_comment_flood',    10, 3 );
add_filter( 'pre_comment_content',      'wp_rel_nofollow',              15    );
add_filter( 'comment_email',            'antispambot'                         );
add_filter( 'option_tag_base',          '_wp_filter_taxonomy_base'            );
add_filter( 'option_category_base',     '_wp_filter_taxonomy_base'            );
add_filter( 'the_posts',                '_close_comments_for_old_posts', 10, 2);
add_filter( 'comments_open',            '_close_comments_for_old_post', 10, 2 );
add_filter( 'pings_open',               '_close_comments_for_old_post', 10, 2 );
add_filter( 'editable_slug',            'urldecode'                           );
add_filter( 'editable_slug',            'esc_textarea'                        );
add_filter( 'nav_menu_meta_box_object', '_wp_nav_menu_meta_box_object'        );
add_filter( 'pingback_ping_source_uri', 'pingback_ping_source_uri'            );
add_filter( 'xmlrpc_pingback_error',    'xmlrpc_pingback_error'               );
add_filter( 'title_save_pre',           'trim'                                );

add_filter( 'http_request_host_is_external', 'allowed_http_request_hosts', 10, 2 );

// Actions
add_action( 'wp_head',             '_wp_render_title_tag',            1     );
add_action( 'wp_head',             'wp_enqueue_scripts',              1     );
add_action( 'wp_head',             'feed_links',                      2     );
add_action( 'wp_head',             'feed_links_extra',                3     );
add_action( 'wp_head',             'rsd_link'                               );
add_action( 'wp_head',             'wlwmanifest_link'                       );
add_action( 'wp_head',             'adjacent_posts_rel_link_wp_head', 10, 0 );
add_action( 'wp_head',             'locale_stylesheet'                      );
add_action( 'publish_future_post', 'check_and_publish_future_post',   10, 1 );
add_action( 'wp_head',             'noindex',                          1    );
add_action( 'wp_head',             'print_emoji_detection_script',     7    );
add_action( 'wp_head',             'wp_print_styles',                  8    );
add_action( 'wp_head',             'wp_print_head_scripts',            9    );
add_action( 'wp_head',             'wp_generator'                           );
add_action( 'wp_head',             'rel_canonical'                          );
add_action( 'wp_head',             'wp_shortlink_wp_head',            10, 0 );
add_action( 'wp_head',             'wp_site_icon',                    99    );
add_action( 'wp_footer',           'wp_print_footer_scripts',         20    );
add_action( 'template_redirect',   'wp_shortlink_header',             11, 0 );
add_action( 'wp_print_footer_scripts', '_wp_footer_scripts'                 );
add_action( 'init',                'check_theme_switched',            99    );
add_action( 'after_switch_theme',  '_wp_sidebars_changed'                   );
add_action( 'wp_print_styles',     'print_emoji_styles'                     );

if ( isset( $_GET['replytocom'] ) )
    add_action( 'wp_head', 'wp_no_robots' );

// Login actions
add_action( 'login_head',          'wp_print_head_scripts',         9     );
add_action( 'login_footer',        'wp_print_footer_scripts',       20    );
add_action( 'login_init',          'send_frame_options_header',     10, 0 );

// Feed Generator Tags
foreach ( array( 'rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head' ) as $action ) {
	add_action( $action, 'the_generator' );
}

// Feed Site Icon
add_action( 'atom_head', 'atom_site_icon' );
add_action( 'rss2_head', 'rss2_site_icon' );


// WP Cron
if ( !defined( 'DOING_CRON' ) )
	add_action( 'init', 'wp_cron' );

// 2 Actions 2 Furious
add_action( 'do_feed_rdf',                'do_feed_rdf',                             10, 1 );
add_action( 'do_feed_rss',                'do_feed_rss',                             10, 1 );
add_action( 'do_feed_rss2',               'do_feed_rss2',                            10, 1 );
add_action( 'do_feed_atom',               'do_feed_atom',                            10, 1 );
add_action( 'do_pings',                   'do_all_pings',                            10, 1 );
add_action( 'do_robots',                  'do_robots'                                      );
add_action( 'set_comment_cookies',        'wp_set_comment_cookies',                  10, 2 );
add_action( 'sanitize_comment_cookies',   'sanitize_comment_cookies'                       );
add_action( 'admin_print_scripts',        'print_emoji_detection_script'                   );
add_action( 'admin_print_scripts',        'print_head_scripts',                      20    );
add_action( 'admin_print_footer_scripts', '_wp_footer_scripts'                             );
add_action( 'admin_print_styles',         'print_emoji_styles'                             );
add_action( 'admin_print_styles',         'print_admin_styles',                      20    );
add_action( 'init',                       'smilies_init',                             5    );
add_action( 'plugins_loaded',             'wp_maybe_load_widgets',                    0    );
add_action( 'plugins_loaded',             'wp_maybe_load_embeds',                     0    );
add_action( 'shutdown',                   'wp_ob_end_flush_all',                      1    );
// Create a revision whenever a post is updated.
add_action( 'post_updated',               'wp_save_post_revision',                   10, 1 );
add_action( 'publish_post',               '_publish_post_hook',                       5, 1 );
add_action( 'transition_post_status',     '_transition_post_status',                  5, 3 );
add_action( 'transition_post_status',     '_update_term_count_on_transition_post_status', 10, 3 );
add_action( 'comment_form',               'wp_comment_form_unfiltered_html_nonce'          );
add_action( 'wp_scheduled_delete',        'wp_scheduled_delete'                            );
add_action( 'wp_scheduled_auto_draft_delete', 'wp_delete_auto_drafts'                      );
add_action( 'admin_init',                 'send_frame_options_header',               10, 0 );
add_action( 'importer_scheduled_cleanup', 'wp_delete_attachment'                           );
add_action( 'upgrader_scheduled_cleanup', 'wp_delete_attachment'                           );
add_action( 'welcome_panel',              'wp_welcome_panel'                               );

// Navigation menu actions
add_action( 'delete_post',                '_wp_delete_post_menu_item'         );
add_action( 'delete_term',                '_wp_delete_tax_menu_item',   10, 3 );
add_action( 'transition_post_status',     '_wp_auto_add_pages_to_menu', 10, 3 );

// Post Thumbnail CSS class filtering
add_action( 'begin_fetch_post_thumbnail_html', '_wp_post_thumbnail_class_filter_add'    );
add_action( 'end_fetch_post_thumbnail_html',   '_wp_post_thumbnail_class_filter_remove' );

// Redirect Old Slugs
add_action( 'template_redirect', 'wp_old_slug_redirect'              );
add_action( 'post_updated',      'wp_check_for_changed_slugs', 12, 3 );

// Nonce check for Post Previews
add_action( 'init', '_show_post_preview' );

// Output JS to reset window.name for previews
add_action( 'wp_head', 'wp_post_preview_js', 1 );

// Timezone
add_filter( 'pre_option_gmt_offset','wp_timezone_override_offset' );

// Admin Color Schemes
add_action( 'admin_init', 'register_admin_color_schemes', 1);
add_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

// If the upgrade hasn't run yet, assume link manager is used.
add_filter( 'default_option_link_manager_enabled', '__return_true' );

// This option no longer exists; tell plugins we always support auto-embedding.
add_filter( 'default_option_embed_autourls', '__return_true' );

// Default settings for heartbeat
add_filter( 'heartbeat_settings', 'wp_heartbeat_settings' );

// Check if the user is logged out
add_action( 'admin_enqueue_scripts', 'wp_auth_check_load' );
add_filter( 'heartbeat_send',        'wp_auth_check' );
add_filter( 'heartbeat_nopriv_send', 'wp_auth_check' );

// Default authentication filters
add_filter( 'authenticate', 'wp_authenticate_username_password',  20, 3 );
add_filter( 'authenticate', 'wp_authenticate_spam_check',         99    );
add_filter( 'determine_current_user', 'wp_validate_auth_cookie'          );
add_filter( 'determine_current_user', 'wp_validate_logged_in_cookie', 20 );

// Split term updates.
add_action( 'admin_init',        '_wp_check_for_scheduled_split_terms' );
add_action( 'split_shared_term', '_wp_check_split_default_terms',  10, 4 );
add_action( 'split_shared_term', '_wp_check_split_terms_in_menus', 10, 4 );
add_action( 'split_shared_term', '_wp_check_split_nav_menu_terms', 10, 4 );
add_action( 'wp_split_shared_term_batch', '_wp_batch_split_terms' );

/**
 * Filters formerly mixed into wp-includes
 */
// Theme
add_action( 'wp_loaded', '_custom_header_background_just_in_time' );
add_action( 'plugins_loaded', '_wp_customize_include' );
add_action( 'admin_enqueue_scripts', '_wp_customize_loader_settings' );
add_action( 'delete_attachment', '_delete_attachment_theme_mod' );

// Calendar widget cache
add_action( 'save_post', 'delete_get_calendar_cache' );
add_action( 'delete_post', 'delete_get_calendar_cache' );
add_action( 'update_option_start_of_week', 'delete_get_calendar_cache' );
add_action( 'update_option_gmt_offset', 'delete_get_calendar_cache' );

// Author
add_action( 'transition_post_status', '__clear_multi_author_cache' );

// Post
add_action( 'init', 'create_initial_post_types', 0 ); // highest priority
add_action( 'admin_menu', '_add_post_type_submenus' );
add_action( 'before_delete_post', '_reset_front_page_settings_for_post' );
add_action( 'wp_trash_post',      '_reset_front_page_settings_for_post' );

// Post Formats
add_filter( 'request', '_post_format_request' );
add_filter( 'term_link', '_post_format_link', 10, 3 );
add_filter( 'get_post_format', '_post_format_get_term' );
add_filter( 'get_terms', '_post_format_get_terms', 10, 3 );
add_filter( 'wp_get_object_terms', '_post_format_wp_get_object_terms' );

// KSES
add_action( 'init', 'kses_init' );
add_action( 'set_current_user', 'kses_init' );

// Script Loader
add_action( 'wp_default_scripts', 'wp_default_scripts' );
add_filter( 'wp_print_scripts', 'wp_just_in_time_script_localization' );
add_filter( 'print_scripts_array', 'wp_prototype_before_jquery' );

add_action( 'wp_default_styles', 'wp_default_styles' );
add_filter( 'style_loader_src', 'wp_style_loader_src', 10, 2 );

// Taxonomy
add_action( 'init', 'create_initial_taxonomies', 0 ); // highest priority

// Canonical
add_action( 'template_redirect', 'redirect_canonical' );
add_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );

// Shortcodes
add_filter( 'the_content', 'do_shortcode', 11 ); // AFTER wpautop()

// Media
add_action( 'wp_playlist_scripts', 'wp_playlist_scripts' );
add_action( 'customize_controls_enqueue_scripts', 'wp_plupload_default_settings' );

// Nav menu
add_filter( 'nav_menu_item_id', '_nav_menu_item_id_use_once', 10, 2 );

// Widgets
add_action( 'init', 'wp_widgets_init', 1 );

// Admin Bar
// Don't remove. Wrong way to disable.
add_action( 'template_redirect', '_wp_admin_bar_init', 0 );
add_action( 'admin_init', '_wp_admin_bar_init' );
add_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
add_action( 'in_admin_header', 'wp_admin_bar_render', 0 );

// Former admin filters that can also be hooked on the front end
add_action( 'media_buttons', 'media_buttons' );
add_filter( 'image_send_to_editor', 'image_add_caption', 20, 8 );
add_filter( 'media_send_to_editor', 'image_media_send_to_editor', 10, 3 );

unset( $filter, $action );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/**
 * WordPress CRON API
 *
 * @package WordPress
 */

/**
 * Schedules a hook to run only once.
 *
 * Schedules a hook which will be executed once by the WordPress actions core at
 * a time which you specify. The action will fire off when someone visits your
 * WordPress site, if the schedule time has passed.
 *
 * @since 2.1.0
 * @link https://codex.wordpress.org/Function_Reference/wp_schedule_single_event
 *
 * @param int $timestamp Timestamp for when to run the event.
 * @param string $hook Action hook to execute when cron is run.
 * @param array $args Optional. Arguments to pass to the hook's callback function.
 * @return void|false
 */
function wp_schedule_single_event( $timestamp, $hook, $args = array()) {
	// don't schedule a duplicate if there's already an identical event due within 10 minutes of it
	$next = wp_next_scheduled($hook, $args);
	if ( $next && abs( $next - $timestamp ) <= 10 * MINUTE_IN_SECONDS ) {
		return;
	}

	$crons = _get_cron_array();
	$event = (object) array( 'hook' => $hook, 'timestamp' => $timestamp, 'schedule' => false, 'args' => $args );
	/**
	 * Filter a single event before it is scheduled.
	 *
	 * @since 3.1.0
	 *
	 * @param object $event An object containing an event's data.
	 */
	$event = apply_filters( 'schedule_event', $event );

	// A plugin disallowed this event
	if ( ! $event )
		return false;

	$key = md5(serialize($event->args));

	$crons[$event->timestamp][$event->hook][$key] = array( 'schedule' => $event->schedule, 'args' => $event->args );
	uksort( $crons, "strnatcasecmp" );
	_set_cron_array( $crons );
}

/**
 * Schedule a periodic event.
 *
 * Schedules a hook which will be executed by the WordPress actions core on a
 * specific interval, specified by you. The action will trigger when someone
 * visits your WordPress site, if the scheduled time has passed.
 *
 * Valid values for the recurrence are hourly, daily and twicedaily. These can
 * be extended using the cron_schedules filter in wp_get_schedules().
 *
 * Use wp_next_scheduled() to prevent duplicates
 *
 * @since 2.1.0
 *
 * @param int $timestamp Timestamp for when to run the event.
 * @param string $recurrence How often the event should recur.
 * @param string $hook Action hook to execute when cron is run.
 * @param array $args Optional. Arguments to pass to the hook's callback function.
 * @return false|void False when does not schedule event.
 */
function wp_schedule_event( $timestamp, $recurrence, $hook, $args = array()) {
	$crons = _get_cron_array();
	$schedules = wp_get_schedules();

	if ( !isset( $schedules[$recurrence] ) )
		return false;

	$event = (object) array( 'hook' => $hook, 'timestamp' => $timestamp, 'schedule' => $recurrence, 'args' => $args, 'interval' => $schedules[$recurrence]['interval'] );
	/** This filter is documented in wp-includes/cron.php */
	$event = apply_filters( 'schedule_event', $event );

	// A plugin disallowed this event
	if ( ! $event )
		return false;

	$key = md5(serialize($event->args));

	$crons[$event->timestamp][$event->hook][$key] = array( 'schedule' => $event->schedule, 'args' => $event->args, 'interval' => $event->interval );
	uksort( $crons, "strnatcasecmp" );
	_set_cron_array( $crons );
}

/**
 * Reschedule a recurring event.
 *
 * @since 2.1.0
 *
 * @param int $timestamp Timestamp for when to run the event.
 * @param string $recurrence How often the event should recur.
 * @param string $hook Action hook to execute when cron is run.
 * @param array $args Optional. Arguments to pass to the hook's callback function.
 * @return false|void False when does not schedule event.
 */
function wp_reschedule_event( $timestamp, $recurrence, $hook, $args = array() ) {
	$crons = _get_cron_array();
	$schedules = wp_get_schedules();
	$key = md5( serialize( $args ) );
	$interval = 0;

	// First we try to get it from the schedule
	if ( isset( $schedules[ $recurrence ] ) ) {
		$interval = $schedules[ $recurrence ]['interval'];
	}
	// Now we try to get it from the saved interval in case the schedule disappears
	if ( 0 == $interval ) {
		$interval = $crons[ $timestamp ][ $hook ][ $key ]['interval'];
	}
	// Now we assume something is wrong and fail to schedule
	if ( 0 == $interval ) {
		return false;
	}

	$now = time();

	if ( $timestamp >= $now ) {
		$timestamp = $now + $interval;
	} else {
		$timestamp = $now + ( $interval - ( ( $now - $timestamp ) % $interval ) );
	}

	wp_schedule_event( $timestamp, $recurrence, $hook, $args );
}

/**
 * Unschedule a previously scheduled cron job.
 *
 * The $timestamp and $hook parameters are required, so that the event can be
 * identified.
 *
 * @since 2.1.0
 *
 * @param int $timestamp Timestamp for when to run the event.
 * @param string $hook Action hook, the execution of which will be unscheduled.
 * @param array $args Arguments to pass to the hook's callback function.
 * Although not passed to a callback function, these arguments are used
 * to uniquely identify the scheduled event, so they should be the same
 * as those used when originally scheduling the event.
 */
function wp_unschedule_event( $timestamp, $hook, $args = array() ) {
	$crons = _get_cron_array();
	$key = md5(serialize($args));
	unset( $crons[$timestamp][$hook][$key] );
	if ( empty($crons[$timestamp][$hook]) )
		unset( $crons[$timestamp][$hook] );
	if ( empty($crons[$timestamp]) )
		unset( $crons[$timestamp] );
	_set_cron_array( $crons );
}

/**
 * Unschedule all cron jobs attached to a specific hook.
 *
 * @since 2.1.0
 *
 * @param string $hook Action hook, the execution of which will be unscheduled.
 * @param array $args Optional. Arguments that were to be pass to the hook's callback function.
 */
function wp_clear_scheduled_hook( $hook, $args = array() ) {
	// Backward compatibility
	// Previously this function took the arguments as discrete vars rather than an array like the rest of the API
	if ( !is_array($args) ) {
		_deprecated_argument( __FUNCTION__, '3.0', __('This argument has changed to an array to match the behavior of the other cron functions.') );
		$args = array_slice( func_get_args(), 1 );
	}

	// This logic duplicates wp_next_scheduled()
	// It's required due to a scenario where wp_unschedule_event() fails due to update_option() failing,
	// and, wp_next_scheduled() returns the same schedule in an infinite loop.
	$crons = _get_cron_array();
	if ( empty( $crons ) )
		return;

	$key = md5( serialize( $args ) );
	foreach ( $crons as $timestamp => $cron ) {
		if ( isset( $cron[ $hook ][ $key 