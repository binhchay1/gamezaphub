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
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content {
    border: 0;
    background: transparent;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content ul.wptp-tabs {
    float: left;
    width: 100%;
    display: inline-block;
    overflow: hidden;
    background: <?php echo $tab_bg; ?>; /* Old browsers */
    background: -moz-linear-gradient(top, transparent 0px, transparent 15px, <?php echo $tab_bg; ?> 15px, <?php echo $tab_bg; ?> 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(top, transparent 0px, transparent 15px, <?php echo $tab_bg; ?> 15px, <?php echo $tab_bg; ?> 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(to bottom, transparent 0px, transparent 15px, <?php echo $tab_bg; ?> 15px, <?php echo $tab_bg; ?> 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content ul.wptp-tabs li { position: relative; }
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .tab_title a,
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .wpt_acc_title a {
    color: <?php echo $tab_color; ?>;
    background: <?php echo $tab_bg; ?>;
    font-weight: 600;
    padding: 13px 0;
    line-height: 1.5;
    height: 46px;
    box-sizing: border-box;
    border-bottom: 0;
    border-color: rgba(255,255,255,.06);
    font-size: 14px;
    -webkit-border-top-left-radius: 15px;
    -webkit-border-top-right-radius: 15px;
    -moz-border-radius-topleft: 15px;
    -moz-border-radius-topright: 15px;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px; 
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .wpt_acc_title a {
    border-radius: 0!important
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .tab_title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .wpt_acc_title a:hover {
    color: <?php echo $tab_hover_color; ?>;
    background: <?php echo $tab_bg; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .tab_title.selected a,
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .wpt_acc_title.selected a {
    color: <?php echo $tab_active_color; ?>;
    background: <?php echo $tab_active_bg; ?>;
    border-bottom: 0;
    z-index: 3;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li:before, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li:after, 
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li a:before, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li a:after {
    position: absolute;
    bottom: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected:after, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected:before, 
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected a:after, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected a:before {
    content: "";
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected:before, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected:after {
    background: <?php echo $tab_active_bg; ?>;
    z-index: 1;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li:before, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li:after {
    background: <?php echo $tab_bg; ?>;
    width: 10px;
    height: 10px;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li:before {
    left: -10px;      
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li:after { 
    right: -10px;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li a:after, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li a:before {
    width: 20px; 
    height: 20px;
    -webkit-border-radius: 10px;
    -moz-border-radius:    10px;
    border-radius:         10px;
    background: <?php echo $tab_active_bg; ?>;
    z-index: 2;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected a:after, #<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs .selected a:before {
    background: <?php echo $tab_bg; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li a:before {
    left: -21px;
}
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp-tabs li a:after {
    right: -20px;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .inside {
    background: <?php echo $bg; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .tab-content li.wptp-list-item {
    border: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .entry-title,
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp_comment_meta {
    font-size: 15px;
    line-height: 24px;
    margin-bottom: 5px;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .entry-title a,
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp_comment_meta a,
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content a {
    color: <?php echo $link_color; ?>;
    font-weight: normal;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .entry-title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp_comment_meta a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content a:hover {
    color: <?php echo $link_hover_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp_excerpt {
    color: <?php echo $color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-9.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-9 .wptp_excerpt {
    font-size: 11px;
}
</style>