<?php
/**
 * WP Review: Facebook
 * Description: Facebook Review Box template for WP Review
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

	<?php if ( ! $review['hide_desc'] ) : ?>
		<?php if ( ! empty( $review['total'] ) ) : ?>
			<div class="fb-review-total">
				<div class="review-total-wrapper">
					<span class="review-total-box">
						<?php echo esc_html( wp_review_get_rating_text( $review['total'], $review['type'] ) ); ?>
					</span>
					<?php
					if ( 'star' === $review['type'] ) {
						// translators: review total.
						$review_desc_text = sprintf( __( '%s of 5 stars', 'wp-review' ), $review['total'] );
						?>
						<i class="fa fa-star"></i>
						<?php
					} elseif ( 'point' === $review['type'] ) {
						// translators: review total.
						$review_desc_text = sprintf( __( '%s of 10 points', 'wp-review' ), $review['total'] );
					} elseif ( 'percentage' === $review['type'] || 'circle' === $review['type'] ) {
						// translators: review total.
						$review_desc_text = sprintf( __( '%s of 100', 'wp-review' ), $review['total'] );
					} else {
						$review_desc_text = '';
					}
					?>
				</div>
				<div class="fb-review-total-text">
					<span><?php echo $review_desc_text; ?></span>
					<span><?php esc_html_e( '1 review', 'wp-review' ); ?></span>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( $review['items'] && is_array( $review['items'] ) && empty( $review['disable_features'] ) || ! $review['hide_desc'] && $review['desc'] ) : ?>
		<div class="review-list-desc-wrapper">
			<?php wp_review_load_template( 'global/partials/review-features.php', compact( 'review' ) ); ?>

			<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>
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
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		flex: 2;
		margin: 0 30px;
		padding: 10px 0 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-desc p,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item p {
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a {
		background: <?php echo $colors['color']; ?>;
		color: #fff;
		padding: 6px 20px;
		border-radius: 3px;
		font-size: 16px;
		box-shadow: none;
		border: none;
		font-weight: bold;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a:hover {
		box-shadow: none;
		border: none;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 30px 20px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper .review-result i,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper i {
		color: #fff;
		opacity: 0.7;
		font-size: 20px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title {
		padding: 10px 30px;
		margin: 0;
		background: rgba(255, 255, 255, 0.3);
		color: inherit;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros {
		background: rgba(246, 247, 249,0.6);
		padding: 30px;
		box-sizing: border-box;
		border-right: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		background: rgba(246, 247, 249, 0.3);
		padding: 30px;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .mb-5 {
		text-transform: uppercase;
		letter-spacing: 1px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 10px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wp-review-user-rating .review-result-wrapper .review-result {
        letter-spacing: -1.8px;
    }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		font-weight: 700;
		padding: 15px 30px;
		background: <?php echo $colors['bgcolor1']; ?>;
		letter-spacing: 1px;
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .fb-review-total {
		overflow: hidden;
		display: flex;
		align-items: center;
		overflow: hidden;
		margin-top: 15px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .fb-review-total .fb-review-total-text {
		margin-left: 12px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .fb-review-total .fb-review-total-text span {
		display: block;
		line-height: 1.2;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		width: auto;
		color: #fff;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		border-top: 1px solid <?php echo $colors['bordercolor']; ?>;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
		text-align: center;
		background: <?php echo $colors['color']; ?>;
		padding: 5px 25px;
		display: flex;
		align-items: center;
		margin: 15px 0 15px 30px;
		float: left;
	}
	.wp-review-user-rating .review-result-wrapper span,
	.wp-review-comment-rating .review-result-wrapper span { padding: 0 0 0 3.5px }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper h5 {
		color: #fff;
		margin-bottom: 5px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper .review-result i { opacity: 1; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
		box-shadow: inset 0 -2px 0 rgba(0,0,0,0.1);
		height: 28px;
		margin-bottom: 0;
		background: <?php echo $colors['inactive_color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box {
		float: left;
		text-align: center;
		padding: 0;
		line-height: 1.5;
		font-size: 16px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-star-type .review-total-wrapper .review-total-box {margin-right: 7px;}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-total-wrapper .review-total-box,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-total-wrapper .review-total-box {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title {
		display: inline-block;
		margin: 0;
		padding: 0;
		color: inherit;
		background: transparent;
		border-bottom: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li {
		padding: 15px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list-desc-wrapper {
		display: flex;
		padding: 25px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-thumbs-type .review-list-desc-wrapper {
		flex-direction: column;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-thumbs-type .review-list-desc-wrapper .review-desc {
		margin-top: 15px;
		margin-bottom: 15px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
		border-top: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list-desc-wrapper .review-list {
		flex: 1;
		margin: 0 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list-desc-wrapper li {
		padding: 10px 0;
		border-bottom: none;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper>.review-total-box {
		display: block;
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
	.wp-review-<?php echo $review['post_id']; ?> .review-embed-code {
		padding: 7px 30px 15px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating { margin-top: 0; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item,
	.wp-review-<?php echo $review['post_id']; ?> .review-links,
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating {
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	@media screen and (max-width:480px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons { padding: 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title { padding: 10px 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links { padding: 15px 15px 5px; }
		.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
			margin: 10px 15px;
			width: -moz-calc(100% - 30px);
			width: -webkit-calc(100% - 30px);
			width: -o-calc(100% - 30px);
			width: calc(100% - 30px);
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list-desc-wrapper {
			flex-direction: column;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list-desc-wrapper .review-list,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
			margin: 0;
		}
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
			margin-left: 10px;
		}
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of facebook template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_facebook_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
