bars that you have created.</p></div>';
  endif;

}



// Checked List Item
// =============================================================================

function ups_checked_list_item( $needle, $haystack ) {

  if ( array_key_exists( $needle, $haystack ) ) {
    $output = ' checked="checked"';
  } else {
    $output = '';
  }

  return $output;

}



// Print Scripts in Footer
// =============================================================================

function ups_print_scripts_in_footer() {
  echo '<script>jQuery(document).ready(function(){ postboxes.add_postbox_toggles(pagenow); });</script>';
}

add_action( 'admin_footer-appearance_page_ups_sidebars', 'ups_print_scripts_in_footer' );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php

// =============================================================================
// FUNCTIONS/GLOBAL/PORTFOLIO.PHP
// -----------------------------------------------------------------------------
// Portfolio related functions beyond custom post type setup.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Get the Page Link to First Portfolio Page
//   02. Get the Page Title to First Portfolio Page
//   03. Get Parent Portfolio ID
//   04. Get Parent Portfolio Link
//   05. Get Parent Portfolio Title
//   06. Output Portfolio Filters
//   07. Output Portfolio Item Featured Content
//   08. Output Portfolio Item Project Link
//   09. Output Portfolio Item Tags
//   10. Output Portfolio Item Social
//   11. Portfolio Page Template Precedence
// =============================================================================

// Get the Page Link to First Portfolio Page
// =============================================================================

function x_get_first_portfolio_page_link() {

  $results = get_pages( array(
    'meta_key'    => '_wp_page_template',
    'meta_value'  => 'template-layout-portfolio.php',
    'sort_order'  => 'ASC',
    'sort_column' => 'ID'
  ) );

  return get_page_link( $results[0]->ID );

}



// Get the Page Title to First Portfolio Page
// =============================================================================

function x_get_first_portfolio_page_title() {

  $results = get_pages( array(
    'meta_key'    => '_wp_page_template',
    'meta_value'  => 'template-layout-portfolio.php',
    'sort_order'  => 'ASC',
    'sort_column' => 'ID'
  ) );

  return $results[0]->post_title;

}



// Get Parent Portfolio ID
// =============================================================================

function x_get_parent_portfolio_id() {

  $meta      = get_post_meta( get_the_ID(), '_x_portfolio_parent', true );
  $parent_id = ( $meta ) ? $meta : 'Default';

  return $parent_id;

}



// Get Parent Portfolio Link
// =============================================================================

function x_get_parent_portfolio_link() {

  $parent_id = x_get_parent_portfolio_id();
  $link      = ( $parent_id != 'Default' ) ? get_permalink( $parent_id ) : x_get_first_portfolio_page_link();

  return $link;

}



// Get Parent Portfolio Title
// =============================================================================

function x_get_parent_portfolio_title() {

  $parent_id = x_get_parent_portfolio_id();
  $title     = ( $parent_id != 'Default' ) ? get_the_title( $parent_id ) : x_get_first_portfolio_page_title();

  return $title;

}



// Output Portfolio Filters
// =============================================================================

function x_portfolio_filters() {

  $stack           = x_get_stack();
  $filters         = get_post_meta( get_the_ID(), '_x_portfolio_category_filters', true );
  $disable_filters = get_post_meta( get_the_ID(), '_x_portfolio_disable_filtering', true );
  $one_filter      = count( $filters ) == 1;
  $all_categories  = in_array( 'All Categories', $filters );


  //
  // 1. If one filter is selected and that filter is "All Categories."
  // 2. If one filter is selected and that filter is a category.
  // 3. If more than one category filter is selected.
  //

  if ( $one_filter && $all_categories ) { // 1

    $terms = get_terms( 'portfolio-category' );

  } elseif ( $one_filter && ! $all_categories ) { // 2

    $terms = array();
    foreach ( $filters as $filter ) {
      $children = get_term_children( $filter, 'portfolio-category' );
      $terms    = array_merge( $children, $terms );
    }
    $terms = get_terms( 'portfolio-category', array( 'include' => $terms ) );

  } else { // 3

    $terms = array();
    foreach ( $filters as $filter ) {
      $parent   = array( $filter );
      $children = get_term_children( $filter, 'portfolio-category' );
      $terms    = array_merge( $parent, $terms );
      $terms    = array_merge( $children, $terms );
    }
    $terms = get_terms( 'portfolio-category', array( 'include' => $terms ) );

  }


  //
  // Main filter button content.
  //

  if ( $stack == 'integrity' ) {
    $button_content = '<i class="x-icon-sort"></i> <span>' . x_get_option( 'x_integrity_portfolio_archive_sort_button_text', __( 'Sort Portfolio', '__x__' ) ) . '</span>';
  } elseif ( $stack == 'ethos' ) {
    $button_content = '<i class="x-icon-chevron-down"></i>';
  } else {
    $button_content = '<i class="x-icon-plus"></i>';
  }


  //
  // Filters.
  //

  if ( $disable_filters != 'on' ) {
    if ( $stack != 'ethos' ) {

    ?>

      <ul class="option-set unstyled" data-option-key="filter">
        <li>
          <a href="#" class="x-portfolio-filters"><?php echo $button_content; ?></a>
          <ul class="x-portfolio-filters-menu unstyled">
            <li><a href="#" data-option-value="*" class="x-portfolio-filter selected"><?php _e( 'All', '__x__' ); ?></a></li>
            <?php foreach ( $terms as $term ) { ?>
              <li><a href="#" data-option-value=".x-portfolio-<?php echo md5( $term->slug ); ?>" class="x-portfolio-filter"><?php echo $term->name; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>

    <?php } elseif ( $stack == 'ethos' ) { ?>

      <ul class="option-set unstyled" data-option-key="filter">
        <li>
          <a href="#" class="x-portfolio-filters cf">
            <span class="x-portfolio-filter-label"><?php _e( 'Filter by Category', '__x__' ); ?></span>
            <?php echo $button_content; ?>
          </a>
          <ul class="x-portfolio-filters-menu unstyled">
            <li><a href="#" data-option-value="*" class="x-portfolio-filter selected"><?php _e( 'All', '__x__' ); ?></a></li>
            <?php foreach ( $terms as $term ) { ?>
              <li><a href="#" data-option-value=".x-portfolio-<?php echo md5( $term->slug ); ?>" class="x-portfolio-filter"><?php echo $term->name; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>

    <?php

    }
  }

}



// Output Portfolio Item Featured Content
// =============================================================================

function x_portfolio_item_featured_content() {

  if ( x_get_option( 'x_portfolio_enable_cropped_thumbs', '' ) == '1' ) :
    x_featured_portfolio( 'cropped' );
  else :
    x_featured_portfolio();
  endif;

}



// Output Portfolio Item Project Link
// =============================================================================

function x_portfolio_item_project_link() {

  $project_link  = get_post_meta( get_the_ID(), '_x_portfolio_project_link', true );
  $launch_title  = x_get_option( 'x_portfolio_launch_project_title', __( 'Launch Project', '__x__' ) );
  $launch_button = x_get_option( 'x_portfolio_launch_project_button_text', __( 'See it Live!', '__x__' ) );

  if ( $project_link ) :

  ?>

  <h2 class="h-extra launch"><?php echo $launch_title; ?></h2>
  <a href="<?php echo $project_link; ?>" title="<?php echo $launch_button; ?>" class="x-btn x-btn-block" target="_blank"><?php echo $launch_button; ?></a>

  <?php

  endif;

}



// Output Portfolio Item Tags
// =============================================================================

function x_portfolio_item_tags() {

  $stack     = x_get_stack();
  $tag_title = x_get_option( 'x_portfolio_tag_title', __( 'Skills', '__x__' ) );

  if ( has_term( NULL, 'portfolio-tag', NULL ) ) :

    echo '<h2 class="h-extra skills">' . $tag_title . '</h2>';
    call_user_func( 'x_' . $stack . '_portfolio_tags');

  endif;

}



// Output Portfolio Item Social
// =============================================================================

function x_portfolio_item_social() {

  $share_project_title = x_get_option( 'x_portfolio_share_project_title', __( 'Share this Project', '__x__' ) );
  $enable_facebook     = x_get_option( 'x_portfolio_enable_facebook_sharing', '1' );
  $enable_twitter      = x_get_option( 'x_portfolio_enable_twitter_sharing', '1' );
  $enable_google_plus  = x_get_option( 'x_portfolio_enable_google_plus_sharing', '' );
  $enable_linkedin     = x_get_option( 'x_portfolio_enable_linkedin_sharing', '' );
  $enable_pinterest    = x_get_option( 'x_portfolio_enable_pinterest_sharing', '' );
  $enable_reddit       = x_get_option( 'x_portfolio_enable_reddit_sharing', '' );
  $enable_email        = x_get_option( 'x_portfolio_enable_email_sharing', '' );

  $share_url     = urlencode( get_permalink() );
  $share_title   = urlencode( get_the_title() );
  $share_source  = urlencode( get_bloginfo( 'name' ) );
  $share_content = urlencode( get_the_excerpt() );
  $share_media   = wp_get_attachment_thumb_url( get_post_thumbnail_id() );

  $facebook    = ( $enable_facebook == '1' )    ? "<a href=\"#share\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-trigger=\"hover\" class=\"x-share\" title=\"" . __( 'Share on Facebook', '__x__' ) . "\" onclick=\"window.open('http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}', 'popupFacebook', 'width=650, height=270, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0'); return false;\"><i class=\"x-icon-facebook-square\"></i></a>" : '';
  $twitter     = ( $enable_twitter == '1' )     ? "<a href=\"#share\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-trigger=\"hover\" class=\"x-share\" title=\"" . __( 'Share on Twitter', '__x__' ) . "\" onclick=\"window.open('https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}', 'popupTwitter', 'width=500, height=370, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0'); return false;\"><i class=\"x-icon-twitter-square\"></i></a>" : '';
  $google_plus = ( $enable_google_plus == '1' ) ? "<a href=\"#share\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-trigger=\"hover\" class=\"x-share\" title=\"" . __( 'Share on Google+', '__x__' ) . "\" onclick=\"window.open('https://plus.google.com/share?url={$share_url}', 'popupGooglePlus', 'width=650, height=226, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0'); return false;\"><i class=\"x-icon-google-plus-square\"></i></a>" : '';
  $linkedin    = ( $enable_linkedin == '1' )    ? "<a href=\"#share\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-trigger=\"hover\" class=\"x-share\" title=\"" . __( 'Share on LinkedIn', '__x__' ) . "\" onclick=\"window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url={$share_url}&amp;title={$share_title}&amp;summary={$share_content}&amp;source={$share_source}', 'popupLinkedIn', 'width=610, height=480, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0'); return false;\"><i class=\"x-icon-linkedin-square\"></i></a>" : '';
  $pinterest   = ( $enable_pinterest == '1' )   ? "<a href=\"#share\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-trigger=\"hover\" class=\"x-share\" title=\"" . __( 'Share on Pinterest', '__x__' ) . "\" onclick=\"window.open('http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}', 'popupPinterest', 'width=750, height=265, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0'); return false;\"><i class=\"x-icon-pinterest-square\"></i></a>" : '';
  $reddit      = ( $enable_reddit == '1' )      ? "<a href=\"#share\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-trigger=\"hover\" class=\"x-share\" title=\"" . __( 'Share on Reddit', '__x__' ) . "\" onclick=\"window.open('http://www.reddit.com/submit?url={$share_url}', 'popupReddit', 'width=875, height=450, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0'); return false;\"><i class=\"x-icon-reddit-square\"></i></a>" : '';
  $email       = ( $enable_email == '1' )       ? "<a href=\"mailto:?subject=" . get_the_title() . "&amp;body=" . __( 'Hey, thought you might enjoy this! Check it out when you have a chance:', '__x__' ) . " " . get_permalink() . "\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-trigger=\"hover\" class=\"x-share email\" title=\"" . __( 'Share via Email', '__x__' ) . "\"><span><i class=\"x-icon-envelope-square\"></i></span></a>" : '';

  if ( $enable_facebook == '1' || $enable_twitter == '1' || $enable_google_plus == '1' || $enable_linkedin == '1' || $enable_pinterest == '1' || $enable_reddit == '1' || $enable_email == '1' ) :

    ?>

    <div class="x-entry-share man">
      <div class="x-share-options">
        <p><?php echo $share_project_title; ?></p>
        <?php echo $facebook . $twitter . $google_plus . $linkedin . $pinterest . $reddit . $email; ?>
      </div>
    </div>

    <?php

  endif;

}



// Portfolio Page Template Precedence
// =============================================================================

//
// Allows a user to set their Custom Portfolio Slug to the same value as their
// page slug. When the x-portfolio post type is found, it gives precedence to
// the portfolio template page instead of the archive page.
//

function x_portfolio_page_template_precedence( $request ) {

  if ( array_key_exists( 'post_type', $request ) && 'x-portfolio' == $request['post_type'] ) {

      $page_slug = basename( $_SERVER['REQUEST_URI'] );

      if ( get_page_by_path( $page_slug ) && ( x_get_option( 'x_custom_portfolio_slug' ) == $page_slug ) ) {
        unset( $request['post_type'] );
        $request['pagename'] = $page_slug;
      }

  }

  return $request;

}

add_filter( 'request', 'x_portfolio_page_template_precedence' );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php

// =============================================================================
// FUNCTIONS/ETHOS.PHP
// -----------------------------------------------------------------------------
// Ethos specific functions.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Entry Meta
//   02. Entry Cover Background Image Style
//   03. Entry Cover
//   04. Entry Top Navigation
//   05. Featured Index Content
//   06. Post Categories
//   07. Category Accent Color
//   08. Portfolio Tags
//   09. Individual Comment
// =============================================================================

// Entry Meta
// =============================================================================

if ( ! function_exists( 'x_ethos_entry_meta' ) ) :
  function x_ethos_entry_meta() {

    //
    // Author.
    //

    $author = sprintf( ' %1$s %2$s</span>',
      __( 'by', '__x__' ),
      get_the_author()
    );


    //
    // Date.
    //

    $date = sprintf( '<span><time class="entry-date" datetime="%1$s">%2$s</time></span>',
      esc_attr( get_the_date( 'c' ) ),
      esc_html( get_the_date() )
    );


    //
    // Categories.
    //

    if ( get_post_type() == 'x-portfolio' ) {
      if ( has_term( '', 'portfolio-category', NULL ) ) {
        $categories        = get_the_terms( get_the_ID(), 'portfolio-category' );
        $separator         = ', ';
        $categories_output = '';
        foreach ( $categories as $category ) {
          $categories_output .= '<a href="'
                              . get_term_link( $category->slug, 'portfolio-category' )
                              . '" title="'
                              . esc_attr( sprintf( __( "View all posts in: &ldquo;%s&rdquo;", '__x__' ), $category->name ) )
                              . '"> '
                              . $category->name
                              . '</a>'
                              . $separator;
        }

        $categories_list = sprintf( '<span>%1$s %2$s',
          __( 'In', '__x__' ),
          trim( $categories_output, $separator )
        );
      } else {
        $categories_list = '';
      }
    } else {
      $categories        = get_the_category();
      $separator         = ', ';
      $categories_output = '';
      foreach ( $categories as $category ) {
        $categories_output .= '<a href="'
                            . get_category_link( $category->term_id )
                            . '" title="'
                            . esc_attr( sprintf( __( "View all posts in: &ldquo;%s&rdquo;", '__x__' ), $category->name ) )
                            . '"> '
                            . $category->name
                            . '</a>'
                            . $separator;
      }

      $categories_list = sprintf( '<span>%1$s %2$s',
        __( 'In', '__x__' ),
        trim( $categories_output, $separator )
      );
    }


    //
    // Comments link.
    //

    if ( comments_open() ) {

      $title  = apply_filters( 'x_entry_meta_comments_title', get_the_title() );
      $link   = apply_filters( 'x_entry_meta_comments_link', get_comments_link() );
      $number = apply_filters( 'x_entry_meta_comments_number', get_comments_number() );

      if ( $number == 0 ) {
        $text = __( 'Leave a Comment' , '__x__' );
      } else if ( $number == 1 ) {
        $text = $number . ' ' . __( 'Comment' , '__x__' );
      } else {
        $text = $number . ' ' . __( 'Comments' , '__x__' );
      }

      $comments = sprintf( '<span><a href="%1$s" title="%2$s" class="meta-comments">%3$s</a></span>',
        esc_url( $link ),
        esc_attr( sprintf( __( 'Leave a comment on: &ldquo;%s&rdquo;', '__x__' ), $title ) ),
        $text
      );

    } else {

      $comments = '';

    }


    //
    // Output.
    //

    if ( x_does_not_need_entry_meta() ) {
      return;
    } else {
      printf( '<p class="p-meta">%1$s%2$s%3$s%4$s</p>',
        $categories_list,
        $author,
        $date,
        $comments
      );
    }

  }
endif;



// Entry Cover Background Image Style
// =============================================================================

if ( ! function_exists( 'x_ethos_entry_cover_background_image_style' ) ) :
  function x_ethos_entry_cover_background_image_style() {

    $featured_image   = x_make_protocol_relative( x_get_featured_image_url() );
    $background_image = ( $featured_image != '' ) ? 'background-image: url(' . $featured_image . ');' : 'background-image: none;';

    return $background_image;

  }
endif;



// Entry Cover
// =============================================================================

if ( ! function_exists( 'x_ethos_entry_cover' ) ) :
  function x_ethos_entry_cover( $location ) {

    if ( $location == 'main-content' ) { ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <a class="entry-cover" href="<?php the_permalink(); ?>" style="<?php echo x_ethos_entry_cover_background_image_style(); ?>">
          <h2 class="h-entry-cover"><span><?php x_the_alternate_title(); ?></span></h2>
        </a>
      </article>

    <?php } elseif ( $location == 'post-carousel' ) { ?>

      <?php GLOBAL $post_carousel_entry_id; ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <a class="entry-cover" href="<?php the_permalink(); ?>" style="<?php echo x_ethos_entry_cover_background_image_style(); ?>">
          <h2 class="h-entry-cover"><span><?php ( $post_carousel_entry_id == get_the_ID() ) ? the_title() : x_the_alternate_title(); ?></span></h2>
          <div class="x-post-carousel-meta">
            <span class="entry-cover-author"><?php echo get_the_author(); ?></span>
            <span class="entry-cover-categories"><?php echo x_ethos_post_categories(); ?></span>
            <span class="entry-cover-date"><?php echo get_the_date( 'F j, Y' ); ?></span>
          </div>
        </a>
      </article>

    <?php }

  }
endif;



// Entry Top Navigation
// =============================================================================

if ( ! function_exists( 'x_ethos_entry_top_navigation' ) ) :
  function x_ethos_entry_top_navigation() {

    if ( x_is_portfolio_item() ) {
      $link = x_get_parent_portfolio_link();
    } elseif ( x_is_product() ) {
      $link = x_get_shop_link();
    }

    $title = esc_attr( __( 'See All Posts', '__x__' ) );

    ?>

      <div class="entry-top-navigation">
        <a href="<?php echo $link; ?>" class="entry-parent" title="<?php $title; ?>"><i class="x-icon-th"></i></a>
        <?php x_entry_navigation(); ?>
      </div>

    <?php

  }
endif;



// Featured Index Content
// =============================================================================

if ( ! function_exists( 'x_ethos_featured_index' ) ) :
  function x_ethos_featured_index() {

    $entry_id                    = get_the_ID();
    $index_featured_layout       = get_post_meta( $entry_id, '_x_ethos_index_featured_post_layout', true );
    $index_featured_size         = get_post_meta( $entry_id, '_x_ethos_index_featured_post_size', true );
    $index_featured_layout_class = ( $index_featured_layout == 'on' ) ? ' featured' : '';
    $index_featured_size_class   = ( $index_featured_layout == 'on' ) ? ' ' . strtolower( $index_featured_size ) : '';
    $is_index_featured_layout    = $index_featured_layout == 'on' && ! is_single();

    ?>

      <a href="<?php the_permalink(); ?>" class="entry-thumb<?php echo $index_featured_layout_class; echo $index_featured_size_class; ?>" style="<?php echo x_ethos_entry_cover_background_image_style(); ?>">
        <?php if ( $is_index_featured_layout ) : ?>  
          <span class="featured-meta"><?php echo x_ethos_post_categories(); ?> / <?php echo get_the_date( 'F j, Y' ); ?></span>
          <h2 class="h-featured"><span><?php x_the_alternate_title(); ?></span></h2>
          <span class="featured-view"><?php _e( 'View Post', '__x__' ); ?></span>
        <?php else : ?>
          <span class="view"><?php _e( 'View Post', '__x__' ); ?></span>
        <?php endif; ?>
      </a>

    <?php

  }
endif;



// Post Categories
// =============================================================================

if ( ! function_exists( 'x_ethos_post_categories' ) ) :
  function x_ethos_post_categories() {

    $categories      = get_the_terms( get_the_ID(), 'category' );
    $separator       = ', ';
    $categories_list = '';

    foreach ( $categories as $category ) {
      $categories_list .= $category->name . $separator;
    }

    $categories_output = trim( $categories_list, $separator );

    return $categories_output;

  }
endif;



// Category Accent Color
// =============================================================================

if ( ! function_exists( 'x_ethos_category_accent_color' ) ) :
  function x_ethos_category_accent_color( $category_id, $fallback_color = '#ffffff' ) {

    $t_id      = $category_id;
    $term_meta = get_option( 'taxonomy_' . $t_id );
    $accent    = ( $term_meta['accent'] != '' ) ? $term_meta['accent'] : $fallback_color;

    return $accent;

  }
endif;



// Portfolio Tags
// =============================================================================

if ( ! function_exists( 'x_ethos_portfolio_tags' ) ) :
  function x_ethos_portfolio_tags() {

    $terms = get_the_terms( get_the_ID(), 'portfolio-tag' );

    echo '<ul class="x-ul-tags">';
    foreach( $terms as $term ) {
      echo '<li><a href="' . get_term_link( $term->slug, 'portfolio-tag' ) . '">' . $term->name . '</a></li>';
    };
    echo '</ul>';

  }
endif;



// Individual Comment
// =============================================================================

//
// 1. Pingbacks and trackbacks.
// 2. Normal Comments.
//

if ( ! function_exists( 'x_ethos_comment' ) ) :
  function x_ethos_comment( $comment, $args, $depth ) {

    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
      case 'pingback' :  // 1
      case 'trackback' : // 1
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
      <p><?php _e( 'Pingback:', '__x__' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', '__x__' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
        break;
      default : // 2
      GLOBAL $post;
      if ( X_WOOCOMMERCE_IS_ACTIVE ) :
        $rating = esc_attr( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) );
      endif;
    ?>
    <li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
      <article id="comment-<?php comment_ID(); ?>" class="comment">
        <?php
        printf( '<div class="x-comment-img">%1$s %2$s</div>',
          '<span class="avatar-wrap cf">' . get_avatar( $comment, 120 ) . '</span>',
          ( $comment->user_id === $post->post_author ) ? '<span class="bypostauthor">' . __( 'Author', '__x__' ) . '</span>' : ''
        );
        ?>
        <div class="x-comment-content-wrap">
          <header class="x-comment-header">
            <div class="x-comment-meta">
              <?php
              printf( '<a href="%1$s" class="x-comment-time"><time datetime="%2$s">%3$s</time></a>',
                esc_url( get_comment_link( $comment->comment_ID ) ),
                get_comment_time( 'c' ),
                sprintf( __( '%1$s', '__x__' ),
                  get_comment_date()
                )
              );
              ?>
              <?php if ( ! x_is_product() ) : ?>
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span class="comment-reply-link-after"><i class="x-icon-reply"></i></span>', '__x__' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
              <?php endif; ?>
              <?php edit_comment_link( __( 'Edit <i class="x-icon-edit"></i>', '__x__' ) ); ?>
            </div>
            <?php
            printf( '<cite class="x-comment-author">%1$s</cite>',
              get_comment_author_link()
            );
            ?>
            <?php if ( x_is_product() && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>
              <div class="star-rating-container">
                <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', '__x__' ), $rating ) ?>">
                  <span style="width:<?php echo ( intval( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) ) / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo intval( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) ); ?></strong> <?php _e( 'out of 5', '__x__' ); ?></span>
                </div>
              </div>
            <?php endif; ?>
          </header>
          <?php if ( '0' == $comment->comment_approved ) : ?>
            <p class="x-comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', '__x__' ); ?></p>
          <?php endif; ?>
          <section class="x-comment-content">
            <?php comment_text(); ?>
          </section>
        </div>
      </article>
    <?php
        break;
    endswitch;

  }
endif;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  body .gform_wrapper{max-width:100%;margin:0 0 1.5em}body .gform_wrapper .gform_heading{width:100%}body .gform_wrapper .top_label .gfield,body .gform_wrapper .top_label li.gsection.gf_scroll_text{margin-bottom:1em !important}body .gform_wrapper .left_label .gfield,body .gform_wrapper .left_label li.gsection.gf_scroll_text,body .gform_wrapper .right_label .gfield,body .gform_wrapper .right_label li.gsection.gf_scroll_text{margin-bottom:1.5em !important}body .gform_wrapper .top_label .gsection,body .gform_wrapper .top_label li.gfield.gf_left_half+li.gsection,body .gform_wrapper .top_label li.gfield.gf_right_half+li.gsection{margin-top:0 !important;margin-bottom:4px !important;border-bottom:1px solid #e5e5e5;padding:28px 0 4px}body .gform_wrapper .left_label .gsection,body .gform_wrapper .left_label li.gfield.gf_left_half+li.gsection,body .gform_wrapper .left_label li.gfield.gf_right_half+li.gsection,body .gform_wrapper .right_label .gsection,body .gform_wrapper .right_label li.gfield.gf_left_half+li.gsection,body .gform_wrapper .right_label li.gfield.gf_right_half+li.gsection{margin-top:0 !important;margin-bottom:10px !important;border-bottom:1px solid #e5e5e5;padding:28px 0 6px}body .gform_wrapper .gform_footer{margin:1.5em 0 0;padding:0}body .gform_wrapper .gform_footer.left_label{padding:0 0 0 30%}@media (max-width: 767px){body .gform_wrapper .gform_footer.left_label{padding:0}}body .gform_wrapper .gform_footer.right_label{padding:0 30% 0 0}@media (max-width: 767px){body .gform_wrapper .gform_footer.right_label{padding:0}}body .gform_wrapper h3.gform_title,body .gform_wrapper h2.gsection_title,body .gform_wrapper .gsection_description{width:100%}body .gform_wrapper h3.gform_title{margin:0 0 0.2em;font-size:200%}@media (max-width: 480px){body .gform_wrapper h3.gform_title{font-size:175%}}body .gform_wrapper h2.gsection_title{font-size:125%}body .gform_wrapper .top_label .gfield_label{margin:0}body .gform_wrapper .left_label .gfield_label,body .gform_wrapper .right_label .gfield_label{width:30%;padding:0 4% 0 0;font-size:82.5%}@media (max-width: 767px){body .gform_wrapper .left_label .gfield_label,body .gform_wrapper .right_label .gfield_label{margin-bottom:0}}body .gform_wrapper .left_label .gfield_label{float:left;margin-right:0;padding:0 4% 0 0}@media (max-width: 767px){body .gform_wrapper .left_label .gfield_label{float:none;width:100%;padding:0}}body .gform_wrapper .right_label .gfield_label{float:right;margin-left:0;padding:0 0 0 4%;text-align:right}@media (max-width: 767px){body .gform_wrapper .right_label .gfield_label{float:none;width:100%;padding:0;text-align:left}}body .gform_wrapper li.gfield.gf_list_2col label.gfield_label,body .gform_wrapper li.gfield.gf_list_3col label.gfield_label,body .gform_wrapper li.gfield.gf_list_4col label.gfield_label,body .gform_wrapper li.gfield.gf_list_5col label.gfield_label,body .gform_wrapper li.gfield.gf_list_inline label.gfield_label{margin-top:0}body .gform_wrapper .ginput_complex label,body .gform_wrapper .description,body .gform_wrapper .gfield_description,body .gform_wrapper .gsection_description,body .gform_wrapper .instruction{font-family:inherit;font-size:67.5%;opacity:0.7}body .gform_wrapper .description,body .gform_wrapper .gsection_description{padding:5px 0 0}body .gform_wrapper .gfield_description{padding:2px 0 0}body .gform_wrapper .description_above .gfield_description{padding:0 0 2px}body .gform_wrapper .left_label .instruction,body .gform_wrapper .left_label .gfield_description,body .gform_wrapper .left_label li.gsection.gf_scroll_text{width:70% !important;margin-left:30% !important;margin-right:0 !important}@media (max-width: 767px){body .gform_wrapper .left_label .instruction,body .gform_wrapper .left_label .gfield_description,body .gform_wrapper .left_label li.gsection.gf_scroll_text{width:100% !important;margin-left:0 !important}}body .gform_wrapper .right_label .instruction,body .gform_wrapper .right_label .gfield_description,body .gform_wrapper .right_label li.gsection.gf_scroll_text{width:70% !important;margin-left:0 !important;margin-right:30% !important}@media (max-width: 767px){body .gform_wrapper .right_label .instruction,body .gform_wrapper .right_label .gfield_description,body .gform_wrapper .right_label li.gsection.gf_scroll_text{width:100% !important;margin-right:0 !important}}body .gform_wrapper .ginput_complex label,body .gform_wrapper .gfield_time_hour label,body .gform_wrapper .gfield_time_minute label,body .gform_wrapper .gfield_date_month label,body .gform_wrapper .gfield_date_day label,body .gform_wrapper .gfield_date_year label,body .gform_wrapper .instruction{margin:0}body .gform_wrapper .gfield_radio li label,body .gform_wrapper .gfield_checkbox li label{font-size:13px;font-size:1.3rem}body .gform_wrapper .ginput_full input:focus+label,body .gform_wrapper .ginput_left input:focus+label,body .gform_wrapper .ginput_right input:focus+label{font-weight:inherit}body .gform_wrapper ul.gfield_radio li input[type="radio"]:checked+label,body .gform_wrapper ul.gfield_checkbox li input[type="checkbox"]:checked+label{font-weight:inherit}body .gform_wrapper.gf_browser_chrome .gfield_checkbox li label,body .gform_wrapper.gf_browser_chrome .gfield_radio li label,body .gform_wrapper.gf_browser_safari .gfield_checkbox li label,body .gform_wrapper.gf_browser_safari .gfield_radio li label{margin-top:2px}body .gform_wrapper input[type=text],body .gform_wrapper input[type=url],body .gform_wrapper input[type=email],body .gform_wrapper input[type=tel],body .gform_wrapper input[type=number],body .gform_wrapper input[type=password],body .gform_wrapper select,body .gform_wrapper textarea{display:inline-block;height:2.65em;margin:3px 0;padding:0 0.65em;line-height:2.65em;font-size:13px;font-size:1.3rem}body .gform_wrapper select[multiple],body .gform_wrapper select[size]{height:auto}body .gform_wrapper.gf_browser_gecko select{padding:0.45em 0.65em}body .gform_wrapper .top_label li.gfield.gf_left_half,body .gform_wrapper .top_label li.gfield.gf_left_third,body .gform_wrapper .top_label li.gfield.gf_middle_third{margin-right:4%}body .gform_wrapper .top_label li.gfield.gf_left_half,body .gform_wrapper .top_label li.gfield.gf_right_half,body .gform_wrapper .top_label li.gfield.gf_left_third,body .gform_wrapper .top_label li.gfield.gf_middle_third,body .gform_wrapper .top_label li.gfield.gf_right_third{float:left;margin-left:0 !important}body .gform_wrapper li.gfield .ginput_complex .ginput_full,body .gform_wrapper li.gfield .ginput_complex .ginput_left,body .gform_wrapper li.gfield .ginput_complex .ginput_right{margin-bottom:8px}body .gform_wrapper li.gfield .ginput_complex .ginput_full+.ginput_left,body .gform_wrapper li.gfield .ginput_complex .ginput_left+.ginput_left,body .gform_wrapper li.gfield .ginput_complex .ginput_right+.ginput_left{clear:left}body .gform_wrapper li.gfield .ginput_complex .ginput_full+.ginput_right,body .gform_wrapper li.gfield .ginput_complex .ginput_left+.ginput_right,body .gform_wrapper li.gfield .ginput_complex .ginput_right+.ginput_right{clear:right}body .gform_wrapper .top_label input.medium,body .gform_wrapper .top_label select.medium,body .gform_wrapper .top_label li.gfield.gf_left_half,body .gform_wrapper .top_label li.gfield.gf_right_half{width:48%}@media (max-width: 480px){body .gform_wrapper .top_label input.medium,body .gform_wrapper .top_label select.medium,body .gform_wrapper .top_label li.gfield.gf_left_half,body .gform_wrapper .top_label li.gfield.gf_right_half{float:none;width:100%}}body .gform_wrapper .ginput_complex .ginput_left,body .gform_wrapper .ginput_complex .ginput_right,body .gform_wrapper .gfield_error .ginput_complex .ginput_left,body .gform_wrapper .gfield_error .ginput_complex .ginput_right{width:48%}@media (max-width: 767px){body .gform_wrapper .ginput_complex .ginput_left,body .gform_wrapper .ginput_complex .ginput_right,body .gform_wrapper .gfield_error .ginput_complex .ginput_left,body .gform_wrapper .gfield_error .ginput_complex .ginput_right{float:none;width:100%}}body .gform_wrapper .top_label li.gfield.gf_left_third,body .gform_wrapper .top_label li.gfield.gf_middle_third,body .gform_wrapper .top_label li.gfield.gf_right_third{width:30.66667%}@media (max-width: 480px){body .gform_wrapper .top_label li.gfield.gf_left_third,body .gform_wrapper .top_label li.gfield.gf_middle_third,body .gform_wrapper .top_label li.gfield.gf_right_third{float:none;width:100%}}body .gform_wrapper .gfield_radio li,body .gform_wrapper .gfield_checkbox li{margin-bottom:0 !important}body .gform_wrapper .gfield_radio li input,body .gform_wrapper .gfield_checkbox li input{margin-left:1px}body .gform_wrapper li.gfield.gf_list_2col ul.gfield_checkbox li,body .gform_wrapper li.gfield.gf_list_2col ul.gfield_radio li{padding-left:2.5% !important}@media (max-width: 480px){body .gform_wrapper li.gfield.gf_list_2col ul.gfield_checkbox li,body .gform_wrapper li.gfield.gf_list_2col ul.gfield_radio li{float:none;width:100%;padding-left:0 !important}}body .gform_wrapper li.gfield.gf_list_3col ul.gfield_checkbox li,body .gform_wrapper li.gfield.gf_list_3col ul.gfield_radio li{padding-left:2.5% !important}@media (max-width: 767px){body .gform_wrapper li.gfield.gf_list_3col ul.gfield_checkbox li,body .gform_wrapper li.gfield.gf_list_3col ul.gfield_radio li{float:none;width:100%;padding-left:0 !important}}body .gform_wrapper li.gfield.gf_list_2col ul.gfield_checkbox li:nth-child(2n+1),body .gform_wrapper li.gfield.gf_list_2col ul.gfield_radio li:nth-child(2n+1),body .gform_wrapper li.gfield.gf_list_3col ul.gfield_checkbox li:nth-child(3n+1),body .gform_wrapper li.gfield.gf_list_3col ul.gfield_radio li:nth-child(3n+1){padding-left:0 !important}body .gform_wrapper .top_label input.small,body .gform_wrapper .top_label select.small,body .gform_wrapper .left_label input.small,body .gform_wrapper .left_label select.small,body .gform_wrapper .right_label input.small,body .gform_wrapper .right_label select.small{width:25%}@media (max-width: 480px){body .gform_wrapper .top_label input.small,body .gform_wrapper .top_label select.small,body .gform_wrapper .left_label input.small,body .gform_wrapper .left_label select.small,body .gform_wrapper .right_label input.small,body .gform_wrapper .right_label select.small{width:100%}}body .gform_wrapper .left_label input.medium,body .gform_wrapper .left_label select.medium,body .gform_wrapper .right_label input.medium,body .gform_wrapper .right_label select.medium{width:33.635%}@media (max-width: 767px){body .gform_wrapper .left_label input.medium,body .gform_wrapper .left_label select.medium,body .gform_wrapper .right_label input.medium,body .gform_wrapper .right_label select.medium{width:100%}}body .gform_wrapper .left_label div.ginput_complex,body .gform_wrapper .right_label div.ginput_complex,body .gform_wrapper .left_label textarea.textarea,body .gform_wrapper .right_label textarea.textarea,body .gform_wrapper .left_label input.large,body .gform_wrapper .left_label select.large,body .gform_wrapper .right_label input.large,body .gform_wrapper .right_label select.large{width:70%}@media (max-width: 767px){body .gform_wrapper .left_label div.ginput_complex,body .gform_wrapper .right_label div.ginput_complex,body .gform_wrapper .left_label textarea.textarea,body .gform_wrapper .right_label textarea.textarea,body .gform_wrapper .left_label input.large,body .gform_wrapper .left_label select.large,body .gform_wrapper .right_label input.large,body .gform_wrapper .right_label select.large{width:100%}}@media (max-width: 767px){body .gform_wrapper .left_label li.gfield.gf_left_half,body .gform_wrapper .right_label li.gfield.gf_left_half,body .gform_wrapper .left_label li.gfield.gf_right_half,body .gform_wrapper .right_label li.gfield.gf_right_half{clear:none;width:48%}}@media (max-width: 480px){body .gform_wrapper .left_label li.gfield.gf_left_half,body .gform_wrapper .right_label li.gfield.gf_left_half,body .gform_wrapper .left_label li.gfield.gf_right_half,body .gform_wrapper .right_label li.gfield.gf_right_half{clear:both;width:100%}}@media (max-width: 767px){body .gform_wrapper .left_label li.gfield.gf_left_half,body .gform_wrapper .right_label li.gfield.gf_left_half{clear:left;float:left}}@media (max-width: 767px){body .gform_wrapper .left_label li.gfield.gf_right_half,body .gform_wrapper .right_label li.gfield.gf_right_half{clear:right;float:right}}body .gform_wrapper li.gsection.gf_scroll_text{overflow-x:hidden;overflow-y:scroll;border:2px solid #ddd !important;padding-right:20px}body .gform_wrapper .top_label input.large,body .gform_wrapper .top_label select.large,body .gform_wrapper .top_label textarea.textarea,body .gform_wrapper .top_label li.gfield.gf_left_half input.medium,body .gform_wrapper .top_label li.gfield.gf_left_half input.large,body .gform_wrapper .top_label li.gfield.gf_left_half select.medium,body .gform_wrapper .top_label li.gfield.gf_left_half select.large,body .gform_wrapper .top_label li.gfield.gf_right_half input.medium,body .gform_wrapper .top_label li.gfield.gf_right_half input.large,body .gform_wrapper .top_label li.gfield.gf_right_half select.medium,body .gform_wrapper .top_label li.gfield.gf_right_half select.large,body .gform_wrapper .top_label li.gfield.gf_left_third input.medium,body .gform_wrapper .top_label li.gfield.gf_left_third input.large,body .gform_wrapper .top_label li.gfield.gf_left_third select.medium,body .gform_wrapper .top_label li.gfield.gf_left_third select.large,body .gform_wrapper .top_label li.gfield.gf_middle_third input.medium,body .gform_wrapper .top_label li.gfield.gf_middle_third input.large,body .gform_wrapper .top_label li.gfield.gf_middle_third select.medium,body .gform_wrapper .top_label li.gfield.gf_middle_third select.large,body .gform_wrapper .top_label li.gfield.gf_right_third input.medium,body .gform_wrapper .top_label li.gfield.gf_right_third input.large,body .gform_wrapper .top_label li.gfield.gf_right_third select.medium,body .gform_wrapper .top_label li.gfield.gf_right_third select.large,body .gform_wrapper .top_label li.gsection.gf_scroll_text,body .gform_wrapper .ginput_complex .ginput_left input[type=text],body .gform_wrapper .ginput_complex .ginput_left input[type=url],body .gform_wrapper .ginput_complex .ginput_left input[type=email],body .gform_wrapper .ginput_complex .ginput_left input[type=tel],body .gform_wrapper .ginput_complex .ginput_left input[type=number],body .gform_wrapper .ginput_complex .ginput_left input[type=password],body .gform_wrapper .ginput_complex .ginput_left select,body .gform_wrapper .ginput_complex .ginput_right input[type=text],body .gform_wrapper .ginput_complex .ginput_right input[type=url],body .gform_wrapper .ginput_complex .ginput_right input[type=email],body .gform_wrapper .ginput_complex .ginput_right input[type=tel],body .gform_wrapper .ginput_complex .ginput_right input[type=number],body .gform_wrapper .ginput_complex .ginput_right input[type=password],body .gform_wrapper .ginput_complex .ginput_right select,body .gform_wrapper .ginput_complex .ginput_full input[type=text],body .gform_wrapper .ginput_complex .ginput_full input[type=url],body .gform_wrapper .ginput_complex .ginput_full input[type=email],body .gform_wrapper .ginput_complex .ginput_full input[type=tel],body .gform_wrapper .ginput_complex .ginput_full input[type=number],body .gform_wrapper .ginput_complex .ginput_full input[type=password],body .gform_wrapper .ginput_complex .ginput_full select,body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=text],body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=url],body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=email],body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=tel],body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=number],body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=password],body .gform_wrapper .gfield_error .ginput_complex .ginput_left select,body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=text],body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=url],body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=email],body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=tel],body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=number],body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=password],body .gform_wrapper .gfield_error .ginput_complex .ginput_right select{width:100% !important}body .gform_wrapper .top_label .gfield_error,body .gform_wrapper .top_label .gfield_error .ginput_container{width:100%;max-width:100%}body .gform_wrapper li.gfield.gfield_error.gfield_contains_required label.gfield_label{margin-top:0}body .gform_wrapper li.gfield.gfield_error,body .gform_wrapper li.gfield.gfield_error.gfield_contains_required{border:1px solid;padding:6px 10px !important;border-color:#e5bdc4;color:#b94a48;background-color:#f2dede}body .gform_wrapper .validation_message{font-weight:inherit}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    