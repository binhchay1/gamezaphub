<?php
/**
 * Template for shortcode [wp-review-google-place-reviews]
 *
 * @package WP_Review
 * @since   3.0.0
 * @version 3.0.0
 *
 * @var array $response
 * @var array $atts
 */

if ( empty( $response['result'] ) ) {
	return;
}

$place = $response['result'];
if ( ! isset( $place['rating'] ) ) {
	return;
}

$rating = $place['rating'];
?>
<div class="wpr-place-reviews">
	<div class="place-review">
		<div class="review-data">
			<div class="reviewer-name">
				<a href="<?php echo esc_url( $place['url'] ); ?>"><?php echo esc_html( $place['name'] ); ?></a>
			</div>

			<div class="review-rating">
				<?php echo floatval( $rating ); ?>
				<?php wp_review_star_rating( $rating ); ?>
			</div>
		</div>
	</div>

	<?php
	$markup = array(
		'@context'     => 'http://schema.org',
		'@type'        => 'Review',
		'itemReviewed' => array(
			'@type' => 'LocalBusiness',
			'name'  => $place['name'],
			'url'   => $place['url'],
		),
		'reviewRating' => array(
			'@type'       => 'Rating',
			'ratingValue' => $rating,
			'bestRating'  => 5,
			'worstRating' => 0,
		),
	);

	/**
	 * Allow changing schema markup for Google place average rating.
	 *
	 * @since 3.0.4
	 *
	 * @param array $markup Schema markup.
	 * @param array $place  Place data.
	 */
	$markup = apply_filters( 'wp_review_google_place_average_rating_schema_markup', $markup, $place );

	printf( '<script type="application/ld+json">%s</script>', wp_json_encode( $markup ) );
	?>
</div>
