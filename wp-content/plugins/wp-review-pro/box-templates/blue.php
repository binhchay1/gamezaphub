<?php
/**
 * WP Review: Blue
 * Description: Blue Review Box template for WP Review
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
	<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Serif:400,700" rel="stylesheet">
	<style type="text/css">
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper { font-family: 'IBM Plex Serif', sans-serif; }
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

		<?php if ( $review['desc'] ) : ?>
			<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>

			<?php wp_review_load_template( 'global/partials/review-pros-cons.php', compact( 'review' ) ); ?>
		<?php endif; ?>

	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-features.php', compact( 'review' ) ); ?>

	<?php if ( ! empty( $review['total'] && ! $review['hide_desc'] ) ) : ?>
		<div class="review-total-wrapper">
			<h5><?php echo esc_attr( 'Overall', 'wp-review' ); ?></h5>
			<span class="review-total-box"><?php echo esc_html( wp_review_get_rating_text( $review['total'], $review['type'] ) ); ?></span>
			<?php if ( 'point' !== $review['type'] && 'percentage' !== $review['type'] ) : ?>
				<?php echo wp_review_get_total_rating( $review ); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! $is_embed && $review['user_review'] && ! $review['hide_visitors_rating'] ) : ?>
		<?php if ( ! wp_review_user_can_rate_features( $review['post_id'] ) ) : ?>
			<div class="user-review-area visitors-review-area">
				<div class="user-total-wrapper">
					<h5 class="user-review-title"><?php esc_html_e( 'User Review', 'wp-review' ); ?></h5>
					<span class="review-total-box">
						<span class="wp-review-user-rating-total"><?php echo esc_html( wp_review_get_rating_text( $review['user_review_total'], $review['user_review_type'] ) ); ?></span>
						<small>(<span class="wp-review-user-rating-counter"><?php echo esc_html( $review['user_review_count'] ); ?></span> <?php echo esc_html( _n( 'vote', 'votes', $review['user_review_count'], 'wp-review' ) ); ?>)</small>
					</span>
				</div>
				<?php echo wp_review_user_rating( $review['post_id'] ); ?>
			</div>
		<?php else : ?>
			<?php echo wp_review_visitor_feature_rating( $review['post_id'] ); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-comments-rating.php', compact( 'review' ) ); ?>

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
		padding: 15px 5px 5px;
		border: 3px solid <?php echo $colors['color']; ?>!important;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-desc p,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item p {
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		margin-top: -15px;
		width: -moz-calc(100% + 10px);
		width: -webkit-calc(100% + 10px);
		width: -o-calc(100% + 10px);
		width: calc(100% + 10px);
		margin-left: -5px;
		background: #689fce;
		color: #fff;
		padding: 15px 20px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a {
		color: #fff;
		background-color: <?php echo $colors['color']; ?>;
		border: 0;
		border-radius: 0;
		padding: 8px 25px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:nth-child(2n) {
		background: <?php echo $colors['bgcolor1']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item,
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		float: none;
		margin: 0 auto;
		text-align: center;
		color: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box {
		text-align: center;
		padding: 25px 0 20px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li .review-result-wrapper i { font-size: 16px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wp-review-user-rating .review-result-wrapper .review-result {
        letter-spacing: -1.7px;
    }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper h5 {
		color: inherit;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-circle.review-total {
		width: auto;
		padding-top: 10px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
		background: <?php echo $colors['color']; ?>;
		margin: 0 15px;
		width: -moz-calc(100% - 30px);
		width: -webkit-calc(100% - 30px);
		width: -o-calc(100% - 30px);
		width: calc(100% - 30px);
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of blue template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_blue_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
