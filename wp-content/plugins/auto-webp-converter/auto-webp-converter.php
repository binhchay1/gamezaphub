<?php
/**
 * Plugin Name: Auto WebP Converter
 * Plugin URI: https://gamezaphub.com
 * Description: Tự động chuyển đổi ảnh sang WebP và JPG, bọc ảnh trong thẻ picture với fallback. Tối ưu cho WP Rocket và Cloudflare.
 * Version: 1.0.0
 * Author: GameZapHub
 * License: GPL v2 or later
 * Text Domain: auto-webp-converter
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AWC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AWC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AWC_VERSION', '1.0.0');

class AutoWebPConverter {
    
    private static $instance = null;
    private $options;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->options = get_option('awc_settings', array());
        $this->init();
    }
    
    private function init() {
        $this->loadFiles();
        
        $this->initHooks();
        
        if (is_admin()) {
            $this->initAdmin();
        }
        
        $this->initCacheCompatibility();
    }
    
    private function loadFiles() {
        require_once AWC_PLUGIN_PATH . 'includes/class-image-converter.php';
        require_once AWC_PLUGIN_PATH . 'includes/class-picture-tag.php';
        require_once AWC_PLUGIN_PATH . 'includes/class-batch-processor.php';
        require_once AWC_PLUGIN_PATH . 'includes/class-admin.php';
        require_once AWC_PLUGIN_PATH . 'includes/class-cache-compatibility.php';
    }
    
    private function initHooks() {
        add_action('wp_handle_upload', array($this, 'handleImageUpload'), 10, 2);
        add_filter('wp_generate_attachment_metadata', array($this, 'generateWebPVersions'), 10, 2);
        
        add_filter('the_content', array($this, 'wrapImagesInPicture'), 20);
        add_filter('post_thumbnail_html', array($this, 'wrapImagesInPicture'), 20);
        
        add_action('wp_ajax_awc_batch_convert', array($this, 'ajaxBatchConvert'));
        add_action('wp_ajax_awc_get_progress', array($this, 'ajaxGetProgress'));
        add_action('wp_ajax_awc_reset_batch', array($this, 'ajaxResetBatch'));
        add_action('wp_ajax_awc_check_conflicts', array($this, 'ajaxCheckConflicts'));
        add_action('wp_ajax_awc_get_log', array($this, 'ajaxGetLog'));
        
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    private function initAdmin() {
        new AWC_Admin();
    }
    
    private function initCacheCompatibility() {
        $cache_compatibility = new AWC_CacheCompatibility();
        $cache_compatibility->optimizeForCache();
    }
    
    /**
     * Handle image upload - convert to WebP and JPG
     */
    public function handleImageUpload($upload, $context) {
        if (!isset($upload['file']) || !file_exists($upload['file'])) {
            return $upload;
        }
        
        $file_path = $upload['file'];
        $file_type = wp_check_filetype($file_path);
        
        if (!in_array($file_type['type'], array('image/jpeg', 'image/png', 'image/gif', 'image/webp'))) {
            return $upload;
        }
        
        $converter = new AWC_ImageConverter();
        
        $webp_path = $converter->convertToWebP($file_path);
        if ($webp_path) {
            $upload['webp_file'] = $webp_path;
        }
        
        if (!in_array($file_type['type'], array('image/jpeg', 'image/png'))) {
            $jpg_path = $converter->convertToJPG($file_path);
            if ($jpg_path) {
                $upload['jpg_file'] = $jpg_path;
            }
        }
        
        return $upload;
    }
    
    /**
     * Generate WebP versions for attachment metadata
     */
    public function generateWebPVersions($metadata, $attachment_id) {
        if (!isset($metadata['file'])) {
            return $metadata;
        }
        
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['basedir'] . '/' . $metadata['file'];
        
        if (!file_exists($file_path)) {
            return $metadata;
        }
        
        $converter = new AWC_ImageConverter();
        
        $webp_path = $converter->convertToWebP($file_path);
        if ($webp_path) {
            $metadata['webp_file'] = str_replace($upload_dir['basedir'] . '/', '', $webp_path);
        }
        
        if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size => $size_data) {
                $thumb_path = $upload_dir['basedir'] . '/' . dirname($metadata['file']) . '/' . $size_data['file'];
                if (file_exists($thumb_path)) {
                    $thumb_webp = $converter->convertToWebP($thumb_path);
                    if ($thumb_webp) {
                        $metadata['sizes'][$size]['webp_file'] = basename($thumb_webp);
                    }
                }
            }
        }
        
        return $metadata;
    }
    
    /**
     * Wrap images in picture tags
     */
    public function wrapImagesInPicture($content) {
        if (empty($content) || is_admin()) {
            return $content;
        }
        
        $picture_tag = new AWC_PictureTag();
        return $picture_tag->wrapImages($content);
    }
    
    /**
     * AJAX handler for batch conversion
     */
    public function ajaxBatchConvert() {
        check_ajax_referer('awc_batch_convert', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        try {
            $processor = new AWC_BatchProcessor();
            $result = $processor->processBatch();
            
            $result['debug'] = array(
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
                'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
            );
            
            wp_send_json_success($result);
        } catch (Exception $e) {
            wp_send_json_error('Error processing batch: ' . $e->getMessage());
        }
    }
    
    /**
     * AJAX handler for getting progress
     */
    public function ajaxGetProgress() {
        check_ajax_referer('awc_batch_convert', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        try {
            $processor = new AWC_BatchProcessor();
            $progress = $processor->getProgress();
            
            wp_send_json_success($progress);
        } catch (Exception $e) {
            wp_send_json_error('Error getting progress: ' . $e->getMessage());
        }
    }
    
    /**
     * AJAX handler for resetting batch
     */
    public function ajaxResetBatch() {
        check_ajax_referer('awc_batch_convert', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $processor = new AWC_BatchProcessor();
        $processor->resetBatch();
        
        wp_send_json_success('Batch processing reset successfully');
    }
    
    /**
     * AJAX handler for checking conflicts
     */
    public function ajaxCheckConflicts() {
        check_ajax_referer('awc_batch_convert', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $cache_compatibility = new AWC_CacheCompatibility();
        $conflicts = $cache_compatibility->checkConflicts();
        
        wp_send_json_success(array('conflicts' => $conflicts));
    }
    
    /**
     * AJAX handler for getting log
     */
    public function ajaxGetLog() {
        check_ajax_referer('awc_batch_convert', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        try {
            $processor = new AWC_BatchProcessor();
            $log = $processor->getLog(100);
            
            wp_send_json_success(array('log' => $log));
        } catch (Exception $e) {
            wp_send_json_error('Error getting log: ' . $e->getMessage());
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        $default_options = array(
            'auto_convert' => true,
            'quality' => 85,
            'exclude_dirs' => array('plugins', 'languages', 'upgrade', 'cache'),
            'batch_size' => 10,
            'enable_picture_tag' => true,
            'lazy_load_compatible' => true
        );
        
        add_option('awc_settings', $default_options);
        
        $upload_dir = wp_upload_dir();
        $webp_dir = $upload_dir['basedir'] . '/webp-converted';
        if (!file_exists($webp_dir)) {
            wp_mkdir_p($webp_dir);
        }
        
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        wp_clear_scheduled_hook('awc_batch_convert');
        
        flush_rewrite_rules();
    }
    
    /**
     * Get plugin options
     */
    public function getOption($key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }
    
    /**
     * Update plugin options
     */
    public function updateOption($key, $value) {
        $this->options[$key] = $value;
        update_option('awc_settings', $this->options);
    }
}

function awc_init() {
    return AutoWebPConverter::getInstance();
}

add_action('plugins_loaded', 'awc_init');

function awc_get_webp_url($image_url) {
    $upload_dir = wp_upload_dir();
    $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);
    $webp_path = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $image_path);
    
    if (file_exists($webp_path)) {
        return str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $webp_path);
    }
    
    return false;
}

function awc_supports_webp() {
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        return strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    }
    return false;
}

function awc_wrap_single_image($image_url, $attributes = array()) {
    $picture_tag = new AWC_PictureTag();
    return $picture_tag->wrapSingleImage($image_url, $attributes);
}

function awc_get_responsive_sources($image_id) {
    $picture_tag = new AWC_PictureTag();
    return $picture_tag->getResponsiveSources($image_id);
}

function awc_clear_cache() {
    $cache_compatibility = new AWC_CacheCompatibility();
    $cache_compatibility->clearWPRocketCache();
    $cache_compatibility->updateConversionVersion();
}
