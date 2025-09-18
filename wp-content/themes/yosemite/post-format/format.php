<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php
global $content_width;
if ( 667 == $content_width ) {
	$w = 667;
	$h = 333;
} else {
	$w = 1000;
	$h = 500;
}
if(!is_singular()) { ?>
<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" id="featured-thumbnail">
<?php } ?>
	<div class="featured-thumbnail">
		<?php if ( has_post_thumbnail() ) {
			$id        = get_post_thumbnail_id();
			$image     = wp_get_attachment_image_src( $id, 'full' );
			$image_url = $image[0];
			$thumbnail = bfi_thumb( $image_url, array( 'width' => $w, 'height' => $h, 'crop' => true ) );
		echo '<img src="'.$thumbnail.'" class="wp-post-image">';
		if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper');
		} ?>
	</div>
<?php if(!is_singular()) { ?>
</a>
<?php } ?>