<?php
/**
 * WP Review: Dash
 * Description: Dash Review Box template for WP Review
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
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper { font-family: 'Lato', cursive; }
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

	<?php if ( ! $review['hide_desc'] ) : ?>

		<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>

		<?php if ( ! empty( $review['total'] ) ) : ?>
			<div class="review-total-wrapper">
				<div class="review-total-box">
					<h5><?php esc_html_e( 'Overall', 'wp-review' ); ?></h5>
					<div><?php echo esc_html( wp_review_get_rating_text( $review['total'], $review['type'] ) ); ?></div>
				</div>

				<?php echo wp_review_get_total_rating( $review ); ?>
			</div>
		<?php endif; ?>

	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-features.php', compact( 'review' ) ); ?>

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
			<?php echo wp_review_visitor_feature_rating( $review['post_id'], array( 'title_first' => false ) ); ?>
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
		border: 2px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		width: 60%;
		padding: 25px 22px 25px 22px;
		float: left;
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
		color: #fff;
		cursor: pointer;
		border-radius: 3px	;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a:hover {
		box-shadow: none;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li {
		padding: 30px 20px 20px 20px;
		width: 25%;
		float: left;
		border-right: 2px solid;
		border-bottom: 0;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-of-type(4n) {
		border-right: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li {
		width: 33.33%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li:nth-child(3n+3) { border-right: 0; }
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li {
		width: 50%;
		padding: 12px 22px;
		border-right: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-thumbs-type .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li{
	    width: 100%;
		padding: 12px 22px;
		border-right: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list .review-count,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list .review-count {
		width: 5%;
		border: 2px solid;
		padding: 6px 0 1px 0;
		float: right;
		text-align: center;
		position: absolute;
		right: 27px;
		top: 28px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list .review-point,
	.wp-review-<?php echo $review['post_id']; ?> .review-list .review-percentage {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item {
		border: none;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 22px 20px 22px;
		border-bottom: 2px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-result-wrapper i {
		font-size: 18px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
		border-bottom: 2px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
		border-right: 2px solid;
		padding: 20px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		padding: 20px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 15px 22px;
		border-bottom: 0;
		border-bottom: 2px solid;
		margin-top: -2px;
		float: left;
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wp-review-user-rating .review-result-wrapper .review-result {
        letter-spacing: -1.4px;
    }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		letter-spacing: 1px;
		font-weight: 700;
		padding: 15px 22px;
		text-transform: none;
		border-bottom: none;
		background: <?php echo $colors['bgcolor1']; ?>;
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		width: 40%;
		margin: 0;
		padding: 35px 0;
		clear: none;
		color: #fff;
		background: <?php echo $colors['bgcolor2']; ?>;
		text-align: center;
		border-left: 2px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating {
		clear: both;
		width: 100%;
		overflow: hidden;
		border-top: 2px solid;
		border-bottom: 2px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating {
		border: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .review-star,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-feature-rating-star {
		float: left;
		display: block;
		margin: 0 0 0 -2px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper {
		padding: 20px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
		height: 15px;
		margin-bottom: 0;
		background: <?php echo $colors['inactive_color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper li .review-point .review-result {
		background: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper li:nth-of-type(2n) .review-point .review-result {
		background: <?php echo $colors['bgcolor1']; ?>;
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
		color: <?php echo $colors['fontcolor']; ?>;
		line-height: 1.5;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box h5 {
		margin-top: 10px;
		color: inherit;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-total-wrapper .review-total-box,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-total-wrapper .review-total-box {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box > div { display: none; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		color: #fff;
		margin-top: 10px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title {
		display: inline-block;
		color: <?php echo $colors['fontcolor']; ?>;
		text-transform: uppercase;
		letter-spacing: 1px;
		padding: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		margin: auto 0;
		padding-top: 10px;
		width: auto;
		height: 100%;
		clear: both;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper h5.user-review-title {
		margin-top: 12px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper span.user-review-title {
		margin-top: 4px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 22px;
		border-bottom: 2px solid;
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
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating {
		float: left;
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title {
		color: inherit;
		padding: 12px 22px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-embed-code { padding: 7px 22px 15px; }
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
		background: <?php echo $colors['color']; ?>;
		margin: 10px 22px 0;
		width: -moz-calc(100% - 44px);
		width: -webkit-calc(100% - 44px);
		width: -o-calc(100% - 44px);
		width: calc(100% - 44px);
		border-radius: 3px;
	}
	@media screen and (max-width:1100px) {
		.wp-review-<?php echo $review['post_id']; ?> .review-list li {
			padding: 30px 20px 20px 20px;
		}
	}
	@media screen and (max-width:767px) {
		.wp-review-<?php echo $review['post_id']; ?> .review-list li {
			width: 50%;
			border-bottom: 2px solid;
		}
		.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
		.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-last-child(-n + 2):nth-child(2n + 1),
		.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-last-child(-n + 2):nth-child(2n + 1) ~ li { border-bottom: 0; }
		.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-of-type(2n) {
			border-right: none;
		}
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li:nth-child(3n+3) { border-right: 2px solid <?php echo $colors['bordercolor']; ?>; }
	}
	@media screen and (max-width:480px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
			width: 100%;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
			flex: 100%;
			padding: 15px;
			border: 0;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
			padding-top: 0;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
			border-left: none;
			border-top: 2px solid <?php echo $colors['bordercolor']; ?>;
			padding: 15px;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links { padding: 15px 15px 5px; }
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li:nth-child(3n+3) { border-right: 0; }
    	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li,
    	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li {
    	    width: 100%;
    	}
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of dash template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_dash_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
