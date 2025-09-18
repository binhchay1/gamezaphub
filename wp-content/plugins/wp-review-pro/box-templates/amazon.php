<?php
/**
 * WP Review: Amazon
 * Description: Amazon Review Box template for WP Review
 * Version: 3.3.8
 * Author: MyThemesShop
 * Author URI: http://mythemeshop.com/
 *
 * @package   WP_Review
 * @since     3.0.0
 * @version   3.3.8
 * @copyright Copyright (c) 2017, MyThemesShop
 * @author    MyThemesShop
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @var array $review
 */

/**
 * Use print_r( $review ); to inspect the $review array.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = implode( ' ', $review['css_classes'] );

$is_embed = wp_review_is_embed();

if ( ! empty( $review['fontfamily'] ) ) : ?>
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
	<style type="text/css">
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper { font-family: 'Lato', sans-serif; }
	</style>
<?php endif; ?>

<div id="review" class="<?php echo esc_attr( $classes ); ?>">
	<?php if ( empty( $review['heading'] ) ) : ?>
		<?php echo esc_html( apply_filters( 'wp_review_item_title_fallback', '' ) ); ?>
	<?php else : ?>
		<div class="review-heading">
			<h5 class="review-title">
				<?php echo esc_html( $review['heading'] ); ?>

				<?php if ( ! empty( $review['product_price'] ) ) : ?>
					<span class="review-price"><?php echo esc_html( $review['product_price'] ); ?></span>
				<?php endif; ?>
			</h5>
		</div>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-schema.php', compact( 'review' ) ); ?>

	<?php if ( ! empty( $review['total'] && ! $review['hide_desc'] && 'thumbs' !== $review['type'] ) ) : ?>
		<div class="review-total-wrapper">
			<span class="review-total-box"><?php echo esc_html( wp_review_get_rating_text( $review['total'], $review['type'] ) ); ?></span>
			<?php echo wp_review_get_total_rating( $review ); ?>
		</div>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-features.php', compact( 'review' ) ); ?>

	<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>

	<?php if ( ! $is_embed && $review['user_review'] && ! $review['hide_visitors_rating'] ) : ?>
		<?php if ( ! wp_review_user_can_rate_features( $review['post_id'] ) ) : ?>
			<div class="user-review-area visitors-review-area">
				<?php echo wp_review_user_rating( $review['post_id'] ); ?>
				<div class="user-total-wrapper">
					<h5 class="user-review-title"><?php esc_html_e( 'User Review', 'wp-review' ); ?></h5>
					<span class="review-total-box">
						<span class="wp-review-user-rating-total"><?php echo esc_html( wp_review_get_rating_text( $review['user_review_total'], $review['user_review_type'] ) ); ?></span>
						<small>(<span class="wp-review-user-rating-counter"><?php echo esc_html( $review['user_review_count'] ); ?></span> <?php echo esc_html( _n( 'vote', 'votes', $review['user_review_count'], 'wp-review' ) ); ?>)</small>
					</span>
				</div>
			</div>
		<?php else : ?>
			<?php echo wp_review_visitor_feature_rating( $review['post_id'] ); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-comments-rating.php', compact( 'review' ) ); ?>

	<?php if ( ! $review['hide_desc'] ) : ?>
		<?php wp_review_load_template( 'global/partials/review-pros-cons.php', compact( 'review' ) ); ?>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-links.php', compact( 'review' ) ); ?>

	<?php wp_review_load_template( 'global/partials/review-embed.php', compact( 'review' ) ); ?>
</div>

<?php
$colors            = $review['colors'];
$light_color       = wp_review_color_luminance( $colors['color'], '0.1' );
$dark_color        = wp_review_color_luminance( $colors['color'], '-0.4' );
$dark_border_color = wp_review_color_luminance( $colors['color'], '-0.4' );

ob_start();
// phpcs:disable
?>
<style type="text/css">
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		width: <?php echo $review['width']; ?>%;
		float: <?php echo $review['align']; ?>;
		border: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		width: 60%;
		float: right;
		padding: 0;
		margin-bottom: 20px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper i,
	.wp-review-comment-<?php echo $review['post_id']; ?> .review-result-wrapper i {
		-webkit-text-stroke-width: 1px;
		-webkit-text-stroke-color: <?php echo $dark_color; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-desc p,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item p {
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a {
		background: <?php echo $colors['color']; ?>;
		background: linear-gradient(to top, <?php echo $colors['color']; ?>, <?php echo $light_color; ?>);
		color: <?php echo $colors['fontcolor']; ?>;
		padding: 5px 20px;
		border-radius: 3px;
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset;
		border: 1px solid <?php echo $dark_border_color; ?>;
		transition: all 0.25s linear;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a:hover {
		background: <?php echo $light_color; ?>;
		background: linear-gradient(to bottom, <?php echo $light_color; ?>, <?php echo $colors['bgcolor1']; ?>);
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
		width: 35%;
		padding: 5px 0 20px 0;
		float: left;
	}
	<?php if ( $review['hide_desc'] ) : ?>
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list,
	<?php endif; ?>
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating .review-list,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-thumbs-type .review-list, .wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-thumbs-type .review-desc {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-list,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-list {
		padding: 0 0 30px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-list {
		padding: 10px 0 30px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item,
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 5px 0;
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 0 20px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
		flex: 100%;
		padding: 15px 0 10px 0;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		flex: 100%;
		padding: 0 0 10px 0;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .mb-5 {
		text-transform: uppercase;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 0 0 10px 0;
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area .review-star {
        letter-spacing: .5px;
    }
    .wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type.review-wrapper .user-review-area .review-result-wrapper .review-result,
    .wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type.review-wrapper .user-review-area .review-result-wrapper .review-result { min-width: 5%; }
    .wp-review-<?php echo $review['post_id']; ?> .wp-review-user-rating .review-result-wrapper .review-result { letter-spacing: -1.4px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		padding: 0;
		background: transparent;
		letter-spacing: 1px;
		text-transform: none;
		border: 0;
		font-weight: bold;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		width: 100%;
		padding: 0 0 20px 0;
		float: left;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper i { font-size: 22px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		margin: 5px 0 0 20px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-total-box {
		margin-top: 22px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
		box-shadow: inset 0 1px 2px rgba(0,0,0,.4), inset 0 0 0 1px rgba(0,0,0,.1);
		height: 28px;
		margin-bottom: 0;
		background: <?php echo $colors['inactive_color']; ?>;
		border-radius: 4px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-point .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-percentage .review-result {
		border: 1px solid <?php echo $dark_color; ?>;
		box-shadow: none;
		background: linear-gradient(to top, <?php echo $colors['bgcolor1']; ?> 0%, <?php echo $colors['color']; ?> 100%);
		box-sizing: border-box;
		min-width: 2%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box {
		width: auto;
		float: left;
		text-align: left;
		padding: 0;
		font-size: 34px;
		line-height: 1;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-total-wrapper .review-total,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-total-wrapper .review-total {
		width: 60%;
		float: right;
		clear: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title {
		display: inline-block;
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 30px 0;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper h5.user-review-title {
		margin-top: 12px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper span.user-review-title {
		margin-top: 5px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-percentage,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-point {
		width: 20%;
		float: right;
		margin-top: 5px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating .user-review-title {
		margin: 0;
		padding: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		float: right;
		width: 70px;
		height: 70px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total .review-result-wrapper > div, .wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total .review-result-wrapper > div > canvas {
	    width: 70px!important;
	    height: 70px!important;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total .review-result-wrapper > div > input {
	    margin-top: 20px!important;
        margin-left: -62px!important;
        font-size: 20px!important;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box > div { display: none; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box h5 {
		color: inherit;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-embed-code { padding: 7px 0 0; }
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
		margin: -20px 0 20px;
		border-radius: 4px;
		background: <?php echo $colors['color']; ?>;
		background: linear-gradient(to top, <?php echo $colors['color']; ?>, <?php echo $light_color; ?>);
		color: <?php echo $colors['fontcolor']; ?>;
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset;
		border: 1px solid <?php echo $dark_border_color; ?>;
	}
	@media screen and (max-width:600px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
			width: 100%;
		}
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of amazon template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_amazon_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
