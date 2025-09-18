<?php
/*
 *
 * Available variables:
 *		$tab_name
 *		$icon
 *  	$num_post
 *  	$show_price
 *  	$show_rating
 *		$show_thumbnail
 *  	$thumb_size
 *  	$small_thumb_align
 *  	$custom_thumb_align
 *  	$custom_thumb_w
 *  	$custom_thumb_h
 *		$show_excerpt
 *  	$excerpt_length
 *  	$allow_pagination
 *  	$title_length
 *  	$page
 *
 *  	$widget_number
 *  	$tab_key
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists('WooCommerce') ) {
?>
	<ul class="wptp-list">
		<?php
		$lat_post_num = (empty($num_post) ? 5 : intval($num_post));
		if ($lat_post_num > 20 || $lat_post_num < 1) { // max 20 posts
			$lat_post_num = 5;
		}

		$lat_excerpt_length = intval($excerpt_length);
		if ($lat_excerpt_length > 50 || $lat_excerpt_length < 1) {
			$lat_excerpt_length = 10;
		}

		$lat_title_length = ! empty($title_length) ? $title_length : apply_filters( 'wpt_products_title_length_default', '15' );

		$thumb_class = $class_prefix.'_thumbnail '. $class_prefix . '_thumb_' .$thumb_size;
		if ( 'small' === $thumb_size ) $thumb_class .= ' ' . $class_prefix . '_thumb_align_' .$small_thumb_align;
		if ( 'custom' === $thumb_size ) $thumb_class .= ' ' . $class_prefix . '_thumb_align_' .$custom_thumb_align;

		// Backward compatibility filter
		$query_args = apply_filters(
			'wpt_products_args',
			array(
				'post_type' => 'product',
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $lat_post_num,
				'post_status' => 'publish',
				'orderby' => 'post_date',
				'order' => 'desc',
				'paged' => $page
			)
		);

		$query_args = apply_filters( 'wpt_products_query_args', $query_args, $widget_number, $tab_key );

		$recent = new WP_Query( $query_args );
		$last_page = $recent->max_num_pages;
		while ($recent->have_posts()) : $recent->the_post();
			if ( !has_post_thumbnail() && 'custom' === $thumb_size ) $show_thumbnail = '0';
			?>
			<li class="wptp-list-item">
				<?php if ( '1' === $show_thumbnail ) : ?>
					<div class="<?php echo $thumb_class;?>">
						<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>">
							<?php if('custom' === $thumb_size): ?>
								<?php //if(has_post_thumbnail()): ?>
									<?php the_post_thumbnail( array( $custom_thumb_w, $custom_thumb_h ), array( 'title' => '' ) ); ?>
								<?php //endif; ?>
							<?php else: ?>
								<?php if(has_post_thumbnail()): ?>
									<?php the_post_thumbnail('wp_review_'.$thumb_size, array('title' => '')); ?>
								<?php else: ?>
									<img src="<?php echo WPT_PLUGIN_URL.'img/'.$thumb_size.'thumb.png'; ?>" alt="<?php the_title(); ?>"  class="wp-post-image" />
								<?php endif; ?>
							<?php endif; ?>
						</a>
					</div>
				<?php endif; ?>
				<div class="entry-title"><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php echo wpt_post_title( $lat_title_length ); ?></a></div>
				<?php if ( '1' === $show_price || '1' === $show_rating ) : ?>
					<div class="<?php echo $class_prefix;?>-postmeta wptp-product-data woocommerce">
						<?php $_product = wc_get_product( get_the_ID() ); ?>
						<?php if ( '1' === $show_rating && get_option( 'woocommerce_enable_review_rating' ) !== 'no' && $rating_html = $_product->get_rating_html() ) : ?>
							<?php echo '<div class="wptp-product-rating">'.$rating_html.'</div>'; ?>
						<?php endif; ?>
						<?php if ( '1' === $show_price && $price_html = $_product->get_price_html() ) : ?>
							<?php echo '<div class="wptp-product-price">'.$price_html.'</div>'; ?>
						<?php endif; ?>
					</div> <!--end .entry-meta-->
				<?php endif; ?>

				<?php if ( '1' === $show_excerpt ) : ?>
					<div class="<?php echo $class_prefix;?>_excerpt">
						<p><?php echo wpt_excerpt($lat_excerpt_length); ?></p>
					</div>
				<?php endif; ?>
					
				<div class="clear"></div>
			</li>
		<?php endwhile; wp_reset_postdata(); ?>
	</ul>
	<div class="clear"></div>
	<?php if ( '1' === $allow_pagination ) : ?>
		<?php
		wpt_get_template(
			'pagination.php',
			array(
				'page' => $page,
				'last_page' => $last_page,
				'class_prefix' => $class_prefix,
			)
		);
		?>
	<?php endif; ?>
<?php } else { ?>
	<p><?php _e( 'WooCommerce plugin must be active.', 'wp-tab-widget' ); ?></p>
<?php } ?>