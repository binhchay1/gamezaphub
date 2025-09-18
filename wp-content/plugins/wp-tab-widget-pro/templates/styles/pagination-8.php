<?php
/*
 *
 * Available variables:
 *      $style            - main template
 *      $pagination_style - pagination template
 *      $bg               - content
 *      $color            - content
 *      $link_color       - content
 *      $link_hover_color - content
 *      $list_hover_bg    - content
 *      $tab_bg           - tabs nav
 *      $tab_color        - tabs nav
 *      $tab_hover_bg     - tabs nav
 *      $tab_hover_color  - tabs nav
 *      $tab_active_bg    - tabs nav
 *      $tab_active_color - tabs nav
 *      $nav_bg           - pagination
 *      $nav_color        - pagination
 *		$nav_button_bg    - pagination
 *		$nav_button_color - pagination
 *      $nav_hover_bg     - pagination
 *      $nav_hover_color  - pagination
 *      $nav_active_bg    - pagination
 *      $nav_active_color - pagination
 *
 *      $widget_id
 *
 * Note: non used variables are empty ( see wptp_widget::get_presets() )
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<style type="text/css">
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers {
	background: <?php echo $nav_bg; ?>;
	color: <?php echo $nav_color; ?>;
	z-index: 0;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers.prev,
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers.next {
	background: <?php echo $nav_button_bg; ?>;
	color: <?php echo $nav_button_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li a.page-numbers:hover,
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers.current {
	color: <?php echo $nav_active_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li a.page-numbers:before,
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers:before {
	content: "";
	position: absolute;
	background: <?php echo $nav_bg; ?>;
	width: 22px;
	height: 22px;
	border-radius: 50%;
	top: 50%;
	left: 50%;
	margin-left: -11px;
	margin-top: -11px;
	display: inline-block;
	z-index: -1;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li a.page-numbers:hover:before,
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers.current:before {
	background: <?php echo $nav_active_bg; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .fa { margin: 0; }
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .wptp-button-text { display: none; }
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers.next { border-left: 1px solid #e3e3e4; }
#<?php echo $widget_id; ?>_content.wptp-pagination-style-8.wptp_widget_content .wptp-pagination li .page-numbers.prev { border-right: 1px solid #e3e3e4; }
</style>