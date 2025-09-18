<?php
/**
 * All the theme options are declared in this file.
 *
 * @package Schema
 */

defined( 'ABSPATH' ) || die;

/**
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 */
require_once dirname( __FILE__ ) . '/options/options.php';

/**
 * Add support tab
 */
if ( ! defined( 'MTS_THEME_WHITE_LABEL' ) || ! MTS_THEME_WHITE_LABEL ) {
	require_once dirname( __FILE__ ) . '/options/support.php';
	$mts_options_tab_support = MTS_Options_Tab_Support::get_instance();
}

/**
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 */
function add_another_section( $sections ) {
	$sections[] = array(
		'title'  => __( 'A Section added by hook', 'schema' ),
		'desc'   => '<p class="description">' . __( 'This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.', 'schema' ) . '</p>',
		// All the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		// You dont have to though, leave it blank for default.
		'icon'   => trailingslashit( get_template_directory_uri() ) . 'options/img/glyphicons/glyphicons_062_attach.png',
		// Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(),
	);

	return $sections;

}

/**
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 */
function change_framework_args( $args ) {
	return $args;
}

/**
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 */
function setup_framework_options() {
	$args = array();

	// Set it to dev mode to view the class settings/info in the form - default is false.
	$args['dev_mode'] = false;

	if ( ! MTS_THEME_WHITE_LABEL ) {
		// Setup custom links in the footer for share icons.
		$args['share_icons']['twitter']  = array(
			'link'  => 'http://twitter.com/mythemeshopteam',
			'title' => __( 'Follow Us on Twitter', 'schema' ),
			'img'   => 'fa fa-twitter-square',
		);
		$args['share_icons']['facebook'] = array(
			'link'  => 'http://www.facebook.com/mythemeshop',
			'title' => __( 'Like us on Facebook', 'schema' ),
			'img'   => 'fa fa-facebook-square',
		);
	}

	// Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores.
	$args['opt_name'] = MTS_THEME_NAME;

	// Custom menu title for options page - default is "Options".
	$args['menu_title'] = __( 'Theme Options', 'schema' );

	// Custom Page Title for options page - default is "Options".
	$args['page_title'] = __( 'Theme Options', 'schema' );

	// Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options".
	$args['page_slug'] = 'theme_options';

	// Custom page location - default 100 - must be unique or will override other items.
	$args['page_position'] = 62;

	if ( ! MTS_THEME_WHITE_LABEL ) {
		// Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition.
		$args['help_tabs'][] = array(
			'id'      => 'nhp-opts-1',
			'title'   => __( 'Support', 'schema' ),
			// translators: Support Forum Link.
			'content' => '<p>' . sprintf( __( 'If you are facing any problem with our theme or theme option panel, head over to our %s.', 'schema' ), '<a href="http://community.mythemeshop.com/">' . __( 'Support Forums', 'schema' ) . '</a>' ) . '</p>',
		);
		$args['help_tabs'][] = array(
			'id'      => 'nhp-opts-2',
			'title'   => __( 'Earn Money', 'schema' ),
			// translators: Affiliate Program Link.
			'content' => '<p>' . sprintf( __( 'Earn 55%% commision on every sale by refering your friends and readers. Join our %s.', 'schema' ), '<a href="http://mythemeshop.com/affiliate-program/">' . __( 'Affiliate Program', 'schema' ) . '</a>' ) . '</p>',
		);
	}

	$mts_patterns = array(
		'nobg'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/nobg.png' ),
		'pattern0'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern0.png' ),
		'pattern1'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern1.png' ),
		'pattern2'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern2.png' ),
		'pattern3'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern3.png' ),
		'pattern4'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern4.png' ),
		'pattern5'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern5.png' ),
		'pattern6'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern6.png' ),
		'pattern7'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern7.png' ),
		'pattern8'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern8.png' ),
		'pattern9'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern9.png' ),
		'pattern10' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern10.png' ),
		'pattern11' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern11.png' ),
		'pattern12' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern12.png' ),
		'pattern13' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern13.png' ),
		'pattern14' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern14.png' ),
		'pattern15' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern15.png' ),
		'pattern16' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern16.png' ),
		'pattern17' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern17.png' ),
		'pattern18' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern18.png' ),
		'pattern19' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern19.png' ),
		'pattern20' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern20.png' ),
		'pattern21' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern21.png' ),
		'pattern22' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern22.png' ),
		'pattern23' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern23.png' ),
		'pattern24' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern24.png' ),
		'pattern25' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern25.png' ),
		'pattern26' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern26.png' ),
		'pattern27' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern27.png' ),
		'pattern28' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern28.png' ),
		'pattern29' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern29.png' ),
		'pattern30' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern30.png' ),
		'pattern31' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern31.png' ),
		'pattern32' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern32.png' ),
		'pattern33' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern33.png' ),
		'pattern34' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern34.png' ),
		'pattern35' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern35.png' ),
		'pattern36' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern36.png' ),
		'pattern37' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/pattern37.png' ),
		'hbg'       => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg.png' ),
		'hbg2'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg2.png' ),
		'hbg3'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg3.png' ),
		'hbg4'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg4.png' ),
		'hbg5'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg5.png' ),
		'hbg6'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg6.png' ),
		'hbg7'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg7.png' ),
		'hbg8'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg8.png' ),
		'hbg9'      => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg9.png' ),
		'hbg10'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg10.png' ),
		'hbg11'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg11.png' ),
		'hbg12'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg12.png' ),
		'hbg13'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg13.png' ),
		'hbg14'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg14.png' ),
		'hbg15'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg15.png' ),
		'hbg16'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg16.png' ),
		'hbg17'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg17.png' ),
		'hbg18'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg18.png' ),
		'hbg19'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg19.png' ),
		'hbg20'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg20.png' ),
		'hbg21'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg21.png' ),
		'hbg22'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg22.png' ),
		'hbg23'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg23.png' ),
		'hbg24'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg24.png' ),
		'hbg25'     => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg25.png' ),
	);

	$sections = array();

	$sections[] = array(
		'icon'   => 'fa fa-cogs',
		'title'  => __( 'General Settings', 'schema' ),
		'desc'   => '<p class="description">' . __( 'This tab contains common setting options which will be applied to the whole theme.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_logo',
				'type'     => 'upload',
				'title'    => __( 'Logo Image', 'schema' ),
				'sub_desc' => __( 'Upload your logo using the Upload Button or insert image URL. Preferable Size 120px X 28px', 'schema' ),
				'return'   => 'id',
			),
			array(
				'id'       => 'mts_favicon',
				'type'     => 'upload',
				'title'    => __( 'Favicon', 'schema' ),
				// translators: Favicon Size.
				'sub_desc' => sprintf( __( 'Upload a %s image that will represent your website\'s favicon.', 'schema' ), '<strong>32 x 32 px</strong>' ),
				'return'   => 'id',
			),
			array(
				'id'       => 'mts_touch_icon',
				'type'     => 'upload',
				'title'    => __( 'Touch icon', 'schema' ),
				// translators: Touch Icon Size.
				'sub_desc' => sprintf( __( 'Upload a %s image that will represent your website\'s touch icon for iOS 2.0+ and Android 2.1+ devices.', 'schema' ), '<strong>152 x 152 px</strong>' ),
				'return'   => 'id',
			),
			array(
				'id'       => 'mts_metro_icon',
				'type'     => 'upload',
				'title'    => __( 'Metro icon', 'schema' ),
				// translators: Metro Icon Size.
				'sub_desc' => sprintf( __( 'Upload a %s image that will represent your website\'s IE 10 Metro tile icon.', 'schema' ), '<strong>144 x 144 px</strong>' ),
				'return'   => 'id',
			),
			array(
				'id'       => 'mts_twitter_username',
				'type'     => 'text',
				'title'    => __( 'Twitter Username', 'schema' ),
				'sub_desc' => __( 'Enter your Username here.', 'schema' ),
			),
			array(
				'id'       => 'mts_feedburner',
				'type'     => 'text',
				'title'    => __( 'FeedBurner URL', 'schema' ),
				// translators: Example feed link.
				'sub_desc' => sprintf( __( 'Enter your FeedBurner\'s URL here, ex: %s and your main feed (http://example.com/feed) will get redirected to the FeedBurner ID entered here.)', 'schema' ),
					'<strong>http://feeds.feedburner.com/mythemeshop</strong>'
				),
				'validate' => 'url',
			),
			array(
				'id'       => 'mts_header_code',
				'type'     => 'textarea',
				'title'    => __( 'Header Code', 'schema' ),
				'sub_desc' => wp_kses(
					__(
						'Enter the code which you need to place <strong>before closing &lt;/head&gt; tag</strong>. (ex: Google Webmaster Tools verification, Bing Webmaster Center, BuySellAds Script, Alexa verification etc.)',
						'schema'
					),
					array(
						'strong' => '',
					)
				),
			),
			array(
				'id'       => 'mts_analytics_code',
				'type'     => 'textarea',
				'title'    => __( 'Footer Code', 'schema' ),
				'sub_desc' => wp_kses(
					__(
						'Enter the codes which you need to place in your footer. <strong>(ex: Google Analytics, Clicky, STATCOUNTER, Woopra, Histats, etc.)</strong>.',
						'schema'
					),
					array(
						'strong' => '',
					)
				),
			),
			array(
				'id'       => 'mts_pagenavigation_type',
				'type'     => 'radio',
				'title'    => __( 'Pagination Type', 'schema' ),
				'sub_desc' => __( 'Select pagination type.', 'schema' ),
				'options'  => array(
					'0' => __( 'Default (Next / Previous)', 'schema' ),
					'1' => __( 'Numbered (1 2 3 4...)', 'schema' ),
					'2' => __( 'AJAX (Load More Button)', 'schema' ),
					'3' => __( 'AJAX (Auto Infinite Scroll)', 'schema' ),
				),
				'std'      => '0',
			),
			array(
				'id'       => 'mts_ajax_search',
				'type'     => 'button_set',
				'title'    => __( 'AJAX Quick search', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'Enable or disable search results appearing instantly below the search form', 'schema' ),
				'std'      => '0',
			),
			array(
				'id'       => 'mts_full_posts',
				'type'     => 'button_set',
				'title'    => __( 'Posts on blog pages', 'schema' ),
				'options'  => array(
					'0' => 'Excerpts',
					'1' => 'Full posts',
				),
				'sub_desc' => __( 'Show post excerpts or full posts on the homepage and other archive pages.', 'schema' ),
				'std'      => '0',
				'class'    => 'green',
			),
			array(
				'id'       => 'mts_responsive',
				'type'     => 'button_set',
				'title'    => __( 'Responsiveness', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __(
					'MyThemeShop themes are responsive, which means they adapt to tablet and mobile devices, ensuring that your content is always displayed beautifully no matter what device visitors are using. Enable or disable responsiveness using this option.',
					'schema'
				),
				'std'      => '1',
			),
			array(
				'id'       => 'mts_rtl',
				'type'     => 'button_set',
				'title'    => __( 'Right To Left Language Support', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'Enable this option for right-to-left sites.', 'schema' ),
				'std'      => '0',
			),
			array(
				'id'       => 'mts_shop_products',
				'type'     => 'text',
				'title'    => __( 'No. of Products', 'schema' ),
				'sub_desc' => __(
					'Enter the total number of products which you want to show on shop page (WooCommerce plugin must be enabled).',
					'schema'
				),
				'validate' => 'numeric',
				'std'      => '9',
				'class'    => 'small-text',
			),
		),
	);

	$sections[] = array(
		'icon'   => 'fa fa-bolt',
		'title'  => __( 'Performance', 'schema' ),
		'desc'   => '<p class="description">' . __( 'This tab contains performance-related options which can help speed up your website.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_prefetching',
				'type'     => 'button_set',
				'title'    => __( 'Prefetching', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __(
					'Enable or disable prefetching. If user is on homepage, then single page will load faster and if user is on single page, homepage will load faster in modern browsers.',
					'schema'
				),
				'std'      => '0',
			),
			array(
				'id'               => 'mts_lazy_load',
				'type'             => 'button_set_hide_below',
				'title'            => __( 'Theme\'s Lazy Loading', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc'         => __( 'Delay loading of images outside of viewport, until user scrolls to them.', 'schema' ),
				'std'              => '0',
				'args'             => array( 'hide' => 2 ),
				'reset_at_version' => '3.0',
			),
			array(
				'id'               => 'mts_lazy_load_thumbs',
				'type'             => 'button_set',
				'title'            => __( 'Lazy load featured images', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc'         => __( 'Enable or disable Lazy load of featured images across site.', 'schema' ),
				'std'              => '0',
				'reset_at_version' => '3.0',
			),
			array(
				'id'               => 'mts_lazy_load_content',
				'type'             => 'button_set',
				'title'            => __( 'Lazy load post content images', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc'         => __( 'Enable or disable Lazy load of images inside post/page content.', 'schema' ),
				'std'              => '0',
				'reset_at_version' => '3.0',
			),
			array(
				'id'               => 'mts_lazy_load_comments',
				'type'             => 'button_set',
				'title'            => __( 'Theme\'s Lazy Load Comment Images', 'schema' ),
				'sub_desc'         => __( 'Enable or disable lazy loading for the comment author images.', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'std'              => '1',
				'reset_at_version' => '3.9.0',
			),
			array(
				'id'               => 'mts_async_js',
				'type'             => 'button_set',
				'title'            => __( 'Async JavaScript', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				// translators: `async` don't change.
				'sub_desc'         => sprintf( __( 'Add %s attribute to script tags to improve page download speed.', 'schema' ), '<code>async</code>' ),
				'std'              => '1',
				'reset_at_version' => '3.0',
			),
			array(
				'id'               => 'mts_remove_ver_params',
				'type'             => 'button_set',
				'title'            => __( 'Remove ver parameters', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				// translators: `ver` don't change.
				'sub_desc'         => sprintf( __( 'Remove %s parameter from CSS and JS file calls. It may improve speed in some browsers which do not cache files having the parameter.', 'schema' ),
					'<code>ver</code>'
				),
				'std'              => '1',
				'reset_at_version' => '3.0',
			),
			array(
				'id'               => 'mts_optimize_wc',
				'type'             => 'button_set',
				'title'            => __( 'Optimize WooCommerce scripts', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc'         => __( 'Load WooCommerce scripts and styles only on WooCommerce pages (WooCommerce plugin must be enabled).', 'schema' ),
				'std'              => '1',
				'reset_at_version' => '3.0',
			),
			'cache_message' => array(
				'id'    => 'mts_cache_message',
				'type'  => 'info',
				'title' => __( 'Use Cache', 'schema' ),
				// Translators: %1$s = popup link to W3 Total Cache, %2$s = popup link to WP Super Cache.
				'desc'  => sprintf(
					// translators: Cache Plugin Names.
					__( 'A cache plugin can increase page download speed dramatically. We recommend using %1$s or %2$s.', 'schema' ),
					'<a href="https://community.mythemeshop.com/tutorials/article/8-make-your-website-load-faster-using-w3-total-cache-plugin/" target="_blank" title="W3 Total Cache">W3 Total Cache</a>',
					'<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&plugin=wp-super-cache&TB_iframe=true&width=772&height=574' )
						. '" class="thickbox" title="WP Super Cache">WP Super Cache</a>'
				),
			),
		),
	);

	// Hide cache message on multisite or if a chache plugin is active already.
	if ( is_multisite() || strstr( join( ';', get_option( 'active_plugins' ) ), 'cache' ) ) {
		unset( $sections[1]['fields']['cache_message'] );
	}

	$sections[] = array(
		'icon'   => 'fa fa-adjust',
		'title'  => __( 'Styling Options', 'schema' ),
		'desc'   => '<p class="description">' . __( 'Control the visual appearance of your theme, such as colors, layout and patterns, from here.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_color_scheme',
				'type'     => 'color',
				'title'    => __( 'Color Scheme', 'schema' ),
				'sub_desc' => __( 'The theme comes with unlimited color schemes for your theme\'s styling.', 'schema' ),
				'std'      => '#0274BE',
			),
			array(
				'id'       => 'mts_layout',
				'type'     => 'radio_img',
				'title'    => __( 'Layout Style', 'schema' ),
				'sub_desc' => wp_kses( __( 'Choose the <strong>default sidebar position</strong> for your site. The position of the sidebar for individual posts can be set in the post editor.', 'schema' ), array( 'strong' => '' ) ),
				'options'  => array(
					'cslayout' => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/cs.png' ),
					'sclayout' => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/sc.png' ),
				),
				'std'      => 'cslayout',
			),
			array(
				'id'       => 'mts_background',
				'type'     => 'background',
				'title'    => __( 'Site Background', 'schema' ),
				'sub_desc' => __( 'Set background color, pattern and image from here.', 'schema' ),
				'options'  => array(
					'color'         => '', // false to disable, not needed otherwise.
					'image_pattern' => $mts_patterns, // false to disable, array of options otherwise ( required !!! ).
					'image_upload'  => '', // false to disable, not needed otherwise.
					'repeat'        => array(), // false to disable, array of options to override default ( optional ).
					'attachment'    => array(), // false to disable, array of options to override default ( optional ).
					'position'      => array(), // false to disable, array of options to override default ( optional ).
					'size'          => array(), // false to disable, array of options to override default ( optional ).
					'gradient'      => '', // false to disable, not needed otherwise.
					'parallax'      => array(), // false to disable, array of options to override default ( optional ).
				),
				'std'      => array(
					'color'         => '#eeeeee',
					'use'           => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'        => 'repeat',
					'attachment'    => 'scroll',
					'position'      => 'left top',
					'size'          => 'cover',
					'gradient'      => array(
						'from'      => '#ffffff',
						'to'        => '#000000',
						'direction' => 'horizontal',
					),
					'parallax'      => '0',
				),
			),
			array(
				'id'       => 'mts_custom_css',
				'type'     => 'textarea',
				'title'    => __( 'Custom CSS', 'schema' ),
				'sub_desc' => __( 'You can enter custom CSS code here to further customize your theme. This will override the default CSS used on your site.', 'schema' ),
			),
			array(
				'id'       => 'mts_lightbox',
				'type'     => 'button_set',
				'title'    => __( 'Lightbox', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'A lightbox is a stylized pop-up that allows your visitors to view larger versions of images without leaving the current page. You can enable or disable the lightbox here.', 'schema' ),
				'std'      => '0',
			),
		),
	);
	$sections[] = array(
		'icon'   => 'fa fa-credit-card',
		'title'  => __( 'Header', 'schema' ),
		'desc'   => '<p class="description">' . __( 'From here, you can control the elements of header section.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_header_style',
				'type'     => 'radio_img',
				'title'    => __( 'Header Styling', 'schema' ),
				'sub_desc' => __( 'Choose the <strong>Header design</strong> for your site.', 'schema' ),
				'options'  => array(
					'regular_header'     => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/h1.png' ),
					'logo_in_nav_header' => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/h2.png' ),
				),
				'std'      => 'regular_header',
			),

			array(
				'id'               => 'mts_regular_header_bg',
				'type'             => 'background',
				'title'            => __( 'Header Background', 'schema' ),
				'sub_desc'         => __( 'Set regular header background color, pattern and image from here.', 'schema' ),
				'options'          => array(
					'color'	        => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		    => array(),
					'attachment' 	  => array(),
					'position'	    => array(),
					'size'		      => array(),
					'gradient'	    => '',
					'parallax'	    => array(),
				),
				'std'             => array(
					'color'		      => '#ffffff',
					'use'		        => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		    => 'repeat',
					'attachment'	  => 'scroll',
					'position'  	  => 'left top',
					'size'		      => 'cover',
					'gradient'	    => array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	    => '0',
				),
				//reset version at 3.8.4
				'reset_at_version' => '3.8.4',
			),
			array(
				'id'               => 'mts_regular_header_nav_bg',
				'type'             => 'color',
				'title'            => __( 'Navigation Background', 'schema' ),
				'sub_desc'         => __( 'Choose the background color you want to use for navigation in regular header layout.', 'schema' ),
				'std'              => '#222222',
				'reset_at_version' => '3.8.4',
			),
			array(
				'id'               => 'mts_layout2_header_bg',
				'type'             => 'background',
				'title'            => __( 'Header Background', 'schema' ),
				'sub_desc'         => __( 'Set header layout2 background color, pattern and image from here.', 'schema' ),
				'options'          => array(
					'color'	        => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		    => array(),
					'attachment' 	  => array(),
					'position'	    => array(),
					'size'		      => array(),
					'gradient'	    => '',
					'parallax'	    => array(),
				),
				'std'      => array(
					'color'		      => '#ffffff',
					'use'		        => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		    => 'repeat',
					'attachment'	  => 'scroll',
					'position'  	  => 'left top',
					'size'		      => 'cover',
					'gradient'	    => array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	    => '0',
				),
				//reset version at 3.8.4
				'reset_at_version' => '3.8.4',
			),
			array(
				'id'               => 'mts_layout2_header_nav_bg',
				'type'             => 'color',
				'title'            => __( 'Logo Area Background', 'schema' ),
				'sub_desc'         => __( 'Choose the background color you want to use in layout2 header navigation.', 'schema' ),
				'std'              => '#222222',
				//reset version at 3.8.4
				'reset_at_version' => '3.8.4',
			),
			array(
				'id'       => 'mts_sticky_nav',
				'type'     => 'button_set_hide_below',
				'title'    => __( 'Floating Navigation Menu', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				// translators: Floating Navigation Menu.
				'sub_desc' => sprintf( __( 'Use this button to enable %s.', 'schema' ), '<strong>' . __( 'Floating Navigation Menu', 'schema' ) . '</strong>' ),
				'std'      => '0',
			),
			array(
				'id'               => 'sticky_responsive_nav',
				'type'             => 'button_set',
				'title'            => esc_html__( 'Floating Navigation Responsive', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				// translators: Primary Navigation Menu.
				'sub_desc'         => wp_kses( __( 'Use this button to Show or Hide the <strong>Floating Navigation Menu</strong> on Responsive screen.', 'schema' ), [ 'strong' => '' ] ),
				'std'              => '0',
				'reset_at_version' => '3.9.15',
			),
			array(
				'id'       => 'mts_show_primary_nav',
				'type'     => 'button_set',
				'title'    => __( 'Show Primary Menu', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				// translators: Primary Navigation Menu.
				'sub_desc' => sprintf( __( 'Use this button to enable %s.', 'schema' ), '<strong>' . __( 'Primary Navigation Menu', 'schema' ) . '</strong>' ),
				'std'      => '1',
			),
			array(
				'id'       => 'mts_header_section2',
				'type'     => 'button_set',
				'title'    => __( 'Show Logo', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => wp_kses( __( 'Use this button to Show or Hide the <strong>Logo</strong> completely.', 'schema' ), array( 'strong' => '' ) ),
				'std'      => '1',
			),
			array(
				'id'       => 'mts_header_social_icons',
				'type'     => 'button_set_hide_below',
				'title'    => __( 'Show header social icons', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'Use this button to show or hide Header Social Icons.', 'schema' ),
				'std'      => '1',
			),
			array(
				'id'               => 'mts_header_social',
				'title'            => __( 'Header Social Icons', 'schema' ),
				'sub_desc'         => __( 'Add Social Media icons in header.', 'schema' ),
				'type'             => 'group',
				'groupname'        => __( 'Header Icons', 'schema' ), // Group name.
				'subfields'        =>
				array(
					array(
						'id'    => 'mts_header_icon_title',
						'type'  => 'text',
						'title' => __( 'Title', 'schema' ),
					),
					array(
						'id'    => 'mts_header_icon',
						'type'  => 'icon_select',
						'title' => __( 'Icon', 'schema' ),
					),
					array(
						'id'    => 'mts_header_icon_link',
						'type'  => 'text',
						'title' => __( 'URL', 'schema' ),
					),
				),
				'std'              => array(
					'facebook' => array(
						'group_title'           => 'Facebook',
						'group_sort'            => '1',
						'mts_header_icon_title' => 'Facebook',
						'mts_header_icon'       => 'facebook',
						'mts_header_icon_link'  => '#',
					),
					'twitter'  => array(
						'group_title'           => 'Twitter',
						'group_sort'            => '2',
						'mts_header_icon_title' => 'Twitter',
						'mts_header_icon'       => 'twitter',
						'mts_header_icon_link'  => '#',
					),
					'youtube'  => array(
						'group_title'           => 'YouTube',
						'group_sort'            => '4',
						'mts_header_icon_title' => 'YouTube',
						'mts_header_icon'       => 'youtube-play',
						'mts_header_icon_link'  => '#',
					),
				),
				'reset_at_version' => '3.0',
			),
			array(
				'id'               => 'mts_show_header_search',
				'type'             => 'button_set',
				'title'            => __( 'Show Search for in Main Navigation', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				// translators: Primary Navigation Menu.
				'sub_desc'         => sprintf( __( 'Use this button to enable %s in Secondary Navigation', 'schema' ), '<strong>' . __( 'Header Search', 'schema' ) . '</strong>' ),
				'std'              => '0',
				'reset_at_version' => '3.9.0',
			),
		),
	);
	$sections[] = array(
		'icon'   => 'fa fa-home',
		'title'  => __( 'Homepage', 'schema' ),
		'desc'   => '<p class="description">' . __( 'From here, you can control the elements of the homepage.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_featured_slider',
				'type'     => 'button_set_hide_below',
				'title'    => __( 'Homepage Slider', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => wp_kses( __( '<strong>Enable or Disable</strong> homepage slider with this button. The slider will show recent articles from the selected categories.', 'schema' ), array( 'strong' => '' ) ),
				'std'      => '0',
				'args'     => array( 'hide' => 3 ),
			),
			array(
				'id'       => 'mts_featured_slider_cat',
				'type'     => 'cats_multi_select',
				'title'    => __( 'Slider Category(s)', 'schema' ),
				'sub_desc' => wp_kses( __( 'Select a category from the drop-down menu, latest articles from this category will be shown <strong>in the slider</strong>.', 'schema' ), array( 'strong' => '' ) ),
			),
			array(
				'id'       => 'mts_featured_slider_num',
				'type'     => 'text',
				'class'    => 'small-text',
				'title'    => __( 'Number of posts', 'schema' ),
				'sub_desc' => __( 'Enter the number of posts to show in the slider', 'schema' ),
				'std'      => '3',
				'args'     => array( 'type' => 'number' ),
			),
			array(
				'id'        => 'mts_custom_slider',
				'type'      => 'group',
				'title'     => __( 'Custom Slider', 'schema' ),
				'sub_desc'  => __( 'With this option you can set up a slider with custom image and text instead of the default slider automatically generated from your posts.', 'schema' ),
				'groupname' => __( 'Slider', 'schema' ), // Group name.
				'subfields' =>
				array(
					array(
						'id'       => 'mts_custom_slider_title',
						'type'     => 'text',
						'title'    => __( 'Title', 'schema' ),
						'sub_desc' => __( 'Title of the slide', 'schema' ),
					),
					array(
						'id'       => 'mts_custom_slider_image',
						'type'     => 'upload',
						'title'    => __( 'Image', 'schema' ),
						'sub_desc' => __( 'Upload or select an image for this slide', 'schema' ),
						'return'   => 'id',
					),
					array(
						'id'       => 'mts_custom_slider_link',
						'type'     => 'text',
						'title'    => __( 'Link', 'schema' ),
						'sub_desc' => __( 'Insert a link URL for the slide', 'schema' ),
						'std'      => '#',
					),
				),
			),
			array(
				'id'       => 'mts_thumb_layout',
				'type'     => 'radio_img',
				'title'    => __( 'HomePage Thumbnail Size', 'schema' ),
				'sub_desc' => __( 'Choose the <strong>featured thumbnail size</strong> for your site.', 'schema' ),
				'options'  => array(
					'large_home_thumb' => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/tb.png' ),
					'small_home_thumb' => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/ts.png' ),
				),
				'std'      => 'large_home_thumb',
			),
			array(
				'id'        => 'mts_featured_categories',
				'type'      => 'group',
				'title'     => __( 'Featured Categories', 'schema' ),
				'sub_desc'  => __( 'Select categories appearing on the homepage.', 'schema' ),
				'groupname' => __( 'Section', 'schema' ), // Group name.
				'subfields' =>
				array(
					array(
						'id'       => 'mts_featured_category',
						'type'     => 'cats_select',
						'title'    => __( 'Category', 'schema' ),
						'sub_desc' => __( 'Select a category or the latest posts for this section', 'schema' ),
						'std'      => 'latest',
						'args'     => array(
							'include_latest' => 1,
							'hide_empty'     => 0,
						),
					),
					array(
						'id'       => 'mts_featured_category_postsnum',
						'type'     => 'text',
						'class'    => 'small-text',
						'title'    => __( 'Number of posts', 'schema' ),
						// translators: WordPress Reading Settings Link.
						'sub_desc' => sprintf( wp_kses_post( __( 'Enter the number of posts to show in this section.<br/><strong>For Latest Posts</strong>, this setting will be ignored, and number set in <a href="%s" target="_blank">Settings&nbsp;&gt;&nbsp;Reading</a> will be used instead.', 'schema' ) ), admin_url( 'options-reading.php' ) ),
						'std'      => '3',
						'args'     => array( 'type' => 'number' ),
					),
				),
				'std'       => array(
					'1' => array(
						'group_title'                    => '',
						'group_sort'                     => '1',
						'mts_featured_category'          => 'latest',
						'mts_featured_category_postsnum' => get_option( 'posts_per_page' ),
					),
				),
			),
			array(
				'id'               => 'mts_home_headline_meta_info',
				'type'             => 'layout',
				'title'            => __( 'HomePage Post Meta Info', 'schema' ),
				'sub_desc'         => __( 'Organize how you want the post meta info to appear on the homepage', 'schema' ),
				'options'          => array(
					'enabled'  => array(
						'author'   => __( 'Author Name', 'schema' ),
						'date'     => __( 'Date', 'schema' ),
						'category' => __( 'Categories', 'schema' ),
						'comment'  => __( 'Comment Count', 'schema' ),
					),
					'disabled' => array(),
				),
				'std'              => array(
					'enabled'  => array(
						'author'   => __( 'Author Name', 'schema' ),
						'date'     => __( 'Date', 'schema' ),
						'category' => __( 'Categories', 'schema' ),
						'comment'  => __( 'Comment Count', 'schema' ),
					),
					'disabled' => array(),
				),
				'reset_at_version' => '3.0',
			),
		),
	);
	$sections[] = array(
		'icon'   => 'fa fa-table',
		'title'  => __( 'Footer', 'schema' ),
		'desc'   => '<p class="description">' . __( 'From here, you can control the elements of Footer section.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_top_footer',
				'type'     => 'button_set_hide_below',
				'title'    => __( 'Footer', 'schema' ),
				'sub_desc' => __( 'Enable or disable footer with this option.', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'std'      => '0',
			),
			array(
				'id'       => 'mts_top_footer_num',
				'type'     => 'button_set',
				'class'    => 'green',
				'title'    => __( 'Footer Layout', 'schema' ),
				'sub_desc' => wp_kses( __( 'Choose the number of widget areas in the <strong>footer</strong>', 'schema' ), array( 'strong' => '' ) ),
				'options'  => array(
					'3' => __( '3 Widgets', 'schema' ),
					'4' => __( '4 Widgets', 'schema' ),
				),
				'std'      => '4',
			),
			array(
				'id'       => 'mts_footer_bg_color',
				'type'     => 'color',
				'title'    => __( 'Footer Background Color', 'schema' ),
				'sub_desc' => __( 'Pick a color for the footer background color.', 'schema' ),
				'std'      => '#222222',
			),
			array(
				'id'       => 'mts_footer_bg_pattern',
				'type'     => 'radio_img',
				'title'    => __( 'Footer Background Pattern', 'schema' ),
				'sub_desc' => __( 'Choose from any of <strong>25</strong> awesome background patterns for your site\'s background.', 'schema' ),
				'options'  => array(
					'nobg'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/nobg.png' ),
					'hbg'   => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg.png' ),
					'hbg2'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg2.png' ),
					'hbg3'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg3.png' ),
					'hbg4'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg4.png' ),
					'hbg5'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg5.png' ),
					'hbg6'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg6.png' ),
					'hbg7'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg7.png' ),
					'hbg8'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg8.png' ),
					'hbg9'  => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg9.png' ),
					'hbg10' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg10.png' ),
					'hbg11' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg11.png' ),
					'hbg12' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg12.png' ),
					'hbg13' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg13.png' ),
					'hbg14' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg14.png' ),
					'hbg15' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg15.png' ),
					'hbg16' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg16.png' ),
					'hbg17' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg17.png' ),
					'hbg18' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg18.png' ),
					'hbg19' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg19.png' ),
					'hbg20' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg20.png' ),
					'hbg21' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg21.png' ),
					'hbg22' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg22.png' ),
					'hbg23' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg23.png' ),
					'hbg24' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg24.png' ),
					'hbg25' => array( 'img' => NHP_OPTIONS_URL . 'img/patterns/hbg25.png' ),
				),
				'std'      => 'nobg',
			),
			array(
				'id'       => 'mts_footer_bg_pattern_upload',
				'type'     => 'upload',
				'title'    => __( 'Custom Footer Background Image', 'schema' ),
				'sub_desc' => __( 'Upload your own custom footer background image or pattern.', 'schema' ),
			),
			array(
				'id'       => 'mts_copyrights',
				'type'     => 'textarea',
				'title'    => __( 'Copyrights Text', 'schema' ),
				'sub_desc' => __( 'You can change or remove our link from footer and use your own custom text.', 'schema' ) . ( MTS_THEME_WHITE_LABEL ? '' : wp_kses( __( '(You can also use your affiliate link to <strong>earn 70% of sales</strong>. Ex: <a href="https://mythemeshop.com/go/aff/aff" target="_blank">https://mythemeshop.com/?ref=username</a>)', 'schema' ), array(
					'strong' => '',
					'a'      => array(
						'href'   => array(),
						'target' => array(),
					),
				) ) ),
				// translators: MyThemeShop.
				'std'      => MTS_THEME_WHITE_LABEL ? null : sprintf( __( 'Theme by %s', 'schema' ), '<a href="http://mythemeshop.com/" rel="nofollow">MyThemeShop</a>' ),
			),
			array(
				'id'       => 'mts_copyrights_bg_color',
				'type'     => 'color',
				'title'    => __( 'Copyrights Background Color', 'schema' ),
				'sub_desc' => __( 'Pick a color for the Copyrights section background color.', 'schema' ),
				'std'      => '#ffffff',
			),
		),
	);
	$sections[] = array(
		'icon'   => 'fa fa-file-text',
		'title'  => __( 'Single Posts', 'schema' ),
		'desc'   => '<p class="description">' . __( 'From here, you can control the appearance and functionality of your single posts page.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_single_post_layout',
				'type'     => 'layout2',
				'title'    => __( 'Single Post Layout', 'schema' ),
				'sub_desc' => __( 'Customize the look of single posts', 'schema' ),
				'options'  => array(
					'enabled'  => array(
						'content' => array(
							'label'     => __( 'Post Content', 'schema' ),
							'subfields' => array(),
						),
						'related' => array(
							'label'     => __( 'Related Posts', 'schema' ),
							'subfields' => array(
								array(
									'id'       => 'mts_related_posts_taxonomy',
									'type'     => 'button_set',
									'title'    => __( 'Related Posts Taxonomy', 'schema' ),
									'options'  => array(
										'tags'       => __( 'Tags', 'schema' ),
										'categories' => __( 'Categories', 'schema' ),
									),
									'class'    => 'green',
									'sub_desc' => __( 'Related Posts based on tags or categories.', 'schema' ),
									'std'      => 'categories',
								),
								array(
									'id'       => 'mts_related_postsnum',
									'type'     => 'text',
									'class'    => 'small-text',
									'title'    => __( 'Number of related posts', 'schema' ),
									'sub_desc' => __( 'Enter the number of posts to show in the related posts section.', 'schema' ),
									'std'      => '3',
									'args'     => array(
										'type' => 'number',
									),
								),
							),
						),
						'author'  => array(
							'label'     => __( 'Author Box', 'schema' ),
							'subfields' => array(),
						),
					),
					'disabled' => array(
						'tags' => array(
							'label'     => __( 'Tags', 'schema' ),
							'subfields' => array(),
						),
					),
				),
			),
			array(
				'id'               => 'mts_single_headline_meta_info',
				'type'             => 'layout',
				'title'            => __( 'Meta Info to Show', 'schema' ),
				'sub_desc'         => __( 'Organize how you want the post meta info to appear', 'schema' ),
				'options'          => array(
					'enabled'  => array(
						'author'   => __( 'Author Name', 'schema' ),
						'date'     => __( 'Date', 'schema' ),
						'category' => __( 'Categories', 'schema' ),
						'comment'  => __( 'Comment Count', 'schema' ),
					),
					'disabled' => array(),
				),
				'std'              => array(
					'enabled'  => array(
						'author'   => __( 'Author Name', 'schema' ),
						'date'     => __( 'Date', 'schema' ),
						'category' => __( 'Categories', 'schema' ),
						'comment'  => __( 'Comment Count', 'schema' ),
					),
					'disabled' => array(),
				),
				'reset_at_version' => '3.0',
			),
			array(
				'id'       => 'mts_breadcrumb',
				'type'     => 'button_set',
				'title'    => __( 'Breadcrumbs', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'Breadcrumbs are a great way to make your site more user-friendly. You can enable them by checking this box.', 'schema' ),
				'std'      => '1',
			),
			array(
				'id'       => 'mts_show_featured',
				'type'     => 'button_set',
				'title'    => __( 'Show Featured image', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'Enable/Disable the Featured images in the single post.', 'schema' ),
				'std'      => '0',
			),
			array(
				'id'       => 'mts_author_comment',
				'type'     => 'button_set',
				'title'    => __( 'Highlight Author Comment', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'Use this button to highlight author comments.', 'schema' ),
				'std'      => '1',
			),
			array(
				'id'       => 'mts_comment_date',
				'type'     => 'button_set',
				'title'    => __( 'Date in Comments', 'schema' ),
				'options'  => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc' => __( 'Use this button to show the date for comments.', 'schema' ),
				'std'      => '1',
			),
		),
	);
	$sections[] = array(
		'icon'   => 'fa fa-group',
		'title'  => __( 'Social Buttons', 'schema' ),
		'desc'   => '<p class="description">' . __( 'Enable or disable social sharing buttons on single posts using these buttons.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'               => 'mts_social_button_layout',
				'type'             => 'radio_img',
				'title'            => __( 'Social Sharing Buttons Layout', 'schema' ),
				'sub_desc'         => wp_kses( __( 'Choose default <strong>social sharing buttons</strong> layout or modern <strong>social sharing buttons</strong> layout for your site. ', 'schema' ), array( 'strong' => array() ) ),
				'options'          => array(
					'default'  => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/default-social.jpg' ),
					'modern'   => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/modern-social.jpg' ),
					'circular' => array( 'img' => NHP_OPTIONS_URL . 'img/layouts/circular.jpg' ),
				),
				'std'              => 'default',
				'reset_at_version' => '3.9.23',
			),
			array(
				'id'       => 'mts_social_button_position',
				'type'     => 'button_set',
				'title'    => __( 'Social Sharing Buttons Position', 'schema' ),
				'options'  => array(
					'top'      => __( 'Above Content', 'schema' ),
					'bottom'   => __( 'Below Content', 'schema' ),
					'floating' => __( 'Floating', 'schema' ),
				),
				'sub_desc' => __( 'Choose position for Social Sharing Buttons.', 'schema' ),
				'std'      => 'floating',
				'class'    => 'green',
			),
			array(
				'id'               => 'mts_social_buttons_on_pages',
				'type'             => 'button_set',
				'title'            => __( 'Social Sharing Buttons on Pages', 'schema' ),
				'options'          => array(
					'0' => __( 'Off', 'schema' ),
					'1' => __( 'On', 'schema' ),
				),
				'sub_desc'         => __( 'Enable the sharing buttons for pages too, not just posts.', 'schema' ),
				'std'              => '0',
				'reset_at_version' => '3.0',
			),
			array(
				'id'               => 'mts_social_buttons',
				'type'             => 'layout',
				'title'            => __( 'Social Media Buttons', 'schema' ),
				'sub_desc'         => __( 'Organize how you want the social sharing buttons to appear on single posts', 'schema' ),
				'options'          => array(
					'enabled'  => array(
						'facebookshare' => __( 'Facebook Share', 'schema' ),
						'facebook'      => __( 'Facebook Like', 'schema' ),
						'twitter'       => __( 'Twitter', 'schema' ),
						'pinterest'     => __( 'Pinterest', 'schema' ),
					),
					'disabled' => array(
						'linkedin'  => __( 'LinkedIn', 'schema' ),
						'stumble'   => __( 'Mix', 'schema' ),
						'reddit'    => __( 'Reddit', 'schema' ),
						'whatsapp'  => __('WhatsApp', 'schema' ),
						'telegram'  => __('Telegram', 'schema' ),
						'tumblr'    => __('Tumblr', 'schema' ),
						'pocket'    => __('Pocket', 'schema' ),
						'buffer'    => __('Buffer', 'schema' ),
						'vkontakte' => __('Vkontakte', 'schema' ),
					),
				),
				'std'              => array(
					'enabled'  => array(
						'facebookshare' => __( 'Facebook Share', 'schema' ),
						'facebook'      => __( 'Facebook Like', 'schema' ),
						'twitter'       => __( 'Twitter', 'schema' ),
						'pinterest'     => __( 'Pinterest', 'schema' ),
					),
					'disabled' => array(
						'linkedin'  => __( 'LinkedIn', 'schema' ),
						'stumble'   => __( 'Mix', 'schema' ),
						'reddit'    => __( 'Reddit', 'schema' ),
						'whatsapp'  => __('WhatsApp', 'schema' ),
						'telegram'  => __('Telegram', 'schema' ),
						'tumblr'    => __('Tumblr', 'schema' ),
						'pocket'    => __('Pocket', 'schema' ),
						'buffer'    => __('Buffer', 'schema' ),
						'vkontakte' => __('Vkontakte', 'schema' ),
					),
				),
				'reset_at_version' => '3.9.6',
			),
		),
	);
	$sections[] = array(
		'icon'   => 'fa fa-bar-chart-o',
		'title'  => __( 'Ad Management', 'schema' ),
		'desc'   => '<p class="description">' . __( 'Now, ad management is easy with our options panel. You can control everything from here, without using separate plugins.', 'schema' ) . '</p>',
		'fields' => array(
			array(
				'id'       => 'mts_header_adcode',
				'type'     => 'textarea',
				'title'    => __( 'Header Ad', 'schema' ),
				'sub_desc' => __( 'Paste your Adsense, BSA or other ad code here to show ads in Header Area.', 'schema' ),
			),
			array(
				'id'       => 'mts_posttop_adcode',
				'type'     => 'textarea',
				'title'    => __( 'Below Post Title', 'schema' ),
				'sub_desc' => __( 'Paste your Adsense, BSA or other ad code here to show ads below your article title on single posts.', 'schema' ),
			),
			array(
				'id'       => 'mts_posttop_adcode_time',
				'type'     => 'text',
				'title'    => __( 'Show After X Days', 'schema' ),
				'sub_desc' => __( 'Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', 'schema' ),
				'validate' => 'numeric',
				'std'      => '0',
				'class'    => 'small-text',
				'args'     => array( 'type' => 'number' ),
			),
			array(
				'id'       => 'mts_postend_adcode',
				'type'     => 'textarea',
				'title'    => __( 'Below Post Content', 'schema' ),
				'sub_desc' => __( 'Paste your Adsense, BSA or other ad code here to show ads below the post content on single posts.', 'schema' ),
			),
			array(
				'id'       => 'mts_postend_adcode_time',
				'type'     => 'text',
				'title'    => __( 'Show After X Days', 'schema' ),
				'sub_desc' => __( 'Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', 'schema' ),
				'validate' => 'numeric',
				'std'      => '0',
				'class'    => 'small-text',
				'args'     => array( 'type' => 'number' ),
			),
		),
	);
	$sections[] = array(
		'icon'   => 'fa fa-columns',
		'title'  => __( 'Sidebars', 'schema' ),
		'desc'   => '<p class="description">' . __( 'Now you have full control over the sidebars. Here you can manage sidebars and select one for each section of your site, or select a custom sidebar on a per-post basis in the post editor.', 'schema' ) . '<br></p>',
		'fields' => array(
			array(
				'id'        => 'mts_custom_sidebars',
				'type'      => 'group', // Doesn't need to be called for callback fields.
				'title'     => __( 'Custom Sidebars', 'schema' ),
				'sub_desc'  => wp_kses( __( 'Add custom sidebars. <strong style="font-weight: 800;">You need to save the changes to use the sidebars in the dropdowns below.</strong><br />You can add content to the sidebars in Appearance &gt; Widgets.', 'schema' ), array(
					'strong' => '',
					'br'     => '',
				) ),
				'groupname' => __( 'Sidebar', 'schema' ), // Group name.
				'subfields' =>
				array(
					array(
						'id'       => 'mts_custom_sidebar_name',
						'type'     => 'text',
						'title'    => __( 'Name', 'schema' ),
						'sub_desc' => __( 'Example: Homepage Sidebar', 'schema' ),
					),
					array(
						'id'       => 'mts_custom_sidebar_id',
						'type'     => 'text',
						'title'    => __( 'ID', 'schema' ),
						'sub_desc' => __( 'Enter a unique ID for the sidebar. Use only alphanumeric characters, underscores (_) and dashes (-), eg. "sidebar-home"', 'schema' ),
						'std'      => 'sidebar-',
					),
				),
			),
			array(
				'id'       => 'mts_sidebar_for_home',
				'type'     => 'sidebars_select',
				'title'    => __( 'Homepage', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the homepage.', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_post',
				'type'     => 'sidebars_select',
				'title'    => __( 'Single Post', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the single posts. If a post has a custom sidebar set, it will override this.', 'schema' ),
				'args'     => array( 'exclude' => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ) ),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_page',
				'type'     => 'sidebars_select',
				'title'    => __( 'Single Page', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the single pages. If a page has a custom sidebar set, it will override this.', 'schema' ),
				'args'     => array( 'exclude' => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ) ),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_archive',
				'type'     => 'sidebars_select',
				'title'    => __( 'Archive', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the archives. Specific archive sidebars will override this setting (see below).', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_category',
				'type'     => 'sidebars_select',
				'title'    => __( 'Category Archive', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the category archives.', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_tag',
				'type'     => 'sidebars_select',
				'title'    => __( 'Tag Archive', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the tag archives.', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_date',
				'type'     => 'sidebars_select',
				'title'    => __( 'Date Archive', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the date archives.', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_author',
				'type'     => 'sidebars_select',
				'title'    => __( 'Author Archive', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the author archives.', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_search',
				'type'     => 'sidebars_select',
				'title'    => __( 'Search', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the search results.', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),
			array(
				'id'       => 'mts_sidebar_for_notfound',
				'type'     => 'sidebars_select',
				'title'    => __( '404 Error', 'schema' ),
				'sub_desc' => __( 'Select a sidebar for the 404 Not found pages.', 'schema' ),
				'args'     => array(
					'allow_nosidebar' => false,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => '',
			),

			array(
				'id'       => 'mts_sidebar_for_shop',
				'type'     => 'sidebars_select',
				'title'    => __( 'Shop Pages', 'schema' ),
				'sub_desc' => wp_kses( __( 'Select a sidebar for Shop main page and product archive pages (WooCommerce plugin must be enabled). Default is <strong>Shop Page Sidebar</strong>.', 'schema' ), array( 'strong' => '' ) ),
				'args'     => array(
					'allow_nosidebar' => true,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => 'shop-sidebar',
			),
			array(
				'id'       => 'mts_sidebar_for_product',
				'type'     => 'sidebars_select',
				'title'    => __( 'Single Product', 'schema' ),
				'sub_desc' => wp_kses( __( 'Select a sidebar for single products (WooCommerce plugin must be enabled). Default is <strong>Single Product Sidebar</strong>.', 'schema' ), array( 'strong' => '' ) ),
				'args'     => array(
					'allow_nosidebar' => true,
					'exclude'         => array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'widget-header', 'shop-sidebar', 'product-sidebar' ),
				),
				'std'      => 'product-sidebar',
			),
		),
	);

	$sections[] = array(
		'icon'  => 'fa fa-list-alt',
		'title' => __( 'Navigation', 'schema' ),
		// translators: Menu Link.
		'desc'  => '<p class="description"><div class="controls">' . sprintf( __( 'Navigation settings can now be modified from the %s.', 'schema' ), '<a href="nav-menus.php"><b>' . __( 'Menus Section', 'schema' ) . '</b></a>' ) . '<br></div></p>',
	);

	$tabs = array();

	$args['presets']        = array();
	$args['show_translate'] = false;
	include 'theme-presets.php';

	global $NHP_Options; // PHPCS:ignore
	$NHP_Options = new NHP_Options( $sections, $args, $tabs );  // PHPCS:ignore

}
add_action( 'init', 'setup_framework_options', 0 );

/**
 * Custom function for the callback validation referenced above
 */
function validate_callback_function( $field, $value, $existing_value ) {

	$error = false;
	$value = 'just testing';

	$return['value'] = $value;
	if ( true === $error ) {
		$return['error'] = $field;
	}
	return $return;

}

/**
 * Default Typography Settings
 */
if ( function_exists( 'mts_register_typography' ) ) {
	mts_register_typography(
		array(

			'Logo Font'  => array(
				'preview_text'   => 'Logo',
				'preview_color'  => 'light',
				'font_family'    => 'Roboto Slab',
				'font_variant'   => 'normal',
				'font_size'      => '32px',
				'font_color'     => '#222',
				'css_selectors'  => '#logo a',
				'additional_css' => 'text-transform: uppercase;',
			),

			'primary_navigation_font' => array(
				'preview_text'  => 'Primary Navigation Font',
				'preview_color' => 'light',
				'font_family'   => 'Raleway',
				'font_variant'  => '500',
				'font_size'     => '13px',
				'font_color'    => '#777',
				'css_selectors' => '#primary-navigation a, .mts-cart',
			),

			'secondary_navigation_font' => array(
				'preview_text'   => 'Secondary Navigation Font',
				'preview_color'  => 'dark',
				'font_family'    => 'Raleway',
				'font_variant'   => '700',
				'font_size'      => '16px',
				'font_color'     => '#fff',
				'css_selectors'  => '#secondary-navigation a',
				'additional_css' => 'text-transform: uppercase;',
			),

			'home_title_font' => array(
				'preview_text'  => 'Home Article Title',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_size'     => '38px',
				'font_variant'  => '300',
				'font_color'    => '#0274BE',
				'css_selectors' => '.latestPost .title a',
			),

			'single_title_font' => array(
				'preview_text'  => 'Single Article Title',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_size'     => '38px',
				'font_variant'  => '300',
				'font_color'    => '#222',
				'css_selectors' => '.single-title',
			),

			'content_font' => array(
				'preview_text'  => 'Content Font',
				'preview_color' => 'light',
				'font_family'   => 'Raleway',
				'font_size'     => '16px',
				'font_variant'  => '500',
				'font_color'    => '#444444',
				'css_selectors' => 'body',
			),

			'sidebar_title_font' => array(
				'preview_text'   => 'Sidebar Title Font',
				'preview_color'  => 'light',
				'font_family'    => 'Roboto Slab',
				'font_variant'   => 'normal',
				'font_size'      => '20px',
				'font_color'     => '#222222',
				'css_selectors'  => '#sidebar .widget h3',
				'additional_css' => 'text-transform: uppercase;',
			),

			'sidebar_font' => array(
				'preview_text'  => 'Sidebar Font',
				'preview_color' => 'light',
				'font_family'   => 'Raleway',
				'font_variant'  => '500',
				'font_size'     => '16px',
				'font_color'    => '#444444',
				'css_selectors' => '#sidebar .widget',
			),

			'top_footer_title_font' => array(
				'preview_text'   => 'Footer Title Font',
				'preview_color'  => 'dark',
				'font_family'    => 'Roboto Slab',
				'font_variant'   => 'normal',
				'font_size'      => '18px',
				'font_color'     => '#ffffff',
				'css_selectors'  => '.footer-widgets h3',
				'additional_css' => 'text-transform: uppercase;',
			),

			'top_footer_link_font' => array(
				'preview_text'  => 'Footer Links',
				'preview_color' => 'dark',
				'font_family'   => 'Raleway',
				'font_variant'  => '500',
				'font_size'     => '16px',
				'font_color'    => '#999999',
				'css_selectors' => '.f-widget a, footer .wpt_widget_content a, footer .wp_review_tab_widget_content a, footer .wpt_tab_widget_content a, footer .widget .wp_review_tab_widget_content a',
			),

			'top_footer_font' => array(
				'preview_text'  => 'Footer Font',
				'preview_color' => 'dark',
				'font_family'   => 'Raleway',
				'font_variant'  => '500',
				'font_size'     => '16px',
				'font_color'    => '#777777',
				'css_selectors' => '.footer-widgets, .f-widget .top-posts .comment_num, footer .meta, footer .twitter_time, footer .widget .wpt_widget_content .wpt-postmeta, footer .widget .wpt_comment_content, footer .widget .wpt_excerpt, footer .wp_review_tab_widget_content .wp-review-tab-postmeta, footer .advanced-recent-posts p, footer .popular-posts p, footer .category-posts p',
			),

			'copyrights_font' => array(
				'preview_text'  => 'Copyrights Font',
				'preview_color' => 'dark',
				'font_family'   => 'Raleway',
				'font_variant'  => '500',
				'font_size'     => '14px',
				'font_color'    => '#7e7d7d',
				'css_selectors' => '#copyright-note',
			),

			'h1_headline' => array(
				'preview_text'  => 'H1 Headline',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_variant'  => 'normal',
				'font_size'     => '28px',
				'font_color'    => '#222222',
				'css_selectors' => 'h1',
			),

			'h2_headline' => array(
				'preview_text'  => 'H2 Headline',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_variant'  => 'normal',
				'font_size'     => '24px',
				'font_color'    => '#222222',
				'css_selectors' => 'h2',
			),

			'h3_headline' => array(
				'preview_text'  => 'H3 Headline',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_variant'  => 'normal',
				'font_size'     => '22px',
				'font_color'    => '#222222',
				'css_selectors' => 'h3',
			),

			'h4_headline' => array(
				'preview_text'  => 'H4 Headline',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_variant'  => 'normal',
				'font_size'     => '20px',
				'font_color'    => '#222222',
				'css_selectors' => 'h4',
			),

			'h5_headline' => array(
				'preview_text'  => 'H5 Headline',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_variant'  => 'normal',
				'font_size'     => '18px',
				'font_color'    => '#222222',
				'css_selectors' => 'h5',
			),

			'h6_headline' => array(
				'preview_text'  => 'H6 Headline',
				'preview_color' => 'light',
				'font_family'   => 'Roboto Slab',
				'font_variant'  => 'normal',
				'font_size'     => '16px',
				'font_color'    => '#222222',
				'css_selectors' => 'h6',
			),
		)
	);
}
