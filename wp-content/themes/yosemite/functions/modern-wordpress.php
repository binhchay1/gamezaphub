<?php
/**
 * Modern WordPress Compatibility for Yosemite Theme
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Replace deprecated wp_title with modern document title
 */
function mts_modern_document_title($title_parts) {
    global $paged, $page, $mts_options;
    
    if (is_feed()) {
        return $title_parts;
    }
    
    if (!isset($title_parts['site'])) {
        $title_parts['site'] = get_bloginfo('name');
    }
    
    if ((is_home() || is_front_page()) && !isset($title_parts['tagline'])) {
        $site_description = get_bloginfo('description', 'display');
        if ($site_description) {
            $title_parts['tagline'] = $site_description;
        }
    }
    
    if (($paged >= 2 || $page >= 2) && !isset($title_parts['page'])) {
        $title_parts['page'] = sprintf(__('Page %s', 'mythemeshop'), max($paged, $page));
    }
    
    return $title_parts;
}

/**
 * Enhanced document title separator
 */
function mts_document_title_separator($separator) {
    return '|';
}

/**
 * Modernize comment form
 */
function mts_modernize_comment_form($args) {
    $args['class_form'] = 'comment-form';
    $args['class_submit'] = 'submit btn btn-primary';
    $args['submit_button'] = '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />';
    $args['submit_field'] = '<p class="form-submit">%1$s %2$s</p>';
    
    return $args;
}

/**
 * Add modern WordPress features
 */
function mts_add_modern_features() {
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    add_theme_support('editor-styles');
    add_editor_style('css/editor-style.css');
    
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
    
    add_theme_support('custom-header', array(
        'default-image' => '',
        'default-text-color' => '000000',
        'width' => 1200,
        'height' => 600,
        'flex-height' => true,
        'flex-width' => true,
    ));
}

/**
 * Modernize navigation menus
 */
function mts_modernize_navigation() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'mythemeshop'),
        'footer-menu' => __('Footer Menu', 'mythemeshop'),
        'mobile-menu' => __('Mobile Menu', 'mythemeshop'),
    ));
}

/**
 * Enhanced widget areas
 */
function mts_enhanced_widget_areas() {
    $mts_options = get_option(MTS_THEME_NAME);
    
    register_sidebar(array(
        'name' => __('Sidebar', 'mythemeshop'),
        'description' => __('Default sidebar.', 'mythemeshop'),
        'id' => 'sidebar',
        'before_widget' => '<div id="%1$s" class="widget panel %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget Area 1', 'mythemeshop'),
        'description' => __('First footer widget area.', 'mythemeshop'),
        'id' => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget Area 2', 'mythemeshop'),
        'description' => __('Second footer widget area.', 'mythemeshop'),
        'id' => 'footer-2',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget Area 3', 'mythemeshop'),
        'description' => __('Third footer widget area.', 'mythemeshop'),
        'id' => 'footer-3',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    if (!empty($mts_options['mts_custom_sidebars']) && is_array($mts_options['mts_custom_sidebars'])) {
        foreach ($mts_options['mts_custom_sidebars'] as $sidebar) {
            if (!empty($sidebar['mts_custom_sidebar_id']) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-') {
                register_sidebar(array(
                    'name' => $sidebar['mts_custom_sidebar_name'],
                    'id' => sanitize_title(strtolower($sidebar['mts_custom_sidebar_id'])),
                    'before_widget' => '<div id="%1$s" class="widget panel %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h3 class="widget-title">',
                    'after_title' => '</h3>',
                ));
            }
        }
    }
}

/**
 * Modernize image handling
 */
function mts_modernize_image_handling() {
    add_image_size('mts-featured', 1200, 600, true);
    add_image_size('mts-thumbnail', 300, 200, true);
    add_image_size('mts-medium', 600, 400, true);
    add_image_size('mts-large', 1200, 800, true);
    
    add_filter('wp_generate_attachment_metadata', 'mts_add_webp_metadata', 10, 2);
}

/**
 * Add WebP metadata to attachments
 */
function mts_add_webp_metadata($metadata, $attachment_id) {
    $file_path = get_attached_file($attachment_id);
    
    if (!$file_path || !file_exists($file_path)) {
        return $metadata;
    }
    
    $file_info = pathinfo($file_path);
    
    if (isset($metadata['sizes'])) {
        foreach ($metadata['sizes'] as $size => $size_data) {
            $webp_path = $file_info['dirname'] . '/' . $size_data['file'] . '.webp';
            
            if (function_exists('imagewebp') && !file_exists($webp_path)) {
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
                
                if ($image) {
                    $resized_image = imagecreatetruecolor($size_data['width'], $size_data['height']);
                    imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $size_data['width'], $size_data['height'], imagesx($image), imagesy($image));
                    
                    if (imagewebp($resized_image, $webp_path, 80)) {
                        $metadata['sizes'][$size]['webp'] = $webp_path;
                    }
                    
                    imagedestroy($resized_image);
                    imagedestroy($image);
                }
            }
        }
    }
    
    return $metadata;
}

/**
 * Modernize admin interface
 */
function mts_modernize_admin() {
    add_action('admin_enqueue_scripts', 'mts_admin_styles');
    
    add_action('customize_register', 'mts_customize_register');
}

/**
 * Admin styles
 */
function mts_admin_styles($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        wp_enqueue_style('mts-admin-styles', get_template_directory_uri() . '/css/admin.css', array(), '1.0');
    }
}

/**
 * Customizer options
 */
function mts_customize_register($wp_customize) {
    $wp_customize->add_section('mts_performance', array(
        'title' => __('Performance Settings', 'mythemeshop'),
        'priority' => 200,
    ));
    
    $wp_customize->add_setting('mts_lazy_loading', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mts_lazy_loading', array(
        'label' => __('Enable Lazy Loading', 'mythemeshop'),
        'section' => 'mts_performance',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('mts_webp_support', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mts_webp_support', array(
        'label' => __('Enable WebP Support', 'mythemeshop'),
        'section' => 'mts_performance',
        'type' => 'checkbox',
    ));
}

/**
 * Modernize security
 */
function mts_modernize_security() {
    remove_action('wp_head', 'wp_generator');
    
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    add_filter('login_errors', '__return_empty_string');
    
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}

/**
 * Initialize modern WordPress features
 */
function mts_init_modern_wordpress() {
    mts_add_modern_features();
    mts_modernize_navigation();
    mts_enhanced_widget_areas();
    mts_modernize_image_handling();
    mts_modernize_admin();
    mts_modernize_security();
    
    add_filter('document_title_parts', 'mts_modern_document_title');
    add_filter('document_title_separator', 'mts_document_title_separator');
    add_filter('comment_form_defaults', 'mts_modernize_comment_form');
}

add_action('after_setup_theme', 'mts_init_modern_wordpress');
