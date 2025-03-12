<?php
$trending_query = new WP_Query( $trending_args );
if ( $trending_query->have_posts() ) {
	$trending_title = get_theme_mod( 'horizon_news_trending_posts_title', __( 'Trending Posts', 'horizon-news' ) );
	?>
	<div class="trending-part">
		<div class="section-header">
			<?php if ( ! empty( $trending_title ) ) : ?>
				<h3 class="section-title"><span><?php echo esc_html( $trending_title ); ?></span></h3>
			<?php endif; ?>
			<div class="banner-trending-arrows magazine-carousel-slider-navigation header-carousel-nav"></div>
		</div>
		<div class="trending-wrapper">
			<?php
			$i = 1;
			while ( $trending_query->have_posts() ) :
				$trending_query->the_post();
				?>
				<div class="carousel-item">
					<div class="mag-post-single banner-gird-single has-image list-design">
						<div class="mag-post-img">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'post-thumbnail' ); ?></a>
							<span class="trending-no"><?php echo absint( $i ); ?></span>
						</div>
						<div class="mag-post-detail">
							<h4 class="mag-post-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h4>
							<div class="mag-post-meta">
								<?php
								horizon_news_posted_by();
								horizon_news_posted_on();
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
}
