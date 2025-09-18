<?php
/**
 * Template for review embed
 *
 * @since   3.3.7
 * @version 3.3.7
 * @package WP_Review
 *
 * @var array $review
 */

if ( wp_review_is_embed() || empty( $review['enable_embed'] ) ) {
	return;
}
?>
<div class="review-embed-code">
	<label for="wp_review_embed_code"><?php esc_html_e( 'Embed code', 'wp-review' ); ?></label>
	<textarea id="wp_review_embed_code" rows="2" cols="40" readonly onclick="this.select()"><?php echo esc_textarea( wp_review_get_embed_code( $review['post_id'] ) ); ?></textarea>
</div>
