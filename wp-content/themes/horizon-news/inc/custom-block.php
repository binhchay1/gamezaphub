<?php

function custom_table_block_styles()
{
    wp_enqueue_style(
        'custom-table-styles',
        get_template_directory_uri() . '/assets/css/custom-table.css',
        array(),
        '1.0'
    );
}
add_action('enqueue_block_assets', 'custom_table_block_styles');

function custom_table_block_render($block_content, $block)
{
    if ($block['blockName'] === 'core/table') {
        $block_content = '<div class="custom-table-wrapper">' .
            str_replace(
                '<table>',
                '<table class="custom-table wp-block-table">',
                $block_content
            ) .
            '</div>';
    }
    return $block_content;
}
add_filter('render_block', 'custom_table_block_render', 10, 2);

function custom_gallery_block_scripts()
{
    wp_enqueue_script(
        'custom-gallery-block',
        get_template_directory_uri() . '/assets/js/custom-gallery.js',
        array(),
        '1.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'custom_gallery_block_scripts');

function custom_gallery_frontend_scripts()
{
    wp_enqueue_script(
        'custom-gallery-block',
        get_template_directory_uri() . '/assets/js/custom-gallery.js',
        array(),
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'custom_gallery_frontend_scripts');
