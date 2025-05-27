<?php
/*
Plugin Name: Lasso URL Refresh
Description: Refreshes Lasso URLs by updating game data from RAWG API and downloading related media.
Version: 1.3.1
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'action-scheduler/action-scheduler.php';

class Lasso_URL_Refresh
{
    const API_KEY = '79ca0ed080bb4109af9f504fe3bfca5b';
    const CACHE_KEY_PREFIX = 'rawg_game_';
    const LOG_TABLE = 'lasso_url_refresh_logs';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_ajax_lasso_refresh_all_urls', [$this, 'ajax_refresh_all_urls']);
        add_action('wp_ajax_lasso_get_refresh_logs', [$this, 'ajax_get_refresh_logs']);
        add_action('lasso_refresh_single_url', [__CLASS__, 'refresh_single_url'], 10, 1);
        register_activation_hook(__FILE__, [__CLASS__, 'create_log_table']);
    }

    public static function create_log_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::LOG_TABLE;
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            game_name VARCHAR(255) NOT NULL,
            status VARCHAR(50) NOT NULL,
            message TEXT NOT NULL,
            timestamp DATETIME NOT NULL,
            PRIMARY KEY (id),
            INDEX idx_post_id (post_id)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function add_admin_menu()
    {
        add_menu_page('Lasso URL Refresh', 'Lasso URL Refresh', 'manage_options', 'lasso-url-refresh', [$this, 'render_admin_page'], 'dashicons-update', 80);
    }

    public function render_admin_page()
    {
        include plugin_dir_path(__FILE__) . 'views/admin-page.php';
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
            as_enqueue_async_action('lasso_refresh_single_url', ['post_id' => $post->ID], 'lasso_url_refresh');
            self::log($post->ID, $post->post_title, 'queued', 'Game queued for refresh.');
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

    public static function refresh_single_url($args)
    {
        $post_id = $args['post_id'];
        $post = get_post($post_id);
        if (!$post || empty($post->post_title)) {
            self::log($post_id, 'Unknown', 'error', 'Invalid post or title.');
            return;
        }

        $title = $post->post_title;
        self::log($post_id, $title, 'processing', 'Refreshing game...');

        $search = urlencode($title);
        $cache_key = self::CACHE_KEY_PREFIX . md5($title);
        $search_api = "https://api.rawg.io/api/games?key=" . self::API_KEY . "&search=$search";

        $resp = wp_remote_get($search_api, ['timeout' => 20, 'sslverify' => false]);
        if (is_wp_error($resp)) return self::log($post_id, $title, 'error', $resp->get_error_message());

        $games = json_decode(wp_remote_retrieve_body($resp), true);
        if (empty($games['results'][0])) return self::log($post_id, $title, 'error', 'No game found.');

        $game = $games['results'][0];
        $details_url = "https://api.rawg.io/api/games/{$game['id']}?key=" . self::API_KEY;
        $details = wp_remote_get($details_url, ['timeout' => 20, 'sslverify' => false]);
        if (is_wp_error($details)) return self::log($post_id, $title, 'error', $details->get_error_message());

        $data = json_decode(wp_remote_retrieve_body($details), true);
        if (!is_array($data)) return self::log($post_id, $title, 'error', 'Game details invalid.');

        $bg_img = $data['background_image'] ?? '';
        $bg_img_url = $bg_img ? self::download_image_to_media($bg_img) : '';

        $screenshots = array_map(fn($s) => $s['image'], $data['short_screenshots'] ?? []);
        $processed_shots = [];
        foreach ($screenshots as $url) {
            $img = self::download_image_to_media($url);
            if (!is_wp_error($img)) $processed_shots[] = $img;
        }

        $description = self::extract_summary($data['description'] ?? '');
        $meta = [
            'name' => $data['name'],
            'released' => $data['released'],
            'background_image' => $bg_img_url,
            'rating' => $data['rating'],
            'description' => $description,
            'screen_shots' => $processed_shots,
            'updated_on' => date('Y-m-d')
        ];

        foreach ($meta as $key => $value) update_post_meta($post_id, $key, $value);

        wp_update_post([
            'ID' => $post_id,
            'post_title' => $data['name'],
            'post_type' => 'lasso-urls',
            'post_name' => sanitize_title($data['name']),
            'post_content' => $description,
            'post_status' => 'publish'
        ]);

        set_transient($cache_key, $data, DAY_IN_SECONDS);
        self::log($post_id, $title, 'success', 'Refreshed successfully.');
    }

    private static function download_image_to_media($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return new WP_Error('invalid_url', 'Invalid image URL.');

        $res = wp_remote_get($url, ['timeout' => 30, 'sslverify' => false]);
        if (is_wp_error($res) || wp_remote_retrieve_response_code($res) !== 200)
            return new WP_Error('download_error', 'Could not download image.');

        $body = wp_remote_retrieve_body($res);
        if (!$body) return new WP_Error('empty', 'Empty image body.');

        $upload = wp_upload_bits(basename(parse_url($url, PHP_URL_PATH)), null, $body);
        if ($upload['error']) return new WP_Error('upload_error', $upload['error']);

        $type = wp_check_filetype($upload['file']);
        $attach_id = wp_insert_attachment([
            'guid' => $upload['url'],
            'post_mime_type' => $type['type'],
            'post_title' => sanitize_file_name(basename($upload['file'])),
            'post_content' => '',
            'post_status' => 'inherit'
        ], $upload['file']);

        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $upload['file']));
        return wp_get_attachment_url($attach_id);
    }

    public static function extract_summary($text)
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', wp_strip_all_tags($text), -1, PREG_SPLIT_NO_EMPTY);
        return implode(' ', array_slice($sentences, 0, min(3, count($sentences))));
    }
}

new Lasso_URL_Refresh();
