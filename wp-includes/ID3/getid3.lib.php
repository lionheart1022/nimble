enu( array(
			'parent' => 'network-admin',
			'id'     => 'network-admin-p',
			'title'  => __( 'Plugins' ),
			'href'   => network_admin_url( 'plugins.php' ),
		) );
	}

	// Add site links
	$wp_admin_bar->add_group( array(
		'parent' => 'my-sites',
		'id'     => 'my-sites-list',
		'meta'   => array(
			'class' => is_super_admin() ? 'ab-sub-secondary' : '',
		),
	) );

	foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {
		switch_to_blog( $blog->userblog_id );

		$blavatar = '<div class="blavatar"></div>';

		$blogname = $blog->blogname;

		if ( ! $blogname ) {
			$blogname = preg_replace( '#^(https?://)?(www.)?#', '', get_home_url() );
		}

		$menu_id  = 'blog-' . $blog->userblog_id;

		$wp_admin_bar->add_menu( array(
			'parent'    => 'my-sites-list',
			'id'        => $menu_id,
			'title'     => $blavatar . $blogname,
			'href'      => admin_url(),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'id'     => $menu_id . '-d',
			'title'  => __( 'Dashboard' ),
			'href'   => admin_url(),
		) );

		if ( current_user_can( get_post_type_object( 'post' )->cap->create_posts ) ) {
			$wp_admin_bar->add_menu( array(
				'parent' => $menu_id,
				'id'     => $menu_id . '-n',
				'title'  => __( 'New Post' ),
				'href'   => admin_url( 'post-new.php' ),
			) );
		}

		if ( current_user_can( 'edit_posts' ) ) {
			$wp_admin_bar->add_menu( array(
				'parent' => $menu_id,
				'id'     => $menu_id . '-c',
				'title'  => __( 'Manage Comments' ),
				'href'   => admin_url( 'edit-comments.php' ),
			) );
		}

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'id'     => $menu_id . '-v',
			'title'  => __( 'Visit Site' ),
			'href'   => home_url( '/' ),
		) );

		restore_current_blog();
	}
}

/**
 * Provide a shortlink.
 *
 * @since 3.1.0
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_shortlink_menu( $wp_admin_bar ) {
	$short = wp_get_shortlink( 0, 'query' );
	$id = 'get-shortlink';

	if ( empty( $short ) )
		return;

	$html = '<input class="shortlink-input" type="text" readonly="readonly" value="' . esc_attr( $short ) . '" />';

	$wp_admin_bar->add_menu( array(
		'id' => $id,
		'title' => __( 'Shortlink' ),
		'href' => $short,
		'meta' => array( 'html' => $html ),
	) );
}

/**
 * Provide an edit link for posts and terms.
 *
 * @since 3.1.0
 *
 * @global object   $tag
 * @global WP_Query $wp_the_query
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_edit_menu( $wp_admin_bar ) {
	global $tag, $wp_the_query;

	if ( is_admin() ) {
		$current_screen = get_current_screen();
		$post = get_post();

		if ( 'post' == $current_screen->base
			&& 'add' != $current_screen->action
			&& ( $post_type_object = get_post_type_object( $post->post_type ) )
			&& current_user_can( 'read_post', $post->ID )
			&& ( $post_type_object->public )
			&& ( $post_type_object->show_in_admin_bar ) )
		{
			if ( 'draft' == $post->post_status ) {
				$preview_link = set_url_scheme( get_permalink( $post->ID ) );
				/** This filter is documented in wp-admin/includes/meta-boxes.php */
				$preview_link = apply_filters( 'preview_post_link', add_query_arg( 'preview', 'true', $preview_link ), $post );
				$wp_admin_bar->add_menu( array(
					'id' => 'preview',
					'title' => $post_type_object->labels->view_item,
					'href' => esc_url( $preview_link ),
					'meta' => array( 'target' => 'wp-preview-' . $post->ID ),
				) );
			} else {
				$wp_admin_bar->add_menu( array(
					'id' => 'view',
					'title' => $post_type_object->labels->view_item,
					'href' => get_permalink( $post->ID )
				) );
			}
		} elseif ( 'edit-tags' == $current_screen->base
			&& isset( $tag ) && is_object( $tag )
			&& ( $tax = get_taxonomy( $tag->taxonomy ) )
			&& $tax->public )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'view',
				'title' => $tax->labels->view_item,
				'href' => get_term_link( $tag )
			) );
		}
	} else {
		$current_object = $wp_the_query->get_queried_object();

		if ( empty( $current_object ) )
			return;

		if ( ! empty( $current_object->post_type )
			&& ( $post_type_object = get_post_type_object( $current_object->post_type ) )
			&& current_user_can( 'edit_post', $current_object->ID )
			&& $post_type_object->show_ui && $post_type_object->show_in_admin_bar
			&& $edit_post_link = get_edit_post_link( $current_object->ID ) )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'edit',
				'title' => $post_type_object->labels->edit_item,
				'href' => $edit_post_link
			) );
		} elseif ( ! empty( $current_object->taxonomy )
			&& ( $tax = get_taxonomy( $current_object->taxonomy ) )
			&& current_user_can( $tax->cap->edit_terms )
			&& $tax->show_ui
			&& $edit_term_link = get_edit_term_link( $current_object->term_id, $current_object->taxonomy ) )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'edit',
				'title' => $tax->labels->edit_item,
				'href' => $edit_term_link
			) );
		}
	}
}

/**
 * Add "Add New" menu.
 *
 * @since 3.1.0
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_new_content_menu( $wp_admin_bar ) {
	$actions = array();

	$cpts = (array) get_post_types( array( 'show_in_admin_bar' => true ), 'objects' );

	if ( isset( $cpts['post'] ) && current_user_can( $cpts['post']->cap->create_posts ) )
		$actions[ 'post-new.php' ] = array( $cpts['post']->labels->name_admin_bar, 'new-post' );

	if ( isset( $cpts['attachment'] ) && current_user_can( 'upload_files' ) )
		$actions[ 'media-new.php' ] = array( $cpts['attachment']->labels->name_admin_bar, 'new-media' );

	if ( current_user_can( 'manage_links' ) )
		$actions[ 'link-add.php' ] = array( _x( 'Link', 'add new from admin bar' ), 'new-link' );

	if ( isset( $cpts['page'] ) && current_user_can( $cpts['page']->cap->create_posts ) )
		$actions[ 'post-new.php?post_type=page' ] = array( $cpts['page']->labels->name_admin_bar, 'new-page' );

	unset( $cpts['post'], $cpts['page'], $cpts['attachment'] );

	// Add any additional custom post types.
	foreach ( $cpts as $cpt ) {
		if ( ! current_user_can( $cpt->cap->create_posts ) )
			continue;

		$key = 'post-new.php?post_type=' . $cpt->name;
		$actions[ $key ] = array( $cpt->labels->name_admin_bar, 'new-' . $cpt->name );
	}
	// Avoid clash with parent node and a 'content' post type.
	if ( isset( $actions['post-new.php?post_type=content'] ) )
		$actions['post-new.php?post_type=content'][1] = 'add-new-content';

	if ( current_user_can( 'create_users' ) || current_user_can( 'promote_users' ) )
		$actions[ 'user-new.php' ] = array( _x( 'User', 'add new from admin bar' ), 'new-user' );

	if ( ! $actions )
		return;

	$title = '<span class="ab-icon"></span><span class="ab-label">' . _x( 'New', 'admin bar menu group label' ) . '</span>';

	$wp_admin_bar->add_menu( array(
		'id'    => 'new-content',
		'title' => $title,
		'href'  => admin_url( current( array_keys( $actions ) ) ),
	) );

	foreach ( $actions as $link => $action ) {
		list( $title, $id ) = $action;

		$wp_admin_bar->add_menu( array(
			'parent'    => 'new-content',
			'id'        => $id,
			'title'     => $title,
			'href'      => admin_url( $link )
		) );
	}
}

/**
 * Add edit comments link with awaiting moderation count bubble.
 *
 * @since 3.1.0
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_comments_menu( $wp_admin_bar ) {
	if ( !current_user_can('edit_posts') )
		return;

	$awaiting_mod = wp_count_comments();
	$awaiting_mod = $awaiting_mod->moderated;
	$awaiting_title = esc_attr( sprintf( _n( '%s comment awaiting moderation', '%s comments awaiting moderation', $awaiting_mod ), number_format_i18n( $awaiting_mod ) ) );

	$icon  = '<span class="ab-icon"></span>';
	$title = '<span id="ab-awaiting-mod" class="ab-label awaiting-mod pending-count count-' . $awaiting_mod . '">' . number_format_i18n( $awaiting_mod ) . '</span>';

	$wp_admin_bar->add_menu( array(
		'id'    => 'comments',
		'title' => $icon . $title,
		'href'  => admin_url('edit-comments.php'),
		'meta'  => array( 'title' => $awaiting_title ),
	) );
}

/**
 * Add appearance submenu items to the "Site Name" menu.
 *
 * @since 3.1.0
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_appearance_menu( $wp_admin_bar ) {
	$wp_admin_bar->add_group( array( 'parent' => 'site-name', 'id' => 'appearance' ) );

	if ( current_user_can( 'switch_themes' ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id'     => 'themes',
			'title'  => __( 'Themes' ),
			'href'   => admin_url( 'themes.php' ),
		) );
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	if ( current_theme_supports( 'widgets' )  ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id'     => 'widgets',
			'title'  => __( 'Widgets' ),
			'href'   => admin_url( 'widgets.php' ),
		) );
	}

	if ( current_theme_supports( 'menus' ) || current_theme_supports( 'widgets' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'menus', 'title' => __('Menus'), 'href' => admin_url('nav-menus.php') ) );

	if ( current_theme_supports( 'custom-background' ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id'     => 'background',
			'title'  => __( 'Background' ),
			'href'   => admin_url( 'themes.php?page=custom-background' ),
			'meta'   => array(
				'class' => 'hide-if-customize',
			),
		) );
	}

	if ( current_theme_supports( 'custom-header' ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id'     => 'header',
			'title'  => __( 'Header' ),
			'href'   => admin_url( 'themes.php?page=custom-header' ),
			'meta'   => array(
				'class' => 'hide-if-customize',
			),
		) );
	}

}

/**
 * Provide an update link if theme/plugin/core updates are available.
 *
 * @since 3.1.0
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_updates_menu( $wp_admin_bar ) {

	$update_data = wp_get_update_data();

	if ( !$update_data['counts']['total'] )
		return;

	$title = '<span class="ab-icon"></span><span class="ab-label">' . number_format_i18n( $update_data['counts']['total'] ) . '</span>';
	$title .= '<span class="screen-reader-text">' . $update_data['title'] . '</span>';

	$wp_admin_bar->add_menu( array(
		'id'    => 'updates',
		'title' => $title,
		'href'  => network_admin_url( 'update-core.php' ),
		'meta'  => array(
			'title' => $update_data['title'],
		),
	) );
}

/**
 * Add search form.
 *
 * @since 3.3.0
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_search_menu( $wp_admin_bar ) {
	if ( is_admin() )
		return;

	$form  = '<form action="' . esc_url( home_url( '/' ) ) . '" method="get" id="adminbarsearch">';
	$form .= '<input class="adminbar-input" name="s" id="adminbar-search" type="text" value="" maxlength="150" />';
	$form .= '<label for="adminbar-search" class="screen-reader-text">' . __( 'Search' ) . '</label>';
	$form .= '<input type="submit" class="adminbar-button" value="' . __('Search') . '"/>';
	$form .= '</form>';

	$wp_admin_bar->add_menu( array(
		'parent' => 'top-secondary',
		'id'     => 'search',
		'title'  => $form,
		'meta'   => array(
			'class'    => 'admin-bar-search',
			'tabindex' => -1,
		)
	) );
}

/**
 * Add secondary menus.
 *
 * @since 3.3.0
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_admin_bar_add_secondary_groups( $wp_admin_bar ) {
	$wp_admin_bar->add_group( array(
		'id'     => 'top-secondary',
		'meta'   => array(
			'class' => 'ab-top-secondary',
		),
	) );

	$wp_admin_bar->add_group( array(
		'parent' => 'wp-logo',
		'id'     => 'wp-logo-external',
		'meta'   => array(
			'class' => 'ab-sub-secondary',
		),
	) );
}

/**
 * Style and scripts for the admin bar.
 *
 * @since 3.1.0
 */
function wp_admin_bar_header() { ?>
<style type="text/css" media="print">#wpadminbar { display:none; }</style>
<?php
}

/**
 * Default admin bar callback.
 *
 * @since 3.1.0
 */
function _admin_bar_bump_cb() { ?>
<style type="text/css" media="screen">
	html { margin-top: 32px !important; }
	* html body { margin-top: 32px !important; }
	@media screen and ( max-width: 782px ) {
		html { margin-top: 46px !important; }
		* html body { margin-top: 46px !important; }
	}
</style>
<?php
}

/**
 * Set the display status of the admin bar.
 *
 * This can be called immediately upon plugin load. It does not need to be called from a function hooked to the init action.
 *
 * @since 3.1.0
 *
 * @global WP_Admin_Bar $wp_admin_bar
 *
 * @param bool $show Whether to allow the admin bar to show.
 */
function show_admin_bar( $show ) {
	global $show_admin_bar;
	$show_admin_bar = (bool) $show;
}

/**
 * Determine whether the admin bar should be showing.
 *
 * @since 3.1.0
 *
 * @global WP_Admin_Bar $wp_admin_bar
 * @global string       $pagenow
 *
 * @return bool Whether the admin bar should be showing.
 */
function is_admin_bar_showing() {
	global $show_admin_bar, $pagenow;

	// For all these types of requests, we never want an admin bar.
	if ( defined('XMLRPC_REQUEST') || defined('DOING_AJAX') || defined('IFRAME_REQUEST') )
		return false;

	// Integrated into the admin.
	if ( is_admin() )
		return true;

	if ( ! isset( $show_admin_bar ) ) {
		if ( ! is_user_logged_in() || 'wp-login.php' == $pagenow ) {
			$show_admin_bar = false;
		} else {
			$show_admin_bar = _get_admin_bar_pref();
		}
	}

	/**
	 * Filter whether to show the admin bar.
	 *
	 * Returning false to this hook is the recommended way to hide the admin bar.
	 * The user's display preference is used for logged in users.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $show_admin_bar Whether the admin bar should be shown. Default false.
	 */
	$show_admin_bar = apply_filters( 'show_admin_bar', $show_admin_bar );

	return $show_admin_bar;
}

/**
 * Retrieve the admin bar display preference of a user.
 *
 * @since 3.1.0
 * @access private
 *
 * @param string $context Context of this preference check. Defaults to 'front'. The 'admin'
 * 	preference is no longer used.
 * @param int $user Optional. ID of the user to check, defaults to 0 for current user.
 * @return bool Whether the admin bar should be showing for this user.
 */
function _get_admin_bar_pref( $context = 'front', $user = 0 ) {
	$pref = get_user_option( "show_admin_bar_{$context}", $user );
	if ( false === $pref )
		return true;

	return 'true' === $pref;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      ÿØÿà JFIF  H H  ÿáExif  MM *           n    	   t       ~       †(       1    8.4 2       ‡i       ¢    Apple iPhone 6     H      H   2015:07:15 14:23:53   ‚š      (‚      0ˆ"       ˆ'             0221      8      L‘     ’ 
     `’      h’ 
     p’ 
     x’       ’	       ’
      €’      ˆ’|   :  ’‘    681 ’’    681       0100               	       À¢       £       ¤        ¤        ¤       ¤        ¤2      Ê¤3      ê¤4    "  ğ          (      2015:07:15 14:23:53 2015:07:15 14:23:53   c   Ó  Ô    1£  	´          S   _Ç5Apple iOS  MM 
  	          .   Œ     h  º  	        	      â  	      Ş  	        
     " 	 	       	           bplist00O n ^ X T Y X ] W V O J G C < 9 6 f i d T F G B G I D = 4 8 2 1 - ` ş õ à Ö È ¬ ¡  u e > : 5 - * b !*++OE;3#ï Ö e /,',Gdf`XPE2i ,&BVoqiaPv ü ø 4anib>‚ 
 ú ó ò ú  ;V]YŒ ş ı ğ ô è ó ü ñ î ú .IM” õ ô ğ ï é è ó æ ç ç ÷ Ç Š ¡ ñ ñ á é à æ é Û Û × å é » @ D ° î ğ å å Ş å ä Õ Ö Ï Ù × À ‰ – À í î í é à ä Û Ø Ö Ó Ğ Í Å ¸ n Ñ ê ï é é İ ß × Ï É Ì È È Æ Ê ¤ æ ï ï î é ä â İ × Ñ Î Ç È Æ É Î y a g n a v s z { s w x u m v x                             bplist00ÔUflagsUvalueUepochYtimescale  ÅE´ ;šÊ #-/8:             	               ?ÿÿÿİ  4èÿÿõZ  ÿÿóE  …   S      S               Apple iPhone 6 back camera 4.15mm f/2.2 ÿâXICC_PROFILE   HLino  mntrRGB XYZ Î  	  1  acspMSFT    IEC sRGB              öÖ     Ó-HP                                                 cprt  P   3desc  „   lwtpt  ğ   bkpt     rXYZ     gXYZ  ,   bXYZ  @   dmnd  T   pdmdd  Ä   ˆvued  L   †view  Ô   $lumi  ø   meas     $tech  0   rTRC  <  gTRC  <  bTRC  <  text    Copyright (c) 1998 Hewlett-Packard Company  desc       sRGB IEC61966-2.1           sRGB IEC61966-2.1                                                  XYZ       óQ    ÌXYZ                 XYZ       o¢  8õ  XYZ       b™  ·…  ÚXYZ       $   „  ¶Ïdesc       IEC http://www.iec.ch           IEC http://www.iec.ch                                              desc       .IEC 61966-2.1 Default RGB colour space - sRGB           .IEC 61966-2.1 Default RGB colour space - sRGB                      desc       ,Reference Viewing Condition in IEC61966-2.1           ,Reference Viewing Condition in IEC61966-2.1                          view     ¤ş _. Ï íÌ  \   XYZ      L	V P   Wçmeas                            sig     CRT curv           
     # ( - 2 7 ; @ E J O T Y ^ c h m r w |  † ‹  • š Ÿ ¤ © ® ² · ¼ Á Æ Ë Ğ Õ Û à å ë ğ ö û%+28>ELRY`gnu|ƒ‹’š¡©±¹ÁÉÑÙáéòú&/8AKT]gqz„˜¢¬¶ÁËÕàëõ !-8COZfr~Š–¢®ºÇÓàìù -;HUcq~Œš¨¶ÄÓáğş+:IXgw†–¦µÅÕåö'7HYj{Œ¯ÀÑãõ+=Oat†™¬¿Òåø2FZn‚–ª¾Òçû		%	:	O	d	y		¤	º	Ï	å	û

'
=
T
j

˜
®
Å
Ü
ó"9Qi€˜°Èáù*C\u§ÀÙó&@Zt©ÃŞø.Id›¶Òî	%A^z–³Ïì	&Ca~›¹×õ1OmŒªÉè&Ed„£Ãã#Ccƒ¤Åå'Ij‹­Îğ4Vx›½à&Il²ÖúAe‰®Ò÷@eŠ¯Õú Ek‘·İ*QwÅì;cŠ²Ú*R{£ÌõGp™Ãì@j”¾é>i”¿ê  A l ˜ Ä ğ!!H!u!¡!Î!û"'"U"‚"¯"İ#
#8#f#”#Â#ğ$$M$|$«$Ú%	%8%h%—%Ç%÷&'&W&‡&·&è''I'z'«'Ü((?(q(¢(Ô))8)k))Ğ**5*h*›*Ï++6+i++Ñ,,9,n,¢,×--A-v-«-á..L.‚.·.î/$/Z/‘/Ç/ş050l0¤0Û11J1‚1º1ò2*2c2›2Ô33F33¸3ñ4+4e44Ø55M5‡5Â5ı676r6®6é7$7`7œ7×88P8Œ8È99B99¼9ù:6:t:²:ï;-;k;ª;è<'<e<¤<ã="=a=¡=à> >`> >à?!?a?¢?â@#@d@¦@çA)AjA¬AîB0BrBµB÷C:C}CÀDDGDŠDÎEEUEšEŞF"FgF«FğG5G{GÀHHKH‘H×IIcI©IğJ7J}JÄKKSKšKâL*LrLºMMJM“MÜN%NnN·O OIO“OİP'PqP»QQPQ›QæR1R|RÇSS_SªSöTBTTÛU(UuUÂVV\V©V÷WDW’WàX/X}XËYYiY¸ZZVZ¦Zõ[E[•[å\5\†\Ö]']x]É^^l^½__a_³``W`ª`üaOa¢aõbIbœbğcCc—cëd@d”dée=e’eçf=f’fèg=g“géh?h–hìiCišiñjHjŸj÷kOk§kÿlWl¯mm`m¹nnknÄooxoÑp+p†pàq:q•qğrKr¦ss]s¸ttptÌu(u…uáv>v›vøwVw³xxnxÌy*y‰yçzFz¥{{c{Â|!||á}A}¡~~b~Â#„å€G€¨
kÍ‚0‚’‚ôƒWƒº„„€„ã…G…«††r†×‡;‡ŸˆˆiˆÎ‰3‰™‰şŠdŠÊ‹0‹–‹üŒcŒÊ1˜ÿfÎ6nÖ‘?‘¨’’z’ã“M“¶” ”Š”ô•_•É–4–Ÿ—
—u—à˜L˜¸™$™™üšhšÕ›B›¯œœ‰œ÷dÒ@®ŸŸ‹Ÿú i Ø¡G¡¶¢&¢–££v£æ¤V¤Ç¥8¥©¦¦‹¦ı§n§à¨R¨Ä©7©©ªª««u«é¬\¬Ğ­D­¸®-®¡¯¯‹° °u°ê±`±Ö²K²Â³8³®´%´œµµŠ¶¶y¶ğ·h·à¸Y¸Ñ¹J¹Âº;ºµ».»§¼!¼›½½¾
¾„¾ÿ¿z¿õÀpÀìÁgÁãÂ_ÂÛÃXÃÔÄQÄÎÅKÅÈÆFÆÃÇAÇ¿È=È¼É:É¹Ê8Ê·Ë6Ë¶Ì5ÌµÍ5ÍµÎ6Î¶Ï7Ï¸Ğ9ĞºÑ<Ñ¾Ò?ÒÁÓDÓÆÔIÔËÕNÕÑÖUÖØ×\×àØdØèÙlÙñÚvÚûÛ€ÜÜŠİİ–ŞŞ¢ß)ß¯à6à½áDáÌâSâÛãcãëäsäüå„ææ–çç©è2è¼éFéĞê[êåëpëûì†ííœî(î´ï@ïÌğXğåñrñÿòŒóó§ô4ôÂõPõŞömöû÷Šøø¨ù8ùÇúWúçûwüü˜ı)ıºşKşÜÿmÿÿÿáihttp://ns.adobe.com/xap/1.0/ <x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="XMP Core 5.4.0">
   <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
      <rdf:Description rdf:about=""
            xmlns:xmp="http://ns.adobe.com/xap/1.0/"
            xmlns:photoshop="http://ns.adobe.com/photoshop/1.0/">
         <xmp:CreateDate>2015-07-15T14:23:53</xmp:CreateDate>
         <xmp:ModifyDate>2015-07-15T14:23:53</xmp:ModifyDate>
         <xmp:CreatorTool>8.4</xmp:CreatorTool>
         <photoshop:DateCreated>2015-07-15T14:23:53</photoshop:DateCreated>
      </rdf:Description>
   </rdf:RDF>
</x:xmpmeta>
ÿÛ C 


ÿÛ C		ÿÀ ,," ÿÄ              	ÿÄ Q 
  !1"AQaq2‘¡#±Á$BRÑ3Cbr‚²Óáğ’Â%&“¢'4STcsƒ„³ÃÒÔñÿÄ            ÿÄ '       !12"AQa#qğÿÚ   ? ÕÙXíG™ÚÒbÙPŒÛPya‡æãY²(ñêÓO;â&R?XE²MäH6î]€¡'ßÊ¢äÁXÊAÎ‡ı85ó’GoÚgÚ	»’%µ±¸Eq1MïûH–êæÚ{~xné
<ˆ¯6›ÊÌG9ò]­×ß¯Ö‡4/´GBñÄ$áØj¾¿àLUû%]ZÜü=Ö¤¶;Ô°Í·ıÒn7lV—ı“­á´øy¬÷0ˆ»ÍP— `1îf·s^¿¤råíY-šCNêñæµCÀô¥Ò ¢p)†#¥'“E8¤•æ€Fh€Åc–«L1¶²)Db° DÁ¢-v(ÉÈ´ÀÀæŠ½(((‹Í4ÑPæŒ§Ş‚´@i‘ÌMÎ§*Çi¤](éÁPá“G'½1CÅ6)‘÷Ì™é^[¦ÏZe¿ƒ&*‚H^}ëÆó5ßsXi:â˜9õ€<àV‹ı©o¥“áÑ…'’#=ÜQæ7*Ç«pG>U¸®%Ïh/ÚKI»?¥YİÍ*÷fUi[*Œ8ÿ ÅXòzÕc;qƒK¸îÇ}tu+ñõæ¢uı#Sµ†Yëæmv7xK€G9ûTÍ¶j¨L7©’í±Î›ëÖ×ñv~õWP‘í¶cbËs€z×™§Aİ´KienvaŠd‚j+LœÏg…¼0E:½™R4<K˜è>´ßH”&œªÀHÂÕqí‘ÔS#Ÿ–{ˆô–<l ÿ fÔxÆ7Ü¤1ã¯ó¥A–¸Ñ‰Ü]= ‰ıhˆñG$áñğÿ ßJŠ‚ã¼‘dd ã
Ã•43½«•xÒaœ¾_cş4ğêšt£úĞÍ»q°ş}Ö9£;‘·…c±ı±ÆpÏöİq¨,ëû™Õp2cuİŸ±ä~uw70\²ı¼ÅKGcñxš6ŠD#Â|,>‡Î…y¥Ih¹"+ˆWÄmqõÏsAÓ_²X[Ï†z£Ç•Aª¸
I8ıÌ^¿ZÜ-j¯ÙMãu7‰QCjògkdq5µ^f&½^9øG>^Cä}i]ŞI+“Íxô­H + R™¹¡°â€X9¯f’§ŠöE0WQDV
9 w€V7n4o.ãíYLúÒ Í-M0X$2ÑTñL­Î(ªh¨ëBGˆ§"›©Í-M2;±FCM£4tÍQ£bˆ€´@~ÔÀ›©³X-÷¡³â€QlI’‡œùÒ©‡¥ns§íOo%Üº(‚M‹,¥Oû :èwjæ/Ú†õÚé‘1Ä‰
]¼ö¬9¯áW–}+=K¤ÀOêj?´pDš\…­Q$B¡vFª.:â¦ °ıøT»½ƒÈ*\6ßÖ£»OÖ–¼ÔnfˆÍdr­ŸöÏå^|é±ÿ ÚGİ–îü^†¦ÃİYÌÎ@T¶?İê?•í•`*	<~”´]ö—ÈÇW˜ñU%2ÅÆ¡£ä®Çêr€ãøĞ£eæ\l=áÈ¦:Åé†÷L•Ê‚6c1ê?fÙf¸W‘¥ —<ŒsíShIOÙç½ŒÉ; •Çò¨Ïè`Vğ1° §C\›KÙ„°İ_@dÇj’(§Åáı++ók²KMjæ1Ó3Â¯ƒè|#ø×7	¥ÕbÚ±ÌÌ‚
·±¥A©ˆäa;ÜNÛ”èúÇ?Zesk®DÂ;H¯ˆ·DÇè9œèš•µä$	â€%¶á•¼Æg«:Ÿöi…á èª¢M^V%:7îaæ¶C®kÿ ÙâÖ;?†7rê’ÈWÈêÇ·­lÜ×±ÃŞ¹²óY…¤³œb°ÍAgæ¶KÌØ5àKt¤ç>ôeQ¶€Kå¥ˆÔÕnNÓêØ=”ÖzpCÚúOVfoNhe±L*×=­Ô-íå»/©Ej]¥¸Ò4U$±ï‰ j‘Ó/õ¹5b¿Ñí­­åVa,¦VLø”Æ g8á‰öÆpÛâÏa»GƒÉÓnqÿ ºjªv…4ËëKQo=ËO“±…w
lundN '®ÆÚñ+k˜îàx$Ya‘C$ˆAV¡Ša}­Ã¦ÌÔsE3ó]Ùh‡®â¹Ø«`{Ô„1O
K‹,r ÈèÛ•èAó¨ı{PmKø u€	$Ê0Fóö]ÇíI“³±Å3ÜiÒ:éÛs˜Æb”ùï¡'Í†ÅLªiFzş”µ¨NÑ»İ\ØÉc9Ô­‘d’FP«•Û
wmlAN|²ûMÖb¾ÂñKit&Şå6¶R:†T‘Í–SŠZõ¡@¥©Éª„sZsÍ1Åx¦G^-÷¡³í.GZF>y¤?&“¿=i;³@*“çX%›€3KíQ´ÈÚZä¿ÚSK¶Ö~#»ÊÙh-"‹*›ŠòÇsı¯Jë;¸Î=ë‰~;ö¦Ş×ã¼²Aas-a.C#q“ã ù“ÓËò2úá¶üSí’¯geMİÄÏ"æHÈ>İ)¿j,¤Hàcw)ŒÈ€ÄÎqõÆzŒÓ[fmVõş^ÎÜe°¡uª .@ÇÖœëZ^¯•¥ÍÔelšã`]ÛÌ7ml±‹W2İoq°Î|˜X‡*-´éÕğíDÀ÷Çıi¤Ø1ÈÜp2O½.ÚR¯©I€ËŞ*óé´Vß¦)I‚É¨XÅ·,"|•›&1®åŞÜıë€5«\0 [ÈÛG—+ÿ Z¤ÆRÓÁ)Ú]›zdæ¦’¥¡ »™åV~â;³qrûË°êBz²B¶‹tÊÅsæÀÁöCZG@íeöœ`]d€LZNœäñ«Î“¨O{4“{Û‰HeŠ6ËF£<“ÀÉÈõÅexZ}ô¶O~úozè²É|ëÄı¡×¦9™.4íu"šEŠæÚUÇÈoì°#zôªßhoä’[tY’×y3K2íWÿ Tq’zjy ^ß\Ş7ÉÆ_O´`b*± 
O$Ó5Ú×jûmÚ¿³U²Y|)š(å¸‘­90ÛŠşê€}>¾õ²sƒZ»öc¿–ÿ áCÉ<OŸÒ·:±o\ñü+h5z|}c_$»f†M(õ¯¥j–†²[# ñIc)=)‚ƒX$â°N*;Q×ôİ.NîîşÚÖM¡öM*©ÁÈùàşF˜µ–¯{Ù}bİåy­&Œ$c,ÙB0™¤-¬úÍ•©Á/ô~¤C4EÔ,Š¥ãtÈ$d)à‚
z‚!Û]-1Iqv¥œÓÍ×¿ËFà[j ûé_òéƒı
ÆşÒêökÆ¶HæÚV\”o“0[+•ä¹É$ÔÂ¿5[NÕî´ÍVoo‘tÿ Eí ÇÁÙ½QÇ©6ëÿ ¢˜?íı¡Ò.lVò[$7‰Ú$FÜ¬¥H!õòÅÛFÕZ…Ïhn;ÅQ¼Û[Â9üHÜSaÚPcÿ eµOıõ§üúÊöS9?ä¦­ŸNúÏş}t;0¶“µŞŸw-½û€²Ï9ï–p:	‘ÀÉÀR¸ÉƒŠsg¢\Éwow©ß­äÖÄ¼QÁ†%b¥w`–bv³[ôÎNÒj˜ç²z°ö3Yÿ Ï¥'iµ>3ÙM[í5Ÿüúp-­:óUqÚOË²:Áıõ—ÿ Ø¢/k®Süïfµ¨ıq/ÿ †šVÄç}Ø¨}YMb	$Kk»]´­İ»ÂÇŒğqRÈÀÌù•æ‡eù'œ.§4ƒÁ=hŠª¢½šA“ ó@,Ë”€ÅÚ†_$Q¢¦F×îjùİñÓ[+ñS´ÅLs¡Ô=²DŞ<<>E}™2¹è+æÏoõBNÖëWRÙEq÷³Ê®¤Œ!<õõ®no·UUWS"»ÙB ğ3¦¢=í¢¬2ş<âC‘Ğò95«orÀÏ¥8Ïœn¨5)¢|ôŠüªÍª	"Ea÷â¸>¸ï§MÊé)«Ô.<d)ã ¡D¨Öw.’çf=qü¨·Ñ	&·\o™œà{Í{Nò¶¡°“—<ù–5LiÅ»£ö‘ß;m|½ÛşŸ¥JÚ´{ª‘œ
·D ¾qÚ¦}Yº~TxH¢Df éS`iÍ²æãQØºê3Ç‡›íG%Üsõö«çdty´û'”#tY^õÃe°=³ŒûUôM‰,†ÚÚY$]˜ÆH÷¦ŒöÂ·6ıÄÔ‡€ôÈ<Ö›+Ö—õŠ^£§<¯Ş¼§.w.NŞ§…è8=zĞî=&Â+KK¤‘ë–ïõÚ8Àò«¼º4LW,T¯) 9?‘şU\½Ò.`“¼¶‚7qÊ¶ì }Nj'&×1u/ì­m=·Âù˜Ì/&©q ŒõQ¶0ä+lÍjÿ Ù+¨~Â/vÔ.”ÎŞˆ03×¥m¯_ñkåŒsX-+Ş|Ö+T“ç^'€+'­%¨u“Ö³ŠÀ4Á@qéJEæ“š"ñTO)zã^êhgœf”¦9¥/Z ƒ8¬ŒVR—Öœ!x ‚hª8äôª!UÏˆzĞ@ç4¬šåd VDƒ9¦å‰Å†ph4„)Ÿµ;K"G4â; y#£U±€OĞS˜—‚ÅIGn€1E[uóô¡õ9¾s+*FÌO¦|Ä»†ÒâY9fäbØF	>çùWÓ‰w1éíÖÓº:áò<±ù{qY¸1ª„Œÿ :àù7VGWŠqs¦´>5½–2½×Ö¥{/5Ì×`\J³ ¨Sq¸wrã¿v~¹éRúX»µ¸wºüA2¿Ï¥r6«®#¼d'u·>äÖˆ–ê³i›#2øšychîæèD*<üËqS°m[å&( >ØÉşR²¦úz-íö­rŒª¢ôÏ ÇçLoVâ9wxHõ¬hWR÷¼¥@ù‹¦Ulq€vÿ *6·pÿ 8¢(P€eH ÑSNc½¶»V’9[O*ÍƒÏ§­buƒ!Õ™ßàŒT,–‰ìc'k©©[(#Xù`àôóYj,âÒöM5ÚDRÄôÈ}èŸÒeSzÛF_9Ï?jÌVÈI%Ò€¼í+Î~ü}ëÆ.2FG‚äıê.2«nøp÷?­d‘B±»Ÿ€}ÅlŒ¢ü@Ÿ4ğª÷óg»ªÛ¨]Kgøm&½lãº7×ÆÊ?Zõxæ°Œo“ÕÚ­Nm³z­ı¿3YÚÉpƒÜQKmÇ¡Æ>ô8õMRâ5dÑ$·'>»˜Ôüê7´ã]½ìæ­nºm“w¶’ 	|å‰(F îzıëT­EtVC•# :Á?•URÃ´:®‰b‹¨[éÇ3A‘ÈÛøNì`}0xê)İ–‰«ÙÆ"ÄM9f6ò;“ìÒLøòã '÷cŞ²=*´KÇÎuëõöHíÀıb5“Ù˜fÖ/uÏ÷…ì‘úeE0M¶¾òk=ÄÚÚM4–ÖÎ%¤rÙ?iGOô}y©‰.¢·ŞÊ‘n`‹½€Ë€{Õ'³™Ğ¢ÑK_ªÌa¾¹Üo®^@²¤şûîŞ£{o¡Y¥Î›kaÙ+YÄ’<Ñ\Ei
ë…7?yİùƒ×Ê˜^uÕé42K{©Ú[,cs	fU8úf„½ºìóÊÑ®¹§»€	T¹F<çìj-£“´}š'HM2;ËvU¸¹ rD,röNŞiÅ¼×öšÇô­İœöö·ék4
Ï!,¯„Ï„—‘N3ÑO8DöÃK@14²X­¥“ş5_Õ>*hZhì`êêî&ßØÜc(q³7UÎÖénãß“o‘xÙ	û)¦£c4×Ú]ÄI¿åîIÈcFêqŸr§èPU»5ñwAºĞ,¦’mFyÖ6¸’=*î@­´n,Â":çœÔö•ñFÖ,âº³WšÚU£D½
ãÈ‚aä{Ô…g.ŸÖ®…Q'‘âpFÖGràÔmİ·ŸîĞt/QĞû›X;½=#HÔË)F‹nAÚ6»:·‘éA'nôÈ#i%‡U5g}í@äÅE¹ÒÊ+•¿d4ºmÊ>¦1Ií™{}¨YÏl±È–ĞNé¯…7`‹# xÎ|)0j×º}¬6ĞögTxáEvÍkĞ³Jd?ùÙÈÿ Îëº}¹şì÷~LA©­7R³Õ¢Y]Áy	ÿ I×ó¸˜•RARG ù{P¿Éí2êö;Ù4ûW¼F—ïƒ “ñr%¸ãŠkj‡‚zÔ¤`U·„|QŒb³IİOI(zÖIô¤n¯;mÉ¯ş<^O„ı¨pvî²x³œ~/ó¯³³‡}¬¥Aààƒ]áûTKÿ È¼…¶‰Lä{Ê•óö-9­Ôww…	?„`È×•ò¯çÜ3ñ:h÷à“êqS:3-×zƒp@?zª›Èåe;&àò¦­]Ü-ÀuØCªc çÜzõ®yZeÑR(‰uNyüÍM4Ÿ,óì%€SSÏŞ£®íÿ ¬ê¼D=NştëU
×7]Úƒ°! p›3^ÎÈn;;"2ªÇ$Í'‹;‰ÿ ±O>U/İZB í8òµ İˆ´[xä–ÓÎO<T®Ÿyo.¬¡|g qïS²ÓÖ—É,§tq3‘†ÉÃ=ïl)–%#ÕğÙ5é¬ä¸V!QO™e¢/m%>ãU=©MRæM:òF„b—#Å»@ût¬¥±¤lx.Ÿ¸üC¼Œ2·×SkI†ŞöÜø¼åPÃ;Ãj¢1°ÿ uŠÿ BCğ{‰şir\ïZ{ƒûµÚ1Şq“WÜõ5Hø&#t“!³	ÏúV«¡5éaë_,ç4–<W³šI85¡<Oôœ×•³^$A³ƒi`zRTãÎ”M2iö¿7ó_/Íco°oÇ¦î´ä6) }éC¨¦‚íÃ÷h;`¼Ç©ûõúÑsïH¯Í0(4E4 |©c¯)”ŠP9¡©ŠP4ÈQŠÎ3H–:ĞB¤y§Ö±íúÓH¹©~)ÄÓËqƒOPÓhE:^•I¥Ö¬ŠÎ)¥€ æçwJœH4'í—3¯ÂnƒÅq}
ãŸ,·—Ò¸?|ğ¶âÒ¢ãÍwá]¡ûr\Ü7a{?mnÛYõç¨XØø«‹Q½·VY­K¨ãpæs÷›¿Škc™£*ÈQıÃ?•[û!9h¤.¹Ä½Î ûU#cs*–‡ûÍŒÖÃì[Gop’9`€À°Ñåz8$¼Ôu)’-²ÆÀñÀÀËô¡ê–ki{v¨Î¦Xñgšªiı Ôÿ ›£ZÇ0IoRÃ ó§zÔvrÏe"³N$aT*dc?z4ªz2#èÖX`²Å…'ñÁÿ 
z¶Q3H3 ÃÃN†ZöÜ(ŒG;20üsOo,ÅË¤©½C Î©æ™QèJ‹€ëĞàäšÆGªuò?¯5«[AVš1âá[Ü+ö¤Ç¾¥TôÜXüY¬T‘ši`ŒçhÏ—¤Ã{/tCàÆNy_OáM¿|‹µ¦YOM¸ÁÅd©+‚vät]ümß	´È;šàçÿ Æz¸j•ğ=6ü!ìöFnyÿ óUÑzxzÆT–È¨ıG^°ÒRîò$+¿c·ˆ/›cÈ^”!®÷ÜE§ßÊØÎŞã»ı\¨ıjµÚ‹ù­­%†ò5i„Ïán6Ä˜Ë°ğ!PqÁ+ïZÙ_# äA¬îÍBYÜkvêM­€?„I;;äJ„–~¸¡Øh¥©->¸×R6wH`Ú~Cløh5…yQâ¡ÆpäîÕï™sø@‰Q?­,vrÓoKÉ3Ô5ôÄ¶üS uNoé[k.…ŒKº¹º*§jSÅÀŞKsÎ„‚{ÙÕ¸x_X´ŠU€–@Æqá-€Ü‘Ó=E&~ÁèW1Ü¾™oó	ş•PnapÇÏ:ò<±No{3mw4n‡åU ’ P¥K4l|S#ƒL‚·íÎ‘z…­¥ëoâX,æ‘—œr	ÇŸ:ksÚûşöE±Ñ..‘O<sÀ[ìĞãõ©;.ñu¼½½Šá£‰¢E‚ÜÅÄ[.Ùü#Æ2}x˜ûS
íïhÄßÌHÖ«+C"‹ˆ—ac,\¸89ğp2sÅOXv£R¼šKfìõÍ½äJ­$RÜÃ€8`ÁA!†p?	â§â´‚85‰r.›FÖ,rÙù9õÉ¡iÚ%–’Îm-Öpˆ$ğ3…èN àdÓ#1®ºå4ke>’ßãøFj{îÛ|æ¡y•g"ZJ‰šß²‹ˆûµgÆaÃ1,@lŒÇ÷³{Å)V˜R{îÕÚİÁó¶*o÷Äêš¤„Dø,›so…U—ÉÚzÔwfu}fÎÙ/¯"ÓãÂ‹wùí~|´ËÄˆŒî Ğã ŠÙ!N}ëkÎgXLÃ Q¸­2k'[×­õù¾O†Ü!ïâM^çtÑ6vF 3e[+³ ``S¶«Û	ukKí>Â9Í’Ù‹G¹C¯åK·9 ë€gŸÊ¤!4Ñj•kµ-U¹[­/[»³ŠÕf•e{&df}©·l€Øaƒ@ÀÔ¡ñ9,‘Lšhm„ebÚL®*3AÈ“çVô†'ÉdV'ÜÎGäiÙ"28¬0U†AôËhNÎö¾ÃUx,Zå—S0«´sÛKoßp74]â.õ÷\ã#8ÍXˆò¦éqA¨År˜Há·ùh EQr	Ç§áQj¸¤“¶…*ñF‡' ÒBı¼µmcíÈmn¥%N6àF?óW#Ãw#åâ”şp>¦ºŸöëŠãRí/fí tS”’x²O‰ñÿ W*V€³¼M2Ÿ?!^_-—:ïãŸ„ìT¸h?y<"èu"3V^ÉÜ­î”æŞÙm%fI#<¶MTc·¸R²ˆAÁOÓdìF~jñä!Ú²ä/¥c¹úJ²³Ê×¶ìò–b¬™=OCÏæiÎ¥âìë´X2Ú’G¯„ÿ 0?ZjI,ÛÔ€xNxóşT{9–ë‹ic%ä6ì’ÁşT3F[´Vº…¥ÓgåîWº,:nê§óÈûÓéï…³ì$Œ®9â‡k§Ë9ôÜ,onåT·±Ê=8ü¨–·’I™KFã*T*Œp|½sL%æ³yš<º><ØåN¡ÓÆ¨ğçn\`
KG,j¦9	Íd³|ªX`]Øş5ÌĞ	4LnTç z´ıŞÜ×):*•ÈüóS‰,‰j$YUŠpr¹şø¦:«+”¸…1Èx›“ö<P'ğj–øIÙÈó»3–õş±%[V>1o…™bÙ)?‡ş±'¥Y×«‡¬e¦qçI'>Õâp9¤–­kJÃóI^I¦©g­µæ“û¼ı)×¥(PÕ²9ëJBD
R·4>|ÿ *ÈÏ•2MhKDST)b†4°r}¨!Tf–a8ÅGQÚ9§è:S[E f§’uÆ(Á¹¦¨ôUji}guAjXC”±šËÂ‘04uMÔ~ØwI?Å;8Œ.İÆ÷‘¸eÜãqZRr€dš !Âî9­‘ûU]ïzÚÜ-c†<§$íN1×©ıkSBé#Û¦Ù<<}ëÉÏ¼«¯'¸nûÅx³ÉŞ:}fĞ‹-zÎD…vO`;_éïúS‡Ò..p„K^ğ`ƒ÷¥jSÇlšÀƒlhDR6Â‹#øãó¨Š©Ë”-%ÆøÂ!çj•Fùy4íM dÜ¸êc©÷ÅJ5¨{]Ógi@w>ÿ J°µùÛBÁÜ*#·w†Æ×4$ù¿¨k1]¸&)O‘=TŸÖ˜ëº®¥?ËH$„9Vç' gïúÑôæmwJ1»˜åÆ¼Õ‡_ÈŠ´†ÛV´†w¾‰%²E‘ÃƒĞ-Ñ1e%ÍÂ¤#(Ç•³ÇÛò¬>ª “FĞ±şËÇıâ©b4óşiìİº¼cş
xR´Èoz€U.•\mÙÄ×íÑf—kmBäÕÀ\ÉúQN¶šF&çÉVÇÛü*gÚ[¦é¶ÏÔ£ì$¿áû§Vı¥±·fæK:Lr®¤ƒœ‚TÑÜÛ°şC·ÃÎÅ`‹˜×™œÿ :˜5ğÖâ;Ÿ†=™š)¾b6ÊËıïŞ7Ò¦.Q†G˜)Ø²1U-ä	 àgÏé^Ç¤aû¯O?q’ÎKmA’qä™¨½/WŸPšé&³6¢  c’3µ€à0I ‘âõ ÛKÚEB.,´¹<¯%LLçú
Õí5Û‹ùVÆÊŞÃR“aù¸QÚ>€o‘÷ p ciV< æ´ªs“È¢@ŞvrçU*/uI§ú;8ÄHßí+=>Î…c—R¿š5T,!G§î•)ÂLK¸£eAÁa–ET¬ûBtƒ	×u«KµÈ{ycˆA9Ç=Ó€å‚îßÈ©cÙ9Î&Š[¥şíÕÌ“/äìE2¸øiÙ›‹•¸m&uu³îÆUHCŒ`ùÕ?ô­Ùé$’;[¯–?ÅecaôÏÛ=(v?ôË˜~b[Køí%ob²šXzãÁ2¤# Œf¬ğéöñ]µÊDcÄXª¤•bÍùšiuÙ=.öIÙàtç¾Hg’$”	uV‰óÈæ‚CêŞúH-´;»•†8å‘¥’;VÚìÀ2çğ¤Tj¯5tšÎjKŒ«K-²ƒùJN=ñÏ•<—@·—X²Ô´3ÚÆğa?‘¶Óô*¤.}MJ¨¨”$ø—¨Éú:Á¢i8¶ß3È¬§iWXãr2AÁÆ<ºã*nŞv¹n?bc¶²yÒ¾¹Õ•#%˜*¢6H <ójïa¦YéÖß/km½¾ænê4
¹f,ÇÔ’~ôâæÎËv‚dßc+’:â€‰KÓ8çJÒ—Óÿ YHıAÔâíuİ´Ik›fë"³º_¹ŞkÍ¹ÆsÔrFA´(ãÒŠƒzÕ¦ö‹´/—šÒÎ§%a–mP²“ŒîE¶*Ç Åê)WW»·½°V]0ÛNíwWM¹[iefcn@½9,*ÜÚ=Àì-^pâA+B¥ƒœg>õ(ª À„í®[¶°Ño{«‹=2æh¦6¿+%Ò;íâıÊ‚ÃpSıœƒøzÔ–‹ñCP¸†GÔ{=5´ìç»±†xÍÊ¯–èİ•²zğ<ê÷`ã€qÓŠy[†<ª“µ²5]jy»şÏjmo<léonÛ«•ÚÀ];gŒà/B˜ÍÂÔØc¿¶^Œ÷}Äh¿Wd
>¹Åû§^Ü´íÏ!ËËi<–îücÄÑ²–ãÖ¬šN—lDedLàÏ3ÌüœòÎK¹§¤Zg¦İÙjˆÏgwÚ!ÚÍÀ>‡¤Ü)ÃYB·ãºNü!A&ßÒA#>™ˆ«HØIlZ/tˆ2TRøŞêO	Å ù»ûDÜ¯=®¸X£‘Ê¨Úş/j§µjÖ¹H®7~ñ<‰÷©]§|Zí‹üÊ•]Vâ=­Á;d+Æ~•Qş™™Â3mT¤AûW“–6Û]Òê,Së±K"ÄŒT.>«ƒRœ¶÷}‰…†ìÃ¾ÒKoe9ÉçƒÅCÍt³Ã¶xÑ½ÊóSZ¤WÚT‘I±P¯ØzV}C«HGºÒ-¥BY@î+Æµ$m+^´*ÉÜLøpK.L×»#v·Z"Ã#†X7FŒm Ÿn•Ú=ÒZÈáËËo‰£$àåOO¸¥I?cw®§u¯v²şû®s }ÿ @ö“JÔ¢Õd“J¹[ÌŒœğıŸ°?zv÷‹*Ú^Á™=²„sïĞş•e·»»É‘—<ñÎhM2ÕtØn3°™\áGSœtÅE_i—VRªwmw (Sœ{úRlµG¸Hã{³¹I=ÔÜ8>cëô§÷:„³B¨ÖåÀ`8>6®	k¯AEsoiÜÌ¬»IhQ¼KSPú˜K“óŞ9NvŠ[<úù
=Æi¨Fâ=èPõ=~€ßJhöR¢(I_»–q¸çZNƒ´>®Ï„ı–çåIüØš±*á¾"øaÙt|’Àc9©–c^¾±Í|–_ŠÁnx¨íWS].Ú9Y›ç†  ã™$Tí»?j3ßE£™'¾’'™F8Ú¥Açêâ¬ŒÖsŸ:FáœV7™¦E†æ–9ëB$P¯eW{e‰å^BÌå>@$~F©'€ğ)`â m»S¤'P‹ä¸îçŞ$·“=6È:g#‚“¬7ÄÍGx-]°ù‚3°\)÷Ç¯¼SšXÕtvçGù…%¹šVNğ,3É•Î7¨xÏ
Ûâ•õÑ¶³±ÔîgÄmhĞ3 pJ‰vdgÌqTÅe ^¾ìŞ¨G¯ykÿ >£¯>""]Q‰†&–CvŒ6–FÁœ¾†™.ÉÅxªm§m.îçhíôYeU]ÆQ:÷@`óƒ)œÆiÖŸÚİKR-Üvví•yŞî#R=A|ûf™-È<Í5LÕ;aªéf–¼f™¶«Kwn‘è_¼8'<sQÚ¯Å+İâÎÓPĞ§°º‘DÒ+\Û6L€íıöO€¿—
¨ZmxêFÚj™¥|GÑõ–8RöY" ÈYÉpPã=Ğlgò5/§|Dìôè\ß5º’÷vÒÀ dŒæEŸcéMp·ˆdyTŒCL¶ø©Ø¶»Õ{W¢›‡8X¾~-Çíº®‰‚ƒÎj£;şÙzGJ!éB~)¥†|
cw'†|Jq#`TuôÛ!v8ÔÕGÊNİév×ı¸í%ë»›Q¸“aÉİºV'ãÎ¨Óèq<¸µe‰‰Áñl?¥YïÒæóS¸”3¼ŒùGÏSŸ1ü+İÄÒŞäÇıÇ^¯<ÿ 
ò~Îİy§Ûeõ)XB¸>İıjı¢Eq2˜dÜ‡W9$cÂ¨7[- ™¢Ä+°’bs‚1íáW­&ê&6ê$¼·)ûÆèF•eL
åt­KP‡i@vÊpzÌf«²I,„êê2N|Ï<TUó½¦·å†ÉÔÆ[v7‘È zu£]¹¸IlË ÀÏPO­!£nÈ ŠÛPÓår¼… n|dö8ûU£KÔ’ÎĞ[L#y cm£œ<½*›etšghñ(gùÈ|GËzF>Ù©fÈ]]¬A$±” Øæ©5?:Yj(°=ºG È$gO—‘¦Í¢Ëoõ[¹!ÉYÎõüÏ#ó©{ë[@PŒŒŒòr@¦·PÏ‘ËÆÇ€zûu¯+§mEF.î.ÄRÙ;0`°êO§?Î(¥a€OFEp@âšÅ©_[#(†HQ¼"Dä}ø ÛØŞÎ%½KI1’n"l¾~F´û’Öü;±q˜;Ù´ô±øfœ^ê°XH©(˜³î	$š©¦½ˆS`;,¬K7ôl-ÔøZ–n{xzÇ%ò¥ö›]g›I¹µÓ5-F+K£4–ĞÙJ²?îT®õU8,$tÎr0[[ö—S½í•nÌêVjÚ}ÈEº–Ü3bH2HYN È÷ç¥^·{Rq™VVD2 *®G‰AÆ@>ø­! f=®–ü4)¢ÛØàf9^Y&ÏŸ úSÑi­0Ãj6Qƒÿ ÑÙ6áô&B?J•İ•ìâ˜F
á€ïµ­BU=Pcb±†(vWO“=ïÍÜrRâöiÿ ºÎWô©1Å(J¤ª:/ÃM>ÎÎÖŞêÓLdµ!PZØ¢4Š§Á½ÎIà)8ÆNs‘ÖOLìÕí½ıôóêLª÷2Il DÊDëÚÛ”ò
¡÷ÀBA¢†ªyÙ{™áh"ÔÇpÌdÍÜ=ì±9êÑ¸eÛœaÉÛá ÚvwT˜jw0.œ—F[)„eçTl6Cîğ²1u©à)ç$U•ç=(Ê3U ËÓ5/gçyïD7©ã÷’Âğnum¡Iƒ¿„C`äûT²
2
i¨/±:.™su41q9¸Ù"†Hœ»»„Ä¬Ìzš’Ñ»;g¢¼¯C½‘äc+¸rûAòPN ôÒŸD9ˆfªÁ¹ÓP
I£ r9ë•ü-şğ4;~ÆÙ6Ÿ¥ÃKs}â…”®
¸ ® À c¥¢8©"éUiœ:,‹¬YŞÇ&B[=´»¿‚T«p1U¸ãñŸLV;5Øc é¶vpë7û ¶‚Ù°"ñˆãT%.vçº±#­OÁÅ>‰)¢ÔMŸea´Õf–5k¨\Ás™H¤ì“-œ’•·dácÇá§äfŠˆ~[O‡OŒw¶å¤ïGƒö©hÀ£Š¤ZI 
o+S†i)Í"†Ò“P=§»6¥pHUŠÚIzaIÍNÉÁªWÅË–´øgÚ©Ûİé—Ÿ¤lj2ê4ÇËæLÒ[Ç.øVSş‰THÒMòƒÊ²ÿ PZ6¸ü1¥HZhÉ$J+ä~üñ^3¸{›iµˆŠïçÒàn<ğp9`Ñ5±
Ë¾26xñ:§]í®QĞí™YX0$ AûÔ÷f%WÒ&i0ÒÆIÉä†ãéÒ¢ïgÖ’¬…;è&9=Ä‰‡Ç‡ óşõdï'$† 1€ıŠsÚ{Aye#¬	õÈlàcî*ÚNùbœx’DñíFÊöŠfmoPÖÓ <7éš¼Ùİ-ÔˆûÏ-Ì¹ª~§mí³Û–^îD#Åôâ¥»3}5Æ‹lëp„íÚÜy—µ+–§efÓík{(Ş²q±ŸÌP.5…J:mÇŒŠ=Æ©!˜F1Æ9Lãî:××¿
ÌœÕOyòº´½ôr*«89ôÅOézåÈ€Z	ŠÚa”† ¤rÆGçUçÑËmeI=pE+ån!U@;Ç=8`(³}ÇdöiD}ìÚ®v2Ûõÿ 6´ñ›4Ó³ÊÑöC³*À†M¦àzç¹ZrÆ½ì}cöÆy¬œ:¬öî¯u+{1k~ö1 G±“ºi[$ß¹p7Oƒ¨Ü)Z&™ Îò=¦™–ï‰ZxÉvå¿ÚƒëW	e­{9<Tçic°Õ7wÚe¥² {uûçöØpŸ<¶}9ÿ )­XâËœşœ›[èåBşµp%ËsQ:õÂÜ…µ±ÚG*Å5Ì“lä°\F äÎv0	9 gVÔeºÑgGò73Ä‰÷*ÎJ¬Ë¢v†ã´‡d‘iQLî9 ”ÜG *8(Ñªå†Öyòi¥|¿Õ­4°†êâ87œ cË@:Ÿµob6¢åŸºƒ‹J
`{†Æ>õMÕ;ÚZÉ¢¸í$,¬’ÄÃ»]êÁ†í¯‚	9ØRl;¬‹qn·§Oˆ·xùŠÚDï=V1ã	pG¥Q-Vı®Ò.%+{Ô¼.Ásh¦uRznd(÷83óQÇ<p³,ŠÌ‹ê3üEU¬û$z‹É>­©ŞZÉ²_Ë,£Ê#* aÔ T<F“Ú/‡qjñÚü¥ÕÔSG/î/®%ıÛ##…Î¸yeFxª%¾ûy¼Yã1äÛÆ ù‚?1RkëŸ„zTúÜwĞÅj°Çq­•Í·}åß—+¸Øeô9âCÿ F–Âõe‚!b1xfÒ"tß
ãkŒŒ>\Õ'¥âİÒNQ•€$eNyOa\œUCGøm¢ÚÉs5Ş•¤ÜË9\¬:rGà`aNãŸSŸ!éSĞvB$Ò-aoXcÿ ÃŠ¤ÚMÇi.t›˜â¾†Ê%9ù¶ä3`à˜À$`“Ó:¸Ú"+#FAMºø}_Û]Z¡İÆñ4GUºˆaŠ‚®zlésíMçøDZØ5¾£u¢a7ËÃw"G®7+?xCåó¿í¢“ÓfB¸îWf
ÁŠ¬ägìAûÖµ´ì?j´ÉVâ×_–êåÀËŞÈôØÀF§ê,Øq§£CíÆ¿ª*İØ3¿I¥€Ü7s9erBŒáêwÀÅRkd ¥îâªú†™«\ZZ…ÓtÉÑƒ¤?0Â7 ®Óƒ<²¦‹©›û],®bL"WV(Tà«ÙÈ ƒŸ1MšAš›ÉFcÇB>´ÚVô¥NÌqš×.ÅŸÂ>ÔÈäm6mì.?ZØ’“ÍiÏÚ¯QLøÚ9ä”Wo®fAüëK¬+\;Ê8l‹iI-d)ÏçQrXŒİÉx›k)]»Æ ğ|ÿ *k§e¨mÅ×É6sÏ
HŒÁÕ$·ñ$©;õû~xşáíèi6º>ùdŠh”®æbUÆXÈâ¥;0V8/í®Şñ¸`H#¯§½F^ê?9ÜÀcî¤iNFw
u J©­]ÆÌP²”'ÓÛÏÂ(–Ò²E‚şñÖÒÔ”-İ¸
O>cŠŒĞÕg´02¬kk3Æ~™Êõÿ TŠ—…c}1­ÎüE#ãœrGüB¡´›Ç´¶ñ€Ë*$£+çÊœçè(Hº²EI— *‚@ò óúTE®¦ú,—Vètf2 .:0ÏÜší‚jzfĞf¶“i$M‚ü*¯kØÈn­Ñå»º’A”f‘É<}‡SZ*Ú×Éaœ®éÏÀ<}ê:IZ4yÇSÈóâ˜Äó2;wÄ ãr}ºĞ“P[f\GmyÒ:S¶·RM$$s–ëD[ŒİÆc“¸”ğûg¥4kÛylÄˆÙq0F|é©•î§Ho6ÒÚVlĞösH
ãG§@) ±9â£ÌšÕÑ
YYƒş‘åyˆú UüU#c‡EÒŸÃc üVGZ÷qñ•t›ùrnu™FO1ÚB‘¦=<AÛòjooØ½÷u³]÷­¾OKÍ€2DŒÃ8 } ©¦ñÏ5†]µp‰´³·Ó¢îí`Šİ?»¥½9æ’Ö3*¶ˆ¼PG4Tàæ¨†J2¶G%äQPñN$UãGQA^ ô§â®%$J{äâ
ÓèS¥\E¹#`Sx#©cªEéO¢\bƒ`
{U&ñšr¢‡Å
h¦·ºM–¦U®­£™ÓğHËãO£uj%¥”|Õ¼b4ÉcÉ$“Ô’y'ëF¬1Å2
CMdó§r)´´©Ãykÿ nAí>^D¯³æ/-â?gİÿ –ºV­rÿ íépán“ı½æ¨3ˆä5‡/¥mÇíÜÊ pá?OÏzP²²cË†‰2²¸fn9f¦Ì‘Îry>UâÜez;§Öó-ßwzbËíîÎ@ßûÔÅ»^' íxğ}r§ûÆ«Útêo FB„¾ŞÆ¬WHîÅ·`™6sÓéFµWM9Vsp­q½%e!PF1ôİ®ŸÚ{e!%I"Ü:g‚?á5;§”QÂÙ’[;Š¾Xóâ¡;k»Okk­ÅD¡Æ?
çiı	¢Só\EÜ’Àäøp|üÿ ‰¨o™XÙ¶\4@ÛTSÉ.ùÆ@bí+¿Aô¨©ä‚96ªËÁóÉõ£z	ÆŠt˜ÆY&_6_	üøĞËÛWU†sö§–òE+…+”ÈÊœç×q@A$#2&ßî“Ò¸c§Ë)‚NŞIÎA<})À¹’7È*r¸ñõ?z
;‚3•SÇˆd“íJÂîË©*NA“L;zİ¿õnŸ»ƒò±dzxE °¢2m´³?«Çÿ ¡¯nxq½œÒKóY$
3Í\¦XlšP ıh](Ÿ: :ŠQëA‘ÅI'•P†ŒôeëBN™£§•TM1´ê!Í!´é5¤M9…sŠ’·8Ç4ÎİjNŞ.TgNmãã¥HÂ˜ãxø!x5Qh£Ç•:‰zPâá8ª@ˆ(”„8¥ƒOe^¤9¥ĞØâ™æšÈM8æ›HjNM\§ûuÜÃşLvvÖLøîŞE#œB3ÿ Å]S)É"¹öë»)?d­ÒH±¶æGI?µÌXÇë\Ü÷\uÑÅí‘-´3Û±ØİøèSÏO:NéGßáTàÔÁÕÁf›†
9Gı)w3[›W1Ü’´1Qş5ãmŞH–;Û`yï—¹cRÚÃ­RpB´R!ã#4ÛS[t¹Ó»•Ã€ÎG xäSÍz6:eÂ4{ BÙ>£Îœ©±a³%æÓäïƒeö˜˜uuıi=£ÒæÔáÔ#iîÂ&gÔÒÂñ$ÑÔîäğ°u'“ÅLêWİäEŒÜ¥w©ÚzÏ§ÔÔ~Ò‹Ò¯KiÖ®O¹ c'ŒÖdh÷x‚gİ¦™$èë(&'’,‘Ğ+œ~”ø\íÏáçqE	¨d13HªWËx'×ùV.5IQ£Çwlß„íœ`¦?Zd—ÁlgLnÒ²y|F<øHéŸ>BòûDŠ:íşUË¤!’3âehs¸ÿ 9’Ù!Çöª2Ú\¦½ºã uÜ÷¢%ü¶ä"DS‘¼zƒOAİ*UmÇ÷aAúSBiåÿ eQúS6öc–0HÅ ’8¯s^n*á¼§“šğ|ñI5ìóÒª”Š2cÊ›#âsÇ4öz:C‘Šyxš²EH¤Å\í78§0/­²'@¢ÛÈ’?…úV¬ô‘µ‹Ú¥íbÅ2³‹ TÅ¼8®2£ÃJyô¡ Àâ1ïVËc À¢´%<RÇJ*Ò†)
iTDñBsÍ,šQ°‡Šk)§œm)À¡FS°Å_·D¡ûMÙ´ÆJ[LÄÏ‰”å®ÓŸ¡ó®ı¸.âo‰:LÇw¦«îãtÿ ñÇòoüuÑÃîæÛ„Î¯¤g‰ã3.ÒÁHäS”/:4[”¾ EKb‡k¦~•ämß¤TóÍÜ[ğÈ7œñ€9«KÏÍ±EL–ŒóõÍV%·îõX—&Del.|öš´cHBğTe}éøM;ìÛ;é1ç!B‚WÌpju‚›(dß+ 5_ìóÇ,f27aä\7PwTºK:|ˆ¬NÉAü*ÿ 5Oí(s­¬Û:ì!ÖXÔñÁ§Ü}*E3îØÇß5ó.¥ãÉ²0ç„ş¦™ˆëçÏâ§Rn§f€Ëcß şÜŸ®ÓƒK‹´–&Ö“º“¦ÉFÆb*îgŠk9	cmÀn”€sÿ ûçMîôKY¢YLV÷JÙVV]à3æ1Éô>UÍÕmºŒ³¿uŒr¦8W8'Û"¼ÓD»ËD@ÊøN3é‘ÓÎ«—ı˜H0-àù\°1\’=¸Á¥zÓMÖÅ²¿{Ğ"Nÿ ÏÕL{=¾êãmëŒğ0)ƒ?Z¬ó7³b£º×«0k`RˆÀ¤T­x‘Àæ²F>´)h·J“d\ÉÈÇnä•ê(*<ë\%”E˜òa—&««‹íuñ^%²ì)M®Ç³Â@¢ûcñR6ıâšn RbÓ®õOúDû˜8´ >à×>?Èa.¤Ûérş›ù_Úœ¶ufÿ ÷n¥¹ÖAÅŸj›Ğ\Ü²·ZæNÉüPkù{£t.cÛ('‘×Ïšè?‡úÜ7PÇ—Ëõø¹på›‘ù?Ã&Ô±‡©ÅJD˜¦z~$‰HéRIuÈóm)8¢©¡6#PXœdzœ
 43O°}(KÍ,*šP9¡+RÇ4DâÆ³HsAƒ!Îi¬‡­8àS9›ŠLÀ_=¿mM¥øÉ$!Kw0¡#Ë—oàÂ¾NÀçÒ¾uşÖwi'Ç=z0yHíãlOîPÿ :âù>®f›µ–i%êsŒS›ËÕ<E#Îà1ö¦ıÜlsôó Êª€ÕKznÇñâ¼«¸ˆ®Z}N=ÁÍ°ÈÇO×š•·^ö-¡·…%¹õªÓ]Iµ§ìC‘™H#;‡ÏQV­93,¾"£w ¹ªÒ/Löjë¹»¹WI“pª¿Î¥m.šâêş'AÜ® /‘Ï¶0jÅJëw§*=¿éÒ¤bSm­:Kp ƒ×¼ÔÒFkÄÚÏ¥İ&Hè'¶*OæE>‘IsĞTgkYSG˜´£«l6súTd
Á™ çíEîeÜÏiy7í›%wDŠÁ‡'ÔqŸãíPW¾ºW6=Ç|åBÅˆ„)–ÆzıG­<»ÖÉcÂHÆ	<c¯Z‹Õ,ny•"YòW}=ëÏÇsÅtŞü™Â·ÖÌ-náŞ¶e
Ÿç ƒ}©ı“ü¥âFñMn•fuŞ88ê2*fMnÍ¬wºŒmáu ÂqÃN=r|0¸XÚ(íÏy9ß‡«/vsÓpÀ'<VØçº›‹¹5…ş½6?½Qø9©['P˜yn¦˜ë±Ğ[sXÛFÙMgfĞ;9¡ºäúŠt@Ç=*.öè+VYç1°ÇdŞ]Ú«ºµéî$,|9¹¸ï_ØTuÅ±¼€õâ¼î\ÿ ^·ÄÆNI·?öŞbg“ ‚dn_:Ñı©›ùqÅu×m{e%³˜ˆ'¤Š9Sê?À×?v—áõª^4‹re;³³ãŞ¼ş/¬ËËô«ü·.å€ö
õíb$1ÇıøWLü4íCÃœô®q´´M>Á‚  5¹¾æk{5©¯O+‡qùçò7\şÎÉì¾¦n-#>Df­pË¸
¡v5;(”õW[Sáë_Q…Ş3oŠÎwR!« ‚h
M+v)ÖCä
ğ¡æ”‚Xn( ÒÁâ‚,µ!Î+Ù¤9¤•¹¦S6A§2·Zg;dP¨¹sĞ
ù©ûNêëã—kAlâhgÚÁ¥}+”f¾\|}½k¯Œ½³pASš1ôVÛü«‹äúÈêàöªKN›s±†PhK9ÎN9¡£•Á;“Ğ¯"†Q’¬ÙÎÒ½>õæ»DŠÜJ$d¬LFŞ|Ö¦t©&È[yİ’ç™òê*6Ùã{Õ›s4$ğ Ï#>~TîÒWßq$„(gO!ÿ ~Ô&ÄL]£f,»;•`©;›œSİBW†÷OvfOFÁÏÛŸjR[µbYûÛy/¸ ‘G¼”€¼'áD€çhÎ	÷ôó©¤Œí4†kÌåU¢`sÇFiZ#›­.İÎO€ç¥/X‹Àdº<yyyTWeç2èĞ3–vèIëš©Ü­*ŞF»Ş5rr<kz‘œ}hvZœPß«©>Ê|Ên=zñÓøSËëéklûé6¾3Ï„+ÚBÚ¼Ñ´a€›»õÇÚ¼ßût÷O©Ä92HK70Xû§Û9¦W–ğµÄÇe0ÜÜç'9~T(Dréæq
G<NQd*qïÎ)´­*«·\©êOSïO0Wyj‘ÿ _”ŸZk°qš}«q}/Ö™gŠö6Æ0O5é
Ab(39TÈëYå›cÉ»¸ÙòªåİÉ™Ï¥>Ôd`¡sÁëQWY\«·d„HB©'ŠM”©wTg9MçzÔ~¯;Çm8éSVêŒ—–ï<QaŸ.•Çò2Ö2=‹íÉTí?v®!	rGb¸ÜGû¤VŸí5´bf¥$UR+jÏ¼×7ò°ÆèÄ†vŸÏ5§ş!k·/©…;éœs\¸OÉİ–Vt­ŞmÖãéÅmÿ „à<ºz™£¯®à?€ }kv|%có¶\ôa^¯ŞOÊÊ×cvmBÀ1éV{vªİ?ºìƒVHN+ë$Ô|Wu"­Åz‡	ÈüèT™JB&³BiÂ·4@iºQ¡%¡HüQJo'L¥n¢œÈx¦²ò/ ÚF	#8çòcâCÉ¨üBí-ÙoÆ©u!'§2±şuõ‡Ps¬Ì:„$~Uò;µ:j7òïfo™“†ärÆ¸~Mê;8<ÔvÇ…J²‡ÿ XI2 |CôÇZÄw`ÉçœFØ$`õ8¯>»Òg—@¤nòOøT¤LÉu1ß¸ ¾/½Déó·Ï\UWõj‘³Ë^˜òv•$ısBh÷R+kz`>,³&åA#­[ M›%ã r­Cêñª\ZÀ‘£ğJ“{¦º³hİP¹ÈPJŠ!7­²Ê@Ú3Şş#ÏÖ«ºé1²­Ãÿ hq×ØÕŸQ˜´$a@(£‚µİÃˆï®ÀE9“'?ìŠxŠÿÙ                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ÿØÿà JFIF  H H  ÿáExif  MM *           n    	   t       ~       †(       1    8.4 2       ‡i       ¢    Apple iPhone 6     H      H   2015:07:15 14:23:53   ‚š      (‚      0ˆ"       ˆ'             0221      8      L‘     ’ 
     `’      h’ 
     p’ 
     x’       ’	       ’
      €’      ˆ’|   :  ’‘    681 ’’    681       0100               	       À¢       £       ¤        ¤        ¤       ¤        ¤2      Ê¤3      ê¤4    "  ğ          (      2015:07:15 14:23:53 2015:07:15 14:23:53   c   Ó  Ô    1£  	´          S   _Ç5Apple iOS  MM 
  	          .   Œ     h  º  	        	      â  	      Ş  	        
     " 	 	       	           bplist00O n ^ X T Y X ] W V O J G C < 9 6 f i d T F G B G I D = 4 8 2 1 - ` ş õ à Ö È ¬ ¡  u e > : 5 - * b !*++OE;3#ï Ö e /,',Gdf`XPE2i ,&BVoqiaPv ü ø 4anib>‚ 
 ú ó ò ú  ;V]YŒ ş ı ğ ô è ó ü ñ î ú .IM” õ ô ğ ï é è ó æ ç ç ÷ Ç Š ¡ ñ ñ á é à æ é Û Û × å é » @ D ° î ğ å å Ş å ä Õ Ö Ï Ù × À ‰ – À í î í é à ä Û Ø Ö Ó Ğ Í Å ¸ n Ñ ê ï é é İ ß × Ï É Ì È È Æ Ê ¤ æ ï ï î é ä â İ × Ñ Î Ç È Æ É Î y a g n a v s z { s w x u m v x                             bplist00ÔUflagsUvalueUepochYtimescale  ÅE´ ;šÊ #-/8:             	               ?ÿÿÿİ  4èÿÿõZ  ÿÿóE  …   S      S               Apple iPhone 6 back camera 4.15mm f/2.2 ÿâXICC_PROFILE   HLino  mntrRGB XYZ Î  	  1  acspMSFT    IEC sRGB              öÖ     Ó-HP                                                 cprt  P   3desc  „   lwtpt  ğ   bkpt     rXYZ     gXYZ  ,   bXYZ  @   dmnd  T   pdmdd  Ä   ˆvued  L   †view  Ô   $lumi  ø   meas     $tech  0   rTRC  <  gTRC  <  bTRC  <  text    Copyright (c) 1998 Hewlett-Packard Company  desc       sRGB IEC61966-2.1           sRGB IEC61966-2.1                                                  XYZ       óQ    ÌXYZ                 XYZ       o¢  8õ  XYZ       b™  ·…  ÚXYZ       $   „  ¶Ïdesc       IEC http://www.iec.ch           IEC http://www.iec.ch                                              desc       .IEC 61966-2.1 Default RGB colour space - s