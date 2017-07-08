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
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        �PNG

   
?������(�����@�������z��l���ء����s��/~e�����������ɬj����]��'7j�򆦏�O���T�uU��
)/�{!�sgc��?��"e��;��q�5/n=���Co�j ��(Pi��?_:��T�֊�n���ÒyU5C<����p��l,tş�diD��\.��>���������|vڜ+F�������M��Ǐe;L�G���|�����'�|~ڜ|�՝mV@Ye JN6y�Л����'L;���j{����<9�ޥ���~����'s-���զ��y�R�g��W��}���SG֝<��zf?ɶ]��z_.�/���;om�揻������b���
xu�j�s��;���#򋟞rKQ}iM�w��Wp1���[����g��6�=��莗��:��o�
N��F��~e�X��v׆5�6w���s��.2k�m�g��q�ٕ{7��M?M�[�_=~�G�/��ڣ��_����[�ofH��������ص J�(P��4����w���*�W��+M��_��ߎx�����/L��H�Ĭ{�"����C[�+��-���ݳ�gٰ*�|_~�����؍s
�L�!�1�Ⱥ���ڰ&ۋ�ߙZ=~���m�Kzp�+�!�L�q�_����Xفk��f��d�2 둷��������r�74�S����)$�8�V:T+�g�ߟ�t��l,�l˳W;������g�Ρ��6����j�0��.'۸�^T�e�;��6������[Q�i��Ԫ���˾>�L�@�e *�Sm[��/M�U��%�2�#��"��W��g�8'��&{,74�<�Q���压���,2=�����M��~"׼�6gs\R�.���;\�s�]녎��ũ��˺�T6Q���=ڞ~?kBc����5����;�3���J��Z�q���g�]4M�C[�Sd*�ʩ�ӓ��w����Xw��7w��~��
�T-����$� T�������+�{0�F�N�_��7U0���n���{ِ/��
><��O�[0VO�������t�.�ɬ;y8�/͂����-������I��3�s��S]�	@ie *}�y����O�t��l ���+�Ȑ~x>�+�.>wx��"͚И�z��>���9���nL�Kr��Z����op˪Tz�Pv�t�@�e *�m��7z�yU��>~Տ�UVw�����s��z��;y؎T��V����XG���t�
�H��Lo3�f���SG���'L�Nz<|C�E�%z�:(Y�@%+�����/�'�N
����t�ߪ�����5��t.����ڑ*R�Id�Ξ
J��ϥ�z�gl�[�)l�֟�`�X�<]�P�D�J��������YF�u��������~?>i��>��'��X'PP�F^��?��s�E����6S�ZOt��@�(3"7j���,�g�3�����R&� T�
?�ٖ
�IJ�(@�yU5��7�ө^��`��OO��چ�و�׋��퓳���s�MOXպ��x��g�x�`C/�6��q�釮j+��q����w�hC���6��C��\s��le�����x�����L�P�D ���d#�5C{Bڀ�V����8p;��w�q����ʈ��Z�������x��<qp��e���2٘�g�����p�g-]r+��>~׆5Cv�X�XV���YOν7=�(��[������.��wp2 �e�U ��F��&N�/�v���#���]�X�?U�Sn[׿a�ʽ��=��n~��z��G�߸�I.�U�������ԍ�N�������+s�FɆ��(�df��̵ٟd���켪�2���S
Z̈w����]/{S�S�S}㷿��������ܱ��)��Ԉ2 \��n�}¤A�����Ϝ\��4�S��U���5w66����*�'��~���|�`��|}ã��~�l����;Ou]n��
�[W=��A?ʕԛ%;�,l��?�e��o�zק��y��P:D ��Қ��M���l���6���r-W{�B���i]9u�G���,%�!�G������f���
���d�:���[gmă�_yq\m�X��.�j�B��(� C�SnK�]C<�oꩶ�����������NW�~���6{7�hhZR㢉S.w�E#�W�X{h��J��7����ޟ��������c�~���c�xG��o��������i�w��Zw� ���/<m- �K�5�� 7j������o<u���f������1�Қ����՞m�����4��Uc�GW������c�ێu<{���eC{�]՞�3cN~qȦe-X	�~�lϙ=n�̱����f����\���3�;Ξ�x��<��n<�_8w�>R����(E=�ƾYz?����@�D   � f    =Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    � ��gǎ    �[cOa  )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    )   0�2    � ��Y)���    IEND�B`�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      �PNG

   
    ��(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   @�    �    e    �2    D   � �   @ Q    �(   �O����f�e=��槟�×Q6|؄�q��mm;�88 WW��Q�sf�VB�7���/>���}V�8f����:|���C��wo���{�e������j��ƛ���;07M�J(��N������ζ���~����?x��{���J?��?�ԟv�cX�]+�`[�w4@� D��3Ϋ� ��߽/���w����[o����� ?4�א[��c���w������8_����GV����î����x�c>7dȐ���l �����g6�xP���
�yͺ�E˺�;��.��5#���>�o#�t%�y�jgN�4��|��n����^z���O�r���)W�����J޶������o��-��e�M/�}�a髃��f+��-fC�kΔ(ِ,�S5��,h߾���?-�_�����̕S.��2ًY�����sF�������O�o��U6|���WϘV5���z�	Ͷr��m˿����
).�g!�S9q����:be�2V��;V��={���B
��,�;-2���;�k
1u��'M�͆U����b{rj��ּ"�m�B�9���m���[5�,���J��#�P�$/4d��4��+��?=+;p͝}��0`�2 %k�҇�`r��>tMuU:��[6t<���ҡZ!~'���鴣�X��ں��X�d�U��3Cg��~ٍ�צ_Y��X�/L�L�q��(�������O��g���sg�xUz�הK��:�D�R�r����W[��%�2��}{.Xd���J�>��pÝ���d��&��l��%��pA�L��O ���{o���k�_fs|�����osaNg���[Zs�#�Fu�(m�@)k~���W�c/<�H�Ȕʊt��UO4��uˋ[s�gL��{0e#�kk��~e��(2���[�'C�n�V�"�aSk۲�J��׆Ha��u�盷s(7�:�D��������e#����d1S���5��7Z��w/��N%�
��O�a�t�n�������%��L�(ew/��.�z������?���)~Sy��^9咓}�s�93]|��;RI���?��?����r��
�sv�|�m���c�����yk0��Mkǌ>�f֌�őe#~�ꁯ̙g (R� ���W��_������>�ʉ���	�0ڽgo�7��?��)I��v�������ި���➽����0��_H_j{e����E�.{A����2{�#�� �H�`@ț��|�^mMu����ͷ��
�,��o�=.?��lyq[����u�TIJO��iz��(>l򤉹�C����y%w�������P�d��ws�US/_p�l{@e �N�Һu[�&��k=7��	N�_ٴ6�;n�dc����t�n�u!/��[��