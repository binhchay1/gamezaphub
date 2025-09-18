<?php
/**
 * Emergency Disable - Fix Infinite Loading Issues
 * 
 * Temporarily disable all optimizations to fix site loading issues
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Emergency disable all theme optimizations
 */
function mts_emergency_disable_optimizations() {
    // Disable all performance optimizations
    remove_action('init', 'mts_init_performance_optimizations');
    remove_action('init', 'mts_init_plugin_compatibility');
    remove_action('init', 'mts_init_accessibility_fixes');
    remove_action('init', 'mts_init_seo_enhancements');
    
    // Remove all wp_head actions
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
    
    // Remove all wp_footer actions
    remove_action('wp_footer', 'mts_add_aria_labels_script');
    remove_action('wp_footer', 'mts_add_plugin_compatibility_js', 1);
    
    // Remove all filters
    remove_filter('script_loader_tag', 'mts_add_defer_attribute', 10);
    remove_filter('wp_get_attachment_image_attributes', 'mts_add_lazy_loading_attributes', 10);
    remove_filter('style_loader_tag', 'mts_minify_inline_css');
    
    // Remove send_headers actions
    remove_action('send_headers', 'mts_add_cache_headers');
    
    // Remove pre_get_posts actions
    remove_action('pre_get_posts', 'mts_optimize_post_queries');
    
    // Remove wp_generate_attachment_metadata actions
    remove_action('wp_generate_attachment_metadata', 'mts_generate_webp_images');
    
    // Remove wp_enqueue_scripts actions
    remove_action('wp_enqueue_scripts', 'mts_enqueue_accessibility_assets');
    
    // Remove admin actions
    remove_action('admin_init', 'mts_add_plugin_compatibility_notices');
    remove_action('customize_register', 'mts_add_plugin_compatibility_customizer');
    
    // Remove debug actions
    remove_action('wp_footer', 'mts_debug_plugin_compatibility');
    
    // Add emergency notice
    add_action('admin_notices', 'mts_emergency_notice');
}

/**
 * Emergency notice
 */
function mts_emergency_notice() {
    if (current_user_can('manage_options')) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>Emergency Mode:</strong> All theme optimizations have been disabled to fix loading issues. Please check your site and contact support if needed.</p>';
        echo '</div>';
    }
}

/**
 * Add emergency CSS to prevent layout issues
 */
function mts_emergency_css() {
    ?>
    <style>
    /* Emergency CSS to prevent layout issues */
    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        margin: 0;
        padding: 0;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .header {
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px 0;
    }
    
    .main-content {
        min-height: 400px;
        padding: 20px 0;
    }
    
    .footer {
        background: #f8f9fa;
        padding: 20px 0;
        margin-top: 40px;
    }
    
    /* Ensure images load properly */
    img {
        max-width: 100%;
        height: auto;
    }
    
    /* Basic link styles */
    a {
        color: #0073aa;
        text-decoration: none;
    }
    
    a:hover {
        color: #005177;
        text-decoration: underline;
    }
    
    /* Basic button styles */
    .button, button, input[type="submit"] {
        background: #0073aa;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 3px;
        cursor: pointer;
    }
    
    .button:hover, button:hover, input[type="submit"]:hover {
        background: #005177;
    }
    
    /* Basic form styles */
    input, textarea, select {
        border: 1px solid #ddd;
        padding: 8px 12px;
        border-radius: 3px;
        width: 100%;
        max-width: 300px;
    }
    
    input:focus, textarea:focus, select:focus {
        border-color: #0073aa;
        outline: 2px solid #0073aa;
        outline-offset: 2px;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }
        
        .header, .main-content, .footer {
            padding: 15px 0;
        }
    }
    </style>
    <?php
}

/**
 * Initialize emergency mode
 */
function mts_init_emergency_mode() {
    // Only run on frontend
    if (is_admin()) {
        return;
    }
    
    // Disable all optimizations
    mts_emergency_disable_optimizations();
    
    // Add emergency CSS
    add_action('wp_head', 'mts_emergency_css', 1);
    
    // Add emergency JavaScript to prevent any JS conflicts
    add_action('wp_footer', 'mts_emergency_js', 1);
}

/**
 * Emergency JavaScript
 */
function mts_emergency_js() {
    ?>
    <script>
    // Emergency JavaScript to prevent conflicts
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent any infinite loops
        console.log('Emergency mode: Theme optimizations disabled');
        
        // Basic image loading
        var images = document.querySelectorAll('img');
        images.forEach(function(img) {
            if (!img.src && img.dataset.src) {
                img.src = img.dataset.src;
            }
        });
        
        // Basic lazy loading fallback
        if ('IntersectionObserver' in window) {
            var lazyImages = document.querySelectorAll('img[data-src]');
            if (lazyImages.length > 0) {
                var imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                lazyImages.forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        }
    });
    </script>
    <?php
}

// Initialize emergency mode
add_action('init', 'mts_init_emergency_mode', 1);

/**
 * Add emergency mode indicator
 */
function mts_emergency_mode_indicator() {
    if (current_user_can('manage_options') && !is_admin()) {
        echo '<!-- Emergency Mode: All optimizations disabled -->';
    }
}

add_action('wp_head', 'mts_emergency_mode_indicator', 999);
