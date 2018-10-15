<?php
/**
 * The Header
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Cryout Creations
 * @subpackage Zombie Apocalypse
 * @since Zombie Apocalypse 0.5
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=0.3, maximum-scale=3.0">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
/* This  retrieves  admin options. */
$options = get_option('za_options');
if($options) {
$zmb_hand = $options['zmb_hand'];
$zmb_menu = $options['zmb_menu'];
$zmb_tables = $options['zmb_tables'];
$zmb_side = $options['zmb_side'];
$zmb_sidewidth = (int)$options['zmb_sidewidth'];
$zmb_colpad = $options['zmb_colpad'];
$zmb_fontsize = $options['zmb_fontsize'];
$zmb_textalign = $options['zmb_textalign'];
$zmb_fontfamily = $options['zmb_fontfamily'];
$zmb_caption = $options['zmb_caption'];
$zmb_title = $options['zmb_title'];
$zmb_pagetitle = $options['zmb_pagetitle'];
$zmb_categtitle = $options['zmb_categtitle'];
$zmb_postdate = $options['zmb_postdate'];
$zmb_postauthor = $options['zmb_postauthor'];
$zmb_postcateg = $options['zmb_postcateg'];
$zmb_postbook = $options['zmb_postbook'];
$zmb_parindent = $options['zmb_parindent'];
$zmb_posttime = $options['zmb_posttime'];
$zmb_splash = $options['zmb_splash'];
$zmb_top = $options['zmb_top'];
$zmb_puddle = $options['zmb_puddle'];


 if ( $zmb_menu == "Enable") {
	if ( !is_admin() ) {
		wp_register_script('menu',
		    get_template_directory_uri() . '/js/menu.js',
		    array('jquery') );
		// enqueue the script
		wp_enqueue_script('menu');
	}

 } }?>


 <style type="text/css">
<?php
if($options) {
 if ($zmb_side == "Disable") { ?>#content {margin:20px;}  <?php }
?><?php $zmb_sidewidth = $zmb_sidewidth - $zmb_colpad;
		 if ($zmb_side == "Right") { ?>
#container {margin-right:<?php echo (-920+$zmb_sidewidth) ?>px;}
#content { width:<?php echo ($zmb_sidewidth) ?>px;}
#primary,#secondary {width:<?php echo (900-$zmb_sidewidth - $zmb_colpad ) ?>px;}
#content img {	max-width:<?php echo ($zmb_sidewidth-40) ?>px;}
#content .wp-caption{	max-width:<?php echo ($zmb_sidewidth-30) ?>px;} <?php }
?><?php if ($zmb_side == "Left") { ?>
#container {margin:0 0 0 <?php echo (-920+$zmb_sidewidth) ?>px;float:right;}
#content { width:<?php echo ($zmb_sidewidth) ?>px;float:right;margin:0 20px 0 0;}
#primary,#secondary {width:<?php echo (900-$zmb_sidewidth - $zmb_colpad) ?>px;float:left;padding-left:20px;clear:left;}
#primary {background:url(<?php echo get_template_directory_uri(); ?>/images/widget2.jpg) left top no-repeat;}
#content img {	max-width:<?php echo ($zmb_sidewidth-40) ?>px;}
#content .wp-caption{	max-width:<?php echo ($zmb_sidewidth-30) ?>px;} <?php } ?>

#content p, #content ul, #content ol {
font-size:<?php echo $zmb_fontsize ?>;
<?php if ($zmb_textalign != "Default") { ?>text-align:<?php echo $zmb_textalign;  ?> ; <?php } ?>}
<?php if (stripslashes($zmb_fontfamily) != "Verdana, Geneva, sans-serif (Default)") { ?>
* {font-family:<?php echo stripslashes($zmb_fontfamily);  ?>; }<?php }
?><?php if ($zmb_caption != "Light Gray") { ?> #content .wp-caption { <?php }
?><?php if ($zmb_caption == "Black") { ?> color:#900;background:#000;border-color:#900;}
 <?php } else if ($zmb_caption == "Gray") {?> color:#333;background:url(<?php echo get_template_directory_uri(); ?>/images/frames/Gray.jpg) repeat;}
 <?php } else if ($zmb_caption == "Bloody") {?> color:#CCC;background:url(<?php echo get_template_directory_uri(); ?>/images/frames/Bloody.jpg) repeat #CCC;border-color:#000;}
 <?php } else if ($zmb_caption == "Light Bloody") {?> color:#CCC;background:url(<?php echo get_template_directory_uri(); ?>/images/frames/LightBloody.jpg) repeat;}
 <?php } else if ($zmb_caption == "Paper") {?> color:#333;background:url(<?php echo get_template_directory_uri(); ?>/images/frames/Paper.jpg) repeat #CCC;}
<?php }
?><?php if ($zmb_title == "Hide") { ?> #site-title, #site-description { visibility:hidden;} <?php }
?><?php if ($zmb_tables == "Enable") { ?> #content table {border:none;} #content tr {background:none;} #content table {border:none;} #content tr th,
#content thead th {background:none;} #content tr td {border:none;}<?php }
?><?php if ($zmb_pagetitle == "Hide") { ?> #content h1.entry-title { display:none;} <?php }
?><?php if ($zmb_categtitle == "Hide") { ?> h1.page-title { display:none;} <?php }
?><?php if ($zmb_postdate == "Hide" && $zmb_postauthor == "Hide" && $zmb_posttime == "Hide") { ?>.entry-meta {display:none;} <?php }
?><?php if ($zmb_postdate == "Hide") { ?> span.entry-date, span.onDate {display:none;} <?php }
?><?php if ($zmb_postauthor == "Hide") { ?> .author,.entry-meta .meta-sep {display:none;} <?php }
?><?php if ($zmb_postcateg == "Hide") { ?> .cat-links, span.bl_posted {display:none;} <?php }
?><?php if ($zmb_postbook == "Hide") { ?>  span.bl_bookmark {display:none;} <?php }
?><?php if ($zmb_parindent != "0px") { ?>  p {text-indent:<?php echo $zmb_parindent;?> ;} <?php }
?><?php if ($zmb_posttime == "Hide") { ?>  .entry-time {display:none;} <?php } }?>
</style>


<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
    wp_enqueue_script("jquery");
	wp_head(); ?>
</head>

<body <?php body_class(); ?>>



<div id="topper" <?php if($options) if ($zmb_splash == "Hide") { ?> style="background-image:none;" <?php } ?>>
<div id="splatter"  <?php if($options) if ($zmb_top == "Hide") { ?> style="background-image:none;" <?php } ?>>
<div id="wrapper" class="hfeed">
<div id="header" <?php  if($options) if ($zmb_hand=="Hide") { ?> style="background-image:none;" <?php } ?>>

		<div id="masthead">
			<div id="branding" role="banner">
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-title">
					<span>
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</span>
				</<?php echo $heading_tag; ?>>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>

				<?php
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else : ?><?php if (get_header_image() != '') { ?>

						<style> #branding { background:url(<?php header_image(); ?>) no-repeat;
								 width:<?php echo HEADER_IMAGE_WIDTH; ?>px;margin-left:20px;
								 height:<?php echo HEADER_IMAGE_HEIGHT; ?>px; }
</style>
									<?php } else { ?><?php } ?><?php endif; ?>
			</div><!-- #branding -->

			<div id="access" role="navigation">
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'zombie-apocalypse' ); ?>"><?php _e( 'Skip to content', 'zombie-apocalypse' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */
				?><?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div><!-- #access -->
		</div><!-- #masthead -->
<div style="clear:both;"> </div>


	</div><!-- #header -->

	<div id="main">
	<div  <?php if($options) {if ($zmb_puddle == "Hide") { ?> id="forbottom2" <?php } else { ?> id="forbottom" <?php }} else {?> id="forbottom" <?php } ?> >