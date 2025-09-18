<?php
/**
 * Template for review features
 *
 * @since   3.3.7
 * @version 3.3.8
 * @package WP_Review
 *
 * @var array $review
 */

if ( empty( $review['items'] ) || ! is_array( $review['items'] ) || ! empty( $review['disable_features'] ) ) {
	return;
}
$rating_types = wp_review_get_rating_types();
?>
<ul class="review-list">
	<?php
	foreach ( $review['items'] as $item ) :
		$value_text = '';
		$item       = wp_parse_args(
			$item,
			array(
				'wp_review_item_star'           => '',
				'wp_review_item_title'          => '',
				'wp_review_item_color'          => '',
				'wp_review_item_inactive_color' => '',
				'wp_review_item_positive'       => '',
				'wp_review_item_negative'       => '',
			)
		);
		if ( 'star' !== $review['type'] ) {
			$value_text = ' - <span>' . sprintf( $rating_types[ $review['type'] ]['value_text'], $item['wp_review_item_star'] ) . '</span>';
		}
		?>
		<li>
			<span><?php echo wp_kses_post( $item['wp_review_item_title'] ); ?><?php echo $value_text; ?></span>
			<?php
			echo wp_review_rating(
				$item['wp_review_item_star'],
				$review['post_id'],
				array(
					'color'          => $item['wp_review_item_color'],
					'inactive_color' => $item['wp_review_item_inactive_color'],
					'positive_count' => $item['wp_review_item_positive'],
					'negative_count' => $item['wp_review_item_negative'],
				)
			);
			?>
		</li>
	<?php endforeach; ?>
</ul><!-- End .review-list -->
