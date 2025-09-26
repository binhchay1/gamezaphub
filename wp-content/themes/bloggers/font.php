<?php
/*--------------------------------------------------------------------*/
/*     Register Google Fonts
/*--------------------------------------------------------------------*/

// Google Fonts are now loaded directly in header.php for better performance
// This function is disabled to prevent duplicate font loading
function bloggers_fonts_url() {
    return '';
}

function bloggers_scripts_styles() {
    // Fonts are loaded directly in header.php with preconnect and preload optimization
    // No need to enqueue fonts here to avoid duplicate loading
}
// Commented out to prevent duplicate font loading
// add_action( 'wp_enqueue_scripts', 'bloggers_scripts_styles' );