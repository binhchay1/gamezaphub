<?php

$hash = isset($_GET['hash']) ? preg_replace('/[^a-f0-9]/', '', strtolower($_GET['hash'])) : '';
$size = isset($_GET['s']) ? intval($_GET['s']) : 72;

if (!$hash) {
    header("Content-Type: image/png");
    readfile(__DIR__ . "/wp-content/themes/yourtheme/images/default-avatar.png");
    exit;
}

$upload_dir = __DIR__ . '/wp-content/uploads/avatars';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$local_file = "{$upload_dir}/{$hash}_{$size}.jpg";
$gravatar_url = "https://secure.gravatar.com/avatar/{$hash}?s={$size}&d=mm&r=g";

if (!file_exists($local_file) || (time() - filemtime($local_file) > 2592000)) {
    $img = @file_get_contents($gravatar_url);
    if ($img) {
        file_put_contents($local_file, $img);
    }
}

if (file_exists($local_file)) {
    header("Content-Type: image/jpeg");
    header("Cache-Control: public, max-age=31536000");
    readfile($local_file);
    exit;
}

header("Content-Type: image/png");
readfile(__DIR__ . "/wp-content/themes/yourtheme/images/default-avatar.png");
