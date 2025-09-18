<?php
/**
 * Accessibility Fixes for PageSpeed Insights
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fix Viewport Meta Tag
 */
function mts_fix_viewport_meta() {
    // Remove the problematic viewport meta tag
    remove_action('wp_head', 'wp_site_icon');
    
    // Add proper viewport meta tag
    add_action('wp_head', 'mts_add_proper_viewport_meta', 1);
}

function mts_add_proper_viewport_meta() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
}

/**
 * Fix Color Contrast Issues
 */
function mts_fix_color_contrast() {
    add_action('wp_head', 'mts_add_contrast_fixes', 10);
}

function mts_add_contrast_fixes() {
    ?>
    <style>
    /* Fix color contrast issues */
    .copyrights a {
        color: #0073aa !important;
        text-decoration: underline !important;
    }
    
    .copyrights a:hover {
        color: #005177 !important;
    }
    
    /* Fix author link contrast */
    .postauthor h4 a,
    .postauthor h5 a {
        color: #0073aa !important;
        text-decoration: underline !important;
    }
    
    .postauthor h4 a:hover,
    .postauthor h5 a:hover {
        color: #005177 !important;
    }
    
    /* Fix category links */
    .post-info a,
    .tags a {
        color: #0073aa !important;
        text-decoration: underline !important;
    }
    
    .post-info a:hover,
    .tags a:hover {
        color: #005177 !important;
    }
    
    /* Fix comment links */
    .reply a {
        color: #0073aa !important;
        text-decoration: underline !important;
    }
    
    .reply a:hover {
        color: #005177 !important;
    }
    
    /* Fix rating text contrast */
    .rating-title {
        color: #333 !important;
        font-weight: 600 !important;
    }
    
    .genre-item {
        color: #333 !important;
        font-weight: 500 !important;
    }
    
    /* Fix percentage text contrast */
    .text-green {
        color: #2d5a2d !important;
        font-weight: 600 !important;
    }
    
    .text-yellow {
        color: #8b5a00 !important;
        font-weight: 600 !important;
    }
    
    .text-grey {
        color: #555 !important;
        font-weight: 600 !important;
    }
    
    .text-red {
        color: #8b0000 !important;
        font-weight: 600 !important;
    }
    
    /* Fix footer links */
    .main-footer a {
        color: #0073aa !important;
        text-decoration: underline !important;
    }
    
    .main-footer a:hover {
        color: #005177 !important;
    }
    
    /* Fix navigation links */
    #navigation ul li a {
        color: #333 !important;
    }
    
    #navigation ul li a:hover {
        color: #0073aa !important;
        text-decoration: underline !important;
    }
    
    /* Fix widget links */
    .widget a {
        color: #0073aa !important;
        text-decoration: underline !important;
    }
    
    .widget a:hover {
        color: #005177 !important;
    }
    
    /* Fix button contrast */
    .button,
    .readMore a.button,
    #commentform input#submit,
    .contactform #submit,
    #mtscontact_submit {
        background: #0073aa !important;
        color: #fff !important;
        border: 2px solid #005177 !important;
    }
    
    .button:hover,
    .readMore a.button:hover,
    #commentform input#submit:hover,
    .contactform #submit:hover,
    #mtscontact_submit:hover {
        background: #005177 !important;
        color: #fff !important;
    }
    
    /* Fix pagination contrast */
    .pagination a,
    .pagination ul li span,
    .pagination > span {
        background: #0073aa !important;
        color: #fff !important;
        border: 2px solid #005177 !important;
    }
    
    .pagination a:hover,
    .pagination span.current {
        background: #005177 !important;
        color: #fff !important;
    }
    
    /* Fix search button contrast */
    .header-search .fa-search {
        color: #333 !important;
    }
    
    .header-search .fa-search:hover {
        color: #0073aa !important;
    }
    
    /* Fix mobile menu button */
    .toggle-mobile-menu {
        color: #333 !important;
        background: #f8f8f8 !important;
        border: 2px solid #ddd !important;
    }
    
    .toggle-mobile-menu:hover {
        color: #0073aa !important;
        background: #e8f4fd !important;
        border-color: #0073aa !important;
    }
    </style>
    <?php
}

/**
 * Fix Link Accessibility
 */
function mts_fix_link_accessibility() {
    add_action('wp_head', 'mts_add_link_accessibility_fixes', 10);
}

function mts_add_link_accessibility_fixes() {
    ?>
    <style>
    /* Ensure all links have proper focus states */
    a:focus {
        outline: 2px solid #0073aa !important;
        outline-offset: 2px !important;
        background-color: #e8f4fd !important;
    }
    
    /* Fix search link */
    .header-search .fa-search {
        position: relative;
    }
    
    .header-search .fa-search::after {
        content: "Search";
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
    
    /* Fix mobile menu link */
    .toggle-mobile-menu::after {
        content: "Menu";
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
    
    /* Add visible text for icon-only links */
    .fa-search::before {
        content: "🔍 ";
    }
    
    .toggle-mobile-menu .fa-bars::before {
        content: "☰ ";
    }
    </style>
    <?php
}

/**
 * Fix Heading Order
 */
function mts_fix_heading_order() {
    add_action('wp_head', 'mts_add_heading_order_fixes', 10);
}

function mts_add_heading_order_fixes() {
    ?>
    <style>
    /* Fix heading hierarchy */
    .postauthor h4 {
        font-size: 18px !important;
    }
    
    .postauthor h5 {
        font-size: 16px !important;
    }
    
    .section-title {
        font-size: 20px !important;
        font-weight: 600 !important;
    }
    
    /* Ensure proper heading order in widgets */
    .widget h3 {
        font-size: 16px !important;
    }
    
    .widget h4 {
        font-size: 15px !important;
    }
    
    .widget h5 {
        font-size: 14px !important;
    }
    
    .widget h6 {
        font-size: 13px !important;
    }
    </style>
    <?php
}

/**
 * Add ARIA Labels to Problematic Elements
 */
function mts_add_aria_labels() {
    add_action('wp_footer', 'mts_add_aria_labels_script');
}

function mts_add_aria_labels_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add ARIA labels to search button
        const searchButton = document.querySelector('.header-search .fa-search');
        if (searchButton) {
            searchButton.setAttribute('aria-label', 'Open search');
            searchButton.setAttribute('role', 'button');
            searchButton.setAttribute('tabindex', '0');
        }
        
        // Add ARIA labels to mobile menu button
        const mobileMenuButton = document.querySelector('.toggle-mobile-menu');
        if (mobileMenuButton) {
            mobileMenuButton.setAttribute('aria-label', 'Toggle mobile menu');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            mobileMenuButton.setAttribute('role', 'button');
        }
        
        // Add ARIA labels to all links without text
        const links = document.querySelectorAll('a');
        links.forEach(function(link) {
            if (!link.textContent.trim() && !link.getAttribute('aria-label')) {
                const href = link.getAttribute('href');
                if (href === '#') {
                    link.setAttribute('aria-label', 'Link');
                } else if (href) {
                    link.setAttribute('aria-label', 'Link to ' + href);
                }
            }
        });
        
        // Fix heading order dynamically
        const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
        let lastLevel = 0;
        
        headings.forEach(function(heading) {
            const level = parseInt(heading.tagName.charAt(1));
            
            if (level > lastLevel + 1) {
                // Skip levels detected, adjust
                const newLevel = Math.min(lastLevel + 1, 6);
                const newTag = 'h' + newLevel;
                
                const newHeading = document.createElement(newTag);
                newHeading.innerHTML = heading.innerHTML;
                newHeading.className = heading.className;
                newHeading.id = heading.id;
                
                heading.parentNode.replaceChild(newHeading, heading);
                lastLevel = newLevel;
            } else {
                lastLevel = level;
            }
        });
    });
    </script>
    <?php
}

/**
 * Fix Form Accessibility
 */
function mts_fix_form_accessibility() {
    add_action('wp_head', 'mts_add_form_accessibility_fixes', 10);
}

function mts_add_form_accessibility_fixes() {
    ?>
    <style>
    /* Fix form field contrast */
    input[type="text"],
    input[type="email"],
    input[type="search"],
    input[type="url"],
    textarea,
    select {
        border: 2px solid #ddd !important;
        background: #fff !important;
        color: #333 !important;
    }
    
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="search"]:focus,
    input[type="url"]:focus,
    textarea:focus,
    select:focus {
        border-color: #0073aa !important;
        outline: 2px solid #0073aa !important;
        outline-offset: 2px !important;
    }
    
    /* Fix form labels */
    label {
        color: #333 !important;
        font-weight: 600 !important;
    }
    
    /* Fix required field indicators */
    .required {
        color: #d63638 !important;
        font-weight: bold !important;
    }
    
    /* Fix error messages */
    .error-message {
        color: #d63638 !important;
        font-weight: 600 !important;
    }
    
    /* Fix success messages */
    .success-message {
        color: #00a32a !important;
        font-weight: 600 !important;
    }
    </style>
    <?php
}

/**
 * Fix Table Accessibility
 */
function mts_fix_table_accessibility() {
    add_action('wp_head', 'mts_add_table_accessibility_fixes', 10);
}

function mts_add_table_accessibility_fixes() {
    ?>
    <style>
    /* Fix table contrast */
    table {
        border: 2px solid #333 !important;
    }
    
    table th {
        background: #f8f8f8 !important;
        color: #333 !important;
        font-weight: 600 !important;
        border: 1px solid #333 !important;
    }
    
    table td {
        border: 1px solid #333 !important;
        color: #333 !important;
    }
    
    table tr:nth-child(even) {
        background: #f9f9f9 !important;
    }
    
    table tr:hover {
        background: #e8f4fd !important;
    }
    
    /* Fix table caption */
    table caption {
        color: #333 !important;
        font-weight: 600 !important;
        font-size: 16px !important;
    }
    </style>
    <?php
}

/**
 * Enqueue Accessibility CSS and JS
 */
function mts_enqueue_accessibility_assets() {
    wp_enqueue_style(
        'mts-accessibility-fixes',
        get_template_directory_uri() . '/css/accessibility-fixes.css',
        array(),
        '1.0'
    );
    
    wp_enqueue_script(
        'mts-accessibility-fixes',
        get_template_directory_uri() . '/js/accessibility-fixes.js',
        array(),
        '1.0',
        true
    );
}

/**
 * Initialize all accessibility fixes
 */
function mts_init_accessibility_fixes() {
    mts_fix_viewport_meta();
    mts_fix_color_contrast();
    mts_fix_link_accessibility();
    mts_fix_heading_order();
    mts_add_aria_labels();
    mts_fix_form_accessibility();
    mts_fix_table_accessibility();
    
    add_action('wp_enqueue_scripts', 'mts_enqueue_accessibility_assets');
}

// Initialize accessibility fixes
add_action('init', 'mts_init_accessibility_fixes');
