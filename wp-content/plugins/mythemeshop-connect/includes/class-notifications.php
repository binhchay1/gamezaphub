<?php
/**
 * Admin notices.
 *
 * @since      3.0
 * @package    MyThemeShop_Connect
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */

namespace MyThemeShop_Connect;

defined( 'ABSPATH' ) || exit;

/**
 * Notifications class.
 */
class Notifications {

	private $notices_option = 'mts_connect_notices';

	protected $notices        = array();
	protected $sticky_notices = array();

	protected $notice_defaults = array();
	protected $notice_tags     = array();

	private $dismissed_meta = 'mts_connect_dismissed_notices';

	public function __construct() {
		// Notices default options
		$this->notice_defaults = array(
			'content'  => '',
			'class'    => 'updated notice-success',
			'priority' => 10,
			'sticky'   => false,
			'date'     => time(),
			'expire'   => time() + 7 * DAY_IN_SECONDS,
			'context'  => array(),
		);

		// Show notices.
		if ( is_multisite() ) {
			add_action( 'network_admin_notices', array( $this, 'show_notices' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'show_notices' ) );
		}

		// User has dismissed a notice?
		add_action( 'admin_init', array( $this, 'dismiss_notices' ) );
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	public function init() {
		$this->sticky_notices = $this->get_notices();

		$current_user = \wp_get_current_user();
		// Tags to use in notifications.
		$this->notice_tags = array(
			'[logo_url]'       => MTS_CONNECT_ASSETS . 'img/mythemeshop-logo.png',
			'[plugin_url]'     => network_admin_url( 'admin.php?page=mts-connect' ),
			'[themes_url]'     => network_admin_url( 'themes.php' ),
			'[plugins_url]'    => network_admin_url( 'plugins.php' ),
			'[updates_url]'    => network_admin_url( 'update-core.php' ),
			'[site_url]'       => site_url(),
			'[user_firstname]' => $current_user->first_name,
		);
	}

	public function reset_notices() {
		$notices = $this->notices + $this->sticky_notices;
		foreach ( $notices as $id => $notice ) {
			$this->remove_notice( $id );
			$this->undismiss_notice( $id );
		}
	}

	public function get_notices() {
		$notices = get_site_option( $this->notices_option );
		if ( empty( $notices ) ) {
			$notices = array();
		}
		return $notices;
	}

	/**
	 * add_notice()
	 * $args:
	 * - content: notice content text or HTML
	 * - class: notice element class attribute, possible values are 'updated' (default), 'error', 'update-nag', 'mts-network-notice'
	 * - priority:  default 10
	 * - date: date of notice as UNIX timestamp
	 * - expire: expiry date as UNIX timestamp. Notice is removed and "undissmissed" ater expiring
	 * - (array) context:
	 *      - screen: admin page id where the notice should appear, eg. array('themes', 'themes-network')
	 *      - connected (bool): check if plugin have this setting
	 *      - themes (array): list of themes in format: array('name' => 'magxp', 'version' => '1.0', 'compare' => '='), array(...)
	 *
	 * @return
	 */
	public function add_notice( $args ) {
		if ( Core::get_instance()->invisible_mode ) {
			return;
		}

		if ( empty( $args ) ) {
			return;
		}

		if ( is_string( $args ) && ! strstr( $args, 'content=' ) ) {
			$args = array( 'content' => $args ); // $this->add_notice('instant content!');
		}

		$args = wp_parse_args( $args, $this->notice_defaults );

		if ( empty( $args['content'] ) ) {
			return;
		}

		$id = ( empty( $args['id'] ) ? md5( $args['content'] ) : $args['id'] );
		unset( $args['id'] );

		if ( $args['sticky'] ) {
			if ( ! empty( $args['overwrite'] ) || ( empty( $args['overwrite'] ) && empty( $this->sticky_notices[ $id ] ) ) ) {
				$this->sticky_notices[ $id ] = $args;
				$this->update_notices();
			}
		} else {
			$this->notices[ $id ] = $args;
		}
	}


	public function add_sticky_notice( $args ) {
		$args           = wp_parse_args( $args, array() );
		$args['sticky'] = 1;
		$this->add_notice( $args );
	}

	// Network notices are additional API messages (news and offers) that can be switched off with an option
	public function add_network_notice( $args ) {
		if ( ! empty( Core::get_setting( 'network_notices' ) ) ) {
			$args['network_notice'] = 1;
			$this->add_sticky_notice( $args );
		}
	}

	protected function update_notices() {
		update_site_option( $this->notices_option, $this->sticky_notices );
	}
	public function show_notices() {
		if ( Core::get_instance()->invisible_mode ) {
			return;
		}

		global $current_user;
		$user_id = $current_user->ID;

		$ui_access_type = Core::get_setting( 'ui_access_type' );
		$ui_access_role = Core::get_setting( 'ui_access_role' );
		$ui_access_user = Core::get_setting( 'ui_access_user' );

		$admin_page_role    = 'manage_options';
		$allow_admin_access = false;
		if ( $ui_access_type == 'role' ) {
			$allow_admin_access = current_user_can( $ui_access_role );
		} else { // ui_access_type = user (IDs)
			$allow_admin_access = in_array( $user_id, array_map( 'absint', explode( ',', $ui_access_user ) ) );
		}

		$allow_admin_access = apply_filters( 'mts_connect_admin_access', $allow_admin_access );

		if ( ! $allow_admin_access ) {
			return;
		}

		$notices = $this->notices + $this->sticky_notices;
		uasort( $notices, array( $this, 'sort_by_priority' ) );
		$multiple_notices = false;
		$thickbox         = 0;

		// update-themes class notice: show only the latest
		$update_notice = array();
		$unset_notices = array();
		foreach ( $notices as $id => $notice ) {
			if ( strpos( $notice['class'], 'update-themes' ) !== false ) {
				if ( empty( $update_notice ) ) {
					$update_notice = array(
						'id'   => $id,
						'date' => $notice['date'],
					);
				} else {
					// check if newer
					if ( $notice['date'] < $update_notice['date'] ) {
						$unset_notices[] = $id; // unset this one, there's a newer
					} else {
						// newer: store this one
						$unset_notices[] = $update_notice['id'];
						$update_notice   = array(
							'id'   => $id,
							'date' => $notice['date'],
						);
					}
				}
			}
		}

		// update-plugins class notice: show only the latest
		$update_notice = array();
		foreach ( $notices as $id => $notice ) {
			if ( strpos( $notice['class'], 'update-plugins' ) !== false ) {
				if ( empty( $update_notice ) ) {
					$update_notice = array(
						'id'   => $id,
						'date' => $notice['date'],
					);
				} else {
					// check if newer
					if ( $notice['date'] < $update_notice['date'] ) {
						$unset_notices[] = $id; // unset this one, there's a newer
					} else {
						// newer: store this one
						$unset_notices[] = $update_notice['id'];
						$update_notice   = array(
							'id'   => $id,
							'date' => $notice['date'],
						);
					}
				}
			}
		}

		foreach ( $notices as $id => $notice ) {
			// expired
			if ( $notice['expire'] < time() ) {
				$this->remove_notice( $id );
				$this->undismiss_notice( $id );
				continue;
			}

			// scheduled
			if ( $notice['date'] > time() ) { // ['date'] is in the future
				continue;
			}

			// sticky & dismissed
			if ( $notice['sticky'] ) {
				$dismissed = get_user_meta( $user_id, $this->dismissed_meta, true );
				if ( empty( $dismissed ) ) {
					$dismissed = array();
				}
				if ( in_array( $id, $dismissed ) ) {
					continue;
				}
			}

			// network notice and disabled
			if ( ! empty( $notice['network_notice'] ) && empty( Core::get_setting( 'network_notices' ) ) ) {
				continue;
			}
			// base notice and disabled
			if ( empty( $notice['network_notice'] ) && empty( Core::get_setting( 'update_notices' ) ) ) {
				continue;
			}

			// update notice and disabled
			$is_update_notice = ( strpos( $notice['class'], 'update-themes' ) !== false || strpos( $notice['class'], 'update-plugins' ) !== false );
			if ( empty( Core::get_setting( 'update_notices' ) ) && $is_update_notice ) {
				continue;
			}

			// context: connected
			if ( isset( $notice['context']['connected'] ) ) {
				if ( ( ! $notice['context']['connected'] && Core::get_instance()->connect_data['connected'] )
					|| ( $notice['context']['connected'] && ! Core::get_instance()->connect_data['connected'] ) ) {
					continue; // skip this
				}
			}

			// context: screen
			if ( isset( $notice['context']['screen'] ) ) {
				if ( ! is_array( $notice['context']['screen'] ) ) {
					$notice['context']['screen'] = array( $notice['context']['screen'] );
				}
				$is_targeted_page = false;
				$screen           = get_current_screen();
				foreach ( $notice['context']['screen'] as $page ) {
					if ( $screen->id == $page ) {
						$is_targeted_page = true;
					}
				}
				if ( ! $is_targeted_page ) {
					continue; // skip if not targeted
				}
			}

			// context: themes
			if ( isset( $notice['context']['themes'] ) ) {
				if ( is_string( $notice['context']['themes'] ) ) {
					$notice['context']['themes'] = array( array( 'name' => $notice['context']['themes'] ) );
				}

				$themes    = wp_get_themes();
				$wp_themes = array();
				foreach ( $themes as $theme ) {
					$name               = $theme->get_stylesheet();
					$wp_themes[ $name ] = $theme->get( 'Version' );
				}

				$required_themes_present = true;
				foreach ( $notice['context']['themes'] as $theme ) {
					// 1. check if theme exists
					if ( ! array_key_exists( $theme['name'], $wp_themes ) ) {
						// Check for mts_ version of theme folder
						if ( array_key_exists( 'mts_' . $theme['name'], $wp_themes ) ) {
							$theme['name'] = 'mts_' . $theme['name'];
						} else {
							$required_themes_present = false;
							break; // theme doesn't exist - skip notice
						}
					}
					// 2. compare theme version
					if ( isset( $theme['version'] ) ) {
						if ( empty( $theme['compare'] ) ) {
							$theme['compare'] = '='; // compare with EQUALS by default
						}

						if ( ! version_compare( $wp_themes[ $theme['name'] ], $theme['version'], $theme['compare'] ) ) {
							$required_themes_present = false;
							break; // theme version check fails - skip
						}
					}
				}
				if ( ! $required_themes_present ) {
					continue;
				}
			}

			// context: plugins
			if ( isset( $notice['context']['plugins'] ) ) {
				if ( is_string( $notice['context']['plugins'] ) ) {
					$notice['context']['plugins'] = array( array( 'name' => $notice['context']['plugins'] ) );
				}

				$plugins    = get_plugins();
				$wp_plugins = array();
				foreach ( $plugins as $plugin_name => $plugin_info ) {
					$name                   = explode( '/', $plugin_name );
					$wp_plugins[ $name[0] ] = $plugin_info['Version'];
				}

				$required_plugins_present = true;
				foreach ( $notice['context']['plugins'] as $plugin ) {
					// 1. check if plugin exists
					if ( ! array_key_exists( $plugin['name'], $wp_plugins ) ) {
						$required_plugins_present = false;
						break; // plugin doesn't exist - skip notice
					}
					// 2. compare plugin version
					if ( isset( $plugin['version'] ) ) {
						if ( empty( $plugin['compare'] ) ) {
							$plugin['compare'] = '='; // compare with EQUALS by default
						}

						if ( ! version_compare( $wp_plugins[ $plugin['name'] ], $plugin['version'], $plugin['compare'] ) ) {
							$required_plugins_present = false;
							break; // plugin version check fails - skip
						}
					}
				}
				if ( ! $required_plugins_present ) {
					continue;
				}
			}

			// skip $unset_notices
			if ( in_array( $id, $unset_notices ) ) {
				continue;
			}

			if ( ! $thickbox ) {
				add_thickbox();
				$thickbox = 1; }

			// wrap plaintext content in <p>
			// assumes text if first char != '<'
			if ( substr( trim( $notice['content'] ), 0, 1 ) != '<' ) {
				$notice['content'] = '<p>' . $notice['content'] . '</p>';
			}

			// insert notice tags
			foreach ( $this->notice_tags as $tag => $value ) {
				$notice['content'] = str_replace( $tag, $value, $notice['content'] );
			}

			if ( strpos( $notice['class'], 'update-nag' ) !== false && strpos( $notice['class'], 'update-warning' ) === false ) {
				$notice['class'] .= ' notice-warning';
			}

			echo '<div class="' . $notice['class'] . ( $notice['sticky'] ? ' mts-connect-sticky' : '' ) . ' mts-connect-notice notice" id="notice_' . $id . '">';
			echo $notice['content'];
			echo '<a href="' . esc_url( add_query_arg( 'mts_dismiss_notice', $id ) ) . '" class="dashicons dashicons-dismiss mts-notice-dismiss-icon" title="' . __( 'Dissmiss Notice' ) . '"></a>';
			echo '<a href="' . esc_url( add_query_arg( 'mts_dismiss_notice', 'dismiss_all' ) ) . '" class="dashicons dashicons-dismiss mts-notice-dismiss-all-icon" title="' . __( 'Dissmiss All Notices' ) . '"></a>';
			echo '</div>';
			$multiple_notices = true;
		}

	}

	public function dismiss_notices() {
		if ( ! empty( $_GET['mts_dismiss_notice'] ) && is_string( $_GET['mts_dismiss_notice'] ) ) {
			if ( $_GET['mts_dismiss_notice'] == 'dismiss_all' ) {
				foreach ( $this->sticky_notices as $id => $notice ) {
					$this->dismiss_notice( $id );
				}
			} else {
				$this->dismiss_notice( $_GET['mts_dismiss_notice'] );
			}
		}
	}

	public function dismiss_notice( $id ) {
		global $current_user;
		$user_id   = $current_user->ID;
		$dismissed = get_user_meta( $user_id, $this->dismissed_meta, true );
		if ( is_string( $dismissed ) ) {
			$dismissed = array( $dismissed );
		}
		if ( ! in_array( $id, $dismissed ) ) {
			$dismissed[] = $id;
			update_user_meta( $user_id, $this->dismissed_meta, $dismissed );
		}
	}

	public function undismiss_notice( $id ) {
		global $current_user;
		$user_id   = $current_user->ID;
		$dismissed = get_user_meta( $user_id, $this->dismissed_meta, true );
		if ( is_string( $dismissed ) ) {
			$dismissed = array( $dismissed );
		}
		if ( $key = array_search( $id, $dismissed ) ) {
			unset( $dismissed[ $key ] );
			update_user_meta( $user_id, $this->dismissed_meta, $dismissed );
		}
	}

	public function sort_by_priority( $a, $b ) {
		if ( $a['priority'] == $b['priority'] ) {
			return 1;
		}
		return $a['priority'] - $b['priority'];
	}

	public function error_message( $msg ) {
		$this->add_notice(
			array(
				'content' => $msg,
				'class'   => 'error notice-error',
			)
		);
	}

	public function remove_notice( $id ) {
		unset( $this->notices[ $id ], $this->sticky_notices[ $id ] );
		$this->update_notices();
	}

}
