<?php
/**
 * Picture Tag Class
 * Handles wrapping images in picture tags with WebP fallback
 */

if (!defined('ABSPATH')) {
    exit;
}

class AWC_PictureTag {
    
    private $options;
    private $cache_compatibility;
    
    public function __construct() {
        $this->options = get_option('awc_settings', array());
        $this->cache_compatibility = new AWC_CacheCompatibility();
    }
    
    /**
     * Wrap images in picture tags
     */
    public function wrapImages($content) {
        if (empty($content) || !$this->shouldWrapImages()) {
            return $content;
        }
        

        $pattern = '/<img([^>]*?)src=["\']([^"\']*?)["\']([^>]*?)>/i';
        
        return preg_replace_callback($pattern, array($this, 'replaceImageTag'), $content);
    }
    
    /**
     * Replace individual image tag with picture tag
     */
    private function replaceImageTag($matches) {
        $before_src = $matches[1];
        $src = $matches[2];
        $after_src = $matches[3];
        

        if ($this->isAlreadyWrapped($src)) {
            return $matches[0];
        }
        

        if ($this->isExternalImage($src)) {
            return $matches[0];
        }
        

        if (!$this->isSupportedImage($src)) {
            return $matches[0];
        }
        

        $webp_src = $this->getWebPVersion($src);
        if (!$webp_src) {
            return $matches[0];
        }
        

        $attributes = $this->extractAttributes($before_src . $after_src);
        

        return $this->buildPictureTag($src, $webp_src, $attributes);
    }
    
    /**
     * Check if images should be wrapped
     */
    private function shouldWrapImages() {

        if (is_admin()) {
            return false;
        }
        

        if (!($this->options['enable_picture_tag'] ?? true)) {
            return false;
        }
        

        if (!$this->supportsWebP()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if image is already wrapped in picture tag
     */
    private function isAlreadyWrapped($src) {


        return false;
    }
    
    /**
     * Check if image is external
     */
    private function isExternalImage($src) {
        $site_url = get_site_url();
        return strpos($src, $site_url) === false && strpos($src, 'http') === 0;
    }
    
    /**
     * Check if image format is supported
     */
    private function isSupportedImage($src) {
        $supported_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $extension = strtolower(pathinfo($src, PATHINFO_EXTENSION));
        return in_array($extension, $supported_extensions);
    }
    
    /**
     * Get WebP version of image
     */
    private function getWebPVersion($src) {
        $upload_dir = wp_upload_dir();
        $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $src);
        

        if (!file_exists($image_path)) {
            return false;
        }
        
        $webp_path = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $image_path);
        

        if (file_exists($webp_path)) {
            return str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $webp_path);
        }
        

        $converter = new AWC_ImageConverter();
        $converted_path = $converter->convertToWebP($image_path);
        
        if ($converted_path && file_exists($converted_path)) {
            return str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $converted_path);
        }
        
        return false;
    }
    
    /**
     * Extract attributes from img tag
     */
    private function extractAttributes($attributes_string) {
        $attributes = array();
        

        $common_attrs = array('alt', 'title', 'class', 'id', 'width', 'height', 'loading', 'sizes', 'srcset');
        
        foreach ($common_attrs as $attr) {
            if (preg_match('/' . $attr . '=["\']([^"\']*?)["\']/', $attributes_string, $matches)) {
                $attributes[$attr] = $matches[1];
            }
        }
        
        return $attributes;
    }
    
    /**
     * Build picture tag with WebP source and fallback
     */
    private function buildPictureTag($original_src, $webp_src, $attributes) {
        $picture_attrs = '';
        $img_attrs = '';
        

        if (isset($attributes['class'])) {
            $picture_attrs .= ' class="' . esc_attr($attributes['class']) . '"';
        }
        

        foreach ($attributes as $key => $value) {
            if ($key !== 'class') {
                $img_attrs .= ' ' . $key . '="' . esc_attr($value) . '"';
            }
        }
        

        $loading_attr = $this->handleLazyLoading($attributes);
        

        $picture_tag = '<picture' . $picture_attrs . '>';
        $picture_tag .= '<source srcset="' . esc_url($webp_src) . '" type="image/webp">';
        $picture_tag .= '<img src="' . esc_url($original_src) . '"' . $img_attrs . $loading_attr . '>';
        $picture_tag .= '</picture>';
        
        return $picture_tag;
    }
    
    /**
     * Handle lazy loading compatibility
     */
    private function handleLazyLoading($attributes) {
        $loading_attr = '';
        

        if ($this->cache_compatibility->isWPRocketLazyLoadEnabled()) {

            if (isset($attributes['loading']) && $attributes['loading'] === 'lazy') {
                $loading_attr = ' loading="lazy"';
            }
        }
        

        if (isset($attributes['data-src'])) {
            $loading_attr = ' data-src="' . esc_attr($attributes['data-src']) . '"';
        }
        
        return $loading_attr;
    }
    
    /**
     * Check if browser supports WebP
     */
    private function supportsWebP() {
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            return strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
        }
        return true; // Default to true for server-side rendering
    }
    
    /**
     * Wrap specific image with picture tag
     */
    public function wrapSingleImage($image_url, $attributes = array()) {
        if (!$this->shouldWrapImages()) {
            return '<img src="' . esc_url($image_url) . '"' . $this->buildAttributes($attributes) . '>';
        }
        
        $webp_url = $this->getWebPVersion($image_url);
        if (!$webp_url) {
            return '<img src="' . esc_url($image_url) . '"' . $this->buildAttributes($attributes) . '>';
        }
        
        $picture_attrs = '';
        $img_attrs = $this->buildAttributes($attributes);
        
        if (isset($attributes['class'])) {
            $picture_attrs = ' class="' . esc_attr($attributes['class']) . '"';
        }
        
        $picture_tag = '<picture' . $picture_attrs . '>';
        $picture_tag .= '<source srcset="' . esc_url($webp_url) . '" type="image/webp">';
        $picture_tag .= '<img src="' . esc_url($image_url) . '"' . $img_attrs . '>';
        $picture_tag .= '</picture>';
        
        return $picture_tag;
    }
    
    /**
     * Build attributes string
     */
    private function buildAttributes($attributes) {
        $attr_string = '';
        foreach ($attributes as $key => $value) {
            if ($key !== 'class') {
                $attr_string .= ' ' . $key . '="' . esc_attr($value) . '"';
            }
        }
        return $attr_string;
    }
    
    /**
     * Get responsive image sources
     */
    public function getResponsiveSources($image_id) {
        $sources = array();
        
        if (!$image_id) {
            return $sources;
        }
        
        $metadata = wp_get_attachment_metadata($image_id);
        if (!$metadata) {
            return $sources;
        }
        
        $upload_dir = wp_upload_dir();
        $base_url = $upload_dir['baseurl'] . '/' . dirname($metadata['file']);
        

        $main_file = basename($metadata['file']);
        $main_webp = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $main_file);
        
        $sources[] = array(
            'url' => $base_url . '/' . $main_file,
            'webp_url' => $base_url . '/' . $main_webp,
            'width' => $metadata['width'] ?? 0,
            'height' => $metadata['height'] ?? 0
        );
        

        if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size => $size_data) {
                $thumb_webp = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $size_data['file']);
                
                $sources[] = array(
                    'url' => $base_url . '/' . $size_data['file'],
                    'webp_url' => $base_url . '/' . $thumb_webp,
                    'width' => $size_data['width'],
                    'height' => $size_data['height'],
                    'size' => $size
                );
            }
        }
        
        return $sources;
    }
}
