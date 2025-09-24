<?php
/*--------------------------------------------------------------------*/
/*     Register Google Fonts
/*--------------------------------------------------------------------*/

function bloggers_fonts_url() {
    $fonts_url = '';

    $font_families = array(
        'Playfair Display:300,400,500,600,700,800,900',
        'Quattrocento Sans:400,500,700',
    );

    $query_args = array(
        'family' => urlencode( implode( '|', $font_families ) ),
        'subset' => urlencode( 'latin,latin-ext' ),
    );

    $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

    return $fonts_url;
}

function bloggers_scripts_styles() {
    wp_enqueue_style( 'bloggers-fonts', bloggers_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'bloggers_scripts_styles' );