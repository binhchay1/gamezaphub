<?php
/**
 * HTML Output Fixes
 * Fix heading hierarchy and add aria-labels via output buffering
 * 
 * @package Bloggers
 */

/**
 * Start output buffering to fix HTML before sending to browser
 */
add_action('template_redirect', 'bloggers_start_html_fixes', 1);
function bloggers_start_html_fixes()
{
    if (!is_admin()) {
        ob_start('bloggers_fix_html_output');
    }
}

/**
 * Fix HTML output - Replace H4 with P and add aria-labels
 */
function bloggers_fix_html_output($html)
{
    if (empty($html) || is_admin()) {
        return $html;
    }

    // ========================================================================
    // 1. FIX HEADING HIERARCHY - Replace H4 in related posts with P
    // ========================================================================
    
    // Fix: <h4 class="title sm mb-0"> in related posts
    $html = preg_replace(
        '/<h4(\s+class="title\s+sm\s+mb-0"[^>]*)>(.*?)<\/h4>/is',
        '<p$1 style="font-size: 1.125rem; font-weight: 600; line-height: 1.4; margin-bottom: 0;">$2</p>',
        $html
    );
    
    // Fix: <h4 class="title"> in author box (By admin)
    $html = preg_replace(
        '/<h4(\s+class="title"[^>]*)>(\s*By\s+.*?)<\/h4>/is',
        '<p$1 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">$2</p>',
        $html
    );

    // ========================================================================
    // 2. ADD ARIA-LABELS TO LINKS
    // ========================================================================
    
    // Fix: <a class="auth" href="..."> - Author links
    $html = preg_replace_callback(
        '/<a(\s+[^>]*?class="[^"]*\bauth\b[^"]*"[^>]*?)>/i',
        function($matches) {
            if (stripos($matches[0], 'aria-label') !== false) {
                return $matches[0];
            }
            return '<a' . $matches[1] . ' aria-label="View author profile">';
        },
        $html
    );
    
    // Fix: <a class="bs-author-pic" href="..."> - Author picture links
    $html = preg_replace_callback(
        '/<a(\s+[^>]*?class="[^"]*\bbs-author-pic\b[^"]*"[^>]*?)>/i',
        function($matches) {
            if (stripos($matches[0], 'aria-label') !== false) {
                return $matches[0];
            }
            return '<a' . $matches[1] . ' aria-label="View author profile and posts">';
        },
        $html
    );
    
    // Fix: <a class="link-div" href="..."> - Overlay links
    $html = preg_replace_callback(
        '/<a(\s+[^>]*?class="[^"]*\blink-div\b[^"]*"[^>]*?)>/i',
        function($matches) {
            if (stripos($matches[0], 'aria-label') !== false) {
                return $matches[0];
            }
            return '<a' . $matches[1] . ' aria-label="Read full article">';
        },
        $html
    );
    
    // Fix: <a ... class="bs-blog-thumb caption" href="..."> - Featured image links
    $html = preg_replace_callback(
        '/<a(\s+[^>]*?class="[^"]*\bbs-blog-thumb\b[^"]*"[^>]*?)>/i',
        function($matches) {
            if (stripos($matches[0], 'aria-label') !== false) {
                return $matches[0];
            }
            return '<a' . $matches[1] . ' aria-label="View featured image">';
        },
        $html
    );
    
    // Fix: <a class="blogarise-categories ..." href="...">Category Name</a>
    $html = preg_replace_callback(
        '/<a(\s+[^>]*?class="[^"]*\bblogarise-categories\b[^"]*"[^>]*?)>([^<]+)<\/a>/i',
        function($matches) {
            if (stripos($matches[0], 'aria-label') !== false) {
                return $matches[0];
            }
            $cat_name = strip_tags($matches[2]);
            return '<a' . $matches[1] . ' aria-label="View all posts in ' . esc_attr($cat_name) . '">' . $matches[2] . '</a>';
        },
        $html
    );

    return $html;
}


