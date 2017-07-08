=> 'email' ) );
	}

	/**
	 * Fires before user profile update errors are returned.
	 *
	 * @since 2.8.0
	 *
	 * @param WP_Error &$errors WP_Error object, passed by reference.
	 * @param bool     $update  Whether this is a user update.
	 * @param WP_User  &$user   WP_User object, passed by reference.
	 */
	do_action_ref_array( 'user_profile_update_errors', array( &$errors, $update, &$user ) );

	if ( $errors->get_error_codes() )
		return $errors;

	if ( $update ) {
		$user_id = wp_update_user( $user );
	} else {
		$user_id = wp_insert_user( $user );
		$notify  = isset( $_POST['send_user_notification'] ) ? 'both' : 'admin';

		/**
		  * Fires after a new user has been created.
		  *
		  * @since 4.4.0
		  *
		  * @param int    $user_id ID of the newly created user.
		  * @param string $notify  Type of notification that should happen. See {@see wp_send_new_user_notifications()}
		  *                        for more information on possible values.
		  */
		do_action( 'edit_user_created_user', $user_id, $notify );
	}
	return $user_id;
}

/**
 * Fetch a filtered list of user roles that the current user is
 * allowed to edit.
 *
 * Simple function who's main purpose is to allow filtering of the
 * list of roles in the $wp_roles object so that plugins can remove
 * inappropriate ones depending on the situation or user making edits.
 * Specifically because without filtering anyone with the edit_users
 * capability can edit others to be administrators, even if they are
 * only editors or authors. This filter allows admins to delegate
 * user management.
 *
 * @since 2.8.0
 *
 * @return array
 */
function get_editable_roles() {
	$all_roles = wp_roles()->roles;

	/**
	 * Filter the list of editable roles.
	 *
	 * @since 2.8.0
	 *
	 * @param array $all_roles List of roles.
	 */
	$editable_roles = apply_filters( 'editable_roles', $all_roles );

	return $editable_roles;
}

/**
 * Retrieve user data and filter it.
 *
 * @since 2.0.5
 *
 * @param int $user_id User ID.
 * @return WP_User|bool WP_User object on success, false on failure.
 */
function get_user_to_edit( $user_id ) {
	$user = get_userdata( $user_id );

	if ( $user )
		$user->filter = 'edit';

	return $user;
}

/**
 * Retrieve the user's drafts.
 *
 * @since 2.0.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param int $user_id User ID.
 * @return array
 */
function get_users_drafts( $user_id ) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'draft' AND post_author = %d ORDER BY post_modified DESC", $user_id);

	/**
	 * Filter the user's drafts query string.
	 *
	 * @since 2.0.0
	 *
	 * @param string $query The user's drafts query string.
	 */
	$query = apply_filters( 'get_users_drafts', $query );
	return $wpdb->get_results( $query );
}

/**
 * Remove user and optionally reassign posts and links to another user.
 *
 * If the $reassign parameter is not assigned to a User ID, then all posts will
 * be deleted of that user. The action 'delete_user' that is passed the User ID
 * being deleted will be run after the posts are either reassigned or deleted.
 * The user meta will also be deleted that are for that User ID.
 *
 * @since 2.0.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param int $id User ID.
 * @param int $reassign Optional. Reassign posts and links to new User ID.
 * @return bool True when finished.
 */
function wp_delete_user( $id, $reassign = null ) {
	global $wpdb;

	if ( ! is_numeric( $id ) ) {
		return false;
	}

	$id = (int) $id;
	$user = new WP_User( $id );

	if ( !$user->exists() )
		return false;

	// Normalize $reassign to null or a user ID. 'novalue' was an older default.
	if ( 'novalue' === $reassign ) {
		$reassign = null;
	} elseif ( null !== $reassign ) {
		$reassign = (int) $reassign;
	}

	/**
	 * Fires immediately before a user is deleted from the database.
	 *
	 * @since 2.0.0
	 *
	 * @param int      $id       ID of the user to delete.
	 * @param int|null $reassign ID of the user to reassign posts and links to.
	 *                           Default null, for no reassignment.
	 */
	do_action( 'delete_user', $id, $reassign );

	if ( null === $reassign ) {
		$post_types_to_delete = array();
		foreach ( get_post_types( array(), 'objects' ) as $post_type ) {
			if ( $post_type->delete_with_user ) {
				$post_types_to_delete[] = $post_type->name;
			} elseif ( null === $post_type->delete_with_user && post_type_supports( $post_type->name, 'author' ) ) {
				$post_types_to_delete[] = $post_type->name;
			}
		}

		/**
		 * Filter the list of post types to delete with a user.
		 *
		 * @since 3.4.0
		 *
		 * @param array $post_types_to_delete Post types to delete.
		 * @param int   $id                   User ID.
		 */
		$post_types_to_delete = apply_filters( 'post_types_to_delete_with_user', $post_types_to_delete, $id );
		$post_types_to_delete = implode( "', '", $post_types_to_delete );
		$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_author = %d AND post_type IN ('$post_types_to_delete')", $id ) );
		if ( $post_ids ) {
			foreach ( $post_ids as $post_id )
				wp_delete_post( $post_id );
		}

		// Clean links
		$link_ids = $wpdb->get_col( $wpdb->prepare("SELECT link_id FROM $wpdb->links WHERE link_owner = %d", $id) );

		if ( $link_ids ) {
			foreach ( $link_ids as $link_id )
				wp_delete_link($link_id);
		}
	} else {
		$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_author = %d", $id ) );
		$wpdb->update( $wpdb->posts, array('post_author' => $reassign), array('post_author' => $id) );
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $post_id )
				clean_post_cache( $post_id );
		}
		$link_ids = $wpdb->get_col( $wpdb->prepare("SELECT link_id FROM $wpdb->links WHERE link_owner = %d", $id) );
		$wpdb->update( $wpdb->links, array('link_owner' => $reassign), array('link_owner' => $id) );
		if ( ! empty( $link_ids ) ) {
			foreach ( $link_ids as $link_id )
				clean_bookmark_cache( $link_id );
		}
	}

	// FINALLY, delete user
	if ( is_multisite() ) {
		remove_user_from_blog( $id, get_current_blog_id() );
	} else {
		$meta = $wpdb->get_col( $wpdb->prepare( "SELECT umeta_id FROM $wpdb->usermeta WHERE user_id = %d", $id ) );
		foreach ( $meta as $mid )
			delete_metadata_by_mid( 'user', $mid );

		$wpdb->delete( $wpdb->users, array( 'ID' => $id ) );
	}

	clean_user_cache( $user );

	/**
	 * Fires immediately after a user is deleted from the database.
	 *
	 * @since 2.9.0
	 *
	 * @param int      $id       ID of the deleted user.
	 * @param int|null $reassign ID of the user to reassign posts and links to.
	 *                           Default null, for no reassignment.
	 */
	do_action( 'deleted_user', $id, $reassign );

	return true;
}

/**
 * Remove all capabilities from user.
 *
 * @since 2.1.0
 *
 * @param int $id User ID.
 */
function wp_revoke_user($id) {
	$id = (int) $id;

	$user = new WP_User($id);
	$user->remove_all_caps();
}

/**
 * @since 2.8.0
 *
 * @global int $user_ID
 *
 * @param false $errors Deprecated.
 */
function default_password_nag_handler($errors = false) {
	global $user_ID;
	// Short-circuit it.
	if ( ! get_user_option('default_password_nag') )
		return;

	// get_user_setting = JS saved UI setting. else no-js-fallback code.
	if ( 'hide' == get_user_setting('default_password_nag') || isset($_GET['default_password_nag']) && '0' == $_GET['default_password_nag'] ) {
		delete_user_setting('default_password_nag');
		update_user_option($user_ID, 'default_password_nag', false, true);
	}
}

/**
 * @since 2.8.0
 *
 * @param int    $user_ID
 * @param object $old_data
 */
function default_password_nag_edit_user($user_ID, $old_data) {
	// Short-circuit it.
	if ( ! get_user_option('default_password_nag', $user_ID) )
		return;

	$new_data = get_userdata($user_ID);

	// Remove the nag if the password has been changed.
	if ( $new_data->user_pass != $old_data->user_pass ) {
		delete_user_setting('default_password_nag');
		update_user_option($user_ID, 'default_password_nag', false, true);
	}
}

/**
 * @since 2.8.0
 *
 * @global string $pagenow
 */
function default_password_nag() {
	global $pagenow;
	// Short-circuit it.
	if ( 'profile.php' == $pagenow || ! get_user_option('default_password_nag') )
		return;

	echo '<div class="error default-password-nag">';
	echo '<p>';
	echo '<strong>' . __('Notice:') . '</strong> ';
	_e('You&rsquo;re using the auto-generated password for your account. Would you like to change it?');
	echo '</p><p>';
	printf( '<a href="%s">' . __('Yes, take me to my profile page') . '</a> | ', get_edit_profile_url() . '#password' );
	printf( '<a href="%s" id="default-password-nag-no">' . __('No thanks, do not remind me again') . '</a>', '?default_password_nag=0' );
	echo '</p></div>';
}

/**
 * @since 3.5.0
 * @access private
 */
function delete_users_add_js() { ?>
<script>
jQuery(document).ready( function($) {
	var submit = $('#submit').prop('disabled', true);
	$('input[name="delete_option"]').one('change', function() {
		submit.prop('disabled', false);
	});
	$('#reassign_user').focus( function() {
		$('#delete_option1').prop('checked', true).trigger('change');
	});
});
</script>
<?php
}

/**
 * Optional SSL preference that can be turned on by hooking to the 'personal_options' action.
 *
 * @since 2.7.0
 *
 * @param object $user User data object
 */
function use_ssl_preference($user) {
?>
	<tr class="user-use-ssl-wrap">
		<th scope="row"><?php _e('Use https')?></th>
		<td><label for="use_ssl"><input name="use_ssl" type="checkbox" id="use_ssl" value="1" <?php checked('1', $user->use_ssl); ?> /> <?php _e('Always use https when visiting the admin'); ?></label></td>
	</tr>
<?php
}

/**
 *
 * @param string $text
 * @return string
 */
function admin_created_user_email( $text ) {
	$roles = get_editable_roles();
	$role = $roles[ $_REQUEST['role'] ];
	/* translators: 1: Site name, 2: site URL, 3: role */
	return sprintf( __( 'Hi,
You\'ve been invited to join \'%1$s\' at
%2$s with the role of %3$s.
If you do not want to join this site please ignore
this email. This invitation will expire in a few days.

Please click the following link to activate your user account:
%%s' ), get_bloginfo( 'name' ), home_url(), wp_specialchars_decode( translate_user_role( $role['name'] ) ) );
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     