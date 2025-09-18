<?php
/**
 * Template for review description
 *
 * @since   3.3.7
 * @version 3.3.7
 * @package WP_Review
 *
 * @var array $review
 */

if ( empty( $review['desc'] ) || ! empty( $review['hide_desc'] ) ) {
	return;
}
?>
<div class="review-desc">
	<p class="review-summary-title"><strong><?php echo $review['desc_title']; ?></strong></p>
	<?php echo do_shortcode( apply_filters( 'wp_review_desc', $review['desc'], $review['post_id'] ) ); ?>
</div>
