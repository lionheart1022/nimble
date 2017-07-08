'       => __( 'Audio Post Settings', '__x__' ),
    'description' => __( 'These settings enable you to embed audio into your posts.', '__x__' ),
    'page'        => 'post',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __( 'MP3 File URL', '__x__' ),
        'desc' => __( 'The URL to the .mp3 audio file.', '__x__' ),
        'id'   => '_x_audio_mp3',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'OGA File URL', '__x__' ),
        'desc' => __( 'The URL to the .oga or .ogg audio file.', '__x__' ),
        'id'   => '_x_audio_ogg',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'Embedded Audio Code', '__x__' ),
        'desc' => __( 'If you are using something other than self hosted audio such as Soundcloud paste the embed code here. This field will override the above.', '__x__' ),
        'id'   => '_x_audio_embed',
        'type' => 'textarea',
        'std'  => ''
      )
    )
  );

  x_add_meta_box( $meta_box );


  //
  // Ethos.
  //

  if ( x_get_stack() == 'ethos' ) :

    $meta_box = array(
      'id'          => 'x-meta-box-post-ethos',
      'title'       => __( 'Ethos Post Settings', '__x__' ),
      'description' => __( 'Here you will find some options specific to Ethos that you can use to create different post layouts.', '__x__' ),
      'page'        => 'post',
      'context'     => 'normal',
      'priority'    => 'high',
      'fields'      => array(
        array(
          'name' => __( 'Index Featured Post Layout', '__x__' ),
          'desc' => __( 'Make the featured image of this post fullwidth on the blog index page.', '__x__' ),
          'id'   => '_x_ethos_index_featured_post_layout',
          'type' => 'checkbox',
          'std'  => ''
        ),
        array(
          'name'    => __( 'Index Featured Post Size', '__x__' ),
          'desc'    => __( 'If the "Index Featured Post Layout" option above is selected, select a size for the output.', '__x__' ),
          'id'      => '_x_ethos_index_featured_post_size',
          'type'    => 'radio',
          'std'     => 'Skinny',
          'options' => array( 'Big', 'Skinny' )
        ),
        array(
          'name' => __( 'Post Carousel Display', '__x__' ),
          'desc' => __( 'Display this post in the Post Carousel if you have "Featured" selected in the Customizer.', '__x__' ),
          'id'   => '_x_ethos_post_carousel_display',
          'type' => 'checkbox',
          'std'  => '',
        ),
        array(
          'name' => __( 'Post Slider Display &ndash; Blog', '__x__' ),
          'desc' => __( 'Display this post in the Blog Post Slider if you have "Featured" selected in the Customizer.', '__x__' ),
          'id'   => '_x_ethos_post_slider_blog_display',
          'type' => 'checkbox',
          'std'  => '',
        ),
        array(
          'name' => __( 'Post Slider Display &ndash; Archives', '__x__' ),
          'desc' => __( 'Display this post in the Archives Post Slider if you have "Featured" selected in the Customizer.', '__x__' ),
          'id'   => '_x_ethos_post_slider_archives_display',
          'type' => 'checkbox',
          'std'  => '',
        )
      )
    );

    x_add_meta_box( $meta_box );

  endif;

}

add_action( 'add_meta_boxes', 'x_add_post_meta_boxes' );



// Portfolio Items
// =============================================================================
 
function x_add_portfolio_item_meta_boxes() {

  $meta_box = array(
    'id'          => 'x-meta-box-portfolio-item',
    'title'       => __( 'Portfolio Item Settings', '__x__' ),
    'description' => __( 'Select the appropriate options for your portfolio item.', '__x__' ),
    'page'        => 'x-portfolio',
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
        'name' => __( 'Portfolio Parent', '__x__' ),
        'desc' => __( 'Assign the parent portfolio page for this portfolio item. This will be used in various places throughout the theme such as your breadcrumbs. If "Default" is selected then the first page with the "Layout - Portfolio" template assigned to it will be used.', '__x__' ),
        'id'   => '_x_portfolio_parent',
        'type' => 'select-portfolio-parent',
        'std'  => 'Default'
      ),
      array(
        'name'    => __( 'Media Type', '__x__' ),
        'desc'    => __( 'Select which kind of media you want to display for your portfolio. If selecting a "Gallery," simply upload your images to this post and organize them in the order you want them to display.', '__x__' ),
        'id'      => '_x_portfolio_media',
        'type'    => 'radio',
        'std'     => 'Image',
        'options' => array( 'Image', 'Gallery', 'Video' )
      ),
      array(
        'name'    => __( 'Featured Content', '__x__' ),
        'desc'    => __( 'Select "Media" if you would like to show your video or gallery on the index page in place of the featured image.', '__x__' ),
        'id'      => '_x_portfolio_index_media',
        'type'    => 'radio',
        'std'     => 'Thumbnail',
        'options' => array( 'Thumbnail', 'Media' )
      ),
      array(
        'name' => __( 'Project Link', '__x__' ),
        'desc' => __( 'Provide an external link to the project you worked on if one is available.', '__x__' ),
        'id'   => '_x_portfolio_project_link',
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
  // Video.
  //

  $meta_box = array(
    'id'          => 'x-meta-box-portfolio-item-video',
    'title'       => __( 'Video Portfolio Item Settings', '__x__' ),
    'description' => __( 'These settings enable you to embed videos into your portfolio items.', '__x__' ),
    'page'        => 'x-portfolio',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name'    => __( 'Video Aspect Ratio', '__x__' ),
        'desc'    => __( 'If selecting "Video," choose the aspect ratio you would like for your video.', '__x__' ),
        'id'      => '_x_portfolio_aspect_ratio',
        'type'    => 'select',
        'std'     => '16:9',
        'options' => array( '16:9', '5:3', '5:4', '4:3', '3:2' )
      ),
      array(
        'name' => __( 'M4V File URL', '__x__' ),
        'desc' => __( 'If selecting "Video," place the URL to your .m4v video file here.', '__x__' ),
        'id'   => '_x_portfolio_m4v',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'OGV File URL', '__x__' ),
        'desc' => __( 'If selecting "Video," place the URL to your .ogv video file here.', '__x__' ),
        'id'   => '_x_portfolio_ogv',
        'type' => 'text',
        'std'  => ''
      ),
      array(
        'name' => __( 'Embedded Video Code', '__x__' ),
        'desc' => __( 'If you are using something other than self hosted video such as YouTube, Vimeo, or Wistia, paste the embed code here. This field will override the above.', '__x__' ),
        'id'   => '_x_portfolio_embed',
        'type' => 'textarea',
        'std'  => ''
      )
    )
  );

  x_add_meta_box( $meta_box );

}

add_action( 'add_meta_boxes', 'x_add_portfolio_item_meta_boxes' );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php
 
// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/CUSTOMIZER/OUTPUT/RENEW.PHP
// -----------------------------------------------------------------------------
// Renew CSS ouptut.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Site Link Color Accents
//   02. Layout Sizing
//   03. Masthead
//   04. Navbar
//   05. Navbar - Positioning
//   06. Navbar - Dropdowns
//   07. Colophon
//   08. Custom Fonts
//   09. Custom Fonts - Colors
//   10. Responsive Styling
// =============================================================================

$x_renew_entry_icon_color               = x_get_option( 'x_renew_entry_icon_color' );
$x_renew_topbar_text_color              = x_get_option( 'x_renew_topbar_text_color' );
$x_renew_topbar_link_color_hover        = x_get_option( 'x_renew_topbar_link_color_hover' );
$x_renew_topbar_background              = x_get_option( 'x_renew_topbar_background' );
$x_renew_logobar_background             = x_get_option( 'x_renew_logobar_background' );
$x_renew_navbar_button_color            = x_get_option( 'x_renew_navbar_button_color' );
$x_renew_navbar_background              = x_get_option( 'x_renew_navbar_background' );
$x_renew_navbar_button_background_hover = x_get_option( 'x_renew_navbar_button_background_hover' );
$x_renew_navbar_button_background       = x_get_option( 'x_renew_navbar_button_background' );
$x_renew_footer_background              = x_get_option( 'x_renew_footer_background' );
$x_renew_footer_text_color              = x_get_option( 'x_renew_footer_text_color' );
$x_renew_entry_icon_position            = x_get_option( 'x_renew_entry_icon_position' );
$x_renew_entry_icon_position_vertical   = x_get_option( 'x_renew_entry_icon_position_vertical' );
$x_renew_entry_icon_position_horizontal = x_get_option( 'x_renew_entry_icon_position_horizontal' );

?>

/* Site Link Color Accents
// ========================================================================== */

/*
// Color.
*/

a,
h1 a:hover,
h2 a:hover,
h3 a:hover,
h4 a:hover,
h5 a:hover,
h6 a:hover,
.x-comment-time:hover,
#reply-title small a,
.comment-reply-link:hover,
.x-comment-author a:hover {
  color: <?php echo $x_site_link_color; ?>;
}

a:hover,
#reply-title small a:hover,
.x-recent-posts a:hover .h-recent-posts {
  color: <?php echo $x_site_link_color_hover; ?>;
}

.entry-title:before {
  color: <?php echo $x_renew_entry_icon_color; ?>;
}

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce .price > .amount,
  .woocommerce .price > ins > .amount,
  .woocommerce-page .price > .amount,
  .woocommerce-page .price > ins > .amount,
  .woocommerce li.product .entry-header h3 a:hover,
  .woocommerce-page li.product .entry-header h3 a:hover,
  .woocommerce .star-rating:before,
  .woocommerce-page .star-rating:before,
  .woocommerce .star-rating span:before,
  .woocommerce-page .star-rating span:before {
    color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>


/*
// Border color.
*/

a.x-img-thumbnail:hover,
li.bypostauthor > article.comment {
  border-color: <?php echo $x_site_link_color; ?>;
}

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce div.product .woocommerce-tabs .x-comments-area li.comment.bypostauthor .x-comment-header .star-rating-container,
  .woocommerce-page div.product .woocommerce-tabs .x-comments-area li.comment.bypostauthor .x-comment-header .star-rating-container {
    border-color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>


/*
// Background color.
*/

.flex-direction-nav a,
.flex-control-nav a:hover,
.flex-control-nav a.flex-active,
.x-dropcap,
.x-skill-bar .bar,
.x-pricing-column.featured h2,
.h-comments-title small,
.pagination a:hover,
.x-entry-share .x-share:hover,
.entry-thumb,
.widget_tag_cloud .tagcloud a:hover,
.widget_product_tag_cloud .tagcloud a:hover,
.x-highlight,
.x-recent-posts .x-recent-posts-img,
.x-recent-posts .x-recent-posts-img:before,
.x-portfolio-filters {
  background-color: <?php echo $x_site_link_color; ?>;
}

.x-recent-posts a:hover .x-recent-posts-img,
.x-portfolio-filters:hover {
  background-color: <?php echo $x_site_link_color_hover; ?>;
}

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce .onsale,
  .woocommerce-page .onsale,
  .widget_price_filter .ui-slider .ui-slider-range,
  .woocommerce div.product .woocommerce-tabs .x-comments-area li.comment.bypostauthor article.comment:before,
  .woocommerce-page div.product .woocommerce-tabs .x-comments-area li.comment.bypostauthor article.comment:before {
    background-color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>



/* Layout Sizing
// ========================================================================== */

.x-main {
  width: <?php echo $x_layout_content_width - 3.20197 . '%'; ?>;
}

.x-sidebar {
  width: <?php echo 100 - 3.20197 - $x_layout_content_width . '%'; ?>;
}



/* Masthead
// ========================================================================== */

.x-topbar .p-info,
.x-topbar .p-info a,
.x-topbar .x-social-global a {
  color: <?php echo $x_renew_topbar_text_color; ?>;
}

.x-topbar .p-info a:hover {
  color: <?php echo $x_renew_topbar_link_color_hover; ?>;
}

.x-topbar {
  background-color: <?php echo $x_renew_topbar_background; ?>;
}

<?php if ( $x_logo_navigation_layout == 'stacked' ) : ?>

  .x-logobar {
    background-color: <?php echo $x_renew_logobar_background; ?>;
  }

<?php endif; ?>



/* Navbar
// ========================================================================== */

.x-navbar .desktop .x-nav > li:before {
  padding-top: <?php echo $x_navbar_adjust_links_top . 'px'; ?>;
}


/*
// Color.
*/

.x-brand,
.x-brand:hover,
.x-navbar .desktop .x-nav > li > a,
.x-navbar .desktop .sub-menu li > a,
.x-navbar .mobile .x-nav li a {
  color: <?php echo $x_navbar_link_color; ?>;
}

.x-navbar .desktop .x-nav > li > a:hover,
.x-navbar .desktop .x-nav > .x-active > a,
.x-navbar .desktop .x-nav > .current-menu-item > a,
.x-navbar .desktop .sub-menu li > a:hover,
.x-navbar .desktop .sub-menu li.x-active > a,
.x-navbar .desktop .sub-menu li.current-menu-item > a,
.x-navbar .desktop .x-nav .x-megamenu > .sub-menu > li > a,
.x-navbar .mobile .x-nav li > a:hover,
.x-navbar .mobile .x-nav li.x-active > a,
.x-navbar .mobile .x-nav li.current-menu-item > a {
  color: <?php echo $x_navbar_link_color_hover; ?>;
}

.x-btn-navbar,
.x-btn-navbar:hover {
  color: <?php echo $x_renew_navbar_button_color; ?>;
}


/*
// Background color.
*/

.x-navbar .desktop .sub-menu li:before,
.x-navbar .desktop .sub-menu li:after {
  background-color: <?php echo $x_navbar_link_color; ?>;
}

.x-navbar,
.x-navbar .sub-menu {
  background-color: <?php echo $x_renew_navbar_background; ?> !important;
}

.x-btn-navbar,
.x-btn-navbar.collapsed:hover {
  background-color: <?php echo $x_renew_navbar_button_background_hover; ?>;
}

.x-btn-navbar.collapsed {
  background-color: <?php echo $x_renew_navbar_button_background; ?>;
}


/*
// Box shadow.
*/

.x-navbar .desktop .x-nav > li > a:hover > span,
.x-navbar .desktop .x-nav > li.x-active > a > span,
.x-navbar .desktop .x-nav > li.current-menu-item > a > span {
  box-shadow: 0 2px 0 0 <?php echo $x_navbar_link_color_hover; ?>;
}



/* Navbar - Positioning
// ========================================================================== */

<?php if ( $x_navbar_positioning == 'static-top' || $x_navbar_positioning == 'fixed-top' ) : ?>

  .x-navbar .desktop .x-nav > li > a {
    height: <?php echo $x_navbar_height . 'px'; ?>;
    padding-top: <?php echo $x_navbar_adjust_links_top . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-navbar .desktop .x-nav > li > a {
    padding-top: <?php echo round( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?>;
    padding-bottom: <?php echo round( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?>;
    padding-left: 10%;
    padding-right: 10%;
  }

  .desktop .x-megamenu > .sub-menu {
    width: <?php echo 879 - $x_navbar_width . 'px'; ?>
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' ) : ?>

  .x-widgetbar {
    left: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-widgetbar {
    right: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>



/* Navbar - Dropdowns
// ========================================================================== */

.x-navbar .desktop .x-nav > li ul {
  top: <?php echo $x_navbar_height . 'px'; ?>;
}



/* Colophon
// ========================================================================== */

.x-colophon.bottom {
  background-color: <?php echo $x_renew_footer_background; ?>;
}

.x-colophon.bottom,
.x-colophon.bottom a,
.x-colophon.bottom .x-social-global a {
  color: <?php echo $x_renew_footer_text_color; ?>;
}



/* Custom Fonts
// ========================================================================== */

.h-landmark {
  font-weight: <?php echo $x_body_font_weight; ?>;
  <?php if ( x_is_font_italic( $x_body_font_weight_and_style ) ) : ?>
    font-style: italic;
  <?php endif; ?>
}



/* Custom Fonts - Colors
// ========================================================================== */

/*
// Brand.
*/

<?php if ( $x_logo_font_color_enable == '1' ) : ?>

  .x-brand,
  .x-brand:hover {
    color: <?php echo $x_logo_font_color; ?>;
  }

<?php endif; ?>


/*
// Body.
*/

<?php if ( $x_body_font_color_enable == '1' ) : ?>

  .x-comment-author a {
    color: <?php echo $x_body_font_color; ?>;
  }

  <?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

    .woocommerce .price > .from,
    .woocommerce .price > del,
    .woocommerce p.stars span a:after,
    .woocommerce-page .price > .from,
    .woocommerce-page .price > del,
    .woocommerce-page p.stars span a:after,
    .widget_price_filter .price_slider_amount .button,
    .widget_shopping_cart .buttons .button {
      color: <?php echo $x_body_font_color; ?>;
    }

  <?php endif; ?>

<?php endif; ?>


/*
// Headings.
*/

<?php if ( $x_headings_font_color_enable == '1' ) : ?>

  .x-comment-author a,
  .comment-form-author label,
  .comment-form-email label,
  .comment-form-url label,
  .comment-form-rating label,
  .comment-form-comment label,
  .widget_calendar #wp-calendar caption,
  .widget_calendar #wp-calendar th,
  .x-accordion-heading .x-accordion-toggle,
  .x-nav-tabs > li > a:hover,
  .x-nav-tabs > .active > a,
  .x-nav-tabs > .active > a:hover {
    color: <?php echo $x_headings_font_color; ?>;
  }

  .widget_calendar #wp-calendar th {
    border-bottom-color: <?php echo $x_headings_font_color; ?>;
  }

  .pagination span.current,
  .x-portfolio-filters-menu,
  .widget_tag_cloud .tagcloud a,
  .h-feature-headline span i,
  .widget_price_filter .ui-slider .ui-slider-handle {
    background-color: <?php echo $x_headings_font_color; ?>;
  }

<?php endif; ?>



/* Responsive Styling
// ========================================================================== */

@media (max-width: 979px) {

  <?php if ( $x_navbar_positioning == 'fixed-top' && $x_layout_site == 'boxed' ) : ?>

    .x-navbar.x-navbar-fixed-top.x-container.max.width {
      left: 0;
      right: 0;
      width: 100%;
    }

  <?php endif; ?>

  <?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

    .x-navbar .x-navbar-inner > .x-container.width {
      width: <?php echo $x_layout_site_width . '%'; ?>;
    }

  <?php endif; ?>

  .x-widgetbar {
    left: 0;
    right: 0;
  }
}


<?php if ( is_home() && $x_renew_entry_icon_position == 'creative' && x_get_option( 'x_blog_style' ) == 'standard'  ) : ?>

  @media (min-width: 980px) {
    .x-full-width-active .entry-title:before,
    .x-content-sidebar-active .entry-title:before {
      position: absolute;
      width: 70px;
      height: 70px;
      margin-top: -<?php echo $x_renew_entry_icon_position_vertical . 'px'; ?>;
      margin-left: -<?php echo $x_renew_entry_icon_position_horizontal . '%'; ?>;
      font-size: 32px;
      font-size: 3.2rem;
      line-height: 70px;
      border-radius: 100em;
    }
  }

<?php endif; ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php
 
// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/CUSTOMIZER/OUTPUT/ICON.PHP
// -----------------------------------------------------------------------------
// Icon CSS ouptut.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Site Link Color Accents
//   02. Posts
//   03. Post Colors - Standard
//   04. Post Colors - Image
//   05. Post Colors - Gallery
//   06. Post Colors - Video
//   07. Post Colors - Audio
//   08. Post Colors - Quote
//   09. Post Colors - Link
//   10. Navbar
//   11. Navbar - Positioning
//   12. Navbar - Dropdowns
//   13. Custom Fonts
//   14. Custom Fonts - Colors
//   15. Responsive Styling
// =============================================================================

$x_icon_post_title_icon_enable      = x_get_option( 'x_icon_post_title_icon_enable', '1' );
$x_icon_post_standard_colors_enable = x_get_option( 'x_icon_post_standard_colors_enable', '' );
$x_icon_post_image_colors_enable    = x_get_option( 'x_icon_post_image_colors_enable', '' );
$x_icon_post_gallery_colors_enable  = x_get_option( 'x_icon_post_gallery_colors_enable', '' );
$x_icon_post_video_colors_enable    = x_get_option( 'x_icon_post_video_colors_enable', '' );
$x_icon_post_audio_colors_enable    = x_get_option( 'x_icon_post_audio_colors_enable', '' );
$x_icon_post_quote_colors_enable    = x_get_option( 'x_icon_post_quote_colors_enable', '' );
$x_icon_post_link_colors_enable     = x_get_option( 'x_icon_post_link_colors_enable', '' );

?>

/* Site Link Color Accents
// ========================================================================== */

/*
// Color.
*/

a,
h1 a:hover,
h2 a:hover,
h3 a:hover,
h4 a:hover,
h5 a:hover,
h6 a:hover,
#respond .required,
.pagination a:hover,
.pagination span.current,
.widget_tag_cloud .tagcloud a:hover,
.widget_product_tag_cloud .tagcloud a:hover,
.x-scroll-top:hover,
.x-comment-author a:hover,
.mejs-button button:hover {
  color: <?php echo $x_site_link_color; ?>;
}

a:hover {
  color: <?php echo $x_site_link_color_hover; ?>;
}

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce .price > .amount,
  .woocommerce .price > ins > .amount,
  .woocommerce-page .price > .amount,
  .woocommerce-page .price > ins > .amount,
  .woocommerce li.product .entry-header h3 a:hover,
  .woocommerce-page li.product .entry-header h3 a:hover,
  .woocommerce .star-rating:before,
  .woocommerce-page .star-rating:before,
  .woocommerce .star-rating span:before,
  .woocommerce-page .star-rating span:before,
  .woocommerce .onsale,
  .woocommerce-page .onsale {
    color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>


/*
// Border color.
*/

a.x-img-thumbnail:hover,
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus,
.pagination a:hover,
.pagination span.current,
.widget_tag_cloud .tagcloud a:hover,
.widget_product_tag_cloud .tagcloud a:hover,
.x-scroll-top:hover {
  border-color: <?php echo $x_site_link_color; ?>;
}


/*
// Background color.
*/

.flex-direction-nav a,
.flex-control-nav a:hover,
.flex-control-nav a.flex-active,
.x-dropcap,
.x-skill-bar .bar,
.x-pricing-column.featured h2,
.x-portfolio-filters,
.x-entry-share .x-share:hover,
.widget_price_filter .ui-slider .ui-slider-range,
.mejs-time-current {
  background-color: <?php echo $x_site_link_color; ?>;
}

.x-portfolio-filters:hover {
  background-color: <?php echo $x_site_link_color_hover; ?>;
}



/* Posts
// ========================================================================== */

<?php if ( $x_icon_post_title_icon_enable == '' ) : ?>

  .entry-title:before {
    display: none;
  }

<?php endif; ?>



/* Post Colors - Standard
// ========================================================================== */

<?php if ( $x_icon_post_standard_colors_enable == '1' ) : ?>

  <?php $standard_text_color       = x_get_option( 'x_icon_post_standard_color' ); ?>
  <?php $standard_background_color = x_get_option( 'x_icon_post_standard_background' ); ?>

  .format-standard .entry-wrap {
    color: <?php echo $standard_text_color ?> !important;
    background-color: <?php echo $standard_background_color ?> !important;
  }

  .format-standard a:not(.x-btn):not(.meta-comments),
  .format-standard h1,
  .format-standard h2,
  .format-standard h3,
  .format-standard h4,
  .format-standard h5,
  .format-standard h6,
  .format-standard .entry-title,
  .format-standard .entry-title a,
  .format-standard .entry-title a:hover,
  .format-standard .p-meta,
  .format-standard blockquote,
  .format-standard .x-cite {
    color: <?php echo $standard_text_color; ?>;
  }

  .format-standard .meta-comments {
    border: 0;
    color: <?php echo $standard_background_color; ?>;
    background-color: <?php echo $standard_text_color; ?>;
  }

  .format-standard .entry-content a:not(.x-btn):not(.x-img-thumbnail) {
    border-bottom: 1px dotted;
  }

  .format-standard .entry-content a:hover:not(.x-btn):not(.x-img-thumbnail) {
    opacity: 0.65;
    filter: alpha(opacity=65);
  }

  .format-standard .entry-content a.x-img-thumbnail {
    border-color: #fff;
  }

  .format-standard blockquote,
  .format-standard .x-toc,
  .format-standard .entry-content a.x-img-thumbnail:hover {
    border-color: <?php echo $standard_text_color; ?>;
  }

<?php endif; ?>



/* Post Colors - Image
// ========================================================================== */

<?php if ( $x_icon_post_image_colors_enable == '1' ) : ?>

  <?php $image_text_color       = x_get_option( 'x_icon_post_image_color' ); ?>
  <?php $image_background_color = x_get_option( 'x_icon_post_image_background' ); ?>

  .format-image .entry-wrap {
    color: <?php echo $image_text_color ?> !important;
    background-color: <?php echo $image_background_color ?> !important;
  }

  .format-image a:not(.x-btn):not(.meta-comments),
  .format-image h1,
  .format-image h2,
  .format-image h3,
  .format-image h4,
  .format-image h5,
  .format-image h6,
  .format-image .entry-title,
  .format-image .entry-title a,
  .format-image .entry-title a:hover,
  .format-image .p-meta,
  .format-image blockquote,
  .format-image .x-cite {
    color: <?php echo $image_text_color; ?>;
  }

  .format-image .meta-comments {
    border: 0;
    color: <?php echo $image_background_color; ?>;
    background-color: <?php echo $image_text_color; ?>;
  }

  .format-image .entry-content a:not(.x-btn):not(.x-img-thumbnail) {
    border-bottom: 1px dotted;
  }

  .format-image .entry-content a:hover:not(.x-btn):not(.x-img-thumbnail) {
    opacity: 0.65;
    filter: alpha(opacity=65);
  }

  .format-image .entry-content a.x-img-thumbnail {
    border-color: #fff;
  }

  .format-image blockquote,
  .format-image .x-toc,
  .format-image .entry-content a.x-img-thumbnail:hover {
    border-color: <?php echo $image_text_color; ?>;
  }

<?php endif; ?>



/* Post Colors - Gallery
// ========================================================================== */

<?php if ( $x_icon_post_gallery_colors_enable == '1' ) : ?>

  <?php $gallery_text_color       = x_get_option( 'x_icon_post_gallery_color' ); ?>
  <?php $gallery_background_color = x_get_option( 'x_icon_post_gallery_background' ); ?>

  .format-gallery .entry-wrap {
    color: <?php echo $gallery_text_color ?> !important;
    background-color: <?php echo $gallery_background_color ?> !important;
  }

  .format-gallery a:not(.x-btn):not(.meta-comments),
  .format-gallery h1,
  .format-gallery h2,
  .format-gallery h3,
  .format-gallery h4,
  .format-gallery h5,
  .format-gallery h6,
  .format-gallery .entry-title,
  .format-gallery .entry-title a,
  .format-gallery .entry-title a:hover,
  .format-gallery .p-meta,
  .format-gallery blockquote,
  .format-gallery .x-cite {
    color: <?php echo $gallery_text_color; ?>;
  }

  .format-gallery .meta-comments {
    border: 0;
    color: <?php echo $gallery_background_color; ?>;
    background-color: <?php echo $gallery_text_color; ?>;
  }

  .format-gallery .entry-content a:not(.x-btn):not(.x-img-thumbnail) {
    border-bottom: 1px dotted;
  }

  .format-gallery .entry-content a:hover:not(.x-btn):not(.x-img-thumbnail) {
    opacity: 0.65;
    filter: alpha(opacity=65);
  }

  .format-gallery .entry-content a.x-img-thumbnail {
    border-color: #fff;
  }

  .format-gallery blockquote,
  .format-gallery .x-toc,
  .format-gallery .entry-content a.x-img-thumbnail:hover {
    border-color: <?php echo $gallery_text_color; ?>;
  }

<?php endif; ?>



/* Post Colors - Video
// ========================================================================== */

<?php if ( $x_icon_post_video_colors_enable == '1' ) : ?>

  <?php $video_text_color       = x_get_option( 'x_icon_post_video_color' ); ?>
  <?php $video_background_color = x_get_option( 'x_icon_post_video_background' ); ?>

  .format-video .entry-wrap {
    color: <?php echo $video_text_color ?> !important;
    background-color: <?php echo $video_background_color ?> !important;
  }

  .format-video a:not(.x-btn):not(.meta-comments),
  .format-video h1,
  .format-video h2,
  .format-video h3,
  .format-video h4,
  .format-video h5,
  .format-video h6,
  .format-video .entry-title,
  .format-video .entry-title a,
  .format-video .entry-title a:hover,
  .format-video .p-meta,
  .format-video blockquote,
  .format-video .x-cite {
    color: <?php echo $video_text_color; ?>;
  }

  .format-video .meta-comments {
    border: 0;
    color: <?php echo $video_background_color; ?>;
    background-color: <?php echo $video_text_color; ?>;
  }

  .format-video .entry-content a:not(.x-btn):not(.x-img-thumbnail) {
    border-bottom: 1px dotted;
  }

  .format-video .entry-content a:hover:not(.x-btn):not(.x-img-thumbnail) {
    opacity: 0.65;
    filter: alpha(opacity=65);
  }

  .format-video .entry-content a.x-img-thumbnail {
    border-color: #fff;
  }

  .format-video blockquote,
  .format-video .x-toc,
  .format-video .entry-content a.x-img-thumbnail:hover {
    border-color: <?php echo $video_text_color; ?>;
  }

<?php endif; ?>



/* Post Colors - Audio
// ========================================================================== */

<?php if ( $x_icon_post_audio_colors_enable == '1' ) : ?>

  <?php $audio_text_color       = x_get_option( 'x_icon_post_audio_color' ); ?>
  <?php $audio_background_color = x_get_option( 'x_icon_post_audio_background' ); ?>

  .format-audio .entry-wrap {
    color: <?php echo $audio_text_color ?> !important;
    background-color: <?php echo $audio_background_color ?> !important;
  }

  .format-audio a:not(.x-btn):not(.meta-comments),
  .format-audio h1,
  .format-audio h2,
  .format-audio h3,
  .format-audio h4,
  .format-audio h5,
  .format-audio h6,
  .format-audio .entry-title,
  .format-audio .entry-title a,
  .format-audio .entry-title a:hover,
  .format-audio .p-meta,
  .format-audio blockquote,
  .format-audio .x-cite {
    color: <?php echo $audio_text_color; ?>;
  }

  .format-audio .meta-comments {
    border: 0;
    color: <?php echo $audio_background_color; ?>;
    background-color: <?php echo $audio_text_color; ?>;
  }

  .format-audio .entry-content a:not(.x-btn):not(.x-img-thumbnail) {
    border-bottom: 1px dotted;
  }

  .format-audio .entry-content a:hover:not(.x-btn):not(.x-img-thumbnail) {
    opacity: 0.65;
    filter: alpha(opacity=65);
  }

  .format-audio .entry-content a.x-img-thumbnail {
    border-color: #fff;
  }

  .format-audio blockquote,
  .format-audio .x-toc,
  .format-audio .entry-content a.x-img-thumbnail:hover {
    border-color: <?php echo $audio_text_color; ?>;
  }

<?php endif; ?>



/* Post Colors - Quote
// ========================================================================== */

<?php if ( $x_icon_post_quote_colors_enable == '1' ) : ?>

  <?php $quote_text_color       = x_get_option( 'x_icon_post_quote_color' ); ?>
  <?php $quote_background_color = x_get_option( 'x_icon_post_quote_background' ); ?>

  .format-quote .entry-wrap {
    color: <?php echo $quote_text_color ?> !important;
    background-color: <?php echo $quote_background_color ?> !important;
  }

  .format-quote a:not(.x-btn):not(.meta-comments),
  .format-quote h1,
  .format-quote h2,
  .format-quote h3,
  .format-quote h4,
  .format-quote h5,
  .format-quote h6,
  .format-quote .entry-title,
  .format-quote .entry-title a,
  .format-quote .entry-title a:hover,
  .format-quote .entry-title-sub,
  .format-quote .p-meta,
  .format-quote blockquote,
  .format-quote .x-cite {
    color: <?php echo $quote_text_color; ?>;
  }

  .format-quote .meta-comments {
    border: 0;
    color: <?php echo $quote_background_color; ?>;
    background-color: <?php echo $quote_text_color; ?>;
  }

  .format-quote .entry-content a:not(.x-btn):not(.x-img-thumbnail) {
    border-bottom: 1px dotted;
  }

  .format-quote .entry-content a:hover:not(.x-btn):not(.x-img-thumbnail) {
    opacity: 0.65;
    filter: alpha(opacity=65);
  }

  .format-quote .entry-content a.x-img-thumbnail {
    border-color: #fff;
  }

  .format-quote blockquote,
  .format-quote .x-toc,
  .format-quote .entry-content a.x-img-thumbnail:hover {
    border-color: <?php echo $quote_text_color; ?>;
  }

<?php endif; ?>



/* Post Colors - Link
// ========================================================================== */

<?php if ( $x_icon_post_link_colors_enable == '1' ) : ?>

  <?php $link_text_color       = x_get_option( 'x_icon_post_link_color' ); ?>
  <?php $link_background_color = x_get_option( 'x_icon_post_link_background' ); ?>

  .format-link .entry-wrap {
    color: <?php echo $link_text_color ?> !important;
    background-color: <?php echo $link_background_color ?> !important;
  }

  .format-link a:not(.x-btn):not(.meta-comments),
  .format-link h1,
  .format-link h2,
  .format-link h3,
  .format-link h4,
  .format-link h5,
  .format-link h6,
  .format-link .entry-title,
  .format-link .entry-title a,
  .format-link .entry-title a:hover,
  .format-link .entry-title .entry-external-link:hover,
  .format-link .p-meta,
  .format-link blockquote,
  .format-link .x-cite {
    color: <?php echo $link_text_color; ?>;
  }

  .format-link .meta-comments {
    border: 0;
    color: <?php echo $link_background_color; ?>;
    background-color: <?php echo $link_text_color; ?>;
  }

  .format-link .entry-content a:not(.x-btn):not(.x-img-thumbnail) {
    border-bottom: 1px dotted;
  }

  .format-link .entry-content a:hover:not(.x-btn):not(.x-img-thumbnail) {
    opacity: 0.65;
    filter: alpha(opacity=65);
  }

  .format-link .entry-content a.x-img-thumbnail {
    border-color: #fff;
  }

  .format-link blockquote,
  .format-link .x-toc,
  .format-link .entry-content a.x-img-thumbnail:hover {
    border-color: <?php echo $link_text_color; ?>;
  }

<?php endif; ?>



/* Navbar
// ========================================================================== */

/*
// Color.
*/

.x-navbar .desktop .x-nav > li > a,
.x-navbar .desktop .sub-menu a,
.x-navbar .mobile .x-nav li a {
  color: <?php echo $x_navbar_link_color; ?>;
}

.x-navbar .desktop .x-nav > li > a:hover,
.x-navbar .desktop .x-nav > .x-active > a,
.x-navbar .desktop .x-nav > .current-menu-item > a,
.x-navbar .desktop .sub-menu a:hover,
.x-navbar .desktop .sub-menu .x-active > a,
.x-navbar .desktop .sub-menu .current-menu-item > a,
.x-navbar .desktop .x-nav .x-megamenu > .sub-menu > li > a,
.x-navbar .mobile .x-nav li > a:hover,
.x-navbar .mobile .x-nav .x-active > a,
.x-navbar .mobile .x-nav .current-menu-item > a {
  color: <?php echo $x_navbar_link_color_hover; ?>;
}



/* Navbar - Positioning
// ========================================================================== */

<?php if ( $x_navbar_positioning == 'static-top' || $x_navbar_positioning == 'fixed-top' ) : ?>

  .x-navbar .desktop .x-nav > li > a {
    height: <?php echo $x_navbar_height . 'px'; ?>;
    padding-top: <?php echo $x_navbar_adjust_links_top . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-navbar .desktop .x-nav > li > a {
    padding-top: calc(<?php echo floor( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?> - 0.875em);
    padding-bottom: calc(<?php echo floor( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?> - 0.825em);
    padding-left: 35px;
    padding-right: 35px;
  }

  .desktop .x-megamenu > .sub-menu {
    width: <?php echo 879 - $x_navbar_width . 'px'; ?>
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' ) : ?>

  .x-widgetbar {
    left: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-widgetbar {
    right: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>



/* Navbar - Dropdowns
// ========================================================================== */

.x-navbar .desktop .x-nav > li ul {
  top: <?php echo $x_navbar_height . 'px'; ?>;
}



/* Custom Fonts
// ========================================================================== */

<?php if ( $x_custom_fonts == '1' ) : ?>

  .x-comment-author,
  .x-comment-time,
  .comment-form-author label,
  .comment-form-email label,
  .comment-form-url label,
  .comment-form-rating label,
  .comment-form-comment label {
    font-family: "<?php echo $x_headings_font_family; ?>", "Helvetica Neue", Helvetica, sans-serif;;
  }

<?php endif; ?>



/* Custom Fonts - Colors
// ========================================================================== */

/*
// Brand.
*/

<?php if ( $x_logo_font_color_enable == '1' ) : ?>

  .x-brand,
  .x-brand:hover {
    color: <?php echo $x_logo_font_color; ?>;
  }

<?php endif; ?>


/*
// Body.
*/

<?php if ( $x_body_font_color_enable == '1' ) : ?>

  .x-comment-time,
  .entry-thumb:before,
  .p-meta {
    color: <?php echo $x_body_font_color; ?>;
  }

  <?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

    .woocommerce .price > .from,
    .woocommerce .price > del,
    .woocommerce p.stars span a:after,
    .woocommerce-page .price > .from,
    .woocommerce-page .price > del,
    .woocommerce-page p.stars span a:after {
      color: <?php echo $x_body_font_color; ?>;
    }

  <?php endif; ?>

<?php endif; ?>


/*
// Headings.
*/

<?php if ( $x_headings_font_color_enable == '1' ) : ?>

  .entry-title a:hover,
  .x-comment-author,
  .x-comment-author a,
  .comment-form-author label,
  .comment-form-email label,
  .comment-form-url label,
  .comment-form-rating label,
  .comment-form-comment label,
  .x-accordion-heading .x-accordion-toggle,
  .x-nav-tabs > li > a:hover,
  .x-nav-tabs > .active > a,
  .x-nav-tabs > .active > a:hover,
  .mejs-button button {
    color: <?php echo $x_headings_font_color; ?>;
  }

  .h-comments-title small,
  .h-feature-headline span i,
  .x-portfolio-filters-menu,
  .mejs-time-loaded {
    background-color: <?php echo $x_headings_font_color; ?> !important;
  }

<?php endif; ?>



/* Responsive Styling
// ========================================================================== */

@media (min-width: 1200px) {
  .x-sidebar {
    width: <?php echo $x_layout_sidebar_width . 'px'; ?>;
  }

  body.x-sidebar-content-active,
  body[class*="page-template-template-blank"].x-sidebar-content-active.x-blank-template-sidebar-active {
    padding-left: <?php echo $x_layout_sidebar_width . 'px'; ?>;
  }

  body.x-content-sidebar-active,
  body[class*="page-template-template-blank"].x-content-sidebar-active.x-blank-template-sidebar-active {
    padding-right: <?php echo $x_layout_sidebar_width . 'px'; ?>;
  }

  body.x-sidebar-content-active .x-widgetbar,
  body.x-sidebar-content-active .x-navbar-fixed-top,
  body[class*="page-template-template-blank"].x-sidebar-content-active.x-blank-template-sidebar-active .x-widgetbar,
  body[class*="page-template-template-blank"].x-sidebar-content-active.x-blank-template-sidebar-active .x-navbar-fixed-top {
    left: <?php echo $x_layout_sidebar_width . 'px'; ?>;
  }

  body.x-content-sidebar-active .x-widgetbar,
  body.x-content-sidebar-active .x-navbar-fixed-top,
  body[class*="page-template-template-blank"].x-content-sidebar-active.x-blank-template-sidebar-active .x-widgetbar,
  body[class*="page-template-template-blank"].x-content-sidebar-active.x-blank-template-sidebar-active .x-navbar-fixed-top {
    right: <?php echo $x_layout_sidebar_width . 'px'; ?>;
  }
}


@media (max-width: 979px) {

  <?php if ( $x_navbar_positioning == 'fixed-top' && $x_layout_site == 'boxed' ) : ?>

    .x-navbar.x-navbar-fixed-top.x-container.max.width {
      left: 0;
      right: 0;
      width: 100%;
    }

  <?php endif; ?>

  <?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

    .x-navbar .x-navbar-inner > .x-container.width {
      width: <?php echo $x_layout_site_width . '%'; ?>;
    }

  <?php endif; ?>

  .x-widgetbar {
    left: 0;
    right: 0;
  }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
 
// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/CUSTOMIZER/OUTPUT/ETHOS.PHP
// -----------------------------------------------------------------------------
// Ethos CSS ouptut.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Site Link Color Accents
//   02. Layout Sizing
//   03. Navbar
//   04. Navbar - Positioning
//   05. Navbar - Dropdowns
//   06. Design Options
//   07. Post Slider
//   08. Custom Fonts - Colors
//   09. Responsive Styling
// =============================================================================

$x_ethos_navbar_desktop_link_side_padding = x_get_option( 'x_ethos_navbar_desktop_link_side_padding' );
$x_ethos_topbar_background                = x_get_option( 'x_ethos_topbar_background' );
$x_ethos_navbar_background                = x_get_option( 'x_ethos_navbar_background' );
$x_ethos_sidebar_widget_headings_color    = x_get_option( 'x_ethos_sidebar_widget_headings_color' );
$x_ethos_sidebar_color                    = x_get_option( 'x_ethos_sidebar_color' );
$x_ethos_post_slider_blog_height          = x_get_option( 'x_ethos_post_slider_blog_height' );
$x_ethos_post_slider_archive_height       = x_get_option( 'x_ethos_post_slider_archive_height' );

$x_ethos_navbar_outer_border_width        = '2';

?>

/* Site Link Color Accents
// ========================================================================== */

/*
// Color.
*/

a,
h1 a:hover,
h2 a:hover,
h3 a:hover,
h4 a:hover,
h5 a:hover,
h6 a:hover,
.x-breadcrumb-wrap a:hover,
.x-comment-author a:hover,
.x-comment-time:hover,
.p-meta > span > a:hover,
.format-link .link a:hover,
.x-sidebar .widget ul li a:hover,
.x-sidebar .widget ol li a:hover,
.x-sidebar .widget_tag_cloud .tagcloud a:hover,
.x-portfolio .entry-extra .x-ul-tags li a:hover {
  color: <?php echo $x_site_link_color; ?>;
}

a:hover {
  color: <?php echo $x_site_link_color_hover; ?>;
}

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce .price > .amount,
  .woocommerce .price > ins > .amount,
  .woocommerce-page .price > .amount,
  .woocommerce-page .price > ins > .amount,
  .woocommerce .star-rating:before,
  .woocommerce-page .star-rating:before,
  .woocommerce .star-rating span:before,
  .woocommerce-page .star-rating span:before {
    color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>


/*
// Border color.
*/

a.x-img-thumbnail:hover {
  border-color: <?php echo $x_site_link_color; ?>;
}


/*
// Background color.
*/

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce .onsale,
  .woocommerce-page .onsale,
  .widget_price_filter .ui-slider .ui-slider-range {
    background-color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>



/* Layout Sizing
// ========================================================================== */

/*
// Main structural elements.
*/

.x-main {
  width: <?php echo $x_layout_content_width . '%'; ?>;
}

.x-sidebar {
  width: <?php echo 100 - $x_layout_content_width . '%'; ?>;
}


/*
// Main content background.
*/

.x-content-sidebar-active .x-container.main:before {
  right: <?php echo 100 - $x_layout_content_width . '%'; ?>;
}

.x-sidebar-content-active .x-container.main:before {
  left: <?php echo 100 - $x_layout_content_width . '%'; ?>;
}

.x-full-width-active .x-container.main:before {
  left: -5000em;
}



/* Navbar
// ========================================================================== */

/*
// Desktop link side padding.
*/

.x-navbar .desktop .x-nav > li > a {
  padding-left: <?php echo $x_ethos_navbar_desktop_link_side_padding . 'px'; ?>;
  padding-right: <?php echo $x_ethos_navbar_desktop_link_side_padding . 'px'; ?>;
}


/*
// Color.
*/

.x-navbar .desktop .x-nav > li > a,
.x-navbar .desktop .sub-menu a,
.x-navbar .mobile .x-nav li > a,
.x-breadcrumb-wrap a,
.x-breadcrumbs .delimiter {
  color: <?php echo $x_navbar_link_color; ?>;
}

.x-topbar .p-info a:hover,
.x-social-global a:hover,
.x-navbar .desktop .x-nav > li > a:hover,
.x-navbar .desktop .x-nav > .x-active > a,
.x-navbar .desktop .x-nav > .current-menu-item > a,
.x-navbar .desktop .sub-menu a:hover,
.x-navbar .desktop .sub-menu .x-active > a,
.x-navbar .desktop .sub-menu .current-menu-item > a,
.x-navbar .desktop .x-nav .x-megamenu > .sub-menu > li > a,
.x-navbar .mobile .x-nav li > a:hover,
.x-navbar .mobile .x-nav .x-active > a,
.x-navbar .mobile .x-nav .current-menu-item > a,
.x-widgetbar .widget a:hover,
.x-colophon .widget a:hover,
.x-colophon.bottom .x-colophon-content a:hover,
.x-colophon.bottom .x-nav a:hover {
  color: <?php echo $x_navbar_link_color_hover; ?>;
}


/*
// Box shadow.
*/

<?php

$locations = get_nav_menu_locations();
$items     = wp_get_nav_menu_items( $locations['primary'] );

foreach ( $items as $item ) {
  if ( $item->type == 'taxonomy' && $item->menu_item_parent == 0 ) {

    $t_id   = $item->object_id;
    $accent = x_ethos_category_accent_color( $t_id, $x_site_link_color );

    ?>

    <?php if ( $x_navbar_positioning == 'static-top' || $x_navbar_positioning == 'fixed-top' ) : ?>

      .x-navbar .desktop .x-nav > li.tax-item-<?php echo $t_id; ?> > a:hover,
      .x-navbar .desktop .x-nav > li.tax-item-<?php echo $t_id; ?>.x-active > a {
        box-shadow: 0 <?php echo $x_ethos_navbar_outer_border_width; ?>px 0 0 <?php echo $accent; ?>;
      }

    <?php elseif ( $x_navbar_positioning == 'fixed-left' ) : ?>

      .x-navbar .desktop .x-nav > li.tax-item-<?php echo $t_id; ?> > a:hover,
      .x-navbar .desktop .x-nav > li.tax-item-<?php echo $t_id; ?>.x-active > a {
        box-shadow: <?php echo $x_ethos_navbar_outer_border_width; ?>px 0 0 0 <?php echo $accent; ?>;
      }

    <?php elseif ( $x_navbar_positioning == 'fixed-right' ) : ?>

      .x-navbar .desktop .x-nav > li.tax-item-<?php echo $t_id; ?> > a:hover,
      .x-navbar .desktop .x-nav > li.tax-item-<?php echo $t_id; ?>.x-active > a {
        box-shadow: -<?php echo $x_ethos_navbar_outer_border_width; ?>px 0 0 0 <?php echo $accent; ?>;
      }

    <?php endif; ?>

    <?php

  }
}

?>



/* Navbar - Positioning
// ========================================================================== */

<?php if ( $x_navbar_positioning == 'static-top' || $x_navbar_positioning == 'fixed-top' ) : ?>

  .x-navbar .desktop .x-nav > li > a:hover,
  .x-navbar .desktop .x-nav > .x-active > a,
  .x-navbar .desktop .x-nav > .current-menu-item > a {
    box-shadow: 0 <?php echo $x_ethos_navbar_outer_border_width; ?>px 0 0 <?php echo $x_site_link_color; ?>;
  }

  .x-navbar .desktop .x-nav > li > a {
    height: <?php echo $x_navbar_height . 'px'; ?>;
    padding-top: <?php echo $x_navbar_adjust_links_top . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-navbar .desktop .x-nav > li > a {
    padding-top: <?php echo round( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?>;
    padding-bottom: <?php echo round( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?>;
    padding-left: 7%;
    padding-right: 7%;
  }

  .desktop .x-megamenu > .sub-menu {
    width: <?php echo 879 - $x_navbar_width . 'px'; ?>
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' ) : ?>

  .x-navbar .desktop .x-nav > li > a:hover,
  .x-navbar .desktop .x-nav > .x-active > a,
  .x-navbar .desktop .x-nav > .current-menu-item > a {
    box-shadow: <?php echo $x_ethos_navbar_outer_border_width; ?>px 0 0 0 <?php echo $x_site_link_color; ?>;
  }

  .x-widgetbar {
    left: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-navbar .desktop .x-nav > li > a:hover,
  .x-navbar .desktop .x-nav > .x-active > a,
  .x-navbar .desktop .x-nav > .current-menu-item > a {
    box-shadow: -<?php echo $x_ethos_navbar_outer_border_width; ?>px 0 0 0 <?php echo $x_site_link_color; ?>;
  }

  .x-widgetbar {
    right: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>



/* Navbar - Dropdowns
// ========================================================================== */

.x-navbar .desktop .x-nav > li ul {
  top: <?php echo $x_navbar_height + $x_ethos_navbar_outer_border_width . 'px'; ?>;
}



/* Design Options
// ========================================================================== */

/*
// Color.
*/

.h-landmark,
.x-sidebar .h-widget,
.x-sidebar .h-widget a.rsswidget,
.x-sidebar .h-widget a.rsswidget:hover,
.x-sidebar .widget.widget_pages .current_page_item a,
.x-sidebar .widget.widget_nav_menu .current-menu-item a,
.x-sidebar .widget.widget_pages .current_page_item a:hover,
.x-sidebar .widget.widget_nav_menu .current-menu-item a:hover {
  color: <?php echo $x_ethos_sidebar_widget_headings_color; ?>;
}

.x-sidebar .widget,
.x-sidebar .widget a,
.x-sidebar .widget ul li a,
.x-sidebar .widget ol li a,
.x-sidebar .widget_tag_cloud .tagcloud a,
.x-sidebar .widget_product_tag_cloud .tagcloud a,
.x-sidebar .widget a:hover,
.x-sidebar .widget ul li a:hover,
.x-sidebar .widget ol li a:hover,
.x-sidebar .widget_tag_cloud .tagcloud a:hover,
.x-sidebar .widget_product_tag_cloud .tagcloud a:hover,
.x-sidebar .widget_shopping_cart .buttons .button,
.x-sidebar .widget_price_filter .price_slider_amount .button {
  color: <?php echo $x_ethos_sidebar_color; ?>;
}


/*
// Border color.
*/

.x-sidebar .h-widget,
.x-sidebar .widget.widget_pages .current_page_item,
.x-sidebar .widget.widget_nav_menu .current-menu-item {
  border-color: <?php echo $x_ethos_sidebar_widget_headings_color; ?>;
}


/*
// Background color.
*/

.x-topbar,
.x-colophon.bottom {
  background-color: <?php echo $x_ethos_topbar_background; ?>;
}

.x-logobar,
.x-navbar,
.x-navbar .sub-menu,
.x-colophon.top {
  background-color: <?php echo $x_ethos_navbar_background; ?>;
}



/* Post Slider
// ========================================================================== */

.x-post-slider {
  height: <?php echo $x_ethos_post_slider_blog_height . 'px'; ?>;
}
 
.archive .x-post-slider {
  height: <?php echo $x_ethos_post_slider_archive_height . 'px'; ?>;
}

.x-post-slider .x-post-slider-entry {
  padding-bottom: <?php echo $x_ethos_post_slider_blog_height . 'px'; ?>;
}
 
.archive .x-post-slider .x-post-slider-entry {
  padding-bottom: <?php echo $x_ethos_post_slider_archive_height . 'px'; ?>;
}



/* Custom Fonts - Colors
// ========================================================================== */

/*
// Brand.
*/

<?php if ( $x_logo_font_color_enable == '1' ) : ?>

  .x-brand,
  .x-brand:hover {
    color: <?php echo $x_logo_font_color; ?>;
  }

<?php endif; ?>


/*
// Body.
*/

<?php if ( $x_body_font_color_enable == '1' ) : ?>

  .format-link .link a,
  .x-portfolio .entry-extra .x-ul-tags li a {
    color: <?php echo $x_body_font_color; ?>;
  }

<?php endif; ?>


/*
// Headings.
*/

<?php if ( $x_headings_font_color_enable == '1' ) : ?>

  .p-meta > span > a,
  .x-nav-articles a,
  .entry-top-navigation .entry-parent,
  .option-set .x-index-filters,
  .option-set .x-portfolio-filters,
  .option-set .x-index-filters-menu >li >a:hover,
  .option-set .x-index-filters-menu >li >a.selected,
  .option-set .x-portfolio-filters-menu > li > a:hover,
  .option-set .x-portfolio-filters-menu > li > a.selected {
    color: <?php echo $x_headings_font_color; ?>;
  }

  .x-nav-articles a,
  .entry-top-navigation .entry-parent,
  .option-set .x-index-filters,
  .option-set .x-portfolio-filters,
  .option-set .x-index-filters i,
  .option-set .x-portfolio-filters i {
    border-color: <?php echo $x_headings_font_color; ?>;
  }

  .x-nav-articles a:hover,
  .entry-top-navigation .entry-parent:hover,
  .option-set .x-index-filters:hover i,
  .option-set .x-portfolio-filters:hover i {
    background-color: <?php echo $x_headings_font_color; ?>;
  }

<?php endif; ?>



/* Responsive Styling
// ========================================================================== */

@media (max-width: 979px) {

  <?php if ( $x_navbar_positioning == 'fixed-top' && $x_layout_site == 'boxed' ) : ?>

    .x-navbar.x-navbar-fixed-top.x-container.max.width {
      left: 0;
      right: 0;
      width: 100%;
    }

  <?php endif; ?>

  <?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

    .x-navbar .x-navbar-inner > .x-container.width {
      width: <?php echo $x_layout_site_width . '%'; ?>;
    }

  <?php endif; ?>

  .x-widgetbar {
    left: 0;
    right: 0;
  }

  .x-content-sidebar-active .x-container.main:before,
  .x-sidebar-content-active .x-container.main:before {
    left: -5000em;
  }

  <?php if ( $x_body_font_color_enable == '1' ) : ?>

    body .x-sidebar .widget,
    body .x-sidebar .widget a,
    body .x-sidebar .widget a:hover,
    body .x-sidebar .widget ul li a,
    body .x-sidebar .widget ol li a,
    body .x-sidebar .widget ul li a:hover,
    body .x-sidebar .widget ol li a:hover {
      color: <?php echo $x_body_font_color; ?>;
    }

  <?php endif; ?>

  <?php if ( $x_headings_font_color_enable == '1' ) : ?>

    body .x-sidebar .h-widget,
    body .x-sidebar .widget.widget_pages .current_page_item a,
    body .x-sidebar .widget.widget_nav_menu .current-menu-item a,
    body .x-sidebar .widget.widget_pages .current_page_item a:hover,
    body .x-sidebar .widget.widget_nav_menu .current-menu-item a:hover {
      color: <?php echo $x_headings_font_color; ?>;
    }

    body .x-sidebar .h-widget,
    body .x-sidebar .widget.widget_pages .current_page_item,
    body .x-sidebar .widget.widget_nav_menu .current-menu-item {
      border-color: <?php echo $x_headings_font_color; ?>;
    }

  <?php endif; ?>

}

@media (max-width: 767px) {
  .x-post-slider,
  .archive .x-post-slider {
    height: auto !important;
  }

  .x-post-slider .x-post-slider-entry,
  .archive .x-post-slider .x-post-slider-entry {
    padding-bottom: 65% !important;
  }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ‰PNG

   IHDR  Ü  è   Kl«²   tEXtSoftware Adobe ImageReadyqÉe<  (iTXtXML:com.adobe.xmp     <?xpacket begin="ï»¿" id="W5M0MpCehiHzreSzNTczkc9d"?> <x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="Adobe XMP Core 5.6-c014 79.156797, 2014/08/20-09:53:02        "> <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"> <rdf:Description rdf:about="" xmlns:xmp="http://ns.adobe.com/xap/1.0/" xmlns:xmpMM="http://ns.adobe.com/xap/1.0/mm/" xmlns:stRef="http://ns.adobe.com/xap/1.0/sType/ResourceRef#" xmp:CreatorTool="Adobe Photoshop CC 2014 (Macintosh)" xmpMM:InstanceID="xmp.iid:111888F67B4F11E4BF18A97720AC8D90" xmpMM:DocumentID="xmp.did:111888F77B4F11E4BF18A97720AC8D90"> <xmpMM:DerivedFrom stRef:instanceID="xmp.iid:111888F47B4F11E4BF18A97720AC8D90" stRef:documentID="xmp.did:111888F57B4F11E4BF18A97720AC8D90"/> </rdf:Description> </rdf:RDF> </x:xmpmeta> <?xpacket end="r"?>„™e…  4xIDATxÚìİ}lTçèqº‚€kpÀÆ®!¡†;	°¼Ş†FMHJ£´Ùv£-—«¶RouKÔ¨ºREºíİ?¶yÙ?îvÕ H«ªQÉUoWİª,]å¶M¥$M©ÈËKŠ!Á%`‚k0ï^ ğÇ=·“‡1ƒ=şÍŒ?¡ÊÇ±™™sÎœò|çœç¼oòO    `hı™U    0ôD   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € #­€R°¬6wm¿¸öhû >Ü¨Ñ·«Ï/n<u¸ıü¹\]óªj¦WÕi%|SŞ6®nÒ˜±ÓÇÕf‹Í×7¤ÿµël÷ásİ§/üaï©£»Nu=wìàÀ×pÁV+½gN¾~ædinš‚•PìÇ]ZSÇ„É—ÛÊû»ÿÇ…?üşÌé³§7;4OfüÈÑıßFEz{×
?Øé•ÈÁ€(ï›üÂÓÖ@¸ƒ÷üüzë‰Îl¿éØ¡ò³áÁ“sïÍ/~eëó¥ùïşŸüù½êŞÉ¬jİğÄÁ]Ï'7jôò†¦ÔO½õúTuU¿›àyè­Ÿtşîš¥[­xÖìÛ¾rï¦¾fÕô…Ë§ÍÎ/Şğâÿ²‘vºŠô¸+š–Ôß¸hâ”«İÊÙ;ôåÃûW¿ÓZŒÊùâ‚¿ÈW¡şl£ÁR°­.{/üîÔÑ-Ç;^:z°xf€ÛA|³ÖÁÿ÷gNg+mMg[i6t úæL€JÉ²?ŸÈ5?:bÄæ®öì£‚?DWU“™ÌÇ'ÍˆŠ2Ù“ùjÓük¥çM­ÿÅ²?¿áÊWnÔè“›ÈµÔ©È;4ÛÊë;Ú¾Õ¶eÈÎ
)/Ù{!ûsgcÓÊ?ÅÊ"e¬Ê;øÿq¥5/n=Ñù‹CoÅj ®–(PiÔå²?_:ÑùTÛÖŠánòÌŞÃ’yU5C<ĞÍêÿpóâl,tÅŸì¹diD¯‹\.¹á>×ÕşØîí¥ã±ç|vÚœ+F·­üş‘×M­ßÇe;LöGš¹¢|¬´®ú¯'Ğ|~Úœ|óµÕmV@Ye JN6yæĞ›ıùÉÛ'L;òºéãj{ö³ï<9÷Ş¥í­î~¥ÂÖÏ's-½¿ùÕ¦ùŸyã¥R¨gƒóWØ}úèöSGÖ<Üûzf?É¶]¶áz_î´ .÷/‹şò;omºæ»³ı§ãìéb¼êÇ«wâÒšúÇoùÈ%#Ë·rÏ|+ÙV3¡±÷ÛóÎÆ¦E§üó¾m¼½­bV×W¶>¿5µjÜcÆöÇn¬Ğû=Õ“±ÖìÛ¾jÿöA?k¦õDç¶c¥ófyú­Í;Ouğà_7¦úÑYK–tÜ8”‡D ®™(Pr²u?ÏpI¬g¶‹‚7>‘kşIÕØ/¿ñëŠ¹àáZòÃ¶3Îç¿Î†¸¹Q£‡àefòí[ï*¸~ªçÉüªcïSûw\ñ#ıìIf.¿í²wÿä›Ò¿0{Q+›Ïßøµ=®á=sèM×@ÊöĞŒ… ÛÊ¯9ğ½;/bR¯¿;ÏkÏ†¸äÕOÙßüÅş|BcÅ¼=e¯[ZS_ı´6N/¸Rlù´ÙÙ7ÿn×Ëƒ»oo;Ö1dSğôÇÎS]ƒuğÏ7«]¶åY×”8·Ä¨«;Û>óÆK±aíæ®‹şMŸö¿}ë]ó2?>iFşëŸ¶ïê:Ûâ.oh*ö£g#Æµóï/(2Ù@}Uë†ëÿéÁİ¯\ÃEÙ†ûÔ¿?Ÿm¸Öcªì±²ñ¼}{èığÖ%+›§E&ÛÊköm¿kÃšì]vÅ"S ?òö¶Ù¯ü(ÛOò{lşíi+§²u»rï¦l]}eëóïˆº1ÕOÎ½÷±çXKı<øO­_I€J%Ê T”×ÏœÌFøÙØ¯`à·júÂ
xuóªjÒsõŸ;¼ïÕ#ò‹ŸrKQ}iMıwæŞWp1ËÏÛ[³úÀgÖÌ6Ü=›öè—²Á:¦ºoÂöê!öÃ[—œtĞz¢ó¿¾öÿVîİ4À“²ıdö+?Z³o{ÁÈù×‹—g{—5ŸZ{´={G|eëóë‹3dÈúéÿÁ_Æ(q¢@ÊÆ~ÿ4_>mvŒú¾Ú4?ÿõşîãëN~jÿtp[¼×ØSd
NÈFŒî~e¯XİÙv×†5ù6wµ›­sˆõ.2köm¿góÏq¢Ù•{7ı÷M?Më[¶_=~ËGœ/ÓÛÚ£í³_ùÑÏÛ[ÓofH—éÿÁÿ³ÓæØµ J™(P±ÿ4ú­Íéwş×Í*ëW”+Mœ’_üñßx÷Ãáô‡/L¹­HıÄ¬{Ò"³¿ûøC[Ÿ+ÆÔ-íçÏİ³ùgÙ°*Û|_~ã×öä¡ôØs
ŠL¶!Š1çÈº“‡ïÚ°&Û‹òß™Z=~íüûm‚Kzp÷+¡!ÛL•qö_‘şéÙXÙkÅäf« d‰2 ë‘··¥Á¢ç¾Ñåûr–74¥Sü®ùÓ)$¿8ôV:T+ÆgÂßŸó±tÚÑl,½lË³W;±ÈÕ«²Íg†Î¡´¬6÷ÅÒï¬jİ0ğÓ.'Û¸Ù^TĞe¾;óÃ6ÄåŞ½Ïş[Qüi¤ÊÔªıÛÓË¾>–LÅ@©e *ÙSm[ÓÅ/MU¾¯%2æµ#òÁ"­¥W‚úgÂİ8'È&{,74©<¹Q£ÿ¶å‹†µÅ,2=²½è¿ıMº÷~"×¼¬6gs\Rï.óõ–;\˜s¹]ë…½ùÅ©ÕãËºÈT6Q ’­=Ú~?kBc™¾¥5õé»ß;°3ı¯¿J†äZñq³‘Ìg§]4MæC[ŸSd*ÏÊ©³Ó“¡Öw´»ÈôXwòğ7w½œ~§ ‘ê}a»]Îs‡÷¥‹óÇM´N J“(Pá6ŞŸÿzjõø2ı`9,¦gŠßô¿¦ÓıfCëA<×à‘™·§SÉdÂ¢^µDˆyU5Ë§ÍÎ/víşÚCöè«;ÛÖw´¥;°ÙRú°rï¦44/¨Ë9·è’
T-ãê¬€Ò$Ê T¸Ç¥‹·+¿{0åFN§_í™â7U0İï“n”ÇÍ{Ù/¿˜‹1ç+áÒ»zeşñÍ×†ød¨¯íÙ^ÄôÉ\‹«rúğßş&]üRÓ\ëä’Ò£" %K”¨pO]ôyéø‘×•İKH§‰I§øML÷;((öVíù7»SåÉv•4ùeãØ¡¿yûùs?mïj©ª‘£–›ÂöòÖ<œ[Ô|}ƒ“e (_¢@…+øÌ¿ObO§‰I§øM=qpWz·‘ÏM9ğ±z:¿o6V/Æ°	W°«Ì=dVíß,óù‹g2¢À·Ú¶\´§Şj P¦D JÚ²Ú\:kÁ`,•Şmä£Óø¸wªŠ«Slé®Òu¶;*½µŸ?—ÎWíóKkêmËyıÌÉÍ]ïm©u9w L‰2 ”´t‚˜ÖÙ`ìr?ùƒwv§cÚ» äC§”ÂX¢ZZSŸ&¿gÚw>™µ‡ö¤‹Ë&İlõáÙwŞL?ÕğAë¤ÀÕò_Ÿºğ+ 4‰2 ÃKyıÓ¼`¾tâ˜Ş
><¿òµO÷[0VOÏÁ¡’„Ÿtş.ğÉ¬;y8½/Í‚ô¶º³-½àëú©ÖIÁÁ3½sÜÎS]Ö	@ie *}Øyñ˜åõOót¾l öÄÁ+œÈ~x>+î«Ÿ–.>wxŸ©"ÍšĞ˜ÿz÷ñ>ÎÃ¯9ÿºnLµKrúöZ²ºš¯opËªTzêPvğt®@Ée *ÜmÏì»7zØyUÒù>~ÕÓUVw¶¥çÌÓs’±zö®;yØT‘¦VÏ½ãXGøóÙtñì—ÔŞ`õaËñ‹6ÙíãÌÂó‚ùÑ­€’%Ê T¸ô¬ş®³İ¯—O”YÑĞ”^CôÔşıù­ôR£»¯uºßt.†ßu³U¤‚“È
‚HˆçLo3ÖfêÃöSGÒÅÛ'L²Nz<|CËE“%zÓ:(Y¢@%+¸¯óÎã¿/£'ŸN
Ó÷¿©tºßª‘£²ÁÉ5¬´t.†½§Ú‘*RÁIdûÎ
JíçÏ¥§z¥glÑ[Á)lÖŸ`ÍX˜<]»PÊD€JöÈÌÛÓÅïØYFãŠuïÈĞ÷¿©‚é~?>iÆÕ>ôô‹'òØX'PPãF^×Ç?ÊásïE™úÑÕ6SßZOtæ¿ş@•(3"7jô·ç,Ígå3Îÿõë­€R&Ê T¬Mi×ÈF/e47J:L¦øM¥Óı6_ßpµ³¥N­gç¦«ÍŸë÷gNç¿N¯@áŠÒ«‡§¥5õkçßŸÎ”ô·6½^VóˆC¢@Åşëüë-w¤ßyªmk=ÿ»¯rŠßTÁt¿_mšU¿^0‘‡3ÿ+ÕØäL™ôü”XgOÛ4ı—^]˜^u8=vãœïÌ½/-2«Z7\UÎ ÄH«  ò,­©ÏşuQ~ŞŞZFqááZÒ'ßÏ)~S/tì]>mvÏ×‹&NÉİ~şÜ0Ùú+oş/_º0wPşª_zk€ƒºUÓÍ«6ŸÈğtúÂòëmœşâ MÜ3ğ7ËµÉn+&7?k)8¯J‘(¢@¥yø†–‡f,L£Æşîã·m.£—N³¹«ıN¿_µ{>Êd«byCÓğŸ¤•Ğ¶ß%:¿¸6ËjsÎÕ*º1ÕƒuØ¶!¼¥úÒšúicÆµŒ«›3¡1Ê½G×Ùî‡w¼XF«s¢@Eß¾Ô4·àßèû»/Ûòl'RpÇ¨t‚˜şË^ïú¶;›z?=å%îÉ¹÷>9€_?sáüOÛw­Ü»Éš(#¢@ÙË½¼¡éã“fôşÈ´ìŠÌˆ‹§€é:Û½º³íÚşg½™2S«Ç/­©÷ÑñĞKoSTïyİ "å%;ÔÿøÀo×t¶Ÿë4*†(PrÇŒ]V›ëãÆ¼®e\]ÏO~p\íåÆ¢köm/»Ls£F/š8%¿øÂUNñ›Z{´ıoÏvç¯MøÂ”ÛÖ½ñÒpØ¾²õùÒ¹àåÍ?šÊŞ2OÎ½·ò¶¦k—Šª’=ºÎv>×½÷ÔÑMÇ=wì P¾D€’sgcSşkÓz¢óï÷¼Z'†,ohJgÃùÁ;»ò·=Ó¾ë‹3ä×jnÏC¨$é-´*Àú¶Ëİ~+{¥w7NÏß?òº¿7q@Ee *Ç™ç_;rà{v–ï¿Ô?=å–ü××6Åojõ;­ù(“Y1¹ù‘··]ñ·N]|?×=õ£«Kä™TXe(¶éãjÓ`¹¿œg½ÙÇ¹QKíÉßV/ûßìë‡¶>çèPîşÌ* (kÙ8¤õDçš}ÛİñÒŒõÿô™7^*ß£/­©O/Åº¶)~S=ÓıæÈµôç·vêJÇm7«Héírë<ƒ[ºÎvÛLı÷v÷±Ê~Ù±ı¡­ÏåÛSO—Ér€(oÎ”(9ë;Ú9tå±÷ÌÉHRj¾0å¶t8zÍSü¦Òé~³wn0|üÂE—8İ6®Î¼©4O‰JÏÙ9|N”¹‚tvóÓoĞŠ”í¢ßÜõò£³–ô,Vµvşı·¿ú¯ö€ò%Ê ”œ³§‡aÈÎ¤3)~SÓı>0é¦+®Û‚‘ùMckí“©à”¨icÆˆ2Ù» =ggï©£6SæUÕ¤‹Ãdu­îlkÙW·|ÚìÅ©Õãxë’ÏYÌ*’(@IX1¹9]¼½~ê‹ıQîllš×¶åŠgµèÌÛøØ:iã©‹ÌÂ	“åä¬¸oÂéâîÓ¢L_–Ô^´º6;4L^øÊ½›æLhÌ£²ÃÚÃÇ;8¸Ë.PD JBÁ„/—»Ï÷À}nòÌ×¯tÜ½§æ<ucªçUÕTØ•bŒxwÊ¡ıİÇó{Ú¬"DÀ«µpÂ¤tñ¥£m¦>ÌÑ&+¨l•íóÛ~ù‹EäÏ«ZÙ¼xû©#&ı(G&ú Ş²ÚÜÍ´úÑÆéWü™Mäş©†ÚFiG2×ïÔêñ—Ã½Mœ’ÿºël·Ø·EÉêÚß}|Xİğ>{±ïx1½á”IÊ”3e ˆ÷À¤›Ò±è`M(“7väuŸÈıçåQucªW44õ}¡Jö_±$¿ø±I3ús/mÊÎÚC{ò;ÆˆwëÛëqziM}š&_=rÀêCö.î¹9t_zk¸­u'ç­M+›÷,fkãûs>vÏæŸÙ7 Ê‹(@°yU5é¿Ï´ï*FÙ>qJ~Ä{ÿä›®8{Èú¶ü³ê9‡Âi9¬íJçÎµÖ·e“nN×ÚcõaIıéâO:7WÂwÍ[›‹Í×7|wæ‡ÜıŠİ Œ¸|	€`Ÿ›<3]\ıNk1å™ö÷fÁ\P—»â…*/~;]üjÓ|[ª"¥§eõÜ4=äiäF¾;¹°®ël·ùAúPPr[OtÛfúàîWöwÏ/~"×üğ-ö€2"Ê ,äe}G[‘&†(h=_š:ë
?ßÙ–Œó‹¼mSô„#ÃŞÙ}ÑÑ47äi¬œ:;½'mˆôVIŸjÛ:œ×Æ²-Ï¦“Ë<4cáÒšz;	@¹e ˆ´¢¡)Gã™CoéÚÏŸ[ßñŞ%Kw÷cºßïï»èJ–GfŞn{U×ÏœLwŒæë²}rˆŸCnÔèO&wËØE:_¬2,­©OO“Ùß}|íÑöá¼B²ƒÛC[ŸË/VõÄ¬{LúP.D "İ?ù¢)~‹:¸úŞé¸åŠ'ù?qpWz²Ì‚º\Ôµ-Õ·Ú¶¤‹ÿó¦EC< ı‡›§§Éü´}×°º‘ĞÕzü–¤‹«öü›u²îäáU­ò‹ucª¿?çcV@Ye 3¯ªfAİ{™£Ø—ldã–tò…OšqÅ_)8YæÏºÛçÏ•çõ3'ŞŞšhÿáæÅCöè+šÒó>ºÎv¯Ü»ÉF¹œUÓN­Ÿ_ÜÜÕ>ÌO“É{âà®‚s¾¾;óÃV@ée S0±Ë\²ñã¿M-Wœ&&ç´èÌ/Vµvşı6\åy¼msÁBC3[êÒšú¯·Ü‘~çïv½ls\N¶Q–O›_<sáüc»7Z-y_Û³Á¤¿ eG” ÌİC2ÅojMg[:#fî©ô×o¬OejõøŞº¤ØÏ37jôªé]-5d²}¯ †¬l^\ìm¶•¿å#é…K?oouŞÇå,­©hÆÂô;ßÜõ²ÕìÆ_Ş¶Î¤¿ åE” F6âM‡£é„/E´¼vä@~qÑÄ)W¼)õ}óâáúMEí2óªjÖÎ¿ù´ÙOÎ½×İCfíÑö§ßÚœ~§¨]¦g+§Wâìï>şàîWlˆË.şïÂO¦GŒ5û¶¯îl³fz¯şfÇ¯ò‹&ı(}¢ 1Ò)]²áèº“‡‡æq¦û]Ş[ídc¿‚áúM?ôWÅê,«ÍıË¢¿ÌÕW6/6 2¼½-•£gı¯š¾pĞhiM}º•G¼;•Ì²-ÏÚ—ôİ™Î6Dúl3™yçr
ò¢IJœ(@€yU5Í×7äÓ©^Š­`ºßOO¹åÚ†ëÙˆú×‹—îí“³ñÿ“sïMOXÕºÁx†ÒgŞx©`C/Ÿ6ûÅqÅé‡®j+œôqæÂù‡w¼hC÷¶¬6·ñCõ‰\súÍle›ÉÊéûxµ¹ë½ëàLúPÊD ¤“¹d#Ò5C{BÚ€¦VïçœÙ8pÍ¾íéw²qõ£³–ÊˆıáZ¶ø¿¥“˜x·È<qp—½eˆõî2Ù˜ög‹—­š¾p€g-]r+ïï>~×†5Cv¦X¹XV›ËŞYOÎ½7=Ÿ(óô[›™şøò¿.˜ôwp2 ƒe¤U ÀËF¶‹&NÉ/¾väÀŸ#ğÄÁ]ÍX˜?UáSn[×¿aŞÊ½›=şn~ÄŞz¢óGûß¸ÚI.²U±¼¡éóÓæÔ©N¿æÂù¿Ùñ+s¾FÉ†ıï(¸dfù´ÙŸÌµd»ë÷ì¼ª†2¯ªæS| ×R°•G¼{Gçlğì™¼¥5õwL˜ü±I3
ZÌˆw¯ğú»]/{SôS¶S}ã·¿ùÎÜûò«¯·Ü±ïì)ù Ôˆ2 \Ö“nº}Â¤AüğÎî×Ïœ\ŞĞ4ôSüøUÇŞü5w66åöô÷*¡'îÚ~êÈã·|¤`ĞØ|}Ã£³¾~álĞşæé£;Ou]nô˜Ñ§WÕd+vÎ„Æô®Á¨ß6®®H+mï™“Ãçf7—ÜĞÙ~›í-ÙŸ®³İ;ÿ¾gCo<u¸÷ÆZV›?òº…&ÍšĞØ»/Œx·»ıó¾m¼½mpŸv¶Sî$8ÏîçéZ×|³°ƒLöÌo¬R?oo}¼mó ×«Æ1c‹t³Rx³¬;yø›»^~tÖ’üŞ›íÏ»¶<+”Q€ËÊŸƒûn<v(¨¤Ó¸å¿©§öïH'ªX1¹¹ÿÃãì	¯{õ_»qÎg§Í)Cşç }Ä×Û“zÿqáÙïyİ%ç©l¨ÿo¾6À{Ê|qÆ‚"­´5û¶¿>œfWícC×©Îoèk°¾£í[m[Š1ho¾¾á’¥ïšµèìg”yrî½ÅØ
Å[W=‡¸A?Ê•Ô›%;˜,lŸ”?Öe‡ oßz×§şıyÿïP:D †ÔÒšú´Må¿©lŒ—6óÃ×r-W{ÎBöó«ßi]9uöG§÷¾,%ïŠ!¦G×ÙîïïÛf™ÔÏ]
‰¡’dïˆ:ööœ[gmÄƒ»_yq\mşX· .·júB÷®(¢ CêSnKÇ]C<Åoê©¶­ùÏö³Áö²ÚÜÕNWÑ~şÜÇ6{7­hhZRã¢‰S.wñE#ÏWX{h‰JÙÀ7ôæ®ößŞŸíí®éûíğ»îcÿ~¬ãåcïxG¢ÏoûåÚù÷çñòi³wêZwì õ¾É/<m- „Kç5œŒ 7jôíãê‹÷÷o<u¸¥ªfüÈÑùÅØ1êÒšúü“”ÕmÇÛÆÕİ4¶öUcëGW÷¾÷íîcÙÛu<{ú¥£eC{«]ÕÙ3cN~qÈ¦e-X	Å~ÜlÏ™=nâÌ±µÓÇÕf‹“¡ô\³öû3§;ÎŞxìĞ<™ün<è_8w¹>R°­‹ô(E=ØÆ¾Yz?Ÿ¢ÓÊ@ßD   € f    =Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê ÀÿgÇ    ù[cOa  )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    )   02    ƒ ÀÊY)‰ã    IEND®B`‚                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      ‰PNG

   IHDR  Ü  è   Kl«²   tEXtSoftware Adobe ImageReadyqÉe<  (iTXtXML:com.adobe.xmp     <?xpacket begin="ï»¿" id="W5M0MpCehiHzreSzNTczkc9d"?> <x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="Adobe XMP Core 5.6-c014 79.156797, 2014/08/20-09:53:02        "> <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"> <rdf:Description rdf:about="" xmlns:xmp="http://ns.adobe.com/xap/1.0/" xmlns:xmpMM="http://ns.adobe.com/xap/1.0/mm/" xmlns:stRef="http://ns.adobe.com/xap/1.0/sType/ResourceRef#" xmp:CreatorTool="Adobe Photoshop CC 2014 (Macintosh)" xmpMM:InstanceID="xmp.iid:111888F27B4F11E4BF18A97720AC8D90" xmpMM:DocumentID="xmp.did:111888F37B4F11E4BF18A97720AC8D90"> <xmpMM:DerivedFrom stRef:instanceID="xmp.iid:0685757F7B4F11E4BF18A97720AC8D90" stRef:documentID="xmp.did:068575807B4F11E4BF18A97720AC8D90"/> </rdf:Description> </rdf:RDF> </x:xmpmeta> <?xpacket end="r"?>X½ñ»  4µIDATxÚìİ}tUåèqgÖüCm;§L	¾T)¨Œ\&“^Ë¥ağ1†Pj\ËxQÆßš”âê²ÆEq_±--c\«±”0R)‚Ê˜r©×4rqP ŒõœÁ+/3³¦–ïns×ñá`BHròË9ù|«ëìHrÎÙ{Ÿ]oö~öq^Å)    ô¯?¶
    úŸ(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   @”    Ê    e    ˆ2    D   € ¢   @ Q    €(   àO¬€ fúe=ûÆæ§ŸëÃ—Q6|Ø„Šq¹Åmm;ö88 WWùØQçsfVBï7å…çî/>ıççœ}V¶8fô¨ô¿:|äàÁCüîwoüæ­İ{şeÃó¿èıÎÛj…óÆ›ï´ïÜ;07MŞJ(ôóN©¬˜ø…ŠÎ¶ò¾ıï~ğÁïŞ?xğÀ{ï¿ÔöJ?¼˜?ûÔŸvècXè]+ü`[ w4@ş Dù£3Î«° Âıß½/÷æÛwïÙûæ[o·µïèå ?4Ş×[¬¿cñÀüwÿ†æÇËÇ8_ÑøØòGV¾²áÃ®©®ªœxñc>7dÈ“úŞl ¿éùúÇg6÷xPš·Õ
§yÍºúEËºş;÷.¬™5#·øç£>ßo#ít%èyëjgN4¡¢|üÉnåìú¿^zùñÕO¢r¾¸¾)W…º³úJŞ¶î½ì³ğÖÛï´oÿç-¿øeáM/¶}øaé«ƒÿûf+íÇ-fC kÎ”(Ù,ûS5õò»—,hß¾£éÇ?-á_¢–•™Ì•S.‰Š2Ù‹YğõºŒÒsF–¸ñúÚìOÉo¸âU6|ØÜÙWÏ˜V5ôôÓzó	Í¶rëÖmË¿»²ßÎ
).Ùg!ûS9qÂüú›:be2Véü;VÚî={¾é…ØBÀÉ2§@©)?®ñ¾†×7õÏYúı¯¶¦úøaIùØQı?PÿÉª6¬mÊ†C]™C‡dƒ¥ìOw6ÜïOê÷÷Bî¼uë³kn¼¾¶ë"Ó±•÷í·ëŸ–í-Ù>“í9¶r×:be{ë3ÖU÷eGÂùõ7½şÒÆºÚ™Ö@±p¦À€Óºu[Ëúİù›WüÕ'>~ê9gŸ•7¥EÇ¿Î³şå“¿xCı’[?Ó¿tÅñ_\ğõº¯Ì™×Ÿõkk¾ü‘-&œ¿ÜÖ¾gïo^}m×¦Ö¶ãÿBÇì'Ù¶;÷³g¹Sùøq?}ò{~ouİí?Ş{¿ïú¥¶WÕ'qJeEÃ·¾9²lD¶rÇ|+ÙV¾hìÇ<+'şş¨'›ºxéÃ%³ºêïXÜƒï9â3Ÿù‹OwÇÎ:³ìøÏT¶®²?ÍkÖİÿğª>?kf÷½¯î|}à|X¾ÿ£¦×vıº—ÿ¡§Ÿv÷’“'MèÏC" =&Ê 8Ùˆº›×°¤­c¶‹lô’ş…ª©—oøó3ê¾±¸d.XpËìÜ°íèÑ£¹ÇÙ·lø°~x›Ù³¬üû†¼ë§:^Ì–·>úƒ¦^™’½ÈlÃå¶]¶á®ªº<ıÙ›š_Sùø¿œ¿xiŞQËú®ê“=íæ¯ÎÎkÙVnkß¾ê‰æ1©l7Èştlˆ¼ú)ûÉ7^_ûß.[2Ï>Ùë¦TV\yùäK'Wæ—T3kFöÅ{–=Ğ·ûö«;_ï·)xºãµ]¿î«ƒ¶ø«ÖV_[çú/€ÎåK %beÓÚ¯Ì™W5ó÷S“¤_ÏFû+ÿ¾¡dŞæ•S.É=~ú™g>’â^S]UègÏFŒ-O®Ì+2Ù@}Eãcg]4é†ú%=˜+$ÛpU5s³—w}S6¦Ê+ÏÛ·ûßOV=0¿ş¦´Èd[¹yÍº‰WÌÊ>e',2y²Qñâ¥_pñÔl?Éí±¹§­œÊÖmı¢eÙºª¿cqŞ'bèé§5Ş×Ğpç­ÖR7ş#ËF”ÒÁ T‰2 %¥}çŞl„Ÿıò~÷.,wW>vTz®şÏ7oy¹­=·8«úª‚>û”ÊŠÇ\w1Ë†›³zïgÖÌ6Ü¤iµwİ³<ü§cªªK¿h¯îg?Yõ@ŞI»÷ìıòµ_­_´¬—'dûÉOm^³.oä¼õÙ5ÙŞeÍ§šŸ~.ûDÔß±8/cİx}m¶¬Ÿîüe,€N”(AÙØ/ïŸæ5³f”À¨oÁ×ërs–Ö¶GĞ”n÷;ŠLŞ©Ùˆñ†ú%}xuÀÊ¦µ¯˜•;A }ûì+öçşt|‘i^³nÒ´Ú>¼_Rı¢e×İx[Zß²ıªá[ßt¾ÌñšŸ~î‚‹§nØ¸9ıb¶t™îü¯­ù²]` e JöŸæßÿQSú•;çßVÔï(WT”Ï-®iùÙ)øåpzÃœëj
ôÔ,»;-2ûö¿;÷k
1uËş'M«Í†UÙæ«ûÆb{rj¸óÖ¼"“mˆBÌ9²©µmâ³Ò[5,ÑòäJ›à#İP¿$/4d›©4Îş+ĞÁ?=+;pÍ}µÕ0`‰2 %kñÒ‡Ó`rßè>tMuU:Åï[6t<şù¦Ò¡Z!~'Üôøé´£ÙXºúÚº“Xäd‡UÙæ3Cgª™~Ù××¦_YÑøXï/LëL¶q³½(¯Ëü°ñ¢³OÄñgÿ¹ñsgîxUzÙ×”KÿÆ:°D€R¶rÕéâÍW[¼ï%2¦­}{.Xd£µôJ>ÿpÃ·¦ÙdÏå†&¥§lø°%¹pA‹L‡ßO üíï¤{oÕÔËk¦_fs|¤ã»ÌÂÛosaNg»Öó[Zs‹#ËFu‘(m¢@)k~ú¹ôWñc/<¿HßÈ”ÊŠt†İUO4§ÿuË‹[sgLëË{0e#™kk¾œ~eî×(2¥çö[ç¤'CµnİVè"ÓaSkÛ²ûJ¿’×†Ha»uæç›·s(7Ö:˜D€÷Ëÿı«Üã‘e#ŠôËéd1Sü¦ÿ5î7Z÷á¹w/šŸN%“zÕ!ÊÇª™5#·xèğ‘ù‹—öÛ³¯lZÛºu[º›-¥õ‹–¥¡¹|ü8ç}¤¼#Õ˜ÑçY' “(Pâ^j{%]œP1®èŞBÙğaéô«Sü¦ò¦û­6µO7ìeC¾Üb6,Äœ¯„Kïê•yèÑôóÉPó/M/bšş¥+\•Ó…ÅßşNºX7ç:ëä#¥GE ,Q ÄmkÛ‘.~ê“Ÿ,º·N“Nñ›Ê›î·O&PÈì­xĞÍqJP¶«¤É/ÇöÿmÈ÷8øô3Ïæ‡rMu•MÓ™M­mé¹EcFr² ÅK”(qy¿ó/Æ“ØÓibÒ)~SËYŞm¤¶¦º÷cõt~ßl¬^ˆ`.oWÉ›»ßÜÿğªôd™ÿy­{weùw)¤µ×|Ù: H‰2 h5Ó/Kg`ÍŒ¥Ò»\:¹²—Ï›w§ª¨±:…–î*‡‰JoûLç«Îöù)•¶NgÚwîmßşá9€åãÇ¹» EJ”`@K'ˆÙ½go6ëìo65·¤cÚºÚ™½yŞÏW”„±:5¥²"M~ëÖo|1O;YÒÕÉ=à9ŞÏ6lNÿöK—['yÎ:³,÷ø¿~û[+``e —âú§yŞ|éÄ1ÇËûåùUU=¤åÕÓsp(%yáãŸÙøb6µ¶¥—à¥Yã­lZ›^ğõ?.ş¼u’wğLï÷Ú®_[' “(Pâò¦À,®š§ó}d°å¬îúï§¿<ïÍW^>9]üùæ-v¤’4öÂós÷í·‹ó°úÇËmí¹ÇCO?Í%9]kkß{<fô(·¬J¥§eOçúX¢@‰»ğüÏ¥‹o¼ùN½øt¾tÆÎ¬lZ›k7/L÷]4ö‚Üãìnjm³#•¤‘e#rw¾¶+üõ´µs¯´É_ü‚mÔ…öíÿœ.N¨gääÍn… X¢@‰KÏê?tøHø¹ İWW;3½†èÑ4uç»ÒK&OšØ³§Nçbxû}ö¢’”wY^	±áù_¤‹Ÿù‹OÛL]xõØvqÅ_Y'Ü2;=x¶¬ßh X¢@)Ë»¯óë»vÑ‹O'…ézŠßT:İï!C²ÁIVZ:Ã¿yËT’òN"{kßşğ—´ÿÀÁôT¯ôŒ-—w
ÛğOŸatÁnşêìôàéÚ%€L”(ew/šŸ.®z¢¹ˆÆåã?¼¡ë)~SyÓı^9å’“}êsÏ93]|©í;RIúÄÇ?ŞÅ?ÊÁƒ‡r‡j3um÷[íÃÌ)sJÙğa}wi.+=zôöE÷X- ™(P²êjg¦]#½ÑÜ(ét0İ™â7•N÷;fô¨“-uäˆÏØyƒs?{vîqz~J¬÷Ì=N¯@á„Ò«§)•-O®LgJzô{«‹è’U€ÁI”(Ù/¼ı¶ô++W=QD¯?¦;SüóNîwÁ×ëNêÛó&òpæ©:õcË=NÏO‰uà½÷mšî{ó­·sÓ«¡†;o}üÁåi‘YÑøØIål Bü‰U Pz¦TVdÿ:O‡(6n.¢¸°à–Ùé‹ïæ¿©ç·´ÖÌšÑñ¸¢||Ùğaû$[ş×êêæ\×'?êç›^èå ®ñŞ…ıó®Í'28ı×o?èÍ·_:¹òÅõKÏd·¹³¯1­*ï¼*E Xˆ2 ¥fÁ-³oşê1QcßşwïZö`½…t"˜öí;zpúıı¯ÊE™lU\S]5xÆ'é¯Ê{éÕ¯÷ò'ä¶=\Ó/s®Vá=ı´¾ºF¬÷–î›RYqöÈ²1£Ï»hìéTî>2oá]Et±*À 'Ê ”Ôø­nÎuyÿFß·ÿİêkëŠè<‘¼;F¥Ät_ö~[·n«œ8¡cqVõU~ip÷5dzüíG}ú™gë-³&Šˆ(PôÊ†»¦ºêÊ)—ÿ+Ó¢+2§;Ì¡ÃGV6­íÙÏiY¿1eF–˜RYáWÇı/½9NAzêÇúğ!(.Ù¡~MËÏ~Ü²ağ\§	P2D€gø§Ï¨™~YáSŸüä˜ÑçuüÍ³Ï:³³±hóšuE÷+Ó²áÃ*ÊÇçŸßÒÚãÕüôsKÎË]›0çºšAeêïX<p.x™4­¶(ûÈôæƒËµK…]½Exìpèğ‘ƒ½ùÖÛmí;6<ÿ- x‰2 NåÄ	¹S<zf÷½KW<TŒâšêªt6œ¦æ–Şü´uë7Üx}mn­ªé~a0øÄÇO-¥·Óºu[g·ßÊŞéäIs‡ÇS?6d^qäÈ#Ê ”£G¶µo_õDsñşK}VõU¹Ç=›â7õøê§rQ&3wöÕ‹—>|Âïú¯ßş6]tİÓ`0lØĞòJJ¬2Ú9gŸ• ‹ıí´¬ßØÅ¹Qémõ²ÿÍÏıÚG'€b÷ÇV@QËÆ!»÷ìm^³î®{–ŸuÑ¤¯Ì™W¼ÿFÏ†é¥X=›â7Õ1İonqÆ´ªî|×k»~.şÙ§şÔnV’ÒÛåôÕ-xú¶2:|Äfê¾·ßÙ_Úo0;¶ÏıÚ‚\{êè2eÃ‡Ùô EÍ™2 NëÖm-ë7ğ¯½ñæ;½<‘d ™s]M:íñ¿©tºßlàİÿûügºxáùŸ;Å¼¥h`•³sğà!›©kéìæüîw%ÿ~³]tÙıİ½dAÇâ!CZ\ù×—Ì´' /Q`À9ğŞûƒpvÏ²áÃÒ™tz3Åo*oºßêiSO¸nóFæ£Î=Ç>Y’òN‰:{dÙ)§´…
ÒsvŞ|ëm›©åc¹ßÜ¿yk0¼ë•MkÇŒ>¯fÖŒÅ‘e#~²ê¯Ì™g (R¢ ÂÜÙW§‹_øïıâú¦>–Ê‰²Ü	Ï0Ú½goî7ğœ?ÆÖ)IÛÚv¤‹åãúää¬Ş¨ºô‹éâ½¿±™º0ù‹_H_j{e¼ñúEË.{Aî•ÖÜ2{ù#«í ÅH”`@È›ğ¥³û|÷^mMuûÎÜ÷Í·ŞÎx†~Zw:Egÿƒûö¿›ÛÓÆ^x~øKª(—.nùÅ/m¦.”ÿËt1¯²•¶Ú¹ómy"w^Õüú›^}m—IŠ‘‰~ˆW3ı²~›iõÒÉ•'ü;míÇŒîşöK—ÛF%içk»rG–È»¦ÿ}¾¢<÷øĞá#R`×*ÊÇçïÛÿî ºá}öfç-¼+½á”IŠ”3e ˆW=mj:í«	er>ññS«¦şÿ°2ôôÓêjgv}¡Jö_sSif¦\ú7İ¹—6Eç©–ŸåvŒSşPß;È”ÊŠ4M¾ÜÖnu!ûwÜºÃ¦çÿi°­M­m~oõüú›:³µÑôø“¦ÕÚ7 Š‹(@°ò±£Ò)~×­ßPˆòzEynÄ{UÕå'œ=¤uë¶Ü«ê8‡Âi%9¬=tøHnÇ˜1­*°¾]]}UºøTËÏl .L4!]üÇg6Â•°ü‘Õ£G}6ÇŒõÃÆ{n¨_b÷ ("._ XmMuºøøê§
ñ,ëÖoÈ=.?î„ªlyq[º¸àëu¶TIJOËê¸izÈË(>lò¤‰¹ÅC‡˜¤y%w÷½ƒ¶™ŞP¿dßşws‹US/_pËl{@e –NòÒºu[&†Èk=7ÿİ	Nò_Ù´6ç;nÛdc•¦æ–t±nÎu!/ãö[ç¤ã¤‘ãåEÒ•«Ìk£ú