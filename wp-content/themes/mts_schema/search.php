<?php
/**
 * The template for displaying search results pages.
 *
 * @package Schema
 */

$mts_options = get_option( MTS_THEME_NAME );

get_header();
?>

<div id="page">
	<div class="article">
		<?php
		// Elementor `archive` location.
		if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
		?>
			<div id="content_box">
				<h1 class="postsby">
					<span><?php esc_html_e( 'Search Results for:', 'schema' ); ?></span> <?php the_search_query(); ?>
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
			else :
				?>
				<div class="no-results">
					<h2><?php esc_html_e( 'We apologize for any inconvenience, please hit back on your browser or use the search form below.', 'schema' ); ?></h2>
					<?php get_search_form(); ?>
				</div><!--noResults-->
				<?php
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
