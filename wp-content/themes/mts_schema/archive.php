<?php
/**
 * The template for displaying archive pages.
 *
 * Used for displaying archive-type pages. These views can be further customized by
 * creating a separate template for each one.
 *
 * - author.php (Author archive)
 * - category.php (Category archive)
 * - date.php (Date archive)
 * - tag.php (Tag archive)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Schema
 */

$mts_options = get_option( MTS_THEME_NAME );

get_header();
?>

<div id="page">
	<div class="<?php mts_article_class(); ?>">
		<?php
		// Elementor `archive` location.
		if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
			?>
			<div id="content_box">
				<h1 class="postsby">
					<span><?php the_archive_title(); ?></span>
				</h1>
				<?php
				$j = 0;
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>
						<article class="latestPost excerpt">
							<?php mts_archive_post(); ?>
						</article><!--.post excerpt-->
						<?php
					endwhile;
				endif;

				++$j;
				if ( 0 !== $j ) { // No pagination if there is no posts.
					mts_pagination();
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	get_sidebar();
	get_footer();
