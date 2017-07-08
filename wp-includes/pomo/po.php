'high'] ) ? $api->banners['low'] : $api->banners['high'];
		?>
		<style type="text/css">
			#plugin-information-title.with-banner {
				background-image: url( <?php echo esc_url( $low ); ?> );
			}
			@media only screen and ( -webkit-min-device-pixel-ratio: 1.5 ) {
				#plugin-information-title.with-banner {
					background-image: url( <?php echo esc_url( $high ); ?> );
				}
			}
		</style>
		<?php
	}

	echo '<div id="plugin-information-scrollable">';
	echo "<div id='{$_tab}-title' class='{$_with_banner}'><div class='vignette'></div><h2>{$api->name}</h2></div>";
	echo "<div id='{$_tab}-tabs' class='{$_with_banner}'>\n";

	foreach ( (array) $api->sections as $section_name => $content ) {
		if ( 'reviews' === $section_name && ( empty( $api->ratings ) || 0 === array_sum( (array) $api->ratings ) ) ) {
			continue;
		}

		if ( isset( $plugins_section_titles[ $section_name ] ) ) {
			$title = $plugins_section_titles[ $section_name ];
		} else {
			$title = ucwords( str_replace( '_', ' ', $section_name ) );
		}

		$class = ( $section_name === $section ) ? ' class="current"' : '';
		$href = add_query_arg( array('tab' => $tab, 'section' => $section_name) );
		$href = esc_url( $href );
		$san_section = esc_attr( $section_name );
		echo "\t<a name='$san_section' href='$href' $class>$title</a>\n";
	}

	echo "</div>\n";

	$date_format = __( 'M j, Y @ H:i' );
	$last_updated_timestamp = strtotime( $api->last_updated );
	?>
	<div id="<?php echo $_tab; ?>-content" class='<?php echo $_with_banner; ?>'>
	<div class="fyi">
		<ul>
		<?php if ( ! empty( $api->version ) ) { ?>
			<li><strong><?php _e( 'Version:' ); ?></strong> <?php echo $api->version; ?></li>
		<?php } if ( ! empty( $api->author ) ) { ?>
			<li><strong><?php _e( 'Author:' ); ?></strong> <?php echo links_add_target( $api->author, '_blank' ); ?></li>
		<?php } if ( ! empty( $api->last_updated ) ) { ?>
			<li><strong><?php _e( 'Last Updated:' ); ?></strong> <span title="<?php echo esc_attr( date_i18n( $date_format, $last_updated_timestamp ) ); ?>">
				<?php printf( __( '%s ago' ), human_time_diff( $last_updated_timestamp ) ); ?>
			</span></li>
		<?php } if ( ! empty( $api->requires ) ) { ?>
			<li><strong><?php _e( 'Requires WordPress Version:' ); ?></strong> <?php printf( __( '%s or higher' ), $api->requires ); ?></li>
		<?php } if ( ! empty( $api->tested ) ) { ?>
			<li><strong><?php _e( 'Compatible up to:' ); ?></strong> <?php echo $api->tested; ?></li>
		<?php } if ( ! empty( $api->active_installs ) ) { ?>
			<li><strong><?php _e( 'Active Installs:' ); ?></strong> <?php
				if ( $api->active_installs >= 1000000 ) {
					_ex( '1+ Million', 'Active plugin installs' );
				} else {
					echo number_format_i18n( $api->active_installs ) . '+';
				}
			?></li>
		<?php } if ( ! empty( $api->slug ) && empty( $api->external ) ) { ?>
			<li><a target="_blank" href="https://wordpress.org/plugins/<?php echo $api->slug; ?>/"><?php _e( 'WordPress.org Plugin Page &#187;' ); ?></a></li>
		<?php } if ( ! empty( $api->homepage ) ) { ?>
			<li><a target="_blank" href="<?php echo esc_url( $api->homepage ); ?>"><?php _e( 'Plugin Homepage &#187;' ); ?></a></li>
		<?php } if ( ! empty( $api->donate_link ) && empty( $api->contributors ) ) { ?>
			<li><a target="_blank" href="<?php echo esc_url( $api->donate_link ); ?>"><?php _e( 'Donate to this plugin &#187;' ); ?></a></li>
		<?php } ?>
		</ul>
		<?php if ( ! empty( $api->rating ) ) { ?>
		<h3><?php _e( 'Average Rating' ); ?></h3>
		<?php wp_star_rating( array( 'rating' => $api->rating, 'type' => 'percent', 'number' => $api->num_ratings ) ); ?>
		<small><?php printf( _n( '(based on %s rating)', '(based on %s ratings)', $api->num_ratings ), number_format_i18n( $api->num_ratings ) ); ?></small>
		<?php }

		if ( ! empty( $api->ratings ) && array_sum( (array) $api->ratings ) > 0 ) {
			foreach( $api->ratings as $key => $ratecount ) {
				// Avoid div-by-zero.
				$_rating = $api->num_ratings ? ( $ratecount / $api->num_ratings ) : 0;
				?>
				<div class="counter-container">
					<span class="counter-label"><a href="https://wordpress.org/support/view/plugin-reviews/<?php echo $api->slug; ?>?filter=<?php echo $key; ?>"
						target="_blank"
						title="<?php echo esc_attr( sprintf( _n( 'Click to see reviews that provided a rating of %d star', 'Click to see reviews that provided a rating of %d stars', $key ), $key ) ); ?>"><?php printf( _n( '%d star', '%d stars', $key ), $key ); ?></a></span>
					<span class="counter-back">
						<span class="counter-bar" style="width: <?php echo 92 * $_rating; ?>px;"></span>
					</span>
					<span class="counter-count"><?php echo number_format_i18n( $ratecount ); ?></span>
				</div>
				<?php
			}
		}
		if ( ! empty( $api->contributors ) ) { ?>
			<h3><?php _e( 'Contributors' ); ?></h3>
			<ul class="contributors">
				<?php
				foreach ( (array) $api->contributors as $contrib_username => $contrib_profile ) {
					if ( empty( $contrib_username ) && empty( $contrib_profile ) ) {
						continue;
					}
					if ( empty( $contrib_username ) ) {
						$contrib_username = preg_replace( '/^.+\/(.+)\/?$/', '\1', $contrib_profile );
					}
					$contrib_username = sanitize_user( $contrib_username );
					if ( empty( $contrib_profile ) ) {
						echo "<li><img src='https://wordpress.org/grav-redirect.php?user={$contrib_username}&amp;s=36' width='18' height='18' />{$contrib_username}</li>";
					} else {
						echo "<li><a href='{$contrib_profile}' target='_blank'><img src='https://wordpress.org/grav-redirect.php?user={$contrib_username}&amp;s=36' width='18' height='18' />{$contrib_username}</a></li>";
					}
				}
				?>
			</ul>
			<?php if ( ! empty( $api->donate_link ) ) { ?>
				<a target="_blank" href="<?php echo esc_url( $api->donate_link ); ?>"><?php _e( 'Donate to this plugin &#187;' ); ?></a>
			<?php } ?>
		<?php } ?>
	</div>
	<div id="section-holder" class="wrap">
	<?php
		if ( ! empty( $api->tested ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $api->tested ) ), $api->tested, '>' ) ) {
			echo '<div class="notice notice-warning"><p>' . __('<strong>Warning:</strong> This plugin has <strong>not been tested</strong> with your current version of WordPress.') . '</p></div>';
		} elseif ( ! empty( $api->requires ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $api->requires ) ), $api->requires, '<' ) ) {
			echo '<div class="notice notice-warning"><p>' . __('<strong>Warning:</strong> This plugin has <strong>not been marked as compatible</strong> with your version of WordPress.') . '</p></div>';
		}

		foreach ( (array) $api->sections as $section_name => $content ) {
			$content = links_add_base_url( $content, 'https://wordpress.org/plugins/' . $api->slug . '/' );
			$content = links_add_target( $content, '_blank' );

			$san_section = esc_attr( $section_name );

			$display = ( $section_name === $section ) ? 'block' : 'none';

			echo "\t<div id='section-{$san_section}' class='section' style='display: {$display};'>\n";
			echo $content;
			echo "\t</div>\n";
		}
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n"; // #plugin-information-scrollable
	echo "<div id='$tab-footer'>\n";
	if ( ! empty( $api->download_link ) && ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) ) {
		$status = install_plugin_install_status( $api );
		switch ( $status['status'] ) {
			case 'install':
				if ( $status['url'] ) {
					echo '<a class="button button-primary right" href="' . $status['url'] . '" target="_parent">' . __( 'Install Now' ) . '</a>';
				}
				break;
			case 'update_available':
				if ( $status['url'] ) {
					echo '<a data-slug="' . esc_attr( $api->slug ) . '" id="plugin_update_from_iframe" class="button button-primary right" href="' . $status['url'] . '" target="_parent">' . __( 'Install Update Now' ) .'</a>';
				}
				break;
			case 'newer_installed':
				echo '<a class="button button-primary right disabled">' . sprintf( __( 'Newer Version (%s) Installed'), $status['version'] ) . '</a>';
				break;
			case 'latest_installed':
				echo '<a class="button button-primary right disabled">' . __( 'Latest Version Installed' ) . '</a>';
				break;
		}
	}
	echo "</div>\n";

	iframe_footer();
	exit;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php
/**
 * Misc WordPress Administration API.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Returns whether the server is running Apache with the mod_rewrite module loaded.
 *
 * @since 2.0.0
 *
 * @return bool
 */
function got_mod_rewrite() {
	$got_rewrite = apache_mod_loaded('mod_rewrite', true);

	/**
	 * Filter whether Apache and mod_rewrite are present.
	 *
	 * This filter was previously used to force URL rewriting for other servers,
	 * like nginx. Use the got_url_rewrite filter in got_url_rewrite() instead.
	 *
	 * @since 2.5.0
	 *
	 * @see got_url_rewrite()
	 *
	 * @param bool $got_rewrite Whether Apache and mod_rewrite are present.
	 */
	return apply_filters( 'got_rewrite', $got_rewrite );
}

/**
 * Returns whether the server supports URL rewriting.
 *
 * Detects Apache's mod_rewrite, IIS 7.0+ permalink support, and nginx.
 *
 * @since 3.7.0
 *
 * @global bool $is_nginx
 *
 * @return bool Whether the server supports URL rewriting.
 */
function got_url_rewrite() {
	$got_url_rewrite = ( got_mod_rewrite() || $GLOBALS['is_nginx'] || iis7_supports_permalinks() );

	/**
	 * Filter whether URL rewriting is available.
	 *
	 * @since 3.7.0
	 *
	 * @param bool $got_url_rewrite Whether URL rewriting is available.
	 */
	return apply_filters( 'got_url_rewrite', $got_url_rewrite );
}

/**
 * {@internal Missing Short Description}}
 *
 * @since 1.5.0
 *
 * @param string $filename
 * @param string $marker
 * @return array An array of strings from a file (.htaccess ) from between BEGIN and END markers.
 */
function extract_from_markers( $filename, $marker ) {
	$result = array ();

	if (!file_exists( $filename ) ) {
		return $result;
	}

	if ( $markerdata = explode( "\n", implode( '', file( $filename ) ) ));
	{
		$state = false;
		foreach ( $markerdata as $markerline ) {
			if (strpos($markerline, '# END ' . $marker) !== false)
				$state = false;
			if ( $state )
				$result[] = $markerline;
			if (strpos($markerline, '# BEGIN ' . $marker) !== false)
				$state = true;
		}
	}

	return $result;
}

/**
 * {@internal Missing Short Description}}
 *
 * Inserts an array of strings into a file (.htaccess ), placing it between
 * BEGIN and END markers. Replaces existing marked info. Retains surrounding
 * data. Creates file if none exists.
 *
 * @since 1.5.0
 *
 * @param string $filename
 * @param string $marker
 * @param array  $insertion
 * @return bool True on write success, false on failure.
 */
function insert_with_markers( $filename, $marker, $insertion ) {
	if (!file_exists( $filename ) || is_writeable( $filename ) ) {
		if (!file_exists( $filename ) ) {
			$markerdata = '';
		} else {
			$markerdata = explode( "\n", implode( '', file( $filename ) ) );
		}

		if ( !$f = @fopen( $filename, 'w' ) )
			return false;

		$foundit = false;
		if ( $markerdata ) {
			$state = true;
			foreach ( $markerdata as $n => $markerline ) {
				if (strpos($markerline, '# BEGIN ' . $marker) !== false)
					$state = false;
				if ( $state ) {
					if ( $n + 1 < count( $markerdata ) )
						fwrite( $f, "{$markerline}\n" );
					else
						fwrite( $f, "{$markerline}" );
				}
				if (strpos($markerline, '# END ' . $marker) !== false) {
					fwrite( $f, "# BEGIN {$marker}\n" );
					if ( is_array( $insertion ))
						foreach ( $insertion as $insertline )
							fwrite( $f, "{$insertline}\n" );
					fwrite( $f, "# END {$marker}\n" );
					$state = true;
					$foundit = true;
				}
			}
		}
		if (!$foundit) {
			fwrite( $f, "\n# BEGIN {$ma