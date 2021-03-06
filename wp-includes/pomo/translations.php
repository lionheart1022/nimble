                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/**
 * WordPress Plugin Install Administration API
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Retrieve plugin installer pages from WordPress Plugins API.
 *
 * It is possible for a plugin to override the Plugin API result with three
 * filters. Assume this is for plugins, which can extend on the Plugin Info to
 * offer more choices. This is very powerful and must be used with care, when
 * overriding the filters.
 *
 * The first filter, 'plugins_api_args', is for the args and gives the action as
 * the second parameter. The hook for 'plugins_api_args' must ensure that an
 * object is returned.
 *
 * The second filter, 'plugins_api', is the result that would be returned.
 *
 * @since 2.7.0
 *
 * @param string $action
 * @param array|object $args Optional. Arguments to serialize for the Plugin Info API.
 * @return object plugins_api response object on success, WP_Error on failure.
 */
function plugins_api($action, $args = null) {

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
	 * Override the Plugin Install API arguments.
	 *
	 * Please ensure that an object is returned.
	 *
	 * @since 2.7.0
	 *
	 * @param object $args   Plugin API arguments.
	 * @param string $action The type of information being requested from the Plugin Install API.
	 */
	$args = apply_filters( 'plugins_api_args', $args, $action );

	/**
	 * Allows a plugin to override the WordPress.org Plugin Install API entirely.
	 *
	 * Please ensure that an object is returned.
	 *
	 * @since 2.7.0
	 *
	 * @param bool|object $result The result object. Default false.
	 * @param string      $action The type of information being requested from the Plugin Install API.
	 * @param object      $args   Plugin API arguments.
	 */
	$res = apply_filters( 'plugins_api', false, $action, $args );

	if ( false === $res ) {
		$url = $http_url = 'http://api.wordpress.org/plugins/info/1.0/';
		if ( $ssl = wp_http_supports( array( 'ssl' ) ) )
			$url = set_url_scheme( $url, 'https' );

		$http_args = array(
			'timeout' => 15,
			'body' => array(
				'action' => $action,
				'request' => serialize( $args )
			)
		);
		$request = wp_remote_post( $url, $http_args );

		if ( $ssl && is_wp_error( $request ) ) {
			trigger_error( __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ), headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE );
			$request = wp_remote_post( $http_url, $http_args );
		}

		if ( is_wp_error($request) ) {
			$res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
		} else {
			$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
			if ( ! is_object( $res ) && ! is_array( $res ) )
				$res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
		}
	} elseif ( !is_wp_error($res) ) {
		$res->external = true;
	}

	/**
	 * Filter the Plugin Install API response results.
	 *
	 * @since 2.7.0
	 *
	 * @param object|WP_Error $res    Response object or WP_Error.
	 * @param string          $action The type of information being requested from the Plugin Install API.
	 * @param object          $args   Plugin API arguments.
	 */
	return apply_filters( 'plugins_api_result', $res, $action, $args );
}

/**
 * Retrieve popular WordPress plugin tags.
 *
 * @since 2.7.0
 *
 * @param array $args
 * @return array
 */
function install_popular_tags( $args = array() ) {
	$key = md5(serialize($args));
	if ( false !== ($tags = get_site_transient('poptags_' . $key) ) )
		return $tags;

	$tags = plugins_api('hot_tags', $args);

	if ( is_wp_error($tags) )
		return $tags;

	set_site_transient( 'poptags_' . $key, $tags, 3 * HOUR_IN_SECONDS );

	return $tags;
}

/**
 * @since 2.7.0
 */
function install_dashboard() {
	?>
	<p><?php printf( __( 'Plugins extend and expand the functionality of WordPress. You may automatically install plugins from the <a href="%1$s">WordPress Plugin Directory</a> or upload a plugin in .zip format via <a href="%2$s">this page</a>.' ), 'https://wordpress.org/plugins/', self_admin_url( 'plugin-install.php?tab=upload' ) ); ?></p>

	<?php display_plugins_table(); ?>

	<h3><?php _e( 'Popular tags' ) ?></h3>
	<p><?php _e( 'You may also browse based on the most popular tags in the Plugin Directory:' ) ?></p>
	<?php

	$api_tags = install_popular_tags();

	echo '<p class="popular-tags">';
	if ( is_wp_error($api_tags) ) {
		echo $api_tags->get_error_message();
	} else {
		//Set up the tags in a way which can be interpreted by wp_generate_tag_cloud()
		$tags = array();
		foreach ( (array) $api_tags as $tag ) {
			$url = self_admin_url( 'plugin-install.php?tab=search&type=tag&s=' . urlencode( $tag['name'] ) );
			$data = array(
				'link' => esc_url( $url ),
				'name' => $tag['name'],
				'slug' => $tag['slug'],
				'id' => sanitize_title_with_dashes( $tag['name'] ),
				'count' => $tag['count']
			);
			$tags[ $tag['name'] ] = (object) $data;
		}
		echo wp_generate_tag_cloud($tags, array( 'single_text' => __('%s plugin'), 'multiple_text' => __('%s plugins') ) );
	}
	echo '</p><br class="clear" />';
}

/**
 * Display search form for searching plugins.
 *
 * @since 2.7.0
 *
 * @param bool $type_selector
 */
function install_search_form( $type_selector = true ) {
	$type = isset($_REQUEST['type']) ? wp_unslash( $_REQUEST['type'] ) : 'term';
	$term = isset($_REQUEST['s']) ? wp_unslash( $_REQUEST['s'] ) : '';
	$input_attrs = '';
	$button_type = 'button screen-reader-text';

	// assume no $type_selector means it's a simplified search form
	if ( ! $type_selector ) {
		$input_attrs = 'class="wp-filter-search" placeholder="' . esc_attr__( 'Search Plugins' ) . '" ';
	}

	?><form class="search-form search-plugins" method="get">
		<input type="hidden" name="tab" value="search" />
		<?php if ( $type_selector ) : ?>
		<select name="type" id="typeselector">
			<option value="term"<?php selected('term', $type) ?>><?php _e('Keyword'); ?></option>
			<option value="author"<?php selected('author', $type) ?>><?php _e('Author'); ?></option>
			<option value="tag"<?php selected('tag', $type) ?>><?php _ex('Tag', 'Plugin Installer'); ?></option>
		</select>
		<?php endif; ?>
		<label><span class="screen-reader-text"><?php _e('Search Plugins'); ?></span>
			<input type="search" name="s" value="<?php echo esc_attr($term) ?>" <?php echo $input_attrs; ?>/>
		</label>
		<?php submit_button( __( 'Search Plugins' )