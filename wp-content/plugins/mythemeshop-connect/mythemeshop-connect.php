<?php
/**
 * Plugin Name: MyThemeShop Theme & Plugin Updater
 * Plugin URI: https://mythemeshop.com
 * Description: Update MyThemeShop themes & plugins right from your WordPress dashboard.
 * Version: 3.0.7
 * Author: MyThemeShop
 * Author URI: https://mythemeshop.com
 * License: GPLv2
 * MTS Product Type: Free
 *
 * @package MyThemeShop_Connect
 */

use MyThemeShop_Connect\Core;

defined( 'ABSPATH' ) || die;

/* Sets the plugin version constant. */
define( 'MTS_CONNECT_VERSION', '3.0.7' );

/* Sets the plugin slug constant. */
define( 'MTS_CONNECT_PLUGIN_FILE', 'mythemeshop-connect/mythemeshop-connect.php' );

/* Sets the path to the plugin directory. */
define( 'MTS_CONNECT_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Sets the path to the plugin directory URI. */
define( 'MTS_CONNECT_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/* Sets the path to the `includes` directory. */
define( 'MTS_CONNECT_INCLUDES', MTS_CONNECT_DIR . trailingslashit( 'includes' ) );

/* Sets the path to the `assets` directory. */
define( 'MTS_CONNECT_ASSETS', MTS_CONNECT_URI . 'assets/' );

/* We're here. */
define( 'MTS_CONNECT_ACTIVE', true );

/* Dependencies. */
$files = array(
	'ajax',
	'checker',
	'compatibility',
	'notifications',
	'plugin-checker',
	'settings',
	'theme-checker',

	// Legacy classes for backwards compatibility.
	'mts-connector',
	'mts-connection',
);
foreach ( $files as $file ) {
	require_once MTS_CONNECT_INCLUDES . 'class-' . $file . '.php';
}

/* Require main class. */
require_once MTS_CONNECT_INCLUDES . 'class-core.php';

/* Hook init. */
add_action( 'plugins_loaded', 'mythemeshop_connect_init' );
function mythemeshop_connect_init() {
	$mts_connection = Core::get_instance();
}
