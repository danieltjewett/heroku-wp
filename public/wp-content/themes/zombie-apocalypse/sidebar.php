<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package Cryout Creations
 * @subpackage Zombie Apocalypse
 * @since Zombie Apocalypse 0.5
 */
?><?php
/* This  retrieves  admin options. */
 $options = get_option('za_options');
$zmb_drips = $options['zmb_drips'];
$zmb_side = $options['zmb_side'];
?>
		<div id="primary" class="widget-area" role="complementary" <?php if($options) if ($zmb_drips == "Hide" && $zmb_side != "Disable") { ?> style="background:none;" <?php }
		?><?php if ($zmb_side == "Disable") { ?> style="display:none;" <?php } ?>>
			<ul class="xoxo">

<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

			<li id="search" class="widget-container widget_search">
				<?php get_search_form(); ?>
			</li>

			<li id="archives" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Archives', 'zombie-apocalypse' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>

			<li id="meta" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Meta', 'zombie-apocalypse' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>

		<?php endif; // end primary widget area ?>
			</ul>
		</div><!-- #primary .widget-area -->

<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

		<div id="secondary" class="widget-area" role="complementary" <?php if($options) if ($zmb_side == "Disable") { ?> style="display:none;" <?php } ?>>
			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
		</div><!-- #secondary .widget-area -->

<?php endif; ?>
