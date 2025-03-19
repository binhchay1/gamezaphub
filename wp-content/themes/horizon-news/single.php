<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Horizon News
 */

get_header();
?>
	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'single' );

			do_action( 'horizon_news_post_navigation' );

			if ( is_singular( 'post' ) ) {
				$related_posts_label = get_theme_mod( 'horizon_news_post_related_post_label', __( 'Related Posts', 'horizon-news' ) );
				$args                = array(
					'posts_per_page' => 3,
					'post__not_in'   => array( $post->ID ),
					'orderby'        => 'rand',
				);
				$cat_content_id      = get_the_category( $post->ID );
				if ( ! empty( $cat_content_id ) ) {
					$args['cat'] = $cat_content_id[0]->term_id;
				}

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) :
					?>
					<div class="related-posts">
						<div class="section-header">
							<h2 class="section-title"><span><?php echo esc_html( $related_posts_label ); ?></span></h2>
						</div>
						<div class="row">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								?>
								<div>
									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<div class="mag-post-single has-image">
											<div class="mag-post-img">
												<?php horizon_news_post_thumbnail(); ?>
											</div>
											<div class="mag-post-detail">
												<div class="mag-post-category">
													<?php horizon_news_categories_list(); ?>
												</div>
												<?php the_title( '<h3 class="entry-title mag-post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' ); ?>
												<div class="mag-post-meta">
													<?php
													horizon_news_posted_by();
													horizon_news_posted_on();
													?>
												</div>
												<div class="mag-post-excerpt">
													<?php the_excerpt(); ?>
												</div>
											</div>
										</div>
									</article>
								</div>
								<?php
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
					<?php
				endif;
			}

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	</main>
<?php
if ( horizon_news_is_sidebar_enabled() ) {
	get_sidebar();
}
get_footer();
