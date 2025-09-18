<?php

/**
 * Performance Optimization Functions for Yosemite Theme
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Optimize CSS and JS loading
 */
function mts_optimize_assets_loading()
{
    add_filter('script_loader_tag', 'mts_add_defer_attribute', 10, 2);

    add_action('wp_head', 'mts_add_preload_resources', 1);

    add_action('wp_head', 'mts_optimize_font_loading', 2);
}

/**
 * Add defer attribute to non-critical scripts
 */
function mts_add_defer_attribute($tag, $handle)
{
    $defer_scripts = array(
        'owl-carousel',
        'jquery-parallax',
        'prettyPhoto',
        'thumbsAnim',
        'imagesLoaded',
        'mts_ajax',
        'historyjs'
    );

    if (in_array($handle, $defer_scripts)) {
        return str_replace('<script ', '<script defer ', $tag);
    }

    return $tag;
}

/**
 * Add preload for critical resources
 */
function mts_add_preload_resources()
{
    $mts_options = get_option(MTS_THEME_NAME);

    echo '<link rel="preload" href="' . get_template_directory_uri() . '/style.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';

    if (!empty($mts_options['mts_google_fonts'])) {
        echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=' . urlencode($mts_options['mts_google_fonts']) . ':wght@400;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    }

    if (is_singular() && has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
        if ($image) {
            echo '<link rel="preload" href="' . esc_url($image[0]) . '" as="image">';
        }
    }

    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">';
    echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
}

/**
 * Optimize database queries
 */
function mts_optimize_database_queries()
{
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');

    add_action('pre_get_posts', 'mts_optimize_post_queries');
}

/**
 * Optimize post queries
 */
function mts_optimize_post_queries($query)
{
    if (!is_admin() && $query->is_main_query()) {
        $query->set('no_found_rows', true);
        $query->set('update_post_meta_cache', false);
        $query->set('update_post_term_cache', false);
    }
}

/**
 * Optimize WordPress admin
 */
function mts_optimize_admin()
{
    if (!current_user_can('manage_options')) {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
    }
}

/**
 * Add WebP support
 */
function mts_add_webp_support()
{
    add_image_size('webp-thumbnail', 265, 174, true);
    add_image_size('webp-medium', 300, 200, true);
    add_image_size('webp-large', 600, 400, true);
}

/**
 * Generate WebP images
 */
function mts_generate_webp_images($attachment_id)
{
    $upload_dir = wp_upload_dir();
    $file_path = get_attached_file($attachment_id);

    if (!$file_path || !file_exists($file_path)) {
        return false;
    }

    $file_info = pathinfo($file_path);
    $webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

    if (function_exists('imagewebp')) {
        $image = null;

        switch ($file_info['extension']) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file_path);
                break;
            case 'png':
                $image = imagecreatefrompng($file_path);
                break;
        }

        if ($image && imagewebp($image, $webp_path, 80)) {
            imagedestroy($image);
            return $webp_path;
        }
    }

    return false;
}

/**
 * Initialize performance optimizations
 */
function mts_init_performance_optimizations()
{
    mts_optimize_assets_loading();
    mts_optimize_database_queries();
    mts_add_webp_support();

    add_action('send_headers', 'mts_add_cache_headers');
    add_action('init', 'mts_optimize_admin');
    add_action('wp_generate_attachment_metadata', 'mts_generate_webp_images');
}

add_action('init', 'mts_init_performance_optimizations');
