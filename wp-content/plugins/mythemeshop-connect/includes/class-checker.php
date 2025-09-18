<?php
/**
 * Checker base class for theme and plugin checkers.
 *
 * @since      3.0
 * @package    MyThemeShop_Connect
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */

namespace MyThemeShop_Connect;

defined( 'ABSPATH' ) || exit;

/**
 * Checker class.
 */
class Checker {
	/**
	 * API endpoint URL.
	 *
	 * @var object
	 */
	public $api_url = 'https://mythemeshop.com/mtsapi/v1/';

	/**
	 * Dummy constructor method.
	 */
	public function __construct() {

	}

	/**
	 * Determine if we need to check for updates or not.
	 *
	 * @param  mixed $updates_data Updates data.
	 * @return bool                Whether checking is necessary.
	 */
	public function needs_check_now( $updates_data ) {
		return apply_filters( 'mts_connect_needs_check', true, $updates_data, get_class( $this ) );
	}
}
