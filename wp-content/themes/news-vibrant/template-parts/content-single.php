<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="nv-article-thumb">
			<?php the_post_thumbnail( 'full' ); ?>
		</div><!-- .nv-article-thumb -->
	<?php } ?>

	<header class="entry-header">
		<?php 
			the_title( '<h1 class="entry-title">', '</h1>' );
			news_vibrant_post_categories_list();
		?>
		<div class="entry-meta">
			<?php news_vibrant_inner_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'news-vibrant' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'news-vibrant' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php news_vibrant_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->