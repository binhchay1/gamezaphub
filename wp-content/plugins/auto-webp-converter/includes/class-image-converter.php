<?php
/**
 * Image Converter Class
 * Handles conversion of images to WebP and JPG formats
 */

if (!defined('ABSPATH')) {
    exit;
}

class AWC_ImageConverter {
    
    private $quality;
    private $excluded_dirs;
    
    public function __construct() {
        $options = get_option('awc_settings', array());
        $this->quality = isset($options['quality']) ? $options['quality'] : 85;
        $this->excluded_dirs = isset($options['exclude_dirs']) ? $options['exclude_dirs'] : array('plugins', 'languages', 'upgrade', 'cache');
    }
    
    /**
     * Convert image to WebP format
     */
    public function convertToWebP($source_path, $quality = null) {
        if (!file_exists($source_path)) {
            return false;
        }
        
        $quality = $quality ?: $this->quality;
        $file_info = pathinfo($source_path);
        $webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
        
        if (file_exists($webp_path)) {
            return $webp_path;
        }
        
        if (!$this->isWebPSupported()) {
            return false;
        }
        
        $image = $this->createImageResource($source_path);
        if (!$image) {
            return false;
        }
        
        $result = false;
        

        switch (strtolower($file_info['extension'])) {
            case 'jpg':
            case 'jpeg':
                $result = imagewebp($image, $webp_path, $quality);
                break;
            case 'png':

                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                $result = imagewebp($image, $webp_path, $quality);
                break;
            case 'gif':

                $result = imagewebp($image, $webp_path, $quality);
                break;
            case 'webp':

                $result = copy($source_path, $webp_path);
                break;
        }
        
        imagedestroy($image);
        
        if ($result && file_exists($webp_path)) {

            chmod($webp_path, 0644);
            return $webp_path;
        }
        
        return false;
    }
    
    /**
     * Convert image to JPG format
     */
    public function convertToJPG($source_path, $quality = null) {
        if (!file_exists($source_path)) {
            return false;
        }
        
        $quality = $quality ?: $this->quality;
        $file_info = pathinfo($source_path);
        $jpg_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.jpg';
        

        if (file_exists($jpg_path)) {
            return $jpg_path;
        }
        
        $image = $this->createImageResource($source_path);
        if (!$image) {
            return false;
        }
        
        $result = false;
        

        switch (strtolower($file_info['extension'])) {
            case 'png':

                $jpg_image = imagecreatetruecolor(imagesx($image), imagesy($image));
                $white = imagecolorallocate($jpg_image, 255, 255, 255);
                imagefill($jpg_image, 0, 0, $white);
                imagecopy($jpg_image, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                $result = imagejpeg($jpg_image, $jpg_path, $quality);
                imagedestroy($jpg_image);
                break;
            case 'gif':

                $result = imagejpeg($image, $jpg_path, $quality);
                break;
            case 'webp':

                $result = imagejpeg($image, $jpg_path, $quality);
                break;
            case 'jpg':
            case 'jpeg':

                $result = copy($source_path, $jpg_path);
                break;
        }
        
        imagedestroy($image);
        
        if ($result && file_exists($jpg_path)) {

            chmod($jpg_path, 0644);
            return $jpg_path;
        }
        
        return false;
    }
    
    /**
     * Create image resource from file
     */
    private function createImageResource($file_path) {
        $file_info = pathinfo($file_path);
        $extension = strtolower($file_info['extension']);
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($file_path);
            case 'png':
                return imagecreatefrompng($file_path);
            case 'gif':
                return imagecreatefromgif($file_path);
            case 'webp':
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($file_path);
                }
                return false;
            default:
                return false;
        }
    }
    
    /**
     * Check if WebP is supported by the server
     */
    private function isWebPSupported() {
        return function_exists('imagewebp') && function_exists('imagecreatefromwebp');
    }
    
    /**
     * Get image dimensions
     */
    public function getImageDimensions($file_path) {
        if (!file_exists($file_path)) {
            return false;
        }
        
        $image_info = getimagesize($file_path);
        if ($image_info) {
            return array(
                'width' => $image_info[0],
                'height' => $image_info[1],
                'mime' => $image_info['mime']
            );
        }
        
        return false;
    }
    
    /**
     * Check if file is an image
     */
    public function isImage($file_path) {
        if (!file_exists($file_path)) {
            return false;
        }
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $file_info = pathinfo($file_path);
        
        if (!isset($file_info['extension'])) {
            return false;
        }
        
        return in_array(strtolower($file_info['extension']), $allowed_types);
    }
    
    /**
     * Get file size in human readable format
     */
    public function getFileSize($file_path) {
        if (!file_exists($file_path)) {
            return '0 B';
        }
        
        $bytes = filesize($file_path);
        $units = array('B', 'KB', 'MB', 'GB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Optimize image quality based on file size
     */
    public function optimizeQuality($source_path, $target_size_kb = 100) {
        $current_size = filesize($source_path) / 1024; // KB
        
        if ($current_size <= $target_size_kb) {
            return $this->quality;
        }
        

        $ratio = $target_size_kb / $current_size;
        $new_quality = max(60, min(95, $this->quality * $ratio));
        
        return round($new_quality);
    }
    
    /**
     * Batch convert multiple images
     */
    public function batchConvert($file_paths, $format = 'webp') {
        $results = array();
        $converted = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($file_paths as $file_path) {
            if (!$this->isImage($file_path)) {
                $skipped++;
                continue;
            }
            
            $result = false;
            if ($format === 'webp') {
                $result = $this->convertToWebP($file_path);
            } elseif ($format === 'jpg') {
                $result = $this->convertToJPG($file_path);
            }
            
            if ($result) {
                $converted++;
                $results[] = array(
                    'source' => $file_path,
                    'converted' => $result,
                    'status' => 'success'
                );
            } else {
                $errors++;
                $results[] = array(
                    'source' => $file_path,
                    'converted' => false,
                    'status' => 'error'
                );
            }
        }
        
        return array(
            'results' => $results,
            'stats' => array(
                'converted' => $converted,
                'skipped' => $skipped,
                'errors' => $errors,
                'total' => count($file_paths)
            )
        );
    }
}
