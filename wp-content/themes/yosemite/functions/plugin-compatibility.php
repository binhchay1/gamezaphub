<?php
/**
 * Plugin Compatibility Functions for Yosemite Theme
 * 
 * Handles conflicts with WP Rocket, Smush Pro, and other optimization plugins
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check for WP Rocket compatibility
 */
function mts_check_wp_rocket_compatibility() {
    return function_exists('rocket_init') || defined('WP_ROCKET_VERSION');
}

/**
 * Check for Smush Pro compatibility
 */
function mts_check_smush_pro_compatibility() {
    return function_exists('wp_smushit') || class_exists('Smush\\Core\\Core');
}

/**
 * Handle WP Rocket conflicts
 */
function mts_handle_wp_rocket_conflicts() {
    if (!mts_check_wp_rocket_compatibility()) {
        return;
    }
    
    // Disable theme's cache headers if WP Rocket is active
    remove_action('send_headers', 'mts_add_cache_headers');
    
    // Disable theme's minification if WP Rocket minification is active
    if (get_option('rocket_minify_css') || get_option('rocket_minify_js')) {
        remove_filter('style_loader_tag', 'mts_minify_inline_css');
    }
    
    // Disable theme's defer attributes if WP Rocket delay JS is active
    if (get_option('rocket_delay_js')) {
        remove_filter('script_loader_tag', 'mts_add_defer_attribute');
    }
    
    // Disable theme's preload if WP Rocket preload is active
    if (get_option('rocket_preload_fonts') || get_option('rocket_preload_links')) {
        remove_action('wp_head', 'mts_add_preload_resources', 1);
    }
    
    // Add WP Rocket specific optimizations
    add_action('wp_head', 'mts_wp_rocket_optimizations', 1);
}

/**
 * WP Rocket specific optimizations
 */
function mts_wp_rocket_optimizations() {
    // Add critical CSS inline if WP Rocket critical CSS is disabled
    if (!get_option('rocket_critical_css')) {
        echo '<style id="mts-critical-css">';
        echo 'body{font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;line-height:1.6;color:#333;margin:0;padding:0}';
        echo '.container{max-width:1200px;margin:0 auto;padding:0 20px}';
        echo '.header{background:#fff;box-shadow:0 2px 4px rgba(0,0,0,0.1)}';
        echo '.main-content{min-height:400px;padding:20px 0}';
        echo '.footer{background:#f8f9fa;padding:20px 0;margin-top:40px}';
        echo '</style>';
    }
    
    // Add resource hints for WP Rocket
    if (get_option('rocket_dns_prefetch')) {
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">' . "\n";
    }
}

/**
 * Handle Smush Pro conflicts
 */
function mts_handle_smush_pro_conflicts() {
    if (!mts_check_smush_pro_compatibility()) {
        return;
    }
    
    // Disable theme's WebP generation if Smush Pro WebP is active
    if (get_option('wp-smush-webp')) {
        remove_action('wp_generate_attachment_metadata', 'mts_add_webp_metadata');
        remove_action('wp_generate_attachment_metadata', 'mts_generate_webp_images');
    }
    
    // Disable theme's lazy loading if Smush Pro lazy loading is active
    if (get_option('wp-smush-lazy_load')) {
        remove_filter('wp_get_attachment_image_attributes', 'mts_add_lazy_loading_attributes', 10, 3);
        remove_action('wp_footer', 'mts_add_lazy_loading_script');
    }
    
    // Add Smush Pro specific optimizations
    add_action('wp_head', 'mts_smush_pro_optimizations', 1);
}

/**
 * Smush Pro specific optimizations
 */
function mts_smush_pro_optimizations() {
    // Add WebP support detection
    echo '<script>';
    echo 'function supportsWebP() {';
    echo '  var elem = document.createElement("canvas");';
    echo '  return !!(elem.getContext && elem.getContext("2d"));';
    echo '}';
    echo 'if (supportsWebP()) {';
    echo '  document.documentElement.classList.add("webp");';
    echo '} else {';
    echo '  document.documentElement.classList.add("no-webp");';
    echo '}';
    echo '</script>';
    
    // Add lazy loading fallback for browsers that don't support native lazy loading
    echo '<script>';
    echo 'if ("loading" in HTMLImageElement.prototype) {';
    echo '  var images = document.querySelectorAll("img[data-src]");';
    echo '  images.forEach(function(img) {';
    echo '    img.src = img.dataset.src;';
    echo '    img.removeAttribute("data-src");';
    echo '  });';
    echo '} else {';
    echo '  var script = document.createElement("script");';
    echo '  script.src = "https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js";';
    echo '  document.head.appendChild(script);';
    echo '}';
    echo '</script>';
}

/**
 * Handle other optimization plugin conflicts
 */
function mts_handle_other_plugin_conflicts() {
    // W3 Total Cache
    if (function_exists('w3tc_flush_all')) {
        remove_action('send_headers', 'mts_add_cache_headers');
        remove_filter('style_loader_tag', 'mts_minify_inline_css');
    }
    
    // WP Super Cache
    if (function_exists('wp_cache_init')) {
        remove_action('send_headers', 'mts_add_cache_headers');
    }
    
    // Autoptimize
    if (function_exists('autoptimize')) {
        remove_filter('style_loader_tag', 'mts_minify_inline_css');
        remove_filter('script_loader_tag', 'mts_add_defer_attribute');
    }
    
    // WP Fastest Cache
    if (class_exists('WpFastestCache')) {
        remove_action('send_headers', 'mts_add_cache_headers');
    }
    
    // LiteSpeed Cache
    if (class_exists('LiteSpeed_Cache')) {
        remove_action('send_headers', 'mts_add_cache_headers');
        remove_filter('style_loader_tag', 'mts_minify_inline_css');
    }
}

/**
 * Add plugin-specific CSS optimizations
 */
function mts_add_plugin_compatibility_css() {
    $css = '';
    
    // WP Rocket specific styles
    if (mts_check_wp_rocket_compatibility()) {
        $css .= '
        /* WP Rocket compatibility */
        .rocket-lazyload {
            opacity: 0;
            transition: opacity 0.3s;
        }
        .rocket-lazyload.loaded {
            opacity: 1;
        }
        ';
    }
    
    // Smush Pro specific styles
    if (mts_check_smush_pro_compatibility()) {
        $css .= '
        /* Smush Pro compatibility */
        .smush-lazy-load {
            opacity: 0;
            transition: opacity 0.3s;
        }
        .smush-lazy-load.loaded {
            opacity: 1;
        }
        ';
    }
    
    if (!empty($css)) {
        echo '<style id="mts-plugin-compatibility">' . $css . '</style>';
    }
}

/**
 * Add plugin-specific JavaScript optimizations
 */
function mts_add_plugin_compatibility_js() {
    $js = '';
    
    // WP Rocket specific JavaScript
    if (mts_check_wp_rocket_compatibility()) {
        $js .= '
        // WP Rocket compatibility
        document.addEventListener("DOMContentLoaded", function() {
            // Handle rocket-lazyload images
            var rocketImages = document.querySelectorAll(".rocket-lazyload");
            if (rocketImages.length > 0) {
                var rocketObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("loaded");
                        }
                    });
                });
                rocketImages.forEach(function(img) {
                    rocketObserver.observe(img);
                });
            }
        });
        ';
    }
    
    // Smush Pro specific JavaScript
    if (mts_check_smush_pro_compatibility()) {
        $js .= '
        // Smush Pro compatibility
        document.addEventListener("DOMContentLoaded", function() {
            // Handle smush-lazy-load images
            var smushImages = document.querySelectorAll(".smush-lazy-load");
            if (smushImages.length > 0) {
                var smushObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("loaded");
                        }
                    });
                });
                smushImages.forEach(function(img) {
                    smushObserver.observe(img);
                });
            }
        });
        ';
    }
    
    if (!empty($js)) {
        echo '<script id="mts-plugin-compatibility">' . $js . '</script>';
    }
}

/**
 * Add plugin detection and compatibility notices
 */
function mts_add_plugin_compatibility_notices() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $notices = array();
    
    // WP Rocket detection
    if (mts_check_wp_rocket_compatibility()) {
        $notices[] = array(
            'type' => 'success',
            'message' => __('WP Rocket detected! Theme optimizations have been adjusted for compatibility.', 'mythemeshop')
        );
    }
    
    // Smush Pro detection
    if (mts_check_smush_pro_compatibility()) {
        $notices[] = array(
            'type' => 'success',
            'message' => __('Smush Pro detected! Image optimizations have been adjusted for compatibility.', 'mythemeshop')
        );
    }
    
    // Display notices
    foreach ($notices as $notice) {
        add_action('admin_notices', function() use ($notice) {
            echo '<div class="notice notice-' . esc_attr($notice['type']) . ' is-dismissible">';
            echo '<p>' . esc_html($notice['message']) . '</p>';
            echo '</div>';
        });
    }
}

/**
 * Add plugin compatibility settings to customizer
 */
function mts_add_plugin_compatibility_customizer($wp_customize) {
    // Plugin Compatibility Section
    $wp_customize->add_section('mts_plugin_compatibility', array(
        'title' => __('Plugin Compatibility', 'mythemeshop'),
        'priority' => 250,
    ));
    
    // WP Rocket Compatibility
    $wp_customize->add_setting('mts_wp_rocket_compatibility', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mts_wp_rocket_compatibility', array(
        'label' => __('WP Rocket Compatibility', 'mythemeshop'),
        'description' => __('Automatically adjust theme optimizations when WP Rocket is active.', 'mythemeshop'),
        'section' => 'mts_plugin_compatibility',
        'type' => 'checkbox',
    ));
    
    // Smush Pro Compatibility
    $wp_customize->add_setting('mts_smush_pro_compatibility', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mts_smush_pro_compatibility', array(
        'label' => __('Smush Pro Compatibility', 'mythemeshop'),
        'description' => __('Automatically adjust image optimizations when Smush Pro is active.', 'mythemeshop'),
        'section' => 'mts_plugin_compatibility',
        'type' => 'checkbox',
    ));
    
    // Other Plugins Compatibility
    $wp_customize->add_setting('mts_other_plugins_compatibility', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mts_other_plugins_compatibility', array(
        'label' => __('Other Optimization Plugins Compatibility', 'mythemeshop'),
        'description' => __('Automatically adjust theme optimizations for other optimization plugins.', 'mythemeshop'),
        'section' => 'mts_plugin_compatibility',
        'type' => 'checkbox',
    ));
}

/**
 * Initialize plugin compatibility
 */
function mts_init_plugin_compatibility() {
    // Handle conflicts
    mts_handle_wp_rocket_conflicts();
    mts_handle_smush_pro_conflicts();
    mts_handle_other_plugin_conflicts();
    
    // Add compatibility assets
    add_action('wp_head', 'mts_add_plugin_compatibility_css', 1);
    add_action('wp_footer', 'mts_add_plugin_compatibility_js', 1);
    
    // Add admin notices
    add_action('admin_init', 'mts_add_plugin_compatibility_notices');
    
    // Add customizer options
    add_action('customize_register', 'mts_add_plugin_compatibility_customizer');
}

// Initialize plugin compatibility
add_action('init', 'mts_init_plugin_compatibility');

/**
 * Add plugin compatibility information to theme info
 */
function mts_add_plugin_compatibility_info() {
    $compatibility_info = array(
        'wp_rocket' => mts_check_wp_rocket_compatibility(),
        'smush_pro' => mts_check_smush_pro_compatibility(),
        'w3_total_cache' => function_exists('w3tc_flush_all'),
        'wp_super_cache' => function_exists('wp_cache_init'),
        'autoptimize' => function_exists('autoptimize'),
        'wp_fastest_cache' => class_exists('WpFastestCache'),
        'litespeed_cache' => class_exists('LiteSpeed_Cache'),
    );
    
    return $compatibility_info;
}

/**
 * Add plugin compatibility debug information
 */
function mts_debug_plugin_compatibility() {
    if (!current_user_can('manage_options') || !isset($_GET['debug_plugins'])) {
        return;
    }
    
    $compatibility_info = mts_add_plugin_compatibility_info();
    
    echo '<div style="background: #f1f1f1; padding: 20px; margin: 20px 0; border: 1px solid #ccc;">';
    echo '<h3>Plugin Compatibility Debug Information</h3>';
    echo '<pre>';
    print_r($compatibility_info);
    echo '</pre>';
    echo '</div>';
}

add_action('wp_footer', 'mts_debug_plugin_compatibility');
