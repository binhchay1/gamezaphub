<?php
$editor_query = new WP_Query( $editor_args );
if ( $editor_query->have_posts() ) {
	$editor_pick_title = get_theme_mod( 'horizon_news_editor_pick_title', __( 'Editor Pick', 'horizon-news' ) );
	?>
	<div class="editors-pick-part">
		<?php if ( ! empty( $editor_pick_title ) ) : ?>
			<div class="section-header">
				<h3 class="section-title"><span><?php echo esc_html( $editor_pick_title ); ?></span></h3>
			</div>
		<?php endif; ?>
		<div class="editors-pick-wrapper">
			<?php
			while ( $editor_query->have_posts() ) :
				$editor_query->the_post();
				?>
				<div class="mag-post-single has-image tile-design">
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="mag-post-img">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'post-thumbnail' ); ?></a>
						</div>
					<?php } ?>
					<div class="mag-post-detail">
						<div class="mag-post-category with-background">
							<?php horizon_news_categories_list(); ?>
						</div>
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
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
}
