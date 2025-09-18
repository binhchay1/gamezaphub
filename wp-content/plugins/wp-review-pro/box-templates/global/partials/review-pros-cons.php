<?php
/**
 * Template for review pros cons
 *
 * @since   3.3.7
 * @version 3.3.7
 * @package WP_Review
 *
 * @var array $review
 */

if ( empty( $review['pros'] ) && empty( $review['cons'] ) ) {
	return;
}
?>
<div class="review-pros-cons wpr-flex wpr-flex-wrap">
	<div class="review-pros wpr-col-1-2 pr-10">
		<p class="mb-5"><strong><?php esc_html_e( 'Pros', 'wp-review' ); ?></strong></p>
		<?php echo apply_filters( 'wp_review_pros', $review['pros'], $review['post_id'] ); ?>
	</div>

	<div class="review-cons wpr-col-1-2 pl-10">
		<p class="mb-5"><strong><?php esc_html_e( 'Cons', 'wp-review' ); ?></strong></p>
		<?php echo apply_filters( 'wp_review_cons', $review['cons'], $review['post_id'] ); ?>
	</div>
</div><!-- End .review-pros-cons -->
