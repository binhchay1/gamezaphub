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
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content ul {
    display: inline-block;
    float: left;
    width: 100%;
    box-sizing: border-box;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content ul.wptp-tabs {
    padding: 2px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content ul.wptp-tabs li {
    padding: 2px !important;
    box-sizing: border-box;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .tab_title a,
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .wpt_acc_title a {
    color: <?php echo $tab_color; ?>;
    background: <?php echo $tab_bg; ?>;
    font-weight: 600;
    padding: 9px 0;
    line-height: 1.5;
    height: 38px;
    box-sizing: border-box;
    border: 1px solid rgba(0,0,0,0.05)!important;
    text-transform: uppercase;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .tab_title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .wpt_acc_title a:hover {
    color: <?php echo $tab_hover_color; ?>;
    background: <?php echo $tab_hover_bg; ?>;
    position: relative;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .tab_title.selected a,
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .wpt_acc_title.selected a {
    color: <?php echo $tab_active_color; ?>;
    background: <?php echo $tab_active_bg; ?>;
    position: relative;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .inside {
    background: <?php echo $bg; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .tab-content li.wptp-list-item { border-color: rgba(0,0,0,0.05); background: #ffffff; }
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .tab-content li.wptp-list-item:hover { background: <?php echo $list_hover_bg;?>; }
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .entry-title,
#<?php echo $widget_id; ?>_content.wptp-style-3 .wptp_comment_meta {
    font-size: 15px;
    line-height: 24px;
    margin-bottom: 5px;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .entry-title a,
#<?php echo $widget_id; ?>_content.wptp-style-3 .wptp_comment_meta a,
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content a {
    color: <?php echo $link_color; ?>;
    font-weight: normal;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-3 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-3 .wptp_excerpt {
    color: <?php echo $color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-3.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-3 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-3 .wptp_excerpt {
    font-size: 11px;
}
</style>