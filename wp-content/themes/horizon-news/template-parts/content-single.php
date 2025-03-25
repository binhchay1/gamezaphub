<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Horizon News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="mag-post-single background-color-none">
		<div class="mag-post-detail">
			<div class="mag-post-category">
				<?php horizon_news_categories_list(); ?>
			</div>
			<header class="entry-header">
				<?php
				if ( is_singular() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;

				if ( 'post' === get_post_type() ) :
					?>
					<div class="mag-post-meta">
						<?php
						if ( is_singular( 'post' ) ) :
							horizon_news_posted_by();
						endif;
						horizon_news_posted_on();
						?>
					</div>
				<?php endif; ?>
			</header>
		</div>
		<?php horizon_news_post_thumbnail(); ?>
		<div class="entry-content">
			<?php
			the_content(
				sprintf(
					wp_kses(
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'horizon-news' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'horizon-news' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>

		<footer class="entry-footer">
			<?php horizon_news_entry_footer(); ?>
		</footer>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
