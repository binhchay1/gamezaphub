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
function bloggers_custom_post_navigation($template, $class) {
    // Only override for post navigation (not comment navigation)
    if ($class !== 'post-navigation') {
        return $template;
    }
    
    // Custom template without FA icons
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
function bloggers_custom_post_navigation_content($navigation) {
    // Replace FontAwesome icons with SVG
    // Left arrow SVG
    $svg_left = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px; vertical-align: middle;">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        <path fill-rule="evenodd" d="M7.354 1.646a.5.5 0 0 1 0 .708L1.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>';
    
    // Right arrow SVG
    $svg_right = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px; vertical-align: middle;">
        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
        <path fill-rule="evenodd" d="M8.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L14.293 8 8.646 2.354a.5.5 0 0 1 0-.708z"/>
    </svg>';
    
    // Remove the old FA icons and empty spans
    $navigation = preg_replace('/<div class="fa\s+fa-angle-double-left"><\/div><span><\/span>\s*/i', $svg_left, $navigation);
    $navigation = preg_replace('/\s*<div class="fa\s+fa-angle-double-right"><\/div><span><\/span>/i', $svg_right, $navigation);
    
    return $navigation;
}

/**
 * Remove the action that creates the old navigation
 * and add our custom one
 */
function bloggers_override_single_content_navigation() {
    // Remove parent theme navigation
    remove_action('blogarise_action_main_single_content', 'blogarise_single_content', 40);
    
    // Add our custom navigation
    add_action('blogarise_action_main_single_content', 'bloggers_single_content_with_svg_nav', 40);
}
add_action('after_setup_theme', 'bloggers_override_single_content_navigation', 20);

/**
 * Custom single content function with SVG navigation
 * This is a copy of the parent theme function but with SVG icons
 */
function bloggers_single_content_with_svg_nav() {
    if (is_single()) {
        while (have_posts()) : the_post();
            
            // Get featured image
            global $post;
            $url = blogarise_get_freatured_image_url($post->ID, 'blogarise-slider-full');
            
            if ($url) {
                $caption = get_the_post_thumbnail_caption();
                echo '<div class="bs-blog-thumb lg back-img" style="background-image: url(\'' . esc_url($url) . '\');">';
                echo '<a href="' . esc_url(get_the_permalink()) . '" class="link-div"></a>';
                if (!empty($caption)) {
                    echo '<span class="featured-image-caption">' . esc_html($caption) . '</span>';
                }
                echo '</div>';
            }
            ?>
            
            <article class="small single">
                <?php 
                the_content(); 
                blogarise_edit_link(); 
                blogarise_social_share_post(get_post());
                ?>
                <div class="clearfix mb-3"></div>
                <?php
                // Custom navigation with SVG icons
                $svg_left = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px; vertical-align: middle;">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    <path fill-rule="evenodd" d="M7.354 1.646a.5.5 0 0 1 0 .708L1.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>';
                
                $svg_right = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px; vertical-align: middle;">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    <path fill-rule="evenodd" d="M8.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L14.293 8 8.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>';
                
                $is_rtl = is_rtl();
                $prev_text = $is_rtl ? $svg_right : $svg_left;
                $next_text = $is_rtl ? $svg_left : $svg_right;
                
                the_post_navigation(array(
                    'prev_text' => $prev_text . ' %title',
                    'next_text' => '%title ' . $next_text,
                    'in_same_term' => true,
                ));
                
                wp_link_pages(array(
                    'before' => '<div class="single-nav-links">',
                    'after' => '</div>',
                ));
                ?>
            </article>
            
        <?php 
        endwhile;
        
        do_action('blogarise_action_single_author_box');
        do_action('blogarise_action_single_related_box');
    }
    
    do_action('blogarise_action_single_comments_box');
}
