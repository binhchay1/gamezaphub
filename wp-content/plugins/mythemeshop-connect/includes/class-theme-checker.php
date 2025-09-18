<?php
/**
 * Code responsible to check themes on mythemeshop.com.
 *
 * @since      3.0
 * @package    MyThemeShop_Connect
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */

namespace MyThemeShop_Connect;

defined( 'ABSPATH' ) || exit;

/**
 * Theme_Checker class.
 */
class Theme_Checker extends Checker {

	public function __construct() {
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_theme_updates' ) );

		parent::__construct();
	}

	public function update_themes_now() {
		if ( $transient = get_site_transient( 'update_themes' ) ) {
			delete_site_transient( 'mts_update_themes' );
			delete_site_transient( 'mts_update_themes_no_access' );
			set_site_transient( 'update_themes', $transient );
		}
	}

	public function check_theme_updates( $update_transient ) {
		global $wp_version;

		if ( ! isset( $update_transient->checked ) ) {
			return $update_transient;
		} else {
			$themes = $update_transient->checked;
		}

		if ( ! empty( $_GET['disconnect'] ) ) {
			return $update_transient;
		}

		// New 'mts_' folder structure
		$folders_fix = array();
		foreach ( $themes as $theme => $version ) {
			// Skip selected themes
			if ( ! apply_filters( 'mts_connect_update_theme_' . $theme, true, $version ) ) {
				unset( $themes[ $theme ] );
				continue;
			}

			if ( $theme == 'sociallyviral' && ! in_array( 'sociallyviral', $folders_fix ) ) {
				// SociallyViral free - exclude from API check
				unset( $themes[ $theme ] );
				continue;
			}

			if ( stripos( $theme, 'mts_' ) === 0 ) {
				$themes[ str_replace( 'mts_', '', $theme ) ] = $version;
				$folders_fix[]                               = str_replace( 'mts_', '', $theme );
				unset( $themes[ $theme ] );
			}
		}

		$mts_updates = get_site_transient( 'mts_update_themes' );
		if ( ! $this->needs_check_now( $mts_updates ) ) {
			// Add themes from our transient
			if ( isset( $mts_updates->response ) ) {
				foreach ( $mts_updates->response as $theme => $data ) {
					$folder_fix_theme = str_replace( 'mts_', '', $theme );
					if ( array_key_exists( $folder_fix_theme, $themes ) && isset( $data['new_version'] ) && version_compare( $themes[ $folder_fix_theme ], $data['new_version'], '<' ) ) {
						$update_transient->response[ $theme ] = $data;
					}
				}
			}
			return $update_transient;
		}

		$sites_themes = array();
		if ( is_multisite() ) {
			// get list of sites using this theme
			$sites = get_sites();
			foreach ( $sites as $i => $site_obj ) {
				$siteurl = $site_obj->siteurl;
				switch_to_blog( $site_obj->blog_id );
				$theme = get_template();
				restore_current_blog();

				$sites_themes[ $siteurl ] = $theme;
			}
		}

		$r           = 'check_themes';
		$send_to_api = array(
			'themes'   => $themes,
			'prefixed' => $folders_fix,
			'info'     => array(
				'url'            => home_url(),
				'multisite'      => is_multisite(),
				'sites'          => $sites_themes,
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

		$last_update   = new \stdClass();
		$no_access     = new \stdClass();
		$theme_request = wp_remote_post( $this->api_url . $r, $options );

		if ( ! is_wp_error( $theme_request ) && wp_remote_retrieve_response_code( $theme_request ) == 200 ) {
			$theme_response = json_decode( wp_remote_retrieve_body( $theme_request ), true );
			// print_r($theme_response);die();
			if ( ! empty( $theme_response ) ) {
				if ( ! empty( $theme_response['themes'] ) ) {
					if ( empty( $update_transient->response ) ) {
						$update_transient->response = array();
					}
					$update_transient->response = array_merge( (array) $update_transient->response, (array) $theme_response['themes'] );
				}
				$last_update->checked = $themes;

				if ( ! empty( $theme_response['themes'] ) ) {
					$last_update->response = $theme_response['themes'];
				} else {
					$last_update->response = array();
				}

				if ( ! empty( $theme_response['themes_no_access'] ) ) {
					$no_access->response = $theme_response['themes_no_access'];
				} else {
					$no_access->response = array();
				}

				if ( ! empty( $theme_response['notices'] ) ) {
					foreach ( $theme_response['notices'] as $notice ) {
						if ( ! empty( $notice['network_notice'] ) ) {
							Core::get( 'notifications' )->add_network_notice( (array) $notice );
						} else {
							Core::get( 'notifications' )->add_sticky_notice( (array) $notice );
						}
					}
				}

				if ( ! empty( $theme_response['disconnect'] ) ) {
					Core::get_instance()->disconnect();
				}
			}
		}

		$last_update->last_checked = time();
		set_site_transient( 'mts_update_themes', $last_update );
		set_site_transient( 'mts_update_themes_no_access', $no_access );

		return $update_transient;
	}
}
