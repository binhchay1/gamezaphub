<?php
/**
 * SVG Loader Functions
 * Load SVG icons from separate files
 */

function getStoreSVG($slug) {
    $svgPath = __DIR__ . '/svg/stores/' . $slug . '.svg';
    
    if (file_exists($svgPath)) {
        $svgContent = file_get_contents($svgPath);
        // Remove XML declaration and comments if any
        $svgContent = preg_replace('/<\?xml[^>]*\?>/', '', $svgContent);
        $svgContent = preg_replace('/<!--.*?-->/s', '', $svgContent);
        return trim($svgContent);
    }
    
    // Fallback SVG if file not found
    return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>';
}

function getPlatformSVG($slug) {
    $svgPath = __DIR__ . '/svg/platforms/' . $slug . '.svg';
    
    if (file_exists($svgPath)) {
        $svgContent = file_get_contents($svgPath);
        // Remove XML declaration and comments if any
        $svgContent = preg_replace('/<\?xml[^>]*\?>/', '', $svgContent);
        $svgContent = preg_replace('/<!--.*?-->/s', '', $svgContent);
        return trim($svgContent);
    }
    
    // Fallback SVG if file not found
    return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>';
}
?>
