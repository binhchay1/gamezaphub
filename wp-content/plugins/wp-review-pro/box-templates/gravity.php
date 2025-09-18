<?php
/**
 * WP Review: Gravity
 * Description: Gravity Review Box template for WP Review
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
	<link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
	<style type="text/css">
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper { font-family: 'Comfortaa', cursive; }
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
					- <span class="review-price"><?php echo esc_html( $review['product_price'] ); ?></span>
				<?php endif; ?>
			</h5>
		</div>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-schema.php', compact( 'review' ) ); ?>

	<?php wp_review_load_template( 'global/partials/review-features.php', compact( 'review' ) ); ?>

	<?php if ( ! empty( $review['total'] ) && ! $review['hide_desc'] ) : ?>
		<div class="review-total-wrapper">
			<div class="review-total-box">
				<h5><?php esc_html_e( 'Overall', 'wp-review' ); ?></h5>
				<div><?php echo esc_html( wp_review_get_rating_text( $review['total'], $review['type'] ) ); ?></div>
			</div>
			<?php echo wp_review_get_total_rating( $review ); ?>
		</div>
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

		<?php if ( $review['desc'] ) : ?>
			<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>

			<?php wp_review_load_template( 'global/partials/review-pros-cons.php', compact( 'review' ) ); ?>
		<?php endif; ?>

	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-links.php', compact( 'review' ) ); ?>

	<?php wp_review_load_template( 'global/partials/review-embed.php', compact( 'review' ) ); ?>
</div>

<?php
$colors           = $review['colors'];
$dark_color       = wp_review_color_luminance( $colors['color'], '-0.1' );
$light_color      = wp_review_color_luminance( $colors['color'], '0.05' );
$darkborder_color = wp_review_color_luminance( $colors['color'], '-0.5' );

ob_start();
// phpcs:disable
?>
<style type="text/css">
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		width: <?php echo $review['width']; ?>%;
		float: <?php echo $review['align']; ?>;
		border: none;
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		width: 100%;
		padding: 25px 30px 25px 30px;
		background: <?php echo $colors['bordercolor']; ?>;
		float: right;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc .review-summary-title {
		text-transform: uppercase;
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
		padding: 10px 20px 10px 20px;;
		border: 0;
		box-shadow: none;
		color: <?php echo $colors['fontcolor']; ?>;
		border-radius: 25px;
		cursor: pointer;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a:hover,
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn:hover {
		background: <?php echo $colors['bgcolor1']; ?>;
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li {
		padding: 12px 30px 12px 30px;
		border: none;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-child(even), .wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating .user-review-title {
		background: <?php echo $colors['bgcolor1']; ?>;
		color: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating .user-review-title {
		margin: 0;
		padding: 12px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-price { float: none; }
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li {
		padding: 14px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-thumbs-type .review-list li .review-result-wrapper i {
		background: transparent;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li span { float: right; line-height: 50px; }
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li span span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li span span {
		background: <?php echo $colors['color']; ?>;
		width: 50px;
		font-size: 13px;
		line-height: 50px;
		text-align: center;
		border-radius: 40px;
		color: <?php echo $colors['bgcolor1']; ?>;
		margin-left: 15px;
		display: inline-block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li .review-point,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li .review-percentage {
		width: 60%;
		float: left;
		clear: none;
		margin-top: 22px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li .wp-review-your-rating-value,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li .wp-review-your-rating-value{ line-height: 1.2; }
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item,
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		border: none;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 30px 20px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-result-wrapper i {
		font-size: 18px;
		color: <?php echo $colors['inactive_color']; ?>;
		background: <?php echo $colors['color']; ?>;
		border-radius: 15px;
		width: 27px;
		line-height: 27px;
		text-align: center;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wp-review-user-rating .review-result-wrapper:hover span i {
		color: <?php echo $colors['bgcolor1']; ?>!important;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wp-review-user-rating .review-result-wrapper span {
	    margin: 0;
	    margin-left: 4px;
	    padding: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wp-review-user-rating .review-result-wrapper .review-result { letter-spacing: -10px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wp-review-user-rating .review-result-wrapper span:hover ~ span i {
		color: <?php echo $colors['inactive_color']; ?>!important;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-result-wrapper .review-result i {
		color: <?php echo $colors['bgcolor1']; ?> !important;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wp-review-user-rating .review-result-wrapper .review-result i {
	    letter-spacing: 0;
        padding-left: 0;
        margin-left: 4px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total .review-result-wrapper .review-result {
		height: 44px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total .review-result-wrapper i {
		font-size: 36px;
		line-height: 1.4;
		width: 54px;
		border-radius: 50%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
		border-top: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
		padding: 30px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		padding: 30px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros .mb-5 {
		background: <?php echo $colors['color']; ?>;
		padding: 15px 15px 10px 15px;
		color: <?php echo $colors['fontcolor']; ?>;
		margin-bottom: 25px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons .mb-5 {
		background: <?php echo $colors['bgcolor1']; ?>;
		padding: 15px 15px 10px 15px;
		color: #fff;
		margin-bottom: 25px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 12px 30px;
		border: none;
		float: left;
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		letter-spacing: 1px;
		padding: 15px 30px;
		color: <?php echo $colors['color']; ?>;
		border: 0;
		text-align: center;
		background: <?php echo $colors['bgcolor1']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		width: 100%;
		margin: 0;
		padding: 35px 0;
		background: <?php echo $colors['bgcolor1']; ?>;
		text-align: center;
		float: left;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper {
		padding: 30px 0 40px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .review-circle { height: 32px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-rating-input.review-circle { height: 50px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		margin: 47px auto;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
		height: 8px;
		margin-bottom: 0;
		background: <?php echo $colors['inactive_color']; ?>;
		border-radius: 25px;
		overflow: hidden;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper {
		border: 1px solid <?php echo $colors['bgcolor1']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper li .review-point .review-result {
		background: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-total-wrapper .review-point.review-total,
	.wp-review-<?php echo $review['post_id']; ?> .review-total-wrapper .review-percentage.review-total {
		width: 70%;
		display: inline-block;
		margin: 20px auto 0 auto;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box {
		float: left;
		text-align: center;
		padding: 0;
		color: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box div { padding: 20px 0; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box h5 {
		color: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-total-wrapper .review-total-box,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-total-wrapper .review-total-box {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		color: #fff;
		margin-top: 10px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper h5.user-review-title {
		margin-top: 12px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper span.user-review-title {
		margin-top: 11px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 30px;
		background: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-percentage,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-point {
		width: 20%;
		float: right;
		margin-top: 5px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		margin: auto 0;
		padding-top: 15px;
		width: auto;
		height: 100%;
		clear: both;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box > div { display: none; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box h5 {
		color: inherit;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-embed-code { padding: 7px 30px 15px; }
	.wp-review-<?php echo $review['post_id']; ?> .review-embed-code #wp_review_embed_code { background: rgba(255, 255, 255, 0.5) }
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
		background: <?php echo $colors['color']; ?>;
		background: linear-gradient(to top, <?php echo $colors['color']; ?>, <?php echo $light_color; ?>);
		color: <?php echo $colors['fontcolor']; ?>;
		margin: 10px 30px 12px;
		width: -moz-calc(100% - 60px);
		width: -webkit-calc(100% - 60px);
		width: -o-calc(100% - 60px);
		width: calc(100% - 60px);
		border-radius: 50px;
	}
	@media screen and (max-width:600px) {
	    .wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li .review-point,
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li .review-percentage {
			width: 40%;
		}
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li .wp-review-user-rating .review-point,
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li .wp-review-user-rating .review-percentage {
			width: 100%;
		}
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .wpr-user-features-rating .review-list li span,
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .wpr-user-features-rating .review-list li span {
            float: left;
            line-height: 1.4;
            font-size: 14px;
        }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
			padding: 15px 30px;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
			padding: 15px 30px;
			padding-top: 0;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper {
			max-width: 100%;
			float: left;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper { max-width: 70%; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper h5,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title { font-size: 14px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-percentage,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-point {
			width: 50.5%;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links { padding: 15px 30px 5px; }
	}
	@media screen and (max-width: 480px) {
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item,
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc { padding: 15px; }
	    .wp-review-<?php echo $review['post_id']; ?> .review-list li,
	    .wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li,
        .wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li,
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating .user-review-title,
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area,
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros,
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons,
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links,
	    .wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code { padding: 12px 15px; }
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of gravity template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_gravity_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
