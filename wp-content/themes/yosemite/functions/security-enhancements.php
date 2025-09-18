<?php
/**
 * Security Enhancements for Yosemite Theme
 * 
 * @package Yosemite
 * @version 1.3.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced Security Functions
 */
class MTS_Security_Enhancements {
    
    /**
     * Initialize security enhancements
     */
    public static function init() {
        self::remove_wordpress_info();
        self::secure_database_queries();
        self::sanitize_inputs();
        self::prevent_direct_access();
        self::add_security_headers();
        self::secure_file_uploads();
        self::prevent_brute_force();
    }
    
    /**
     * Remove WordPress information from headers
     */
    public static function remove_wordpress_info() {
        // Remove WordPress version
        remove_action('wp_head', 'wp_generator');
        
        // Remove unnecessary meta tags
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Remove WordPress version from RSS feeds
        add_filter('the_generator', '__return_empty_string');
        
        // Remove WordPress version from scripts and styles
        add_filter('style_loader_src', array(__CLASS__, 'remove_version_from_assets'), 9999);
        add_filter('script_loader_src', array(__CLASS__, 'remove_version_from_assets'), 9999);
    }
    
    /**
     * Remove version from assets
     */
    public static function remove_version_from_assets($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Secure database queries
     */
    public static function secure_database_queries() {
        // Add prepared statement for image ID query
        add_filter('mts_get_image_id_from_url', array(__CLASS__, 'secure_image_id_query'));
        
        // Sanitize all database queries
        add_action('pre_get_posts', array(__CLASS__, 'sanitize_query_vars'));
    }
    
    /**
     * Secure image ID query
     */
    public static function secure_image_id_query($image_url) {
        if (is_numeric($image_url)) {
            return intval($image_url);
        }
        
        global $wpdb;
        $attachment = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE guid = %s AND post_type = 'attachment'",
            esc_url_raw($image_url)
        ));
        
        return $attachment ? intval($attachment) : false;
    }
    
    /**
     * Sanitize query variables
     */
    public static function sanitize_query_vars($query) {
        if (!is_admin() && $query->is_main_query()) {
            // Sanitize search query
            if (is_search()) {
                $query->set('s', sanitize_text_field(get_query_var('s')));
            }
            
            // Sanitize category query
            if (is_category()) {
                $query->set('cat', intval(get_query_var('cat')));
            }
            
            // Sanitize tag query
            if (is_tag()) {
                $query->set('tag_id', intval(get_query_var('tag_id')));
            }
        }
    }
    
    /**
     * Sanitize all inputs
     */
    public static function sanitize_inputs() {
        // Sanitize AJAX search
        add_action('wp_ajax_mts_search', array(__CLASS__, 'sanitize_ajax_search'));
        add_action('wp_ajax_nopriv_mts_search', array(__CLASS__, 'sanitize_ajax_search'));
        
        // Sanitize contact form
        add_action('wp_ajax_mts_contact_form', array(__CLASS__, 'sanitize_contact_form'));
        add_action('wp_ajax_nopriv_mts_contact_form', array(__CLASS__, 'sanitize_contact_form'));
    }
    
    /**
     * Sanitize AJAX search
     */
    public static function sanitize_ajax_search() {
        if (!isset($_REQUEST['q']) || empty($_REQUEST['q'])) {
            wp_die('Invalid search query');
        }
        
        $query = sanitize_text_field($_REQUEST['q']);
        
        // Prevent SQL injection
        if (strlen($query) > 100) {
            wp_die('Search query too long');
        }
        
        // Check for malicious patterns
        $malicious_patterns = array(
            'union', 'select', 'insert', 'update', 'delete', 'drop', 'create', 'alter',
            'script', 'javascript', 'vbscript', 'onload', 'onerror', 'onclick'
        );
        
        foreach ($malicious_patterns as $pattern) {
            if (stripos($query, $pattern) !== false) {
                wp_die('Invalid search query');
            }
        }
        
        // Continue with original search function
        ajax_mts_search();
    }
    
    /**
     * Sanitize contact form
     */
    public static function sanitize_contact_form() {
        $required_fields = array('name', 'email', 'message');
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                wp_die('Missing required field: ' . $field);
            }
        }
        
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        
        // Validate email
        if (!is_email($email)) {
            wp_die('Invalid email address');
        }
        
        // Check for spam patterns
        $spam_patterns = array(
            'viagra', 'casino', 'lottery', 'winner', 'congratulations',
            'click here', 'free money', 'make money', 'work from home'
        );
        
        $message_lower = strtolower($message);
        foreach ($spam_patterns as $pattern) {
            if (strpos($message_lower, $pattern) !== false) {
                wp_die('Message appears to be spam');
            }
        }
        
        // Continue with original contact form function
        mts_contact_form();
    }
    
    /**
     * Prevent direct access to sensitive files
     */
    public static function prevent_direct_access() {
        // Block access to sensitive files
        add_action('init', array(__CLASS__, 'block_sensitive_files'));
        
        // Hide sensitive directories
        add_action('init', array(__CLASS__, 'hide_sensitive_directories'));
    }
    
    /**
     * Block sensitive files
     */
    public static function block_sensitive_files() {
        $request_uri = $_SERVER['REQUEST_URI'];
        
        $blocked_files = array(
            '.htaccess',
            '.htpasswd',
            'wp-config.php',
            'wp-config-sample.php',
            'readme.html',
            'license.txt',
            'wp-includes',
            'wp-admin',
            'xmlrpc.php'
        );
        
        foreach ($blocked_files as $file) {
            if (strpos($request_uri, $file) !== false) {
                status_header(403);
                exit('Access denied');
            }
        }
    }
    
    /**
     * Hide sensitive directories
     */
    public static function hide_sensitive_directories() {
        // Add .htaccess rules to hide sensitive directories
        $htaccess_content = "
# Block access to sensitive files
<Files ~ \"^.*\.([Hh][Tt][Aa])\">
    order allow,deny
    deny from all
    satisfy all
</Files>

# Block access to wp-config.php
<Files wp-config.php>
    order allow,deny
    deny from all
</Files>

# Block access to readme files
<Files readme.html>
    order allow,deny
    deny from all
</Files>

<Files license.txt>
    order allow,deny
    deny from all
</Files>

# Block access to XML-RPC
<Files xmlrpc.php>
    order allow,deny
    deny from all
</Files>
";
        
        $htaccess_file = ABSPATH . '.htaccess';
        
        if (is_writable(ABSPATH) && !file_exists($htaccess_file)) {
            file_put_contents($htaccess_file, $htaccess_content);
        }
    }
    
    /**
     * Add security headers
     */
    public static function add_security_headers() {
        add_action('send_headers', array(__CLASS__, 'set_security_headers'));
    }
    
    /**
     * Set security headers
     */
    public static function set_security_headers() {
        if (!is_admin()) {
            // Prevent clickjacking
            header('X-Frame-Options: SAMEORIGIN');
            
            // Prevent MIME type sniffing
            header('X-Content-Type-Options: nosniff');
            
            // Enable XSS protection
            header('X-XSS-Protection: 1; mode=block');
            
            // Strict Transport Security (HTTPS only)
            if (is_ssl()) {
                header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
            }
            
            // Referrer Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Content Security Policy
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://ajax.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self';";
            header("Content-Security-Policy: $csp");
        }
    }
    
    /**
     * Secure file uploads
     */
    public static function secure_file_uploads() {
        // Restrict file types
        add_filter('upload_mimes', array(__CLASS__, 'restrict_upload_types'));
        
        // Check file size
        add_filter('wp_handle_upload_prefilter', array(__CLASS__, 'check_file_size'));
        
        // Scan uploaded files
        add_filter('wp_handle_upload', array(__CLASS__, 'scan_uploaded_file'));
    }
    
    /**
     * Restrict upload types
     */
    public static function restrict_upload_types($mimes) {
        // Only allow safe file types
        $allowed_mimes = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif' => 'image/gif',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain'
        );
        
        return $allowed_mimes;
    }
    
    /**
     * Check file size
     */
    public static function check_file_size($file) {
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if ($file['size'] > $max_size) {
            $file['error'] = 'File size too large. Maximum size is 5MB.';
        }
        
        return $file;
    }
    
    /**
     * Scan uploaded file
     */
    public static function scan_uploaded_file($file) {
        // Basic file content check
        $file_content = file_get_contents($file['file']);
        
        // Check for malicious patterns
        $malicious_patterns = array(
            '<?php',
            '<script',
            'javascript:',
            'vbscript:',
            'onload=',
            'onerror=',
            'eval(',
            'base64_decode('
        );
        
        foreach ($malicious_patterns as $pattern) {
            if (stripos($file_content, $pattern) !== false) {
                unlink($file['file']);
                $file['error'] = 'File contains potentially malicious content';
                break;
            }
        }
        
        return $file;
    }
    
    /**
     * Prevent brute force attacks
     */
    public static function prevent_brute_force() {
        // Limit login attempts
        add_action('wp_login_failed', array(__CLASS__, 'limit_login_attempts'));
        
        // Add login delay
        add_action('wp_authenticate_user', array(__CLASS__, 'add_login_delay'));
    }
    
    /**
     * Limit login attempts
     */
    public static function limit_login_attempts($username) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $attempts = get_transient('login_attempts_' . $ip);
        
        if ($attempts === false) {
            $attempts = 0;
        }
        
        $attempts++;
        
        if ($attempts >= 5) {
            // Block IP for 1 hour
            set_transient('login_blocked_' . $ip, true, HOUR_IN_SECONDS);
        } else {
            // Store attempts for 15 minutes
            set_transient('login_attempts_' . $ip, $attempts, 15 * MINUTE_IN_SECONDS);
        }
    }
    
    /**
     * Add login delay
     */
    public static function add_login_delay($user) {
        $ip = $_SERVER['REMOTE_ADDR'];
        
        // Check if IP is blocked
        if (get_transient('login_blocked_' . $ip)) {
            wp_die('Too many login attempts. Please try again later.');
        }
        
        // Add delay for failed attempts
        $attempts = get_transient('login_attempts_' . $ip);
        if ($attempts && $attempts > 2) {
            sleep($attempts);
        }
        
        return $user;
    }
}

/**
 * Initialize security enhancements
 */
function mts_init_security_enhancements() {
    MTS_Security_Enhancements::init();
}

// Initialize security enhancements
add_action('init', 'mts_init_security_enhancements');

/**
 * Additional security measures
 */

// Disable file editing in admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// Disable plugin/theme installation
if (!defined('DISALLOW_FILE_MODS')) {
    define('DISALLOW_FILE_MODS', true);
}

// Hide login errors
add_filter('login_errors', '__return_empty_string');

// Remove admin bar for non-admins
add_action('after_setup_theme', function() {
    if (!current_user_can('manage_options')) {
        show_admin_bar(false);
    }
});

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove REST API for non-authenticated users
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        return $result;
    }
    
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }
    
    return $result;
});
