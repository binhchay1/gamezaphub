<?php
/*
 *
 * Available variables:
 *		$style            - main template
 *		$pagination_style - pagination template
 *		$bg               - content
 *  	$color            - content
 *  	$link_color       - content
 *  	$link_hover_color - content
 *		$list_hover_bg    - content
 *  	$tab_bg           - tabs nav
 *		$tab_color        - tabs nav
 *  	$tab_hover_bg     - tabs nav
 *		$tab_hover_color  - tabs nav
 *		$tab_active_bg    - tabs nav
 *  	$tab_active_color - tabs nav
 *  	$nav_bg           - pagination
 *  	$nav_color        - pagination
 *  	$nav_hover_bg     - pagination
 *  	$nav_hover_color  - pagination
 *  	$nav_active_bg    - pagination
 *  	$nav_active_color - pagination
 *
 *		$widget_id
 *
 * Note: non used variables are empty ( see wpt_widget::get_presets() )
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style type="text/css">
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content {
    border: 0;
    background: transparent;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab_title {
	padding: 0 5px 5px 0!important;
	box-sizing: border-box;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab_title:nth-child(3n) {
    padding: 0 0 5px 0!important;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title {
    padding: 0!important;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab_title.selected,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title.selected {
    padding-bottom: 0!important;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab_title a,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title a {
	color: <?php echo $tab_color; ?>;
    font-weight: 600;
    padding: 8px 0;
    height: 38px;
    line-height: 1.5;
    border: 1px solid rgba(0,0,0,0.08);
    text-shadow: 0 1px rgba(255,255,255,0.95);
    box-shadow: inset 0 1px 0 0 rgba(255,255,255,0.8), 0 1px 0 0 rgba(255,255,255,0.24);
    box-sizing: border-box;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    background: <?php echo $tab_bg; ?>;
    background: -webkit-linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>); /* For Firefox 3.6 to 15 */
    background: linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>); /* Standard syntax (must be last) */
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title a {
    border-radius: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab_title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title a:hover {
	color: <?php echo $tab_hover_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab_title.selected a,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title.selected a {
	color: <?php echo $tab_active_color; ?>;
	background: <?php echo $tab_active_bg; ?>;
	box-shadow: none;
    -webkit-border-radius: 0;
    border-radius: 0;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    height: 42px;
    border-bottom: 0;
    position: relative;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title.selected a {
    border: 0;
    border-radius: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab_title.selected a:after,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wpt_acc_title.selected a:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: -1px;
    height: 1px;
    z-index: 1;
    background: <?php echo $tab_active_bg; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .inside {
	background: <?php echo $bg; ?>;
    border: 1px solid rgba(0,0,0,0.08);
    margin-top: -1px;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab-content li.wptp-list-item { border-color: #e3e3e4;box-shadow: inset 0 1px 0 0 rgba(255,255,255,0.25), 0 1px 0 0 rgba(255,255,255,0.8); }
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .tab-content li.wptp-list-item:hover { background: <?php echo $list_hover_bg;?>; }
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .entry-title,
#<?php echo $widget_id; ?>_content.wptp-style-20 .wptp_comment_meta {
    font-size: 15px;
    line-height: 24px;
    margin-bottom: 5px;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .entry-title a,
#<?php echo $widget_id; ?>_content.wptp-style-20 .wptp_comment_meta a,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content a {
	color: <?php echo $link_color; ?>;
	font-weight: normal;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .entry-title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-20 .wptp_comment_meta a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content a:hover {
	color: <?php echo $link_hover_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-20 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-20 .wptp_excerpt {
    color: <?php echo $color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-20.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-20 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-20 .wptp_excerpt {
    font-size: 11px;
}
</style>