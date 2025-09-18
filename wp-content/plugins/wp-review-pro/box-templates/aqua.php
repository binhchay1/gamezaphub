<?php
/**
 * WP Review: Aqua
 * Description: Aqua Review Box template for WP Review
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
	<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
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
					<span class="review-price"><?php echo esc_html( $review['product_price'] ); ?></span>
				<?php endif; ?>
			</h5>
		</div>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-schema.php', compact( 'review' ) ); ?>

	<?php if ( ! empty( $review['total'] && ! $review['hide_desc'] && 'thumbs' !== $review['type'] ) ) : ?>
		<div class="review-total-wrapper">
			<div class="review-total-box">
				<h5><?php esc_html_e( 'Overall', 'wp-review' ); ?></h5>
				<div><?php echo wp_review_get_rating_text( $review['total'], $review['type'] ); ?></div>
			</div>
			<?php echo wp_review_get_total_rating( $review ); ?>
		</div>
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

		<?php if ( $review['desc'] ) : ?>
			<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>

			<?php wp_review_load_template( 'global/partials/review-pros-cons.php', compact( 'review' ) ); ?>
		<?php endif; ?>

	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-links.php', compact( 'review' ) ); ?>

	<?php wp_review_load_template( 'global/partials/review-embed.php', compact( 'review' ) ); ?>
</div>

<?php
$colors     = $review['colors'];
$dark_color = wp_review_color_luminance( $colors['color'], '-0.2' );

ob_start();
// phpcs:disable
?>
<style type="text/css">
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		width: <?php echo $review['width']; ?>%;
		float: <?php echo $review['align']; ?>;
		border: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		padding: 25px 30px 25px 30px;
		line-height: 26px;
		clear: both;
		border-bottom: 1px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-desc p,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item p {
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a {
		background: <?php echo $colors['color']; ?>;
		padding: 9px 20px 6px 20px;
		box-shadow: 0 2px <?php echo $dark_color; ?>, inset 0 1px rgba(255,255,255,0.2);
		border: none;
		color: #fff;
		border: 1px solid <?php echo $dark_color; ?>;
		cursor: pointer;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li {
		padding: 30px 30px 20px 30px;
		width: 50%;
		float: left;
		border-right: 1px solid <?php echo $colors['bordercolor']; ?>;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-star-type .wpr-user-features-rating .review-list {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-thumbs-type .review-list li {
		width: 100%;
		padding: 12px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-circle-type .review-list li {
		width: 100%;
		padding: 10px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li {
		width: 100%;
		padding: 15px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li > span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li > span {
		display: inline-block;
		position: absolute;
		z-index: 1;
		top: 23px;
		left: 45px;
		font-size: 14px;
		line-height: 1;
		color: <?php echo $colors['bgcolor2']; ?>;
		-webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .wpr-user-features-rating .review-list li > span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .wpr-user-features-rating .review-list li > span {
	    color: inherit;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .wpr-user-features-rating .review-list li .wp-review-input-set + span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .wpr-user-features-rating .review-list li .wp-review-input-set + span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .wpr-user-features-rating .review-list li .wp-review-user-rating:hover + span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .wpr-user-features-rating .review-list li .wp-review-user-rating:hover + span {
	    color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-star-type .review-list li:nth-child(2n+1) { clear: left; border-right: 1px solid <?php echo $colors['bordercolor']; ?>; }
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 30px 20px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper {
		height: 28px;
	}
	.wp-review-comment-<?php echo $review['post_id']; ?> .wp-review-comment-rating .review-point .review-result-wrapper .review-result,
	.wp-review-comment-<?php echo $review['post_id']; ?> .wp-review-comment-rating .review-percentage .review-result-wrapper .review-result {
	    height: 22px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-result-wrapper i {
		font-size: 18px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
		padding: 30px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		padding: 30px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros .mb-5,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons .mb-5 {
		font-weight: bold;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
		margin-bottom: 10px;
		padding-bottom: 8px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .mb-5 {
		text-transform: uppercase;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating {
		margin-top: -1px;
		clear: both;
		float: left;
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 18px 30px;
		border-top: 1px solid;
		margin-top: -1px;
		float: left;
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		letter-spacing: 1px;
		font-weight: 700;
		padding: 15px 30px;
		text-transform: none;
		background: <?php echo $colors['bgcolor1']; ?>;
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		width: 40%;
		margin: 0;
		padding: 42px 0;
		color: #fff;
		text-align: center;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
		clear: none;
		width: 60%;
	}
	<?php if( $review['hide_desc'] ) { ?>
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
		width: 100%;
	}
	<?php } ?>
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .review-star,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-feature-rating-star {
		float: left;
		display: block;
		margin: 10px 0 0 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-feature-rating-star + span { clear: left; display: block; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-rating.wp-review-user-feature-rating-star,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-rating.wp-review-user-feature-rating-star .review-star {
	    margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-rating.wp-review-user-feature-rating-star .review-result-wrapper {
	    margin-left: -5px;
        margin-bottom: 6px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-feature-rating-star .review-result { letter-spacing: -2.2px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper {
		padding: 20px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		margin: 0 auto;
		padding-top: 10px;
		clear: both;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-total-wrapper .review-point.review-total,
	.wp-review-<?php echo $review['post_id']; ?> .review-total-wrapper .review-percentage.review-total {
		width: 70%;
		display: inline-block;
		margin: 20px auto 0 auto;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box > div { display: none; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box {
		float: left;
		text-align: center;
		padding: 0;
		color: <?php echo $colors['fontcolor']; ?>;
		line-height: 1.5;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box h5 {
		margin-top: 6px;
		color: inherit;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-total-wrapper .review-total-box,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-total-wrapper .review-total-box,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating .review-list,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-thumbs-type .review-list {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating .review-list li {
	    border-right: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		color: #fff;
		margin-top: 10px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title {
		color: inherit;
		padding: 18px 30px 16px;
		margin: 0;
		border-bottom: 1px solid;
		border-top: 1px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title {
		display: inline-block;
		color: inherit;
		text-transform: uppercase;
		letter-spacing: 1px;
		padding: 0;
		border: 0;
		margin-top: 3px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper h5.user-review-title {
		margin-top: 12px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper span.user-review-title {
		margin-top: 4px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-percentage,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-point {
		width: 20%;
		float: right;
		margin-top: -2px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code { padding: 10px 30px 15px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item,
	.wp-review-<?php echo $review['post_id']; ?> .review-links,
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		border-color: <?php echo $colors['bordercolor']; ?>;
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
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area .review-circle,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .review-circle,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-circle .review-result-wrapper { height: 32px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .wp-review-user-rating .review-circle { height: 50px; }
	@media screen and (max-width:570px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
			width: 100%;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
			width: 100%;
			border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
			border-left: 0;
			padding: 15px 0;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-star-type .review-list li:nth-child(2n+1) { clear: none; border-right: 0; }
	}
	@media screen and (max-width:480px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons { padding: 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons > div > div { padding: 15px; padding-top: 0; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title { padding: 12px 15px; border-right: 0; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .ui-tabs-nav { padding: 0 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links { padding: 15px 15px 5px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper { max-width: 60%; font-size: 14px; }
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li > span,
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li > span {
    		top: 12px;
    		left: 30px;
    	}
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of aqua template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_aqua_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
