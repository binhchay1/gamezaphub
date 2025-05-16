<?php

/**
 * Plugin Name: Video Schema & Thumbnail Injector
 * Description: Tự động chèn schema VideoObject và chèn thumbnail cho video upload lên từ block Video WordPress.
 * Version: 1.0
 * Author: BroGPT
 */

add_filter('the_content', 'vgst_inject_video_schema_and_thumbnail');

function vgst_inject_video_schema_and_thumbnail($content)
{
    if (strpos($content, '<video') === false) {
        return $content;
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);

    $videos = $dom->getElementsByTagName('video');
    $schemas = [];

    foreach ($videos as $video) {
        $src = $video->getAttribute('src');
        if (!$src) continue;

        $video_url = vgst_make_absolute_url($src);
        $thumbnail_url = vgst_get_video_thumbnail($video_url);
        if ($thumbnail_url) {
            $img = $dom->createElement('img');
            $img->setAttribute('src', $thumbnail_url);
            $img->setAttribute('alt', 'Video thumbnail');
            $img->setAttribute('loading', 'lazy');
            $video->parentNode->insertBefore($img, $video->nextSibling);
        }

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "VideoObject",
            "name" => get_the_title(),
            "description" => get_the_excerpt(),
            "thumbnailUrl" => $thumbnail_url,
            "uploadDate" => get_the_date(DATE_ATOM),
            "contentUrl" => $video_url,
        ];

        $schemas[] = $schema;
    }

    if (!empty($schemas)) {
        $json_ld = '<script type="application/ld+json">' . json_encode(count($schemas) === 1 ? $schemas[0] : $schemas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        $content .= $json_ld;
    }

    return $dom->saveHTML($dom->getElementsByTagName('body')->item(0));
}

function vgst_make_absolute_url($src)
{
    if (strpos($src, 'http') === 0) return $src;
    return home_url($src);
}

function vgst_get_video_thumbnail($video_url)
{
    global $wpdb;
    $attachment_id = attachment_url_to_postid($video_url);
    if (!$attachment_id) return '';

    $upload_dir = wp_upload_dir();
    $thumb_path = $upload_dir['basedir'] . "/video_thumbs/{$attachment_id}.jpg";
    $thumb_url = $upload_dir['baseurl'] . "/video_thumbs/{$attachment_id}.jpg";

    if (!file_exists($thumb_path)) {
        @mkdir(dirname($thumb_path), 0755, true);

        $video_path = get_attached_file($attachment_id);
        $cmd = "ffmpeg -i " . escapeshellarg($video_path) . " -ss 00:00:01 -vframes 1 " . escapeshellarg($thumb_path) . " 2>&1";
        exec($cmd, $out, $code);
        if ($code !== 0) return '';
    }

    return $thumb_url;
}
