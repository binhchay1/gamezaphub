<?php

/**
 * Speed Optimizations - Focus on Speed Index
 * Aggressive optimizations for fastest possible page load
 * 
 * @package Bloggers
 */

// ============================================================================
// 1. AGGRESSIVE IMAGE PRELOADING FOR LCP
// ============================================================================

add_action('wp_head', 'bloggers_aggressive_lcp_preload', 1);
function bloggers_aggressive_lcp_preload()
{
    if (is_admin()) {
        return;
    }

    if (!is_singular() && !is_front_page()) {
        return;
    }

    global $post;

    $image_id = get_post_thumbnail_id($post);
    if (!$image_id) {
        return;
    }

    $image_url = wp_get_attachment_image_url($image_id, 'large');
    if ($image_url) {
        echo '<link rel="preload" as="image" href="' . esc_url($image_url) . '" fetchpriority="high">' . "\n";

        $image_srcset = wp_get_attachment_image_srcset($image_id, 'large');
        if ($image_srcset) {
            echo '<link rel="preload" as="image" imagesrcset="' . esc_attr($image_srcset) . '" fetchpriority="high">' . "\n";
        }
    }
}

// ============================================================================
// 2. REMOVE RENDER-BLOCKING RESOURCES AGGRESSIVELY
// ============================================================================

/**
 * Remove ALL non-critical CSS and load async
 */
add_action('wp_enqueue_scripts', 'bloggers_remove_blocking_css', 9999);
function bloggers_remove_blocking_css()
{
    $non_critical = array(
        'animate',
        'smartmenus',
        'all-css',
        'dark'
    );

    foreach ($non_critical as $handle) {
        wp_dequeue_style($handle);
    }
}

/**
 * Async load non-critical JavaScript
 */
add_filter('script_loader_tag', 'bloggers_async_scripts', 10, 3);
function bloggers_async_scripts($tag, $handle, $src)
{
    if (is_admin()) {
        return $tag;
    }

    $defer_scripts = array(
        'jquery',
        'bloggers-performance-optimizer',
        'bloggers-owl-js'
    );

    $async_scripts = array(
        'blogarise_main-js',
        'smartmenus-js',
        'bootstrap-smartmenus-js',
        'swiper-bundle',
        'sticksy-js'
    );

    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src=', ' defer src=', $tag);
    }

    if (in_array($handle, $async_scripts)) {
        return str_replace(' src=', ' async src=', $tag);
    }

    return $tag;
}

// ============================================================================
// 3. INLINE CRITICAL RESOURCES (Smaller, faster)
// ============================================================================

add_action('wp_head', 'bloggers_ultra_critical_css', 1);
function bloggers_ultra_critical_css()
{
?>
    <style id="ultra-critical-css">
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333
        }

        img {
            max-width: 100%;
            height: auto;
            display: block
        }

        a {
            color: #0073aa;
            text-decoration: none
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px
        }

        header {
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 999
        }

        .bs-blog-post {
            margin-bottom: 2rem
        }

        .bs-blog-thumb {
            position: relative;
            overflow: hidden;
            aspect-ratio: 16/9
        }

        .title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0.5rem 0;
            line-height: 1.3
        }

        h1 {
            font-size: 2rem;
            font-weight: 600
        }
    </style>
<?php
}

// ============================================================================
// 4. REDUCE DOM SIZE - Remove unnecessary elements
// ============================================================================

/**
 * Remove WordPress default assets that slow down
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// ============================================================================
// 6. DISABLE HEARTBEAT API (Reduces background requests)
// ============================================================================

add_action('init', 'bloggers_disable_heartbeat', 1);
function bloggers_disable_heartbeat()
{
    wp_deregister_script('heartbeat');
}

// ============================================================================
// 7. LIMIT POST REVISIONS (Cleaner database = faster queries)
// ============================================================================

if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

// ============================================================================
// 8. OPTIMIZE QUERIES - Disable unnecessary queries
// ============================================================================

/**
 * Remove unnecessary queries
 */
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10);
remove_action('wp_head', 'start_post_rel_link', 10);

// ============================================================================
// 9. AGGRESSIVE BROWSER CACHE HINTS
// ============================================================================

add_action('send_headers', 'bloggers_aggressive_cache_headers');
function bloggers_aggressive_cache_headers()
{
    if (!is_admin() && !is_user_logged_in()) {
        header('Cache-Control: public, max-age=3600, stale-while-revalidate=86400');
    }
}

// ============================================================================
// 10. EARLY HINTS (HTTP 103) FOR CRITICAL RESOURCES
// ============================================================================

add_action('template_redirect', 'bloggers_send_early_hints', 1);
function bloggers_send_early_hints()
{
    if (function_exists('wp_cache_get') && !headers_sent()) {
        // Send early hints for fonts
        header('Link: <https://fonts.googleapis.com>; rel=preconnect; crossorigin', false);
        header('Link: <https://fonts.gstatic.com>; rel=preconnect; crossorigin', false);
    }
}
