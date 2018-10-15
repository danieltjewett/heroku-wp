<?php
/**
 * @package Zombie Apocalypse
 * @since Zombie Apocalypse 0.5
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run zombie_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'zombie_setup' );

if ( ! function_exists( 'zombie_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * To override zombie_setup() in a child theme, add your own zombie_setup to your child theme's
 * functions.php file.
 *
 * @since Zombie Apocalypse 0.5
 */
function zombie_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	// Support title tag since WP 4.1
	add_theme_support( 'title-tag' );
	
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'zombie-apocalypse', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'zombie-apocalypse' ),
	) );

	// This theme allows users to set a custom background
	add_theme_support( 'custom-background', array('default-image' => get_template_directory_uri().'/images/back.jpg' ) );

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '' );
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to zombie_header_image_width and zombie_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'zombie_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'zombie_header_image_height', 200 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 900 pixels wide by 200 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See zombie_admin_header_style(), below.
	add_theme_support( 'custom-header' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'blood' => array(
			'url' => '%s/images/headers/blood.jpg',
			'thumbnail_url' => '%s/images/headers/blood-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Zombie blood', 'zombie-apocalypse' )
		),

		'red' => array(
			'url' => '%s/images/headers/red.jpg',
			'thumbnail_url' => '%s/images/headers/red-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Zombie Red', 'zombie-apocalypse' )
		),

		'door' => array(
			'url' => '%s/images/headers/door.jpg',
			'thumbnail_url' => '%s/images/headers/door-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Zombie Door', 'zombie-apocalypse' )
		)
	) );
}
endif;

if ( ! function_exists( 'zombie_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in zombie_setup().
 *
 * @since Zombie Apocalypse 0.5
 */
function zombie_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

// Backwards compatibility for the title-tag
if ( ! function_exists( '_wp_render_title_tag' ) ) :
	add_action( 'wp_head', 'zombie_render_title' );
	add_filter( 'wp_title', 'zombie_filter_wp_title' );
	add_filter('wp_title_rss','zombie_filter_wp_title_rss');
endif;

function zombie_render_title() { ?>
		<title><?php wp_title( '', true, 'right' ); ?></title>
<?php }

function zombie_filter_wp_title( $title ) {
    // Get the Site Name
    $site_name = get_bloginfo( 'name' );
    // Prepend name
    $filtered_title = (((strlen($site_name)>0)&&(strlen($title)>0))?$title.' - '.$site_name:$title.$site_name);
	// Get the Site Description
 	$site_description = get_bloginfo( 'description' );
    // If site front page, append description
    if ( (is_home() || is_front_page()) && $site_description ) {
        // Append Site Description to title
        $filtered_title = ((strlen($site_name)>0)&&(strlen($site_description)>0))?$site_name. " | ".$site_description:$site_name.$site_description;
    }
	// Add pagination if that's the case
	global $page, $paged;
	if ( $paged >= 2 || $page >= 2 )
	$filtered_title .=	 ' | ' . sprintf( __( 'Page %s', 'zombie-apocalypse' ), max( $paged, $page ) );

    // Return the modified title
    return $filtered_title;
}

function zombie_filter_wp_title_rss($title) {
	return ' ';
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Zombie Apocalypse 0.5
 */
function zombie_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'zombie_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function zombie_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'zombie_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Zombie Apocalypse 0.5
 * @return string "Continue Reading" link
 */
function zombie_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'zombie-apocalypse' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and zombie_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Zombie Apocalypse 0.5
 * @return string An ellipsis
 */
function zombie_auto_excerpt_more( $more ) {
	return ' &hellip;' . zombie_continue_reading_link();
}
add_filter( 'excerpt_more', 'zombie_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Zombie Apocalypse 0.5
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function zombie_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= zombie_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'zombie_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css.
 *
 * @since Zombie Apocalypse 0.5
 * @return string The gallery style filter, with the styles themselves removed.
 */
function zombie_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'zombie_remove_gallery_css' );

if ( ! function_exists( 'zombie_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own zombie_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Zombie Apocalypse 0.5
 */
function zombie_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 );
		?><?php printf( __( '%s <span class="says">says:</span>', 'zombie-apocalypse' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'zombie-apocalypse' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'zombie-apocalypse' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'zombie-apocalypse' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'zombie-apocalypse' ); ?><?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'zombie-apocalypse'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override zombie_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Zombie Apocalypse 0.5
 * @uses register_sidebar
 */
function zombie_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'zombie-apocalypse' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'zombie-apocalypse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'zombie-apocalypse' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'zombie-apocalypse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'zombie-apocalypse' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'zombie-apocalypse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'zombie-apocalypse' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'zombie-apocalypse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'zombie-apocalypse' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'zombie-apocalypse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'zombie-apocalypse' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'zombie-apocalypse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running zombie_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'zombie_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Zombie Apocalypse 0.5
 */
function zombie_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'zombie_remove_recent_comments_style' );

if ( ! function_exists( 'zombie_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Zombie Apocalypse 0.5
 */
function zombie_posted_on() {
	printf( __( '<span class="%1$s">Posted </span><span class="onDate">on </span> %2$s <span class="meta-sep">by</span> %3$s', 'zombie-apocalypse' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span> <span class="entry-time"> at %2$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'zombie-apocalypse' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'zombie_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Zombie Apocalypse 0.5
 */
function zombie_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( '<span class="bl_posted"> This entry was posted in %1$s and tagged %2$s.</span><span class="bl_bookmark"> Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.</span>', 'zombie-apocalypse' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( '<span class="bl_posted">This entry was posted in %1$s.</span> <span class="bl_bookmark">Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.</span>', 'zombie-apocalypse' );
	} else {
		$posted_in = __( '<span class="bl_bookmark">Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.</span>', 'zombie-apocalypse' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

