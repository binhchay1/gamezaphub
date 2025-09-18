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
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li .page-numbers {
    padding: 9px;
    min-width: 36px;
    min-height: 36px;
	color: <?php echo $nav_color; ?>;
	border: 1px solid #<?php echo wptp_lighten_color( $nav_color, 60 ); ?>;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li .page-numbers.prev,
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li .page-numbers.next {
	color: <?php echo $nav_button_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li a.page-numbers:hover {
	background: <?php echo $nav_hover_bg; ?>;
	color: <?php echo $nav_hover_color; ?>;
	border: 1px solid #<?php echo wptp_darken_color( $nav_hover_bg, 13 ); ?>;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li .page-numbers.current {
	background: <?php echo $nav_active_bg; ?>;
	color: <?php echo $nav_active_color; ?>;
	border: 1px solid #<?php echo wptp_darken_color( $nav_active_bg, 13 ); ?>;
}
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li { margin-right: 3px }
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li .fa { margin: 0; }
#<?php echo $widget_id; ?>_content.wptp-pagination-style-25.wptp_widget_content .wptp-pagination li .wptp-button-text { display: none; }
</style>