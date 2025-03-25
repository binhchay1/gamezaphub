<?php

function add_owl_css_to_all_editors()
{
    $owl_js = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';
    $owl_css = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css';

    wp_enqueue_script('custom-owl-js', get_template_directory_uri() . '/assets/js/owl-custom.js', array('jquery', 'owl-frontend-js'), '1.0', true);
    wp_enqueue_style('owl-editor-css', $owl_css, array(), '2.3.4');
    wp_enqueue_script('owl-editor-css', $owl_js, array('jquery'), '2.3.4');
}
add_action('enqueue_block_editor_assets', 'add_owl_css_to_all_editors');

function add_owl_assets_to_frontend()
{
    $owl_js = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';
    $owl_css = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css';

    wp_enqueue_script('custom-owl-js', get_template_directory_uri() . '/assets/js/owl-custom.js', array('jquery', 'owl-frontend-js'), '1.0', true);
    wp_enqueue_style('owl-frontend-css', $owl_css, array(), '2.3.4');
    wp_enqueue_script('owl-frontend-js', $owl_js, array('jquery'), '2.3.4', true);

    if (get_query_var('custom_games') == 1) {
        wp_enqueue_style('custom-single-games-css', get_template_directory_uri() . '/assets/css/single-games.css', array(), '1.0');
        wp_enqueue_script('custom-single-games-js', get_template_directory_uri() . '/assets/js/single-games.js', array('jquery'), '1.0');
    }
}
add_action('wp_enqueue_scripts', 'add_owl_assets_to_frontend');
