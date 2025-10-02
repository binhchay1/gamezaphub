<?php
/**
 * Clear WPMU DEV Update Cache
 * Run this file ONE TIME to force clear all WPMU DEV update notices
 * 
 * HOW TO USE:
 * 1. Upload to website root
 * 2. Visit: http://yourdomain.com/clear-wpmudev-cache.php
 * 3. DELETE this file after use
 */

// Security: Only run if accessed directly
if (!isset($_SERVER['HTTP_HOST'])) {
    die('Direct access not allowed');
}

// Load WordPress
require_once('wp-load.php');

// Check if user is admin
if (!current_user_can('administrator')) {
    die('You must be logged in as admin');
}

echo '<h1>Clearing WPMU DEV Update Cache...</h1>';
echo '<pre>';

// List of all WPMU DEV related transients
$wpmudev_transients = array(
    'wdp_un_updates_available',
    'wpmudev_updates_available',
    'wpmudev_updates_data',
    'wpmudev_dashboard_updates',
    'wpmudev_dashboard_api_response',
    'wpmudev_projects',
    '_site_transient_wpmudev_dashboard_updates',
    '_transient_wpmudev_updates_available'
);

$cleared = 0;

foreach ($wpmudev_transients as $transient) {
    // Try both site and regular transients
    if (delete_site_transient($transient)) {
        echo "✅ Deleted site transient: $transient\n";
        $cleared++;
    }
    
    if (delete_transient($transient)) {
        echo "✅ Deleted transient: $transient\n";
        $cleared++;
    }
}

// Clear WP core update transients
$core_transients = array(
    'update_plugins',
    'update_themes',
    'update_core'
);

foreach ($core_transients as $transient) {
    if (delete_site_transient($transient)) {
        echo "✅ Deleted core transient: $transient\n";
        $cleared++;
    }
}

// Clear all plugin update transients
global $wpdb;
$results = $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%wpmudev%'");
if ($results) {
    echo "✅ Deleted $results WPMU DEV options from database\n";
    $cleared += $results;
}

echo "\n";
echo "=====================================\n";
echo "Total cleared: $cleared items\n";
echo "=====================================\n\n";

echo "<h2>✅ DONE!</h2>";
echo "<p>Now go to WordPress Admin → Plugins and refresh the page.</p>";
echo "<p><strong>⚠️ IMPORTANT: Delete this file after use for security!</strong></p>";
echo "<p><a href='/wp-admin/plugins.php' style='background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin-top: 10px;'>Go to Plugins Page</a></p>";

echo '</pre>';
?>

