<?php
global $content_width;
if ( 667 == $content_width ) {
	$w = 667;
	$h = 333;
} else {
	$w = 1000;
	$h = 500;
}
$gallery_type = '';//cfpf_post_gallery_type();
$gallery_shortcode = get_post_meta( get_the_ID(), '_format_gallery_shortcode', true );

if ( 'shortcode' == $gallery_type ) {

	$content_width = $content_width+80; // add left/right paddings to content width gallery shortcode

	echo do_shortcode( $gallery_shortcode );

	$content_width = $content_width-80; // remove paddings from content width

} else {

	if ( $images = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image' ) ) ) { ?>
		<div class="slider-container loading">
			<div id="slider" class="slides gallery-slider">
				<?php
				foreach( $images as $image ) {
					$attachment_img = wp_get_attachment_image_src( $image->ID, 'full' );
					$attachment_url = $attachment_img[0];
					$image_src      = bfi_thumb( $attachment_url, array( 'width' => $w, 'height' => $h, 'crop' => true ) );

					echo '<div><img src="'.$image_src.'" class="wp-post-image"></div>';
				}
				?>
			</div>
		</div>
<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
<?php	}
}
?>