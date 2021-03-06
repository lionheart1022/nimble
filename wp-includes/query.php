---------
 */

.sidebar .archive-meta {
	padding-left: 316px;
	padding-right: 0;
}


/**
 * 5.10 Search Results/No posts
 * ----------------------------------------------------------------------------
 */

.sidebar .page-content {
	padding-left: 376px;
	padding-right: 60px;
}

/**
 * 5.12 Comments
 * ----------------------------------------------------------------------------
 */

.sidebar .comments-title,
.sidebar .comment-list,
.sidebar .comment-reply-title,
.sidebar .comment-navigation,
.sidebar .comment-respond .comment-form {
	padding-left: 376px;
	padding-right: 60px;
}

.comment-list .children {
	margin-left: auto;
	margin-right: 20px;
}

.comment-author {
	float: right;
	margin-left: 50px;
	margin-right: auto;
}

.comment-list .edit-link {
	margin-left: auto;
	margin-right: 20px;
}

.comment-metadata,
.comment-content,
.comment-list .reply,
.comment-awaiting-moderation {
	float: left;
}

.comment-awaiting-moderation:before {
	margin-left: 5px;
	margin-right: auto;
}

.comment-reply-link:before,
.comment-reply-login:before {
	margin-left: 3px;
	margin-right: auto;
	-webkit-transform: scaleX(-1);
	-moz-transform:    scaleX(-1);
	-ms-transform:     scaleX(-1);
	-o-transform:      scaleX(-1);
	transform:         scaleX(-1);
}

.comment-reply-title small a {
	float: left;
}

.comment-form [for="author"],
.comment-form [for="email"],
.comment-form [for="url"],
.comment-form [for="comment"] {
	float: right;
}

.form-allowed-tags code {
	margin-left: auto;
	margin-right: 3px;
}

.sidebar .no-comments {
	padding-left: 376px;
	padding-right: 60px;
}


/**
 * 6.0 Sidebar
 * ----------------------------------------------------------------------------
 */

.site-main .widget-area {
	float: left;
}

.widget-area a {
	max-width: 100%;
}


/**
 * 6.1 Widgets
 * ----------------------------------------------------------------------------
 */

.widget .widget-title {
	font-style: normal;
}

.widget li > ul,
.widget li > ol {
	margin-left: auto;
	margin-right: 20px;
}

/**
 * 7.0 Footer
 * ----------------------------------------------------------------------------
 */

.site-footer .widget-area,
.sidebar .site-footer {
	text-align: right;
}
.sidebar .site-footer .widget-area {
	left: auto;
	right: -158px;
}

.site-footer .widget {
	float: right;
	margin-left: 20px;
	margin-right: auto;
}

.sidebar .site-footer .widget:nth-of-type(4),
.sidebar .site-footer .widget:nth-of-type(3) {
	margin-left: 0;
	margin-right: auto;
}


/**
 * 8.0 Media Queries
 * ----------------------------------------------------------------------------
 */

@media (max-width: 1069px) {
	ul.nav-menu,
	div.nav-menu > ul {
		margin-left: auto;
		margin-right: 0;
	}

	.error404 .page-header,
	.sidebar .format-image .entry-content img.size-full,
	.sidebar .format-image .wp-caption:first-child .wp-caption-text {
		margin-right: auto;
	}

	.main-navigation .search-form {
		left: 20px;
		right: auto;
	}

	.site-main .widget-area {
		margin-left: 60px;
		margin-right: auto;
	}
}

@media (max-width: 999px) {
	.sidebar .entry-header,
	.sidebar .entry-content,
	.sidebar .entry-summary,
	.sidebar .entry-meta,
	.sidebar .comment-list,
	.sidebar .comment-reply-title,
	.sidebar .comment-navigation,
	.sidebar .comment-respond .comment-form,
	.sidebar .featured-gallery,
	.sidebar .post-navigation .nav-links,
	.author.sidebar .author-info,
	.sidebar .format-image .entry-content {
		max-width: 604px;
		padding-left: 0;
		padding-right: 0;
	}

	.site-main .widget-area {
		float: none;
		margin-left: auto;
	}

	.attachment .entry-meta {
		float: right;
		text-align: right;
	}

	.sidebar .format-status .entry-content,
	.sidebar .format-status .entry-meta {
		padding-left: 0;
		padding-right: 35px;
	}

	.sidebar .format-status .entry-content:before,
	.sidebar .format-status .entry-meta:before {
		left: auto;
		right: 10px;
	}

	.sidebar .format-status .entry-content p:first-child:before {
		left: auto;
		right: 4px;
	}

	.sidebar .site-footer .widget-area {
		left: auto;
		right: 0;
	}

	.sidebar .paging-navigation .nav-links {
		padding: 0 60px;
	}
}

@media (max-width: 767px) {
	.format-image .entry-content img:first-of-type,
	.format-image .wp-caption:first-child .wp-caption-text {
		margin-right: auto;
	}
}

@media (max-width: 643px) {
	.sidebar .entry-header,
	.sidebar .entry-content,
	.sidebar .entry-summary,
	.sidebar .entry-meta,
	.sidebar .comment-list,
	.sidebar .comment-navigation,
	.sidebar .featured-gallery,
	.sidebar .post-navigation .nav-links,
	.sidebar .format-image .entry-content {
		padding-left: 20px;
		padding-right: 20px;
	}

	#content .format-status .entry-content,
	#content .format-status .entry-met {
		padding-left: 0;
		padding-right: 35px;
	}

	.menu-toggle:after {
		padding-left: 0;
		padding-right: 8px;
	}

	.toggled-on .nav-menu,
	.toggled-on .nav-menu > ul {
		margin-left: auto;
		margin-right: 0;
	}

	.toggled-on .nav-menu li > ul {
		margin-left: auto;
		margin-right: 20px;
		right: auto;
	}

	#content .featured-gallery {
		padding-left: 0;
		padding-right: 24px;
	}

	.gallery-columns-1 .gallery-item {
		margin-left: 0;
		margin-right: auto;
	}

	.comment-author {
		margin-left: 30px;
		margin-right: auto;
	}

	.format-audio .audio-content {
		background: none;
		float: none;
		padding-left: 0;
		padding-right: 0;
	}

	.gallery-columns-3 .gallery-item:nth-of-type(3n) {
		margin-left: 4px;
		margin-right: auto;
	}
}

@media (max-width: 359px) {
	.gallery {
		margin-left: auto;
		margin-right: 0;
	}

	.gallery .gallery-item:nth-of-type(even) {
		margin-left: 0;
		margin-right: auto;
	}

	.gallery .gallery-item,
	.gallery.gallery-columns-3 .gallery-item:nth-of-type(even),
	.gallery-columns-3 .gallery-item:nth-of-type(3n),
	.gallery-columns-5 .gallery-item:nth-of-type(5n),
	.gallery-columns-7 .gallery-item:nth-of-type(7n),
	.gallery-columns-9 .gallery-item:nth-of-type(9n) {
		margin-left: 4px;
		margin-right: auto;
	}

	.comment-author .avatar {
		margin-left: 5px;
		margin-right: auto;
	}
}


/**
 * 9.0 Print
 * ----------------------------------------------------------------------------
 */

@media print {
	.entry-content img.alignleft,
	.entry-content .wp-caption.alignleft {
		margin-left: auto;
		margin-right: 0;
	}

	.entry-content img.alignright,
	.entry-content .wp-caption.alignright {
		margin-left: 0;
		margin-right: auto;
	}
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php
/**
 * Twenty Thirteen functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development
 * and https://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

/*
 * Set up the content width value based on the theme's design.
 *
 * @see twentythirteen_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 604;

/**
 * Add support for a custom header image.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Twenty Thirteen only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6-alpha', '<' ) )
	require get_template_directory() . '/inc/back-compat.php';

/**
 * Twenty Thirteen setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Thirteen supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_setup() {
	/*
	 * Makes Twenty Thirteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Thirteen, use a find and
	 * replace to change 'twentythirteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'twentythirteen', get_template_directory() . '/languages' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', twentythirteen_fonts_url() ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * This theme supports all available post formats by default.
	 * See https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'twentythirteen' ) );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 604, 270, true );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'twentythirteen_setup' );

/**
 * Return the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function twentythirteen_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Source Sans Pro, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'twentythirteen' );

	/* Translators: If there are characters in your language that are not
	 * supported by Bitter, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$bitter = _x( 'on', 'Bitter font: on or off', 'twentythirteen' );

	if ( 'off' !== $source_sans_pro || 'off' !== $bitter ) {
		$font_families = array();

		if ( 'off' !== $source_sans_pro )
			$font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';

		if ( 'off' !== $bitter )
			$font_families[] = 'Bitter:400,700';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_scripts_styles() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds Masonry to handle vertical alignment of footer widgets.
	if ( is_active_sidebar( 'sidebar-1' ) )
		wp_enqueue_script( 'jquery-masonry' );

	// Loads JavaScript file with functionality specific to Twenty Thirteen.
	wp_enqueue_script( 'twentythirteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );

	// Add Source Sans Pro and Bitter fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentythirteen-fonts', twentythirteen_fonts_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.03' );

	// Loads our main stylesheet.
	wp_enqueue_style( 'twentythirteen-style', get_stylesheet_uri(), array(), '2013-07-18' );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentythirteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentythirteen-style' ), '2013-07-18' );
	wp_style_add_data( 'twentythirteen-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'twentythirteen_scripts_styles' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function twentythirteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentythirteen' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentythirteen_wp_title', 10, 2 );

/**
 * Register two widget areas.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Widget Area', 'twentythirteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears in the footer section of the site.', 'twentythirteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Secondary Widget Area', 'twentythirteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears on posts and pages in the sidebar.', 'twentythirteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentythirteen_widgets_init' );

if ( ! function_exists( 'twentythirteen_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'twentythirteen' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentythirteen' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentythirteen' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'twentythirteen_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
*
* @since Twenty Thirteen 1.0
*/
function twentythirteen_post_nav() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'twentythirteen' ); ?></h1>
		<div class="nav-links">

			<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'twentythirteen' ) ); ?>
			<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'twentythirteen' ) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'twentythirteen_entry_meta' ) ) :
/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentythirteen_entry_meta() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . esc_html__( 'Sticky', 'twentythirteen' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		twentythirteen_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentythirteen' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentythirteen' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links">' . $tag_list . '</span>';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentythirteen' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;

if ( ! function_exists( 'twentythirteen_entry_date' ) ) :
/**
 * Print HTML with date information for current post.
 *
 * Create your own twentythirteen_entry_date() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param boolean $echo (optional) Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function twentythirteen_entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'twentythirteen' );
	else
		$format_prefix = '%2$s';

	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'twentythirteen' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}
endif;

if ( ! function_exists( 'twentythirteen_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since Twenty thirteen 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	$attachment_size     = apply_filters( 'twentythirteen_attachment_size', array( 724, 724 ) );
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return string The Link format URL.
 */
function twentythirteen_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

if ( ! function_exists( 'twentythirteen_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ...
 * and a Continue reading link.
 *
 * @since Twenty Thirteen 1.4
 *
 * @param string $more Default Read More excerpt link.
 * @return string Filtered Read More excerpt link.
 */
function twentythirteen_excerpt_more( $more ) {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			sprintf( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'twentythirteen' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
		);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentythirteen_excerpt_more' );
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function twentythirteen_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	return $classes;
}
add_filter( 'body_class', 'twentythirteen_body_class' );

/**
 * Adjust content_width value for video post formats and attachment templates.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_content_width() {
	global $content_width;

	if ( is_attachment() )
		$content_width = 724;
	elseif ( has_post_format( 'audio' ) )
		$content_width = 484;
}
add_action( 'template_redirect', 'twentythirteen_content_width' );

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function twentythirteen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'twentythirteen_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_customize_preview_js() {
	wp_enqueue_script( 'twentythirteen-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20141120', true );
}
add_action( 'customize_preview_init', 'twentythirteen_customize_preview_js' );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 /*
 * Twenty Fourteen Featured Content Slider
 *
 * Adapted from FlexSlider v2.2.0, copyright 2012 WooThemes
 * @link http://www.woothemes.com/flexslider/
 */
/* global DocumentTouch:true,setImmediate:true,featuredSliderDefaults:true,MSGesture:true */
( function( $ ) {
	// FeaturedSlider: object instance.
	$.featuredslider = function( el, options ) {
		var slider = $( el ),
			msGesture = window.navigator && window.navigator.msPointerEnabled && window.MSGesture,
			touch = ( ( 'ontouchstart' in window ) || msGesture || window.DocumentTouch && document instanceof DocumentTouch ), // MSFT specific.
			eventType = 'click touchend MSPointerUp',
			watchedEvent = '',
			watchedEventClearTimer,
			methods = {},
			namespace;

		// Make variables public.
		slider.vars = $.extend( {}, $.featuredslider.defaults, options );

		namespace = slider.vars.namespace,

		// Store a reference to the slider object.
		$.data( el, 'featuredslider', slider );

		// Private slider methods.
		methods = {
			init: function() {
				slider.animating = false;
				slider.currentSlide = 0;
				slider.animatingTo = slider.currentSlide;
				slider.atEnd = ( slider.currentSlide === 0 || slider.currentSlide === slider.last );
				slider.containerSelector = slider.vars.selector.substr( 0, slider.vars.selector.search( ' ' ) );
				slider.slides = $( slider.vars.selector, slider );
				slider.container = $( slider.containerSelector, slider );
				slider.count = slider.slides.length;
				slider.prop = 'marginLeft';
				slider.isRtl = $( 'body' ).hasClass( 'rtl' );
				slider.args = {};
				// TOUCH
				slider.transitions = ( function() {
					var obj = document.createElement( 'div' ),
						props = ['perspectiveProperty', 'WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'],
						i;

					for ( i in props ) {
						if ( obj.style[ props[i] ] !== undefined ) {
							slider.pfx = props[i].replace( 'Perspective', '' ).toLowerCase();
							slider.prop = '-' + slider.pfx + '-transform';
							return true;
						}
					}
					return false;
				}() );
				// CONTROLSCONTAINER
				if ( slider.vars.controlsContainer !== '' ) {
					slider.controlsContainer = $( slider.vars.controlsContainer ).length > 0 && $( slider.vars.controlsContainer );
				}

				slider.doMath();

				// INIT
				slider.setup( 'init' );

				// CONTROLNAV
				methods.controlNav.setup();

				// DIRECTIONNAV
				methods.directionNav.setup();

				// KEYBOARD
				if ( $( slider.containerSelector ).length === 1 ) {
					$( document ).bind( 'keyup', function( event ) {
						var keycode = event.keyCode,
							target = false;
						if ( ! slider.animating && ( keycode === 39 || keycode === 37 ) ) {
							if ( keycode === 39 ) {
								target = slider.getTarget( 'next' );
							} else if ( keycode === 37 ) {
								target = slider.getTarget( 'prev' );
							}

							slider.featureAnimate( target );
						}
					} );
				}

				// TOUCH
				if ( touch ) {
					methods.touch();
				}

				$( window ).bind( 'resize orientationchange focus', methods.resize );

				slider.find( 'img' ).attr( 'draggable', 'false' );
			},

			controlNav: {
				setup: function() {
					methods.controlNav.setupPaging();
				},
				setupPaging: function() {
					var type = 'control-paging',
						j = 1,
						item,
						slide,
						i;

					slider.controlNavScaffold = $( '<ol class="' + namespace + 'control-nav ' + namespace + type + '"></ol>' );

					if ( slider.pagingCount > 1 ) {
						for ( i = 0; i < slider.pagingCount; i++ ) {
							slide = slider.slides.eq( i );
							item = '<a>' + j + '</a>';
							slider.controlNavScaffold.append( '<li>' + item + '</li>' );
							j++;
						}
					}

					// CONTROLSCONTAINER
					( slider.controlsContainer ) ? $( slider.controlsContainer ).append( slider.controlNavScaffold ) : slider.append( slider.controlNavScaffold );
					methods.controlNav.set();

					methods.controlNav.active();

					slider.controlNavScaffold.delegate( 'a, img', eventType, function( event ) {
						event.preventDefault();

						if ( watchedEvent === '' || watchedEvent === event.type ) {
							var $this = $( this ),
								target = slider.controlNav.index( $this );

							if ( ! $this.hasClass( namespace + 'active' ) ) {
								slider.direction = ( target > slider.currentSlide ) ? 'next' : 'prev';
								slider.featureAnimate( target );
							}
						}

						// Set up flags to prevent event duplication.
						if ( watchedEvent === '' ) {
							watchedEvent = event.type;
						}

						methods.setToClearWatchedEvent();
					} );
				},
				set: function() {
					var selector = 'a';
					slider.controlNav = $( '.' + namespace + 'control-nav li ' + selector, ( slider.controlsContainer ) ? slider.controlsContainer : slider );
				},
				active: function() {
					slider.controlNav.removeClass( namespace + 'active' ).eq( slider.animatingTo ).addClass( namespace + 'active' );
				},
				update: function( action, pos ) {
					if ( slider.pagingCount > 1 && action === 'add' ) {
						slider.controlNavScaffold.append( $( '<li><a>' + slider.count + '</a></li>' ) );
					} else if ( slider.pagingCount === 1 ) {
						slider.controlNavScaffold.find( 'li' ).remove();
					} else {
						slider.controlNav.eq( pos ).closest( 'li' ).remove();
					}
					methods.controlNav.set();
					( slider.pagingCount > 1 && slider.pagingCount !== slider.controlNav.length ) ? slider.update( pos, action ) : methods.controlNav.active();
				}
			},

			directionNav: {
				setup: function() {
					var directionNavScaffold = $( '<ul class="' + namespace + 'direction-nav"><li><a class="' + namespace + 'prev" href="#">' + slider.vars.prevText + '</a></li><li><a class="' + namespace + 'next" href="#">' + slider.vars.nextText + '</a></li></ul>' );

					// CONTROLSCONTAINER
					if ( slider.controlsContainer ) {
						$( slider.controlsContainer ).append( directionNavScaffold );
						slider.directionNav = $( '.' + namespace + 'direction-nav li a', slider.controlsContainer );
					} else {
						slider.append( directionNavScaffold );
						slider.directionNav = $( '.' + namespace + 'direction-nav li a', slider );
					}

					methods.directionNav.update();

					slider.directionNav.bind( eventType, function( event ) {
						event.preventDefault();
						var target;

						if ( watchedEvent === '' || watchedEvent === event.type ) {
							target = ( $( this ).hasClass( namespace + 'next' ) ) ? slider.getTarget( 'next' ) : slider.getTarget( 'prev' );
							slider.featureAnimate( target );
						}

						// Set up flags to prevent event duplication.
						if ( watchedEvent === '' ) {
							watchedEvent = event.type;
						}

						methods.setToClearWatchedEvent();
					} );
				},
				update: function() {
					var disabledClass = namespace + 'disabled';
					if ( slider.pagingCount === 1 ) {
						slider.directionNav.addClass( disabledClass ).attr( 'tabindex', '-1' );
					} else {
						slider.directionNav.removeClass( disabledClass ).removeAttr( 'tabindex' );
					}
				}
			},

			touch: function() {
				var startX,
					startY,
					offset,
					cwidth,
					dx,
					startT,
					scrolling = false,
					localX = 0,
					localY = 0,
					accDx = 0;

				if ( ! msGesture ) {
					el.addEventListener( 'touchstart', onTouchStart, false );
				} else {
					el.style.msTouchAction = 'none';
					el._gesture = new MSGesture(); // MSFT specific.
					el._gesture.target = el;
					el.addEventListener( 'MSPointerDown', onMSPointerDown, false );
					el._slider = slider;
					el.addEventListener( 'MSGestureChange', onMSGestureChange, false );
					el.addEventListener( 'MSGestureEnd', onMSGestureEnd, false );
				}

				function onTouchStart( e ) {
					if ( slider.animating ) {
						e.preventDefault();
					} else if ( ( window.navigator.msPointerEnabled ) || e.touches.length === 1 ) {
						cwidth = slider.w;
						startT = Number( new Date() );

						// Local vars for X and Y points.
						localX = e.touches[0].pageX;
						localY = e.touches[0].pageY;

						offset = ( slider.currentSlide + slider.cloneOffset ) * cwidth;
						if ( slider.animatingTo === slider.last && slider.direction !== 'next' ) {
							offset = 0;
						}

						startX = localX;
						startY = localY;

						el.addEventListener( 'touchmove', onTouchMove, false );
						el.addEventListener( 'touchend', onTouchEnd, false );
					}
				}

				function onTouchMove( e ) {
					// Local vars for X and Y points.
					localX = e.touches[0].pageX;
					localY = e.touches[0].pageY;

					dx = startX - localX;
					scrolling = Math.abs( dx ) < Math.abs( localY - startY );

					if ( ! scrolling ) {
						e.preventDefault();
						if ( slider.transitions ) {
							slider.setProps( offset + dx, 'setTouch' );
						}
					}
				}

				function onTouchEnd() {
					// Finish the touch by undoing the touch session.
					el.removeEventListener( 'touchmove', onTouchMove, false );

					if ( slider.animatingTo === slider.currentSlide && ! scrolling && dx !== null ) {
						var updateDx = dx,
							target = ( updateDx > 0 ) ? slider.getTarget( 'next' ) : slider.getTarget( 'prev' );

						slider.featureAnimate( target );
					}
					el.removeEventListener( 'touchend', onTouchEnd, false );

					startX = null;
					startY = null;
					dx = null;
					offset = null;
				}

				function onMSPointerDown( e ) {
					e.stopPropagation();
					if ( slider.animating ) {
						e.preventDefault();
					} else {
						el._gesture.addPointer( e.pointerId );
						accDx = 0;
						cwidth = slider.w;
						startT = Number( new Date() );
						offset = ( slider.currentSlide + slider.cloneOffset ) * cwidth;
						if ( slider.animatingTo === slider.last && slider.direction !== 'next' ) {
							offset = 0;
						}
					}
				}

				function onMSGestureChange( e ) {
					e.stopPropagation();
					var slider = e.target._slider,
						transX,
						transY;
					if ( ! slider ) {
						return;
					}

					transX = -e.translationX,
					transY = -e.translationY;

					// Accumulate translations.
					accDx = accDx + transX;
					dx = accDx;
					scrolling = Math.abs( accDx ) < Math.abs( -transY );

					if ( e.detail === e.MSGESTURE_FLAG_INERTIA ) {
						setImmediate( function () { // MSFT specific.
							el._gesture.stop();
						} );

						return;
					}

					if ( ! scrolling || Number( new Date() ) - startT > 500 ) {
						e.preventDefault();
						if ( slider.transitions ) {
							slider.setProps( offset + dx, 'setTouch' );
						}
					}
				}

				function onMSGestureEnd( e ) {
					e.stopPropagation();
					var slider = e.target._slider,
						updateDx,
						target;
					if ( ! slider ) {
						return;
					}

					if ( slider.animatingTo === slider.currentSlide && ! scrolling && dx !== null ) {
						updateDx = dx,
						target = ( updateDx > 0 ) ? slider.getTarget( 'next' ) : slider.getTarget( 'prev' );

						slider.featureAnimate( target );
					}

					startX = null;
					startY = null;
					dx = null;
					offset = null;
					accDx = 0;
				}
			},

			resize: function() {
				if ( ! slider.animating && slider.is( ':visible' ) ) {
					slider.doMath();

					// SMOOTH HEIGHT
					methods.smoothHeight();
					slider.newSlides.width( slider.computedW );
					slider.setProps( slider.computedW, 'setTotal' );
				}
			},

			smoothHeight: function( dur ) {
				var $obj = slider.viewport;
				( dur ) ? $obj.animate( { 'height': slider.slides.eq( slider.animatingTo ).height() }, dur ) : $obj.height( slider.slides.eq( slider.animatingTo ).height() );
			},

			setToClearWatchedEvent: function() {
				clearTimeout( watchedEventClearTimer );
				watchedEventClearTimer = setTimeout( function() {
					watchedEvent = '';
				}, 3000 );
			}
		};

		// Public methods.
		slider.featureAnimate = function( target ) {
			if ( target !== slider.currentSlide ) {
				slider.direction = ( target > slider.currentSlide ) ? 'next' : 'prev';
			}

			if ( ! slider.animating && slider.is( ':visible' ) ) {
				slider.animating = true;
				slider.animatingTo = target;

				// CONTROLNAV
				methods.controlNav.active();

				slider.slides.removeClass( namespace + 'active-slide' ).eq( target ).addClass( namespace + 'active-slide' );

				slider.atEnd = target === 0 || target === slider.last;

				// DIRECTIONNAV
				methods.directionNav.update();

				var dimension = slider.computedW,
					slideString;

				if ( slider.currentSlide === 0 && target === slider.count - 1 && slider.direction !== 'next' ) {
					slideString = 0;
				} else if ( slider.currentSlide === slider.last && target === 0 && slider.direction !== 'prev' ) {
					slideString = ( slider.count + 1 ) * dimension;
				} else {
					slideString = ( target + slider.cloneOffset ) * dimension;
				}
				slider.setProps( slideString, '', slider.vars.animationSpeed );
				if ( slider.transitions ) {
					if ( ! slider.atEnd ) {
						slider.animating = false;
						slider.currentSlide = slider.animatingTo;
					}
					slider.container.unbind( 'webkitTransitionEnd transitionend' );
					slider.container.bind( 'webkitTransitionEnd transitionend', function() {
						slider.wrapup( dimension );
					} );
				} else {
					slider.container.animate( slider.args, slider.vars.animationSpeed, 'swing', function() {
						slider.wrapup( dimension );
					} );
				}

				// SMOOTH HEIGHT
				methods.smoothHeight( slider.vars.animationSpeed );
			}
		};

		slider.wrapup = function( dimension ) {
			if ( slider.currentSlide === 0 && slider.animatingTo === slider.last ) {
				slider.setProps( dimension, 'jumpEnd' );
			} else if ( slider.currentSlide === slider.last && slider.animatingTo === 0 ) {
				slider.setProps( dimension, 'jumpStart' );
			}
			slider.animating = false;
			slider.currentSlide = slider.animatingTo;
		};

		slider.getTarget = function( dir ) {
			slider.direction = dir;

			// Swap for RTL.
			if ( slider.isRtl ) {
				dir = 'next' === dir ? 'prev' : 'next';
			}

			if ( dir === 'next' ) {
				return ( slider.currentSlide === slider.last ) ? 0 : slider.currentSlide + 1;
			} else {
				return ( slider.currentSlide === 0 ) ? slider.last : slider.currentSlide - 1;
			}
		};

		slider.setProps = function( pos, special, dur ) {
			var target = ( function() {
				var posCalc = ( function() {
						switch ( special ) {
							case 'setTotal': return ( slider.currentSlide + slider.cloneOffset ) * pos;
							case 'setTouch': return pos;
							case 'jumpEnd': return slider.count * pos;
							case 'jumpStart': return pos;
							default: return pos;
						}
					}() );

					return ( posCalc * -1 ) + 'px';
				}() );

			if ( slider.transitions ) {
				target = 'translate3d(' + target + ',0,0 )';
				dur = ( dur !== undefined ) ? ( dur / 1000 ) + 's' : '0s';
				slider.container.css( '-' + slider.pfx + '-transition-duration', dur );
			}

			slider.args[slider.prop] = target;
			if ( slider.transitions || dur === undefined ) {
				slider.container.css( slider.args );
			}
		};

		slider.setup = function( type ) {
			var sliderOffset;

			if ( type === 'init' ) {
				slider.viewport = $( '<div class="' + namespace + 'viewport"></div>' ).css( { 'overflow': 'hidden', 'position': 'relative' } ).appendTo( slider ).append( slider.container );
				slider.cloneCount = 0;
				slider.cloneOffset = 0;
			}
			slider.cloneCount = 2;
			slider.cloneOffset = 1;
			// Clear out old clones.
			if ( type !== 'init' ) {
				slider.container.find( '.clone' ).remove();
			}

			slider.container.append( slider.slides.first().clone().addClass( 'clone' ).attr( 'aria-hidden', 'true' ) ).prepend( slider.slides.last().clone().addClass( 'clone' ).attr( 'aria-hidden', 'true' ) );
			slider.newSlides = $( slider.vars.selector, slider );

			sliderOffset = slider.currentSlide + slider.cloneOffset;
			slider.container.width( ( slider.count + slider.cloneCount ) * 200 + '%' );
			slider.setProps( sliderOffset * slider.computedW, 'init' );
			setTimeout( function() {
				slider.doMath();
				slider.newSlides.css( { 'width': slider.computedW, 'float': 'left', 'display': 'block' } );
				// SMOOTH HEIGHT
				methods.smoothHeight();
			}, ( type === 'init' ) ? 100 : 0 );

			slider.slides.removeClass( namespace + 'active-slide' ).eq( slider.currentSlide ).addClass( namespace + 'active-slide' );
		};

		slider.doMath = function() {
			var slide = slider.slides.first();

			slider.w = ( slider.viewport === undefined ) ? slider.width() : slider.viewport.width();
			slider.h = slide.height();
			slider.boxPadding = slide.outerWidth() - slide.width();

			slider.itemW = slider.w;
			slider.pagingCount = slider.count;
			slider.last = slider.count - 1;
			slider.computedW = slider.itemW - slider.boxPadding;
		};

		slider.update = function( pos, action ) {
			slider.doMath();

			// Update currentSlide and slider.animatingTo if necessary.
			if ( pos < slider.currentSlide ) {
				slider.currentSlide += 1;
			} else if ( pos <= slider.currentSlide && pos !== 0 ) {
				slider.currentSlide -= 1;
			}
			slider.animatingTo = slider.currentSlide;

			// Update controlNav.
			if ( action === 'add' || slider.pagingCount > slider.controlNav.length ) {
				methods.controlNav.update( 'add' );
			} else if ( action === 'remove' || slider.pagingCount < slider.controlNav.length ) {
				if ( slider.currentSlide > slider.last ) {
					slider.currentSlide -= 1;
					slider.animatingTo -= 1;
				}
				methods.controlNav.update( 'remove', slider.last );
			}
			// Update directionNav.
			methods.directionNav.update();
		};

		// FeaturedSlider: initialize.
		methods.init();
	};

	// Default settings.
	$.featuredslider.defaults = {
		namespace: 'slider-',     // String: prefix string attached to the class of every element generated by the plugin.
		selector: '.slides > li', // String: selector, must match a simple pattern.
		animationSpeed: 600,      // Integer: Set the speed of animations, in milliseconds.
		controlsContainer: '',    // jQuery Object/Selector: container navigation to append elements.

		// Text labels.
		prevText: featuredSliderDefaults.prevText, // String: Set the text for the "previous" directionNav item.
		nextText: featuredSliderDefaults.nextText  // String: Set the text for the "next" directionNav item.
	};

	// FeaturedSlider: plugin function.
	$.fn.featuredslider = function( options ) {
		if ( options === undefined ) {
			options = {};
		}

		if ( typeof options === 'object' ) {
			return this.each( function() {
				var $this = $( this ),
					selector = ( options.selector ) ? options.selector : '.slides > li',
					$slides = $this.find( selector );

			if ( $slides.length === 1 || $slides.length === 0 ) {
					$slides.fadeIn( 400 );
				} else if ( $this.data( 'featuredslider' ) === undefined ) {
					new $.featuredslider( this, options );
				}
			} );
		}
	};
} )( jQuery );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php
/**
 * Twenty Fourteen Featured Content
 *
 * This module allows you to define a subset of posts to be displayed
 * in the theme's Featured Content area.
 *
 * For maximum compatibility with different methods of posting users
 * will designate a featured post tag to associate posts with. Since
 * this tag now has special meaning beyond that of a normal tags, users
 * will have the ability to hide it from the front-end of their site.
 */
class Featured_Content {

	/**
	 * The maximum number of posts a Featured Content area can contain.
	 *
	 * We define a default value here but themes can override
	 * this by defining a "max_posts" entry in the second parameter
	 * passed in the call to add_theme_support( 'featured-content' ).
	 *
	 * @see Featured_Content::init()
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @static
	 * @access public
	 * @var int
	 */
	public static $max_posts = 15;

	/**
	 * Instantiate.
	 *
	 * All custom functionality will be hooked into the "init" action.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 */
	public static function setup() {
		add_action( 'init', array( __CLASS__, 'init' ), 30 );
	}

	/**
	 * Conditionally hook into WordPress.
	 *
	 * Theme must declare that they support this module by adding
	 * add_theme_support( 'featured-content' ); during after_setup_theme.
	 *
	 * If no theme support is found there is no need to hook into WordPress.
	 * We'll just return early instead.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 */
	public static function init() {
		$theme_support = get_theme_support( 'featured-content' );

		// Return early if theme does not support Featured Content.
		if ( ! $theme_support ) {
			return;
		}

		/*
		 * An array of named arguments must be passed as the second parameter
		 * of add_theme_support().
		 */
		if ( ! isset( $theme_support[0] ) ) {
			return;
		}

		// Return early if "featured_content_filter" has not been defined.
		if ( ! isset( $theme_support[0]['featured_content_filter'] ) ) {
			return;
		}

		$filter = $theme_support[0]['featured_content_filter'];

		// Theme can override the number of max posts.
		if ( isset( $theme_support[0]['max_posts'] ) ) {
			self::$max_posts = absint( $theme_support[0]['max_posts'] );
		}

		add_filter( $filter,                              array( __CLASS__, 'get_featured_posts' )    );
		add_action( 'customize_register',                 array( __CLASS__, 'customize_register' ), 9 );
		add_action( 'admin_init',                         array( __CLASS__, 'register_setting'   )    );
		add_action( 'switch_theme',                       array( __CLASS__, 'delete_transient'   )    );
		add_action( 'save_post',                          array( __CLASS__, 'delete_transient'   )    );
		add_action( 'delete_post_tag',                    array( __CLASS__, 'delete_post_tag'    )    );
		add_action( 'customize_controls_enqueue_scripts', array( __CLASS__, 'enqueue_scripts'    )    );
		add_action( 'pre_get_posts',                      array( __CLASS__, 'pre_get_posts'      )    );
		add_action( 'wp_loaded',                          array( __CLASS__, 'wp_loaded'          )    );
	}

	/**
	 * Hide "featured" tag from the front-end.
	 *
	 * Has to run on wp_loaded so that the preview filters of the Customizer
	 * have a chance to alter the value.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 */
	public static function wp_loaded() {
		if ( self::get_setting( 'hide-tag' ) ) {
			add_filter( 'get_terms',     array( __CLASS__, 'hide_featured_term'     ), 10, 3 );
			add_filter( 'get_the_terms', array( __CLASS__, 'hide_the_featured_term' ), 10, 3 );
		}
	}

	/**
	 * Get featured posts.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @return array Array of featured posts.
	 */
	public static function get_featured_posts() {
		$post_ids = self::get_featured_post_ids();

		// No need to query if there is are no featured posts.
		if ( empty( $post_ids ) ) {
			return array();
		}

		$featured_posts = get_posts( array(
			'include'        => $post_ids,
			'posts_per_page' => count( $post_ids ),
		) );

		return $featured_posts;
	}

	/**
	 * Get featured post IDs
	 *
	 * This function will return the an array containing the
	 * post IDs of all featured posts.
	 *
	 * Sets the "featured_content_ids" transient.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @return array Array of post IDs.
	 */
	public static function get_featured_post_ids() {
		// Get array of cached results if they exist.
		$featured_ids = get_transient( 'featured_content_ids' );

		if ( false === $featured_ids ) {
			$settings = self::get_setting();
			$term     = get_term_by( 'name', $settings['tag-name'], 'post_tag' );

			if ( $term ) {
				// Query for featured posts.
				$featured_ids = get_posts( array(
					'fields'           => 'ids',
					'numberposts'      => self::$max_posts,
					'suppress_filters' => false,
					'tax_query'        => array(
						array(
							'field'    => 'term_id',
							'taxonomy' => 'post_tag',
							'terms'    => $term->term_id,
						),
					),
				) );
			}

			// Get sticky posts if no Featured Content exists.
			if ( ! $featured_ids ) {
				$featured_ids = self::get_sticky_posts();
			}

			set_transient( 'featured_content_ids', $featured_ids );
		}

		// Ensure correct format before return.
		return array_map( 'absint', $featured_ids );
	}

	/**
	 * Return an array with IDs of posts maked as sticky.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @return array Array of sticky posts.
	 */
	public static function get_sticky_posts() {
		return array_slice( get_option( 'sticky_posts', array() ), 0, self::$max_posts );
	}

	/**
	 * Delete featured content ids transient.
	 *
	 * Hooks in the "save_post" action.
	 *
	 * @see Featured_Content::validate_settings().
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 */
	public static function delete_transient() {
		delete_transient( 'featured_content_ids' );
	}

	/**
	 * Exclude featured posts from the home page blog query.
	 *
	 * Filter the home page posts, and remove any featured post ID's from it.
	 * Hooked onto the 'pre_get_posts' action, this changes the parameters of
	 * the query before it gets any posts.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @param WP_Query $query WP_Query object.
	 * @return WP_Query Possibly-modified WP_Query.
	 */
	public static function pre_get_posts( $query ) {

		// Bail if not home or not main query.
		if ( ! $query->is_home() || ! $query->is_main_query() ) {
			return;
		}

		// Bail if the blog page is not the front page.
		if ( 'posts' !== get_option( 'show_on_front' ) ) {
			return;
		}

		$featured = self::get_featured_post_ids();

		// Bail if no featured posts.
		if ( ! $featured ) {
			return;
		}

		// We need to respect post ids already in the blacklist.
		$post__not_in = $query->get( 'post__not_in' );

		if ( ! empty( $post__not_in ) ) {
			$featured = array_merge( (array) $post__not_in, $featured );
			$featured = array_unique( $featured );
		}

		$query->set( 'post__not_in', $featured );
	}

	/**
	 * Reset tag option when the saved tag is deleted.
	 *
	 * It's important to mention that the transient needs to be deleted,
	 * too. While it may not be obvious by looking at the function alone,
	 * the transient is deleted by Featured_Content::validate_settings().
	 *
	 * Hooks in the "delete_post_tag" action.
	 *
	 * @see Featured_Content::validate_settings().
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @param int $tag_id The term_id of the tag that has been deleted.
	 */
	public static function delete_post_tag( $tag_id ) {
		$settings = self::get_setting();

		if ( empty( $settings['tag-id'] ) || $tag_id != $settings['tag-id'] ) {
			return;
		}

		$settings['tag-id'] = 0;
		$settings = self::validate_settings( $settings );
		update_option( 'featured-content', $settings );
	}

	/**
	 * Hide featured tag from displaying when global terms are queried from the front-end.
	 *
	 * Hooks into the "get_terms" filter.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $terms      List of term objects. This is the return value of get_terms().
	 * @param array $taxonomies An array of taxonomy slugs.
	 * @return array A filtered array of terms.
	 *
	 * @uses Featured_Content::get_setting()
	 */
	public static function hide_featured_term( $terms, $taxonomies, $args ) {

		// This filter is only appropriate on the front-end.
		if ( is_admin() ) {
			return $terms;
		}

		// We only want to hide the featured tag.
		if ( ! in_array( 'post_tag', $taxonomies ) ) {
			return $terms;
		}

		// Bail if no terms were returned.
		if ( empty( $terms ) ) {
			return $terms;
		}

		// Bail if term objects are unavailable.
		if ( 'all' != $args['fields'] ) {
			return $terms;
		}

		$settings = self::get_setting();
		foreach ( $terms as $order => $term ) {
			if ( ( $settings['tag-id'] === $term->term_id || $settings['tag-name'] === $term->name ) && 'post_tag' === $term->taxonomy ) {
				unset( $terms[ $order ] );
			}
		}

		return $terms;
	}

	/**
	 * Hide featured tag from display when terms associated with a post object
	 * are queried from the front-end.
	 *
	 * Hooks into the "get_the_terms" filter.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $terms    A list of term objects. This is the return value of get_the_terms().
	 * @param int   $id       The ID field for the post object that terms are associated with.
	 * @param array $taxonomy An array of taxonomy slugs.
	 * @return array Filtered array of terms.
	 *
	 * @uses Featured_Content::get_setting()
	 */
	public static function hide_the_featured_term( $terms, $id, $taxonomy ) {

		// This filter is only appropriate on the front-end.
		if ( is_admin() ) {
			return $terms;
		}

		// Make sure we are in the correct taxonomy.
		if ( 'post_tag' != $taxonomy ) {
			return $terms;
		}

		// No terms? Return early!
		if ( empty( $terms ) ) {
			return $terms;
		}

		$settings = self::get_setting();
		foreach ( $terms as $order => $term ) {
			if ( ( $settings['tag-id'] === $term->term_id || $settings['tag-name'] === $term->name ) && 'post_tag' === $term->taxonomy ) {
				unset( $terms[ $term->term_id ] );
			}
		}

		return $terms;
	}

	/**
	 * Register custom setting on the Settings -> Reading screen.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 */
	public static function register_setting() {
		register_setting( 'featured-content', 'featured-content', array( __CLASS__, 'validate_settings' ) );
	}

	/**
	 * Add settings to the Customizer.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	public static function customize_register( $wp_customize ) {
		$wp_customize->add_section( 'featured_content', array(
			'title'          => __( 'Featured Content', 'twentyfourteen' ),
			'description'    => sprintf( __( 'Use a <a href="%1$s">tag</a> to feature your posts. If no posts match the tag, <a href="%2$s">sticky posts</a> will be displayed instead.', 'twentyfourteen' ),
				esc_url( add_query_arg( 'tag', _x( 'featured', 'featured content default tag slug', 'twentyfourteen' ), admin_url( 'edit.php' ) ) ),
				admin_url( 'edit.php?show_sticky=1' )
			),
			'priority'       => 130,
			'theme_supports' => 'featured-content',
		) );

		// Add Featured Content settings.
		$wp_customize->add_setting( 'featured-content[tag-name]', array(
			'default'              => _x( 'featured', 'featured content default tag slug', 'twentyfourteen' ),
			'type'                 => 'option',
			'sanitize_js_callback' => array( __CLASS__, 'delete_transient' ),
		) );
		$wp_customize->add_setting( 'featured-content[hide-tag]', array(
			'default'              => true,
			'type'                 => 'option',
			'sanitize_js_callback' => array( __CLASS__, 'delete_transient' ),
		) );

		// Add Featured Content controls.
		$wp_customize->add_control( 'featured-content[tag-name]', array(
			'label'    => __( 'Tag Name', 'twentyfourteen' ),
			'section'  => 'featured_content',
			'priority' => 20,
		) );
		$wp_customize->add_control( 'featured-content[hide-tag]', array(
			'label'    => __( 'Don&rsquo;t display tag on front end.', 'twentyfourteen' ),
			'section'  => 'featured_content',
			'type'     => 'checkbox',
			'priority' => 30,
		) );
	}

	/**
	 * Enqueue the tag suggestion script.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( 'featured-content-suggest', get_template_directory_uri() . '/js/featured-content-admin.js', array( 'jquery', 'suggest' ), '20131022', true );
	}

	/**
	 * Get featured content settings.
	 *
	 * Get all settings recognized by this module. This function
	 * will return all settings whether or not they have been stored
	 * in the database yet. This ensures that all keys are available
	 * at all times.
	 *
	 * In the event that you only require one setting, you may pass
	 * its name as the first parameter to the function and only that
	 * value will be returned.
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @param string $key The key of a recognized setting.
	 * @return mixed Array of all settings by default. A single value if passed as first parameter.
	 */
	public static function get_setting( $key = 'all' ) {
		$saved = (array) get_option( 'featured-content' );

		$defaults = array(
			'hide-tag' => 1,
			'tag-id'   => 0,
			'tag-name' => _x( 'featured', 'featured content default tag slug', 'twentyfourteen' ),
		);

		$options = wp_parse_args( $saved, $defaults );
		$options = array_intersect_key( $options, $defaults );

		if ( 'all' != $key ) {
			return isset( $options[ $key ] ) ? $options[ $key ] : false;
		}

		return $options;
	}

	/**
	 * Validate featured content settings.
	 *
	 * Make sure that all user supplied content is in an expected
	 * format before saving to the database. This function will also
	 * delete the transient set in Featured_Content::get_featured_content().
	 *
	 * @static
	 * @access public
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $input Array of settings input.
	 * @return array Validated settings output.
	 */
	public static function validate_settings( $input ) {
		$output = array();

		if ( empty( $input['tag-name'] ) ) {
			$output['tag-id'] = 0;
		} else {
			$term = get_term_by( 'name', $input['tag-name'], 'post_tag' );

			if ( $term ) {
				$output['tag-id'] = $term->term_id;
			} else {
				$new_tag = wp_create_tag( $input['tag-name'] );

				if ( ! is_wp_error( $new_tag ) && isset( $new_tag['term_id'] ) ) {
					$output['tag-id'] = $new_tag['term_id'];
				}
			}

			$output['tag-name'] = $input['tag-name'];
		}

		$output['hide-tag'] = isset( $input['hide-tag'] ) && $input['hide-tag'] ? 1 : 0;

		// Delete the featured post ids transient.
		self::delete_transient();

		return $output;
	}
} // Featured_Content

Featured_Content::setup();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             wOFF     CH     j�                       FFTM  �      j(�GDEF  �        � OS/2  �   B   `�[h�cmap      �  ��i�ccvt   �      b�fpgm  �  �  eS�/�gasp  \         glyf  d  9~  Z@�v#head  =�   +   6Q?hhea  >      $�hmtx  >,   �  
U�@{loca  >�    ��j�maxp  ?�        �Bname  ?�  b  �B�^post  A<  �  
�$�prep  C   .   .��+webf  C@      �@R�       �=��    ����    ����x�c`d``�b	`b`�z f� 	� �   x�c`f��8�����՘��L�t���Ja���~��2���Kc`�``� 
3")Q`` ���  x�c```f�`FX�1��,���00��������'��_8�H~��b���K��/%_����`���I���g�/_�(|1���%�K�����b�g�����c���1���Q������a箁ڎ0�1��12	&t�  �\*3  	 E A f  �  x�]Q�N[A���� 9�����{�	�Սbd;��i7r��q@�Dگ���H�!H|B>!3k��4;;�sΙ3Kʑ�w�k�S�$����6�NH�� ���덌��Zlf��u���є;j �=o)M;�Z�����
����;�4���:	�!�qK��ͺ�����b00����.?�R��4�j˰��Ѽ�3��4@Skm���!��qK�˦�6����$���tUS�����]���`�*́��Vy&ҷ$�,�b���
9�����@�HƼIJ;ㆵƑ���6O��<�Mmo��Y�w�K:�Ȇ�b;b)�	DBFU��Ͻ,�R��@��������D<��u1Vz~���ˊ�V�΋Bwo�j��)��^ξ���Ac�����J��<,�4hCz7z���ꈫ�>�'ӿ�Z     �� xڭ||Tչ�Z{�y&��=�d���{B&�	��d �!*(��/j�Z���=��U+h[��c�L@�bk��Z[G������Zm=����������gBl���n`f�������=��c-�!�G��=Cx�%RK̄L�b����5�e�]"�����^��	���	������t�ur�.���i�J��M$D��T#�w�H|���ȕD&	��d!![R��$�IY���)�P�I�6!ו�\���E��M&���T�	��p^����u����dW�`�&�E���-�X���H�D��;���;e��i�IeI e��N�5G$��{)�#�(���?���٘��%��~�a0'��r�� Bt����
b�hF>�ʑ��WT�#p�1�X�!�`�p�/D��dg:����>JW��_�D�O�����w�O�o|��!l݇$�D%���u]�O�g����r#3!�a#���䚒���XokKr��P���d��`�3m��~�R�Z�s9�^����Q�3�[�����胱h���b���`L6��M#"�mv�J�L�ĕ��9v�/"���O��y����V9��(�HG���Q�oG��#�OiT|V�߆�cO�>zt�-�<���~<_M�q���2�!���U���%!���Jȼ����ڤ\c��ƀX8k�i啂�V��(p�G�R}W�zkT��&-�"\
�0��S	����	�M�+�/Miؠ!0)��T0�D���rp�v:Q#���NҞ�wo=M�ޭ���<��Ϸ�� q��<�x~IK��8�J"k��f��I�F�Z ����QC����G�1jA��B5@SI6YdrP�a»�ʥ��N�b�3�?s
�#)Ǐ��'@4����qJ��;%Ҍѥ���>��sK�^��4럾T��a� ��[
n�V�T���1>�B4:J�:6�Ae���F��cc4�� u�z�kc}.W���ָ<1��B�����u�%VMd2J�tg֡u�(�1�x)\ ��8�e�N$�O�0Cdh0�������f�|=�čPi���}�%��`˖TZ��/��"cs���J��Q�G@ց�Ȉif%A�FdB��p���kSL�r�	��B� X��`$�F)��8�b0�Y?Jm��X%�U�R�J^Zu�G�����ER����&�+y:r@Y%��q*�i!��U9�@}dfE�S���$�����i*0f��a��V�Z�)|8d��`v8�*� ���t4��Q\=4�갛���e���{��29sQ߅��Xvy|`O��>�W����Sf̍ο�󢾣�<ڷb�7/}��h���փF!��"_��/)ɭ�A�|%��Β�i)4R���z�}'���?oA�d������`�eL�_rp��2��*�k�6iiK�g^��XB�$����P*)��B4�H&ݙIG{|�"�85q�HT���f�uiv/���L4N�q!�JM���
.��3kG5�G�����1��ZU�S~
����F��&ԥ�/�^�g����zF㤎��A麹�ڦ�˖�8�'��j��7x܁:��Y�C�S�i ��B�N ��_�/T�Oe�1�P��:]s���'�g=�푭�Y�N��`��s�?<Q�r�=7l߿���Y>}�:��
u��zNW��ؚ��������]��V��i�qN�q:|csc&���'�>��� �:Bbr��ڣ�$w���7���p!�Wo��"��A�u��:���ji�L���>�F���L��.����,��&�d<���rh��)L���jL7o��:I6��Ѭ3��ZB��d������H(�R/��\\��ټ>c͆��3E���[�R>Tv+k��ah���w߳�w���=G�����?S�Wt�����V��ok�=0������O�����	:�z������bӤ��^�W��'���o��r�*�^[���:��d�W������%��OY@��-s�c���)�q�|=��/�c)o������3��tHy��~���m��G�L~$�8�7��a�)_8ԙJ�a
�h�f�Y��B�N%]�Y�_Ȳ&^���J~v�3��7����K�.K�{�1�������\�v�&ڳ��w�_Ky��|��{�����[41>��%W,~�5�������[���7/������/�_�}#��t���HP�0oIYLȾ��,�n�uZ w�@0
0�`\)� K�
�y\��dw�����T���SP����`��yh�C "�Ґ�}���w��Vy7���v��K�Y֦(嶡}�����νH����,�?��%;/��2Z�!��O$�{��2��S��d�P4n�P�^��n���$v]L]����a��6�'`+6<%�{�T��Ly�P��јs�o�k��*;�v��mn��6)/(#�K4A��{6�<����azU96z��M@�W�kܱ��Z���u	yiInN�i�d��8��E*�׍��n����R�{������RX� �W��Qp(�6��3�m^��L�b��}+/^�e�Q<���GE,/�Z:Pj�k'H��VRA��Z[*�9�v:�:���xж�.���jc�I��5 �P��l���
Z8X'���Υ�܏���H9�|M9�Q�TI�q��txc0ఙ�miD�a��l��0�!�ب��H��9��9�Ƣ���y~�$�&��_U�nٱ�f����z��5S�9��8�?)�����I��9�k���8�.�q5b����A�ô���J|y& x��5�&�h7����?�u\������o�:��z��16�3�rry��KrKJ�]B�9��l/��q��{a��תs��:j`�ewR��N!|4�ܸ�W�0o��g- 捚�Xo����l�g�\Il&��o�s]���t������+/c��&�䠙���6�3��z�_�~�
�Qj�̂y���,;Me�-�C]`O5.�U�`[�����kH7�����k�m;r�*�जf� ���\|74�lQ����h����Gn������9��P�ǣ���%�Msܽ�9Ү^������O����*h��FP���Q��&��Om�ܵMb���aLϜ|ב�G:�^v�K�S�P7�ab`b�'l�_!t>�kA��7��/�����j����yC�0|pLi��	�R��)ԙ��� ����E�!���=��2�s2 ��:l�7���lM%#�y�e��e�^j� �]��[���Xg�s���ͮ�N�4�Og��ݾ(ԛJ�}���?�8���f��Z}�������	��ə�V�(�'׵��5���`�x��m�=�?/>�O� {f�L��@�t0g��;!ʝ)���Ev�������0'���ٯrkK�MUC9 ���L���. N�*grrL,tt"�|p�ВQ]��&��璋��՘�@�@��it� ��w�"Ձ�u��[f�vS��ۓ����Fd�p��/��=o��G������v[Kװ��������"[��@� ��4�]Rt"~2'�	%�����)�K�	Fv2`)�3��0����|4�8bF�� T)��'rdd�j��bEx����	jALs�������;��nZhʀ �rSP�bQ����vk�<��1}4�ܣJ6�*�N���	�D9P��H֦b
's����,�`�v^��h�{���c4��L/�xB�ً�/0���^��і��,O1���b�.W>�����l���Y����/	��7�<|�u/)���7�����v}���.N^x�G7,J��\��|�N|�5]~��k���ᮟ�Ro1��^ð�d7)��� ;f'�.���&.e��䰥�J�-�s-��p6�$OV�_�=�#�Mmb��Ũ/g�GL[s�4���l-8�"�&�BMg�b0�;<!5y��x�d��hg��J>&��k9]�N`�lg$
TfX��Ϣa�U���u���_�{�gV�����\�������s}nh��#p^�1]�'�l}��}�,��V3��7}�v�ް�V����Y~�r	������;��N�ٶ�߼l�M�/��Z��d3�6�8����[�����~���n�]z���B�M	��s-e~�n�n�� (F3i!L�8�IQ5Bc)��u������uZ">�r<�W��U��U�k�+���n�O2c��%ٚ�M,��6�>���[+�e�d�k��T�ww7Rk*I���p	L��-£h����W.���o��&��֢�I����,�-[�fw���-�o�\�q�r���$���'��+��ʀ����L��Y4a�߿�0�Tc�f�㭄b�� Z�`+9W��T:�_ ��w�E}���ߛ�IGz�_I�z: ��lH��R9���N`�ҙ,��$�ա�I�vf���wfEuyQD�D� �W?u�o���6�Xv�o���&��	e���1{������N��kvڻ�{Y��=� }�6��X~�|�,�$12���lX	oB��䆄H�	�J�`��rw�(a}�(�����i�`�LN1�s
Hl�#
��	���Ƽ�F�͐BCd��n���XM8��>�6~A0p�O�CN����H��H�eE����d���,�qe�rܢ|DY���ܟ�2���,C2��<���x]���f幓�����Pg�-(`�؉<�f�^�ߜ�	F�Z0/{�k��1s�tip���M��rH<�� �s��[���H\�v&]顣�42�FmT�Ȼ�pՈ����2�x��-"�c���+ڭ�r��o;�i�w�{�z������=��}��)��ۼ���\O_��Q�뛕v�Si���ǝmڤ<�<N��Ү����=�P9@�=�,���tY��M�9��2d)y�<�x�^�{��J�D����8���X�ή)�ר���1��ڀi���{�C�,_�{ �r��X'\�Z�A��j5vo�#��3gޥ[n��c�Zf����52i2�9�>M�o���c�S��u$S���T2��8�EQ�@��H(�f;CY@��$R��4�c?��DC⊰������N4q4�Z �NfGPx�og��PFм���7����r�d�e��W`0�L��W$Ĕ�y|��7�o��޶v��}/m�!G;go۬<}�q僾9uK���"���r�g���}�Z��5wֽ��Û�e���7���P�J�7����~�Uk���M�>y���.�;Rɕ+D�z1�^<09W��5�$��8���1���X�b��9Znpw�Wx���l�֕�?��K��K��v����P�q���_��1�s\�������˷ݼ��CC��箻Oy��][<��������/���r�_;=�Ϝs|M���~�6��]�����vnޠ���k���.^�Μy��K/[���5��7aY�,��;;�jqhhW����x�B,0wQ4�744_pŴ	��;�lX�m�y�uw�loܾw���`S�:~�mx�g�}r����#�	��|r%���Ao"�&!�-�RB�5%�T�w'��M��I{��M���%po���#*�MțRr+�ȝ	9qB^_�p}B�I	����B���d�EΖ�lN
;��W�ܷC�q�,�=�tsR��$ߒ,޾��[��܎�{$p;�R��a�Ȱ�egY�#�A�y���#-js�E�1RX�"/)̚����}3l�B�=|h�o�L[L�/\�����R���m1rd�̾Y�K�-�W��9-
�׃Hf4�P�]bq���Qp};@7��(Z��@����J ѡ6�I,:]Lʣ�C�;�~���^@k=��ƅ�si�>�*��t} `J�j7��̹L���g�=|���v�����ԙ �y�aN�j)�_���Y���y:C�%6����
�u��-��x�����F��o̞盾�m]z��J��&۔9w�J%g�X_Ħ����k��TϴPs���:���SM+�]�_�tM���4MZ3���e�
�c���k.�Jn���̘Ҟm���m�������,O��O�˒��wm���UÉ�Yg�1��mq[��s�|���}��V��F��|(�mM��[�: ڔ��Vnߑ�:���.ں�|�_�b�/�������ˮ�@�^Hw�6'��,�\:���  �PX	�=Ma�/\2�s���Z:+N�囩W$נ�Mj�μ� ����k\v0d��]����o�̃�ܿk����媋�ȶ�6n�v�K��V�Ո�������3lV�ӻ,�k�+�ֻ���E+V����_�͵46�bc��pR�4A��#?]9�dgjj04�YK����nX~�@֦���i�?�@{����5xk�h} f
����u���=��s��f=�,7$���Z0oR[M�K��M�7�:n:�ɵ��"�j)Z�p ܆�|yI^�`)�%Ք ��K���
pQKŀ�E�ց�{x�%���B���b��e{@���� N�\!����hh��ۉU'�f���Rw��*s<��-�뼤O��ؙWJ1�]qLuNs�� :�xHYp���1􊙝��p������� N�î�����[�חN���OҮwv�R�K'oٵ�¹O*?{g�8��ٙ^z͑}[7wO�LZ2o��Ơ�/�\��-��:�@��~Rn���;6n��镗~��)9��g����	W���䋥�!�}�O~��v�;�Ϟ\�dh3\M2}��������_���ML��/Y�lJO$��Po��f���|���3\�L��7�[�sf�^{���7��fo2?�����]n��� cZ�q&�
"�!�.F�X��<��u'd�Tu�(�b-���޽�=�Y���X����t��FP�T����Z3E�m����b4�d��a*�Lܴ:Ou�n��L�^Od�;"N�ߟ�#V��v�]�J���䑏o׈����,Zr ��L��G�<:������%�W��m'�$2�,"k�F���@�&�{�Z�/K��K���h��A��,��!%�+����������aa;򖒼C������O�ݥ�dw��M��nK�dbAI^`)�����<da��HIކ��V�.2]������O��K��:��)��O䆑���=��w��A�#�Z�S7B���k���U���kf���Ek!��/�.&[��ց��׊�S���P��uxKXu��7XMޫ�l��l�X�k0�h9LowN�'��'B���ɻ��8����/5M}��kA�vs����|�Jr���Ҡd;���y�K�1���@�7N�vx� N�a3��1G�d!)�6`O��.��ҝ�N�����RJW��>�����As�s֯�ӷV������u���7��<�lS��K[fmKb��m¤�O��w���'�P�z��[ۦ��zWz[�]w�K���k���A�/�.�V�ўoo�Ӂ�XnB�𕁫V�KΚZͬ	�m��n勵��ȮhLN���ݻk��qy&���ʽr�顡�6��'+{{W��󚻺�ޘyp�}��_�?�^��y�-�֥p���|�;�ܘ��R��T�6��j)0vc��R�q<S�S_���Ȭ����j�G��B�y��FV�T�c��Pێ�m�
���6�-��>?�A�U6���0������.�.�a�<�q�3�,�=��Y�����	͋������җ����p�7�B���?�{şH��{�����6�w����y�s?��uď�_��?����0�"S���,�m@O1�(!�dQ-< �0�m18_�#�8�SKU*��(��E��d�E;��t	�0M!�{_-$�$B���ް���tf��ʛ�!,�`yK������֡��O��:���H8�sς������>b3hm��f�7h%zgS�.��3ӭ����6����G��Ӎ�?+wӭ�Y��(*�W^�����ʑrv�:�RJ�+?�~Lg)o)�hZ9�����`���8ȸ[����%/���Ȱ�˓�ڇ5,���p��Z���|���uT��|{m�H��uZ:�8�X�P��K�/)K���GNӃghQ*W���_�Ԓz��XSnDf/�X��/�3�B+c��X.�2x�?Vo������Ō����}",Р���%98^ �)����������h=\��4��,��q�5fW��j��Y�+x`B�q.`��K�R��?���5?ڼ�Gʧ�;ʧ?W<���?�B=�oK��"����3�y
�C�e�rbQkŴ�?����fE(a�kD�	�k�
��L;�(Z���O���e)``X�4���e�g�XCI��ZAê�b��T,
J�>\P����P��r;Q���ʪqc�J�	��$<�ɐ��e���)����R�L��^"��@f����t�d��p �F.�C���gx�e���[rG"�XU	��>�g�>Ǟ�(��ϸ��jU��I9���),�9w�����auU�B�G_B` ��&95Rh�|"���b[{
��0�c8@�fk�D[&�2�˘A��J��2�.oׁΎ�A��c"�)����s����n�m|�����\G9��֤��i�ԞE�j�3AR$K&��d:9R�dS��H�X1�D��/QlIw�������ح-Q��CK{�������qDo��|p��Tx�`������R�,�f�]p�*:'�Y�y8�Z*�{�86a3�)ڠ��W��y�Gy�<�';*O�j�s��Ƿ3���A����W��e�+9�b� �Ef���tQ
�i����A��������H�0�|8<9����t�=�P�>
.�ħ�w�~��������gǅs�4��iZ5��V�k3֛bl[#�,9�NH��%����$SRcPUk��Kz9��(�mǩՂ����\Q���I�����|����i���5w~��(��ڼ�A�W�b�4�YV:ǟ�w?Y[���U�U�8w�������+GM����V���,���F�c�?٭������p��&��q�3]L�L�{[�9lf��f?����[Ǎ���(Nr Y��D4�j��i�D�(C�
�BN��EWK߹��N��#�X������X� Ԧ'�㤎�XƐKj���ɹh��@/p�='�%��Kl��x�F��,�3A<d���ѣ/t&G��?`̈́Ǉ)�
 �*�<��dg�*�T:���sZ�>k�����6Z��~�h�P�[9����?�m�^���s�N宇���B�P�n�g�I �Ժ���*E�n`�S�]���t�#��?T>�Ua`�*-�6-R$�<r��A�@:�u2���
.��I̱�����r{v,�kQk����R�F]f�Q��M�e�l��!�Q'�������o߽nK����i�E��'��u*m&7�����6z-#Ⱦ��7�muԗ�_4?�&~�<���Ii�,�;�����mHz=�ٶ�$Q�9���Bf��N�ye�h^RF؛2���3D��d�5�б/������yit���� �=�=�2-U0_h�vr�*	Dy�d�hf�l6�X��4��м`3��d:.A���<j+ɶ�j!�ԳDm!|k�Q�A���X�3A]�b �m��0�!���p\j�x��S��D5��l"���Pd�T�Z8l@$�X�4q�dYU�L��r~��sq�w�z��p01�kEy������o�7��ߔ>$ܨ<]~��Uq.:�r���h�T�F��5�V����@�5��/t����)�1`F1m�t� +�$�i��Au���EpQ���1?��W�([
Dё��)�#�1�z�r�r(��6�#����թ�g�,͢��KGmd5V�*n`�2��#���G�0)��+��{�G�o�o�1T�ÈL׃�eD�剨Fr)[�=&�@qI.�(3��,�!��5:�ơ�G
����3wцi_�聞(�m��*R6WWհªF�(<�2 ��) M7u�-	Q�����V֜&�;F�҅�駛N�_(�Q"��=�S�����_��l������mǨ@ߠK�c `{u�=�h]_�F�L����	4o���diu��=h[�V���m˸�s>8����m?�#wCj8����[J^�+�~�������-�=\խ�Xm��DH�\Jx4���'N0� �M%9�q��Re����h��C�Z'r�	�M��`��!�a���D6ڦ�Q�Q�:]�	FY�W	ɱ�KZVJ������S����e�9C��?y�ܡ�և��.�y���n��rJ96!�c�w��Y��vr��fhNx;:��H�66#��>��u�bUb��P�Z*Z�R&+�8`
|j�X����MCvFm�#�
��M��4���X�m��!X��ҋ����֊
��Ԩ�hw�άXb��TŽ�,i��[���#���O>�yT�L�S�'�.��J7^q�%�d��GޣEy1'�7.=C��WaW�(�݂Cjs,VEv-����WT� ������+���-P������(�T��*�k��Q=�bq� �Jz���{!����*#¤걿�B︕�������p]ՙ�z�1Q_������8�A$!���=!)KE#[�f{&k�A5N��J�}�l�	nY�,���򹂯�Au��5��QL�Z]b$�Q�$7�梢S��I���k7����0k��R~�8/�^�[�4�8�ȃԵ�we�}?�RV>�)��'��Q���:��bT����ã*�o����ၦ��tT�A-!QЌ-�����@�v'��R����h�^�	�X�7��6�}Q�� .����(:�hm���h��������B.�<�Xn�9�kA��I?תK���Δ*�2P�m�Z�T+Z1�^ Zh�6#�x�ћ��҃a��ޠ&����w�{h�K�&�Tq��ro��ܢ�7��42�է�:�o�[޺�a�h�����(�`1���#��4�mU��{g�%\��$HSu����-��)�du)rAC ��p&T�Ņv�FzFѣ1��R�2��ύ5*��ޠ��:iՓ�FF�g�]��i�Sr����.�����U>!���i%9�V�$X�M)�:���T5���f��B!Wm�!pf�T���K�sO/>0Y]+�<
��`�X�rW�1.:����=���:��Ư*?���HF�A�q�/��.�la1�0��SPE�Gc���Y�7�at\0D�/̅��F� hUx�8�g��7`F�7��lb�H	+�Ѽct��"�%���LUr�Ҿ!����/_�8�m�I��R��;���}t��N���U����<��F������T�\�n�&G��/�9s��?>�z�8�X���(9p��������ߟ��Rr�/O��A�� 5��P'��8yUeT�u���j�y5�[����4j�T�{lm�!�"A\v*kԤ�,Q�ޔ�,�*��t)�J_:��� ]
��F_�J컀����B�K��~�K��#�Y��+���.�#���`�# �<;cϡ���s����Wy%�._/kDɏՔ��I��")�L3�Q��<N^]�NACC7?�9�yT��R�����ضVV���.����a�S�#G�R_dF^h$-9k���EI���/Z|)�Z�(�'��
��=��6�z�p��b}K� $N�a[m����"�u���#��9QԽ"�RQk4�3����Mxe+�n/;c9�bHU�R1���$fH P�]�I@-vNꁳ´�~g�����O�9"��?N:{�2��f,��\�����B ��XPݳ�i\��X��E��A��Q+{�LZ ��W���u�jJk��]5TA�T�`�G�x2:>o��O�!_"ؙ@	�1��Cz@<*�`���:[�oF]Uד#v�뒈���,W�uơ�
>#�+�h9��X�d#t��\�XJ�~h 2� �#�9{ȡ1{�n� �&U?�J�����="�h��a�b���sZX?f� ��C]�i���5�k)j�H 5ĊZ˛c��+�o(UWu��=��i��+G42�[o�&o)o�9bo����H��Z�N/��(E�MR��� D�1�0[�I1��W�ඥ����Yk3�?�f!�[[A�� x��C��b��]��)�R���(�[���U½8i�Q��������(�`��񜱙\�c�x$�^���l ^��>�:D\��.M��`0.��_���{L�2{zn�Yw�)�e�l�UN��g%vc �|5�\��v�-ֲ�Z��6����䜅�ڢŌZ��F���݊�ъ�/����6��F�
FU��$dE�:Ec�$�p0Q.�����}(c�7�0�?V~���XW F�U�Q��5��v%�=�9�Q#��*e΀�G�C�p�*���J�Ե&ܘ���g"8p�4J�65N�_�e��V6jqW�z12�T0��Up6��(C1���ND��t#~X_������l��+U~�BB-��'��!��U�U�w�FXyV����9!w���&u��j��Ǵ�F_;�uZZ���������oj�H����C-�ǉ�)SM=n�!�����:�ͥ�)Y�d�f5�Y>�̖u�NW؅uN:�.���tX��Em������A���$:1������/���Jщ�X��j�T'4��_��ѕR^�"�q�6v'��0~����wC;g��7�����l�;0�Ǭ�؞F.u&�98m�q&�Ψ�6#��:bu��۱(K�6����S���������Y��㷝�/S�r|w�4U�����~o"(������c�(l����G���e���d������4:�����'�u�l��D�`�e�*n�İ,.5u`��R���4�/��(b���>�Z̳��"�S�`&��j���0b�&R��Հ��R�����`c��A����!cq �ٱ��ו�P~T�_�3�}�$�_�O�U>T���	�Ʌ!����q���|�G0�Y�v�Rw�R�q*�o���D���D��<�mT��b�i��I���.:u�)de넑0���
o�Ra���ʌ��V���H�	�����t�������!N���ԝq٪��_�w~�U�oKRGy���s��\e�x������x�u�,�H�!vu����O����D���Y����=���<w߶"�Wld�*��uf�yV�c������e���ڟ���g|x���|�L��=���7���y�+Y�%�������'jۦR���A�d�T���(���@��ü���������A0��;RjmY�:���������yh
+�����s�ɭ��H���>sA��e�:c ^�䫇j�;t���>��lj���M�<vG{fҤ���M;��`kx՜�+���Z 9T�^�ѷY���s�Ť<+��q�A���S?��^�|���	2��v���	t?7�)[Mc�FՐ��m4hKE�6v��<��o�Ń �1i�r	��G�԰�t� ⥱�J^QՉ��ܘ���FBf�[�p�q�m��\ym�#�lQ^�ܡ<?T~��b�v�I��ׇD�ϐ����m3(x�i�Yަf�0�k[K;^?v�u��cJ>���Nʱ�)%���hn����>���H_ܫ�X�I mU�'6�0�NP6�,X�T��/U٨.�1BV�hD�,�I `#�*+�h��х?�f�1�ߜ�rH@MŰh0g<�c[��0Kc���D�y�W�V���e��Kt��B�|JJ%�RW}��f*���>ϸ=�����+ǽl�p�V�mj�Q��mԡ��vU�b� �7�6����eC�a��������{a�~�/n���Eʾ���Wg(��)�7^E�4�۽,����[�
��Tj1P�y0=I��q�]�X�k ���̚ӈ9X]�C��আ��}s�*R%�X�7ˡb�ݔ�D/)p���&��~���'���q�"h�&��l�]R��`:�WE>~�M���'�  1�V��)t@�<n��
�T�ƾ����7�D�S��6��r�N�c�_屳��dp�Q�8H?���hZ*j�XP��cR�,�\ �à7֘�6u=�lʍ�Y��o��h�9�НӘ����-e�e+�*X�	�o�?�aJ��� 3�j�5�$���U�N��RQpbg�S�jJ��EV &N.gU�*h����L]/��K��㴍U<�5�0�L�J0�0ŕ�~>ð	��8��u��K6�=[�u ��e����&u�V�^n̎kT��XY1:)8L"�k��"\ʞJ8[d�tl�S0�B�����0�/7���nedS�#j1
��}CKgE�d�QLK�b}����p�C�^�R�-��Q�������������Y
�'��'����x��R�Φl��_�S:Nj�>�Z�;p�E��D��I�c�w\58U���Ԕpk�J�$��j��F,L�EHm.7�[�R���
����Q�#T�< �ˏ�WQ=�4l�9Cq��VRݿ�)QI��DWV���+�d3ueEʲ���>� �x������x Z�2�%�Ȁ��Xs���Ğc�V�9o|?�uW#�o4���R�4&�ole�U�"�Y���X�����.�.�� ���X"����k  x�c`d```f8���f�x~��� p�k�~d���B1�( w�X x�c`d`�`�D20��$�"(� c��x�5O��@�M
�����GM���e��aza ,��{����- *�" �D2��j�0ʻ�.dw��b�f(�k>�GG���9�),R�Sz�wE�]���0���������Z+O�����Z/z?sh���y`!�   & & & . � �Z���:�$�P"p� �b�	�
x
�6�� �@F�0�Z���P���\��`�(J��$@l��6�.r��6`�&�   R t � � � � �!!$!�"8"�##x#�$4$l$�%R%�&&:&X&v&�&�&�'t'�((�(�(�) )")L)�*$*b*�*�++d+�,,V,�,�,�---       � �            �    xڍ��NA���`aE,�6�BM���PY��C4jg����	+`���X��V~3� ���ιg�=���JZѫ��II��	��"���V��p\eo�Ih�+;���w��2���I�{��i-�r�]�؎�Uә|����U������
�_X[�Ȫ��G�HMP�2(�X�|{DS����SYY�Ȏ��>����&�C������������a6Tu�����t���j���A5s���x�EUt�S���(/=��H��i����w��.a&ьm���ߐ����h�o�z��h���x��Jv��Q����?�mW�����;�A���%'=T��O^`��p��oY����ퟓc����p  x�m��rQF�Y�www�>g���@pww	48O�+!��誩���ګ��n�<�~�R�Ϗ??Zmڌa,�O��$&3��Lc:3��,f3���c>X�"���,c9+X�*V����c=��&6���lc;*��P��K;��.v����c?�� ��0G8�1�s����4g8�9�s��\�2W��5�s����6w��=��<�1O�)�x��x�+^󆷼c��|�#������=�C����݁��q+7�����۸�n��&�����^�KzI/�%�4�Uz�^�W�Uz�^�W�Uz�^�KzI/�%�����d��ޓ�'{O_������	��	��	��	���t���]�.a��K�%�v	��]�.a��K�%�v�����^��zY/�e�����^��zY/�B/�B/�B/�B/�B����W�^�+zE���Z�֫�j�Z�֫�j�Z��k��F��k�^�*~W��t�oU(5����� K�PX��Y�F+X!�YK�RX!��Y�+\XY�+   R��?                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 FFTMj(�     GDEF �   8    OS/2�[h�  X   `cmap�i�c  �  �cvt b�  l   fpgmS�/�  |  egasp     �   glyf�v#  �  Z@headQ?  `,   6hhea�  `d   $hmtxU�@{  `�  
loca��j�  a�  maxp�B  b�    nameB�^  b�  �post�$�  e�  
prep��+  j�   .webf�@R�  j�          �=��    ����    ����                       ��  3�   �3�  � 2�                           UKWN @%��f�f    (                               �       �         %��	�	��%��	��)�9�I�Y�i�t���  %�� � �� � � �� �0�@�P�`�p� ���(1+%|                                                                                                                                                                                                                                                                                                    	 E A f  �  � ,� K�LPX�JvY� #?�+X=YK�LPX}Y ԰.-�, ڰ+-�,KRXE#Y!-�,i �@PX!�@Y-�,�+X!#!zX��YKRXX��Y#!�+X�FvYX��YYY-�,\Z-�,�"�PX� �\\� Y-�,�$�PX�@�\\� Y-�, 9/-�	, }�+X��Y �%I# �&J� PX�e�a � PX8!!Y��a � RX8!!YY-�
,�+X!!Y-�, Ұ+-�, /�+\X  G#Faj X db8!!Y!Y-�,  9/ � G�Fa#� �#J� PX#� RX�@8!Y#� PX�@e8!YY-�,�+X=�!! ֊KRX �#I � UX8!!Y!!YY-�,# � /�+\X# XKS!�YX��&I#�# �I�#a8!!!!Y!!!!!Y-�, ڰ+-�, Ұ+-�, /�+\X  G#Faj� G#F#aj` X db8!!Y!!Y-�, � �� �%Jd#�� PX<�Y-�,� @@BBK� c K� c � �UX � �RX#b � #Bb �#BY �@RX�   CcB� CcB� c�e!Y!!Y-�,�Cc#� Cc#-     ��      ��   	    1!%!��>��T��������D_ � ��� �Y� �����             1     �       h � /�Ͱ/�3�Ͱ2�/�3�	Ͱ2�/���/�ֲ 222�Ͳ
+�@	+�@	+��+�ͱ22�
+�@	+�+ 015!5!5!5!5!5!  �  � ��� ����   �� �� �� ��� �� ��        B �
/�Ͱ/���/�ְͰ�+�ͱ+��	
$9 �� $901 $  $ 264&" �a�a�����^��2�Ԗ��/�a�����^�����Ԗ�Ԗ     ����    ! z � /�Ͱ /�Ͱ/�ͳ
+�Ͱ��"/�ְͰ�+�Ͱ�+�ͱ#+��
99��9��$9��9 � �$9��9901%!5! 32 54 #" 46  &� � � �� @����������
�������� � ��� �`����������������  �  �     C � /�33�Ͱ	2�/�Ͱ2�/� ְ2�Ͱ�+�Ͱ2��+�ͱ+ 01!!!3!���������� ��� ���� ������   ���    �� ��  ���   4 �/� ְͰͳ	 +�
Ͱ
/�	ͱ+�
 �9�	�9 0146 .��>�q� q�����|�(���(�  �   
  \ �/�3�Ͱ2� /�3�Ͱ2�/� ְͰ�
Ͱ
/�

+�@
	+��+�Ͱ�Ͱ/�
+�@	+�+ 01! #265!! #265 ����j� ����j�������� �j������ �j  ��   3 � �/�Ͱ+/���4/� ְͲ 
+�@	+��+�.Ͳ.
+�@.1	+�.�+�Ͳ
+�@	+��'+�!Ͳ'!
+�@'$	+�5+��
99�.�+$9�'�*99 �+�$1$9014672654'5 &46 5654&". jV@p�p@Vj���� �
�jV@p�p@Vj��a�%�9U��PppP�U9�%�a������������a�%�9U�PppP��U9�%�      ��   # , �!/�3�Ͱ/�3���$/�ְͱ%+ ��901463!2#!#"&7!2652+�` `��`�@�@�`� ����`��`��@��`��`��`��@���`�ب��`��`��@�   ���     F � /���!/�ְͰ�+�ͱ"+��
$9�� 99 � �	$901!%64'7>4&'7 ����5KK[);5ZGOOG[Xc.S:  �� �KK�K[=EL'N�6ZG�лG[Y�W��;      4   g k �e/�^Ͱ*/���h/� ְ6Ͱ6�+�
ͱi+�6 �KMP999��<DUb$9 �^e�b99�*@
 (,4@IV_$9��"$%02$9014 $32  &54654'>54'>&'.&'..'.'*67'$ �{��{��y��$"EMy�V7i
3<c7v��v7b>/

:0i7V�yL5?XW 	"6		"$6=R."$���y �|�����������g�Q�;	Hj�r�q(H_11%!!%1�}s�r�jH /Z87#8!

2x g             # , : G P t �  +�&Ͱ/�Ͱ8/���Q/�ְͰ�O+�B2�	ͱR+�O@$(-5;H$9 �&@
	 *>?@BPL$9��;$9�8�!/D99901 $    $ 6 7&''7$7' !27 >7&$#"6'67$�{�{�������d����$tl}��@���q� �􊍳����H��!�����f��M�i)~��� �P'�Lz�G��T:[����2�{�������d������I���x�6M0�V2�H.�U�����P,�J��i���: >B$~��p3T!�9_j����ʗlT�.    �,�� 3  3 7.'327.=.547&546326767# �0( �w�$'/2�PWLX2���
ؘ�l�i*wmfIn>u���٤�����oʄ+2�^eT��'-��t?�G-oP z��˘W  � ��� ' D �$/�3�"Ͱ2�/� 3�Ͱ/���(/�ְ2�	Ͱ	�Ͱ/�3�)+��9 01463!2#!!!=4637&#"#3!"&��j j��j����+B�Ni^�X,���,j�� j��j� j���<7�
7^uD����9�   � ���  N � /�3�Ͱ2� 
+�@ 	+�/�Ͱ2�/�ְ2�Ͱ2�
+�@	+�
+�@ 	+�+ 0134>32#"!!!��4f�m7j�L3I�����/N�n@��@E�����I   � ���   T \ f � �/�WͰ/�%433�ͱ).22�R/���g/�ְͰ�<+�O2�dͲ<d
+�@<K	+�d�`+�	ͱh+�<@	UY]$9 �W@	 "7G\]`d$9��+HKb$9�R�(099016$  $&326?6'.6326?6>54.'.	5463&$#"327&'654'���LlL�����������4���F�4%l#",1&�}U�O&k##+1#QN:
n�����Opt������c=JlL����������������{d�"��X" 
��O9\!.Y5"

9Veo���!.���js�ϳ(*��    � ���  E Y e � �W/�(3�IͰ/�ͰC/�3�ͲC
+�@C	+�2�f/�ְ2�Ͱ�"+�2�$Ͱ2�"$
+�@"8	+�g+��F[$9�"@
)+BKTVa$9 �IW� +F999��.8999�C�!#$;\b$901327"#"5463!2!#!!3!#!654&'.5467654&'37!">!"&&6'.�}� I���j j�� ��  � �j�]�#$.L�ZS�����E9ڀTJV$<��U�A�{y��{}���=9!<%Aj��j� � ��  � j���k
4F;��q�0�b��Ui;1"1%e6��壠��      �   3 H �/�$Ͱ/�
��4/� ְͰ�+�ͱ5+� �99��$99 �$�,-.$901;265.#"!!!47>32!4.#"5! eQTfdSSgJ�� IV?:L!I9g�R$B3/! ��IbbIK`a����!)62F:aE��8p�k7#������]  � ���     7 } �/�ͱ!+22�0/�Ͱ  ��63�Ͱ���8/� ְͰͰ�+�!Ͱ!�"+�+Ͱ+�,+�	ͱ9+��9�!�99�"�59�+�0699 � �(59901463!2#!"&;265.#"!!!47>32!4&#"5!��j j��j� j� QACSPBCR���E3���2Q8"��� j��j� j��x:OO:<MN�����-(8��Xǯ�,&p  �  P  A � �  +�/�)Ͱ0/���B/� ְ5Ͱ5�+�"Ͱ"�-+�ͱC+�6�>�J +
������&��&+�& � �#9 �&.....�&.....�@�5�9=999�"�0999�-�)99 �0)� >$9014>$32#"&''&7&5463232654 #"'&�G��+��/�t����c�"I"]��(�fSX%=yeu�c�����XS	#��m�Ξ_y������v�]@��9{�6�{�Qw��kR3��9c��1��c��x�d=(	3           V � �  +�ͰI/�8Ͱ/���W/� ְ%Ͱ%�3+�LͰL�F+�;Ͱ;�+�
ͱX+�L3�+/ST$9�F�8AP$9�;�?99 ��T99�I@	
 %)3;?P$9014 $32  #"'6732654.#"67>76&'&54>32#"&7>54&#"$ �{��{������ѓ�JH�P���^�����z9jgDG��q��P�^Rc1GCRs	q���� �|�������^����*tg5K�?�rԣbM���Y��*	@	Nza��Qۮ���uP.�v*BW�q+Q�%Sa�x�    �``�   ( �/�
3�Ͱ2�/�ְͰ�	+�ͱ+ 01 6&  6& ��X������X���������X������X�    �   ! 5 �/�Ͱ/���"/�#+ ��9�� 999�� $90163232 %$632#"&�Ty\Q�l��na�����E@�pq8I<41�Y�clT��������[�S�\��OH/�q     ����    �/� ְͱ+ 01463!2#!"&%	�� ���� ������  ���� �����   � �(� " # �	/���#/�ְͰ2�$+ �	�901332767#"'&'&5!!#���_]}n[Xx��L<,��V�-0IJk���yBAr 8X$-.��ŋY]=@#  � ���  # + ; � �/�Ͱ/�'Ͱ/�"3�/Ͱ+/�Ͱ�8��</� ְͰ� +�%Ͱ%�)+�Ͱ�+�32�	ͱ=+�% �"9�)�,;$9��9 �'� %($9�/�$)9901463!2#!"&73!265!   547!$ 6& %;26=4&+"��j j��j� j��K5 5K�����X������>����K5�5KK5�5K� j��j� j��j5KK5 B>���,�>B����>�5KK5�5KK5    �     # ' + . P �//� ְͰ�+�2�$Ͱ(2�$�-+�ͱ0+�� $9�$�!#$9�-�"%*,$9 014762#"'&?'%%7''7	5 �(��B�B���5��������B�����������-�- �,��nn����4�8��3������"X����X��n�  � ���  5 W � � �/�Ͱ>/��ͰJ/�R/����/�ְͰ�,+�6Ͱ6��+�wͰw�+�	ͱ�+�6,�W9����YgB�$9�w�v999�@"02<>JM_p������$9 �>�&:;999�J�<M�$9�R�023999016$  $&32$7654.''&$'&76$7277#".54?6$32&'&$'&676&'.7>76'&'&7./&67>&'&7>'&'&7>'32 =.'&$���LlL�����������>�x���f;W��st�'6Iro������GJWX-�!���ȗ�|x {LH����b��b$"h�ܟ���mmd�mn�;=KF�OU32\\P&434TS;9+(/.H	ELG.&G++[!JV�BDe%�or87&#ꁃ�WV�rxS[�
kTf��mt==JlL�������������������̈�uќj0�y���x{pޟ�m��������IG9���֟r��{9'~�uok��zw�!S][�JM<>_bTT1G)XGH<7S%?8+J!+"LR'7	DeAk/m�Jkg++suyy�ډ�ss*#"�k�>M;>ik     � � � N h  �� �/�fͰ�/��3��Ͱ�2���
+�@��	+���
+�@��	+���z ��5Ͱ'/�n3�*���/�/ְiͳOi/+� Ͱ /�OͰi�@+�Ͱ�7Ͱ7/��t+� ͳc t+�Ͱ ��+��2��Ͱ�2���
+�@��	+���
+�@��	+��+�6�	q�� +
�H�F��V��X��t�( +
�G�E��W��Y��HF�G�FGE+�GE�H�GHF+�V�WVX+�VX�W�XWY+ �EFGHVWXY........�EFGHVWXY........�@�@i�5n999��\fz999� t�'($9���)9 ��f� @$9���;999�'z�/it$90132>54./.4>7>54.'37! 32774>7>32#"&47>32#".5!3!!#�@y�|uˑg2 6$!U%,< .������IM C\~I8
=J�{32I(�"+A$#SH-#3>A$����v3[.8cD2;4=:dC0� � � ��Bw_82Vq�C(E=-7B+0/&B>Y2+N?0/U�@�T8k^G*,.+ KNG/Q0*	$.7*23q���n@#*9\st4q;9Zqn��� � ��       � �8� Z H �G/���[/�ֱ4+�7Ͱ2�47
+�@4/	+�7�=+�ͱ\+�4�OUV999�7�"G99 01>7>././67!07676.'#0.�GCF�����{\(m]e7(+loxohWA (?%4nTE!DC88A�{i#?R[\S@Bَ9(		�:)���\Nr; 
	HU���}Y�S7��""-'("+$-��� \N�L�[J-!}2A-`PM:,�	 in   � �s� ! x � �/�(Ͳ(
+�@	+�k/�cͰU/�ͲU
+�@	+�y/�ְ[Ͱ"Ͳ"
+�@"g	+�"
+�@ 	+�[�.+�Ͳ.
+�@.>	+�z+�"� 9�.[�(Mu$9��9 �(�9�ck�."u$9�U� 6$9��9014>32632#"'#"$&547&32767654'&'&'&'&'&'&'&54763232764'&'&'&#"#"'&'&'&'&#"��ᅍwEK�8�3��{lOL����C�64Ww��qo<;"#=:UMnY#!!46\a,+ /5"":9RVo�lk9974\_~]=9!!>AiN-/ +4#$���K���ȫ`Ujo��:��8�VSt��KNL/?..TUgX=>&'	."$  >0$#\2/--('JIafDA((2@), "41!!   � �  2 [ �,/�'Ͳ',
+�@'	+�3/�ְͲ
+�@*	+�@	+�@	+�@#	+�
+�@ 	+�4+��9 �',� 90132>54>32!2+2#!".#��1na@!YF�5K0&0&K5@5KK5�@AtGZ*��^���-!T�RU�%&K5)B!')B!'5KKjK(0(    ��3s 1 : I R d l t }v �*/�?Ͱb/�YͲYb
+� YV	+�\2�R/�k3�MͲ4gw222�8/�|3�Ͱ2�/�pͰt/���~/�-ְ<ͳ2<-+� Ͱ /�2Ͱ<�K+�PͰP�f+�jͰj�C+�&Ͱr ��ͳy&C+�!ͱ+�6�>��{ +
����������� +
�������� �.......�.......�@�<-�/499�K�86$9�P�*SW999�f�?GYb$9�j�)[^_$9�C@	mpsu|$9�&�$w99 �RY�<9�M�&-/$;C$9�8�!26 uy$9��G999��99�tp�9901463267676>32#"&'%632 $&547.767&#"32$654&$#"4632"46232762#"'$462" 264&"654&#"�vSd<��\9S0JiiJGf��Mޯ<gSu8.����r���,5f(/U,):Q�#��#���ݫ���rA./AB\*8��8*V��V�A^AB\0-@--@S0+9)-�SvSa	�H)1i�iaG<�~`VvS7[21�慅�26Y5/K>$9�ֶjj�kl�jj�\BB\B�+88+VV�\BB\Bz@--@-��=M0(:     �  . � �/�,3�Ͳ
+�@	+�(2�/���//� ְͰ�+�Ͱ�+�2�"Ͱ2�"�'+�*ͱ0+��9��9�"�-99 ��$%999��	 !$9��
9015!265> '54&"#"&%5726=! & /D/���i/D/ɍ��Qi�/D/����Q��!//!爽��k-0\".."���ƈ�0.�!//!�ы��  �    $ = �/�Ͱ/�"3���%/� ְͰ�+�	ͱ&+��99 �� 901463!2 $ 72764&#"	&" �j j�����^��� &j%�%j%J65%����%l$��j��j�������a�j&��@@j&jJ%��&%%     � ��B       	5	7	�m����q���rr���l����q���#�����Y�����7sg3��gs���H�����4����    �     �/�3���/�+ 01463!2#!#"& �j j��j�@�@�j���j��j��j��@�� � �   # � /�	Ͱ/�Ͱ��/�+ �	 �901!!!37!��@�����@��   ��� ���         �/���/�
ְͱ+ 01	! 264&"   � �KjKKj  � � 5jKKjK   ��    % ] �/�Ͱ/���&/�ְͰ� +�#Ͱ#�+�	ͱ'+� �$9�#�$%$9 ��	 !%$901 6$  $&2>4."3 o��oo��������[���՛[[���՛��*Z��oo��������oo�	�՛[[���՛[[�����[��[   � � 	   � /�Ͱ/���/�ְͱ+ 01463!2 462" � ��� �Ԗ��� ���� �Ԗ�Ԗ  ��    , m � /�Ͱ+/�Ͳ+
+�@+'	+�/�ͱ	22�Ͱ2�-/� ְͰ�+�Ͱ�)+�$Ͳ)$
+� )	+�$�+�	Ͱ	�+�ͱ.+ 01!53!53!3!2654&#!"46;2"&5#"  ��� � K5�5KK5��5K�&�&&4&@� �����  5KK5�5KK5Z4&&� &&�   ��    6 � � /�Ͱ5/�43�Ͱ/�ͱ	22�Ͱ2�7/� ְͰ�+�Ͱ�+�	Ͱ	�+�ͱ8+�6�>�� +
�4.�3��,��-� �,-3...�,-34....�@��&'()*$9 �5�/901!53!53!3!2654&#!"463!;'.7#"  ��� � K5�5KK5��5K�& !�-l�� �����  5KK5�5KK5Z4& 
� -�  ��     # ' + / 3 7 ; ? C � � /�Ͱ/�$033�ͱ%122� /�(4<333�!Ͳ)5=222�,/�8@33�-ͱ9A22�/�ͱ	22�Ͱ2�D/� ְͰ�+� 22�ͱ"22��$+�(,22�'ͱ*.22�'�0+�4822�3ͱ6:22�3�<+�@22�?ͱB22�?�+�ͱE+ 01!53!53!3!2654&#!"53535353535353535353  ��� � K5�5KK5��5K��������������������� �����  5KK5�5KK5���� ��� �� �� ��� �� �� ��� �� ��    VVzz   >7'72?64'7&"'V80��%k&�&&��%j&�%%���uD[V-4Q0��%%�&j%���&&�%j&���uD2         N �/�Ͱ/���/�ְͰ�+�	ͱ+��$9�	�9 ��9�� 	$901 4>2#". 32  #" [���՛[o����u՛%������K�՛[[��u�����o[�����r           ! f �/�Ͱ/�Ͱ/���"/�ְͰ�+�	ͱ#+�� $9�	�9 ��9�� $9��	9��99901 4>2#". 32  #"5! [���՛[o����u՛%��������K�՛[[��u�����o[�����r� ��          ) � �/�Ͱ/�%3�Ͱ#2�
+�@(	+�
+�@!	+�/���*/�ְͰ�(+� 2�'Ͱ"2�'(
+�@'%	+�('
+�@(	+�'�+�	ͱ++�(�9�'�999��99�	�9 ��9�� $9��	9��99901 4>2#". 32  #"5!3!!# [���՛[o����u՛%������� � � �K�՛[[��u�����o[�����r� � � ��        , �   # + f �/�Ͱ#/�'Ͱ+/�Ͱ/���,/�ְ%Ͱ%�)+�!ͱ-+�%�#$9�)�
"$9 �'#�! $9�+� 99016$ 	 $'267	&#"6  264&"�e,d��e������d�rM���Lu����l�K�>���KjKKj �gssf��grsg���NSSMts�RN��>�����5jKKjK     , �    + 3 @ �*/�Ͱ	/���4/�5+ �*�999�	@
 %&-2$9��999016$32&#"'4632&73267'7	#"#"�e����gpl�K����%Z Z�-�,6�,�gpl�Lu�x�e����_��, �gs_�.RN�����Z Z�S���,6���.SMt����grb�6,��    ��     	7			�@���@@���@����� @@���@������@��   � �     	7			�@���@@���@����� @@���@������@��  �� �  ! - � �/�ͱ22�
+�@	+�222� /�ͱ
%22�,/���./�ְͰ�+�Ͱ�+�Ͱ�+�ͱ/+��"#999��()99��	9 �,�	99015%463!23333333#!"& 3!264&#!"� K5�5K � �������K5��5K & &&�  ��5KK5��� ��@��@��@��@5KK4&&4&     � 	  		 ܷ�۷���������L��J��  � ��  
 * �/���/�ְͰ�+�ͱ+��9 01	!!!����� � � � � ��  �@�         	  	5!   �  �  �����@�����   ����      	77''������� �� -`S`�M`S`����@���� � -`S`�`S`   ���    � /���	/�ְͱ
+ � �901!	 ���������@������             B �/�33�Ͱ/�	Ͱ/���!/�ֱ22�Ͱ�+�Ͱ�+�ͱ"+ 01 264&"2 !4$2 ! $ p�pp�p�{�� ������Q�n� ����&�pp�p� �������a�  n������r��W�     ��     �/�Ͱ/���/�+ 01&7>#!3 264&"NrX�9��:�Xr���}3�4��KjKKj ̎�]CC]�+�� �����jKKjK    ����  & . _ �"/�Ͳ"
+� 	+�/�Ͱ. ��*Ͱ /�Ͱ/�Ͳ
+� 	+�//�(ְ,ͱ0+�,(�"$9 ��',9901!6$32"'&#"4623265!!#"&'7 462"�6K=�X%Jj&t���hLj%q��� ��K���Yp�pp�  ��dY%jL&r��djJ%s�� ��dYr�pp�p    ��  ' } �&/�Ͱ/�Ͳ
+�@	+�/� ��(/� ְͰ�+�Ͱ�+�Ͱ�+�#ͱ)+��&999��99��%999�� 99 ��99013 654&"2653"&546     ��
�p�p&4&�p�p�
�������@@�������PppP� &&��<PppP ���������    ��     . �/���/�ְͰ�+�	ͱ+��999 01 4>2	& 6& �[���՛[YP�i�iP��
����K�՛[[����P�j�P�����
�    `� �   7#`����� � �� `�            � /�Ͱ/�Ͱ/�	��/�+ 01!!! �  �    � � � � �        $ N �%/� ְ2�
Ͳ
 
+�@
	+�2�
�+� Ͱ$2� 
+�@	+�2�&+�
 �9� �#9 0147'!'&$>54' xk���HP٧��ܨ��GQ٧�$�xk� �k����G�h���+���E����G�g�+�������k�   �� �   � /�Ͱ��/�+ 01!��� �     �      ( � /�Ͱ/���/� ְͰ�+�ͱ	+ 01!%!!��� ���  � ��   S@ �    # � �/�Ͱ /�3�Ͱ2��Ͱ!2�/���$/�ְͰ�	+�Ͱ�+�Ͱ�"+�!ͱ%+��$9�"�999 ��$9��	$901!#64>2"&264&"	!#Sup�����V���VV���*q�qq��up����@�>��� i��VV���VV9�qq�q���>���          2 �/�Ͱ/�
Ͱ/���/�+ �� 99�
�9901!!3 264&" � ���� �3�4��KjKKj� ���� �� �����jKKjK 	��      # + 7 ; ? C � � /�Ͱ8/�3�9Ͱ2�</�"3�=Ͱ2�@/�*3�AͰ&2�/�/Ͱ/�3�Ͱ	2�6/���D/� ְͰ�+�$$2�Ͳ (,222��8+�<@22�;ͳ	>B$2�;�+�ͱE+�;8�2399 01!463!2!%!#!5#462"462"462"3!264&#!"5!5!5!� K5�5K � ������&4&&4&&4&&4&&4&&4Z& &&� Z�������� 5KK5� � ����4&&4&&4&&4&&4&&4&�4&&4&� �� �� ��    � �   / �/�Ͱ2�
+�@		+�@	+�/�ְͰͱ+ 0146;&54632>3232#!"��j���^/�r��j��j� j�Ԗ#��`e{�>B�Ԗ   ��     '&4?62'	7��JJ�K�JJJ�J�K�9� �� � �J�K�JJ��K�K�IH�9�� �           �/�Ͱ��/�ְͰͱ	+ 01 462" �Ԗ���Ԗ�Ԗ   ����   � /���	/�
+ � �9015!7	'����W��WW��WV��VW ���   	7!5!'�W�����WW��VW�WV �@     7	����������@���� �� @   		�@@��@�@ @�����@        ��   �   +�/�+ 011���� �   �   �   +�/�+ 01!�����   � ��    �/���/�+ 01	5!����   ��� ��   ��          # ' + / 3 7 ; ? � � /� 0333�Ͳ!1222�/�$4333�Ͳ%5222�/�(8333�	Ͳ)9222�/�,<333�Ͳ-=222�@/� ֲ222�Ͳ
222��+�222�Ͳ222�� +�$(,222�#Ͳ&*.222�#�0+�48<222�3Ͳ6:>222�A+ 0153535353535353535353535353535353 ���������������������������������� �� �� ��� �� �� �� ��� �� �� �� ��� �� �� �� ��            ? �/�Ͱ/�Ͱ/���/� ְͰ�+�	Ͱ	�Ͱ/�+��9 01463!2#!"&!! 264&" �j j��j� j�� �  KjKKj  j��j� j��j �5jKKjK         & � �/�"Ͱ&/�
Ͳ
&
+�@
	+� /�Ͱ/�Ͳ
+�@	+�'/�ְ2�
Ͱ2� Ͱ
�+�Ͱ�$Ͱ$/�(+� 
�$9�$�9 � 
�9��9��901!5	53!!#5463!2#!"&$264&"  ���� � � ��j j��j� j��KjKKj  �� � �� �� ��j��j� j���jKKjK    � �  	   H �
/�3�Ͱ2�/�
ְͰ�+�Ͳ
+�@	+�
+�@ 	+��+�ͱ+ 01! !& 462!462� ���� ��� KjK KjK����-��-_g�5KK5���5KK5��     �  �   & T �&  +�/�3�Ͱ2�
+�@		+�@	+�'/�!ְ$ͱ(+�$!�&99 �&� !$999��"#9990146;&54632>3232#!!!"!!!��j���^/�r��j��j��� ��jj   ���Ԗ  ��`e{�>B�Ԗ � � � ��     � �  " D � /�3�Ͱ2� 
+�@		+�@	+� �Ͱ2�#/�$+ � � 99��990146;&54632>3232#!!	!!"��j���^/�r��j��j�  ���� � j�Ԗ  ��`e{�>B�Ԗ ����    �� 	  v � /�Ͱ/�Ͱ/�Ͳ
+�@	+�/� ְͰ�+�	Ͱ	�+�Ͳ
+�@	+�+��
999�	�9 ��
999��901!!!7	!5!#  �� ��� &�Z���� ���� �� �%������     � �  
 8 � /�Ͱ
/���/� ְͰ�+�ͱ+��9 �
�901!%!!! ���� ����� �������   � �    F �/�Ͱ2�/�3���/� ְͰͰ2�ͳ
 +�Ͱ/�
ͱ+�
�99 01463!#";!"&63!&47!"��j @5KK5@� j��K5222��5  j���KjK���jK7�7   � �`` . 6 v �&/�2Ͱ6/���7/�ְ0Ͱ0�4+�ͱ8+�0�)-$9�4�$($9��#$9 �2&� !$(+$9�6�$9��
$9017&547'7677627%%'#"''&' 264&"���:1/<��CCɌ831:��:��,?��C	Cɋ83��&�Ԗ����C	Cɋ</1:��:��,?��C	CɌ83��:��:1,?
Ԗ�Ԗ          F �
/�Ͱ/���/�ְͰ�+�ͱ+��	
$9 �� $901 $  $ 327654 #" �a�a�����^��2,ԇ|�EH��H��Ԉ/�a�����^����2���H�~3�E{��,         n �/�3�Ͱ2�
/�Ͱ/�Ͱ /���/�
ְ2�Ͱ2�

+�@
 	+�
�Ͱ�+�Ͱ2�+�
�99��99��9 015!!!!!462"$462" ��� ���� KjKKj�KjKKj��� ���� ��jKKjKKjKKjK    � �    �/� ְͰ�+�ͱ	+ 01!!! � �� �  �           � /�Ͱ��/� ְͰͱ+ 01!    �     � �    �/� ְͱ+ 01! �� � � ���   � �    �/�ְͱ+ 01!  ��� ���� �   � �     � ��           A �  +�Ͱ/�Ͱ/���/� ְͰ�+�	Ͱ	�Ͱ/�+��9 01463!2#!"&!! 264&" �j j��j� j�  � �KjKKj  j��j� j��j �5jKKjK    �       & } �  +�"Ͱ&/�
Ͳ
&
+�@
	+� /�Ͱ/�Ͳ
+�@	+�'/�ְ2�
Ͱ2�
�+�Ͱ�$Ͱ$/�(+�$
�$9 � 
�9��9��901!5	5!!!!463!2#!"&$264&"� �����  � � �j j��j� j��KjKKj  �� � �� ��� ���j��j� j���jKKjK          0 �
/�Ͱ/�Ͱ/���/�+ �� 99��9901 $  $ 264&"3# �a�a�����^���KjKKj+��/�a�����^�����jKKjK� �         0 �
/�Ͱ/�Ͱ/���/�+ �� 99��9901 $  $3 264&" �a�a�����^���3�4��KjKKj/�a�����^����2�����jKKjK       + 3 h �
/�/Ͱ3/�Ͱ*/���4/�ְͲ
+�@	+��&+�ͱ5+��
,-$9�&�	*.03$9 �3� 99�*�9901 $  $63235476767654'&#"264&" �a�a�����^��\�d&5D�ES%%TU��KjKKj/�a�����^����ԴG#"*-,A9"-5::QoAA��jKKjK     ����   	�  � � � ������    ����   �  �  ����  � �         
   & �
/�3�Ͱ2�

+�@
	+�2�/�+ 013!#3'#3!33'# �VTV� ��+�� �VTV�� ��+�  � �  ���  � �  �    � �   	6&$'&�p��p���p��p�����j��ej<�j��j     f��  	   7''	f0����n� ���/���� �9�99��X> >����ǎ��          	  % s �/�Ͱ2�Ͱ%/� 3�Ͱ�#Ͱ	2�&/�
ְͱ#22�

+�@
	+� 2�
�Ͱ�+�!2�Ͱ�Ͱ/�'+ �� 99�#%�9901%5!	463!2#!"&!3!5!#!  �  � �K5�5KK5��5K� � � ��  �����@��������5KK5��5KK5�  � �          	   H �/�Ͱ/���/�
ְͰ�+�ͱ+��99 �� 99��	99901%5!	463!2#!"&!5!  �  � �K5�5KK5��5K���� �����@��������5KK5��5KK5�          	    %5!	463!2#!"&	''  �  � �K5�5KK5��5Kl�Z��� �����@��������5KK5��5KKE���Z���    ���    �/���/�+ �� 99901	!2 '.#!���@�� �,�J�d�  �����������������ԖJL��   �� �   �/���/�+ �� 901	!2 '.#!�� �,�J�d�  �������ԖJL��           4 �/�3�Ͱ2�
+�@	+�2� /� ְͱ!+ ��901!2>323!!".#"  �#93>lGFoKKoF � FoKKoFGl>39  � � &&&&� &&&&    �    K �/�	Ͱ/�Ͳ
+�@ 	+�2�/� ְͰ�+�ͱ+��
$9��9 01!!!75!!'&!!  � � � qp� pq �   � �������qqqq�    � �    M �/�ͱ22�/���/�ְͲ
+�@ 	+��+�Ͳ
+�@	+�+��99 0146354   2#!"&!54&"�K5,�,5KK5� 5K� �Ԗ��5K��,��ԀK5��5KK5�j��j   �     U � /�Ͱ/�Ͱ/���/� ְͰ2��+�
Ͱ ��ͱ+��9 ��
9��9��901!2#%3264&+53264&+  j�9Sf�����PppP��5KK5�  �jWH'�^���p�p�KjK  � �   M �/�	Ͱ2�/�33���/�+�6�=�� +
�.�.��	�����	....�@ 0173#7!#3�(���(�(���( � ��� �  ����  	  B � /�Ͱ/���/�ְͰ�+�ͱ+��9��	99 ��9901!%!'264&"� �� ��@��KjKKj� � �@��` �jKKjK     � �        �/���/�+ 017!'!7'77'!'��暚������f ���������� ���������� � � �暚�������  �   �   �   +�/�+ 013	�����@     ��    5	��@���    �@     	��� �@� @ �     	@�@���      ��Գ@_<�      ����    ����                    ��     ��                 �      �  �      � � �          � �� � �  � �   � � �� �  � � � �� �   � �      V       � �� � �    � �`   �� S � �� ���  ��   � � �  � �         �    � �   f        �  � �� � �   �@     & & & . � �Z���:�$�P"p� �b�	�
x
�6�� �@F�0�Z���P���\��`�(J��$@l��6�.r��6`�&�   R t � � � � �!!$!�"8"�##x#�$4$l$�%R%�&&:&X&v&�&�&�'t'�((�(�(�) )")L)�*$*b*�*�++d+�,,V,�,�,�---       � �            �       
 ~  	   �    	   �  	   �  	  :   	  $:  	  x^  	  $�  	 	 �  	 �   	 � 0* G P L   v e r s i o n   2   o r   a n y   l a t e r   v e r s i o n   w i t h   f o n t   e x c e p t i o n   ( h t t p : / / w w w . g n u . o r g / l i c e n s e s / g p l - f a q . h t m l # F o n t E x c e p t i o n ) G e n e r i c o n s R e g u l a r 3 . 0 0 3 ; U K W N ; G e n e r i c o n s - R e g u l a r G e n e r i c o n s   R e g u l a r V e r s i o n   3 . 0 0 3 ; P S   0 0 3 . 0 0 3 ; h o t c o n v   1 . 0 . 7 0 ; m a k e o t f . l i b 2 . 5 . 5 8 3 2 9 G e n e r i c o n s - R e g u l a r J o e n   A s m u s s e n W e b f o n t   1 . 0 F r i   J a n   1 0   0 5 : 5 3 : 5 1   2 0 1 4       �� 2                     �    	
 !"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~uni25FCuniF100uniF101uniF102uniF103uniF104uniF105uniF106uniF107uniF108uniF109uniF200uniF201uniF202uniF203uniF204uniF205uniF206uniF207uniF208uniF209uniF210uniF211uniF212uniF213uniF214uniF215uniF216uniF217uniF218uniF219uniF220uniF221uniF222uniF223uniF224uniF225uniF300uniF301uniF302uniF303uniF304uniF305uniF306uniF307uniF308uniF400uniF401uniF402uniF403uniF404uniF405uniF406uniF407uniF408uniF409uniF410uniF411uniF412uniF413uniF414uniF415uniF416uniF417uniF418uniF419uniF420uniF421uniF422uniF423uniF424uniF425uniF426uniF427uniF428uniF429uniF430uniF431uniF432uniF433uniF434uniF435uniF436uniF437uniF438uniF439uniF440uniF441uniF442uniF443uniF444uniF445uniF446uniF447uniF448uniF449uniF450uniF451uniF452uniF453uniF454uniF455uniF456uniF457uniF458uniF459uniF460uniF461uniF462uniF463uniF464uniF465uniF466uniF467uniF468uniF469uniF470uniF471uniF472uniF473uniF474uniF500uniF501uniF502uniF503  ����� K�PX��Y�F+X!�YK�RX!��Y�+\XY�+   R��?                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              W:  !9                 �   LP                       @���                   G e n e r i c o n s    R e g u l a r   x V e r s i o n   3 . 0 0 3 ; P S   0 0 3 . 0 0 3 ; h o t c o n v   1 . 0 . 7 0 ; m a k e o t f . l i b 2 . 5 . 5 8 3 2 9   $ G e n e r i c o n s   R e g u l a r     BSGP                 W� %� %� ����`�W�hKqJx"U:r,/�4\��li����ʚ�E�LFM��պ�C>����.�"H��,�&�S�fZ�}Vo6�c�euP���1��R�j�-ŷѠ�A�Yw>{=��dBU�/L����o���z]��������9�Y@k��՗_5b]Ak�	��=����~�$� /2��X.���ȳ�׏������+,=f`�ڟ;I�ď����!���n������X2#�9ɑ(0dNI�ACxW��[ ���|Bg���n�p��;Ù}a��4p����!�s�ʵ���!"��(\�AœX�b� UǾp/+�Bu�P	󸰔Yx@E�u�.,�k��r:��򴥯�J�o��R�Uxk(���)]g	�W\�с���0�7��ST��p**f��84[�]0(*d a0Q�`I�w�fE�*)�E��XȨ�^���o���$袶�2�5&�腇����>���;��d����\@Z�u��e�i���p� ��F���p�E��wF�ғ~�A� 8Z}�����T�Z�V�S9�oڱ�eַW�x�{������(��� �F��ީf��^�]��T�  ^�ᑆ������bTɻp��G^�e��x=�I RBEt��w�Hyz!˞=�Ơo͕��͊�1�=�h���䊐�f"��倝Fg
��:	�?䕓�?F2G;���O�So�AY�g4�G:��Zͣ@<t��A]�>B si)3su)��H?�1EN�P}�_GpOa�vE��Y���=�� �?�uo����pcHpmT�3 �!]��SP�#0�E���	y`��$�?ܐ6(����3�a[/�%z,mWpޖ��;�C 4���cH?�[З�fG�N���8�A�:�"_���8#-;/�4�ƼW��1�E�/���I)�@���'���2�o3�V������t������{��F�KC��o��d�h�2$�)��ēn[���|
*��&w�$IXI�Z~��I ��mw��Zl�1��C�"P7i<��8�M;���fm"�&�Պ��5�p;�G�}�� �(:^K���a�������J�-���3_ H&��L`�P%v����AԵE�D,�!B ��������n��ʰ���8�5�*z��x��+�o&	�U$~�e#G�¼����Aϊ�ȑ�����t=`F5l��P���Ow�j(6��\���F��Pڭ��+�Yȶ&����c�Q�h�N� ��Ɓ��q���B�h���!tu�����`�M��^^�g���P�{M�	Ą��(,$s9/�p��� �2���R*%�l�1'�Nz�X������V�Y�V?�B�<��@e��&�!Rj$W�M����MTA����Dx���	 �U�wP��4X���'�jv�#�����t艾[9|>�iкfVٙ��ܗ��(BH�f�6lOe�W�6�'	|��FV��C��(q",#����15��I��M~�_���s$����ϵ���Ⱦ���(�ǖ����U%(y�Z����/��c��E��g2+�tdŅ������:�w��cO�8t�C�[B�@�]��>�r�к�����0�_�`�D�W�	�ܼ|�b@h�-�na 8"!��z'�Z&��N��+�M�n�u�)�x���%�48�ܒ�����9Q��l5�2p[l�m���A*�"�Z��E�L�]b:��(Q��%p�� �^��I�δ�� �K~W�E�Z5]��	ʑ��p)�S�{qr�T���R�6�<PGw�
�1��|_5�8~�e���\~� �����p��X$�4���sP���VI��D�&k-h�;(\�Jf��h0�.�}��/g��Xʀ��i�8��B.���r���(h"}��σ|�+@�m��K�+P�#�/��q�a��|^�P�o�0��NZ�#zl�}���iak��I��x�(v8X:����;Df�P�%~�p�qCUA�Q���Y��vT�A�Sr�#9�bɱN=�O��}��Z�&z���	��U���T|��8�e k�r����a�c5��}��g{33p���m���ĉe�LJEh��2�p�\&O&��V9ۏ������V_��5|Ȧ�i��c)�!7�GJBI	� ��"]��h؍�1�?�d��E{Z����؅PJj�Y 4��ج�r��P6��n��z�zETR7��f+��_]�OJuIeϡao �|��o���=S�G�:���s��r���4�B��Qʹ����7`t��I���إ=�P�eXi�|�����C)���v@`nD��d����"Ə��1�W*>����X	�A����JN���O����U�y3��ķ� ad�ќ��&彈q=����)	���p�#LB���܍�׷bP��6z����qFOS'�m��m����s�8��L�O'pG���-a��s��	s\�m�	����c�
���+9�1X�9��J�� ��!��ؑ���H*��Fv�7K�]Z�u�ꁧ��<z�"m�̜:Z��o�<��.YH��c���ݤ�Ii(�O��E�=ZK�r��Y�<Z�]Yo�bE64ɠ<;�������D����\��'Yt��#}K��XW_�Ě���ݽ���>�)Ob4|�d�q��}_�~�hz�i��P��_�%i����|���^�����{��u�Hh!I�I�>@���������¿�Ζbk�yf���1d�!�"��\?�X�b��_���ݶ���#�c�@�e���B*2ۤ�˻���<.{��� n7�K�b�4�"�	���Q�>�g��P��ɜЫ��Օ�3�A���.��*�D��4�#�BBX�a|[1�q�lP��A%�>��X�U�7nh ?؀�Q�QŦ���5N>4��a�U�.(k�،�g��%��*6	ۻH�rQ: $-��Ϥ�Ow˥�yGf���y��������ذ�X$뢡�}r��$<����z�8�����ӊ@���\sжm�=X�O�����T��Х��J�R���
7|X�PWE�a 8��t��̇�[P�7�&m������S��Z4)��e��e�k"�ظ��Wyk,�̀r� v~�-6W�ώ�Gs�Y�qّ�<Fч[E��oWhHM��è�-��
̑���[L	�� �Z�g�0*��}eΠ��*a�B��#åD��ƒC(� �<f"�p-^��v�me���M�r;�f�N "���'-
���$�Q"�pL��Q�@m����4EV�a[ W��r������! ��c`�M�G
�*�5j��^��Tƈr��e�H�	6S��;+�ǂ���<�D�泲,8b�Z�y0)k���,E>7����ƕY!qW�,�L�k\Ƴ�QX��@��̑�t�y���)��cr��[5�B?槓��ꜻH������8� O 8T���(������.���)��a�l	��@Iכ:NU��� �zy#{s��Q�� l�Om!!k���N�uVĵAOo��� ϴ`c"Qc�������<�<K�k͖�3& �R�2��Vb�A.it��	 �y���p/��h���' ���,ה#(͆N)��M�&�ys�H�{���-*�N�L�Y�, ����5*1oQ*�~����A��z#����]hH|��+�������䐫BA�J�t?=:}*��˨�G��l�7�F�,e?Uv6�� �G�Y?����i*�!ŭIYL��j.&�(p'eݺqz�fIቾ/���Q�H	
���m�BX���ι��2���x�F��D���X-|�4œeBG��z���X;Dl���!Y����Yx�`��=��}+����G�q���tZ�p:{�����(b�H��n��
sw]�cb�2��gB.��muQ���P��P��{����ABVQ;�*V(T�5R�md[�XN���;�ge��С�J��S�1��Ҭ'�h�9��� AWR�h7Hc"h��h���Ѽ�B���5�}ːԤ|�>V	=��I�!Nd��֕�<���B؃�~�J��C�3�����`G�<��Ɂ�Y��� ���x��#�)�:��c���d͛%�"nC�Tݧ�5��a@-��n���/��[�k~R$�d���.&w�U �B����w��o��)-'��Z��*s 
�QC�*��ε�XT��f.�ң	]��fM|hu��8��%K�&�J�}��
x��8����R4kH1��S�E��*��9":7w��`�^��*	Q�нn����me��ux8�Q��N���	�}����;� �x�,q���ω�MEe.��A�����w�ܰp� -T���ΌQ9t-1�4���Nʦ^���0Ws�7����|wRz�_pN��f��wy���P^n�}/�c4}K��V2�a�|�PzrZ�Ae�>}��"��Lt��F����.C��b��c�I�G���!�� ٔ�����T���ȻL��?�����+�/�G�.AVF} h�aޖ���dK,�T�;�E�z��U589�C3O�eU�S�_���Uq��0P�O��2��PAc+��Gi�e0[�x�F.��"I"���s��Bd��~�(`nǥ���"�PU���1��t�P�mr0^�}M�D�H�ԕ�X6#YS��	�Vj��W�	r�/��`+]G�pb>e*��<�S�(��L�x�-9�L	د��0����^U�.=F�9���Y&`	�Df����I�Nn���sG�:�:o�!
�bo�ڃ�, �E�ĜN�l� 8�� l֒p����p�/ڼ�Q" ��7��w�'vQה�Ð I�Rm@���o�$����
�g�I�鱅u�"T�+�u=��~po�@���'|�U����03�~z��#���U�BwajYy�jS��e���cG(�$ae!pc���HV�j�K+$��{����c���T-�6�W=��}�����n3d��ި���^ ���zK����e$)ݧ����\̪2��̸�V�>�,���3&$q��WY�d�R�\�tY�j�\���`�Nt]�.?��T�j���`��+ U�R|n�4�Ϯ��(`!���akk�r��	��䆥
��a��
���xn�fJ&ȶa��0*���Zwc��{0Q>�3����x'�s��!!����Z��y�n��~? �VJ��Bk�f§l��D�d�K+f ��6=<���"2ـ@t�6K��Sd�H<���^}&���3*��	E�〷��27���\�ş��lK��d�R���I2mhqz2<ʰ,Tx���ɀ���JM�XS�z*���V�i�܊������w�M��?Ex!y���|�w�0��_��Hg�/q�l�'f:N�@S}��L���@��ha�)(1P���o��W�A�sb���)1E
�M�N�fy���}d$E�wn6��}$�;3��X���9Dm�
P��@�*'�B�!�M��S����	g����Tb�U�nff���˱���D���<��55�b�rK�@T��포*��0+��-��|�����T�-���������w�ʁ���<��rE��e\��:5�A��D�1p��~�D6g(��R��Od8�V����&&��Z�w�
���շ[��ed¼���4h�b���V�AR�]P���������.�>Д�`�B�ɨ����@%tg��K(��,�5�z�p�0{(r���Br"!�?ux���`O�c)Z�K��S���ӻ�_�o�0"^
@�[�8��E�n S���,��FA'��Q����`!AX4P��u�S�tY��T�YŊq~*H��:��[�kz�H��
E�5��%1%mWm������m�p���2��PNv��wb�B]	eo
���0�%I�Y`��>6���Jdt�y�aϊ,n�29��s7��)?S���6OiZ�Y�X��f� �Ð�g��T�NoX�1��w�@��{q-_�g�'��[F)��04I��T%�ĀC�� �br�+��M&&��==���]�ZC�������!D�<��x���`�'� j:B (܂"��Ņ�'�Yk҃��ʔeV^� �y๜$;��t�!Ԓ�L~2G�}k��c�c2�I��I���4y��P����!؈���1�f6 ,���c,�r(º$7౦�'�5��8PX��&�.���@�!_xYU=�x-V�l�KM����Ϙ+|��l�$�!�gA4T~㤗3����C�kC�ro�,e&�Z�Y7�SI99����2�^Y��
�q0z���ʙ#:�����#b� ��������Z��pn�ˏ�G�^F�?S�k�1N�;�źI�6���ҁ�ȑXU����1u��z<�3�� ����ż���' g8����œ�,��ZGf���VT�P�}[���%�D��n�bv�L8|�iN�Ngd�����=Y�h)3������΁0}
(�{AZ���6N�8�֧ C,pl�'j��Hdqh�bi��'�̣�L�������R D)"8ǹP��Z+��k(�����ޠom)����ʅ�L�;o�Ct�|�\���7H����hHs�%�g�%]�܉ ��+�C���W����b�dV3�)�0�?�x�
�������Nꆊ�V�s���4�V�����L���P���?�U���ND�q�ȝ9a�ֈ���`�>F�����n03V��PT;^���N���)�!D�>\��uG��r9K`ͻ
K�?�����qT���g]GRrkHd��aV^� I�Е�v�,��R�����,�e<,��s�)6�F�B~�O"����JE�n�+��F�Cr%)l�]�"A�ކg����+3a�S׏��v��\�������_@��a�~56RO�βi}FS�
bQ)�Ow�*�'x6���M͏��:������|��m��`���8"l�Y���%>n�"����؟�l4�rf��˳,��V� ΄H3�QV��&�(���/�2U�v�g=�:�o�
YlA� �ey���oqS��`%��a��P}��C� �M�Q�HKX�/�N��I�՜[[D��4ݑ0X��jYM2=��!؉�D�9zMT%��s�����؄$=ZQ�&�L{�,��S���݄z��pm�O@wk�ɖ{D�wl}�)if���gN�Kd�L(�/���ƾ�  (��`mb�U�e��9`��Yr�p�y�����&�!SE�V&5��0'���*|���l8��	�|]]%O ���(��D��o7���c��e�n��TzI�A!�'�X�T�SO?���� VV�� �m'��?���Fy�7F�]?k33�w���Ő�J4�'�	��;\��hJㅈ�YT_��!-�_G���m�T���@,�C-Tb#C�5�I� fWr'nޙ�5�,Bc�{��Pr�Ѝ.�mS&�B�ф���+#sQ:NjFHS�E��L/l�D�&� Ąd���]����D��-�z(��D���*�<is"\R�><1q�	)U��`9�����,��F��|��~�âa��Q(! �?�M��� �!���0�M��a�ԡ_Pus�Bd�)�Ry4 �^�����$���܀&��.��e.�:n�b�F��
��=W${��O�s�e�wS��%�~1��)���r$'^,�iY�Sb���&
����EsBkK����^�&Ξʽ���=��1��1��$^�v��]��D~	��r	!Zl�:	�C|$f�W+�Q�ta-�~ƬT�����C�SxY�h��_�P���(�ز
�U�G�f�����:�!�����2r��|U�eE�4�a��='L�0Z����P�X�-���&�u������m�D��.����ɢ`�A5�����Zw���vGJ�3��5 d�57iJ�^�rq�2�xQ�X��7��k/��`Iҟ��1"�tK92�1��	� ��m�b%���Sa ���GHVb}H#"�!���m1�&>�+Ð#@�
$!��j����C�tg�����"q͕�|�Mn�B[�
�L$1��W�D�� T��mb�%ZM��!�=��n� �����x�Ι�Ϧ���)���P� VbA1x[�UD	&Y�M]`�h��,��#�IT@�Ԧ����w�ԟ��<y0"7׷���&Ao�I��f���2�����9o�� �V L����T�Cn<�L;�o<��'�̔�T��9H����7��r6R"'����9�P�O���v@{ƿW�N�T̤XM�H�QY�����/{�|�ߛ\�!�������sebUV��~�Q�%�
�l`���{�v5���Ӕ��{�gT�=f3#��	����@S��qx�M	{Z���' ��c9?v��rѐF�&pw"���6A��wBo��F��g�U�J��1l�'�l��@QwV�+|�f��iM����>��A�w�ñiN�S��-�H� �J�Fd�.I��ps�T���Ҳ4�5i���p�x4�8TAR�WQ�n�!+ �M6��nE9�<���HG�L�g@E-[�p#�������
e �,���8�z��j�O*`2�A�q�5�3~y�ReO��z?�Q�BL�r�n:<I��&��I�6d)۶Q9�~��$��@�[�[.p��& ��b�=��̍�Qݘ�ƈ�{���H%V}�}xR��"�6�g�����_�v�8Ca�#�I՞�	�m�ZvI��k!��h6J��aMB��DL�H>���l�m;	���ʰ�2�EU� �I��"���%��PN�ƍPqQ%���B��W�{����)�p�Z��hP��ؼj�nM���k�DQ/��D��{X�X )���w��:6���i�BJb$MWHߚ�Ht�ڪB�=Ob<����A.yo��b�(�"~�U=na�$ (?�kc�e�N"��r�8��i�Ԥ��8\ !��3	�x��y��p�`3U$g���@U��k�c|�"�Ҡ0�o�=�� ��3��E�m��CԎ�w#"�͝�\`y�LS`����fZ����e�VM\Z���պ���
��h�5=<���t �8c���b@��AG�� �ǈD�GTFUF����	�U�H�v1T�EYl
��]�*�DUp���LW��3�]W¹k��Ҵ�f+�U툩z�RE���E�
�!
>��p�2�oL �I���Z� ,�  `�(��(�h$�N�@��!6$!6�k�JE�=��k'��ɨ�P�&��k�4�ri>٤Z��+�hn�W�]�n��FʠvU��u`*��UV�j��U�j��UD���uL�Q_���^�U��]����\.պ�[Mծ�P+u@mU�j��UqY�n�[jγV9����A)T�R��F�U%T�^�j�#WI
����T.uP)�|���j��uÂ�թ]H�O}����@}:�M'���yZR{��[�%�mg'P��1�����9w8Ϻ ˀ�     ���δ�,�*���mx�m7+.��9Dq?\�v��dS��*mAO@;����1��JۣQY�x��h��H�>�Lu �ܞ*�,H��Z).]$+ގK�Q��n0��&��෪��#GѺv���Iŗ���ʶ���3,F��u�4��D9�7�ǲ��r��L�c��FrHx �Y�(M��ܷ�@0�r�&��JB>Q��UQa�0hB)�L3�C6��`����s�<p�TG݁H^Fz+�K�̓��*�M�%�j5�H��NX���������Gߋ��,��b��,�j�$ EA "1AF��V��d.?��?9���B͹%sUm��"��I�T:o��Ō����YL(蒖�mr%0"ق�`��*�ZҍM�����6+N�<���-PfȔ�=�mT��N@`�?w.�b�t�^_�X����T�h�B��	�#�	����$e`xcq;~AC�����h���Ƿ�IN�itPY�P��Ѹ&�ɵ��I.�������``�x���%�`����Az���\!�*��.��{ˋjUM�N����R�{�Hp�fh�~Q(g�J��#R6��b�\!����c^��W0(X�ծ\{�#���L����f�'$��u\HY���O��\J��~	��з5P��ݛ:��ٽi#��J�R6|L��l	m�<F��շB� ����1,dڐ����)
���(�^S�B�����M�a&�b��E��k��Ȧ��D15mE�0ĝl�t+}��n��D���gT!�����2N�)E�����ˏ���S��A@��&
TX⋐�x �>����p����zqY.���8Ht����*}��a�𬂑o	�L�88f�=���Z�_p&/ T�h�Q��)��}�>|���c^>V��Q,B�Y�F�l��"`�6�Z`c"wu���xi�fF�`k��*�x���T0��z��)�8�.���� Iy.��^�^���6���i���Ȱ*N#�6C�P�m�v(P\� `J��L	9��P� �@_RM0�����Z�W��=�C�F�4ǣB�S��Xg��b'p��&��D���0S�*�p,z�cI����3���h�A1o3���!w���q �ζ�M�!|[�QO������e�x`f��u�C�|����3���\�cL���!���R�Ā�Gސ,��vp+��G�����D+7�����&��|&o�V�x�J��Sv���E)K��'�zwf��Í����յ�����.��M��0��*1�Y�Ɛa���H�g*����@����Bҏ����+�wD��d���`}^V��7�l:�˪>��Ժ�`7J�1�K���?[v�j��w�/]1l�������	�ǌ��*�Δ�9��-�9�����	�3DiC��\�5m���eacR�/��:{�
J Eߦ�E�DH"����2��(?)-3���Jb���&�����{z�g��&I��c��\��4*�Qĥ_-�;>�Kś�?M$Wp�����C��U�cYL�	�VmA$;�0b��m"��YwtD'T;����UځЏ�a�M?A���~�����du	�"��]׋x|��Fo-Db�h�$5���5I�����*�!ӑG:���L��8 S*��n���Et��J��LT� ����$%�@�Z��|KگD9`Y9j8R��Of��^�� ���9����gκ"U���>�����,���{��m��D���|���!�U|*��h���֬����$�.l����h+�^#㳭d�����P/�"�U���4�"<�CAQ���8��j30�z���]N�q`ݵ��ӑ��>.��>w+1J�:0��at܅�@b@ &ca���6X����'3B3ħKT`�e.�,�#���}
IrcSxR��.�$j�� ]�e"Y�Qj��JՂh��#���Q�:����*d��
�>���9tY����A�6Fï4u�C��#Tn��`�ە�.�2�$�Z ��%F�ϛ���̞�`)�"$QvX*^�O�Bj4.0��ƿ��?�5E��5�~�fk��3
�w��Y�̥�|OW�r����;dYhoI!Q���_��P-$`�2ڰn�Ҧ��<)��>H�C�a������1
�xq�d#`��/�G���h��*�6y�Ũ�M�iO���B2�4޽��f���,V��R53�&�Ɣ�=4΋EN*�i5�v�-}x����>�7���������k:�ѢR�+�(��[��s �u#��D�Ce��]��G-���]$�x+�R��_W�i�<r�9�V����M������x�N;�ʀ%�t��% ��[҈�]����fǠ1hCnD�!$����k�d�&�r���0G�s���ީ}�~,"y�� �'�Ao�u�/)R�&g	�@�W�T�?j�d�j/i��?�>.e�!���"&
\|3�8�o�ܓZ ܖ�n�bK�g�$)No���33���#��_j�-���b�����Fሟ��xg�rt��.����w�d�a�[�M1�Xt%+��M�M��C$�����ֵ�y��ā�%��XFs���*B��n���^R�鏬 1%�8��H��Ĉ���l�>u@k��*�Ǿ��fh�̈�<���%��Vd��)�rB)N� �ux�M�Q��uZ`�V��FkSHܮ��3<W":fI�
���	�ⱦx`����y�6�wUB���?*zY��R�t)�RK��06+��q���D6�����"�����x��=G�1�E!?=����2�VQI���E(@���ˡL����1��<�n;�8s�Y�m����R��wx�1��
t�:�Q��Ҫ��0GE�*�p��#��C�`�G���y5a��υ�ؓ��o��<�%�U`v��B}_���o,���H���nP���!�SOK�̝��.%��ם��v/|�O�m�c٭fp��9̯�7HWUز&���&�H���S�y��P�0_�)�y�Y
����N����U�(��k�H,�זO��P�ڂǥ�&�i_�����>�<��F�PV�]�b[$(!{����@�)�f��3j�荲��涳0��N��n`B���Q���i:�H��i �_a����q�u־�9_I�����?.}�ר��GK�ziP���պ0�!�C,��{�v���MS� ��ݙTN.r︺��.y��gS$�d@�/�<�(��k.�I�H�"A1�X�f���"W1�ҁ�h�oz�TۻJ7ȋp�X~D1f�w�d<����7���ihUU!���:�fS��Î���K���ى����ٺV?\�ԃ�jv��y��?��W����U�54r{E�ڑ[nn��˚2��>�RuHt�{��9]&|r9�WBb��c�ӥ5�Χ�,dv�u$4x�Z�`!I*8�K���C^��[k���@pVy�-3	94m�^� z�b�<A� {�K)�V}��S��n�:CSZ�B&Y@te�g�C�ҥ��f�ux��H٢��f�)@Mɻ�^��(k¬@+!^ fiK���Cs��~"�i� 5���E��W�W�;�3���h
���>�"E]xM�m^�1�bI�P�!(�R��27Z�"wUP�� 
n�:'�E{�X��'[A���d�q�5��M�X��d_���8�[�:��`9�<�� I�J7'̓5��	��R1��Q�"U.�(��ܧ,(K�Kx$*dD�
_A?������+LH |�b�����Q
[���R�B�P!b!g�BcF`�m��c�"!���}bh �@߀�-3�1��&g�ɵ�[.P�t�XNS��f�>4>��vX�	�w��6"V�RP֟p���X���@+/$M��uOb�R��c��v=�-$폼� p�p*�d㸎�)�/N�/�c����� �R8�p-EOn�&�/'�hCWL�R��oS���Rxap�"��V�q-��Hea#-w������[�)!��ˈ;�����yo�3� �4d|Lrj�?'��Ŀ�	*+F\����C�Fw�^������E�"	��0p(X�]���Sߓ��H-���0Lւ,�q:J��SQ��G?�vc�g���a q��B��A��e���Ԧ�99[ͼOb��M	&��`��4L�*�}��W�ڜ�4��P%H>AL��Z��A O����k�'���8	�5�P�Ȑ����QYP틟��5�_FK�jG���(��e�y��.G�v������@�{���/� v����n>kCژPp�la��@k��6�	b�"!�ܑ���e��YM��`(G�y#VKȞ�TGi�����e����F9�T��9��EG����z��"�t=4,o�B5n���@NE�����&@d�j8�����N�SY��$LhGR� �ys5�,2.�G�x� "
�Q@�
�4ifåC�ke�J��t����0bD���^d�"����r)�I��N>���,�Lr+wY�2?�${���Əf�VP�.�d���}�q&�ŕ�z,)�읖`~M�\� �Ĉ����[���gxs�l�qfϜts�"C����rD�8���lҀ��m/#-�J�n^75�J�UgJ��yCTV��Fo`�?�@"#�U�
)�v��| �@�Bز�"'<b6,�������L�-	D��C�$Flh���� �<Ѷ+4�l��U�yh�� CW:���Ĥ�k���bz�V�էQ��Zuc�Y���Y�����h�5�h��5��d����$��5Ӄ��3�3�3����G�=�a��^�. Bᄾ�Ǩ�Hi�PW�RGZ��G3�ם� �1�*�0�7�T�y};/��                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             GNU GENERAL PUBLIC LICENSE
                       Version 2, June 1991

 Copyright (C) 1989, 1991 Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 Everyone is permitted to copy and distribute verbatim copies
 of this license document, but changing it is not allowed.

                            Preamble

  The licenses for most software are designed to take away your
freedom to share and change it.  By contrast, the GNU General Public
License is intended to guarantee your freedom to share and change free
software--to make sure the software is free for all its users.  This
General Public License applies to most of the Free Software
Foundation's software and to any other program whose authors commit to
using it.  (Some other Free Software Foundation software is covered by
the GNU Lesser General Public License instead.)  You can apply it to
your programs, too.

  When we speak of free software, we are referring to freedom, not
price.  Our General Public Licenses are designed to make sure that you
have the freedom to distribute copies of free software (and charge for
this service if you wish), that you receive source code or can get it
if you want it, that you can change the software or use pieces of it
in new free programs; and that you know you can do these things.

  To protect your rights, we need to make restrictions that forbid
anyone to deny you these rights or to ask you to surrender the rights.
These restrictions translate to certain responsibilities for you if you
distribute copies of the software, or if you modify it.

  For example, if you distribute copies of such a program, whether
gratis or for a fee, you must give the recipients all the rights that
you have.  You must make sure that they, too, receive or can get the
source code.  And you must show them these terms so they know their
rights.

  We protect your rights with two steps: (1) copyright the software, and
(2) offer you this license which gives you legal permission to copy,
distribute and/or modify the software.

  Also, for each author's protection and ours, we want to make certain
that everyone understands that there is no warranty for this free
software.  If the software is modified by someone else and passed on, we
want its recipients to know that what they have is not the original, so
that any problems introduced by others will not reflect on the original
authors' reputations.

  Finally, any free program is threatened constantly by software
patents.  We wish to avoid the danger that redistributors of a free
program will individually obtain patent licenses, in effect making the
program proprietary.  To prevent this, we have made it clear that any
patent must be licensed for everyone's free use or not licensed at all.

  The precise terms and conditions for copying, distribution and
modification follow.

                    GNU GENERAL PUBLIC LICENSE
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

  0. This License applies to any program or other work which contains
a notice placed by the copyright holder saying it may be distributed
under the terms of this General Public License.  The "Program", below,
refers to any such program or work, and a "work based on the Program"
means either the Program or any derivative work under copyright law:
that is to say, a work containing the Program or a portion of it,
either verbatim or with modifications and/or translated into another
language.  (Hereinafter, translation is included without limitation in
the term "modification".)  Each licensee is addressed as "you".

Activities other than copying, distribution and modification are not
covered by this License; they are outside its scope.  The act of
running the Program is not restricted, and the output from the Program
is covered only if its contents constitute a work based on the
Program (independent of having been made by running the Program).
Whether that is true depends on what the Program does.

  1. You may copy and distribute verbatim copies of the Program's
source code as you receive it, in any medium, provided that you
conspicuously and appropriately publish on each copy an appropriate
copyright notice and disclaimer of warranty; keep intact all the
notices that refer to this License and to the absence of any warranty;
and give any other recipients of the Program a copy of this License
along with the Program.

You may charge a fee for the physical act of transferring a copy, and
you may at your option offer warranty protection in exchange for a fee.

  2. You may modify your copy or copies of the Program or any portion
of it, thus forming a work based on the Program, and copy and
distribute such modifications or work under the terms of Section 1
above, provided that you also meet all of these conditions:

    a) You must cause the modified files to carry prominent notices
    stating that you changed the files and the date of any change.

    b) You must cause any work that you distribute or publish, that in
    whole or in part contains or is derived from the Program or any
    part thereof, to be licensed as a whole at no charge to all third
    parties under the terms of this License.

    c) If the modified program normally reads commands interactively
    when run, you must cause it, when started running for such
    interactive use in the most ordinary way, to print or display an
    announcement including an appropriate copyright notice and a
    notice that there is no warranty (or else, saying that you provide
    a warranty) and that users may redistribute the program under
    these conditions, and telling the user how to view a copy of this
    License.  (Exception: if the Program itself is interactive but
    does not normally print such an announcement, your work based on
    the Program is not required to print an announcement.)

These requirements apply to the modified work as a whole.  If
identifiable sections of that work are not derived from the Program,
and can be reasonably considered independent and separate works in
themselves, then this License, and its terms, do not apply to those
sections when you distribute them as separate works.  But when you
distribute the same sections as part of a whole which is a work based
on the Program, the distribution of the whole must be on the terms of
this License, whose permissions for other licensees extend to the
entire whole, and thus to each and every part regardless of who wrote it.

Thus, it is not the intent of this section to claim rights or contest
your rights to work written entirely by you; rather, the intent is to
exercise the right to control the distribution of derivative or
collective works based on the Program.

In addition, mere aggregation of another work not based on the Program
with the Program (or with a work based on the Program) on a volume of
a storage or distribution medium does not bring the other work under
the scope of this License.

  3. You may copy and distribute the Program (or a work based on it,
under Section 2) in object code or executable form under the terms of
Sections 1 and 2 above provided that you also do one of the following:

    a) Accompany it with the complete corresponding machine-readable
    source code, which must be distributed under the terms of Sections
    1 and 2 above on a medium customarily used for software interchange; or,

    b) Accompany it with a written offer, valid for at least three
    years, to give any third party, for a charge no more than your
    cost of physically performing source distribution, a complete
    machine-readable copy of the corresponding source code, to be
    distributed under the terms of Sections 1 and 2 above on a medium
    customarily used for software interchange; or,

    c) Accompany it with the information you received as to the offer
    to distribute corresponding source code.  (This alternative is
    allowed only for noncommercial distribution and only if you
    received the program in object code or executable form with such
    an offer, in accord with Subsection b above.)

The source code for a work means the preferred form of the work for
making modifications to it.  For an executable work, complete source
code means all the source code for all modules it contains, plus any
associated interface definition files, plus the scripts used to
control compilation and installation of the executable.  However, as a
special exception, the source code distributed need not include
anything that is normally distributed (in either source or binary
form) with the major components (compiler, kernel, and so on) of the
operating system on which the executable runs, unless that component
itself accompanies the executable.

If distribution of executable or object code is made by offering
access to copy from a designated place, then offering equivalent
access to copy the source code from the same place counts as
distribution of the source code, even though third parties are not
compelled to copy the source along with the object code.

  4. You may not copy, modify, sublicense, or distribute the Program
except as expressly provided under this License.  Any attempt
otherwise to copy, modify, sublicense or distribute the Program is
void, and will automatically terminate your rights under this License.
However, parties who have received copies, or rights, from you under
this License will not have their licenses terminated so long as such
parties remain in full compliance.

  5. You are not required to accept this License, since you have not
signed it.  However, nothing else grants you permission to modify or
distribute the Program or its derivative works.  These actions are
prohibited by law if you do not accept this License.  Therefore, by
modifying or distributing the Program (or any work based on the
Program), you indicate your acceptance of this License to do so, and
all its terms and conditions for copying, distributing or modifying
the Program or works based on it.

  6. Each time you redistribute the Pr