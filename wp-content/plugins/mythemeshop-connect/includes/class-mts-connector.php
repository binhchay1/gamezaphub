<?php
/**
 * Deprecated class. Defined only for backwards-compatibility, some MTS
 * products are checking if ( class_exists( 'MTS_Connector' ) ).
 *
 * @since      2.0
 * @package    MyThemeShop_Connect
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Legacy Connector class.
 */
class MTS_Connector {
	/**
	 * Connected status.
	 *
	 * @var bool
	 */
	public $connected = false;
}
