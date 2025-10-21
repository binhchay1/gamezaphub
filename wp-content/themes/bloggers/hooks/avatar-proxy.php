<?php
/**
 * Avatar Proxy - Cache Gravatar avatars locally
 * Improves performance by caching avatars and serving from local server
 */

// Get hash and size from query params
$hash = isset($_GET['hash']) ? preg_replace('/[^a-f0-9]/', '', strtolower($_GET['hash'])) : '';
$size = isset($_GET['s']) ? intval($_GET['s']) : 72;

// Validate hash
if (!$hash || strlen($hash) !== 32) {
    serve_default_avatar();
    exit;
}

// Setup paths - FIXED: Correct absolute paths
$wp_root = dirname(dirname(dirname(dirname(__DIR__))));  // Go up to WordPress root
$upload_dir = $wp_root . '/wp-content/uploads/avatars';

// Create avatars directory if not exists
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        // Fallback to temp directory if can't create
        $upload_dir = sys_get_temp_dir() . '/wp-avatars';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
    }
}

// Local file path and Gravatar URL
$local_file = "{$upload_dir}/{$hash}_{$size}.jpg";
$gravatar_url = "https://secure.gravatar.com/avatar/{$hash}?s={$size}&d=404&r=g";  // d=404 to detect if avatar exists

// Download and cache if not exists or older than 30 days
if (!file_exists($local_file) || (time() - filemtime($local_file) > 2592000)) {
    // Try cURL first (more reliable), fallback to file_get_contents
    if (function_exists('curl_init')) {
        $ch = curl_init($gravatar_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,  // Skip SSL verification for dev
            CURLOPT_USERAGENT => 'Mozilla/5.0 (WordPress Avatar Proxy)',
        ]);
        
        $img = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // 404 means no avatar on Gravatar
        if ($http_code == 404 || $img === false) {
            serve_default_avatar();
            exit;
        }
        
        if ($img && strlen($img) > 0) {
            @file_put_contents($local_file, $img);
        }
    } else {
        // Fallback to file_get_contents
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Mozilla/5.0 (WordPress Avatar Proxy)',
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);
        
        $img = @file_get_contents($gravatar_url, false, $context);
        
        if ($img !== false && strlen($img) > 0) {
            @file_put_contents($local_file, $img);
        } else {
            serve_default_avatar();
            exit;
        }
    }
}

// Serve cached avatar
if (file_exists($local_file) && filesize($local_file) > 0) {
    header("Content-Type: image/jpeg");
    header("Cache-Control: public, max-age=31536000, immutable");
    header("Last-Modified: " . gmdate('D, d M Y H:i:s', filemtime($local_file)) . ' GMT');
    readfile($local_file);
    exit;
}

// Fallback to default avatar
serve_default_avatar();

/**
 * Serve default mystery man avatar
 */
function serve_default_avatar() {
    // Gravatar mystery man as base64 (small placeholder)
    $default_avatar_base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mN8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==';
    
    header("Content-Type: image/png");
    header("Cache-Control: public, max-age=86400");
    
    // Try to output actual Gravatar default or fallback to 1x1 pixel
    $size = isset($_GET['s']) ? intval($_GET['s']) : 96;
    $gravatar_default = "https://secure.gravatar.com/avatar/?s={$size}&d=mm&r=g";
    
    $img = @file_get_contents($gravatar_default, false, stream_context_create([
        'http' => ['timeout' => 5]
    ]));
    
    if ($img !== false) {
        echo $img;
    } else {
        echo base64_decode($default_avatar_base64);
    }
}
