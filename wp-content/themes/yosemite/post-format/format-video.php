<?php
global $content_width;
$content_width = $content_width+80; // add left/right paddings to content width for oembeds
$video_meta = get_post_meta( get_the_ID(), '_format_video_embed', true );
if ( $video_meta == '' ) { ?>
	<div class="message_box warning">
		<p><?php _e( 'Please add valid embed Code.', 'mythemeshop' ); ?></p>
	</div>
<?php
} elseif ( strpos( $video_meta, 'iframe' ) !== false || strpos( $video_meta, 'embed' ) !== false ) {
	
	echo '<div class="flex-video">'. $video_meta . '</div>';
	
} else {

	$embed_code = wp_oembed_get( $video_meta );
	echo '<div class="flex-video">'. $embed_code . '</div>';
}
$content_width = $content_width-80;// remove paddings from content width
?>