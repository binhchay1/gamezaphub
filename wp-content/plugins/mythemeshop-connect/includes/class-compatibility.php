<?php
/**
 * Compatibility code for MTS themes & plugins.
 *
 * @since      3.0
 * @package    MyThemeShop_Connect
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */

namespace MyThemeShop_Connect;

defined( 'ABSPATH' ) || exit;

/**
 * MTS_Theme_Compatibility class.
 */
class Compatibility {
	/**
	 * Check if any MTS theme is in use on the site.
	 *
	 * @var bool
	 */
	public $mts_theme_in_use = false;

	/**
	 * Get the number of MTS plugins in use on the site.
	 *
	 * @var int
	 */
	public $mts_plugins_in_use = 0;

	/**
	 * Hold custom messages for theme & plugin settings screens.
	 *
	 * @var array
	 */
	protected $custom_admin_messages = array();

	/**
	 * Nagging message.
	 *
	 * @var string
	 */
	protected $ngmsg = '';

	/**
	 * Constructor method.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'replace_admin_pages' ), 99 );

		add_filter( 'nhp-opts-sections', '__return_empty_array', 9, 1 );

		add_action( 'after_setup_theme', array( $this, 'ui' ), 22 );

		add_filter( 'plugins_loaded', array( $this, 'check_for_mts_plugins' ), 11 );

		add_filter( 'after_setup_theme', array( $this, 'check_for_mts_theme' ), 11 );

		add_filter( 'after_switch_theme', array( $this, 'clear_theme_check' ), 11 );

		add_action( 'init', array( $this, 'set_theme_defaults' ), -11, 1 );

		add_action( 'init', array( $this, 'init' ) );

		// Fix false wordpress.org update notifications.
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'fix_false_wp_org_theme_update_notification' ) );

		// Remove old notifications & connect menu.
		add_action( 'after_setup_theme', array( $this, 'after_theme' ) );

		$this->ngmsg = $this->str_convert( '596F75 206E65656420746F20 3C6120687265663D225B70 6C7567696E5F757 26C5D223E636F6E6E6563742 0776974 6820796F7572204D795468656D65536 86F70206163 636F756E743C2F613E2074 6F207573652 07468652063757272 656E74207468656D652 06F7220706C7567696E2E' );
	}

	/**
	 * Set theme defaults.
	 */
	public function set_theme_defaults() {
		if ( defined( 'MTS_THEME_NAME' ) ) {
			if ( ! get_option( MTS_THEME_NAME, false ) ) {
				remove_filter( 'nhp-opts-sections', '__return_empty_array', 9 );
				remove_filter( 'nhp-opts-sections', array( $this, 'nhp_sections' ), 10 );
			}
		}
	}

	/**
	 * Define theme constant.
	 *
	 * @return void
	 */
	public function init() {
		$the = 'the';
		define( 'MTS_THEME_T', 'mts' . $the );
	}

	/**
	 * Add hook to remove old "Theme Updates" page from older themes.
	 *
	 * @return void
	 */
	public function after_theme() {
		add_action( 'admin_menu', array( $this, 'remove_themeupdates_page' ) );
	}

	/**
	 * Remove old "Theme Updates" page from older themes.
	 *
	 * @return void
	 */
	public function remove_themeupdates_page() {
		remove_submenu_page( 'index.php', 'mythemeshop-updates' );
	}

	/**
	 * Add overlay to connect.
	 */
	public function add_overlay() {
		add_thickbox();
		add_action( 'admin_footer', array( $this, 'show_overlay' ), 10, 1 );
	}

	/**
	 * Show overlay to connect.
	 *
	 * @return void
	 */
	public function show_overlay() {
		?>
		<div
		<?php
		$this->str_convert(
			'6964 3D226D74732D636F 6E 6E656374 2D6D6F64 616C2220636C6173 73 3D 22 6D74 73 2D636F6E 6E6563742D74 68 65 6D652D6D6F64616C222073 74796C653D22646973706C6179 3A6E6F6E653B 22',
			1
		);
		?>
				>
			<div></div>
			<div>
				<p><?php echo wp_kses_post( str_replace( '[plugin_url]', network_admin_url( 'admin.php?page=mts-connect' ), $this->ngmsg ) ); ?></p>
				<?php Core::get( 'settings' )->connect_form_html(); ?>
				<p><a class="button button-secondary" href="#"><?php $this->str_convert( '436F6E6E656374204C61746572', 1 ); ?></a></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Add reminder message.
	 */
	public function add_reminder() {
		$exclude_pages = array( 'toplevel_page_mts-connect', 'toplevel_page_mts-connect-network', 'toplevel_page_mts-install-plugins' );

		$screen = get_current_screen();
		// Never show on excluded pages.
		if ( in_array( $screen->id, $exclude_pages, true ) ) {
			return;
		}
		// Multisite: show only on network admin.
		if ( is_multisite() && ! is_network_admin() ) {
			return;
		}
		Core::get( 'notifications' )->add_notice(
			array(
				'content' => $this->ngmsg,
				'class'   => 'error notice-error',
			)
		);
		$this->add_overlay();

	}

	public function ui() {
		if ( ( $this->mts_theme_in_use || $this->mts_plugins_in_use ) && ! Core::is_connected() && ! Core::get_instance()->invisible_mode && ! Core::is_free_plan() ) {
			add_filter( 'nhp-opts-sections', array( $this, 'nhp_sections' ), 10, 1 );
			add_filter( 'nhp-opts-args', array( $this, 'nhp_opts' ), 10, 1 );
			add_filter( 'nhp-opts-extra-tabs', '__return_empty_array', 11, 1 );
			add_action( 'current_screen', array( $this, 'add_reminder' ), 10, 1 );
		} else {
			remove_filter( 'nhp-opts-sections', '__return_empty_array', 9 );
			remove_action( 'admin_menu', array( $this, 'replace_admin_pages' ), 99 );
		}
	}

	/**
	 * Set NHP options.
	 *
	 * @param  array $opts Original options array.
	 * @return array       New options array.
	 */
	public function nhp_opts( $opts ) {
		$opts['show_import_export']    = false;
		$opts['show_typography']       = false;
		$opts['show_translate']        = false;
		$opts['show_child_theme_opts'] = false;
		$opts['last_tab']              = 0;

		return $opts;
	}

	/**
	 * Set NHP sections.
	 *
	 * @param  array $sections Original sections array.
	 * @return array       New sections array.
	 */
	public function nhp_sections( $sections ) {
		$url        = network_admin_url( 'admin.php?page=mts-connect' );
		$sections[] = array(
			'icon'   => 'fa fa-cogs',
			'title'  => __( 'Not Connected', 'mythemeshop-connect' ),
			// Translators: placeholder is a URL.
			'desc'   => '<p class="description">' . sprintf( __( 'You will find all the theme options here after <a href="%s">connecting with your MyThemeShop account</a>.', 'mythemeshop-connect' ), $url ) . '</p>',
			'fields' => array(),
		);
		return $sections;
	}

	/**
	 * Replace admin pages if needed.
	 *
	 * @return void
	 */
	public function replace_admin_pages() {
		$default_title = __( 'Settings', 'mythemeshop-connect' );
		/* Translators: 1 is opening tag for link to admin page, 2 is closing tag for the same */
		$default_message = sprintf( __( 'Plugin settings will appear here after you %1$sconnect with your MyThemeShop account.%2$s', 'mythemeshop-connect' ), '<a href="' . network_admin_url( 'admin.php?page=mts-connect' ) . '">', '</a>' );
		$replace         = array(
			array(
				'parent_slug' => 'options-general.php',
				'menu_slug'   => 'wp-review-pro',
				'title'       => __( 'WP Review Settings', 'mythemeshop-connect' ),
				/* Translators: 1 is opening tag for link to admin page, 2 is closing tag for the same */
				'message'     => sprintf( __( 'Review settings will appear here after you %1$sconnect with your MyThemeShop account.%2$s', 'mythemeshop-connect' ), '<a href="' . network_admin_url( 'admin.php?page=mts-connect' ) . '">', '</a>' ),
			),
			array(
				'parent_slug' => 'admin.php',
				'menu_slug'   => 'url_shortener_settings',
			),
			array(
				'parent_slug' => 'edit.php?post_type=wp_quiz',
				'menu_slug'   => 'wp_quiz_config',
			),
			array(
				'parent_slug' => 'admin.php',
				'menu_slug'   => 'wp-shortcode-options-general',
			),
			array(
				'parent_slug' => 'edit.php?post_type=listing',
				'menu_slug'   => 'wre_options',
			),
			array(
				'parent_slug' => 'edit.php?post_type=mts_notification_bar',
				'menu_slug'   => 'mts-notification-bar',
			),
			array(
				'parent_slug' => 'options-general.php',
				'menu_slug'   => 'wps-subscribe',
			),
		);

		$hide_items = array(
			array(
				'parent_slug' => 'edit.php?post_type=wp_quiz',
				'menu_slug'   => 'edit.php?post_type=wp_quiz',
			),
			array(
				'parent_slug' => 'edit.php?post_type=wp_quiz',
				'menu_slug'   => 'post-new.php?post_type=wp_quiz',
			),
		);

		foreach ( $replace as $menu_data ) {
			$parent_slug = $menu_data['parent_slug'];
			$menu_slug   = $menu_data['menu_slug'];
			$hookname    = get_plugin_page_hookname( $menu_slug, $parent_slug );

			$title   = ! empty( $menu_data['title'] ) ? $menu_data['title'] : $default_title;
			$message = ! empty( $menu_data['message'] ) ? $menu_data['message'] : $default_message;

			$this->custom_admin_messages[ $hookname ] = array(
				'title'   => $title,
				'message' => $message,
			);

			remove_all_actions( $hookname );
			add_action( $hookname, array( $this, 'replace_settings_page' ) );
		}

		foreach ( $hide_items as $i => $item ) {
			remove_submenu_page( $item['parent_slug'], $item['menu_slug'] );
		}

		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 99, 2 );
	}

	/**
	 * Remove meta boxes if needed.
	 *
	 * @param  string $post_type Post type name.
	 * @param  object $post      Post object.
	 * @return void
	 */
	public function remove_meta_boxes( $post_type, $post ) {
		$remove_meta_boxes = array(
			'wp-review-metabox-review',
			'wp-review-metabox-item',
			'wp-review-metabox-reviewLinks',
			'wp-review-metabox-desc',
			'wp-review-metabox-userReview',
		);
		$post_types        = get_post_types( array( 'public' => true ), 'names' );
		foreach ( $post_types as $post_type ) {
			foreach ( $remove_meta_boxes as $box ) {
				remove_meta_box( $box, $post_type, 'normal' );
			}
		}
	}

	/**
	 * Replace plugin settings page if needed.
	 *
	 * @return void
	 */
	public function replace_settings_page() {
		$hookname = current_filter();
		$data     = $this->custom_admin_messages[ $hookname ];

		?>
		<div class="wrap wp-review">
			<h1><?php echo $data['title']; ?></h1>

			<p><?php echo $data['message']; ?></p>
		</div>
		<script type="text/javascript">var mts_connect_refresh = true;</script>
		<?php
	}

	/**
	 * String converter.
	 *
	 * @param  string  $text String to convert.
	 * @param  boolean $echo Output results.
	 * @return mixed         Resulting string if $echo is false, otherwise true.
	 */
	public function str_convert( $text, $echo = false ) {
		$text   = preg_replace( '/\s+/', '', $text );
		$string = '';
		for ( $i = 0; $i < strlen( $text ) - 1; $i += 2 ) {
			$string .= chr( hexdec( $text[ $i ] . $text[ $i + 1 ] ) );
		}

		if ( $echo ) {
			echo $string;
			return true;
		}

		return $string;
	}

	/**
	 * Fix wrong theme notifications coming from the repo.
	 *
	 * @param  object $val Updates data object.
	 * @return object      New updates data object.
	 */
	public function fix_false_wp_org_theme_update_notification( $val ) {
		$allow_update = array( 'point', 'ribbon-lite' );
		if ( is_object( $val ) && property_exists( $val, 'response' ) && is_array( $val->response ) ) {
			foreach ( $val->response as $key => $value ) {
				if ( isset( $value['theme'] ) ) {// added by WordPress
					if ( in_array( $value['theme'], $allow_update ) ) {
						continue;
					}
					$url       = $value['url'];// maybe wrong url for MyThemeShop theme
					$theme     = wp_get_theme( $value['theme'] );// real theme object
					$theme_uri = $theme->get( 'ThemeURI' );// theme url
					// If it is MyThemeShop theme but wordpress.org have the theme with same name, remove it from update response
					if ( false !== strpos( $theme_uri, 'mythemeshop.com' ) && false !== strpos( $url, 'wordpress.org' ) ) {
						unset( $val->response[ $key ] );
					}
				}
			}
		}
		return $val;
	}

	/**
	 * Fix wrong plugin notifications coming from the repo.
	 *
	 * @param  object $val Updates data object.
	 * @return object      New updates data object.
	 */
	public function fix_false_wp_org_plugin_update_notification( $val ) {
		if ( property_exists( $val, 'response' ) && is_array( $val->response ) ) {
			foreach ( $val->response as $key => $value ) {
				$url        = $value->url;
				$plugin     = get_plugin_data( WP_PLUGIN_DIR . '/' . $key, false, false );
				$plugin_uri = $plugin['PluginURI'];
				if ( 0 !== strpos( $plugin_uri, 'mythemeshop.com' && 0 !== strpos( $url, 'wordpress.org' ) ) ) {
					unset( $val->response[ $key ] );
				}
			}
		}
		return $val;
	}

	/**
	 * Check how many MTS plugins we are using and store it in an option row.
	 *
	 * @return void
	 */
	public function check_for_mts_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$active_plugins = get_option( 'active_plugins', array() );

		$hash = substr( md5( serialize( $active_plugins ) ), 0, 8 ); // phpcs:ignore
		$opt  = get_option( 'mts_plugins_active', false );
		if ( $opt !== false ) {
			$stored_hash = substr( $opt, 0, 8 );
			if ( $hash == $stored_hash ) {
				// No change in the list of plugins
				$this->mts_plugins_in_use = (int) substr( $opt, 9 );
				return;
			}
		}

		$all_plugins = get_plugins( '' );
		foreach ( $active_plugins as $plugin_file ) {
			if ( $plugin_file == MTS_CONNECT_PLUGIN_FILE ) {
				continue;
			}
			if ( isset( $all_plugins[ $plugin_file ] ) && isset( $all_plugins[ $plugin_file ]['Author'] ) && stripos( $all_plugins[ $plugin_file ]['Author'], 'MyThemeShop' ) !== false ) {
				$this->mts_plugins_in_use++;
			}
		}

		update_option( 'mts_plugins_active', $hash . '-' . $this->mts_plugins_in_use );
		return;

	}

	/**
	 * Check if we are using a MTS theme and store it in an option row.
	 *
	 * @return void
	 */
	public function check_for_mts_theme() {
		// Check for mts_theme once.
		if ( ( $stored = get_option( 'mts_theme_active', false ) ) !== false ) {
			$this->mts_theme_in_use = ( $stored === '1' );
			return;
		}

		$theme  = wp_get_theme();
		$author = $theme->get( 'Author' );
		if ( stripos( $author, 'MyThemeShop' ) !== false ) {
			$this->mts_theme_in_use = true;
			update_option( 'mts_theme_active', '1' );
			return;
		}

		// Also check parent.
		if ( $theme->parent() ) {
			$parent_author = $theme->parent()->get( 'Author' );
			if ( stripos( $parent_author, 'MyThemeShop' ) !== false ) {
				$this->mts_theme_in_use = true;
				update_option( 'mts_theme_active', '1' );
				return;
			}
		}

		update_option( 'mts_theme_active', '0' );
		return;
	}

	/**
	 * Clear option that stores if we are using a MTS theme.
	 *
	 * @return void
	 */
	public function clear_theme_check() {
		delete_option( 'mts_theme_active' );
	}
}
