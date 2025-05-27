<?php
/*
Plugin Name: Lasso URL Refresh
Description: Refreshes Lasso URLs by updating game data from RAWG API and downloading related media.
Version: 1.3.6
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'action-scheduler/action-scheduler.php';

class Lasso_URL_Refresh
{
    const API_KEY = '79ca0ed080bb4109af9f504fe3bfca5b';
    const CACHE_KEY_PREFIX = 'rawg_game_';
    const LOG_TABLE = 'lasso_url_refresh_logs';
    const DEBUG_TABLE = 'lasso_debug_logs';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_ajax_lasso_refresh_all_urls', [$this, 'ajax_refresh_all_urls']);
        add_action('wp_ajax_lasso_get_refresh_logs', [$this, 'ajax_get_refresh_logs']);
        add_action('wp_ajax_lasso_get_debug_logs', [$this, 'ajax_get_debug_logs']);
        add_action('lasso_refresh_single_url', [__CLASS__, 'refresh_single_url'], 10, 1);
        register_activation_hook(__FILE__, [__CLASS__, 'create_tables']);
    }

    public static function create_tables()
    {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $log_table = $wpdb->prefix . self::LOG_TABLE;
        $sql1 = "CREATE TABLE $log_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            game_name VARCHAR(255) NOT NULL,
            status VARCHAR(50) NOT NULL,
            message TEXT NOT NULL,
            timestamp DATETIME NOT NULL,
            PRIMARY KEY (id),
            INDEX idx_post_id (post_id)
        ) $charset;";

        $debug_table = $wpdb->prefix . self::DEBUG_TABLE;
        $sql2 = "CREATE TABLE $debug_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            context VARCHAR(255) NOT NULL,
            log TEXT NOT NULL,
            timestamp DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql1);
        dbDelta($sql2);
    }

    public function add_admin_menu()
    {
        add_menu_page('Lasso URL Refresh', 'Lasso URL Refresh', 'manage_options', 'lasso-url-refresh', [$this, 'render_admin_page'], 'dashicons-update', 80);
        add_submenu_page('lasso-url-refresh', 'Debug Logs', 'Debug Logs', 'manage_options', 'lasso-debug-logs', [$this, 'render_debug_page']);
    }

    public function render_admin_page()
    {
        include plugin_dir_path(__FILE__) . 'views/admin-page.php';
    }

    public function render_debug_page()
    {
        include plugin_dir_path(__FILE__) . 'views/debug-page.php';
    }

    public function ajax_refresh_all_urls()
    {
        if (!current_user_can('manage_options')) wp_send_json_error(['message' => 'Unauthorized']);

        global $wpdb;
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}" . self::LOG_TABLE);

        $lasso_posts = get_posts([
            'post_type' => 'lasso-urls',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);

        if (empty($lasso_posts)) {
            wp_send_json_error(['message' => 'No Lasso URLs found.']);
        }

        foreach ($lasso_posts as $post) {
            if (!$post->ID || !$post->post_title) continue;
            as_enqueue_async_action('lasso_refresh_single_url', ['post_id' => $post->ID], 'lasso_url_refresh');
            self::log($post->ID, $post->post_title, 'queued', 'Game queued for refresh.');
            self::debug_log('queue', "Queued post ID: {$post->ID} - {$post->post_title}");
        }

        wp_send_json_success(['message' => 'Queued ' . count($lasso_posts) . ' Lasso URLs.']);
    }

    public function ajax_get_refresh_logs()
    {
        if (!current_user_can('manage_options')) wp_send_json_error(['message' => 'Unauthorized']);

        global $wpdb;
        $last_id = isset($_POST['last_log_id']) ? (int) $_POST['last_log_id'] : 0;
        $logs = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}" . self::LOG_TABLE . " WHERE id > %d ORDER BY id DESC LIMIT 50",
            $last_id
        ), ARRAY_A);

        $is_complete = !as_has_scheduled_action('lasso_refresh_single_url');

        wp_send_json_success(['logs' => $logs, 'is_complete' => $is_complete]);
    }

    public function ajax_get_debug_logs()
    {
        if (!current_user_can('manage_options')) wp_send_json_error(['message' => 'Unauthorized']);

        global $wpdb;
        $logs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}" . self::DEBUG_TABLE . " ORDER BY id DESC LIMIT 200", ARRAY_A);

        wp_send_json_success(['logs' => $logs]);
    }

    private static function log($post_id, $name, $status, $msg)
    {
        global $wpdb;
        $wpdb->insert($wpdb->prefix . self::LOG_TABLE, [
            'post_id' => $post_id,
            'game_name' => $name,
            'status' => $status,
            'message' => $msg,
            'timestamp' => current_time('mysql')
        ]);
    }

    public static function debug_log($context, $msg)
    {
        global $wpdb;
        $wpdb->insert($wpdb->prefix . self::DEBUG_TABLE, [
            'context' => $context,
            'log' => $msg,
            'timestamp' => current_time('mysql')
        ]);
    }

    public static function refresh_single_url($args)
    {
        if (!is_array($args)) $args = ['post_id' => $args];
        if (!isset($args['post_id']) || !is_numeric($args['post_id']) || $args['post_id'] <= 0) {
            self::debug_log('refresh', 'Invalid post_id received: ' . print_r($args, true));
            return;
        }

        $post_id = (int) $args['post_id'];
        $post = get_post($post_id);
        if (!$post || empty($post->post_title)) {
            self::log($post_id, 'Unknown', 'error', 'Invalid post or title.');
            self::debug_log('refresh', "Post invalid or missing title (ID: $post_id)");
            return;
        }

        $title = $post->post_title;
        self::log($post_id, $title, 'processing', 'Refreshing game...');
        self::debug_log('refresh', "Starting refresh for post ID $post_id → $title");

        // Search game by title
        $search = urlencode($title);
        $search_api = "https://api.rawg.io/api/games?key=" . self::API_KEY . "&search=$search";
        $resp = wp_remote_get($search_api);
        self::debug_log('api', "Search URL: $search_api");

        if (is_wp_error($resp)) {
            self::debug_log('api', 'Search API error: ' . $resp->get_error_message());
            return;
        }

        $games = json_decode(wp_remote_retrieve_body($resp), true);
        $game = $games['results'][0] ?? null;
        if (!$game) {
            self::debug_log('api', "No game found for: $title");
            return;
        }

        $details_url = "https://api.rawg.io/api/games/{$game['id']}?key=" . self::API_KEY;
        $details = wp_remote_get($details_url);

        if (is_wp_error($details)) {
            self::debug_log('api', 'Detail API error: ' . $details->get_error_message());
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($details), true);

        $slug = $data['slug'] ?? '';
        $store_url = "https://api.rawg.io/api/games/{$slug}/stores?key=" . self::API_KEY;
        $store_resp = wp_remote_get($store_url);
        $respStore = is_wp_error($store_resp) ? [] : json_decode(wp_remote_retrieve_body($store_resp), true);
        self::debug_log('stores', "Fetched stores for $slug: " . json_encode($respStore));

        $screenshots = array_map(fn($s) => $s['image'], $game['short_screenshots']);
        self::debug_log('screenshots_raw', json_encode($screenshots));
        $processed_shots = [];
        foreach ($screenshots as $url) {
            $img = self::download_image_to_media($url);
            if (!is_wp_error($img)) {
                $processed_shots[] = $img;
                self::debug_log('screenshot', "Saved screenshot: $url → $img");
            } else {
                self::debug_log('screenshot', "Failed: $url - " . $img->get_error_message());
            }
        }

        $bg_img = $data['background_image'] ?? '';
        $bg_img_url = $bg_img ? self::download_image_to_media($bg_img) : '';
        self::debug_log('image', "Background: $bg_img → saved as $bg_img_url");

        $meta = [
            'stores' => $respStore['results'] ?? [],
            'screen_shots' => $processed_shots
        ];

        foreach ($meta as $key => $val) {
            update_post_meta($post_id, $key, $val);
            self::debug_log('meta', "Updated meta [$key] => " . json_encode($val));
        }

        self::log($post_id, $title, 'success', 'Refreshed successfully.');
        self::debug_log('done', "Finished refresh for: $title");
    }

    private static function download_image_to_media($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return new WP_Error('invalid_url', 'Invalid URL');

        $res = wp_remote_get($url, ['timeout' => 30]);
        if (is_wp_error($res)) return $res;

        $body = wp_remote_retrieve_body($res);
        if (!$body) return new WP_Error('empty', 'Empty body');

        $upload = wp_upload_bits(basename(parse_url($url, PHP_URL_PATH)), null, $body);
        if ($upload['error']) return new WP_Error('upload_error', $upload['error']);

        $type = wp_check_filetype($upload['file']);
        $attachment = [
            'guid' => $upload['url'],
            'post_mime_type' => $type['type'],
            'post_title' => sanitize_file_name(basename($upload['file'])),
            'post_content' => '',
            'post_status' => 'inherit'
        ];
        $attach_id = wp_insert_attachment($attachment, $upload['file']);
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $upload['file']));

        return wp_get_attachment_url($attach_id);
    }
}

new Lasso_URL_Refresh();
