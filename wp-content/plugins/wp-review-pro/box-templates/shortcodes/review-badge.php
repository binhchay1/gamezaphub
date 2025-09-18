<?php
/**
 * Template for shortcode [wp-review-badge]
 *
 * @package WP_Review
 * @since   3.3.10
 * @version 3.3.10
 *
 * @var array $rating_data
 * @var bool  $fixed
 * @var array $atts
 */

$el_class = array( 'wpr-badge' );
if ( $fixed ) {
	$el_class[] = 'wpr-badge--fixed';
}

$el_style = array();
if ( ! empty( $atts['bg_color'] ) ) {
	$el_style[] = 'background-color:' . $atts['bg_color'];
}
if ( ! empty( $atts['top'] ) ) {
	$el_style[] = 'top:' . $atts['top'];
}
if ( ! empty( $atts['left'] ) ) {
	$el_style[] = 'left:' . $atts['left'];
}
if ( ! empty( $atts['right'] ) ) {
	$el_style[] = 'right:' . $atts['right'];
}
if ( ! empty( $atts['bottom'] ) ) {
	$el_style[] = 'bottom:' . $atts['bottom'];
}
?>
<div class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" style="<?php echo esc_attr( implode( ';', $el_style ) ); ?>">
	<?php
	echo wp_review_rating(
		floatval( $rating_data['rating'] ),
		$atts['id'],
		array(
			'user_rating' => true,
		)
	);
	?>

	<div class="wpr-badge__texts">
		<div class="wpr-badge__avg">
			<?php
			printf(
				// translators: rating value.
				esc_html__( '%s Average', 'wp-review' ),
				'<span>' . floatval( $rating_data['rating'] ) . '</span>'
			);
			?>
		</div>

		<div class="wpr-badge__count">
			<?php
			printf(
				// translators: rating count.
				esc_html( $rating_data['count'] > 1 ? __( '%s Reviews', 'wp-review' ) : __( '%s Review', 'wp-review' ) ),
				'<span>' . intval( $rating_data['count'] ) . '</span>'
			);
			?>
		</div>
	</div>
</div><!-- End .wpr-badge -->
