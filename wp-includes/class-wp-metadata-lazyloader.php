efaults to all allowed themes.
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
				'customize' => ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) ? wp_customize_url( $slug ) : null,
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
		<div class="theme-wrap">
			<div class="theme-header">
				<button type="button" class="left dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show previous theme' ); ?></span></button>
				<button type="button" class="right dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show next theme' ); ?></span></button>
				<button type="button" class="close dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Close details dialog' ); ?></span></button>
			</div>
			<div class="theme-about">
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
					<h3 class="theme-name">{{{ data.name }}}<span class="theme-version"><?php printf( __( 'Version: %s' ), '{{ data.version }}' ); ?></span></h3>
					<h4 class="theme-author"><?php printf( __( 'By %s' ), '{{{ data.authorAndUri }}}' ); ?></h4>
					<p class="theme-description">{{{ data.description }}}</p>

					<# if ( data.parent ) { #>
						<p class="parent-theme"><?php printf( __( 'This is a child theme of %s.' ), '<strong>{{{ data.parent }}}</strong>' ); ?></p>
					<# } #>

					<# if ( data.tags ) { #>
						<p class="theme-tags"><span><?php _e( 'Tags:' ); ?></span> {{ data.tags }}</p>
					<# } #>
				</div>
			</div>

			