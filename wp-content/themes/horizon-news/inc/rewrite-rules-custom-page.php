<?php

function add_custom_rewrite_rules()
{
    add_rewrite_rule(
        '^games/([^/]+)/?$',
        'index.php?game_slug=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^video/([^/]+)/?$',
        'index.php?video_slug=$matches[1]',
        'top'
    );
}
add_action('init', 'add_custom_rewrite_rules');

function add_custom_query_vars($vars)
{
    $vars[] = 'game_slug';
    $vars[] = 'video_slug';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');

function custom_template($template)
{
    $video_slug = get_query_var('video_slug');
    $game_slug = get_query_var('game_slug');

    if ($video_slug) {
        return get_template_directory() . '/single-video.php';
    }

    if ($game_slug) {
        return get_template_directory() . '/single-games.php';
    }

    return $template;
}
add_filter('template_include', 'custom_template');

function flush_rewrite_rules_on_activation()
{
    add_custom_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');

function redirect_to_trailing_slash() {
    if (is_admin() || strpos($_SERVER['REQUEST_URI'], '/wp-json/') === 0) {
        return;
    }

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

    if (empty($query) && !empty($uri) && substr($uri, -1) !== '/' && !preg_match('/\.(php|html|jpg|png|gif|css|js|ico|woff|woff2|ttf)$/i', $uri)) {
        $redirect_url = trailingslashit($uri);
        wp_redirect($redirect_url, 301);
        exit;
    }
}
add_action('template_redirect', 'redirect_to_trailing_slash');
