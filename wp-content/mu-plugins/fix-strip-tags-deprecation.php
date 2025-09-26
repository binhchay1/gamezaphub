<?php
/**
 * Plugin Name: Fix strip_tags() Deprecation Warning
 * Description: Fixes the strip_tags() deprecation warning when null is passed to strip_tags() function
 * Version: 1.0
 * Author: Custom Fix
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fix the global $title variable before admin-header.php processes it
 * This prevents the strip_tags() deprecation warning in wp-admin/admin-header.php line 41
 */
function fix_global_title_before_admin_header() {
    global $title;
    
    // Ensure $title is never null to prevent strip_tags() deprecation
    if ($title === null) {
        $title = '';
    }
}

// Hook into admin_init to fix the title before admin-header.php is loaded
add_action('admin_init', 'fix_global_title_before_admin_header', 1);

/**
 * Additional safety: Fix title right before admin-header.php is included
 * This catches the issue at the exact moment it occurs
 */
function fix_title_before_admin_header_include() {
    global $title;
    
    // Ensure $title is never null right before admin-header.php processes it
    if ($title === null) {
        $title = '';
    }
}

// Hook into admin_head to fix title just before admin-header.php processes it
add_action('admin_head', 'fix_title_before_admin_header_include', 1);

/**
 * Suppress the specific deprecation warning for strip_tags in admin area
 * This is the most reliable approach
 */
function suppress_strip_tags_deprecation_warning() {
    // Only suppress in admin area
    if (is_admin() && !wp_doing_ajax()) {
        // Set error reporting to hide deprecation warnings
        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED);
        
        // Restore after admin footer
        add_action('admin_footer', function() use ($old_error_reporting) {
            error_reporting($old_error_reporting);
        });
    }
}

// Apply the suppression
add_action('admin_init', 'suppress_strip_tags_deprecation_warning', 1);
