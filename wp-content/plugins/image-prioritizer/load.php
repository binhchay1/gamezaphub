<?php
/**
 * Plugin Name: Image Prioritizer
 * Plugin URI: https://github.com/WordPress/performance/tree/trunk/plugins/image-prioritizer
 * Description: Prioritizes the loading of images and videos based on how visible they are to actual visitors; adds <code>fetchpriority</code> and applies lazy-loading.
 * Requires at least: 6.6
 * Requires PHP: 7.2
 * Requires Plugins: optimization-detective
 * Version: 1.0.0-beta2
 * Author: WordPress Performance Team
 * Author URI: https://make.wordpress.org/performance/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: image-prioritizer
 *
 * @package image-prioritizer
 */

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// @codeCoverageIgnoreEnd

(
	/**
	 * Register this copy of the plugin among other potential copies embedded in plugins or themes.
	 *
	 * @param string  $global_var_name Global variable name for storing the plugin pending loading.
	 * @param string  $version         Version.
	 * @param Closure $load            Callback that loads the plugin.
	 */
	static function ( string $global_var_name, string $version, Closure $load ): void {
		if ( ! isset( $GLOBALS[ $global_var_name ] ) ) {
			$bootstrap = static function () use ( $global_var_name ): void {
				if (
					isset( $GLOBALS[ $global_var_name ]['load'], $GLOBALS[ $global_var_name ]['version'] )
					&&
					$GLOBALS[ $global_var_name ]['load'] instanceof Closure
					&&
					is_string( $GLOBALS[ $global_var_name ]['version'] )
				) {
					call_user_func( $GLOBALS[ $global_var_name ]['load'], $GLOBALS[ $global_var_name ]['version'] );
					unset( $GLOBALS[ $global_var_name ] );
				}
			};

			/*
			 * Wait until after the plugins have loaded and the theme has loaded. The after_setup_theme action could be
			 * used since it is the first action that fires once the theme is loaded. However, plugins may embed this
			 * logic inside a module which initializes even later at the init action. The earliest action that this
			 * plugin has hooks for is the init action at the default priority of 10 (which includes the rest_api_init
			 * action), so this is why it gets initialized at priority 9.
			 */
			add_action( 'init', $bootstrap, 9 );
		}

		// Register this copy of the plugin.
		if (
			// Register this copy if none has been registered yet.
			! isset( $GLOBALS[ $global_var_name ]['version'] )
			||
			// Or register this copy if the version greater than what is currently registered.
			version_compare( $version, $GLOBALS[ $global_var_name ]['version'], '>' )
			||
			// Otherwise, register this copy if it is actually the one installed in the directory for plugins.
			rtrim( WP_PLUGIN_DIR, '/' ) === dirname( __DIR__ )
		) {
			$GLOBALS[ $global_var_name ]['version'] = $version;
			$GLOBALS[ $global_var_name ]['load']    = $load;
		}
	}
)(
	'image_prioritizer_pending_plugin',
	'1.0.0-beta2',
	static function ( string $version ): void {
		if ( defined( 'IMAGE_PRIORITIZER_VERSION' ) ) {
			return;
		}

		define( 'IMAGE_PRIORITIZER_VERSION', $version );

		require_once __DIR__ . '/helper.php';
		require_once __DIR__ . '/hooks.php';
	}
);
