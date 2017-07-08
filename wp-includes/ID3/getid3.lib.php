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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      ���� JFIF  H H  ��Exif  MM *           n    	   t       ~       �(       1    8.4 2       ��i       �    Apple iPhone 6     H      H   2015:07:15 14:23:53   ��      (��      0�"       �'        �     0221�      8�      L�     � 
     `�      h� 
     p� 
     x�       �	       �
      ��      ��|   :  ���    681 ��    681 �     0100�       �      	��      ��       �       �        �        �       �        �2      ʤ3      �4    "  �          (      2015:07:15 14:23:53 2015:07:15 14:23:53   c   �  �  �  1�  	�          S   _�5Apple iOS  MM 
  	          .   �     h  �  	        	      �  	      �  	        
     " 	 	       	           bplist00O n ^ X T Y X ] W V O J G C < 9 6 f i d T F G B G I D = 4 8 2 1 - ` � � � � � � � � u e > : 5 - * b !*++OE;3#� � e /,',Gdf`XPE2i ,&BVoqiaPv � � 4anib>� 
 � � � �  ;V]Y� � � � � � � � � � � .IM� � � � � � � � � � � � � � � � � � � � � � � � � � � � @ D � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � n � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � y a g n a v s z { s w x u m v x                             bplist00�UflagsUvalueUepochYtimescale  �E� ;�� #-/8:             	               ?����  4����Z  ����E  �   S      S               Apple iPhone 6 back camera 4.15mm f/2.2 ��XICC_PROFILE   HLino  mntrRGB XYZ �  	  1  acspMSFT    IEC sRGB              ��     �-HP                                                 cprt  P   3desc  �   lwtpt  �   bkpt     rXYZ     gXYZ  ,   bXYZ  @   dmnd  T   pdmdd  �   �vued  L   �view  �   $lumi  �   meas     $tech  0   rTRC  <  gTRC  <  bTRC  <  text    Copyright (c) 1998 Hewlett-Packard Company  desc       sRGB IEC61966-2.1           sRGB IEC61966-2.1                                                  XYZ       �Q    �XYZ                 XYZ       o�  8�  �XYZ       b�  ��  �XYZ       $�  �  ��desc       IEC http://www.iec.ch           IEC http://www.iec.ch                                              desc       .IEC 61966-2.1 Default RGB colour space - sRGB           .IEC 61966-2.1 Default RGB colour space - sRGB                      desc       ,Reference Viewing Condition in IEC61966-2.1           ,Reference Viewing Condition in IEC61966-2.1                          view     �� _. � ��  \�   XYZ      L	V P   W�meas                         �   sig     CRT curv           
     # ( - 2 7 ; @ E J O T Y ^ c h m r w | � � � � � � � � � � � � � � � � � � � � � � � � �%+28>ELRY`gnu|����������������&/8AKT]gqz������������ !-8COZfr~���������� -;HUcq~���������+:IXgw��������'7HYj{�������+=Oat�������2FZn�������		%	:	O	d	y	�	�	�	�	�	�

'
=
T
j
�
�
�
�
�
�"9Qi������*C\u�����&@Zt�����.Id����	%A^z����	&Ca~����1Om����&Ed����#Cc����'Ij����4Vx���&Il����Ae����@e���� Ek���*Qw���;c���*R{���Gp���@j���>i���  A l � � �!!H!u!�!�!�"'"U"�"�"�#
#8#f#�#�#�$$M$|$�$�%	%8%h%�%�%�&'&W&�&�&�''I'z'�'�((?(q(�(�))8)k)�)�**5*h*�*�++6+i+�+�,,9,n,�,�--A-v-�-�..L.�.�.�/$/Z/�/�/�050l0�0�11J1�1�1�2*2c2�2�33F33�3�4+4e4�4�55M5�5�5�676r6�6�7$7`7�7�88P8�8�99B99�9�:6:t:�:�;-;k;�;�<'<e<�<�="=a=�=�> >`>�>�?!?a?�?�@#@d@�@�A)AjA�A�B0BrB�B�C:C}C�DDGD�D�EEUE�E�F"FgF�F�G5G{G�HHKH�H�IIcI�I�J7J}J�KKSK�K�L*LrL�MMJM�M�N%NnN�O OIO�O�P'PqP�QQPQ�Q�R1R|R�SS_S�S�TBT�T�U(UuU�VV\V�V�WDW�W�X/X}X�YYiY�ZZVZ�Z�[E[�[�\5\�\�]']x]�^^l^�__a_�``W`�`�aOa�a�bIb�b�cCc�c�d@d�d�e=e�e�f=f�f�g=g�g�h?h�h�iCi�i�jHj�j�kOk�k�lWl�mm`m�nnkn�ooxo�p+p�p�q:q�q�rKr�ss]s�ttpt�u(u�u�v>v�v�wVw�xxnx�y*y�y�zFz�{{c{�|!|�|�}A}�~~b~�#��G���
�k�͂0����W�������G����r�ׇ;����i�Ή3�����d�ʋ0�����c�ʍ1�����f�Ώ6����n�֑?����z��M��� �����_�ɖ4���
�u���L���$�����h�՛B��������d�Ҟ@��������i�ءG���&����v��V�ǥ8��������n��R�ĩ7�������u��\�ЭD���-������ �u��`�ֲK�³8���%�������y��h��Y�ѹJ�º;���.���!������
�����z���p���g���_���X���Q���K���F���Aǿ�=ȼ�:ɹ�8ʷ�6˶�5̵�5͵�6ζ�7ϸ�9к�<Ѿ�?���D���I���N���U���\���d���l���v��ۀ�܊�ݖ�ޢ�)߯�6��D���S���c���s��������2��F���[���p������(��@���X���r������4���P���m��������8���W���w����)���K���m����ihttp://ns.adobe.com/xap/1.0/ <x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="XMP Core 5.4.0">
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
�� C 


�� C		�� ,," ��              	�� Q 
  !1"AQaq�2��#��$BR�3Cbr������%&��'4STcs���������            �� '       !12"AQa#q���   ? ��X�G���b�P��Pya����Y�(���O;�&R?�XE�M�H6�]��'�ʢ���X��A���85�Goځg�	��%���Eq1M��H����{~xn�
�<��6���G9�]��߯և4/�GB��$�؎j���L�U�%]Z��=֤�;�԰ͷ���n7lV������y��0���P� `1�f�s^��r��Y-�CN���C���� �p)�#�'�E8���Fh��c��L1��)Db� D��-v(�����抽(((��4�P挧ނ�@i��M��*ǎi�](��P���G'�1C�6)��̞��^[��Ze��&*�H^}���5�sXi�:�9���<�V���o���х'�#=�Q�7*ǫpG>U��%ϝh/ڎKI�?�Y��*�fUi[*�8� �X�z�c;�q�K���}tu+���u�#S��Y��mv7xK�G9�TͶ�j�L7�����Λ����v~�WP��cbːs�zי�AݴKienva�d�j+L��g��0E:��R4<K��>��H�&���H��q��S#��{���<l � f�x�7ܤ1��A�����]�= ��h��G$���� �J��㼁�dd �
��43���x�a���_c�4��t���ͻq��}�9�;���c����p���q�,����p2cuݟ��~uw�70\����KGc�x�6�D#�|,>�΅y�Ih�"+�W��mq��sA�_�X[φz�ǕA��
I8��^�Z�-j��M�u7�QCj�gkdq5�^f&�^9�G>^C�}i]�I+��x��H�+ R�����X9�f����E0WQDV
9�w�V7n4�o.��YL�� �-M0X$2�T�L��(�h����BG��"���-M2;��FCM�4t�Q�b���@~�����X-����QlI��������ns��Oo%ܺ(�M�,�O��:�wj�/چ���1���
�]����9��W���}+�=K���O�j?�pD�\��Q$B�vF�.:� ���T����*\6�֣�O֖��nf��dr�����^|鱍� ��Gݖ��^�����Y��@T�?��?��`*	<~��]�����W���U%2�ơ����r���Уe��\l=�Ȧ:���L��ʂ6c�1�?�f�f�W�� �<�s�ShIO�罌�;�������`V�1�� ��C\�Kل��_@d�j�(����++�k�KMj�1�3¯��|#�ם7	��bڱ�̂
���A���a;�N۔���?Zesk�D�;H���D��9�蚕��$	�%��ᕼ�g�:��i�Ꭰ誢M^V%:7�a�C�k� ���;?�7r��W��Ƿ�l�ױ�����Y����b��Ag�K��5�Kt��>�eQ��K������nN���=��zpC��OVfoNhe�L*�=��-�坻/�Ej]����4U$�� j��/��5b����Va,�VL��Ơg8���p���a�G���nq� �j�v�4��KQo=�O���w�
lundN '����+k����x$Ya�C$�AV���a}�æ��sE3�]�h�����`{Ԅ1O
K�,r ��ە��A��{PmK�� u�	$�0F��]��I����3�iҝ:��s��b��'͆��L�iFz����Nѻ�\��c9ԭ�d�FP���
wmlAN|��M�b����Kit&��6�R:��T���S�Z���@��ɪ�sZs�1�x�G�^-�����.GZF>y�?&��=i;�@*��X%��3K�Q����Z��SK��~#���h-"�*����s��J�;���=�~;�������Aas-a.C#q�� �����2���S풯geM��Ϗ"�H�>�)�j,�H�cw)�Ȁ��q��z��[fmV��^��e��u��.@�֜�Z^�����el��`�]��7ml��W�2�oq��|�X��*-������D����i��1��p2O�.�R��I���*��Vߦ)I�ɨXŷ,"|���&1�������5�\0 [��G�+� Z��R��)�]�zd榒������V~�;�qr�˰�B�z�B��t��s����CZG@�e��`�]d�LZN����Γ�O{4�{ۉHe�6�F�<������exZ}��O~�oz��|����צ9�.4�u"�E���U��o�#�z���ho�[tY��y3K2�W� Tq�zjy�^�\�7��_O�`b*� 
O$�5��j�mڿ�U�Y|)�(帑�90ۊ���}>���s�Z��c��� �C�<O�ҷ:�o\��+h5z|}c_$�f�M(���j���[# �Ic�)=)��X$�N*;Q���.N�����M��M*������F����{�}b��y�&�$c,�B0��-��͎���/�~��C4E�,���t�$d)��
z�!�]-�1Iqv�����׿�F�[j���_���
�����kƶH��V\�o��0[+����$�¿5[N���Voo�t� �E����ٽQǩ6�� ��?�����.lV�[$�7��$Fܬ�H!�����F�Z��hn;�Q��[�9�H�Sa�Pc� e�O��������S9?䦭�N���}t;0���ޟw-�����9�p:	����R����sg�\�wow�߭��ļQ��%b�w`�bv�[���N�j��z��3Y� ϥ'i�>3�M[�5���p-�:�UqڍO˲:����� آ/k�S��f���q/� ��V��}ب}YMb	$Kk�]���ݻ�ǌ��qR������e�'�.�4��=h�����A� �@,ˎ���چ_$Q��F��j����[+�S��Ls��=�D�<<>E}�2��+��o�BN��WR�Eq��ʮ��!<���no�UUWS"��B��3��=�2�<�C���95�or�ϥ8Ϝn�5)�|������	"Ea���>��M��)���.<d)㠡D��w.��f=q�����	&�\o���{�{N򶡰��<��5LiŻ�����;m|�����J��{���
��D��qڦ}Y�~Tx�H�Df ��S`i����Qغ�3Ǉ��G%�s�����dty��'��#t�Y^��e�=���U�M�,���Y$]���H������6��ԇ���<��+֗��^��<�޼�.w.Nާ��8=z��=&�+KK������8�򫼺4LW,T�) 9?��U\��.`����7qʶ� }Nj'&�1u/�m=�����/&�q ��Q�0�+l�j� ٞ+�~�/v�.��ވ03ץm�_�k�sX-�+�|�+T��^'�+'�%�u�ֳ��4�@q�JE據"�TO)z�^�hg�f���9�/Z �8��VR�֜!x��h�8���!U��z�@�4���d VD�9����ph4��)��;K"G4�; y#��U��O�S����IGn�1E[u���9��s+*F�O�|Ļ���Y9f��b�F	>��W��w1靁��Ӻ:��<��{qY�1����� :��7VGW�qs��>5��2�׏֥{/5��`\J� �Sq�wr��v~��R�X���w��A2�ϥr6��#�d'u�>�ֈ��i��#2����ych���D*<��qS�m[�&(�>���R���z-���r����� ��LoV�9wxH��hWR���@���Ulq�v� *6�p� 8�(�P�eH �SNc���V�9[O*̓ϧ�bu�!ՙ���T,����c'k��[(#X�`���Yj,���M5�DR���}��eSz�F_9�?j�V�I%Ҁ��+�~�}��.2FG����.2�n��p�?�d�B����}�l���@�4���g��ۨ]Kg�m&�l㺁�7���?Z�x氌o���ڭNm�z���3Y��p��QKmǡ�>�8�MR�5d�$�'>��ԏ��7��]���n�m�w���	|�(F �z��T�EtVC�# �:�?�URô:��b��[��3A����N�`}0x�)ݖ����"�M9f6�;���L����'�c޲=*�K��u���H���b5�٘f�/u�����eE0M���k=ĝ��M4���%��r�?iGO�}y��.���ʑn`�����{�'���Т�K_��a���o�^@�����ޣ{o�Y�Λka�+YĒ<�\Ei
��7?y����ʘ^u��42K{��[,cs	fU8�f������Ѯ����	T�F<��j-���}��'HM2;�vU����r�D,r�Nޝiż������ݜ����k4
�!,��τ��N3�O8D��K@14�X����5_�>*hZh�`����&���c(q��7U���n���o�x�	�)��c4��]�I���I�cF�q�r��PU�5�wA��,��mFy�6��=*�@��n,�":�����F�,⺳�W��U�D�
�Ȃa�{Ԏ�g.�֮�Q'��pF�Gr��mݷ���t�/Q���X�;�=#H��)F�nA�6��:���A'n��#i%�U�5g}�@��E���+���d4�m�>�1I��{}�Y�l�Ȗ�N���7`�# x�|�)0j׺}�6��gTx�E�v�k���Jd?���� ��}����~LA��7R�բY]�y	� I������RARG �{P���2��;�4�W�F�������r%��kj��zԤ`U��|Q�b�I�OI(z�I��n�;mɯ�<^O���pv�x��~/󯝳��}��A���]��TK� Ȟ����L�{ʕ��-9��ww�	?�`��ו���3�:h����qS:3-�z�p@?z����e;&��]��-�u�C�c ��z��yZe�R(�uN�y��M4�,��%��SS�ޣ��� ���D=N�t�U
�7]ڃ�! p�3^��n;;"2��$�'�;�� �O>U/�ZB��8���݈�[x�����O<T��yo.��|g �q�S��֗�,�tq3�����=�l�)�%#���5��V!QO�e�/m%>�U=�MR�M:�F�b�#��@�t�����lx.���C��2��SkI�������P�;�j�1�� u�� B�C�{��ir\�Z{����1�q�W��5H�&#t�!��	��V��5�a�_,�4�<W��I85�<O��ו�^$A���i`zRT�ΔM2i��7�_/�co�oǦ��6) }�C������h;`�ǩ����s�H��0(4E4 |�c�)���P9���P4�Q��3H�:�B�y�ֱ���H��~)���q�OP�hE:^�I������)�� ��wJ�H4'�3��n��q}
�,��Ҹ?|��Ң��w��]��r\�7a{?mn�Y���X����Q��VY�K��p�s����kc��*�Q��?�[�!9h�.�ā�� �U#cs*���͌���[Gop�9`�����z8$��u)�-����������ki{v�ΦX��g��i���� ��Z�0IoRà�z�vr�e"�N$�aT*dc?z4��z2#��X`�Ņ'��� 
z�Q3H3 ��N�Z��(�G;2�0�sOo,�ˤ��C ���Q�J�������G�u�?�5�[AV�1��[�+��Ǿ�T��X�Y�T��i`��hϗ��{/tC��Ny_O�M�|���YOM���d�+�v�t]�m�	��;���� �z��j��=6�!��Fny� �Uюzxz�T�Ȩ�G^�ҝR��$+�c��/�c�^�!���E��������\��j�ڋ���%��5i���n6Ę˰�!Pq�+�Z�_# �A���BY�kv�M���?�I;;�J��~���h��->��R6wH`�~�Cl�h5�yQ�ƍp����s�@�Q?�,vr�o�K�3�5����S u�No�[k.��K���*�jS���Ks���{�ոx_X��U��@��q�-�ܑ�=E&~��W1ܾ�o�	��Pna�p�ϐ:�<�No{3mw4n��U � P�K4l|�S#�L���Αz�����o�X,摗�r	��:ks����E��..�O<s�[�����;.�u����ᣉ�E��Ő�[.��#�2}x��S
��h�ߞ�H֫+C"���ac,\�89�p2s�OXv�R��Kf��ͽ�J�$R�À8`��A!�p?	�ⴂ8�5�r.�F�,r��9�ɡi�%���m-�p�$�3��N �d�#1���4ke>����Fj{��|�y�g"ZJ��߲����g�a�1,@l����{�)V�R{�������*o��ꚤ�D�,�so�U����z�wfu}f��/�"��w��~|��Ğ��� �� ��!N}�k�gX�L� Q���2k�'[׭���O��!��M^�t�6vF 3e[+� ``S���	ukK�>�9��ًG�C���K�9 ��g�ʤ!4�j�k�-U�[�/[����f�e{&df}��l���a��@�Ԏ��9,�L�hm�e�b�L�*3A���V�'�dV'��G�i�"28�0U�A��hN����Ux,Z�S0��s�Ko�p74]�.��\�#8�X���qA��r�H��h�EQr	ǧ�Q�j�����*�F�' �B���mc��m�n�%N6�F?�W#�w#�❔���p>������R�/f� tS��x�O��� �W*�V���M2��?!^_-�:�㟄�T�h?y<"�u"3V^�ܭ����m%�fI#<�MTc���R���A�O�d�F~j��!ڲ�/�c��J���׶��b��=OC��iΥ���X2ڒG��� 0?ZjI,�ԀxNx��T{9��ic%�6���T3F[�V����g��W�,:n�������$���9�k��9��,on�T���=8�����I�KF�*T*�p|�sL%�y�<�><��N��ƨ��n\`
KG,j�9	�d�|�X`]��5��	4LnT� z�����):*����S�,�j$YU�pr����:�+���1�x���<P'�j��I���3����%[�V>1o��b��)?����'�Y׫��e�q�I'>��p9���kJ��I^I���g������)�ץ(Pղ9�JBD
R�4>|� *�ϕ2MhKDST)b�4�r}�!Tf�a8�G�Q�9��:S[E f����u�(�����Uji}guAjXC����ˍ04uM�~�wI?�;8�.�Ɲ���e��qZRr�d� !��9���U]�z��-c�<�$�N1ש�kSB�#ۦ�<<}��ϼ��'�n��x���:}�fЋ-z�D�vO`;_���S��..p�K^�`���jS�l���lhDR6��#��󨊩˔-%���!�j��F�y4�M d���c���J5�{]�gi@w>� J�����B��*#�w����4$���k1]�&)O�=T�֘�����?�H�$�9V�' g�����mwJ1�����Շ_Ȋ����V��w��%�E�Ã�-�1e%�¤�#(������>� �Fб����⩍b4��i�ݺ�c�
xR���oz�U.�\m�����f�kmB���\��QN��F&��V���*�g�[�鶎�ԣ�$����V����f��K�:Lr������T��۰�C�Þ��`��י�� :�5���;��=��)�b6������7Ҧ.Q�G�)ز1U-�	 �g��^��a��O?q��KmA�q����/W�P��&�6�� c�3���0I �����K�EB.,��<�%LL���
���5ۋ�V����R�a��Q�>�o�� p�ciV< ��s�Ȣ@�vr�U*/uI��;8�H��+=�>΅�c�R��5T,!G��)�LK��eA�a��ET��Bt�	�u�K��{yc�A9�=����߁ȩcٍ9�&�[����̓/��E2��iٛ���m&uu����UHC�`��?����$�;[���?�eca���=(v?�˘~b[K��%ob��Xz��2�# �f�����]��Dc�X���b���iu�=.�I��t�Hg�$��	uV����C����H-�;���8呥�;V���2���T��j�5t��jK��K-���JN=�ϕ<�@��X�Ԑ�3���a?����*�.}MJ���$�����:��i8��3Ȭ�iWX�r2A��<��*n�v�n?bc��y���Օ#%�*��6�H�<�j�a�Y���/km���n�4
�f,�Ԓ~�����v�d�c+�:�� K��8�Jҗ�� YH��A���uݴIk�f�"��_�ޞk͹�s�rFA�(�Ҋ�z������/�����%a�mP����E�*� ���)WW����V]0�N�wWM�[iefcn@�9,*���=���-^p�A+B����g>�(� ���[���o{��=2�h�6�+%�;���ʂ�pS����zԖ��CP��G�{=5��绱�x�ʯ��ݕ�z�<��`�qӊy[�<����5]jy���jmo<l�on�۫���];g��/B�����c��^��}�h�Wd
>����^ܴ���!��i<���c�Ѳ��֬�N��l�DedL��3�����K���Zg���j��gw�!����>���)�YB��N�!A&��A#>���H�IlZ/t�2TR���O	� ���D���=��X��ʨ��/j���jֹH�7~�<�����]�|Z��ʕ]V�=��;d+�~�Q����3mT����A�W��6�]��,S�K"ČT�.>��R���}�������Koe9���C�t�öxѽ��SZ�W�T�I�P���zV}C�HG��-�BY@�+��$m+^�*��L��pK.L׻#v�Z"�#�X7F�m ��n��=�Z����o��$��OO��I?cw��u�v����s��}� �@��JԢ�d�J�[�������?zv��*�^��=���s����e����ɑ�<��hM2�t�n3��\�GS�t�E_i�VR�wmw (S�{�Rl�G�H�{��I=��8>c����:��B����`8>6�	k�AEsoi�̬�IhQ�K�SP��K���9Nv�[<��
=Ɲi�F�=�P�=~��Jh�R�(I_��q��ZN��>�τ����I�ؚ�*�"�a�t|���c9��c^���|�_��nx��WS].�9Y�� ��$T�?j3�E��'��'�F8ڥA�����s�:F᎜V7��E��9�B$P�e�W{e��^B���>@$~F�'��)`�m�S�'P������$��=6�:g#����7��Gx-]���3�\)�����S�X�tv�G���%��VN�,3ɕ�7�xϝ
����Ѷ����g�mh�3 pJ�vdg�qT�e�^��ިG�yk� >��>""]Q��&�Cv�6�F�����.��x�m�m.��h��YeU]�Q:�@`�)��i֟��KR-�vv�y��#R=A|�f�-�<�5L�;a��f��f���Kwn��_�8'<sQگ�+����PЧ���D�+\�6L����O���
�Zmx�F�j��|G���8R�Y" ȐY�pP�=�lg�5/�|D���\�5���v���d��E�c�Mp��dyT�CL���ض���{W���8X�~-������j�;��zGJ!�B~)��|
cw'��|�Jq#`Tu��!v8��G�N��v����%��Q��a�ݺV'�Ψ��q<��e����l?�Y����S��3���G�S�1�+��������^�<� 
�~���y��e�)X�B�>��j��Eq2�d܇W9$c¨7[-����+��bs�1��W�&�&6�$��)���F�eL
�t�KP�i@v��pz��f���I,���2N|�<TU󽦷����[v7�� zu�]��Il� ��PO�!�nȠ��P��r�� n|d�8�U�KԒ��[L#y cm��<�*�et�gh�(g��|G�z�F>٩�f�]]��A$�� ��5?:Yj(�=�G �$gO���͢�o�[�!�Y����#�{�[@P����r@��P����ǀz�u�+�mEF.�.�R�;�0`��O��?��(�a�OFEp@�ũ_[#(�HQ�"D�}������%�KI1�n"l�~F�����;�q�;ٴ����f�^�XH�(����	$�����S`;,�K7�l-��Z�n{xz�%���]g�I���5-F+K�4���J�?�T��U8,$t�r0[[��S�핁n��Vj�}�E���3bH2HYN ���^�{Rq�VVD2�*�G�A�@>���! f=���4)����f9^Y&ϟ �S�i�0�j6Q�� ��6��&B?J�ݞ���F
�ﵭBU=Pcb���(vWO�=���rR��i� ��W��1�(J��:/�M>������Ld�!PZآ4�����I�)8�Ns��OL�������L��2Il D�D��۔�
����BA����y�{��h"��p�d��=�9�Ѹeۜ�a����vwT�jw0.��F[)�e�Tl6C��1u��)�$U��=(�3U ��5/g�y�D7�������num�I����C`��T�
2
i��/�:.�su41q9��"�H���������z��ѻ;g���C���c+�r�A�PN �ҟD9�f����P
I� r9��-��4;~��6���Ks}⅔�
� � � c���8�"�Ui�:,��Y��&B[=����T�p1�U���LV;5�c��vp�7� ��ٰ"��T%.v���#�O��>�)��M�ea��f�5�k�\�s�H��-����d�c���f��~[O�O��w���G���h����ZI 
o+S�i)�"�ғP=��6�pHU��IzaI�N���W�˖��gک������lj2�4���L�[�.���VS���TH��M�ʲ� PZ6��1�HZh�$J+�~��^3�{�i��������n<�p9`�5�
˾26x�:�]�Q��YX0$ A���f%W�&i0��I����Ң�g֒���;�&9=ĉ�Ǉ ���d�'$� �1���s�{Aye#�	��l��c�*�N�b�x�D��F���f�moP��� <7隼��-Ԉ��-̹�~�m�ۖ^�D#��⥻3}5Ƌl�p����y���+��ef��k{(޲q����P.5�J:m���=Ʃ!�F1�9L��:מ׿
̜�O�y���r*�89��O�z�ȀZ	��a�� ��r�G�U���meI=pE+�n!U@;�=8`(�}�d�iD}��ڮv�2��� 6��4ӳ���C�*��M��z�Zrƽ�}c���y��:����u+{1k~�1��G���i[$߹p7�O���)Z&����=����Zx��v����W	e�{9<T�ic��7w�e�� {u����p�<�}9� )�X�˜���[��B��p%�sQ:��܅���G*�5̓l�\F���v�0	9 gV�e��gG�73ĉ�*�J�ˢv�㴇d�iQL�9���G�*8(Ѫ��y�i�|�խ4����87� c�@:��ob6�基��J
`{��>�M�;�Zɢ��$,���û]����	9�Rl;��qn��O��x���D�=V1�	pG�Q-V���.%�+{Լ.�sh�uRznd(�83�Q�<p��,�̋�3�EU��$z��>���Z��_�,���#* a� T<F��/�qj������SG/��/�%��##���yeFx�%��y�Y�1��� ��?1Rk럄zT��w��j��q��ͷ}�ߗ+��e��9�C� F���e�!b1xf�"tߞ
�k��>\�'����NQ��$eNyOa\�UCG�m���s5ޕ���9\�:rG�`aN�S�!�S�vB$�-aoXc� Ê��M�i.t��⾆�%9���3`���$`�ӏ:�ځ"+#FAM��}_�]Z����4GU��a���zl�s�M��DZ�5��u�a7��w"G��7+?xC������fB��Wf
�����g�A�ֵ��?j��V��_������Ȏ���F��,�q��C�ƿ�*�؍3�I���7s9erB���w��Rkd �������\ZZ��t�у�?0�7 ����<������],�bL"WV(T��� ��1M�A���Fc�B>��V��N�q��.ş�>���m6m��.?Zؒ��i�گQ�L��9��Wo�fA��K�+\;�8l�iI-d)��QrX���x�k)]�� �|� *k�e�m���6s�
H���$��$�;��~x����i6��>�d�h���bU�X���;0V8/����`H#���F^�?9��c�iNFw
u�J��]��P��'����(�ҲE�����Ԕ-ݸ
O>c����g�02�kk3�~���� T���c}1���E#�rG�B���Ǐ����*$�+�ʜ��(H��EI� *�@� ��TE���,�V�tf2 .:0�ܚ���jzf�f��i$M��*�k��n��建�A�f��<}�SZ*���a�����<}�:IZ4y�S�����2;wĠ�r}�ГP[f\�Gmy�:S��RM$$s���D[���c�����g�4k�ylĈ�q�0F|驕�Ho6�Ҟ��Vl��sH
��G�@) ��9�̚��
�YY����y���U�U#c�E���c ��VGZ�q��t��rnu�FO1�B��=<A��jooؽ�u�]���O��K�̀2D��8 } ����5�]�p����Ӣ��`��?���9��3�*���PG4T�樆J2�G%�QP�N$U�GQA^����%$�J{��
��S�\E�#`Sx#�c�E�O�\b�`
{U&��r���
h���M��U������H��O�uj%��|ռb4�c�$�Ԓy'�F�1�2
CMd�r)����yk�� nA�>^D���/-�?g�� ��V�r� ��p�n���樝3��5�/�m���� p�?O��zP��c˞��2��fn9f�̍��ry>U��ez;���-�wzb����@���Ż^' �x�}r��ƫ�t�o�FB���ƬWH�ŷ`�6sӐ�F�WM9Vsp�q�%e!�PF1�ݮ��{e!%I"�:g�?�5;���Q��ْ[;���X��;k�Okk��D��?
�i�	�S�\Eܒ���p|�� ��o�Xٶ\4@��TS�.��Ɓ@b�+�A����96�������z	Ɗt��Y&_6_	����ˍ�W�U�s����E+�+��ʜ��q@A$#2&��Ҹc��)�N�I�A<})���7�*r���?z
;�3�Sǈd��J��˩*NA�L;zݿ�n����dzxE ��2m��?��� ��nxq���K�Y$
3�\�Xl�P �h](�:�:��Q�A��I'�P���e�BN����TM1���!�!���5�M9�s����8�4��jN�.TgNm��H�x�!x5Qh�Ǖ:�zP��8�@�(��8��Oe^�9������M8��HjNM\��u���Lvv�L���E#�B3� �]S)�"���)?d��H���GI?��X��\��\u����-�3۱����SϏO:��N��G��T�����f��
9�G�)w3[�W1ܒ��1�Q�5�mށH�;�`y��cR���RpB�R!�#4�S[t�ӻ�À�G �x�S�z6:e�4{ B�>�Μ��a�%����e���uu�i=������#i��&g����$ю����u'��L�W��E�ܥw��zϧ��~ҋүKi֮O� c'��dh�x�g���$��(&'�,��+�~��\����qE	�d13H�W�x'��V.5IQ��wl߄�`�?Zd��lg�LnҲy|F<�H�>B��D�:��U��!�3�eh�s�� �9��!����2�\���� u���%����"DS���z�OA�*Um��aA�SBi�� eQ�S6�c�0H� �8�s^n*ἧ���|�I5��Ҫ��2cʛ#�s�4�z:C��yx��EH��\�78�0/��'@��Ȓ?���V�����ڥ�b�2�� Tż8�2��Jy�� ��1��V�c�����%<R�J*҆)
iTD�Bs�,�Q���k)��m)��FS��_�D��Mٴ�J[L�ω��ӟ����.�o�:L�w����t�� ���o�u����ۄ���g��3.��H�S�/:4[��� EKb�k�~��mߤT���[���7��9�K�ͱEL�����V%���X�&Del.|����cHB�Te}��M;��;�1�!B�W�pju��(�d�+ 5_���,f27a�\7PwT�K:|��N�A�*� 5O�(�s���:�!�X�����}*E3����5�.������0獄���������Rn�f��c� ����ӃK����&֓����F�b*�g�k9	�cm�n��s� ��M��KY�YLV�J�VV]�3�1��>U��m�����u�r�8W8'�"��D��D@��N3��Ϋ���H0-��\�1\�=���z�M�Ų�{�"N� ��L{=����m��0)�?Z��7�b��׫0k`R����T�x���F>�)h�J�d\����n��(*<�\%�E��a�&�����u�^%��)M�ǳ�@��c�R6��n Rb����O��D��8� >��>?�a.���r���_ڜ�uf� �n���Aşj��\ܲ�Z�N��Pk�{�t.c�('��Ϛ�?���7PǗ����p囏��?���&Ա���JD��z~$�H�RIu��m)8���6#PX�dz�
 43O�}(K�,*�P9�+R�4D�ƳHsA�!�i���8��S9��L�_=�mM���$!Kw0�#˗o�¾�N��Ҿu��wi'�=z0yH��l�O�P� :��>��f���i%�s�S���<E#��1����ls���ʪ��Kzn��⼞����Z}N=�Ͱ��Oך��^�-���%�����]I���C��H#;��QV�93,�"�w ���/L�j빻�WI�p��Υm.����'Aܮ�/�϶0j�J�w���*=��ҤbSm�:�Kp �����Fk��ϥ�&H��'�*O�E>�Is�TgkYSG������l6s�T��d
�� ��E�e��iy7��%wD���'�q���PW����W6=�|�B���)��z�G�<���c�H�	<c�Z��,ny��"�Y�W��}=���s�t���·��-n���e
�� ��}������F�Mn�fu�88�2*fMnͬw��m�u �q�N=r|�0�X�(��y9߇��/vs�p�'<V�纛��5���6?�Q�9�['P�yn����[sX�FَMgf�;9�����t@�=*.��+�VY�1���d�]ګ����$,|�9���_�Tuű�����\� ^���NI�?��bg� �dn_:�������q�u�m{e%���'��9S�?��?v����^4�re;���޼�/�������.��
��b$1���WL�4�C����q��M>��  5���k{5��O�+�q���7\���쾦n-#>Df�p˸
�v5;(��W[S��_Q��3o��wR!� �h
M+v)�C�
���Xn( ���,�!�+٤9����S6A�2�Zg;dP���s�
���N���kAl�h�g���}+�f�\|}�k����pAS�1�V�����������KN�s��PhK9�N9����;�Я"��Q����ҽ>��D��J$�d�LF�|֦t��&�[yݒ����*6��{՛s4�$� �#>~T��W�q$�(g�O!� ~�&��L]�f,�;�`�;��S�BW��Ov�fOF����j�R[�bY��y/� �G����'�D��h�	��󩤌�4�k��U�`s�FiZ#��.��O��/X��d�<yyyTWe�2��3�v�I뚩��*�F��5rr<k�z���}hvZ�P߫�>�|�n=z���S���kl���6�3τ+�BڼѴa�����ڼ��t�O��92HK7�0X����9�W����e�0���'9~T(Dr��q
G<NQd�*q��)���*��\��OS�O0Wyj�� _��Zk�q�}�q}/֙g��6�0O5�
Ab(39T��Y垛c�ɻ�����əϥ>�d`�s��QWY\��d�HB�'�M��wTg9M�z�~�;�m8�SVꌗ��<Qa�.���2�2=����T�?v�!	rGb��G��V��5�bf�$U�R+j���7���Ć�v��5��!k�/��;�s\�O�ݖVt��m�����m� ��<�z�����?�� }kv|%c�\�a^�ޞO���cvmB�1�V{v�ݝ?��VHN+�$�|�Wu"��z�	���T�JB&�Bi·4@i�Q��%�H�QJo'��L�n���x���/ �F	#8��c�Cɨ�B�-�oƩu!'�2��u��Ps��:�$~U�;�:j7��fo����rƸ~M�;8<�vǅJ��� XI2 |C��Z�w`���F�$`�8�>�Ҏg�@�n�O�T�L�u1߸ �/�D���\UW�j���^��v�$�sBh�R+kz`>,�&�A#�[�M�%� r�C��\Z�����J�{���h�P��PJ�!7���@���3��#�֫��1���� hq��՟Q��$a@(������È��E9�'?�x���                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ���� JFIF  H H  ��Exif  MM *           n    	   t       ~       �(       1    8.4 2       ��i       �    Apple iPhone 6     H      H   2015:07:15 14:23:53   ��      (��      0�"       �'        �     0221�      8�      L�     � 
     `�      h� 
     p� 
     x�       �	       �
      ��      ��|   :  ���    681 ��    681 �     0100�       �      	��      ��       �       �        �        �       �        �2      ʤ3      �4    "  �          (      2015:07:15 14:23:53 2015:07:15 14:23:53   c   �  �  �  1�  	�          S   _�5Apple iOS  MM 
  	          .   �     h  �  	        	      �  	      �  	        
     " 	 	       	           bplist00O n ^ X T Y X ] W V O J G C < 9 6 f i d T F G B G I D = 4 8 2 1 - ` � � � � � � � � u e > : 5 - * b !*++OE;3#� � e /,',Gdf`XPE2i ,&BVoqiaPv � � 4anib>� 
 � � � �  ;V]Y� � � � � � � � � � � .IM� � � � � � � � � � � � � � � � � � � � � � � � � � � � @ D � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � n � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � y a g n a v s z { s w x u m v x                             bplist00�UflagsUvalueUepochYtimescale  �E� ;�� #-/8:             	               ?����  4����Z  ����E  �   S      S               Apple iPhone 6 back camera 4.15mm f/2.2 ��XICC_PROFILE   HLino  mntrRGB XYZ �  	  1  acspMSFT    IEC sRGB              ��     �-HP                                                 cprt  P   3desc  �   lwtpt  �   bkpt     rXYZ     gXYZ  ,   bXYZ  @   dmnd  T   pdmdd  �   �vued  L   �view  �   $lumi  �   meas     $tech  0   rTRC  <  gTRC  <  bTRC  <  text    Copyright (c) 1998 Hewlett-Packard Company  desc       sRGB IEC61966-2.1           sRGB IEC61966-2.1                                                  XYZ       �Q    �XYZ                 XYZ       o�  8�  �XYZ       b�  ��  �XYZ       $�  �  ��desc       IEC http://www.iec.ch           IEC http://www.iec.ch                                              desc       .IEC 61966-2.1 Default RGB colour space - s