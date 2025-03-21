<?php
/*
Plugin Name: Game Data Fetcher
Description: Fetch game developers and publishers from RAWG API and store them in WordPress database.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

function gdf_create_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $developers_table = $wpdb->prefix . 'game_developers';
    $sql_developers = "CREATE TABLE $developers_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        slug varchar(255) NOT NULL,
        games_count int NOT NULL,
        games text NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY slug (slug)
    ) $charset_collate;";

    $publishers_table = $wpdb->prefix . 'game_publishers';
    $sql_publishers = "CREATE TABLE $publishers_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        slug varchar(255) NOT NULL,
        games_count int NOT NULL,
        games text NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY slug (slug)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_developers);
    dbDelta($sql_publishers);
}
register_activation_hook(__FILE__, 'gdf_create_tables');

function gdf_admin_menu()
{
    add_menu_page(
        'Game Data Fetcher',
        'Game Data',
        'manage_options',
        'game-data-fetcher',
        'gdf_admin_page',
        'dashicons-games',
        20
    );
}
add_action('admin_menu', 'gdf_admin_menu');

function gdf_admin_page()
{
?>
    <div class="wrap">
        <h1>Game Data Fetcher</h1>
        <button id="fetch-developers" class="button button-primary">Fetch Developers</button>
        <button id="fetch-publishers" class="button button-primary">Fetch Publishers</button>
        <div id="preloader" style="display:none;">
            <img src="<?php echo plugin_dir_url(__FILE__) . 'img/loader.gif'; ?>" alt="Loading...">
        </div>
        <div id="result"></div>
    </div>
<?php
}

// Đăng ký CSS và JS
function gdf_enqueue_scripts($hook)
{
    if ($hook !== 'toplevel_page_game-data-fetcher') {
        return;
    }
    wp_enqueue_style('gdf-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('gdf-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '1.0', true);
    wp_localize_script('gdf-script', 'gdf_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('gdf_fetch_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'gdf_enqueue_scripts');

function gdf_fetch_data()
{
    check_ajax_referer('gdf_fetch_nonce', 'nonce');

    $type = sanitize_text_field($_POST['type']);
    $api_key = '79ca0ed080bb4109af9f504fe3bfca5b';
    $url = "https://api.rawg.io/api/{$type}?key={$api_key}&page_size=40&page=1";
    $inserted = 0;
    $break = false;

    global $wpdb;
    if ($type === 'developers') {
        $table_name = $wpdb->prefix . 'game_developers';
    } elseif ($type === 'publishers') {
        $table_name = $wpdb->prefix . 'game_publishers';
    } else {
        wp_send_json_error('Type không hợp lệ');
        exit;
    }

    while (!$break) {
        $response = wp_remote_get($url, array('timeout' => 15));
        $status_code = wp_remote_retrieve_response_code($response);
        if (is_wp_error($response) || $status_code >= 400) {
            $break = true;
            wp_send_json_error("Lỗi API: $status_code");
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($data['results'])) {
            $break = true;
            wp_send_json_error('Không có dữ liệu');
        }

        if (array_key_exists('next', $data) and !empty($data['next'])) {
            $url = $data['next'];
        } else {
            $break = true;
            continue;
        }

        foreach ($data['results'] as $item) {
            $games_json = json_encode($item['games'] ?? []);

            $wpdb->replace(
                $table_name,
                array(
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'games_count' => $item['games_count'],
                    'games' => $games_json,
                ),
                array('%s', '%s', '%d', '%s')
            );
            $inserted++;
        }

        sleep(5);
    }

    wp_send_json_success("Đã thêm {$inserted} {$type} vào cơ sở dữ liệu.");
}
add_action('wp_ajax_gdf_fetch_data', 'gdf_fetch_data');
