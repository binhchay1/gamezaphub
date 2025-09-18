<?php
/**
 * WP Review: Tabbed
 * Description: Tabbed Review Box template for WP Review
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

$show_review_list     = $review['items'] && is_array( $review['items'] ) && empty( $review['disable_features'] );
$show_review_desc     = ! $review['hide_desc'] && $review['desc'];
$show_author_tab      = $show_review_list || $show_review_desc;
$show_visitors_rating = ! $is_embed && $review['user_review'] && ! $review['hide_visitors_rating'];
$show_comments_rating = ! $is_embed && $review['comments_review'] && ! $review['hide_comments_rating'];
$show_user_tab        = $show_visitors_rating;

$ui_tab = ! $is_embed && $show_author_tab && $show_user_tab ? 'data-wp-review-tabs' : '';

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
		<h5 class="review-title">
			<?php echo esc_html( $review['heading'] ); ?>

			<?php if ( ! empty( $review['product_price'] ) ) : ?>
				<span class="review-price"><?php echo esc_html( $review['product_price'] ); ?></span>
			<?php endif; ?>
		</h5>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-schema.php', compact( 'review' ) ); ?>

	<div class="review-tabs" <?php echo $ui_tab; ?>>
		<?php if ( ! $is_embed && $show_author_tab && $show_user_tab ) : ?>
			<ul class="tab-titles">
				<li class="tab-title"><button type="button" data-href="#review-tab-author"><?php esc_html_e( 'Author', 'wp-review' ); ?></button></li>
				<li class="tab-title"><button type="button" data-href="#review-tab-users"><?php esc_html_e( 'Users', 'wp-review' ); ?></button></li>
			</ul>
		<?php endif; ?>

		<?php if ( $show_author_tab ) : ?>
			<div class="tab-content" id="review-tab-author" style="display: none;">
				<?php wp_review_load_template( 'global/partials/review-features.php', compact( 'review' ) ); ?>

				<?php if ( $show_review_desc ) : ?>
					<?php if ( ! empty( $review['total'] ) ) : ?>
						<div class="review-total-wrapper">
							<span class="review-total-box"><?php echo esc_html( wp_review_get_rating_text( $review['total'], $review['type'] ) ); ?></span>
							<?php if ( 'point' !== $review['type'] && 'percentage' !== $review['type'] ) : ?>
								<?php echo wp_review_get_total_rating( $review ); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $review['desc'] ) : ?>
						<?php wp_review_load_template( 'global/partials/review-desc.php', compact( 'review' ) ); ?>

						<?php wp_review_load_template( 'global/partials/review-pros-cons.php', compact( 'review' ) ); ?>
					<?php endif; ?>
				<?php endif; ?>
			</div><!-- End #review-tab-author -->
		<?php endif; ?>

		<?php if ( $show_user_tab ) : ?>
			<div class="tab-content" id="review-tab-users" style="display: none;">
				<?php if ( ! wp_review_user_can_rate_features( $review['post_id'] ) ) : ?>
					<div class="user-review-area visitors-review-area">
						<?php echo wp_review_user_rating( $review['post_id'] ); ?>
						<div class="user-total-wrapper">
							<span class="review-total-box">
								<span class="wp-review-user-rating-total"><?php echo esc_html( wp_review_get_rating_text( $review['user_review_total'], $review['user_review_type'] ) ); ?></span>
								<small>(<span class="wp-review-user-rating-counter"><?php echo esc_html( $review['user_review_count'] ); ?></span> <?php echo esc_html( _n( 'vote', 'votes', $review['user_review_count'], 'wp-review' ) ); ?>)</small>
							</span>
						</div>
					</div>
				<?php else : ?>
					<?php echo wp_review_visitor_feature_rating( $review['post_id'] ); ?>
				<?php endif; ?>
			</div><!-- End #review-tab-users -->
		<?php endif; ?>
	</div><!-- End .review-tabs -->

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
	.wp-review-<?php echo $review['post_id']; ?> [data-ui-tabs] .ui-tabs-nav a,
	.wp-review-<?php echo $review['post_id']; ?> [data-wp-review-tabs] .tab-title button,
	.wp-review-<?php echo $review['post_id']; ?> .review-embed-code #wp_review_embed_code {
		color: <?php echo $colors['fontcolor']; ?>;
		background: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> [data-ui-tabs] .ui-tabs-nav .ui-state-active a,
	.wp-review-<?php echo $review['post_id']; ?> [data-wp-review-tabs] .tab-title.active button {
		background: <?php echo $colors['color']; ?>; color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?> [data-ui-tabs] .ui-tabs-nav,
	.wp-review-<?php echo $review['post_id']; ?> [data-wp-review-tabs] .tab-titles {
		padding: 0 30px;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
		list-style: none;
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		width: <?php echo $review['width']; ?>%;
		float: <?php echo $review['align']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		padding: 25px 30px 25px 30px;
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
		padding: 10px 25px;
		border-radius: 25px;
		border: none;
		cursor: pointer;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-star-type .review-list li .review-star + span {
		background: <?php echo $colors['bgcolor2']; ?>;
		position: relative;
		z-index: 1;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li {
		box-sizing: border-box;
		padding: 10px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child {
	    padding-bottom: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li > span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li > span {
		position: absolute;
		top: 19px;
		left: 15px;
		line-height: 1;
		color: #fff;
		font-size: 14px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item {
		border: none;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 25px 20px 25px;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-result-wrapper .review-result i {
		color: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper .review-result i,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper i {
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		padding: 0;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros .mb-5,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons .mb-5 {
		background: <?php echo $colors['bordercolor']; ?>;
		padding: 10px 20px 10px 30px;
		color: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons .mb-5 {
		border-left: 1px solid <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros ul,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons ul {
		padding: 10px 0 20px;
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons > div > div {
		padding: 10px 30px 30px 30px;
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons > div > div p:last-of-type {
		margin-bottom: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 15px 25px;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wp-review-user-rating .review-result-wrapper .review-result {
        letter-spacing: -1.45px;
    }
	.wp-review-<?php echo $review['post_id']; ?> #review-tab-author .user-review-area.comments-review-area {
		border-top: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		border: none;
		font-weight: 700;
		padding: 15px 30px 10px 30px;
		background: <?php echo $colors['bgcolor1']; ?>;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		min-width: 150px;
		margin: 30px;
		color: #fff;
		text-align: center;
		background: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper {
		background: transparent;
		padding: 20px 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		margin: 0 auto;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
		box-shadow: inset 0px 0px 3px rgba(0,0,0,0.1);
		height: 32px;
		margin-bottom: 0;
		background: <?php echo $colors['inactive_color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box {
		text-align: center;
		padding: 25px 0 20px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-total-box small {
		margin-top: 10px;
		text-align: center;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-total-wrapper .review-total-box,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-total-wrapper .review-total-box {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title {
		display: inline-block;
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 30px 30px 20px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
		overflow: hidden;
		padding: 0 25px 20px 30px;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list .review-circle {
	    margin-top: 0;
	    margin-bottom: -5px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code { padding: 10px 25px 30px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .wpr-user-features-rating .user-review-title { display: none; }
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
		background: <?php echo $colors['color']; ?>;
	}
	@media screen and (max-width:480px) {
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code { padding: 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons > div > div { padding: 15px; padding-top: 0; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .ui-tabs-nav { padding: 0 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links { padding: 15px 15px 5px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros .mb-5,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons .mb-5 {
			padding: 10px 15px;
			border: 0;
		}
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of tabbed layout template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_tabbed-layout_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
