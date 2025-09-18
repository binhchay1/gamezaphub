<?php
/*
 *
 * Available variables:
 *  	$custom_content
 *
 *  	$widget_number
 *  	$tab_key
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="wptp-custom-content">
	<?php
	$custom_content = apply_filters( 'wpt_custom_tab_content', $custom_content, $widget_number, $tab_key );
	echo do_shortcode( html_entity_decode( $custom_content ) );
	?>
</div>