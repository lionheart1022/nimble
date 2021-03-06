esponse['wp_autosave'] = array( 'success' => true, 'message' => sprintf( __( 'Draft saved at %s.' ), date_i18n( $draft_saved_date_format ) ) );
		}
	}

	return $response;
}

/**
 * Disables autocomplete on the 'post' form (Add/Edit Post screens) for WebKit browsers,
 * as they disregard the autocomplete setting on the editor textarea. That can break the editor
 * when the user navigates to it with the browser's Back button. See #28037
 *
 * @since 4.0
 *
 * @global bool $is_safari
 * @global bool $is_chrome
 */
function post_form_autocomplete_off() {
	global $is_safari, $is_chrome;

	if ( $is_safari || $is_chrome ) {
		echo ' autocomplete="off"';
	}
}

/**
 * Remove single-use URL parameters and create canonical link based on new URL.
 *
 * Remove specific query string parameters from a URL, create the canonical link,
 * put it in the admin header, and change the current URL to match.
 *
 * @since 4.2.0
 */
function wp_admin_canonical_url() {
	$removable_query_args = array(
		'message', 'settings-updated', 'saved',
		'update', 'updated', 'activated',
		'activate', 'deactivate', 'locked',
		'deleted', 'trashed', 'untrashed',
		'enabled', 'disabled', 'skipped',
		'spammed', 'unspammed',
	);

	/**
	 * Filter the list of URL parameters to remove.
	 *
	 * @since 4.2.0
	 *
	 * @param array $removable_query_args An array of parameters to remove from the URL.
	 */
	$removable_query_args = apply_filters( 'removable_query_args', $removable_query_args );

	if ( empty( $removable_query_args ) ) {
		return;
	}

	// Ensure we're using an absolute URL.
	$current_url  = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	$filtered_url = remove_query_arg( $removable_query_args, $current_url );
	?>
	<link id="wp-admin-canonical" rel="canonical" href="<?php echo esc_url( $filtered_url ); ?>" />
	<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, document.getElementById( 'wp-admin-canonical' ).href + window.location.hash );
		}
	</script>
<?php
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  