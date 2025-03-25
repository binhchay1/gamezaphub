<?php

function add_custom_rewrite_rules()
{
    add_rewrite_rule(
        '^games/([^/]+)/?$',
        'index.php?custom_games=1&game_slug=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^developers/([^/]+)/?$',
        'index.php?custom_developer=1&developer_slug=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^publishers/([^/]+)/?$',
        'index.php?custom_publisher=1&publisher_slug=$matches[1]',
        'top'
    );
}
add_action('init', 'add_custom_rewrite_rules');

function add_custom_query_vars($vars)
{
    $vars[] = 'custom_games';
    $vars[] = 'game_slug';

    $vars[] = 'custom_developers';
    $vars[] = 'developer_slug';

    $vars[] = 'custom_publishers';
    $vars[] = 'publisher_slug';

    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');

function flush_rewrite_rules_on_activation()
{
    add_custom_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');

function custom_games_template($template)
{
    if (get_query_var('custom_games') == 1) {
        $new_template = locate_template(array('single-games.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    } elseif (get_query_var('custom_developers') == 1) {
        $new_template = locate_template(array('single-developers.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    } elseif (get_query_var('custom_publishers') == 1) {
        $new_template = locate_template(array('single-publishers.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'custom_games_template');

function adjust_main_query($query)
{
    if ($query->is_main_query() && !is_admin()) {
        if (get_query_var('custom_games') == 1) {
            $query->is_front_page = false;
            $query->is_singular = true;
            $query->is_page = false;
            $query->is_home = false;
        } elseif (get_query_var('custom_developers') == 1) {
            $query->is_front_page = false;
            $query->is_singular = true;
            $query->is_page = false;
            $query->is_home = false;
        } elseif (get_query_var('custom_publishers') == 1) {
            $query->is_front_page = false;
            $query->is_singular = true;
            $query->is_page = false;
            $query->is_home = false;
        }
    }
}
add_action('pre_get_posts', 'adjust_main_query');

function get_game_data($slug)
{
    $transient_key = 'game_data_' . md5($slug);
    $cached_data = get_transient($transient_key);
    if ($cached_data !== false) {
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

        $post['meta']['screen_shots'] = unserialize($post['meta']['screen_shots']);
        $post['meta']['genres'] = unserialize($post['meta']['genres']);
        $post['meta']['platforms'] = unserialize($post['meta']['platforms']);
        $post['meta']['developers'] = unserialize($post['meta']['developers']);
        $post['meta']['publishers'] = unserialize($post['meta']['publishers']);

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
