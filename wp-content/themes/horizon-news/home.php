<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Horizon News
 */

get_header();
global $wp_query;

$grid_style = get_theme_mod('horizon_news_archive_grid_style', 'grid-column-2');
$current_page = max(1, get_query_var('paged'));
$total_pages = $wp_query->max_num_pages;
?>
<main id="primary" class="site-main">

	<?php
	if (is_home() && ! is_front_page()) {
		do_action('horizon_news_breadcrumb');
	}

	if (have_posts()) :
		if (is_home() && ! is_front_page()) :
	?>

			<header>
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			</header>

		<?php
		endif;
		?>

		<div class="magazine-archive-layout grid-layout <?php echo esc_attr($grid_style); ?>">
			<?php
			while (have_posts()) :
				the_post();
				get_template_part('template-parts/content', get_post_type());
			endwhile;
			?>
		</div>

		<?php if (is_true_homepage()) : ?>
			<?php if ($total_pages > 1) : ?>
				<div class="load-more-wrapper">
					<a href="<?php echo esc_url(home_url('/page/2')); ?>" class="load-more-btn">Xem thêm</a>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<?php if ($total_pages > 1) : ?>
				<div class="custom-pagination">
					<?php if ($current_page > 1) : ?>
						<a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>" class="pagination-prev">← Quay lại</a>
					<?php else : ?>
						<span class="pagination-prev disabled">← PREV</span>
					<?php endif; ?>

					<?php
					$range = 2;
					$start = max(1, $current_page - $range);
					$end = min($total_pages, $current_page + $range);

					for ($i = $start; $i <= $end; $i++) {
						if ($i == $current_page) {
							echo '<span class="pagination-number current">' . $i . '</span>';
						} else {
							echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="pagination-number">' . $i . '</a>';
						}
					}
					?>

					<?php if ($current_page < $total_pages) : ?>
						<a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>" class="pagination-next">Tiếp theo →</a>
					<?php else : ?>
						<span class="pagination-next disabled">NEXT →</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
	<?php endif;
	else :
		get_template_part('template-parts/content', 'none');
	endif;
	?>

</main><!-- #main -->

<?php
if (horizon_news_is_sidebar_enabled()) {
	get_sidebar();
}
?>

<?php
get_footer();
