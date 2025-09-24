<?php

/**
 * Plugin Name: Lasso
 * Plugin URI: https://getlasso.co

 * Description: Lasso lets you add, manage, and beautifully display affiliate links from any network, including Amazon Associates and more.

 * Author: Lasso
 * Author URI: https://getlasso.co

 * Version: 324

 * Text Domain: lasso-urls
 * Domain Path: /languages

 * License: GNU General Public License v2.0 (or later)
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 *
 * @package Lasso
 */

use Lasso\Classes\Deactivator as Lasso_Deactivator;
use Lasso\Classes\Activator as Lasso_Activator;

define('LASSO_VERSION', '324');

if (! defined('WPINC')) {
	die;
}

define('LASSO_PLUGIN_MAIN_FILE', __FILE__);
define('LASSO_PLUGIN_PATH', __DIR__);
define('LASSO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LASSO_PLUGIN_BASE_NAME', plugin_basename(__FILE__));
require_once LASSO_PLUGIN_PATH . '/admin/lasso-constant.php';
require_once LASSO_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'autoload.php';

require_once LASSO_PLUGIN_PATH . '/libs/lasso/lasso-polyfill.php';

define('SENTRY_LOADED', 'lasted');

/**
 * Do something when activate Lasso
 */
function activate_lasso_urls()
{
	$lasso_activator = new Lasso_Activator();
	$lasso_activator->init();
}

/**
 * Do something when deactivate Lasso
 */
function deactivate_lasso_urls()
{
	$lasso_deactivator = new Lasso_Deactivator();
	$lasso_deactivator->init();
}

register_activation_hook(__FILE__, 'activate_lasso_urls');
register_deactivation_hook(__FILE__, 'deactivate_lasso_urls');

require_once LASSO_PLUGIN_PATH . '/classes/class-lasso-init.php';
new Lasso_Init();

// Temporarily disabled to fix memory issue
// require_once LASSO_PLUGIN_PATH . '/libs/lasso/process-other-plugin.php';

// Temporarily disabled to fix memory issue
// if (file_exists(LASSO_PLUGIN_PATH . '/fixes/plugin-object-fix.php')) {
//	require_once LASSO_PLUGIN_PATH . '/fixes/plugin-object-fix.php';
// }

// Load Layout 6 Box CSS in admin
add_action('admin_enqueue_scripts', function () {
	$css_url = LASSO_PLUGIN_URL . '/admin/assets/css/layout-6-box.css';
	if (file_exists(LASSO_PLUGIN_PATH . '/admin/assets/css/layout-6-box.css')) {
		wp_enqueue_style('lasso-layout-6-box', $css_url, array(), '1.0');
	}
});

// Fix stdClass plugin property error
add_filter('all_plugins', function($plugins) {
	if (is_array($plugins)) {
		foreach ($plugins as $key => $plugin) {
			if (is_object($plugin) && !isset($plugin->plugin)) {
				if (is_array($plugin) && isset($plugin['Name'])) {
					// Convert array to object with plugin property
					$plugin_obj = (object) $plugin;
					$plugin_obj->plugin = $key;
					$plugins[$key] = $plugin_obj;
				}
			}
		}
	}
	return $plugins;
}, 10, 1);


add_action('activated_plugin', 'lasso_load_final');
/**
 * Check and change the order of plugins.
 */
function lasso_load_final()
{
	$path    = LASSO_PLUGIN_BASE_NAME;
	$plugins = get_option('active_plugins');
	if ($plugins) {
		$key = array_search($path, $plugins, true);
		if (false !== $key) {
			array_splice($plugins, $key, 1);
			array_push($plugins, $path);
			update_option('active_plugins', $plugins);
		}
	}
}

do_action('lasso_loaded');
