<?php

/** @var bool $is_show_description */

use Lasso\Classes\Helper as Lasso_Helper;
use Lasso\Classes\Html_Helper as Lasso_Html_Helper;

/** @var bool $is_show_disclosure */
/** @var bool $is_show_fields */
/** @var string $type */

?>

<style>
	.lasso-box-3 {
		display: flex;
	}
</style>
<div <?php echo $anchor_id_html ?> class="lasso-container">
	<div class="lasso-display <?php echo $theme_name . ' lasso-url-' . $lasso_url->slug . ' ' . $css_display_theme_mobile ?? ''; ?>">
		<?php if (!empty($lasso_url->display->badge_text)) { ?>
			<div class="lasso-badge">
				<?php echo $lasso_url->display->badge_text; ?>
			</div>
		<?php } ?>

		<div class="lasso-box-1">
			<?php if ($lasso_post->is_show_title()) : ?>
				<?php echo $title_type_start; ?>
				<?php if ($lasso_url->link_from_display_title) : ?>
					<a class="lasso-title" <?php echo $lasso_url_obj->render_attributes($lasso_url->title_url) ?>>
						<?php echo html_entity_decode($lasso_url->name); ?>
					</a>
				<?php else : ?>
					<span class="lasso-title"><?php echo html_entity_decode($lasso_url->name); ?></span>
				<?php endif; ?>
				<?php echo $title_type_end; ?>
			<?php endif; ?>

			<?php if ($is_show_fields && $lasso_url->fields->primary_rating && ($lasso_url->fields->primary_rating->field_value != '')) { ?>
				<div class="lasso-stars" style="--rating: <?php echo $lasso_url->fields->primary_rating->field_value; ?>">
					<?php if ('true' === $lasso_url->fields->primary_rating->show_field_name) : ?>
						<label class="lasso-stars-label float-left mr-1"><strong><?php echo $lasso_url->fields->primary_rating->field_name; ?>:</strong></label>
					<?php endif; ?>
					<span class="lasso-stars-value">
						<?php echo Lasso_Helper::show_decimal_field_rate($lasso_url->fields->primary_rating->field_value); ?>
					</span>
				</div>
			<?php } ?>

			<?php if (isset($lasso_url->rating)) { ?>
				<div class="lasso-stars" style="--rating: <?php echo $lasso_url->rating; ?>">
				</div>
			<?php } ?>

			<?php if (('' !== $lasso_url->price && $lasso_url->display->show_price) || $lasso_url->amazon->is_prime) { ?>
				<div class="lasso-price">
					<?php if ('' !== $lasso_url->price && $lasso_url->display->show_price) { ?>
						<?php $discount_price_html = $lasso_url->amazon->show_discount_pricing ? $lasso_url->amazon->discount_pricing_html : ''; ?>
						<div class="lasso-price-value">
							<span class="discount-price"><?php echo $discount_price_html; ?></span>
							<span class="latest-price"><?php echo $lasso_url->price; ?></span>
						</div>
					<?php } ?>
					<?php if ($lasso_url->amazon->is_prime) { ?>
						<i class="lasso-amazon-prime"></i>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if (isset($lasso_url->categories)) { ?>
				<div class="categories">
					<span class="title-detail">Categories: </span><span><?php echo $lasso_url->categories ?></span>
				</div>
			<?php } ?>

			<?php if (isset($lasso_url->developer)) { ?>
				<div class="developer">
					<span class="title-detail">Developer: </span><span><?php echo $lasso_url->developer ?></span>
				</div>
			<?php } ?>

			<?php if (isset($lasso_url->size)) { ?>
				<div class="size">
					<span class="title-detail">Size: </span><span><?php echo $lasso_url->size ?></span>
				</div>
			<?php } ?>

			<?php if (isset($lasso_url->version)) { ?>
				<div class="version">
					<span class="title-detail">Version: </span><span><?php echo $lasso_url->version ?></span>
				</div>
			<?php } ?>

			<?php if (isset($lasso_url->updated_on)) { ?>
				<div class="updated-on">
					<span class="title-detail">Updated on: </span><span><?php echo $lasso_url->updated_on ?></span>
				</div>
			<?php } ?>
			
			<ins class="adsbygoogle"
     style="display:block; text-align:center;"
     data-ad-layout="in-article"
     data-ad-format="fluid"
     data-ad-client="ca-pub-2064972950158195"
     data-ad-slot="1037220895"></ins>

			<div class="clear"></div>
			<?php if ($lasso_post->is_show_description() && $is_show_description) : ?>
				<div class="lasso-description">
					<?php echo $lasso_url->description; ?>
				</div>
			<?php endif; ?>

			<?php if ($is_show_fields && $lasso_url->fields->user_created) { ?>
				<div class="lasso-fields">
					<?php foreach ($lasso_url->fields->user_created as $field) { ?>
						<?php
						Lasso_Helper::include_with_variables(
							LASSO_PLUGIN_PATH . '/admin/views/displays/field-row.php',
							array(
								'field_data' => $field
							),
							false
						);
						?>
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		<div class="lasso-box-2">
			<a class="lasso-image" <?php echo $lasso_url_obj->render_attributes($lasso_url->title_url); ?>>
				<img src="<?php echo $lasso_url->image_src; ?>" height="500" width="500" <?php echo Lasso_Html_Helper::build_img_lazyload_attributes() ?> alt="<?php echo $image_alt; ?>">
			</a>
		</div>

		<div class="lasso-box-3" style="flex: 0 0 100% !important; max-width: 100% !important;">
			<?php if ('' === $lasso_url->apple_btn_url && '' === $lasso_url->google_btn_url) { ?>
				<a class="lasso-button-1" <?php echo $lasso_url_obj->render_attributes() ?>>
					<?php echo $lasso_url->display->primary_button_text; ?>
				</a>
			<?php } ?>

			<?php if ('' !== $lasso_url->apple_btn_url) { ?>
				<a class="lasso-button-apple lasso-btn-shortcode" title="<?php echo $lasso_url->name ?>" href="<?php echo $lasso_url->apple_btn_url ?>" target="_blank" rel="nofollow noopener sponsored">
					<?php echo $lasso_url->display->apple_btn_text; ?>
				</a>
			<?php } ?>

			<?php if ('' !== $lasso_url->google_btn_url) { ?>
				<a class="lasso-button-google lasso-btn-shortcode" title="<?php echo $lasso_url->name ?>" href="<?php echo $lasso_url->google_btn_url ?>" target="_blank" rel="nofollow noopener sponsored">
					<?php echo $lasso_url->display->google_btn_text; ?>
				</a>
			<?php } ?>

			<?php if ('' !== $lasso_url->display->third_url) { ?>
				<a class="lasso-button-3 lasso-btn-shortcode" title="<?php echo $lasso_url->name ?>" href="<?php echo $lasso_url->display->third_url ?>" target="_blank" rel="nofollow noopener sponsored">
					<?php echo $lasso_url->display->third_btn_text; ?>
				</a>
			<?php } ?>

			<?php if ('' !== $lasso_url->display->fourth_url) { ?>
				<a class="lasso-button-6 lasso-btn-shortcode" title="<?php echo $lasso_url->name ?>" href="<?php echo $lasso_url->display->fourth_url ?>" target="_blank" rel="nofollow noopener sponsored">
					<?php echo $lasso_url->display->fourth_btn_text; ?>
				</a>
			<?php } ?>

			<?php if ('' !== $lasso_url->display->secondary_url) { ?>
				<a class="lasso-button-6 lasso-btn-shortcode" title="<?php echo $lasso_url->name ?>" href="<?php echo $lasso_url->display->secondary_url ?>" target="_blank" rel="nofollow noopener sponsored">
					<?php echo $lasso_url->display->secondary_button_text; ?>
				</a>
			<?php } ?>
		</div>

		<?php if ($is_show_disclosure) : ?>
			<div class="lasso-box-9">
				<div class="lasso-disclosure">
					<?php
					if ($lasso_url->display->show_disclosure) {
						echo "<span>" . $lasso_url->display->disclosure_text . "</span>";
					}
					?>
				</div>
			</div>
		<?php endif; ?>

		<div class="lasso-box-6">
			<div class="lasso-date">
				<?php
				if ($lasso_url->display->show_date && $lasso_url->display->show_price && '' !== $lasso_url->price) {
					echo $lasso_url->display->last_updated . ' <i class="lasso-amazon-info" data-tooltip="Price and availability are accurate as of the date and time indicated and are subject to change."></i>';
				}
				?>
			</div>
		</div>
		<?php if (Lasso_Helper::is_show_brag_icon()) { ?>
			<div class="lasso-single-brag">
				<?php echo Lasso_Html_Helper::get_brag_icon(); ?>
			</div>
		<?php } ?>
	</div>
</div>