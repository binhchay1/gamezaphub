<?php
/**
 * Template for external review badge
 *
 * @package WP_Review
 * @since   3.3.10
 * @version 3.3.10
 *
 * @var float  $rating
 * @var string $name
 * @var string $image
 * @var bool   $fixed
 * @var array  $atts
 */

$el_class = 'wpr-external-review-badge';
if ( $fixed ) {
	$el_class .= ' wpr-badge--fixed';
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
<div class="<?php echo esc_attr( $el_class ); ?>" style="<?php echo esc_attr( implode( ';', $el_style ) ); ?>">
	<div class="wpr-external-review-badge__image">
		<?php echo $image; ?>
	</div>

	<div class="wpr-external-review-badge__data">
		<div class="wpr-external-review-badge__name"><?php echo esc_html( $name ); ?></div>

		<div class="wpr-external-review-badge__rating">
			<?php echo floatval( $rating ); ?>
			<?php wp_review_star_rating( $rating ); ?>
		</div>
	</div>
</div>
