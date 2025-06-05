<?php

add_action('wp_head', function () {
    if (preg_match('/\/games\/[^\/]+/', $_SERVER['REQUEST_URI'])) {
        $slug = get_query_var('game_slug');
        $game_data = get_game_data($slug);
        if ($game_data) {
            $post = $game_data;
            $game_title = esc_attr($post['post_title']);
            $game_description = !empty($post['meta']['affiliate_desc']) ? esc_attr(wp_strip_all_tags($post['meta']['affiliate_desc'])) : 'Check out ' . $game_title . ' on Game Zap Hub!';
            $game_image = !empty($post['meta']['background_image']) ? esc_url($post['meta']['background_image']) : esc_url(get_template_directory_uri() . '/assets/img/no-image.png');
            $canonical_url = esc_url('https://gamezaphub.com/games/' . $slug . '/');
?>
            <title><?php echo $game_title; ?> - Game Zap Hub</title>
            <meta name="description" content="<?php echo $game_description; ?>">
            <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
            <meta property="og:locale" content="en_US">
            <meta property="og:title" content="<?php echo $game_title; ?> - Game Zap Hub">
            <meta property="og:description" content="<?php echo $game_description; ?>">
            <meta property="og:image" content="<?php echo $game_image; ?>">
            <meta property="og:image:secure_url" content="<?php echo $game_image; ?>">
            <meta property="og:image:width" content="1140">
            <meta property="og:image:height" content="570">
            <meta property="og:image:alt" content="<?php echo $game_title; ?>">
            <meta property="og:image:type" content="image/jpeg">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="GameZapHub">
            <meta property="og:updated_time" content="<?php echo $post['meta']['updated_on']; ?>">
            <meta property="article:section" content="Games">
            <meta property="article:published_time" content="<?php echo $post['meta']['updated_on']; ?>">
            <meta property="article:modified_time" content="<?php echo $post['meta']['updated_on']; ?>">
            <meta name="twitter:title" content="<?php echo $game_title; ?> - Game Zap Hub">
            <meta name="twitter:description" content="<?php echo $game_description; ?>">
            <meta name="twitter:image" content="<?php echo $game_image; ?>">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:label1" content="Written by">
            <meta name="twitter:data1" content="Bình Nguyễn">
            <meta name="twitter:label2" content="Time to read">
            <meta name="twitter:data2" content="1 minutes">
            <link rel="canonical" href="<?php echo $canonical_url; ?>">
            <link rel="dns-prefetch" href="//cdnis.cloudflare.com">
            <link rel="dns-prefetch" href="//www.googletagmanager.com">
            <link rel="alternate" type="application/rss+xml" title="Game Zap Hub × Feed" href="https://gamezaphub.com/feed/">
            <link rel="alternate" type="application/rss+xml" title="Game Zap Hub × Comments Feed" href="https://gamezaphub.com/games/<?php echo esc_attr($slug); ?>/feed/">
        <?php
        }
    }
}, 1);

function rankmath_disable_features()
{
    if (preg_match('/\/games\/[^\/]+/', $_SERVER['REQUEST_URI'])) {
        remove_all_actions('rank_math/head');
    }
}
add_action('init', 'rankmath_disable_features', 1);

add_action('wp_head', function () {
    if (preg_match('/\/games\/[^\/]+/', $_SERVER['REQUEST_URI'])) {
        $slug = get_query_var('game_slug');
        $game_data = get_game_data($slug);
        if ($game_data) {
            $game_title = esc_attr($game_data['post_title']);
            $game_image = !empty($game_data['meta']['background_image']) ? esc_url($game_data['meta']['background_image']) : esc_url(get_template_directory_uri() . '/assets/img/no-image.png');
            $date_published = mysql2date('Y-m-d', $game_data['meta']['updated_on']);
        }
        ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "<?php echo esc_js($game_title); ?>",
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>"
                },
                "author": {
                    "@type": "Organization",
                    "name": "Gamezaphub"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "Gamezaphub",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "<?php echo esc_url($game_image); ?>"
                    }
                },
                "datePublished": "<?php echo esc_js($date_published); ?>",
            }
        </script>
<?php
    }
});

add_action('template_redirect', function () {
    $request_uri = $_SERVER['REQUEST_URI'];

    if (
        preg_match('/^\/games\/[^\/]+\/.+\.(webp|jpg|jpeg|png|gif|mp4|webm|ogg|svg)$/i', $request_uri) &&
        strpos($request_uri, '/wp-content/') === false
    ) {
        wp_die('Invalid media file request', 'Error 404', ['response' => 404]);
    }
});
