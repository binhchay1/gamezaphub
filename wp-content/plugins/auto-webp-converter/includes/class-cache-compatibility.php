<?php
/**
 * Cache Compatibility Class
 * Handles compatibility with WP Rocket, Cloudflare, and other caching solutions
 */

if (!defined('ABSPATH')) {
    exit;
}

class AWC_CacheCompatibility {
    
    private $wp_rocket_settings;
    private $is_wp_rocket_active;
    
    public function __construct() {
        $this->is_wp_rocket_active = $this->isWPRocketActive();
        $this->wp_rocket_settings = $this->getWPRocketSettings();
    }
    
    /**
     * Check if WP Rocket is active
     */
    public function isWPRocketActive() {
        return function_exists('rocket_init') || 
               class_exists('WP_Rocket') || 
               defined('WP_ROCKET_VERSION');
    }
    
    /**
     * Get WP Rocket settings
     */
    public function getWPRocketSettings() {
        if (!$this->is_wp_rocket_active) {
            return array();
        }
        
        return get_option('wp_rocket_settings', array());
    }
    
    /**
     * Check if WP Rocket lazy loading is enabled
     */
    public function isWPRocketLazyLoadEnabled() {
        if (!$this->is_wp_rocket_active) {
            return false;
        }
        
        return isset($this->wp_rocket_settings['lazyload']) && $this->wp_rocket_settings['lazyload'];
    }
    
    /**
     * Check if WP Rocket image optimization is enabled
     */
    public function isWPRocketImageOptimizationEnabled() {
        if (!$this->is_wp_rocket_active) {
            return false;
        }
        
        return isset($this->wp_rocket_settings['image_optimization']) && $this->wp_rocket_settings['image_optimization'];
    }
    
    /**
     * Check if Cloudflare is detected
     */
    public function isCloudflareDetected() {
        return isset($_SERVER['HTTP_CF_RAY']) || 
               isset($_SERVER['HTTP_CF_CONNECTING_IP']) ||
               isset($_SERVER['HTTP_CF_VISITOR']);
    }
    
    /**
     * Get Cloudflare settings
     */
    public function getCloudflareSettings() {
        if (!$this->isCloudflareDetected()) {
            return array();
        }
        
        return array(
            'auto_minify' => isset($_SERVER['HTTP_CF_AUTO_MINIFY']),
            'image_optimization' => isset($_SERVER['HTTP_CF_IMAGE_OPTIMIZATION']),
            'cache_level' => $_SERVER['HTTP_CF_CACHE_LEVEL'] ?? 'unknown'
        );
    }
    
    /**
     * Handle WP Rocket compatibility
     */
    public function handleWPRocketCompatibility() {
        if (!$this->is_wp_rocket_active) {
            return;
        }
        

        add_filter('rocket_disable_image_optimization', '__return_true');
        

        if ($this->isWPRocketLazyLoadEnabled()) {
            add_filter('rocket_lazyload_excluded_attributes', array($this, 'excludePictureTagsFromLazyLoad'));
        }
        

        add_action('awc_image_converted', array($this, 'clearWPRocketCache'));
    }
    
    /**
     * Exclude picture tags from WP Rocket lazy loading
     */
    public function excludePictureTagsFromLazyLoad($excluded_attributes) {
        $excluded_attributes[] = 'data-picture';
        return $excluded_attributes;
    }
    
    /**
     * Clear WP Rocket cache
     */
    public function clearWPRocketCache() {
        if (!$this->is_wp_rocket_active) {
            return;
        }
        
        if (function_exists('rocket_clean_domain')) {
            rocket_clean_domain();
        }
    }
    
    /**
     * Handle Cloudflare compatibility
     */
    public function handleCloudflareCompatibility() {
        if (!$this->isCloudflareDetected()) {
            return;
        }
        

        add_action('wp_head', array($this, 'addWebPSupportHeaders'));
        

        add_filter('awc_webp_url', array($this, 'handleCloudflareWebP'));
    }
    
    /**
     * Add WebP support headers
     */
    public function addWebPSupportHeaders() {
        if (!headers_sent()) {
            header('Vary: Accept');
        }
    }
    
    /**
     * Handle Cloudflare WebP URLs
     */
    public function handleCloudflareWebP($webp_url) {
        if (!$this->isCloudflareDetected()) {
            return $webp_url;
        }
        

        $cloudflare_settings = $this->getCloudflareSettings();
        if ($cloudflare_settings['image_optimization']) {

            return str_replace('.webp', '', $webp_url);
        }
        
        return $webp_url;
    }
    
    /**
     * Get cache compatibility status
     */
    public function getCompatibilityStatus() {
        $status = array(
            'wp_rocket' => array(
                'active' => $this->is_wp_rocket_active,
                'lazy_load' => $this->isWPRocketLazyLoadEnabled(),
                'image_optimization' => $this->isWPRocketImageOptimizationEnabled()
            ),
            'cloudflare' => array(
                'detected' => $this->isCloudflareDetected(),
                'settings' => $this->getCloudflareSettings()
            ),
            'recommendations' => array()
        );
        

        if ($this->is_wp_rocket_active && $this->isWPRocketImageOptimizationEnabled()) {
            $status['recommendations'][] = 'Consider disabling WP Rocket image optimization to avoid conflicts with WebP conversion.';
        }
        
        if ($this->isCloudflareDetected()) {
            $status['recommendations'][] = 'Cloudflare detected. Ensure image optimization settings are compatible.';
        }
        
        return $status;
    }
    
    /**
     * Optimize for cache compatibility
     */
    public function optimizeForCache() {

        $this->handleWPRocketCompatibility();
        

        $this->handleCloudflareCompatibility();
        

        add_filter('awc_webp_url', array($this, 'addCacheBusting'));
    }
    
    /**
     * Add cache busting parameter
     */
    public function addCacheBusting($url) {
        $version = get_option('awc_conversion_version', '1.0');
        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . 'v=' . $version;
    }
    
    /**
     * Update conversion version for cache busting
     */
    public function updateConversionVersion() {
        $version = time();
        update_option('awc_conversion_version', $version);
    }
    
    /**
     * Get optimal settings for cache compatibility
     */
    public function getOptimalSettings() {
        $settings = array(
            'enable_picture_tag' => true,
            'lazy_load_compatible' => true,
            'cache_busting' => true
        );
        
        if ($this->is_wp_rocket_active) {
            $settings['wp_rocket_compatible'] = true;
            $settings['disable_wp_rocket_image_opt'] = $this->isWPRocketImageOptimizationEnabled();
        }
        
        if ($this->isCloudflareDetected()) {
            $settings['cloudflare_compatible'] = true;
            $settings['use_cloudflare_webp'] = $this->getCloudflareSettings()['image_optimization'] ?? false;
        }
        
        return $settings;
    }
    
    /**
     * Check for potential conflicts
     */
    public function checkConflicts() {
        $conflicts = array();
        

        $webp_plugins = array(
            'webp-express/webp-express.php',
            'webp-converter-for-media/webp-converter-for-media.php',
            'shortpixel-image-optimiser/wp-shortpixel.php'
        );
        
        foreach ($webp_plugins as $plugin) {
            if (is_plugin_active($plugin)) {
                $conflicts[] = 'Another WebP plugin is active: ' . $plugin;
            }
        }
        

        if ($this->is_wp_rocket_active && $this->isWPRocketImageOptimizationEnabled()) {
            $conflicts[] = 'WP Rocket image optimization may conflict with WebP conversion';
        }
        
        return $conflicts;
    }
    
    /**
     * Get performance recommendations
     */
    public function getPerformanceRecommendations() {
        $recommendations = array();
        
        if ($this->is_wp_rocket_active) {
            $recommendations[] = 'WP Rocket is active - ensure lazy loading is properly configured';
        }
        
        if ($this->isCloudflareDetected()) {
            $recommendations[] = 'Cloudflare detected - consider using their image optimization features';
        }
        
        $recommendations[] = 'Use appropriate batch sizes to avoid server overload';
        $recommendations[] = 'Monitor server resources during batch processing';
        
        return $recommendations;
    }
}
