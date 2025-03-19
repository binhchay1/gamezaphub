<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Horizon News
 */

get_header();
$grid_style = get_theme_mod('horizon_news_archive_grid_style', 'grid-column-2');
?>
<main id="primary" class="site-main">
	<?php if (have_posts()) : ?>
		<header class="page-header">
			<?php
			the_archive_title('<h1 class="page-title">', '</h1>');
			the_archive_description('<div class="archive-description">', '</div>');
			?>
		</header>
		<hr>
		<div class="magazine-archive-layout grid-layout <?php echo esc_attr($grid_style); ?>">
			<?php
			while (have_posts()) :
				the_post();
				get_template_part('template-parts/content', get_post_type());
			endwhile;
			?>
		</div>
	<?php
		do_action('horizon_news_posts_pagination');
	else :
		get_template_part('template-parts/content', 'none');
	endif;
	?>
</main>
<?php
if (horizon_news_is_sidebar_enabled()) {
	get_sidebar();
}
get_footer();
