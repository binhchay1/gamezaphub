<?php
/**
 * Template for review comments rating
 *
 * @since   3.3.7
 * @version 3.3.7
 * @package WP_Review
 *
 * @var array $review
 */

if ( wp_review_is_embed() || empty( $review['comments_review'] ) || ! empty( $review['hide_comments_rating'] ) ) {
	return;
}
?>
<div class="user-review-area comments-review-area">
	<?php echo wp_review_user_comments_rating( $review['post_id'] ); ?>
	<div class="user-total-wrapper">
		<span class="user-review-title"><?php esc_html_e( 'Comments Rating', 'wp-review' ); ?></span>
		<span class="review-total-box">
			<?php
			$comment_reviews       = mts_get_post_comments_reviews( $review['post_id'] );
			$comments_review_total = $comment_reviews['rating'];
			$comments_review_count = $comment_reviews['count'];
			?>
			<span class="wp-review-user-rating-total"><?php echo esc_html( wp_review_get_rating_text( $comments_review_total, $review['user_review_type'] ) ); ?></span>
			<small>(<span class="wp-review-user-rating-counter"><?php echo esc_html( $comments_review_count ); ?></span> <?php echo esc_html( _n( 'review', 'reviews', $comments_review_count, 'wp-review' ) ); ?>)</small>
			<br />
			<small class="awaiting-response-wrapper"></small>
		</span>
	</div>
</div><!-- End .comments-review-area -->
