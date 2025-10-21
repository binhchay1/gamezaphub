<?php

/**
 * Post Navigation Fix
 * Override parent theme navigation to remove FontAwesome icons
 * Replace with SVG icons
 * 
 * @package Bloggers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Override post navigation to use SVG instead of FontAwesome
 */
add_filter('navigation_markup_template', 'bloggers_custom_post_navigation', 10, 2);
function bloggers_custom_post_navigation($template, $class)
{
    if ($class !== 'post-navigation') {
        return $template;
    }

    return '
    <nav class="navigation %1$s" aria-label="%4$s">
        <h2 class="screen-reader-text">%2$s</h2>
        <div class="nav-links">%3$s</div>
    </nav>';
}

/**
 * Override the prev/next text to use SVG instead of FontAwesome
 */
add_filter('the_post_navigation', 'bloggers_custom_post_navigation_content', 10, 1);
function bloggers_custom_post_navigation_content($navigation)
{
    $svg_left = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px; vertical-align: middle;">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        <path fill-rule="evenodd" d="M7.354 1.646a.5.5 0 0 1 0 .708L1.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>';

    $svg_right = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px; vertical-align: middle;">
        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
        <path fill-rule="evenodd" d="M8.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L14.293 8 8.646 2.354a.5.5 0 0 1 0-.708z"/>
    </svg>';

    $navigation = preg_replace('/<div class="fa\s+fa-angle-double-left"><\/div><span><\/span>\s*/i', $svg_left, $navigation);
    $navigation = preg_replace('/\s*<div class="fa\s+fa-angle-double-right"><\/div><span><\/span>/i', $svg_right, $navigation);

    return $navigation;
}


/**
 * Custom single content function with SVG navigation
 * This is a copy of the parent theme function but with SVG icons
 */
function bloggers_single_content_with_svg_nav()
{
    if (is_single()) {
        do_action('blogarise_action_single_author_box');
        do_action('blogarise_action_single_related_box');
    }

    do_action('blogarise_action_single_comments_box');
}
