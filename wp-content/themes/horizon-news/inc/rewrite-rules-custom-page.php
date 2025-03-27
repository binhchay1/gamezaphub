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
