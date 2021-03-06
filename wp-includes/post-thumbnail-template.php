}#login form p.submit{margin:0;padding:0}.login label{color:#72777c;font-size:14px}.login form .forgetmenot label{font-size:12px;line-height:19px}.login h1{text-align:center}.login h1 a{background-image:url(../images/w-logo-blue.png?ver=20131202);background-image:none,url(../images/wordpress-logo.svg?ver=20131107);-webkit-background-size:84px;background-size:84px;background-position:center top;background-repeat:no-repeat;color:#444;height:84px;font-size:20px;line-height:1.3em;margin:0 auto 25px;padding:0;text-decoration:none;width:84px;text-indent:-9999px;outline:0;display:block}#login{width:320px;padding:8% 0 0;margin:auto}.login #backtoblog,.login #nav{font-size:13px;padding:0 24px}.login #nav{margin:24px 0 0}#backtoblog{margin:16px 0}.login #backtoblog a,.login #nav a{text-decoration:none;color:#555d66}.login #backtoblog a:hover,.login #nav a:hover,.login h1 a:hover{color:#00a0d2}.login #backtoblog a:focus,.login #nav a:focus,.login h1 a:focus{color:#124964}.login form .input,.login input[type=text]{font-size:24px;width:100%;padding:3px;margin:2px 0 16px 6px}.login form .input,.login form input[type=checkbox],.login input[type=text]{background:#fbfbfb}.ie7 .login form .input,.ie8 .login form .input{font-family:sans-serif}.login-action-rp input[type=text]{-webkit-box-shadow:none;box-shadow:none;margin:0}.login #pass-strength-result{font-weight:600;margin:-1px 0 16px 5px;padding:6px 5px;text-align:center;width:100%}.mobile #login{padding:20px 0}.mobile #login form{margin-right:0}.mobile #login #backtoblog,.mobile #login #nav{margin-right:8px}body.interim-login{height:auto}.interim-login #login{padding:0;margin:5px auto 20px}.interim-login.login h1 a{width:auto}.interim-login #login_error,.interim-login.login .message{margin:0 0 16px}.interim-login.login form{margin:0}@-ms-viewport{width:device-width}@media screen and (max-width:782px){.interim-login input[type=checkbox]{height:16px;width:16px}.interim-login input[type=checkbox]:checked:before{width:16px;font:400 21px/1 dashicons;margin:-3px -4px 0 0}}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php
/**
 * WordPress user administration API.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Creates a new user from the "Users" form using $_POST information.
 *
 * @since 2.0.0
 *
 * @return int|WP_Error WP_Error or User ID.
 */
function add_user() {
	return edit_user();
}

/**
 * Edit user settings based on contents of $_POST
 *
 * Used on user-edit.php and profile.php to manage and process user options, passwords etc.
 *
 * @since 2.0.0
 *
 * @param int $user_id Optional. User ID.
 * @return int|WP_Error user id of the updated user
 */
function edit_user( $user_id = 0 ) {
	$wp_roles = wp_roles();
	$user = new stdClass;
	if ( $user_id ) {
		$update = true;
		$user->ID = (int) $user_id;
		$userdata = get_userdata( $user_id );
		$user->user_login = wp_slash( $userdata->user_login );
	} else {
		$update = false;
	}

	if ( !$update && isset( $_POST['user_login'] ) )
		$user->user_login = sanitize_user($_POST['user_login'], true);

	$pass1 = $pass2 = '';
	if ( isset( $_POST['pass1'] ) )
		$pass1 = $_POST['pass1'];
	if ( isset( $_POST['pass2'] ) )
		$pass2 = $_POST['pass2'];

	if ( isset( $_POST['role'] ) && current_user_can( 'edit_users' ) ) {
		$new_role = sanitize_text_field( $_POST['role'] );
		$potential_role = isset($wp_roles->role_objects[$new_role]) ? $wp_roles->role_objects[$new_role] : false;
		// Don't let anyone with 'edit_users' (admins) edit their own role to something without it.
		// Multisite super admins can freely edit their blog roles -- they possess all caps.