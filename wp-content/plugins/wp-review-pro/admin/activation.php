<?php
/**
 * Plugin activation
 *
 * @package WP_Review
 */

/**
 * Does something after activation.
 */
function wp_review_after_activation() {
	// Check for Connect plugin version > 1.4.
	if ( class_exists( 'mts_connection' ) && defined( 'MTS_CONNECT_ACTIVE' ) && MTS_CONNECT_ACTIVE ) {
		return;
	}
	$plugin_path = 'mythemeshop-connect/mythemeshop-connect.php';

	// Check if plugin exists.
	$plugins = get_plugins();
	if ( ! array_key_exists( $plugin_path, $plugins ) ) {
		// auto-install it.
		include_once ABSPATH . 'wp-admin/includes/misc.php';
		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		$skin        = new Automatic_Upgrader_Skin();
		$upgrader    = new Plugin_Upgrader( $skin );
		$plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';
		$result      = $upgrader->install( $plugin_file );
		// If install fails then revert to previous theme.
		if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
			switch_theme( $oldtheme->stylesheet );
			return false;
		}
	} else {
		// Plugin is already installed, check version.
		$ver = isset( $plugins[ $plugin_path ]['Version'] ) ? $plugins[ $plugin_path ]['Version'] : '1.0';
		if ( version_compare( $ver, '1.4' ) === -1 ) {
			// Update if < 1.4.
			include_once ABSPATH . 'wp-admin/includes/misc.php';
			include_once ABSPATH . 'wp-admin/includes/file.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			$skin     = new Automatic_Upgrader_Skin();
			$upgrader = new Plugin_Upgrader( $skin );

			add_filter( 'pre_site_transient_update_plugins', 'wp_review_inject_connect_repo', 10, 2 );
			$result = $upgrader->upgrade( $plugin_path );
			remove_filter( 'pre_site_transient_update_plugins', 'wp_review_inject_connect_repo' );

			// If update fails then revert to previous theme.
			if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
				switch_theme( $oldtheme->stylesheet );
				return false;
			}
		}
	}

	// auto-activate Connect.
	$activate = activate_plugin( $plugin_path );
}

/**
 * Injects connect repo.
 *
 * @param mixed $pre       Pre.
 * @param mixed $transient Transient.
 * @return object
 */
function wp_review_inject_connect_repo( $pre, $transient ) {
	$plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';

	$return = new stdClass();

	$return->response = array();

	$return->response['mythemeshop-connect/mythemeshop-connect.php']          = new stdClass();
	$return->response['mythemeshop-connect/mythemeshop-connect.php']->package = $plugin_file;
	return $return;
}
wp_review_after_activation();
