<?php

function is_true_homepage()
{
    if (is_front_page()) {
        $current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        return $current_path === '';
    }

    return false;
}

function get_game_data($slug)
{
    $transient_key = 'game_data_' . md5($slug);
    $cached_data = get_transient($transient_key);

    if (!empty($cached_data)) {
        return $cached_data;
    }

    global $wpdb;
    $post = array();
    $meta_key = 'lasso_final_url';
    $query = $wpdb->prepare(
        "SELECT p.ID, p.post_title
         FROM {$wpdb->posts} p
         WHERE p.post_type = 'lasso-urls'
         AND EXISTS (
             SELECT 1
             FROM {$wpdb->postmeta} pm
             WHERE pm.post_id = p.ID
             AND pm.meta_key = %s
             AND pm.meta_value LIKE %s
         )",
        $meta_key,
        '%/games/' . esc_sql($slug) . '%'
    );

    $results = $wpdb->get_results($query);
    if ($results) {
        $post_data = $results[0];
        $post_id = $post_data->ID;
        $meta_results = $wpdb->get_results($wpdb->prepare(
            "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d",
            $post_id
        ));
        $post = array('ID' => $post_id, 'post_title' => $post_data->post_title, 'meta' => array());
        foreach ($meta_results as $meta) {
            $post['meta'][$meta->meta_key] = $meta->meta_value;
        }

        $post['meta']['screen_shots'] = maybe_unserialize($post['meta']['screen_shots']);
        $post['meta']['genres'] = maybe_unserialize($post['meta']['genres']);
        $post['meta']['platforms'] = maybe_unserialize($post['meta']['platforms']);
        $post['meta']['developers'] = maybe_unserialize($post['meta']['developers']);
        $post['meta']['publishers'] = maybe_unserialize($post['meta']['publishers']);

        $lasso_id = $post['ID'];
        $post_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT detection_id FROM wp_lasso_link_locations WHERE post_id = %d AND display_type = 'Single'",
            $lasso_id
        ));

        $args = array(
            'post_type' => 'post',
            'post__in' => $post_ids,
            'posts_per_page' => 5,
            'orderby' => 'post__in',
            'post_status' => 'publish',
            'no_found_rows' => true,
        );
        $related_query = new WP_Query($args);
        $post['related_posts'] = $related_query;

        set_transient($transient_key, $post, 24 * HOUR_IN_SECONDS);
        return $post;
    }

    set_transient($transient_key, false, 24 * HOUR_IN_SECONDS);
    return false;
}

function clear_game_data_transient($slug)
{
    $transient_key = 'game_data_' . md5($slug);
    delete_transient($transient_key);
}

add_action('save_post', function ($post_id) {
    $slug = get_post_field('post_name', $post_id);
    if ($slug) {
        clear_game_data_transient($slug);
    }
});

add_filter('pre_get_document_title', 'custom_game_page_title', 10, 1);
function custom_game_page_title($title)
{
    if (get_query_var('custom_games') == 1) {
        $game_data = get_game_data(get_query_var('game_slug'));
        if ($game_data) {
            $new_title = esc_html($game_data['post_title']);
            return $new_title;
        }
    }
    return $title;
}

function fetch_and_store_rawg_stores()
{
    $apiKey = '79ca0ed080bb4109af9f504fe3bfca5b';
    $url = "https://api.rawg.io/api/stores?key={$apiKey}";

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        error_log('Failed to fetch RAWG stores: ' . $response->get_error_message());
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    global $wpdb;

    if (!empty($data['results'])) {
        foreach ($data['results'] as $store) {
            $existing = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM wp_rawg_stores WHERE id = %d", $store['id']),
                ARRAY_A
            );

            $wpdb->replace(
                'wp_rawg_stores',
                [
                    'id'          => $store['id'],
                    'name'        => $store['name'],
                    'domain'      => $store['domain'],
                    'slug'        => $store['slug'],
                    'games_count' => $store['games_count'],
                    'updated_at'  => current_time('mysql'),
                    'image_thumb' => $existing['image_thumb'] ?? null,
                ],
                ['%d', '%s', '%s', '%s', '%d', '%s']
            );
        }
    }

    $cache_key = 'rawg_stores';

    set_transient($cache_key, $data, 24 * HOUR_IN_SECONDS);
}

add_action('switch_theme', function () {
    $timestamp = wp_next_scheduled('fetch_rawg_stores_daily');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'fetch_rawg_stores_daily');
    }
});

if (!wp_next_scheduled('fetch_rawg_stores_daily')) {
    wp_schedule_event(time(), 'daily', 'fetch_rawg_stores_daily');
}

add_action('fetch_rawg_stores_daily', 'fetch_and_store_rawg_stores');
