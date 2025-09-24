<?php
/**
 * The template for displaying all WooCommerce pages.
 *
 * @package BlogArise
 */
get_header(); ?>


<!-- #main -->
<main id="content">
	<div class="container">
		<div class="row">
			<!--==================== breadcrumb section ====================-->
			<?php do_action('blogarise_breadcrumb_content'); ?>
			<div class="col-md-12">
				<?php woocommerce_content(); ?>
			</div>
		</div><!-- .container -->
	</div>	
</main><!-- #main -->
<?php get_footer(); ?>