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
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab_title a .fa { display: block; }
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab_title a,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wpt_acc_title a {
    color: <?php echo $tab_color; ?>;
    background: transparent;
    font-weight: 600;
    padding: 18px 0;
    line-height: 1.5;
    height: 68px;
    box-sizing: border-box;
    border: 0;
    border-bottom: 1px solid rgba(0,0,0,.08);
    font-size: 14px;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab_title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wpt_acc_title a:hover {
    color: <?php echo $tab_hover_color; ?>;
    background: transparent;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab_title.selected a,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wpt_acc_title.selected a {
    color: <?php echo $tab_active_color; ?>;
    background: transparent;
    position: relative;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab_title.selected a:before,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wpt_acc_title.selected a:before {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 50%;
    margin-left: -10px;
    border-width: 8px 10px 0;
    border-style: solid;
    border-color: rgba(0,0,0,.08) transparent;
    display: block;
    width: 0;
    z-index: 1;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab_title.selected a:after,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wpt_acc_title.selected a:after {
    content: "";
    position: absolute;
    bottom: -7px;
    left: 50%;
    margin-left: -9px;
    border-width: 7px 9px 0;
    border-style: solid;
    border-color: #ffffff transparent;/* #ffffff should be adjusted */
    display: block;
    width: 0;
    z-index: 1;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .inside {
    background: transparent;
    border: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wptp-list { padding-top: 20px }
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab-content li.wptp-list-item {
    border: 0;
    background: transparent;
    padding-left: 0;
    padding-right: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .entry-title,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp_comment_meta {
    font-size: 15px;
    line-height: 24px;
    margin-bottom: 5px;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .entry-title a,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp_comment_meta a,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content a {
    color: <?php echo $link_color; ?>;
    font-weight: normal;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .entry-title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp_comment_meta a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content a:hover {
    color: <?php echo $link_hover_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp_excerpt {
    color: <?php echo $color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp_excerpt {
    font-size: 11px;
}
#<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .wptp-pagination,
#<?php echo $widget_id; ?>_content.wptp-style-13 .wptp-custom-content, #<?php echo $widget_id; ?>_content.wptp-style-13.wptp_widget_content .tab-content li {
    padding-left: 0;
    padding-right: 0;
}
</style>