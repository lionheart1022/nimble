rg( 'message', 2, $location );

	break;

case 'bulk-delete':
	check_admin_referer( 'bulk-tags' );

	if ( !current_user_can( $tax->cap->delete_terms ) )
		wp_die( __( 'Cheatin&#8217; uh?' ), 403 );

	$tags = (array) $_REQUEST['delete_tags'];
	foreach ( $tags as $tag_ID ) {
		wp_delete_term( $tag_ID, $taxonomy );
	}

	$location = 'edit-tags.php?taxonomy=' . $taxonomy;
	if ( 'post' != $post_type )
		$location .= '&post_type=' . $post_type;
	if ( $referer = wp_get_referer() ) {
		if ( false !== strpos( $referer, 'edit-tags.php' ) )
			$location = $referer;
	}

	$location = add_query_arg( 'message', 6, $location );

	break;

case 'edit':
	$title = $tax->labels->edit_item;

	$tag_ID = (int) $_REQUEST['tag_ID'];

	$tag = get_term( $tag_ID, $taxonomy, OBJECT, 'edit' );
	if ( ! $tag )
		wp_die( __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?' ) );
	require_once( ABSPATH . 'wp-admin/admin-header.php' );
	include( ABSPATH . 'wp-admin/edit-tag-form.php' );
	include( ABSPATH . 'wp-admin/admin-footer.php' );

	exit;

case 'editedtag':
	$tag_ID = (int) $_POST['tag_ID'];
	check_admin_referer( 'update-tag_' . $tag_ID );

	if ( !current_user_can( $tax->cap->edit_terms ) )
		wp_die( __( 'Cheatin&#8217; uh?' ), 403 );

	$tag = get_term( $tag_ID, $taxonomy );
	if ( ! $tag )
		wp_die( __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?' ) );

	$ret = wp_update_term( $tag_ID, $taxonomy, $_POST );

	$location = 'edit-tags.php?taxonomy=' . $taxonomy;
	if ( 'post' != $post_type )
		$location .= '&post_type=' . $post_type;

	if ( $referer = wp_get_original_referer() ) {
		if ( false !== strpos( $referer, 'edit-tags.php' ) )
			$location = $referer;
	}

	if ( $ret && !is_wp_error( $ret ) )
		$location = add_query_arg( 'message', 3, $location );
	else
		$location = add_query_arg( array( 'error' => true, 'message' => 5 ), $location );
	break;
}

if ( ! $location && ! empty( $_REQUEST['_wp_http_referer'] ) ) {
	$location = remove_query_arg( array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI']) );
}

if ( $location ) {
	if ( ! empty( $_REQUEST['paged'] ) ) {
		$location = add_query_arg( 'paged', (int) $_REQUEST['paged'], $location );
	}
	wp_redirect( $location );
	exit;
}

$wp_list_table->prepare_items();
$total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );

if ( $pagenum > $total_pages && $total_pages > 0 ) {
	wp_redirect( add_query_arg( 'paged', $total_pages ) );
	exit;
}

wp_enqueue_script('admin-tags');
if ( current_user_can($tax->cap->edit_terms) )
	wp_enqueue_script('inline-edit-tax');

if ( 'category' == $taxonomy || 'link_category' == $taxonomy || 'post_tag' == $taxonomy  ) {
	$help ='';
	if ( 'category' == $taxonomy )
		$help = '<p>' . sprintf(__( 'You can use categories to define sections of your site and group related posts. The default category is &#8220;Uncategorized&#8221; until you change it in your <a href="%s">writing settings</a>.' ) , 'options-writing.php' ) . '</p>';
	elseif ( 'link_category' == $taxonomy )
		$help = '<p>' . __( 'You can create groups of links by using Link Categories. Link Category names must be unique and Link Categories are separate from the categories you use for posts.' ) . '</p>';
	else
		$help = '<p>' . __( 'Yo