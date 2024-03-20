<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Micro
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer section-fullwidth">
		<div class="row">
			<div class="columns small-12">
				<div class="site-info">
					&#169;Copyright <?php echo date('Y'); ?> Creation Mode Studios, LLC
					<span class="sep"> | </span>
					<a href="/privacy">Privacy Policy</a>
				</div><!-- .site-info -->
			</div><!-- .small-12 -->
		</div><!-- .row -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
