<?php
/**
 * Conflict Resolver - Fix Conflicts with WP Rocket and Smush Pro
 * 
 * Resolve specific conflicts causing infinite loading
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check for WP Rocket conflicts
 */
function mts_check_wp_rocket_conflicts() {
    if (!function_exists('rocket_init') && !defined('WP_ROCKET_VERSION')) {
        return false;
    }
    
    // Check for specific WP Rocket options that might cause conflicts
    $rocket_options = array(
        'rocket_minify_css',
        'rocket_minify_js',
        'rocket_delay_js',
        'rocket_preload_fonts',
        'rocket_preload_links',
        'rocket_critical_css'
    );
    
    foreach ($rocket_options as $option) {
        if (get_option($option)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Check for Smush Pro conflicts
 */
function mts_check_smush_pro_conflicts() {
    if (!function_exists('wp_smushit') && !class_exists('Smush\\Core\\Core')) {
        return false;
    }
    
    // Check for specific Smush Pro options that might cause conflicts
    $smush_options = array(
        'wp-smush-webp',
        'wp-smush-lazy_load',
        'wp-smush-resize',
        'wp-smush-lossy'
    );
    
    foreach ($smush_options as $option) {
        if (get_option($option)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Resolve WP Rocket conflicts
 */
function mts_resolve_wp_rocket_conflicts() {
    if (!mts_check_wp_rocket_conflicts()) {
        return;
    }
    
    // Remove theme optimizations that conflict with WP Rocket
    remove_action('wp_head', 'mts_add_preload_resources', 1);
    remove_action('wp_head', 'mts_optimize_font_loading', 2);
    remove_action('wp_head', 'mts_add_proper_viewport_meta', 1);
    remove_action('wp_head', 'mts_add_contrast_fixes', 10);
    remove_action('wp_head', 'mts_add_link_accessibility_fixes', 10);
    remove_action('wp_head', 'mts_add_heading_order_fixes', 10);
    remove_action('wp_head', 'mts_add_form_accessibility_fixes', 10);
    remove_action('wp_head', 'mts_add_table_accessibility_fixes', 10);
    remove_action('wp_head', 'mts_enhanced_meta_tags', 1);
    remove_action('wp_head', 'mts_wp_rocket_optimizations', 1);
    remove_action('wp_head', 'mts_smush_pro_optimizations', 1);
    remove_action('wp_head', 'mts_add_plugin_compatibility_css', 1);
    
    remove_action('wp_footer', 'mts_add_aria_labels_script');
    remove_action('wp_footer', 'mts_add_plugin_compatibility_js', 1);
    remove_action('wp_footer', 'mts_debug_plugin_compatibility');
    
    remove_filter('script_loader_tag', 'mts_add_defer_attribute', 10);
    remove_filter('wp_get_attachment_image_attributes', 'mts_add_lazy_loading_attributes', 10);
    remove_filter('style_loader_tag', 'mts_minify_inline_css');
    
    remove_action('send_headers', 'mts_add_cache_headers');
    remove_action('pre_get_posts', 'mts_optimize_post_queries');
    remove_action('wp_generate_attachment_metadata', 'mts_generate_webp_images');
    remove_action('wp_enqueue_scripts', 'mts_enqueue_accessibility_assets');
    
    // Add WP Rocket safe optimizations
    add_action('wp_head', 'mts_wp_rocket_safe_optimizations', 1);
}

/**
 * Resolve Smush Pro conflicts
 */
function mts_resolve_smush_pro_conflicts() {
    if (!mts_check_smush_pro_conflicts()) {
        return;
    }
    
    // Remove theme optimizations that conflict with Smush Pro
    remove_action('wp_generate_attachment_metadata', 'mts_add_webp_metadata');
    remove_action('wp_generate_attachment_metadata', 'mts_generate_webp_images');
    remove_filter('wp_get_attachment_image_attributes', 'mts_add_lazy_loading_attributes', 10);
    remove_action('wp_footer', 'mts_add_lazy_loading_script');
    
    // Add Smush Pro safe optimizations
    add_action('wp_head', 'mts_smush_pro_safe_optimizations', 1);
}

/**
 * WP Rocket safe optimizations
 */
function mts_wp_rocket_safe_optimizations() {
    // Only add essential meta tags
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    
    // Add basic critical CSS inline
    echo '<style id="mts-wp-rocket-critical">';
    echo 'body{font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;line-height:1.6;color:#333;margin:0;padding:0}';
    echo '.container{max-width:1200px;margin:0 auto;padding:0 20px}';
    echo '.header{background:#fff;box-shadow:0 2px 4px rgba(0,0,0,0.1);padding:20px 0}';
    echo '.main-content{min-height:400px;padding:20px 0}';
    echo '.footer{background:#f8f9fa;padding:20px 0;margin-top:40px}';
    echo 'img{max-width:100%;height:auto}';
    echo 'a{color:#0073aa;text-decoration:none}';
    echo 'a:hover{color:#005177;text-decoration:underline}';
    echo '.button,button,input[type="submit"]{background:#0073aa;color:#fff;border:none;padding:10px 20px;border-radius:3px;cursor:pointer}';
    echo '.button:hover,button:hover,input[type="submit"]:hover{background:#005177}';
    echo 'input,textarea,select{border:1px solid #ddd;padding:8px 12px;border-radius:3px;width:100%;max-width:300px}';
    echo 'input:focus,textarea:focus,select:focus{border-color:#0073aa;outline:2px solid #0073aa;outline-offset:2px}';
    echo '@media (max-width:768px){.container{padding:0 15px}.header,.main-content,.footer{padding:15px 0}}';
    echo '</style>';
    
    // Add basic resource hints
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">' . "\n";
}

/**
 * Smush Pro safe optimizations
 */
function mts_smush_pro_safe_optimizations() {
    // Add WebP support detection
    echo '<script>';
    echo 'function supportsWebP(){var elem=document.createElement("canvas");return !!(elem.getContext&&elem.getContext("2d"))}';
    echo 'if(supportsWebP()){document.documentElement.classList.add("webp")}else{document.documentElement.classList.add("no-webp")}';
    echo '</script>';
    
    // Add lazy loading fallback
    echo '<script>';
    echo 'if("loading"in HTMLImageElement.prototype){var images=document.querySelectorAll("img[data-src]");images.forEach(function(img){img.src=img.dataset.src;img.removeAttribute("data-src")})}else{var script=document.createElement("script");script.src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js";document.head.appendChild(script)}';
    echo '</script>';
}

/**
 * Add conflict resolution notice
 */
function mts_conflict_resolution_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $notices = array();
    
    if (mts_check_wp_rocket_conflicts()) {
        $notices[] = array(
            'type' => 'info',
            'message' => __('WP Rocket detected! Theme optimizations have been adjusted to prevent conflicts.', 'mythemeshop')
        );
    }
    
    if (mts_check_smush_pro_conflicts()) {
        $notices[] = array(
            'type' => 'info',
            'message' => __('Smush Pro detected! Image optimizations have been adjusted to prevent conflicts.', 'mythemeshop')
        );
    }
    
    foreach ($notices as $notice) {
        echo '<div class="notice notice-' . esc_attr($notice['type']) . ' is-dismissible">';
        echo '<p>' . esc_html($notice['message']) . '</p>';
        echo '</div>';
    }
}

/**
 * Initialize conflict resolution
 */
function mts_init_conflict_resolution() {
    // Only run on frontend
    if (is_admin()) {
        return;
    }
    
    // Resolve conflicts
    mts_resolve_wp_rocket_conflicts();
    mts_resolve_smush_pro_conflicts();
    
    // Add notice
    add_action('admin_notices', 'mts_conflict_resolution_notice');
}

// Initialize conflict resolution
add_action('init', 'mts_init_conflict_resolution', 1);
