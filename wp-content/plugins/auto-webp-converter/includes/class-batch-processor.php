<?php
/**
 * Batch Processor Class
 * Handles batch conversion of existing images
 */

if (!defined('ABSPATH')) {
    exit;
}

class AWC_BatchProcessor {
    
    private $options;
    private $converter;
    private $progress_key = 'awc_batch_progress';
    private $log_key = 'awc_batch_log';
    
    public function __construct() {
        $this->options = get_option('awc_settings', array());
        $this->converter = new AWC_ImageConverter();
        
        if (empty($this->options)) {
            $this->options = array(
                'batch_size' => 10,
                'exclude_dirs' => array('plugins', 'languages', 'upgrade', 'cache')
            );
        }
    }
    
    /**
     * Process batch conversion
     */
    public function processBatch() {
        $batch_size = $this->options['batch_size'] ?? 10;
        $progress = $this->getProgress();
        
        if ($progress['status'] === 'idle') {
            $this->initializeBatch();
            $progress = $this->getProgress();
        }
        
        if ($progress['status'] === 'completed') {
            return array(
                'status' => 'completed',
                'message' => 'Batch conversion already completed',
                'progress' => $progress
            );
        }
        
        $files = $this->getNextBatch($batch_size);
        
        
        if (empty($files)) {
            $this->completeBatch();
            return array(
                'status' => 'completed',
                'message' => 'Batch conversion completed successfully',
                'progress' => $this->getProgress()
            );
        }
        
        $results = $this->processFiles($files);
        
        $this->updateProgress($results);
        
        $updated_progress = $this->getProgress();
        
        
        return array(
            'status' => 'processing',
            'message' => 'Processing batch...',
            'progress' => $updated_progress,
            'results' => $results
        );
    }
    
    /**
     * Initialize batch processing
     */
    private function initializeBatch() {
        try {
            $files = $this->scanForImages();
            
            if (!is_array($files)) {
                $files = array();
            }
            
            
            $progress = array(
                'status' => 'processing',
                'total_files' => count($files),
                'processed_files' => 0,
                'converted_files' => 0,
                'skipped_files' => 0,
                'error_files' => 0,
                'current_file' => '',
                'start_time' => time(),
                'files' => $files
            );
            
            $update_result = update_option($this->progress_key, $progress);
            if (!$update_result) {
            }
            
            $this->clearLog();
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Scan for images in wp-content directory
     */
    private function scanForImages() {
        $upload_dir = wp_upload_dir();
        $wp_content_dir = WP_CONTENT_DIR;
        $excluded_dirs = $this->options['exclude_dirs'] ?? array('plugins', 'languages', 'upgrade', 'cache');
        
        
        $files = array();
        $this->scanDirectory($wp_content_dir, $files, $excluded_dirs);
        
        
        return $files;
    }
    
    /**
     * Recursively scan directory for images
     */
    private function scanDirectory($dir, &$files, $excluded_dirs) {
        if (!is_dir($dir)) {
            return;
        }
        
        $items = @scandir($dir);
        if ($items === false) {
            return;
        }
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $item_path = $dir . '/' . $item;
            
            if (is_dir($item_path) && in_array($item, $excluded_dirs)) {
                continue;
            }
            
            if (is_dir($item_path)) {
                $this->scanDirectory($item_path, $files, $excluded_dirs);
            } elseif ($this->converter->isImage($item_path)) {
                $webp_path = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $item_path);
                if (!file_exists($webp_path)) {
                    $files[] = $item_path;
                    if (count($files) % 100 === 0) {
                    }
                }
            }
        }
    }
    
    /**
     * Get next batch of files to process
     */
    private function getNextBatch($batch_size) {
        $progress = $this->getProgress();
        $files = isset($progress['files']) ? $progress['files'] : array();
        $processed = isset($progress['processed_files']) ? $progress['processed_files'] : 0;
        
        
        if (empty($files)) {
            return array();
        }
        
        if ($processed >= count($files)) {
            return array();
        }
        
        $next_batch = array_slice($files, $processed, $batch_size);
        
        if (!empty($next_batch)) {
        }
        
        return $next_batch;
    }
    
    /**
     * Process files in current batch
     */
    private function processFiles($files) {
        $results = array(
            'converted' => 0,
            'skipped' => 0,
            'errors' => 0,
            'details' => array()
        );
        
        
        foreach ($files as $index => $file_path) {
            
            $result = $this->processFile($file_path);
            
            if ($result['status'] === 'converted') {
                $results['converted']++;
            } elseif ($result['status'] === 'skipped') {
                $results['skipped']++;
            } else {
                $results['errors']++;
            }
            
            $results['details'][] = $result;
            $this->logProgress($file_path, $result);
        }
        
        
        return $results;
    }
    
    /**
     * Process individual file
     */
    private function processFile($file_path) {
        
        if (!file_exists($file_path)) {
            return array(
                'file' => $file_path,
                'status' => 'error',
                'message' => 'File not found'
            );
        }
        
        if (!$this->converter->isImage($file_path)) {
            return array(
                'file' => $file_path,
                'status' => 'skipped',
                'message' => 'Not an image file'
            );
        }
        
        $webp_path = $this->converter->convertToWebP($file_path);
        
        if ($webp_path) {
            
            $file_info = pathinfo($file_path);
            $extension = strtolower($file_info['extension']);
            
            if (!in_array($extension, array('jpg', 'jpeg', 'png'))) {
                $this->converter->convertToJPG($file_path);
            }
            
            return array(
                'file' => $file_path,
                'webp_file' => $webp_path,
                'status' => 'converted',
                'message' => 'Successfully converted to WebP',
                'original_size' => $this->converter->getFileSize($file_path),
                'webp_size' => $this->converter->getFileSize($webp_path)
            );
        }
        
        return array(
            'file' => $file_path,
            'status' => 'error',
            'message' => 'Failed to convert to WebP'
        );
    }
    
    /**
     * Update progress
     */
    private function updateProgress($results) {
        $progress = $this->getProgress();
        
        $old_processed = $progress['processed_files'];
        $progress['processed_files'] += count($results['details']);
        $progress['converted_files'] += $results['converted'];
        $progress['skipped_files'] += $results['skipped'];
        $progress['error_files'] += $results['errors'];
        
        if (!empty($results['details'])) {
            $progress['current_file'] = end($results['details'])['file'];
        }
        
        
        $update_result = update_option($this->progress_key, $progress);
        if (!$update_result) {
        }
        

        $verify_progress = get_option($this->progress_key, array());
        if ($verify_progress['processed_files'] != $progress['processed_files']) {
        }
    }
    
    /**
     * Complete batch processing
     */
    private function completeBatch() {
        $progress = $this->getProgress();
        $progress['status'] = 'completed';
        $progress['end_time'] = time();
        $progress['duration'] = $progress['end_time'] - $progress['start_time'];
        
        update_option($this->progress_key, $progress);
        $this->logMessage('Batch conversion completed successfully');
    }
    
    /**
     * Get current progress
     */
    public function getProgress() {
        $progress = get_option($this->progress_key, array());
        
        if (empty($progress)) {
            return array(
                'status' => 'idle',
                'total_files' => 0,
                'processed_files' => 0,
                'converted_files' => 0,
                'skipped_files' => 0,
                'error_files' => 0,
                'current_file' => '',
                'percentage' => 0
            );
        }
        
        $progress['percentage'] = $progress['total_files'] > 0 
            ? round(($progress['processed_files'] / $progress['total_files']) * 100, 2)
            : 0;
        
        
        return $progress;
    }
    
    /**
     * Reset batch processing
     */
    public function resetBatch() {
        delete_option($this->progress_key);
        $this->clearLog();
    }
    
    /**
     * Get processing log
     */
    public function getLog($limit = 100) {
        $log = get_option($this->log_key, array());
        return array_slice($log, -$limit);
    }
    
    /**
     * Log progress message
     */
    private function logProgress($file_path, $result) {
        $message = sprintf(
            '[%s] %s: %s - %s',
            date('Y-m-d H:i:s'),
            basename($file_path),
            $result['status'],
            $result['message']
        );
        
        $this->logMessage($message);
    }
    
    /**
     * Log message
     */
    private function logMessage($message) {
        $log = get_option($this->log_key, array());
        $log[] = $message;
        
        if (count($log) > 1000) {
            $log = array_slice($log, -1000);
        }
        
        $update_result = update_option($this->log_key, $log);
        if (!$update_result) {
        }
    }
    
    /**
     * Clear log
     */
    private function clearLog() {
        delete_option($this->log_key);
    }
    
    /**
     * Get statistics
     */
    public function getStats() {
        $progress = $this->getProgress();
        
        return array(
            'total_images' => $progress['total_files'],
            'converted_images' => $progress['converted_files'],
            'skipped_images' => $progress['skipped_files'],
            'error_images' => $progress['error_files'],
            'completion_percentage' => $progress['percentage'],
            'status' => $progress['status'],
            'duration' => isset($progress['duration']) ? $this->formatDuration($progress['duration']) : null
        );
    }
    
    /**
     * Format duration in human readable format
     */
    private function formatDuration($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        } else {
            return sprintf('%ds', $seconds);
        }
    }
    
    /**
     * Estimate remaining time
     */
    public function getEstimatedTimeRemaining() {
        $progress = $this->getProgress();
        
        if ($progress['status'] !== 'processing' || $progress['processed_files'] === 0) {
            return null;
        }
        
        $elapsed_time = time() - $progress['start_time'];
        $files_per_second = $progress['processed_files'] / $elapsed_time;
        $remaining_files = $progress['total_files'] - $progress['processed_files'];
        
        if ($files_per_second > 0) {
            $remaining_seconds = $remaining_files / $files_per_second;
            return $this->formatDuration($remaining_seconds);
        }
        
        return null;
    }
}
