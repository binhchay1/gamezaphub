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
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .tab_title a,
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .wpt_acc_title a {
    color: <?php echo $tab_color; ?>;
    background: transparent;
    font-weight: 600;
    padding: 13px 0;
    line-height: 1.5;
    height: 46px;
    box-sizing: border-box;
    border: 0;
    border-bottom: 2px solid rgba(0,0,0,.08);
    font-size: 14px;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .tab_title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .wpt_acc_title a:hover {
    color: <?php echo $tab_hover_color; ?>;
    background: transparent;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .tab_title.selected a,
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .wpt_acc_title.selected a {
    color: <?php echo $tab_active_color; ?>;
    background: transparent;
    border-bottom-color: <?php echo $tab_active_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .inside {
    background: transparent;
    border: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .tab-content li.wptp-list-item {
    border-color: rgba(0,0,0,0.06);
    background: transparent;
    padding-left: 0;
    padding-right: 0;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .entry-title,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp_comment_meta {
    font-size: 15px;
    line-height: 24px;
    margin-bottom: 5px;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .entry-title a,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp_comment_meta a,
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content a {
    color: <?php echo $link_color; ?>;
    font-weight: normal;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .entry-title a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp_comment_meta a:hover,
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content a:hover {
    color: <?php echo $link_hover_color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content,
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp_excerpt {
    color: <?php echo $color; ?>;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .wptp-postmeta,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp_comment_content,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp_excerpt {
    font-size: 11px;
}
#<?php echo $widget_id; ?>_content.wptp-style-12.wptp_widget_content .wptp-pagination,
#<?php echo $widget_id; ?>_content.wptp-style-12 .wptp-custom-content {
    padding-left: 0;
    padding-right: 0;
}
</style>