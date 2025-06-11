<?php

add_action('wp_head', function () {
    if (preg_match('/\/games\/[^\/]+/', $_SERVER['REQUEST_URI'])) {
        $slug = get_query_var('game_slug');
        $game_data = get_game_data($slug);
        if ($game_data) {
            $post = $game_data;
            $game_title = esc_attr($post['post_title']);
            $game_description = !empty($post['meta']['affiliate_desc']) ? esc_attr(wp_strip_all_tags($post['meta']['affiliate_desc'])) : 'The dõi ' . $game_title . ' trên Game Zap Hub!';
            $game_image = !empty($post['meta']['background_image']) ? esc_url($post['meta']['background_image']) : esc_url(get_template_directory_uri() . '/assets/img/no-image.png');
            $canonical_url = esc_url('https://gamezaphub.com/games/' . $slug . '/'); ?>

            <title><?php echo $game_title ?> - Game Zap Hub</title>
            <meta name="description" content="<?php echo $game_description ?>">
            <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
            <meta property="og:locale" content="vi_VN">
            <meta property="og:title" content="<?php echo $game_title ?> - Game Zap Hub">
            <meta property="og:description" content="` . $game_description . `">
            <meta property="og:image" content="<?php echo $game_image ?>">
            <meta property="og:image:secure_url" content="<?php echo $game_image ?>">
            <meta property="og:image:width" content="1140">
            <meta property="og:image:height" content="570">
            <meta property="og:image:alt" content="` . $game_title . `">
            <meta property="og:image:type" content="image/jpeg">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="GameZapHub">
            <meta property="og:updated_time" content="<?php echo $post['meta']['updated_on'] ?>">
            <meta property="article:section" content="Games">
            <meta property="article:published_time" content="<?php echo $post['meta']['updated_on'] ?>">
            <meta property="article:modified_time" content="<?php echo $post['meta']['updated_on'] ?>">
            <meta name="twitter:title" content="<?php echo $game_title ?> - Game Zap Hub">
            <meta name="twitter:description" content="<?php echo $game_description ?>">
            <meta name="twitter:image" content="<?php echo $game_image ?>">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:label1" content="Viết bởi">
            <meta name="twitter:data1" content="Bình Nguyễn">
            <meta name="twitter:label2" content="Thời gian đọc">
            <meta name="twitter:data2" content="1 minutes">
            <link rel="canonical" href="` . $canonical_url . `">
            <link rel="dns-prefetch" href="//cdnis.cloudflare.com">
            <link rel="dns-prefetch" href="//www.googletagmanager.com">
            <link rel="alternate" type="application/rss+xml" title="Game Zap Hub × Feed" href="https://gamezaphub.com/games/<?php echo esc_attr($slug) ?>/feed/">
            <link rel="alternate" type="application/rss+xml" title="Game Zap Hub × Comments Feed" href="https://gamezaphub.com/games/<?php echo esc_attr($slug) ?>/feed/">`;
        <?php        }
    }

    if (preg_match('/\/video\/[^\/]+/', $_SERVER['REQUEST_URI'])) {
        $slug = get_query_var('video_slug');
        $video = get_posts([
            'name'  => $slug,
            'post_type'   => 'attachment',
            'post_status' => 'inherit',
            'numberposts' => 1,
            'post_mime_type' => 'video',
        ]);

        if ($video) {
            $video = $video[0];
            $video_title = esc_attr($video->post_title);
            $video_description = !empty($video->post_content) ? esc_attr(wp_strip_all_tags($video->post_content)) : 'Xem ' . $video_title . ' trên Game Zap Hub!';
            $canonical_url = esc_url('https://gamezaphub.com/video/' . $slug . '/');
            $thumbnail_url = get_the_post_thumbnail_url($video->ID, 'large'); ?>

            <title><?php echo $video_title ?> - Game Zap Hub</title>
            <meta name="description" content="<?php echo $video_description ?>">
            <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
            <meta property="og:locale" content="vi_VN">
            <meta property="og:title" content="<?php echo $video_title ?> - Game Zap Hub">
            <meta property="og:description" content="<?php echo $video_description ?>">
            <meta property="og:image" content="<?php echo esc_url($thumbnail_url) ?>">
            <meta property="og:image:secure_url" content="<?php echo esc_url($thumbnail_url) ?>">
            <meta property="og:image:width" content="1140">
            <meta property="og:image:height" content="570">
            <meta property="og:image:alt" content="<?php echo $video_title ?>">
            <meta property="og:image:type" content="video/mp4">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="GameZapHub">
            <meta property="og:updated_time" content="<?php echo $video->post_date ?>">
            <meta property="article:section" content="Video">
            <meta property="article:published_time" content="<?php echo $video->post_date ?>">
            <meta property="article:modified_time" content="<?php echo $video->post_date ?>">
            <meta name="twitter:title" content="<?php echo $video_title ?> - Game Zap Hub">
            <meta name="twitter:description" content="<?php echo $video_description ?>">
            <meta name="twitter:image" content="<?php echo esc_url($thumbnail_url) ?>">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:label1" content="Viết bởi">
            <meta name="twitter:data1" content="Bình Nguyễn">
            <meta name="twitter:label2" content="Thời gian đọc">
            <meta name="twitter:data2" content="1 minutes">
            <link rel="canonical" href="<?php echo $canonical_url ?>">
            <link rel="dns-prefetch" href="//cdnis.cloudflare.com">
            <link rel="dns-prefetch" href="//www.googletagmanager.com">
            <link rel="alternate" type="application/rss+xml" title="Game Zap Hub × Feed" href="https://gamezaphub.com/video/<?php echo esc_attr($slug) ?>/feed/">
            <link rel="alternate" type="application/rss+xml" title="Game Zap Hub × Comments Feed" href="https://gamezaphub.com/video/<?php echo esc_attr($slug) ?>/feed/">
<?php       }
    }
}, 1);

function rankmath_disable_features()
{
    if (preg_match('/\/games\/[^\/]+/', $_SERVER['REQUEST_URI']) or preg_match('/\/video\/[^\/]+/', $_SERVER['REQUEST_URI'])) {
        remove_all_actions('rank_math/head');
    }
}
add_action('init', 'rankmath_disable_features', 1);

add_action('template_redirect', function () {
    $request_uri = $_SERVER['REQUEST_URI'];

    if (
        preg_match('/^\/games\/[^\/]+\/.+\.(webp|jpg|jpeg|png|gif|mp4|webm|ogg|svg)$/i', $request_uri) &&
        strpos($request_uri, '/wp-content/') === false
    ) {
        wp_die('Invalid media file request', 'Error 404', ['response' => 404]);
    }
});
