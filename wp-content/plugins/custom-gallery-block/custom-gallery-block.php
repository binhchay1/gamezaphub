<?php
/*
 * Plugin Name: Custom Gallery Block
 * Description: Tùy chỉnh block Gallery với carousel và phóng to ảnh.
 * Version: 1.0
 * Author: Your Name
 */

function custom_gallery_block_scripts()
{
    wp_enqueue_script(
        'custom-gallery-block',
        plugins_url('/js/custom-gallery.js', __FILE__),
        array('wp-blocks', 'wp-element'),
        '1.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'custom_gallery_block_scripts');

function custom_gallery_frontend_scripts()
{
    wp_enqueue_script(
        'custom-gallery-frontend',
        plugins_url('/js/custom-gallery.js', __FILE__),
        array(),
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'custom_gallery_frontend_scripts');
