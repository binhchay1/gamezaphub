<?php
/**
 * Loading Fix - Resolve Infinite Loading Issues
 * 
 * Fix specific issues causing infinite loading loops
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fix infinite loading issues
 */
function mts_fix_infinite_loading() {
    // Remove problematic hooks that might cause loops
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
    
    // Remove problematic footer hooks
    remove_action('wp_footer', 'mts_add_aria_labels_script');
    remove_action('wp_footer', 'mts_add_plugin_compatibility_js', 1);
    remove_action('wp_footer', 'mts_debug_plugin_compatibility');
    
    // Remove problematic filters
    remove_filter('script_loader_tag', 'mts_add_defer_attribute', 10);
    remove_filter('wp_get_attachment_image_attributes', 'mts_add_lazy_loading_attributes', 10);
    remove_filter('style_loader_tag', 'mts_minify_inline_css');
    
    // Remove problematic actions
    remove_action('send_headers', 'mts_add_cache_headers');
    remove_action('pre_get_posts', 'mts_optimize_post_queries');
    remove_action('wp_generate_attachment_metadata', 'mts_generate_webp_images');
    remove_action('wp_enqueue_scripts', 'mts_enqueue_accessibility_assets');
    
    // Add safe, minimal optimizations
    add_action('wp_head', 'mts_safe_head_optimizations', 1);
    add_action('wp_footer', 'mts_safe_footer_optimizations', 1);
}

/**
 * Safe head optimizations
 */
function mts_safe_head_optimizations() {
    // Only add essential meta tags
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    
    // Add basic critical CSS inline
    echo '<style id="mts-critical-css">';
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
 * Safe footer optimizations
 */
function mts_safe_footer_optimizations() {
    // Add basic JavaScript for essential functionality
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded",function(){';
    echo 'console.log("Safe mode: Basic optimizations loaded");';
    echo 'var images=document.querySelectorAll("img");';
    echo 'images.forEach(function(img){';
    echo 'if(!img.src&&img.dataset.src){img.src=img.dataset.src}';
    echo '});';
    echo '});';
    echo '</script>';
}

/**
 * Fix specific loading issues
 */
function mts_fix_specific_loading_issues() {
    // Remove any hooks that might cause infinite loops
    remove_action('init', 'mts_init_performance_optimizations');
    remove_action('init', 'mts_init_plugin_compatibility');
    remove_action('init', 'mts_init_accessibility_fixes');
    remove_action('init', 'mts_init_seo_enhancements');
    
    // Remove any problematic admin hooks
    remove_action('admin_init', 'mts_add_plugin_compatibility_notices');
    remove_action('customize_register', 'mts_add_plugin_compatibility_customizer');
    
    // Add safe initialization
    add_action('init', 'mts_safe_initialization', 1);
}

/**
 * Safe initialization
 */
function mts_safe_initialization() {
    // Only run on frontend
    if (is_admin()) {
        return;
    }
    
    // Add safe optimizations
    mts_safe_head_optimizations();
    mts_safe_footer_optimizations();
    
    // Add loading fix notice for admins
    if (current_user_can('manage_options')) {
        add_action('wp_head', function() {
            echo '<!-- Loading Fix: Safe mode enabled -->';
        }, 999);
    }
}

/**
 * Add loading fix notice
 */
function mts_loading_fix_notice() {
    if (current_user_can('manage_options')) {
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p><strong>Loading Fix:</strong> Safe mode enabled to prevent infinite loading. Site should load normally now.</p>';
        echo '</div>';
    }
}

// Initialize loading fix
add_action('init', 'mts_fix_infinite_loading', 1);
add_action('init', 'mts_fix_specific_loading_issues', 1);
add_action('admin_notices', 'mts_loading_fix_notice');
