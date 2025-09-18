<?php
/**
 * Code responsible to check plugins on mythemeshop.com.
 *
 * @since      3.0
 * @package    MyThemeShop_Connect
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */

namespace MyThemeShop_Connect;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin_Checker class.
 */
class Plugin_Checker extends Checker {

	public function __construct() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_plugin_updates' ) );

		parent::__construct();
	}

	public function update_plugins_now() {
		if ( $transient = get_site_transient( 'update_plugins' ) ) {
			delete_site_transient( 'mts_update_plugins' );
			delete_site_transient( 'mts_update_plugins_no_access' );
			set_site_transient( 'update_plugins', $transient );
		}
	}


	public function check_plugin_updates( $update_transient ) {
		global $wp_version;

		if ( ! isset( $update_transient->checked ) ) {
			return $update_transient;
		} else {
			$plugins = $update_transient->checked;
		}

		unset( $plugins['seo-by-rank-math/rank-math.php'] );
		unset( $plugins['seo-by-rank-math-pro/rank-math-pro.php'] );

		$mts_updates = get_site_transient( 'mts_update_plugins' );
		if ( ! $this->needs_check_now( $mts_updates ) ) {
			// Add plugins from our transient
			if ( isset( $mts_updates->response ) ) {
				foreach ( $mts_updates->response as $plugin => $data ) {
					if ( array_key_exists( $plugin, $plugins ) && isset( $data->new_version ) && version_compare( $plugins[ $plugin ], $data->new_version, '<' ) ) {
						$update_transient->response[ $plugin ] = $data;
					}
				}
			}
			return $update_transient;
		}

		if ( ! empty( $_GET['disconnect'] ) ) {
			return $update_transient;
		}

		$sites_plugins = array();
		if ( is_multisite() ) {
			// get list of sites using this theme
			$sites                   = get_sites();
			$_network_active_plugins = wp_get_active_network_plugins();
			$network_active_plugins  = array();
			foreach ( $_network_active_plugins as $plugin ) {
				$network_active_plugins[] = basename( dirname( $plugin ) ) . '/' . basename( $plugin );
			}
			foreach ( $sites as $i => $site_obj ) {
				$siteurl = $site_obj->siteurl;
				switch_to_blog( $site_obj->blog_id );
				// $_plugins = get_option('active_plugins');
				$_plugins     = get_option( 'active_plugins' );
				$site_plugins = array();
				foreach ( (array) $_plugins as $plugin ) {
					$site_plugins[] = $plugin;
				}
				restore_current_blog();

				$sites_plugins[ $siteurl ] = array_merge( $network_active_plugins, $site_plugins );
			}
		}

		foreach ( $plugins as $plugin_file => $plugin_version ) {
			// Skip selected plugins
			if ( ! apply_filters( 'mts_connect_update_plugin_' . $plugin_file, true, $plugin_version ) ) {
				unset( $plugins[ $plugin_file ] );
				continue;
			}
		}

		$r           = 'check_plugins';
		$send_to_api = array(
			'plugins' => $plugins,
			'info'    => array(
				'url'            => is_multisite() ? network_site_url() : home_url(),
				'multisite'      => is_multisite(),
				'sites'          => $sites_plugins,
				'php_version'    => phpversion(),
				'wp_version'     => $wp_version,
				'plugin_version' => MTS_CONNECT_VERSION,
			),
		);

		// is connected
		if ( Core::is_connected() ) {
			$send_to_api['user'] = Core::get_instance()->connect_data['username'];
			$send_to_api['key']  = Core::get_instance()->connect_data['api_key'];
		} else {
			$r = 'guest/' . $r;
		}

		$options = array(
			'timeout' => ( ( defined( 'DOING_CRON' ) && DOING_CRON ) ? 30 : 10 ),
			'body'    => $send_to_api,
		);

		$last_update    = new \stdClass();
		$no_access      = new \stdClass();
		$plugin_request = wp_remote_post( $this->api_url . $r, $options );

		if ( ! is_wp_error( $plugin_request ) && wp_remote_retrieve_response_code( $plugin_request ) == 200 ) {
			$plugin_response = json_decode( wp_remote_retrieve_body( $plugin_request ), true );

			if ( ! empty( $plugin_response ) ) {
				if ( ! empty( $plugin_response['plugins'] ) ) {
					if ( empty( $update_transient->response ) ) {
						$update_transient->response = array();
					}

					// array to object
					$new_arr = array();
					foreach ( $plugin_response['plugins'] as $pluginname => $plugindata ) {
						$object = new \stdClass();
						foreach ( $plugindata as $k => $v ) {
							$object->$k = $v;
						}
						$new_arr[ $pluginname ] = $object;
					}
					$plugin_response['plugins'] = $new_arr;

					$update_transient->response = array_merge( (array) $update_transient->response, (array) $plugin_response['plugins'] );
				}

				$last_update->checked = $plugins;

				if ( ! empty( $plugin_response['plugins'] ) ) {
					$last_update->response = $plugin_response['plugins'];
				} else {
					$last_update->response = array();
				}

				if ( ! empty( $plugin_response['plugins_no_access'] ) ) {
					$no_access->response = $plugin_response['plugins_no_access'];
				} else {
					$no_access->response = array();
				}

				if ( ! empty( $plugin_response['notices'] ) ) {
					foreach ( $plugin_response['notices'] as $notice ) {
						if ( ! empty( $notice['network_notice'] ) ) {
							Core::get( 'notifications' )->add_network_notice( (array) $notice );
						} else {
							Core::get( 'notifications' )->add_sticky_notice( (array) $notice );
						}
					}
				}

				if ( ! empty( $plugin_response['disconnect'] ) ) {
					Core::get_instance()->disconnect();
				}
			}
		}

		$last_update->last_checked = time();
		set_site_transient( 'mts_update_plugins', $last_update );
		set_site_transient( 'mts_update_plugins_no_access', $no_access );

		return $update_transient;
	}
}
