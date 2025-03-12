<?php
$main_news_query = new WP_Query( $main_news_args );
if ( $main_news_query->have_posts() ) {
	$main_news_title = get_theme_mod( 'horizon_news_main_news_title', __( 'Main News', 'horizon-news' ) );
	?>
	<div class="slider-part">
		<div class="section-header">
			<?php if ( ! empty( $main_news_title ) ) : ?>
				<h3 class="section-title"><span><?php echo esc_html( $main_news_title ); ?></span></h3>
			<?php endif; ?>
			<div class="banner-slider-arrows magazine-carousel-slider-navigation header-carousel-nav"></div>
		</div>
		<div class="banner-slider magazine-carousel-slider-navigation">
			<?php
			while ( $main_news_query->have_posts() ) :
				$main_news_query->the_post();
				?>
				<div class="carousel-item">
					<div class="mag-post-single banner-grid-single has-image tile-design">
						<div class="mag-post-img">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'full' ); ?>
							</a>
						</div>
						<div class="mag-post-detail">
							<div class="mag-post-category with-background">
								<?php horizon_news_categories_list(); ?>
							</div>
							<h3 class="mag-post-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
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
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
}
