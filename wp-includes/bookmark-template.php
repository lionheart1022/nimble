ngslashit( $short_url );
	if ( strlen( $short_url ) > 35 )
		$short_url = substr( $short_url, 0, 32 ) . '&hellip;';
	return $short_url;
}

/**
 * Resets global variables based on $_GET and $_POST
 *
 * This function resets global variables based on the names passed
 * in the $vars array to the value of $_POST[$var] or $_GET[$var] or ''
 * if neither is defined.
 *
 * @since 2.0.0
 *
 * @param array $vars An array of globals to reset.
 */
function wp_reset_vars( $vars ) {
	foreach ( $vars as $var ) {
		if ( empty( $_POST[ $var ] ) ) {
			if ( empty( $_GET[ $var ] ) ) {
				$GLOBALS[ $var ] = '';
			} else {
				$GLOBALS[ $var ] = $_GET[ $var ];
			}
		} else {
			$GLOBALS[ $var ] = $_POST[ $var ];
		}
	}
}

/**
 * {@internal Missing Short Description}}
 *
 * @since 2.1.0
 *
 * @param string|WP_Error $message
 */
function show_message($message) {
	if ( is_wp_error($message) ){
		if ( $message->get_error_data() && is_string( $message->get_error_data() ) )
			$message = $message->get_error_message() . ': ' . $message->get_error_data();
		else
			$message = $message->get_error_message();
	}
	echo "<p>$message</p>\n";
	wp_ob_end_flush_all();
	flush();
}

/**
 * @since 2.8.0
 *
 * @param string $content
 * @return array
 */
function wp_doc_link_parse( $content ) {
	if ( !is_string( $content ) || empty( $content ) )
		return array();

	if ( !function_exists('token_get_all') )
		return array();

	$tokens = token_get_all( $content );
	$count = count( $tokens );
	$functions = array();
	$ignore_functions = array();
	for ( $t = 0; $t < $count - 2; $t++ ) {
		if ( ! is_array( $tokens[ $t ] ) ) {
			continue;
		}

		if ( T_STRING == $tokens[ $t ][0] && ( '(' == $tokens[ $t + 1 ] || '(' == $tokens[ $t + 2 ] ) ) {
			// If it's a function or class defined locally, there's not going to be any docs available
			if ( ( isset( $tokens[ $t - 2 ][1] ) && in_array( $tokens[ $t - 2 ][1], array( 'function', 'class' ) ) ) || ( isset( $tokens[ $t - 2 ][0] ) && T_OBJECT_OPERATOR == $tokens[ $t - 1 ][0] ) ) {
				$ignore_functions[] = $tokens[$t][1];
			}
			// Add this to our stack of unique references
			$functions[] = $tokens[$t][1];
		}
	}

	$functions = array_unique( $functions );
	sort( $functions );

	/**
	 * Filter the list of functions and classes to be ignored from the documentation lookup.
	 *
	 * @since 2.8.0
	 *
	 * @param array $ignore_functions Functions and classes to be ignored.
	 */
	$ignore_functions = apply_filters( 'documentation_ignore_functions', $ignore_functions );

	$ignore_functions = array_unique( $ignore_functions );

	$out = array();
	foreach ( $functions as $function ) {
		if ( in_array( $function, $ignore_functions ) )
			continue;
		$out[] = $function;
	}

	return $out;
}

/**
 * Saves option for number of rows when listing posts, pages, comments, etc.
 *
 * @since 2.8.0
 */
function set_screen_options() {

	if ( isset($_POST['wp_screen_options']) && is_array($_POST['wp_screen_options']) ) {
		check_admin_referer( 'screen-options-nonce', 'screenoptionnonce' );

		if ( !$user = wp_get_current_user() )
			return;
		$option = $_POST['wp_screen_options']['option'];
		$value = $_POST['wp_screen_options']['value'];

		if ( $option != sanitize_key( $option ) )
			return;

		$map_option = $option;
		$type = str_replace('edit_', '', $map_option);
		$type = str_replace('_per_page', '', $type);
		if ( in_array( $type, get_taxonomies() ) )
			$map_option = 'edit_tags_per_page';
		elseif ( in_array( $type, get_post_types() ) )
			$map_option = 'edit_per_page';
		else
			$option = str_replace('-', '_', $option);

		switch ( $map_option ) {
			case 'edit_per_page':
			case 'users_per_page':
			case 'edit_comments_per_page':
			case 'upload_per_page':
			case 'edit_tags_per_page':
			case 'plugins_per_page':
			// Network admin
			case 'sites_network_per_page':
			case 'users_network_per_page':
			case 'site_users_network_per_page':
			case 'plugins_network_per_page':
			case 'themes_network_per_page':
			case 'site_themes_network_per_page':
				$value = (int) $value;
				if ( $value < 1 || $value > 999 )
					return;
				break;
			default:

				/**
				 * Filter a screen option value before it is set.
				 *
				 * The filter can also be used to modify non-standard [items]_per_page
				 * settings. See the parent function for a full list of standard options.
				 *
				 * Returning false to the filter will skip saving the current option.
				 *
				 * @since 2.8.0
				 *
				 * @see set_screen_options()
				 *
				 * @param bool|int $value  Screen option value. Default false to skip.
				 * @param string   $option The option name.
				 * @param int      $value  The number of rows to use.
				 */
				$value = apply_filters( 'set-screen-option', false, $option, $value );

				if ( false === $value )
					return;
				break;
		}

		update_user_meta($user->ID, $option, $value);
		wp_safe_redirect( remove_query_arg( array('pagenum', 'apage', 'paged'), wp_get_referer() ) );
		exit;
	}
}

/**
 * Check if rewrite rule for WordPress already exists in the IIS 7+ configuration file
 *
 * @since 2.8.0
 *
 * @return bool
 * @param string $filename The file path to the configuration file
 */
function iis7_rewrite_rule_exists($filename) {
	if ( ! file_exists($filename) )
		return false;
	if ( ! class_exists('DOMDocument') )
		return false;

	$doc = new DOMDocument();
	if ( $doc->load($filename) === false )
		return false;
	$xpath = new DOMXPath($doc);
	$rules = $xpath->query('/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'wordpress\')]');
	if ( $rules->length == 0 )
		return false;
	else
		return true;
}

/**
 * Delete WordPress rewrite rule from web.config file if it exists there
 *
 * @since 2.8.0
 *
 * @param string $filename Name of the configuration file
 * @return bool
 */
function iis7_delete_rewrite_rule($filename) {
	// If configuration file does not exist then rules also do not exist so there is nothing to delete
	if ( ! file_exists($filename) )
		return true;

	if ( ! class_exists('DOMDocument') )
		return false;

	$doc = new DOMDocument();
	$doc->preserveWhiteSpace = false;

	if ( $doc -> load($filename) === false )
		return false;
	$xpath = new DOMXPath($doc);
	$rules = $xpath->query('/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'wordpress\')]');
	if ( $rules->length > 0 ) {
		$child = $rules->item(0);
		$parent = $child->parentNode;
		$parent->removeChild($child);
		$doc->formatOutput = true;
		saveDomDocument($doc, $filename);
	}
	return true;
}

/**
 * Add WordPress rewrite rule to the IIS 7+ configuration file.
 *
 * @since 2.8.0
 *
 * @param string $filename The file path to the configuration file
 * @param string $rewrite_rule The XML fragment with URL Rewrite rule
 * @return bool
 */
function iis7_add_rewrite_rule($filename, $rewrite_rule) {
	if ( ! class_exists('DOMDocument') )
		return false;

	// If configuration file does not exist then we create one.
	if ( ! file_exists($filename) ) {
		$fp = fopen( $filename, 'w');
		fwrite($fp, '<configuration/>');
		fclose($fp);
	}

	$doc = new DOMDocument();
	$doc->preserveWhiteSpace = false;

	if ( $doc->load($filename) === false )
		return false;

	$xpath = new DOMXPath($doc);

	// First check if the rule already exists as in that case there is no need to re-add it
	$wordpress_rules = $xpath->query('/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'wordpress\')]');
	if ( $wordpress_rules->length > 0 )
		return true;

	// Check the XPath to the rewrite rule and create XML nodes if they do not exist
	$xmlnodes = $xpath->query('/configuration/system.webServer/rewrite/rules');
	if ( $xmlnodes->length > 0 ) {
		$rules_node = $xmlnodes->item(0);
	} else {
		$rules_node = $doc->createElement('rules');

		$xmlnodes = $xpath->query('/configuration/system.webServer/rewrite');
		if ( $xmlnodes->length > 0 ) {
			$rewrite_node = $xmlnodes->item(0);
			$rewrite_node->appendChild($rules_node);
		} else {
			$rewrite_node = $doc->createElement('rewrite');
			$rewrite_node->appendChild($rules_node);

			$xmlnodes = $xpath->query('/configuration/system.webServer');
			if ( $xmlnodes->length > 0 ) {
				$system_webServer_node = $xmlnodes->item(0);
				$system_webServer_node->appendChild($rewrite_node);
			} else {
				$system_webServer_node = $doc->createElement('system.webServer');
				$system_webServer_node->appendChild($rewrite_node);

				$xmlnodes = $xpath->query('/configuration');
				if ( $xmlnodes->length > 0 ) {
					$config_node = $xmlnodes->item(0);
					$config_node->appendChild($system_webServer_node);
				} else {
					$config_node = $doc->createElement('configuration');
					$doc->appendChild($config_node);
					$config_node->appendChild($system_webServer_node);
				}
			}
		}
	}

	$rule_fragment = $doc->createDocumentFragment();
	$rule_fragment->appendXML($rewrite_rule);
	$rules_node->appendChild($rule_fragment);

	$doc->encoding = "UTF-8";
	$doc->formatOutput = true;
	saveDomDocument($doc, $filename);

	return true;
}

/**
 * Saves the XML document into a file
 *
 * @since 2.8.0
 *
 * @param DOMDocument $doc
 * @param string $filename
 */
function saveDomDocument($doc, $filename) {
	$config = $doc->saveXML();
	$config = preg_replace("/([^\r])\n/", "$1\r\n", $config);
	$fp = fopen($filename, 'w');
	fwrite($fp, $config);
	fclose($fp);
}

/**
 * Display the default admin color scheme picker (Used in user-edit.php)
 *
 * @since 3.0.0
 *
 * @global array $_wp_admin_css_colors
 */
function admin_color_scheme_picker( $user_id ) {
	global $_wp_admin_css_colors;

	ksort( $_wp_admin_css_colors );

	if ( isset( $_wp_admin_css_colors['fresh'] ) ) {
		// Set Default ('fresh') and Light should go first.
		$_wp_admin_css_colors = array_filter( array_merge( array( 'fresh' => '', 'light' => '' ), $_wp_admin_css_colors ) );
	}

	$current_color = get_user_option( 'admin_color', $user_id );

	if ( empty( $current_color ) || ! isset( $_wp_admin_css_colors[ $current_color ] ) ) {
		$current_color = 'fresh';
	}

	?>
	<fieldset id="color-picker" class="scheme-list">
		<legend class="screen-reader-text"><span><?php _e( 'Admin Color Scheme' ); ?></span></legend>
		<?php
		wp_nonce_field( 'save-color-scheme', 'color-nonce', false );
		foreach ( $_wp_admin_css_colors as $color => $color_info ) :

			?>
			<div class="color-option <?php echo ( $color == $current_color ) ? 'selected' : ''; ?>">
				<input name="admin_color" id="admin_color_<?php echo esc_attr( $color ); ?>" type="radio" value="<?php echo esc_attr( $color ); ?>" class="tog" <?php checked( $color, $current_color ); ?> />
				<input type="hidden" class="css_url" value="<?php echo esc_url( $color_info->url ); ?>" />
				<input type="hidden" class="icon_colors" value="<?php echo esc_attr( wp_json_encode( array( 'icons' => $color_info->icon_colors ) ) ); ?>" />
				<label for="admin_color_<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $color_info->name ); ?></label>
				<table class="color-palette">
					<tr>
					<?php

					foreach ( $color_info->colors as $html_color ) {
						?>
						<td style="background-color: <?php echo esc_attr( $html_color ); ?>">&nbsp;</td>
						<?php
					}

					?>
					</tr>
				</table>
			</div>
			<?php

		endforeach;

	?>
	</fieldset>
	<?php
}

/**
 *
 * @global array $_wp_admin_css_colors
 */
function wp_color_scheme_settings() {
	global $_wp_admin_css_colors;

	$color_scheme = get_user_option( 'admin_color' );

	// It's possible to have a color scheme set that is no longer registered.
	if ( empty( $_wp_admin_css_colors[ $color_scheme ] ) ) {
		$color_scheme = 'fresh';
	}

	if ( ! empty( $_wp_admin_css_colors[ $color_scheme ]->icon_colors ) ) {
		$icon_colors = $_wp_admin_css_colors[ $color_s