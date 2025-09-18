<?php
/**
 * WP Review: Darkside
 * Description: Darkside Review Box template for WP Review
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
?>
<?php if ( ! empty( $review['fontfamily'] ) ) : ?>
	<link href="https://fonts.googleapis.com/css?family=Nunito:400,700" rel="stylesheet">
	<style type="text/css">
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper { font-family: 'Nunito', sans-serif; }
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

	<?php wp_review_load_template( 'global/partials/review-features.php', compact( 'review' ) ); ?>

	<?php if ( ! $review['hide_desc'] ) : ?>
		<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>

		<?php if ( ! empty( $review['total'] ) ) : ?>
			<div class="review-total-wrapper">
				<div class="review-total-box">
					<h5><?php esc_html_e( 'Overall ', 'wp-review' ); ?></h5>
					<?php echo esc_html( wp_review_get_rating_text( $review['total'], $review['type'] ) ); ?>
				</div>

				<?php echo wp_review_get_total_rating( $review ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

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
$colors = $review['colors'];
ob_start();
// phpcs:disable
?>
<style type="text/css">
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		width: <?php echo $review['width']; ?>%;
		float: <?php echo $review['align']; ?>;
		position: relative;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		width: 60%;
		padding: 25px 30px 25px 30px;
		float: left;
		box-sizing: border-box;
		min-height: 230px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-desc p,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item p {
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a {
		background: <?php echo $colors['bgcolor1']; ?>;
		padding: 8px 20px;
		box-shadow: none;
		color: <?php echo $colors['fontcolor']; ?>;
		cursor: pointer;
		border-radius: 3px;
		border: 2px solid <?php echo $colors['color']; ?>;
		transition: all 0.25s linear;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a:hover {
		box-shadow: none;
		background: <?php echo $colors['color']; ?>;
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li {
		padding: 15px 20px 12px 20px;
		width: 29.3%;
		margin: 2%;
		float: left;
		border: 2px solid <?php echo $colors['color']; ?>;
		border-radius: 10px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-of-type(4n) {
		margin-right: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list .review-circle {
		margin-top: 0;
		height: 32px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list .wp-review-user-feature-rating-circle {
        margin-top: -8px;
        margin-bottom: 10px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li {
		width: 46%;
		padding: 20px 30px 20px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li > span {
	    margin-top: 4px;
	    float: left;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-thumbs-type .review-list li {
		width: 100%;
		padding: 15px 15px 26px 15px;
		border-right: none;
		margin: 0;
		border: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-thumbs-type .review-list li {
		padding: 15px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-point,
	.wp-review-<?php echo $review['post_id']; ?> .review-percentage { margin-right: 40px; margin-top: 5px; }
	.wp-review-<?php echo $review['post_id']; ?> .wp-review-user-feature-rating-point + span,
	.wp-review-<?php echo $review['post_id']; ?> .wp-review-user-feature-rating-percentage + span {
	    margin-top: 5px;
	    display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating .review-point, .wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating .review-percentage { width: 100%; }
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list .review-count,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list .review-count {
		width: 44px;
		min-width: 42px;
		border: 2px solid <?php echo $colors['color']; ?>;
		padding: 9px 0 7px 0;
		text-align: center;
		position: absolute;
		right: 15px;
		top: 22px;
		border-radius: 25px;
		background: <?php echo $colors['bgcolor2']; ?>;
		z-index: 1;
	}
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item {
		border: none;
	}

	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 30px 20px 30px;
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-result-wrapper i {
		font-size: 18px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper li .review-result-wrapper .review-result i {
		color: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
		border-right: 1px solid <?php echo $colors['bordercolor']; ?>;
		padding: 30px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		padding: 30px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 12px 30px;
		border-top: 1px solid;
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wp-review-user-rating .review-result-wrapper .review-result { letter-spacing: -2.1px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		letter-spacing: 1px;
		font-weight: 700;
		padding: 15px 30px;
		text-transform: none;
		border-bottom: none;
		background: <?php echo $colors['bgcolor1']; ?>;
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		text-align: center;
		border: 10px solid <?php echo $colors['color']; ?>;
		border-radius: 50%;
		height: 180px;
		width: 180px;
		padding-top: 25px;
		clear: none;
		margin: 30px;
		box-sizing: border-box;
		position: absolute;
		right: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
		clear: both;
		width: 100%;
		border-top: 1px solid <?php echo $colors['bordercolor']; ?>;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
		padding: 15px;
		overflow: hidden;
		float: none;
		display: block;
		margin-left: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .review-star,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-feature-rating-star {
		margin-left: -2px;
		float: left;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper {
		width: auto;
		height: auto;
		padding: 14px 0 0 0;
		border: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		float: right;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
		height: 3px;
		margin-bottom: 0;
		background: <?php echo $colors['inactive_color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper li .review-point .review-result {
		background: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-total-wrapper .review-point.review-total,
	.wp-review-<?php echo $review['post_id']; ?> .review-total-wrapper .review-percentage.review-total {
		width: 70%;
		display: inline-block;
		margin: 0 auto;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box {
	    font-size: 34px;
		float: left;
		text-align: center;
		padding: 0;
		color: <?php echo $colors['fontcolor']; ?>;
		line-height: 1.1;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box h5 {
		margin-top: 10px;
		margin-bottom: 0;
		color: <?php echo $colors['fontcolor']; ?>;
		text-transform: uppercase;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title {
		display: inline-block;
		color: <?php echo $colors['fontcolor']; ?>;
		text-transform: uppercase;
		letter-spacing: 1px;
		padding: 0;
		border: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper h5.user-review-title {
		margin-top: 12px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper span.user-review-title {
		margin-top: 11px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 30px;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-percentage,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-point {
		width: 20%;
		float: right;
		margin-top: 10px;
		margin-right: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code {
		padding: 7px 30px 15px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code #wp_review_embed_code {
		background: rgba(0, 0, 0, 0.15);
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title {
		color: inherit;
		padding: 15px 30px;
		border-top: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating { clear: both; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-thumbs .wpr-thumbs-button .wpr-thumbs-icon {
		border: 1px solid rgba(0, 0, 0, 0.2);
		background: rgba(0, 0, 0, 0.2);
	}
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
		background: <?php echo $colors['color']; ?>;
		margin: 10px 30px;
		width: -moz-calc(100% - 60px);
		width: -webkit-calc(100% - 60px);
		width: -o-calc(100% - 60px);
		width: calc(100% - 60px);
		border-radius: 3px;
	}
	@media screen and (max-width:900px) {
		.wp-review-<?php echo $review['post_id']; ?> .review-list li {
			width: 46%;
		}
		.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-of-type(2n) {
			margin-right: 0;
		}
	}
	@media screen and (max-width:767px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
			width: 50%;
		}
	}
	@media screen and (max-width:600px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
			width: 100%;
			min-height: auto;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
			float: none;
			clear: both;
			position: static;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
			flex: 100%;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
			padding-top: 0;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
			float: left;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
			height: 10px;
		}
	}
	@media screen and (max-width:480px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-list li { width: 100%; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
			width: 100%;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
			float: none;
			clear: both;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title .review-price { float: none; }
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of darkside template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_darkside_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
