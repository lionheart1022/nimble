ta" content="width=device-width, initial-scale=1">
		<?php
	}
}

/**
 * Check lock status for posts displayed on the Posts screen
 *
 * @since 3.6.0
 */
function wp_check_locked_posts( $response, $data, $screen_id ) {
	$checked = array();

	if ( array_key_exists( 'wp-check-locked-posts', $data ) && is_array( $data['wp-check-locked-posts'] ) ) {
		foreach ( $data['wp-check-locked-posts'] as $key ) {
			if ( ! $post_id = absint( substr( $key, 5 ) ) )
				continue;

			if ( ( $user_id = wp_check_post_lock( $post_id ) ) && ( $user = get_userdata( $user_id ) ) && current_user_can( 'edit_post', $post_id ) ) {
				$send = array( 'text' => sprintf( __( '%s is currently editing' ), $user->display_name ) );

				if ( ( $avatar = get_avatar( $user->ID, 18 ) ) && preg_match( "|src='([^']+)'|", $avatar, $matches ) )
					$send['avatar_src'] = $matches[1];

				$checked[$key] = $send;
			}
		}
	}

	if ( ! empty( $checked ) )
		$response['wp-check-locked-posts'] = $checked;

	return $response;
}

/**
 * Check lock status on the New/Edit Post screen and refresh the lock
 *
 * @since 3.6.0
 */
function wp_refresh_post_lock( $response, $data, $screen_id ) {
	if ( array_key_exists( 'wp-refresh-post-lock', $data ) ) {
		$received = $data['wp-refresh-post-lock'];
		$send = array();

		if ( ! $post_id = absint( $received['post_id'] ) )
			return $response;

		if ( ! current_user_can('edit_post', $post_id) )
			return $response;

		if ( ( $user_id = wp_check_post_lock( $post_id ) ) && ( $user = get_userdata( $user_id ) ) ) {
			$error = array(
				'text' => sprintf( __( '%s has taken over and is currently editing.' ), $user->display_name )
			);

			if ( $avatar = get_avatar( $user->ID, 64 ) ) {
				if ( preg_match( "|src='([^']+)'|", $avatar, $matches ) )
					$error['avatar_src'] = $matches[1];
			}

			$send['lock_error'] = $error;
		} else {
			if ( $new_lock = wp_set_post_lock( $post_id ) )
				$send['new_lock'] = implode( ':', $new_lock );
		}

		$response['wp-refresh-post-lock'] = $send;
	}

	return $response;
}

/**
 * Check nonce expiration on the New/Edit Post screen and refresh if needed
 *
 * @since 3.6.0
 */
function wp_refresh_post_nonces( $response, $data, $screen_id ) {
	if ( array_key_exists( 'wp-refresh-post-nonces', $data ) ) {
		$received = $data['wp-refresh-post-nonces'];
		$response['wp-refresh-post-nonces'] = array( 'check' => 1 );

		if ( ! $post_id = absint( $received['post_id'] ) ) {
			return $response;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $response;
		}

		$response['wp-refresh-post-nonces'] = array(
			'replace' => array(
				'getpermalinknonce' => wp_create_nonce('getpermalink'),
				'samplepermalinknonce' => wp_create_nonce('samplepermalink'),
				'closedpostboxesnonce' => wp_create_nonce('closedpostboxes'),
				'_ajax_linking_nonce' => wp_create_nonce( 'internal-linking' ),
				'_wpnonce' => wp_create_nonce( 'update-post_' . $post_id ),
			),
			'heartbeatNonce' => wp_create_nonce( 'heartbeat-nonce' ),
		);
	}

	return $response;
}

/**
 * Disable suspension of Heartbeat on the Add/Edit Post scree