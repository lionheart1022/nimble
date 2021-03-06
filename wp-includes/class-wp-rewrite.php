pan>' . __( 'Profile', '__x__' ) . '</span></a></li>';
      }

      if ( $args->theme_location == 'primary' ) {
        $items .= '<li class="menu-item current-menu-parent menu-item-has-children x-menu-item x-menu-item-buddypress">'
                  . '<a href="' . $top_level_link . '" class="x-btn-navbar-buddypress">'
                    . '<span><i class="x-icon x-icon-user"></i><span class="x-hidden-desktop"> ' . __( 'Social', '__x__' ) . '</span></span>'
                  . '</a>'
                  . '<ul class="sub-menu">'
                    . $submenu_items
                  . '</ul>'
                . '</li>';
      }
    }

    return $items;

  }
  add_filter( 'wp_nav_menu_items', 'x_buddypress_navbar_menu', 9998, 2 );
endif;


//
// A custom title function that returns the desired data based upon the current
// location in the theme.
//

if ( ! function_exists( 'x_buddypress_get_the_title' ) ) :
  function x_buddypress_get_the_title() {

    if ( x_is_buddypress_user() ) {
      $output = bp_get_displayed_user_fullname();
    } else if ( x_is_buddypress_component( 'activity' ) ) {
      $output = x_get_option( 'x_buddypress_activity_title', __( 'Activity', '__x__' ) );
    } else if ( x_is_buddypress_component( 'groups' ) ) {
      if ( x_is_buddypress_group() ) {
        $output = bp_get_current_group_name();
      } else {
        $output = x_get_option( 'x_buddypress_groups_title', __( 'Groups', '__x__' ) );
      }
    } else if ( x_is_buddypress_component( 'members' ) ) {
      $output = x_get_option( 'x_buddypress_members_title', __( 'Members', '__x__' ) );
    } else if ( x_is_buddypress_component( 'blogs' ) ) {
      $output = x_get_option( 'x_buddypress_blogs_title', __( 'Sites', '__x__' ) );
    } else if ( x_is_buddypress_component( 'register' ) ) {
      $output = x_get_option( 'x_buddypress_register_title', __( 'Create an Account', '__x__' ) );
    } else if ( x_is_buddypress_component( 'activate' ) ) {
      $output = x_get_option( 'x_buddypress_activate_title', __( 'Activate Your Account', '__x__' ) );
    } else {
      $output = get_the_title();
    }

    return $output;

  }
endif;


//
// A custom subtitle function that returns the desired data based upon the
// current location in the theme.
//

if ( ! function_exists( 'x_buddypress_get_the_subtitle' ) ) :
  function x_buddypress_get_the_subtitle() {

    if ( x_is_buddypress_component( 'activity' ) ) {
      $output = x_get_option( 'x_buddypress_activity_subtitle', __( 'Meet new people, get involved, and stay connected.', '__x__' ) );
    } else if ( x_is_buddypress_component( 'groups' ) ) {
      $output = x_get_option( 'x_buddypress_groups_subtitle', __( 'Find others with similar interests and get plugged in.', '__x__' ) );
    } else if ( x_is_buddypress_component( 'members' ) ) {
      $output = x_get_option( 'x_buddypress_members_subtitle', __( 'See what others are writing about. Learn something new and exciting today!', '__x__' ) );
    } else if ( x_is_buddypress_component( 'blogs' ) ) {
      $output = x_get_option( 'x_buddypress_blogs_subtitle', __( 'Meet your new online community. Kick up your feet and stay awhile.', '__x__' ) );
    } else if ( x_is_buddypress_component( 'register' ) ) {
      $output = x_get_option( 'x_buddypress_register_subtitle', __( 'Just fill in the fields below and we\'ll get a new account set up for you in no time!', '__x__' ) );
    } else if ( x_is_buddypress_component( 'activate' ) ) {
      $output = x_get_option( 'x_buddypress_activate_subtitle', __( 'You\'re almost there! Simply enter your activation code below and we\'ll take care of the rest.', '__x__' ) );
    } else {
      $output = '';
    }

    return $output;

  }
endif;


//
// Checks if the current component is one that should display a landmark
// header.
//

if ( ! function_exists( 'x_buddypress_is_component_with_landmark_header' ) ) :
  function x_buddypress_is_component_with_landmark_header() {

    if (
      bp_is_activity_directory()            ||
      bp_is_groups_directory()              ||
      bp_is_members_directory()             ||
      bp_is_blogs_directory()               ||
      bp_is_current_component( 'register' ) ||
      bp_is_current_component( 'activate' )
    ) {
      return true;
    } else {
      return false;
    }

  }
endif;



// JavaScript
// =============================================================================

//
// BuddyPress core JavaScript strings used in localizing variables.
//

if ( ! function_exists( 'x_buddypress_core_get_js_strings' ) ) :
  function x_buddypress_core_get_js_strings() {

    $buddypress_params = apply_filters( 'bp_core_get_js_strings', array(
      'accepted'            => __( 'Accepted', '__x__' ),
      'close'               => __( 'Close', '__x__' ),
      'comments'            => __( 'comments', '__x__' ),
      'leave_group_confirm' => __( 'Are you sure you want to leave this group?', '__x__' ),
      'mark_as_fav'         => __( 'Favorite', '__x__' ),
      'my_favs'             => __( 'My Favorites', '__x__' ),
      'rejected'            => __( 'Rejected', '__x__' ),
      'remove_fav'          => __( 'Remove Favorite', '__x__' ),
      'show_all'            => __( 'Show all', '__x__' ),
      'show_all_comments'   => __( 'Show all comments for this thread', '__x__' ),
      'show_x_comments'     => __( 'Show all %d comments', '__x__' ),
      'unsaved_changes'     => __( 'Your profile has unsaved changes. If you leave the page, the changes will be lost.', '__x__' ),
      'view'                => __( 'View', '__x__' ),
      'x_show'              => __( 'Show: ', '__x__' ),
    ) );

    return $buddypress_params;

  }
endif;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php

// =============================================================================
// FUNCTIONS/GLOBAL/PLUGINS/BBPRESS.PHP
// -----------------------------------------------------------------------------
// Plugin setup for theme compatibility.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Forums
//   02. Topics
//   03. Replies
//   04. Users
//   05. Search
//   06. Breadcrumbs
//   07. Miscellaneous
// =============================================================================

// Forums
// =============================================================================

//
// Filters the output of nested forums within forums.
//

if ( ! function_exists( 'x_bbpress_filter_list_forums' ) ) :
  function x_bbpress_filter_list_forums( $r ) {

    $r = array(
      'before'            => '<ul class="bbp-forums-list">',
      'after'             => '</ul>',
      'link_before'       => '<li class="bbp-forum">',
      'link_after'        => '</li>',
      'count_before'      => '',
      'count_after'       => '',
      'count_sep'         => '',
      'separator'         => '',
      'forum_id'          => '',
      'show_topic_count'  => false,
      'show_reply_count'  => false,
    );

    return $r;

  }
endif;

add_filter( 'bbp_before_list_forums_parse_args', 'x_bbpress_filter_list_forums' );


//
// Removes the single forum description.
//

if ( ! function_exists( 'x_bbpress_remove_single_forum_description' ) ) :
  function x_bbpress_remove_single_forum_description() {

    return;

  }
endif;

add_filter( 'bbp_get_single_forum_description', 'x_bbpress_remove_single_forum_description' );


//
// Add forum actions to the beginning of single forum.
//

if ( ! function_exists( 'x_bbpress_add_actions_single_forum' ) ) :
  function x_bbpress_add_actions_single_forum() { ?>

    <div class="x-bbp-header">
      <div class="actions">
        <a href="<?php echo get_post_type_archive_link( bbp_get_forum_post_type() ); ?>"><?php _e( 'To Forums List', '__x__' ); ?></a>
        <?php bbp_forum_subscription_link(); ?>
      </div>
    </div>

  <?php }
endif;

add_filter( 'bbp_template_before_single_forum', 'x_bbpress_add_actions_single_forum' );


//
// Multiple functions to wrap various count outputs in a <span> element for
// more flexible styling.
//

if ( ! function_exists( 'x_bbpress_filter_forum_topic_count' ) ) :
  function x_bbpress_filter_forum_topic_count( $topics ) {

    return '<span class="x-bbp-count">' . $topics . '</span>';

  }
endif;

if ( ! function_exists( 'x_bbpress_filter_forum_reply_count' ) ) :
  function x_bbpress_filter_forum_reply_count( $replies ) {

    return '<span class="x-bbp-count">' . $replies . '</span>';

  }
endif;

if ( ! function_exists( 'x_bbpress_filter_forum_post_count' ) ) :
  function x_bbpress_filter_forum_post_count( $retval ) {

    return '<span class="x-bbp-count">' . $retval . '</span>';

  }
endif;

add_filter( 'bbp_get_forum_topic_count',     'x_bbpress_filter_forum_topic_count' );
add_filter( 'bbp_get_forum_topic_count_int', 'x_bbpress_filter_forum_topic_count' );
add_filter( 'bbp_get_forum_reply_count',     'x_bbpress_filter_forum_reply_count' );
add_filter( 'bbp_get_forum_reply_count_int', 'x_bbpress_filter_forum_reply_count' );
add_filter( 'bbp_get_forum_post_count',      'x_bbpress_filter_forum_post_count' );
add_filter( 'bbp_get_forum_post_count_int',  'x_bbpress_filter_forum_post_count' );



// Topics
// =============================================================================

//
// Removes the single topic description.
//

if ( ! function_exists( 'x_bbpress_remove_single_topic_description' ) ) :
  function x_bbpress_remove_single_topic_description() {

    return;

  }
endif;

add_filter( 'bbp_get_single_topic_description', 'x_bbpress_remove_single_topic_description' );


//
// Multiple functions to wrap various count outputs in a <span> element for
// more flexible styling.
//

if ( ! function_exists( 'x_bbpress_filter_topic_voice_count' ) ) :
  function x_bbpress_filter_topic_voice_count( $voices ) {

    return '<span class="x-bbp-count">' . $voices . '</span>';

  }
endif;

if ( ! function_exists( 'x_bbpress_filter_topic_reply_count' ) ) :
  function x_bbpress_filter_topic_reply_count( $replies ) {

    return '<span class="x-bbp-count">' . $replies . '</span>';

  }
endif;

if ( ! function_exists( 'x_bbpress_filter_topic_post_count' ) ) :
  function x_bbpress_filter_topic_post_count( $replies ) {

    return '<span class="x-bbp-count">' . $replies . '</span>';

  }
endif;

add_filter( 'bbp_get_topic_voice_count',     'x_bbpress_filter_topic_voice_count' );
add_filter( 'bbp_get_topic_voice_count_int', 'x_bbpress_filter_topic_voice_count' );
add_filter( 'bbp_get_topic_reply_count',     'x_bbpress_filter_topic_reply_count' );
add_filter( 'bbp_get_topic_reply_count_int', 'x_bbpress_filter_topic_reply_count' );
add_filter( 'bbp_get_topic_post_count',      'x_bbpress_filter_topic_post_count' );
add_filter( 'bbp_get_topic_post_count_int',  'x_bbpress_filter_topic_post_count' );


//
// Filters the output of the topic tags.
//

if ( ! function_exists( 'x_bbpress_filter_get_topic_tag_list' ) ) :
  function x_bbpress_filter_get_topic_tag_list( $r ) {

    $r['before'] = '<div class="bbp-topic-tags"><span>' . __( 'Topic Tags', '__x__' ) . '</span>';
    $r['sep']    = '';
    $r['after']  = '</div>';

    return $r;

  }
endif;

add_filter( 'bbp_before_get_topic_tag_list_parse_args', 'x_bbpress_filter_get_topic_tag_list' );


//
// Filters the output of the topic admin links.
//

if ( ! function_exists( 'x_bbpress_filter_get_topic_admin_links' ) ) :
  function x_bbpress_filter_get_topic_admin_links( $r ) {

    $r['sep'] = '';

    if ( is_user_logged_in() ) {
      $r['before'] = '<div class="bbp-admin-links"><div class="x-bbp-admin-links-inner">';
      $r['after']  = '</div></div>';
    } else {
      $r['before'] = '';
      $r['after']  = '';
    }

    return $r;

  }
endif;

add_filter( 'bbp_before_get_topic_admin_links_parse_args', 'x_bbpress_filter_get_topic_admin_links' );


//
// Hides topic pagination if amount of topics is less than the topics per
// page option.
//

if ( ! function_exists( 'x_bbpress_show_topic_pagination' ) ) :
  function x_bbpress_show_topic_pagination() {

    $total_topics = bbpress()->topic_query->found_posts;

    if ( bbp_get_topics_per_page() <= $total_topics ) {
      return true;
    } else {
      return false;
    }

  }
endif;



// Replies
// =============================================================================

//
// Filters the output of the subscription link.
//

if ( ! function_exists( 'x_bbpress_filter_get_user_subscribe_link' ) ) :
  function x_bbpress_filter_get_user_subscribe_link( $r ) {

    $r['before'] = '';

    return $r;

  }
endif;

add_filter( 'bbp_before_get_user_subscribe_link_parse_args', 'x_bbpress_filter_get_user_subscribe_link' );


//
// Filters the output of the reply admin links.
//

if ( ! function_exists( 'x_bbpress_filter_get_reply_admin_links' ) ) :
  function x_bbpress_filter_get_reply_admin_links( $r ) {

    $r['sep'] = '';

    if ( is_user_logged_in() ) {
      $r['before'] = '<div class="bbp-admin-links"><div class="x-bbp-admin-links-inner">';
      $r['after']  = '</div></div>';
    } else {
      $r['before'] = '';
      $r['after']  = '';
    }

    return $r;

  }
endif;

add_filter( 'bbp_before_get_reply_admin_links_parse_args', 'x_bbpress_filter_get_reply_admin_links' );


//
// Add reply actions to the beginning of the replies loop.
//

if ( ! function_exists( 'x_bbpress_add_actions_replies' ) ) :
  function x_bbpress_add_actions_replies() { ?>

    <?php if ( ! bbp_show_lead_topic() && ! bbp_is_single_user_replies() && ! x_is_buddypress_user() ) : ?>

      <div class="x-bbp-header">
        <div class="actions">
          <a href="<?php echo bbp_get_forum_permalink( bbp_get_topic_forum_id() ); ?>"><?php _e( 'To Parent Forum', '__x__' ); ?></a>
          <?php bbp_topic_subscription_link(); ?>
          <?php bbp_user_favorites_link(); ?>
        </div>
      </div>

    <?php endif; ?>

  <?php }
endif;

add_filter( 'bbp_template_before_replies_loop', 'x_bbpress_add_actions_replies' );


//
// Hides reply pagination if amount of replies is less than the reples per
// page option.
//

if ( ! function_exists( 'x_bbpress_show_reply_pagination' ) ) :
  function x_bbpress_show_reply_pagination() {

    $total_replies = bbpress()->reply_query->found_posts;

    if ( bbp_get_replies_per_page() <= $total_replies ) {
      return true;
    } else {
      return false;
    }

  }
endif;



// Users
// =============================================================================

//
// Add an informational message about changing user passwords.
//

if ( ! function_exists( 'x_bbpress_user_edit_before_account' ) ) :
  function x_bbpress_user_edit_before_account() { ?>

      <div class="bbp-template-notice">
        <p><?php _e( 'Your password should be at least ten characters long. Use upper and lower case letters, numbers, and symbols to make it even stronger.', 'bbpress' ); ?></p>
      </div>

  <?php }
endif;

add_action( 'bbp_user_edit_after_account', 'x_bbpress_user_edit_before_account' );



// Search
// =============================================================================

//
// Hides search pagination if amount of search items is less than the search
// items per page option.
//

if ( ! function_exists( 'x_bbpress_show_search_pagination' ) ) :
  function x_bbpress_show_search_pagination() {

    $total_search_items = bbpress()->search_query->found_posts;

    if ( bbp_get_replies_per_page() <= $total_search_items ) {
      return true;
    } else {
      return false;
    }

  }
endif;



// Breadcrumbs
// =============================================================================

//
// Removes the bbPress breadcrumbs from all default locations (is later
// removed then added back in the breadcrumbs.php file to allow for standard
// output as the breadcrumbs are filtered below to be used there).
//

add_filter( 'bbp_no_breadcrumb', '__return_true' );


//
// Removes the bbPress breadcrumbs from all default locations (is later
// removed then added back in the breadcrumbs.php file to allow for standard
// output as the breadcrumbs are filtered below to be used there).
//

if ( ! function_exists( 'x_bbpress_filter_breadcrumbs' ) ) :
  function x_bbpress_filter_breadcrumbs( $r ) {

    if ( bbp_is_search() ) {
      $current_text = bbp_get_search_title();
    } elseif ( bbp_is_forum_archive() ) {
      $current_text = bbp_get_forum_archive_title();
    } elseif ( bbp_is_topic_archive() ) {
      $current_text = bbp_get_topic_archive_title();
    } elseif ( bbp_is_single_view() ) {
      $current_text = bbp_get_view_title();
    } elseif ( bbp_is_single_forum() ) {
      $current_text = bbp_get_forum_title();
    } elseif ( bbp_is_single_topic() ) {
      $current_text = bbp_get_topic_title();
    } elseif ( bbp_is_single_reply() ) {
      $current_text = bbp_get_reply_title();
    } elseif ( bbp_is_topic_tag() || ( get_query_var( 'bbp_topic_tag' ) && ! bbp_is_topic_tag_edit() ) ) {

      // Always include the tag name
      $tag_data[] = bbp_get_topic_tag_name();

      // If capable, include a link to edit the tag
      if ( current_user_can( 'manage_topic_tags' ) ) {
        $tag_data[] = '<a href="' . esc_url( bbp_get_topic_tag_edit_link() ) . '" class="bbp-edit-topic-tag-link">' . esc_html__( '(Edit)', 'bbpress' ) . '</a>';
      }

      // Implode the results of the tag data
      $current_text = sprintf( __( 'Topic Tag: %s', 'bbpress' ), implode( ' ', $tag_data ) );

    } elseif ( bbp_is_topic_tag_edit() ) {
      $current_text = __( 'Edit', 'bbpress' );
    } else {
      $current_text = get_the_title();
    }

    $r = array(
      'before'          => '',
      'after'           => '',
      'sep'             => x_get_breadcrumb_delimiter(),
      'pad_sep'         => 0,
      'sep_before'      => '',
      'sep_after'       => '',
      'crumb_before'    => '',
      'crumb_after'     => '',
      'include_home'    => false,
      'home_text'       => x_get_breadcrumb_home_text(),
      'include_root'    => true,
      'root_text'       => bbp_get_forum_archive_title(),
      'include_current' => true,
      'current_text'    => $current_text,
      'current_before'  => x_get_breadcrumb_current_before(),
      'current_after'   => x_get_breadcrumb_current_after()
    );

    return $r;

  }
endif;

add_filter( 'bbp_before_get_breadcrumb_parse_args', 'x_bbpress_filter_breadcrumbs' );



// Miscellaneous
// =============================================================================

//
// Filter the author link to remove all images.
//

if ( ! function_exists( 'x_bbpress_filter_author_link' ) ) :
  function x_bbpress_filter_author_link( $r ) {

    $r['type'] = 'name';

    return $r;

  }
endif;

add_filter( 'bbp_before_get_author_link_parse_args', 'x_bbpress_filter_author_link' );


//
// Remove new post form quicktags.
//

if ( ! function_exists( 'x_bbpress_filter_get_the_content' ) ) :
  function x_bbpress_filter_get_the_content( $r ) {

    $r['before']        = '<div class="bbp-the-content-wrapper"><label for="bbp_topic_content">' . __( 'Content', '__x__' ) . '</label>';
    $r['after']         = '</div>';
    $r['textarea_rows'] = 8;
    $r['quicktags']     = ( x_get_option( 'x_bbpress_enable_quicktags', '' ) == '' ) ? false : true;

    return $r;

  }
endif;

add_filter( 'bbp_before_get_the_content_parse_args', 'x_bbpress_filter_get_the_content' );


//
// Outputs a navigation item with quick links to bbPress-specific components
// such as the forums, et cetera.
//

if ( ! function_exists( 'x_bbpress_navbar_menu' ) ) :
  function x_bbpress_navbar_menu( $items, $args ) {

    if ( X_BBPRESS_IS_ACTIVE && x_get_option( 'x_bbpress_header_menu_enable', '' ) == '1' ) {

      $submenu_items  = '';
      $submenu_items .= '<li class="menu-item menu-item-bbpress-navigation"><a href="' . bbp_get_search_url() . '" class="cf"><i class="x-icon x-icon-search"></i> <span>' . __( 'Forums Search', '__x__' ) . '</span></a></li>';

      if ( is_user_logged_in() ) {
        $submenu_items .= '<li class="menu-item menu-item-bbpress-navigation"><a href="' . bbp_get_favorites_permalink( get_current_user_id() ) . '" class="cf"><i class="x-icon x-icon-star"></i> <span>' . __( 'Favorites', '__x__' ) . '</span></a></li>';
        $submenu_items .= '<li class="menu-item menu-item-bbpress-navigation"><a href="' . bbp_get_subscriptions_permalink( get_current_user_id() ) . '" class="cf"><i class="x-icon x-icon-bookmark"></i> <span>' . __( 'Subscriptions', '__x__' ) . '</span></a></li>';
      }

      if ( ! X_BUDDYPRESS_IS_ACTIVE || X_BUDDYPRESS_IS_ACTIVE && x_get_option( 'x_buddypress_header_menu_enable', '' ) == '' ) {
        if ( ! is_user_logged_in() ) {
          $submenu_items .= '<li class="menu-item menu-item-bbpress-navigation"><a href="' . wp_login_url() . '" class="cf"><i class="x-icon x-icon-sign-in"></i> <span>' . __( 'Log in', '__x__' ) . '</span></a></li>';
        } else {
          $submenu_items .= '<li class="menu-item menu-item-bbpress-navigation"><a href="' . bbp_get_user_profile_url( get_current_user_id() ) . '" class="cf"><i class="x-icon x-icon-cog"></i> <span>' . __( 'Profile', '__x__' ) . '</span></a></li>';
        }
      }

      if ( $args->theme_location == 'primary' ) {
        $items .= '<li class="menu-item current-menu-parent menu-item-has-children x-menu-item x-menu-item-bbpress">'
                  . '<a href="' . get_post_type_archive_link( bbp_get_forum_post_type() ) . '" class="x-btn-navbar-bbpress">'
                    . '<span><i class="x-icon x-icon-comment"></i><span class="x-hidden-desktop"> ' . __( 'Forums', '__x__' ) . '</span></span>'
                  . '</a>'
                  . '<ul class="sub-menu">'
                    . $submenu_items
                  . '</ul>'
                . '</li>';
      }
    }

    return $items;

  }
  add_filter( 'wp_nav_menu_items', 'x_bbpress_navbar_menu', 9997, 2 );
endif;                                   <?php

// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/META/SETUP.PHP
// -----------------------------------------------------------------------------
// Sets up custom meta boxes utilized throughout the theme in various areas.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Set Path
//   02. Add Entry Meta
//   03. Create Entry Meta
//   04. Save Entry Meta
//   05. Include Entry and Taxonomy Meta Box Setup
// =============================================================================

// Set Path
// =============================================================================

$meta_path = X_TEMPLATE_PATH . '/framework/functions/global/admin/meta';



// Add Entry Meta
// =============================================================================

function x_add_meta_box( $meta_box ) {

  if ( ! is_array( $meta_box ) )
    return false;

  $callback = create_function( '$post,$meta_box', 'x_create_meta_box( $post, $meta_box["args"] );' );

  add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );

}



// Create Entry Meta
// =============================================================================

function x_create_meta_box( $post, $meta_box ) {
  
  if ( ! is_array( $meta_box ) )
    return false;
    
  if ( isset( $meta_box['description'] ) && $meta_box['description'] != '' )
    echo '<p>' . $meta_box['description'] . '</p>';
    
  wp_nonce_field( basename(__FILE__), 'x_meta_box_nonce' );

  echo '<table class="form-table x-form-table">';
 
  foreach( $meta_box['fields'] as $field ) {

    $meta = get_post_meta( $post->ID, $field['id'], true );

    echo '<tr><th><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong>
        <span>' . $field['desc'] . '</span></label></th>';
    
    switch( $field['type'] ) {  
      case 'text':
        echo '<td><input type="text" name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '" value="' . ( $meta ? $meta : $field['std'] ) . '" size="30" /></td>';
        break;
        
      case 'textarea' :
        echo '<td><textarea name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '" rows="8" cols="5">' . ( $meta ? $meta : $field['std'] ) . '</textarea></td>';
        break;

      case 'select' :
        echo '<td><select name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '">';
        foreach( $field['options'] as $option ) {
          echo'<option';
          if ( $meta ) { 
            if ( $meta == $option ) echo ' selected="selected"'; 
          } else {
            if ( $field['std'] == $option ) echo ' selected="selected"'; 
          }
          echo'>' . $option . '</option>';
        }
        echo '</select></td>';
        break;
        
      case 'radio' :
        echo '<td>';
        foreach( $field['options'] as $option ) {
          echo '<label class="radio-label"><input type="radio" name="x_meta[' . $field['id'] . ']" value="' . $option . '" class="radio"';
          if ( $meta ) {
            if ( $meta == $option ) echo ' checked="yes"'; 
          } else {
            if ( $field['std'] == $option ) echo ' checked="yes"';
          }
          echo ' /> ' . $option . '</label> ';
        }
        echo '</td>';
        break;
        
      case 'checkbox' :
        echo '<td>';
        $val = '';
        if ( $meta ) {
          if ( $meta == 'on' )
            $val = ' checked="yes"';
        } else {
          if( $field['std'] == 'on' )
            $val = ' checked="yes"';
        }
        echo '<input type="hidden" name="x_meta[' . $field['id'] . ']" value="off" />
              <input type="checkbox" id="' . $field['id'] . '" name="x_meta[' . $field['id'] . ']" value="on"' . $val . ' /> ';
        echo '</td>';
        break;

      case 'color':
        echo '<td><input type="text" name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '" class="wp-color-picker" value="' . ( $meta ? $meta : $field['std'] ) . '" data-default-color="' . $field['std'] . '" size="30" /></td>';
        break;

      case 'file':
        echo '<td><input type="text" name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '" value="' . ( $meta ? $meta : $field['std'] ) . '" size="30" class="file" /> <input type="button" class="button" name="'. $field['id'] .'_button" id="'. $field['id'] .'_button" value="Browse" /></td>';
        break;

      case 'images':
        echo '<td><input type="button" class="button" name="' . $field['id'] . '" id="x_images_upload" value="' . $field['std'] .'" /></td>';
        break;

      case 'uploader' :
        GLOBAL $post;
        $output = '';
        if ( $meta != '' ) {
          $thumb = explode( ',', $meta );
          foreach ( $thumb as $thumb_image ) {
            $output .= '<div class="x-uploader-image"><img src="' . $thumb_image . '" alt="" /></div>';
          }
        }
        echo '<td>'
             . '<input type="text" name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '" value="' . ( $meta ? $meta : $field['std'] ) . '" class="file" />'
             . '<input data-id="' . get_the_ID() . '"  type="button" class="button" name="' . $field['id'] . '_button" id="' . $field['id'] . '_upload" value="Select Background Image(s)" />'
             . '<div class="x-meta-box-img-thumb-wrap">' . $output . '</div>'
           . '</td>';
        ?>

        <script> 
          jQuery(document).ready(function($) {

            var x_uploader;
            var wp_media_post_id = wp.media.model.settings.post.id;

            $('#<?php echo $field["id"] ?>_upload').on('click', function(event) {
              event.preventDefault();

              var x_button   = $(this);
              set_to_post_id = x_button.data('id');
              var target     = x_button.prev();
              
              // if media frame exists, reopen
              if ( x_uploader ) {
                // set post id
                x_uploader.uploader.uploader.param('post_id', set_to_post_id);
                x_uploader.open();
                return;
              } else {
                // set the wp.media post id so the uploader grabs the id we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
              }

              // create media frame
              x_uploader = wp.media.frames.x_uploader = wp.media({
                title: x_button.data('title'),
                button: { text: x_button.data('text') },
                multiple: true
              });

              // when image selected, run callback
              x_uploader.on('select', function(){
                var selection = x_uploader.state().get('selection');
                var files     = [];
                selection.map( function( attachment ) {
                  attachment = attachment.toJSON();
                  files.push(attachment.url);
                  x_button.prev().val(files);
                });

                x_button.next().html('');

                for (var i=0; i<files.length; i++){
                  var ext = files[i].substr(files[i].lastIndexOf(".") + 1, files[i].length);
                  x_button.next().append('<div class="row-image"><img src="' + files[i] + '" alt="" /></div>');
                }
            
                // restore main post id
                wp.media.model.settings.post.id = wp_media_post_id;
              });

              // open our awesome media frame
              x_uploader.open();
            });

            // restore main id when media button is pressed
            jQuery('a.add_media').on('click', function() {
              wp.media.model.settings.post.id = wp_media_post_id;
            });
          });  
        </script>

        <?php
        break;

      case 'select-portfolio-parent' :
        $pages = get_pages( array(
          'meta_key'    => '_wp_page_template',
          'meta_value'  => 'template-layout-portfolio.php',
          'sort_order'  => 'ASC',
          'sort_column' => 'ID'
        ) );
        echo '<td><select name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '">';
        echo '<option value="Default">Default</option>';
        foreach ( $pages as $page ) {
          echo '<option value="' . $page->ID . '"';
          if ( $meta ) {
            if ( $meta == $page->ID ) echo ' selected="selected"';
          } else {
            if ( $field['std'] == $page->ID ) echo ' selected="selected"';
          }
          echo'>' . $page->post_title . '</option>';
        }
        echo '</select></td>';
        break;

      case 'select-portfolio-category' :
        $categories = get_terms( 'portfolio-category' );
        $meta       = ( $meta == '' ) ? array( 0 => 'All Categories' ) : $meta;
        echo '<td><select name="x_meta[' . $field['id'] . '][]" id="' . $field['id'] . '" multiple="multiple">';
        echo '<option value="All Categories" ' . selected( $meta[0], 'All Categories', true ) . '>All Categories</option>';
        foreach ( $categories as $category ) {
          echo '<option value="' . $category->term_id . '"';
          if ( in_array( $category->term_id, $meta ) ) echo ' selected="selected"';
          echo'>' . $category->name . '</option>';
        }
        echo '</select></td>';
        break;

      case 'radio-portfolio-layout' :
        echo '<td>';
        foreach( $field['options'] as $key => $option ) {
          echo '<label class="radio-label"><input type="radio" name="x_meta[' . $field['id'] . ']" value="' . $key . '" class="radio"';
          if ( $meta ) {
            if ( $meta == $key ) echo ' checked="yes"';
          } else {
            if ( $field['std'] == $key ) echo ' checked="yes"';
          }
          echo ' /> ' . $option . '</label> ';
        }
        echo '</td>';
        break;

      case 'menus' :
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
        echo '<td><select name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '">';
        echo '<option>Deactivated</option>';
        foreach ( $menus as $menu ) {
          echo '<option';
          if ( $meta ) {
            if ( $meta == $menu->name ) echo ' selected="selected"';
          } else {
            if ( $field['std'] == $menu->name ) echo ' selected="selected"';
          }
          echo'>' . $menu->name . '</option>';
        }
        echo '</select></td>';
        break;

      case 'sliders' :
        $rev_slider = new RevSlider();
        $sliders    = $rev_slider->getArrSliders();
        echo '<td><select name="x_meta[' . $field['id'] . ']" id="' . $field['id'] . '">';
        echo '<option>Deactivated</option>';
        foreach ( $sliders as $slider ) {
          echo '<option';
          if ( $meta ) {
            if ( $meta == $slider->getAlias() ) echo ' selected="selected"';
          } else {
            if ( $field['std'] == $slider->getAlias() ) echo ' selected="selected"';
          }
          echo '>' . $slider->getAlias() . '</option>';
        }
        echo '</select></td>';
        break;

      default :
        do_action( 'x_add_meta_box_field_type', $field['type'] );
        break;

    }
    echo '</tr>';
  }
  echo '</table>';
}



// Save Entry Meta
// =============================================================================

function x_save_meta_box( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;
  
  if ( ! isset( $_POST['x_meta'] ) || ! isset( $_POST['x_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['x_meta_box_nonce'], basename( __FILE__ ) ) )
    return;
  
  if ( 'page' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) ) return;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
  }
 
  foreach( $_POST['x_meta'] as $key => $val ) {
    if ( is_array( $val ) ) {
      update_post_meta( $post_id, $key, $val );
    } else {
      update_post_meta( $post_id, $key, stripslashes( htmlspecialchars( $val ) ) );
    }
  }

}

add_action( 'save_post', 'x_save_meta_box' );



// Include Entry and Taxonomy Meta Box Setup
// =============================================================================

require_once( $meta_path . '/entries.php' );
require_once( $meta_path . '/taxonomies.php' );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php

// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/META/ENTRIES.PHP
// -----------------------------------------------------------------------------
// Registers the meta boxes for pages, posts, and portfolio items.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Pages
//   02. Posts
//   03. Portfolio Items
// =============================================================================

// Pages
// =============================================================================
 
function x_add_page_meta_boxes() {

  $meta_box = array(
    'id'          => 'x-meta-box-page',
    'title'       => __( 'Page Settings', '__x__' ),
    'description' => __( 'Here you will find various options you can use to create different page layouts and styles.', '__x__' ),
    'page'        => 'page',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __( 'Body CSS Class(es)', '__x__' ),
        'desc' => __( 'Add a custom CSS class to the &lt;body&gt; element. Separate multiple class names with a space.', '__x__' ),
        'id'   => '_x_entry_body_css_class',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'Alternate Index Title', '__x__' ),
        'desc' => __( 'Filling out this text input will replace the standard title on all index pages (i.e. blog, category archives, search, et cetera) with this one.', '__x__' ),
        'id'   => '_x_entry_alternate_index_title',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'Disable Page Title', '__x__' ),
        'desc' => __( 'Select to disable the page title. Disabling the page title provides greater stylistic flexibility on individual pages.', '__x__' ),
        'id'   => '_x_entry_disable_page_title',
        'type' => 'checkbox',
        'std'  => ''
      ),
      array(
        'name' => __( 'One Page Navigation', '__x__' ),
        'desc' => __( 'To activate your one page navigation, select a menu from the dropdown. To deactivate one page navigation, set the dropdown back to "Deactivated."', '__x__' ),
        'id'   => '_x_page_one_page_navigation',
        'type' => 'menus',
        'std'  => 'Deactivated'
      ),
      array(
        'name' => __( 'Background Image(s)', '__x__' ),
        'desc' => __( 'Click the button to upload your background image(s), or enter them in manually using the text field above. Loading multiple background images will create a slideshow effect. To clear, delete the image URLs from the text field and save your page.', '__x__' ),
        'id'   => '_x_entry_bg_image_full',
        'type' => 'uploader',
        'std'  => ''
      ),
      array(
        'name' => __( 'Background Image(s) Fade', '__x__' ),
        'desc' => __( 'Set a time in milliseconds for your image(s) to fade in. To disable this feature, set the value to "0."', '__x__' ),
        'id'   => '_x_entry_bg_image_full_fade',
        'type' => 'text',
        'std'  => '750'
      ),
      array(
        'name' => __( 'Background Images Duration', '__x__' ),
        'desc' => __( 'Only applicable if multiple images are selected, creating a background image slider. Set a time in milliseconds for your images to remain on screen.', '__x__' ),
        'id'   => '_x_entry_bg_image_full_duration',
        'type' => 'text',
        'std'  => '7500'
      )
    )
  );

  x_add_meta_box( $meta_box );


  //
  // Icon.
  //

  if ( x_get_stack() == 'icon' ) :

    $meta_box = array(
      'id'          => 'x-meta-box-page-icon',
      'title'       => __( 'Icon Page Settings', '__x__' ),
      'description' => __( 'Here you will find some options specific to Icon that you can use to create different page layouts.', '__x__' ),
      'page'        => 'page',
      'context'     => 'normal',
      'priority'    => 'high',
      'fields'      => array(
        array(
          'name'    => __( 'Blank Template Sidebar', '__x__' ),
          'desc'    => __( 'Because of Icon\'s unique layout, there may be times where you wish to show a sidebar on your blank templates. If that is the case, select "Yes" to activate your sidebar.', '__x__' ),
          'id'      => '_x_icon_blank_template_sidebar',
          'type'    => 'radio',
          'std'     => 'No',
          'options' => array( 'No', 'Yes' )
        )
      )
    );

    x_add_meta_box( $meta_box );

  endif;


  //
  // Sliders.
  //

  if ( X_REVOLUTION_SLIDER_IS_ACTIVE ) :

    $meta_box = array(
      'id'          => 'x-meta-box-slider-above',
      'title'       => __( 'Slider Settings: Above Masthead', '__x__' ),
      'description' => __( 'Select your options to display a slider above the masthead.', '__x__' ),
      'page'        => 'page',
      'context'     => 'normal',
      'priority'    => 'high',
      'fields'      => array(
        array(
          'name' => __( 'Slider', '__x__' ),
          'desc' => __( 'To activate your slider, select an option from the dropdown. To deactivate your slider, set the dropdown back to "Deactivated."', '__x__' ),
          'id'   => '_x_slider_above',
          'type' => 'sliders',
          'std'  => 'Deactivated'
        ),
        array(
          'name' => __( 'Optional Background Video', '__x__' ),
          'desc' => __( 'Input the URL to your .mp4 video file to display an optional background video.', '__x__' ),
          'id'   => '_x_slider_above_bg_video',
          'type' => 'text',
          'std'  => ''
        ),
        array(
          'name' => __( 'Video Poster Image (For Mobile)', '__x__' ),
          'desc' => __( 'Click the button to upload your video poster image to show on mobile devices, or enter it in manually using the text field above. Only select one image for this field. To clear, delete the image URL from the text field and save your page.', '__x__' ),
          'id'   => '_x_slider_above_bg_video_poster',
          'type' => 'uploader',
          'std'  => ''
        ),
        array(
          'name' => __( 'Enable Scroll Bottom Anchor', '__x__' ),
          'desc' => __( 'Select to enable the scroll bottom anchor for your slider.', '__x__' ),
          'id'   => '_x_slider_above_scroll_bottom_anchor_enable',
          'type' => 'checkbox',
          'std'  => ''
        ),
        array(
          'name'    => __( 'Scroll Bottom Anchor Alignment', '__x__' ),
          'desc'    => __( 'Select the alignment of the scroll bottom anchor for your slider.', '__x__' ),
          'id'      => '_x_slider_above_scroll_bottom_anchor_alignment',
          'type'    => 'select',
          'std'     => 'top left',
          'options' => array( 'top left', 'top center', 'top right', 'bottom left', 'bottom center', 'bottom right' )
        ),
        array(
          'name' => __( 'Scroll Bottom Anchor Color', '__x__' ),
          'desc' => __( 'Select the color of the scroll bottom anchor for your slider.', '__x__' ),
          'id'   => '_x_slider_above_scroll_bottom_anchor_color',
          'type' => 'color',
          'std'  => '#ffffff'
        ),
        array(
          'name' => __( 'Scroll Bottom Anchor Color Hover', '__x__' ),
          'desc' => __( 'Select the hover color of the scroll bottom anchor for your slider.', '__x__' ),
          'id'   => '_x_slider_above_scroll_bottom_anchor_color_hover',
          'type' => 'color',
          'std'  => '#ffffff'
        )
      )
    );

    x_add_meta_box( $meta_box );


    $meta_box = array(
      'id'          => 'x-meta-box-slider-below',
      'title'       => __( 'Slider Settings: Below Masthead', '__x__' ),
      'description' => __( 'Select your options to display a slider below the masthead.', '__x__' ),
      'page'        => 'page',
      'context'     => 'normal',
      'priority'    => 'high',
      'fields'      => array(
        array(
          'name' => __( 'Slider', '__x__' ),
          'desc' => __( 'To activate your slider, select an option from the dropdown. To deactivate your slider, set the dropdown back to "Deactivated."', '__x__' ),
          'id'   => '_x_slider_below',
          'type' => 'sliders',
          'std'  => 'Deactivated'
        ),
        array(
          'name' => __( 'Optional Background Video', '__x__' ),
          'desc' => __( 'Input the URL to your .mp4 video file to display an optional background video.', '__x__' ),
          'id'   => '_x_slider_below_bg_video',
          'type' => 'text',
          'std'  => ''
        ),
        array(
          'name' => __( 'Video Poster Image (For Mobile)', '__x__' ),
          'desc' => __( 'Click the button to upload your video poster image to show on mobile devices, or enter it in manually using the text field above. Only select one image for this field. To clear, delete the image URL from the text field and save your page.', '__x__' ),
          'id'   => '_x_slider_below_bg_video_poster',
          'type' => 'uploader',
          'std'  => ''
        ),
        array(
          'name' => __( 'Enable Scroll Bottom Anchor', '__x__' ),
          'desc' => __( 'Select to enable the scroll bottom anchor for your slider.', '__x__' ),
          'id'   => '_x_slider_below_scroll_bottom_anchor_enable',
          'type' => 'checkbox',
          'std'  => ''
        ),
        array(
          'name'    => __( 'Scroll Bottom Anchor Alignment', '__x__' ),
          'desc'    => __( 'Select the alignment of the scroll bottom anchor for your slider.', '__x__' ),
          'id'      => '_x_slider_below_scroll_bottom_anchor_alignment',
          'type'    => 'select',
          'std'     => 'top left',
          'options' => array( 'top left', 'top center', 'top right', 'bottom left', 'bottom center', 'bottom right' )
        ),
        array(
          'name' => __( 'Scroll Bottom Anchor Color', '__x__' ),
          'desc' => __( 'Select the color of the scroll bottom anchor for your slider.', '__x__' ),
          'id'   => '_x_slider_below_scroll_bottom_anchor_color',
          'type' => 'color',
          'std'  => '#ffffff'
        ),
        array(
          'name' => __( 'Scroll Bottom Anchor Color Hover', '__x__' ),
          'desc' => __( 'Select the hover color of the scroll bottom anchor for your slider.', '__x__' ),
          'id'   => '_x_slider_below_scroll_bottom_anchor_color_hover',
          'type' => 'color',
          'std'  => '#ffffff'
        )
      )
    );

    x_add_meta_box( $meta_box );

  endif;


  //
  // Portfolio page template.
  //

  $meta_box = array(
    'id'          => 'x-meta-box-portfolio',
    'title'       => __( 'Portfolio Settings', '__x__' ),
    'description' => __( 'Here you will find various options you can use to setup your portfolio.', '__x__' ),
    'page'        => 'page',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __( 'Category Select', '__x__' ),
        'desc' => __( 'To select multiple nonconsecutive pages or posts, hold down "CTRL" (Windows) or "COMMAND" (Mac), and then click each item you want to select. To cancel the selection of individual items, hold down "CTRL" or "COMMAND", and then click the items that you don\'t want to include.', '__x__' ),
        'id'   => '_x_portfolio_category_filters',
        'type' => 'select-portfolio-category',
        'std'  => 'All Categories'
      ),
      array(
        'name'    => __( 'Columns', '__x__' ),
        'desc'    => __( 'Select how many columns you would like to display for your portfolio.', '__x__' ),
        'id'      => '_x_portfolio_columns',
        'type'    => 'radio',
        'std'     => 'Two',
        'options' => array( 'One', 'Two', 'Three', 'Four' )
      ),
      array(
        'name'    => __( 'Layout', '__x__' ),
        'desc'    => __( 'Select the layout you would like to display for your portfolio. The "Use Global Content Layout" option allows you to keep your sidebar if you have already selected "Content Left, Sidebar Right" or "Sidebar Left, Content Right" for your "Content Layout" in the Customizer.', '__x__' ),
        'id'      => '_x_portfolio_layout',
        'type'    => 'radio-portfolio-layout',
        'std'     => 'full-width',
        'options' => array( 'sidebar' => 'Use Global Content Layout', 'full-width' => 'Fullwidth' )
      ),
      array(
        'name' => __( 'Posts Per Page', '__x__' ),
        'desc' => __( 'Select how many posts you would like to display per page for your portfolio.', '__x__' ),
        'id'   => '_x_portfolio_posts_per_page',
        'type' => 'text',
        'std'  => '24'
      ),
      array(
        'name' => __( 'Disable Filtering', '__x__' ),
        'desc' => __( 'Turning off the portfolio filters will remove the ability to sort portfolio items by category.', '__x__' ),
        'id'   => '_x_portfolio_disable_filtering',
        'type' => 'checkbox',
        'std'  => ''
      )
    )
  );

  x_add_meta_box( $meta_box );

}

add_action( 'add_meta_boxes', 'x_add_page_meta_boxes' );



// Posts
// =============================================================================
 
function x_add_post_meta_boxes() {

  $meta_box = array(
    'id'          => 'x-meta-box-post',
    'title'       => __( 'Post Settings', '__x__' ),
    'description' => __( 'Here you will find various options you can use to create different page styles.', '__x__' ),
    'page'        => 'post',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __( 'Body CSS Class(es)', '__x__' ),
        'desc' => __( 'Add a custom CSS class to the &lt;body&gt; element. Separate multiple class names with a space.', '__x__' ),
        'id'   => '_x_entry_body_css_class',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'Fullwidth Post Layout', '__x__' ),
        'desc' => __( 'If your global content layout includes a sidebar, selecting this option will remove the sidebar for this post.', '__x__' ),
        'id'   => '_x_post_layout',
        'type' => 'checkbox',
        'std'  => ''
      ),
      array(
        'name' => __( 'Alternate Index Title', '__x__' ),
        'desc' => __( 'Filling out this text input will replace the standard title on all index pages (i.e. blog, category archives, search, et cetera) with this one.', '__x__' ),
        'id'   => '_x_entry_alternate_index_title',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'Background Image(s)', '__x__' ),
        'desc' => __( 'Click the button to upload your background image(s), or enter them in manually using the text field above. Loading multiple background images will create a slideshow effect. To clear, delete the image URLs from the text field and save your page.', '__x__' ),
        'id'   => '_x_entry_bg_image_full',
        'type' => 'uploader',
        'std'  => ''
      ),
      array(
        'name' => __( 'Background Image(s) Fade', '__x__' ),
        'desc' => __( 'Set a time in milliseconds for your image(s) to fade in. To disable this feature, set the value to "0."', '__x__' ),
        'id'   => '_x_entry_bg_image_full_fade',
        'type' => 'text',
        'std'  => '750'
      ),
      array(
        'name' => __( 'Background Images Duration', '__x__' ),
        'desc' => __( 'Only applicable if multiple images are selected, creating a background image slider. Set a time in milliseconds for your images to remain on screen.', '__x__' ),
        'id'   => '_x_entry_bg_image_full_duration',
        'type' => 'text',
        'std'  => '7500'
      )
    )
  );

  x_add_meta_box( $meta_box );


  //
  // Quote.
  //

  $meta_box = array(
    'id'          => 'x-meta-box-quote',
    'title'       => __( 'Quote Post Settings', '__x__' ),
    'description' => __( 'Input your quote.', '__x__' ),
    'page'        => 'post',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __( 'The Quote', '__x__' ),
        'desc' => __( 'Input your quote.', '__x__' ),
        'id'   => '_x_quote_quote',
        'type' => 'textarea',
        'std'  => ''
      ),
      array(
        'name' => __( 'Citation', '__x__' ),
        'desc' => __( 'Specify who originally said the quote.', '__x__' ),
        'id'   => '_x_quote_cite',
        'type' => 'text',
        'std'  => ''
      )
    )
  );

  x_add_meta_box( $meta_box );


  //
  // Link.
  //

  $meta_box = array(
    'id'          => 'x-meta-box-link',
    'title'       => __( 'Link Post Settings', '__x__' ),
    'description' => __( 'Input your link.', '__x__' ),
    'page'        => 'post',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __( 'The Link', '__x__' ),
        'desc' => __( 'Insert your link URL, e.g. http://www.themeforest.net.', '__x__' ),
        'id'   => '_x_link_url',
        'type' => 'text',
        'std'  => ''
      )
    )
  );

  x_add_meta_box( $meta_box );


  //
  // Video.
  //

  $meta_box = array(
    'id'          => 'x-meta-box-video',
    'title'       => __( 'Video Post Settings', '__x