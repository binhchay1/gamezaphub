<?php
/*
Plugin Name: Lasso URL Refresh
Description: A plugin to refresh all Lasso URLs by fetching updated game data from rawg.io API and downloading images.
Version: 1.3
Author: Your Name
*/

require_once plugin_dir_path(__FILE__) . 'action-scheduler/action-scheduler.php';

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Lasso_URL_Refresh {
    const API_KEY = '79ca0ed080bb4109af9f504fe3bfca5b';
    const CACHE_KEY_PREFIX = 'rawg_game_';
    const LOG_TABLE = 'lasso_url_refresh_logs';

    /**
     * Initialize the plugin
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_ajax_lasso_refresh_all_urls', [$this, 'ajax_refresh_all_urls']);
        add_action('wp_ajax_lasso_get_refresh_logs', [$this, 'ajax_get_refresh_logs']);
        add_action('lasso_refresh_single_url', ['Lasso_URL_Refresh', 'refresh_single_url'], 10, 1);

        // Create log table on plugin activation
        register_activation_hook(__FILE__, [$this, 'create_log_table']);
    }

    /**
     * Create log table on plugin activation
     */
    public static function create_log_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . self::LOG_TABLE;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            game_name VARCHAR(255) NOT NULL,
            status VARCHAR(50) NOT NULL,
            message TEXT NOT NULL,
            timestamp DATETIME NOT NULL,
            PRIMARY KEY (id),
            INDEX idx_post_id (post_id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_menu_page(
            'Lasso URL Refresh',
            'Lasso URL Refresh',
            'manage_options',
            'lasso-url-refresh',
            [$this, 'render_admin_page'],
            'dashicons-update',
            80
        );
    }

    /**
     * Render admin page with refresh button and log area
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>Lasso URL Refresh</h1>
            <p>Click the button below to refresh all Lasso URLs with updated data from rawg.io API.</p>
            <button id="lasso-refresh-all" class="button button-primary">Refresh All Lasso URLs</button>
            <div id="lasso-refresh-status"></div>
            <h2>Refresh Logs</h2>
            <div id="lasso-refresh-logs" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
                <table class="widefat fixed" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Time</th>
                            <th style="width: 200px;">Game Name</th>
                            <th style="width: 100px;">Status</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody id="lasso-log-body"></tbody>
                </table>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                let isRefreshing = false;
                let lastLogId = 0;

                // Start refresh process
                $('#lasso-refresh-all').on('click', function() {
                    const $button = $(this);
                    if (isRefreshing) return;

                    isRefreshing = true;
                    $button.prop('disabled', true).text('Refreshing...');
                    $('#lasso-refresh-status').html('<p>Starting refresh...</p>');

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: { action: 'lasso_refresh_all_urls' },
                        success: function(response) {
                            $('#lasso-refresh-status').html('<p>' + response.data.message + '</p>');
                            pollLogs();
                        },
                        error: function() {
                            $('#lasso-refresh-status').html('<p>Error occurred during refresh.</p>');
                            isRefreshing = false;
                            $button.prop('disabled', false).text('Refresh All Lasso URLs');
                        }
                    });
                });

                // Poll logs every 2 seconds
                function pollLogs() {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'lasso_get_refresh_logs',
                            last_log_id: lastLogId
                        },
                        success: function(response) {
                            if (response.success && response.data.logs.length > 0) {
                                const logs = response.data.logs;
                                let html = '';
                                logs.forEach(log => {
                                    html += '<tr>';
                                    html += '<td>' + log.timestamp + '</td>';
                                    html += '<td>' + log.game_name + '</td>';
                                    html += '<td><span style="color: ' + (log.status === 'success' ? 'green' : log.status === 'error' ? 'red' : 'orange') + ';">' + log.status + '</span></td>';
                                    html += '<td>' + log.message + '</td>';
                                    html += '</tr>';
                                    lastLogId = Math.max(lastLogId, parseInt(log.id));
                                });
                                $('#lasso-log-body').prepend(html);

                                // Check if refresh is complete
                                if (response.data.is_complete) {
                                    isRefreshing = false;
                                    $('#lasso-refresh-all').prop('disabled', false).text('Refresh All Lasso URLs');
                                    $('#lasso-refresh-status').append('<p>Refresh completed!</p>');
                                } else {
                                    setTimeout(pollLogs, 2000); // Continue polling
                                }
                            } else {
                                setTimeout(pollLogs, 2000); // Continue polling
                            }
                        },
                        error: function() {
                            setTimeout(pollLogs, 2000); // Retry on error
                        }
                    });
                }
            });
        </script>
        <?php
    }

    /**
     * AJAX handler to queue refresh for all Lasso URLs
     */
    public function ajax_refresh_all_urls() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . self::LOG_TABLE;
        $wpdb->query("TRUNCATE TABLE $table_name");

        $args = [
            'post_type' => 'lasso-urls',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        $lasso_posts = get_posts($args);
        $count = count($lasso_posts);

        error_log("Found $count lasso_url posts to queue."); // Debug

        if ($count === 0) {
            wp_send_json_error(['message' => 'No lasso_url posts found to refresh.']);
            return;
        }

        foreach ($lasso_posts as $post) {
            error_log("Queueing post ID: " . $post->ID); // Debug
            as_enqueue_async_action('lasso_refresh_single_url', ['post_id' => $post->ID], 'lasso_url_refresh');
            self::log_message($post->ID, $post->post_title, 'queued', 'Game queued for refresh.');
        }

        wp_send_json_success(['message' => "Queued refresh for $count Lasso URLs."]);
    }

    /**
     * AJAX handler to get refresh logs
     */
    public function ajax_get_refresh_logs() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . self::LOG_TABLE;
        $last_log_id = isset($_POST['last_log_id']) ? intval($_POST['last_log_id']) : 0;

        $logs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id > %d ORDER BY id DESC LIMIT 50",
                $last_log_id
            ),
            ARRAY_A
        );

        // Check if refresh is complete (no more scheduled actions)
        $is_complete = as_has_scheduled_action('lasso_refresh_single_url') ? false : true;

        wp_send_json_success([
            'logs' => $logs,
            'is_complete' => $is_complete,
        ]);
    }

    /**
     * Log a message to the database
     * @param int $post_id Post ID
     * @param string $game_name Game name
     * @param string $status Status (queued, processing, success, error)
     * @param string $message Log message
     */
    private static function log_message($post_id, $game_name, $status, $message) {
        global $wpdb;
        $table_name = $wpdb->prefix . self::LOG_TABLE;

        $wpdb->insert(
            $table_name,
            [
                'post_id' => $post_id,
                'game_name' => $game_name,
                'status' => $status,
                'message' => $message,
                'timestamp' => current_time('mysql'),
            ],
            ['%d', '%s', '%s', '%s', '%s']
        );
    }

    /**
     * Refresh a single Lasso URL
     * @param array $args Arguments containing post_id
     */
    public static function refresh_single_url($args) {
        $post_id = $args['post_id'];
        $post = get_post($post_id);
        $post_title = $post->post_title;

        if (empty($post_title)) {
            self::log_message($post_id, 'Unknown', 'error', 'No title found for post.');
            return;
        }

        self::log_message($post_id, $post_title, 'processing', 'Starting refresh for game.');

        $searchString = urlencode($post_title);
        $cache_key = self::CACHE_KEY_PREFIX . md5($post_title);
        $apiUrlGet = "https://api.rawg.io/api/games?key=" . self::API_KEY . "&search=$searchString";

        $response = wp_remote_get($apiUrlGet, [
            'timeout' => 20,
            'sslverify' => false,
        ]);

        if (is_wp_error($response)) {
            self::log_message($post_id, $post_title, 'error', "API error: " . $response->get_error_message());
            return;
        }

        $respGet = json_decode(wp_remote_retrieve_body($response), true);
        if (!is_array($respGet) || empty($respGet['results'])) {
            self::log_message($post_id, $post_title, 'error', 'No game found in search.');
            return;
        }

        $resultGet = $respGet['results'][0];
        $id = $resultGet['id'];
        $short_screenshots = $resultGet['short_screenshots'] ?? [];

        $apiUrlCheckSearch = "https://api.rawg.io/api/games/$id?key=" . self::API_KEY;
        $response = wp_remote_get($apiUrlCheckSearch, [
            'timeout' => 20,
            'sslverify' => false,
        ]);

        if (is_wp_error($response)) {
            self::log_message($post_id, $post_title, 'error', "Game details error: " . $response->get_error_message());
            return;
        }

        $respCheckSearch = json_decode(wp_remote_retrieve_body($response), true);

        if (!is_array($respCheckSearch)) {
            self::log_message($post_id, $post_title, 'error', 'Game details not found.');
            return;
        }

        $result = $respCheckSearch;
        $description = array_key_exists('description', $result) ? self::extract_summary($result['description']) : '';

        $data = [
            'name' => $result['name'] ?? '',
            'released' => $result['released'] ?? '',
            'background_image' => $result['background_image'] ?? '',
            'rating' => $result['rating'] ?? 0,
            'description' => $description,
            'esrb_rating_name' => $result['esrb_rating']['name'] ?? '',
            'esrb_rating_slug' => $result['esrb_rating']['slug'] ?? '',
            'platforms' => $result['platforms'] ?? [],
            'ratings' => $result['ratings'] ?? [],
            'tags' => array_filter($result['tags'] ?? [], fn($tag) => $tag['language'] === 'eng'),
            'developers' => $result['developers'] ?? [],
            'publishers' => $result['publishers'] ?? [],
            'genres' => $result['genres'] ?? [],
            'screen_shots' => array_map(fn($shot) => $shot['image'], $short_screenshots),
            'stores' => $result['stores'] ?? [],
        ];

        // Process images
        $errors = [];
        if ($data['background_image']) {
            $attachment_url = self::download_image_to_media($data['background_image']);
            if (is_wp_error($attachment_url)) {
                $errors[] = 'Background image failed: ' . $attachment_url->get_error_message();
            } else {
                $data['background_image'] = $attachment_url;
            }
        }

        if (is_array($data['screen_shots'])) {
            $updated_screenshots = [];
            foreach ($data['screen_shots'] as $index => $shot_url) {
                $attachment_url = self::download_image_to_media($shot_url);
                if (is_wp_error($attachment_url)) {
                    $errors[] = "Screenshot #$index failed: " . $attachment_url->get_error_message();
                    continue;
                }
                $updated_screenshots[] = $attachment_url;
            }
            if (!empty($updated_screenshots)) {
                $data['screen_shots'] = $updated_screenshots;
            }
        }

        if (!empty($errors)) {
            self::log_message($post_id, $post_title, 'error', "Image processing errors: " . implode('; ', $errors));
            update_post_meta($post_id, 'image_errors', $errors);
        }

        // Update post
        $lasso_post = [
            'ID' => $post_id,
            'post_title' => $data['name'],
            'post_type' => 'lasso_url',
            'post_name' => self::slugify($data['name']),
            'post_content' => '',
            'post_status' => 'publish',
            'meta_input' => [
                'lasso_custom_redirect' => get_post_meta($post_id, 'lasso_custom_redirect', true) ?: '',
                'lasso_final_url' => get_post_meta($post_id, 'lasso_final_url', true) ?: '',
                'price' => '',
                'lasso_custom_thumbnail' => $data['background_image'],
                'name' => $data['name'],
                'released' => $data['released'],
                'background_image' => $data['background_image'],
                'rating' => $data['rating'],
                'developers' => $data['developers'],
                'esrb_rating_name' => $data['esrb_rating_name'],
                'esrb_rating_slug' => $data['esrb_rating_slug'],
                'platforms' => $data['platforms'],
                'tags' => $data['tags'],
                'genres' => $data['genres'],
                'screen_shots' => $data['screen_shots'],
                'ratings' => $data['ratings'],
                'description' => $data['description'],
                'publishers' => $data['publishers'],
                'stores' => $data['stores'],
                'enable_nofollow' => get_post_meta($post_id, 'enable_nofollow', true) ?: 0,
                'open_new_tab' => get_post_meta($post_id, 'open_new_tab', true) ?: 0,
                'enable_nofollow2' => get_post_meta($post_id, 'enable_nofollow2', true) ?: 0,
                'open_new_tab2' => get_post_meta($post_id, 'open_new_tab2', true) ?: 0,
                'link_cloaking' => get_post_meta($post_id, 'link_cloaking', true) ?: 0,
                'custom_theme' => get_post_meta($post_id, 'custom_theme', true) ?: '',
                'disclosure_text' => get_post_meta($post_id, 'disclosure_text', true) ?: '',
                'badge_text' => get_post_meta($post_id, 'badge_text', true) ?: '',
                'buy_btn_text' => get_post_meta($post_id, 'buy_btn_text', true) ?: '',
                'second_btn_url' => get_post_meta($post_id, 'second_btn_url', true) ?: '',
                'second_btn_text' => get_post_meta($post_id, 'second_btn_text', true) ?: '',
                'show_price' => get_post_meta($post_id, 'show_price', true) ?: 0,
                'show_disclosure' => get_post_meta($post_id, 'show_disclosure', true) ?: 0,
                'show_description' => get_post_meta($post_id, 'show_description', true) ?: 0,
                'enable_sponsored' => get_post_meta($post_id, 'enable_sponsored', true) ?: 0,
                'updated_on' => date('Y-m-d'),
            ],
        ];

        wp_update_post($lasso_post);

        // Update cache
        set_transient($cache_key, $data, 24 * HOUR_IN_SECONDS);

        self::log_message($post_id, $post_title, 'success', 'Game refreshed successfully.');
    }

    /**
     * Download image from URL and save to WordPress media library
     * @param string $image_url URL of the image to download
     * @return string|WP_Error URL of the image in media library or error if failed
     */
    private static function download_image_to_media($image_url) {
        if (empty($image_url) || !filter_var($image_url, FILTER_VALIDATE_URL)) {
            return new WP_Error('invalid_url', 'Invalid image URL.');
        }

        $image_data = wp_remote_get($image_url, [
            'timeout' => 30,
            'sslverify' => false,
        ]);

        if (is_wp_error($image_data)) {
            return $image_data;
        }

        $response_code = wp_remote_retrieve_response_code($image_data);
        if ($response_code != 200) {
            return new WP_Error('http_error', 'HTTP Error ' . $response_code);
        }

        $image_body = wp_remote_retrieve_body($image_data);
        if (empty($image_body)) {
            return new WP_Error('empty_body', 'Unable to retrieve image data.');
        }

        // Check image size
        $max_size = 5 * 1024 * 1024; // 5MB
        if (strlen($image_body) > $max_size) {
            return new WP_Error('file_too_large', 'Image too large, exceeds 5MB.');
        }

        $filename = sanitize_file_name(pathinfo($image_url, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . pathinfo($image_url, PATHINFO_EXTENSION);
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['path'] . '/' . $filename;

        $result = file_put_contents($file_path, $image_body);
        if ($result === false) {
            return new WP_Error('file_error', 'Unable to save image file.');
        }

        $file_type = wp_check_filetype($file_path, null);
        if (empty($file_type['type'])) {
            @unlink($file_path);
            return new WP_Error('invalid_filetype', 'Invalid file type.');
        }

        $attachment = [
            'guid' => $upload_dir['url'] . '/' . $filename,
            'post_mime_type' => $file_type['type'],
            'post_title' => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        $attach_id = wp_insert_attachment($attachment, $file_path);
        if (!$attach_id) {
            @unlink($file_path);
            return new WP_Error('attachment_error', 'Unable to add image to media library.');
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        if (empty($attach_data)) {
            @unlink($file_path);
            wp_delete_attachment($attach_id, true);
            return new WP_Error('metadata_error', 'Unable to generate image metadata.');
        }

        // Resize image if needed
        $image = wp_get_image_editor($file_path);
        if (!is_wp_error($image)) {
            $image->resize(1200, 1200, false);
            $image->save($file_path);
            wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $file_path));
        }

        return wp_get_attachment_url($attach_id);
    }

    /**
     * Slugify text for URL-friendly strings
     * @param string $text Text to slugify
     * @param string $divider Divider character
     * @return string Slugified text
     */
    public static function slugify($text, string $divider = '-') {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Extract summary from text
     * @param string $text Raw description
     * @return string Extracted summary
     */
    public static function extract_summary($text) {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentence_count = count($sentences);

        if ($sentence_count >= 4) {
            return implode(' ', array_slice($sentences, 0, 4));
        }

        if ($sentence_count >= 2) {
            return implode(' ', array_slice($sentences, 0, 2));
        }

        $words = explode(' ', $text);
        return implode(' ', array_slice($words, 0, 100));
    }
}

// Initialize plugin
new Lasso_URL_Refresh();

// Placeholder for Lasso_Helper class
if (!class_exists('Lasso_Helper')) {
    class Lasso_Helper {
        public static function write_log($message, $log_name) {
            // Implement logging logic here
            error_log("[$log_name] $message");
        }
    }
}
?>