<?php
/**
 * Code responsible for AJAX functions.
 *
 * @since      3.0
 * @package    MyThemeShop_Connect
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */

namespace MyThemeShop_Connect;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax class.
 */
class Ajax {

	/**
	 * Constructor method.
	 */
	public function __construct() {
		add_action( 'wp_ajax_mts_connect', array( $this, 'connect' ) );
		add_action( 'wp_ajax_mts_connect_update_settings', array( $this, 'update_settings' ) );
		add_action( 'wp_ajax_mts_connect_check_themes', array( $this, 'check_themes' ) );
		add_action( 'wp_ajax_mts_connect_check_plugins', array( $this, 'check_plugins' ) );
		add_action( 'wp_ajax_mts_connect_reset_notices', array( $this, 'reset_notices' ) );
		add_action( 'wp_ajax_mts_connect_dismiss_notice', array( $this, 'dismiss_notices' ) );
	}

	/**
	 * AJAX handler for first step of connecting.
	 *
	 * @return void
	 */
	public function connect() {
		header( 'Content-type: application/json' );
		$output = array();
		$output['login'] = true;
		$output['auth_url'] = Core::get_instance()->auth_url;
		$output['auth_url'] = add_query_arg( array(
			'site' => rawurlencode( site_url() ),
			'r'    => rawurlencode( network_admin_url( 'admin.php?page=mts-connect' ) ),
		), $output['auth_url'] );

		// Add the URL itself for the login redirect param.
		$output['auth_url'] = add_query_arg( array(
			'mts_redirect_to' => rawurlencode( $output['auth_url'] ),
			'version' => MTS_CONNECT_VERSION,
		), $output['auth_url'] );

		echo wp_json_encode( $output );
		exit;
	}

	/**
	 * AJAX handler for theme check.
	 *
	 * @return void
	 */
	public function check_themes() {
		if ( ! current_user_can( 'update_themes' ) ) {
			return;
		}
		Core::get( 'theme_checker' )->update_themes_now();
		$transient = get_site_transient( 'mts_update_themes' );
		if ( is_object( $transient ) && isset( $transient->response ) ) {
			echo count( $transient->response );
		} else {
			echo '0';
		}

		exit;
	}

	/**
	 * AJAX handler for plugin check.
	 *
	 * @return void
	 */
	public function check_plugins() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}
		Core::get( 'plugin_checker' )->update_plugins_now();
		$transient = get_site_transient( 'mts_update_plugins' );
		if ( is_object( $transient ) && isset( $transient->response ) ) {
			echo count( $transient->response );
		} else {
			echo '0';
		}

		exit;
	}

	/**
	 * AJAX handler for settings update.
	 *
	 * @return void
	 */
	public function update_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		Core::get_instance()->set_settings( $_POST );
		Core::get_instance()->update_settings();

		exit;
	}

	/**
	 * AJAX handler for dismissing notices.
	 *
	 * @return void
	 */
	public function dismiss_notices() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! empty( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {
			foreach ( $_POST['ids'] as $id ) {
				Core::get( 'notifications' )->dismiss_notice( $id );
			}
		}
		exit;
	}

	/**
	 * AJAX handler for resetting all notices.
	 *
	 * @return void
	 */
	public function reset_notices() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		Core::get( 'notifications' )->reset_notices();
		exit;
	}
}
