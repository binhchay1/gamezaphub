<?php
/**
 * Template for review links
 *
 * @since 3.0.0
 *
 * @package WP_Review
 * @var array $review
 */

if ( empty( $review['links'] ) || ! is_array( $review['links'] ) ) {
	return;
}
?>
<ul class="review-links">
	<?php
	foreach ( $review['links'] as $review_link ) :
		$review_link = wp_parse_args(
			$review_link,
			array(
				'url'  => '',
				'text' => '',
			)
		);
		if ( empty( $review_link['text'] ) ) {
			continue;
		}
		$nofollow = isset( $review_link['nofollow'] ) ? $review_link['nofollow'] : '1';
		?>
		<li>
			<a href="<?php echo esc_url( $review_link['url'] ); ?>" target="_blank" <?php echo '1' === $nofollow ? 'rel="nofollow noopener"' : ''; ?>><?php echo wp_kses_post( $review_link['text'] ); ?></a>
		</li>
	<?php endforeach; ?>
</ul>
