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
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wptp-tabs {
	float: left;
	width: 100%;
	box-sizing: border-box;
	background: #<?php echo wptp_darken_color( $tab_bg, 12 ); ?>;
	padding: 8px 5px 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .tab_title {
	padding: 0 3px 0!important;
	box-sizing: border-box;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wpt_acc_title { padding: 0px!important; }
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .tab_title a,
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wpt_acc_title a {
	color: <?php echo $tab_color; ?>;
	background: <?php echo $tab_bg; ?>;
    font-weight: 600;
    padding: 8px 0;
    height: 38px;
    line-height: 1.5;
    /*text-shadow: 0 1px rgba(255,255,255,0.95);*/
    box-sizing: border-box;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px; 
    border: 1px solid #<?php echo wptp_darken_color( $tab_bg, 22); ?>;
    border-bottom: 0;
    box-shadow: inset 0 1px 0 0 rgba(255,255,255,0.16), 0 1px 0 0 rgba(255,255,255,0.1);
    background: <?php echo $tab_bg; ?>;
    background: -webkit-linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>);
    background: -o-linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>);
    background: -moz-linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>);
    background: linear-gradient(<?php echo $tab_bg; ?>, #<?php echo wptp_darken_color( $tab_bg, 5 ); ?>);
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wpt_acc_title a {
    border-radius: 0!important;
    border:0;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .tab_title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wpt_acc_title a:hover {
	color: <?php echo $tab_hover_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .tab_title.selected a,
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wpt_acc_title.selected a {
	color: <?php echo $tab_active_color; ?>;
    background: <?php echo $tab_active_bg; ?>;
    box-shadow: inset 0 1px 0 0 rgba(255,255,255,0.4), 0 1px 0 0 rgba(255,255,255,0.1);
    background: -webkit-linear-gradient(<?php echo $tab_active_bg; ?>, #<?php echo wptp_darken_color( $tab_active_bg, 5 ); ?>);
    background: -o-linear-gradient(<?php echo $tab_active_bg; ?>, #<?php echo wptp_darken_color( $tab_active_bg, 5 ); ?>);
    background: -moz-linear-gradient(<?php echo $tab_active_bg; ?>, #<?php echo wptp_darken_color( $tab_active_bg, 5 ); ?>);
    background: linear-gradient(<?php echo $tab_active_bg; ?>, #<?php echo wptp_darken_color( $tab_active_bg, 5 ); ?>);
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .inside {
    background: <?php echo $bg; ?>;
    border: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .tab-content li.wptp-list-item { border-color: #1f2632; box-shadow: inset 0 1px 0 0 rgba(0,0,0,0.2), 0 1px 0 0 rgba(255,255,255,0.1); }
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .tab-content li.wptp-list-item:hover { background: <?php echo $list_hover_bg;?>; }
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .entry-title,
#<?php echo $widget_id; ?>_content.wptp-style-19 .wptp_comment_meta {
    font-size: 15px;
    line-height: 24px;
    margin-bottom: 5px;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .entry-title a,
#<?php echo $widget_id; ?>_content.wptp-style-19 .wptp_comment_meta a,
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content a {
	color: <?php echo $link_color; ?>;
	font-weight: normal;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .entry-title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-19 .wptp_comment_meta a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content a:hover {
	color: <?php echo $link_hover_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-19 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-19 .wptp_excerpt {
    color: <?php echo $color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-19.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-19 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-19 .wptp_excerpt {
    font-size: 11px;
}
</style>