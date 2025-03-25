<?php

add_action('init', function () {
    if (is_ssl()) {
        add_filter('secure_auth_cookie', '__return_true');
        add_filter('secure_signed_cookie', '__return_true');
    }
});

add_action('init', function () {
    if (!defined('COOKIE_DOMAIN')) {
        define('COOKIE_DOMAIN', '.gamezpub.com');
    }
});

add_action('template_redirect', function () {
    if (is_404()) {
        status_header(200);
        nocache_headers();
    }
});

add_filter('get_the_archive_title', function ($title) {
    if (is_category() or is_tag()) {
        $title = single_cat_title('', false);
    }

    return '<h1 class="page-title">' . $title . '</h1>';
});

add_filter('jpeg_quality', function ($arg) {
    return 100;
});

add_filter('wp_editor_set_quality', function ($arg) {
    return 100;
});

add_filter('get_the_archive_description', function ($description) {
    if (is_category() or is_tag()) {
        $description = strip_tags(category_description());
    }
    return '<div class="archive-description">' . $description . '</div>';
});
