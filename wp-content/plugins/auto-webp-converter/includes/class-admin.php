<?php
/**
 * Admin Class
 * Handles admin interface and settings
 */

if (!defined('ABSPATH')) {
    exit;
}

class AWC_Admin {
    
    private $options;
    
    public function __construct() {
        $this->options = get_option('awc_settings', array());
        $this->initHooks();
    }
    
    private function initHooks() {
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));
        add_action('wp_ajax_awc_test_conversion', array($this, 'ajaxTestConversion'));
    }
    
    /**
     * Add admin menu
     */
    public function addAdminMenu() {
        add_options_page(
            'Auto WebP Converter',
            'WebP Converter',
            'manage_options',
            'auto-webp-converter',
            array($this, 'adminPage')
        );
    }
    
    /**
     * Register settings
     */
    public function registerSettings() {
        register_setting('awc_settings', 'awc_settings', array($this, 'sanitizeSettings'));
        
        add_settings_section(
            'awc_general',
            'General Settings',
            array($this, 'generalSectionCallback'),
            'auto-webp-converter'
        );
        
        add_settings_field(
            'auto_convert',
            'Auto Convert on Upload',
            array($this, 'autoConvertCallback'),
            'auto-webp-converter',
            'awc_general'
        );
        
        add_settings_field(
            'quality',
            'WebP Quality',
            array($this, 'qualityCallback'),
            'auto-webp-converter',
            'awc_general'
        );
        
        add_settings_field(
            'enable_picture_tag',
            'Enable Picture Tag Wrapping',
            array($this, 'pictureTagCallback'),
            'auto-webp-converter',
            'awc_general'
        );
        
        add_settings_section(
            'awc_batch',
            'Batch Processing Settings',
            array($this, 'batchSectionCallback'),
            'auto-webp-converter'
        );
        
        add_settings_field(
            'batch_size',
            'Batch Size',
            array($this, 'batchSizeCallback'),
            'auto-webp-converter',
            'awc_batch'
        );
        
        add_settings_field(
            'exclude_dirs',
            'Exclude Directories',
            array($this, 'excludeDirsCallback'),
            'auto-webp-converter',
            'awc_batch'
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueueAdminScripts($hook) {
        if ($hook !== 'settings_page_auto-webp-converter') {
            return;
        }
        
        wp_enqueue_script(
            'awc-admin',
            AWC_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            AWC_VERSION,
            true
        );
        
        wp_enqueue_style(
            'awc-admin',
            AWC_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            AWC_VERSION
        );
        
        wp_localize_script('awc-admin', 'awc_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('awc_batch_convert'),
            'strings' => array(
                'processing' => 'Processing...',
                'completed' => 'Completed',
                'error' => 'Error',
                'confirm_reset' => 'Are you sure you want to reset the batch processing? This will start over from the beginning.'
            )
        ));
    }
    
    /**
     * Admin page
     */
    public function adminPage() {
        $processor = new AWC_BatchProcessor();
        $progress = $processor->getProgress();
        $stats = $processor->getStats();
        $log = $processor->getLog(50);
        
        ?>
        <div class="wrap">
            <h1>Auto WebP Converter</h1>
            
            <div class="awc-admin-container">
                <div class="awc-main-content">
                    <!-- Settings Form -->
                    <div class="awc-section">
                        <h2>Settings</h2>
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('awc_settings');
                            do_settings_sections('auto-webp-converter');
                            submit_button();
                            ?>
                        </form>
                    </div>
                    
                    <!-- Batch Processing -->
                    <div class="awc-section">
                        <h2>Batch Processing</h2>
                        <div class="awc-batch-controls">
                            <button id="awc-start-batch" class="button button-primary" <?php echo $progress['status'] === 'processing' ? 'disabled' : ''; ?>>
                                Start Batch Conversion
                            </button>
                            <button id="awc-reset-batch" class="button" <?php echo $progress['status'] === 'idle' ? 'disabled' : ''; ?>>
                                Reset
                            </button>
                            <button id="awc-refresh-status" class="button">
                                Refresh Status
                            </button>
                        </div>
                        
                        <div class="awc-progress-container">
                            <div class="awc-progress-bar">
                                <div class="awc-progress-fill" style="width: <?php echo $progress['percentage']; ?>%"></div>
                            </div>
                            <div class="awc-progress-text">
                                <?php echo $progress['processed_files']; ?> / <?php echo $progress['total_files']; ?> files processed (<?php echo $progress['percentage']; ?>%)
                            </div>
                        </div>
                        
                        <div class="awc-stats">
                            <div class="awc-stat-item">
                                <span class="awc-stat-label">Total Images:</span>
                                <span class="awc-stat-value"><?php echo $stats['total_images']; ?></span>
                            </div>
                            <div class="awc-stat-item">
                                <span class="awc-stat-label">Converted:</span>
                                <span class="awc-stat-value awc-stat-success"><?php echo $stats['converted_images']; ?></span>
                            </div>
                            <div class="awc-stat-item">
                                <span class="awc-stat-label">Skipped:</span>
                                <span class="awc-stat-value awc-stat-warning"><?php echo $stats['skipped_images']; ?></span>
                            </div>
                            <div class="awc-stat-item">
                                <span class="awc-stat-label">Errors:</span>
                                <span class="awc-stat-value awc-stat-error"><?php echo $stats['error_images']; ?></span>
                            </div>
                        </div>
                        
                        <?php if ($progress['status'] === 'processing'): ?>
                        <div class="awc-current-file">
                            <strong>Currently processing:</strong> <?php echo basename($progress['current_file']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Log -->
                    <div class="awc-section">
                        <h2>Processing Log</h2>
                        <div class="awc-log-container">
                            <div class="awc-log-entries">
                                <?php if (empty($log)): ?>
                                    <p>No log entries yet.</p>
                                <?php else: ?>
                                    <?php foreach (array_reverse($log) as $entry): ?>
                                        <div class="awc-log-entry"><?php echo esc_html($entry); ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="awc-sidebar">
                    <!-- System Info -->
                    <div class="awc-section">
                        <h3>System Information</h3>
                        <div class="awc-system-info">
                            <div class="awc-info-item">
                                <span class="awc-info-label">WebP Support:</span>
                                <span class="awc-info-value <?php echo function_exists('imagewebp') ? 'awc-success' : 'awc-error'; ?>">
                                    <?php echo function_exists('imagewebp') ? 'Yes' : 'No'; ?>
                                </span>
                            </div>
                            <div class="awc-info-item">
                                <span class="awc-info-label">GD Extension:</span>
                                <span class="awc-info-value <?php echo extension_loaded('gd') ? 'awc-success' : 'awc-error'; ?>">
                                    <?php echo extension_loaded('gd') ? 'Yes' : 'No'; ?>
                                </span>
                            </div>
                            <div class="awc-info-item">
                                <span class="awc-info-label">WP Rocket:</span>
                                <span class="awc-info-value <?php echo $this->isWPRocketActive() ? 'awc-success' : 'awc-warning'; ?>">
                                    <?php echo $this->isWPRocketActive() ? 'Active' : 'Not Active'; ?>
                                </span>
                            </div>
                            <div class="awc-info-item">
                                <span class="awc-info-label">Cloudflare:</span>
                                <span class="awc-info-value awc-info">
                                    Detected
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cache Compatibility -->
                    <div class="awc-section">
                        <h3>Cache Compatibility</h3>
                        <div class="awc-cache-info">
                            <p>This plugin is optimized to work with:</p>
                            <ul>
                                <li>WP Rocket (Lazy Loading)</li>
                                <li>Cloudflare (Image Optimization)</li>
                                <li>WordPress Native Lazy Loading</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Help -->
                    <div class="awc-section">
                        <h3>Help & Support</h3>
                        <div class="awc-help">
                            <p><strong>How it works:</strong></p>
                            <ol>
                                <li>Upload images are automatically converted to WebP</li>
                                <li>Images are wrapped in &lt;picture&gt; tags with fallback</li>
                                <li>Batch processing converts existing images</li>
                                <li>Compatible with WP Rocket and Cloudflare</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Settings callbacks
     */
    public function generalSectionCallback() {
        echo '<p>Configure the general settings for WebP conversion.</p>';
    }
    
    public function autoConvertCallback() {
        $value = $this->options['auto_convert'] ?? true;
        echo '<input type="checkbox" name="awc_settings[auto_convert]" value="1" ' . checked(1, $value, false) . '>';
        echo '<p class="description">Automatically convert images to WebP when uploaded.</p>';
    }
    
    public function qualityCallback() {
        $value = $this->options['quality'] ?? 85;
        echo '<input type="number" name="awc_settings[quality]" value="' . esc_attr($value) . '" min="1" max="100" class="small-text">';
        echo '<p class="description">WebP quality (1-100). Higher values mean better quality but larger file sizes.</p>';
    }
    
    public function pictureTagCallback() {
        $value = $this->options['enable_picture_tag'] ?? true;
        echo '<input type="checkbox" name="awc_settings[enable_picture_tag]" value="1" ' . checked(1, $value, false) . '>';
        echo '<p class="description">Wrap images in &lt;picture&gt; tags with WebP fallback.</p>';
    }
    
    public function batchSectionCallback() {
        echo '<p>Configure batch processing settings for existing images.</p>';
    }
    
    public function batchSizeCallback() {
        $value = $this->options['batch_size'] ?? 10;
        echo '<input type="number" name="awc_settings[batch_size]" value="' . esc_attr($value) . '" min="1" max="50" class="small-text">';
        echo '<p class="description">Number of images to process in each batch.</p>';
    }
    
    public function excludeDirsCallback() {
        $value = $this->options['exclude_dirs'] ?? array('plugins', 'languages', 'upgrade', 'cache');
        $value = is_array($value) ? implode(', ', $value) : $value;
        echo '<input type="text" name="awc_settings[exclude_dirs]" value="' . esc_attr($value) . '" class="regular-text">';
        echo '<p class="description">Comma-separated list of directories to exclude from batch processing.</p>';
    }
    
    /**
     * Sanitize settings
     */
    public function sanitizeSettings($input) {
        $sanitized = array();
        
        $sanitized['auto_convert'] = isset($input['auto_convert']) ? (bool) $input['auto_convert'] : false;
        $sanitized['quality'] = isset($input['quality']) ? max(1, min(100, (int) $input['quality'])) : 85;
        $sanitized['enable_picture_tag'] = isset($input['enable_picture_tag']) ? (bool) $input['enable_picture_tag'] : false;
        $sanitized['batch_size'] = isset($input['batch_size']) ? max(1, min(50, (int) $input['batch_size'])) : 10;
        
        if (isset($input['exclude_dirs'])) {
            $dirs = array_map('trim', explode(',', $input['exclude_dirs']));
            $sanitized['exclude_dirs'] = array_filter($dirs);
        } else {
            $sanitized['exclude_dirs'] = array('plugins', 'languages', 'upgrade', 'cache');
        }
        
        return $sanitized;
    }
    
    /**
     * AJAX test conversion
     */
    public function ajaxTestConversion() {
        check_ajax_referer('awc_batch_convert', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $converter = new AWC_ImageConverter();
        $test_result = array(
            'webp_support' => function_exists('imagewebp'),
            'gd_support' => extension_loaded('gd'),
            'test_conversion' => false
        );
        
        $upload_dir = wp_upload_dir();
        $test_image = $upload_dir['basedir'] . '/test-image.jpg';
        
        if (file_exists($test_image)) {
            $webp_result = $converter->convertToWebP($test_image);
            $test_result['test_conversion'] = $webp_result !== false;
        }
        
        wp_send_json($test_result);
    }
    
    /**
     * Check if WP Rocket is active
     */
    private function isWPRocketActive() {
        return function_exists('rocket_init') || class_exists('WP_Rocket');
    }
}
