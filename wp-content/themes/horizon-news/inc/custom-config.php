<?php
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

add_filter('get_the_archive_description', function ($description) {
    if (is_category() or is_tag()) {
        $description = strip_tags(category_description());
    }
    return '<div class="archive-description">' . $description . '</div>';
});

add_filter('action_scheduler_queue_runner_concurrent_batches', function () {
    return 5;
});
