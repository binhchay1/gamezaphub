<?php
/**
 * News Vibrant functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

if ( ! defined( 'NEWS_VIBRANT_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	$news_vibrant_theme_info = wp_get_theme();
	define( 'NEWS_VIBRANT_VERSION', $news_vibrant_theme_info->get( 'Version' ) );
}

if ( ! function_exists( 'news_vibrant_setup' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function news_vibrant_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on News Vibrant, use a find and replace
		 * to change 'news-vibrant' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'news-vibrant', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'news-vibrant-block-medium', 305, 207, true );
	    add_image_size( 'news-vibrant-featured-medium', 345, 218, true );
		add_image_size( 'news-vibrant-block-thumb', 272, 204, true );
		add_image_size( 'news-vibrant-slider-medium', 700, 441, true );
		add_image_size( 'news-vibrant-carousel-portrait', 400, 600, true );
		add_image_size( 'news-vibrant-alternate-grid', 340, 316, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'news_vibrant_top_menu' => esc_html__( 'Top Menu', 'news-vibrant' ),
			'news_vibrant_primary_menu' => esc_html__( 'Primary Menu', 'news-vibrant' ),
			'news_vibrant_footer_menu' => esc_html__( 'Footer Menu', 'news-vibrant' )
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add theme support for Custom Logo.
		add_theme_support( 'custom-logo', array(
			'width'       => 300,
			'height'      => 45,
			'flex-width'  => true,
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'news_vibrant_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
	     * Restoring the classic Widgets Editor
	     * 
	     * @since 1.0.14
	     */
	    $news_vibrant_block_base_widget_option = get_theme_mod( 'news_vibrant_block_base_widget_option', false );
	    if ( false == $news_vibrant_block_base_widget_option ) {
	        remove_theme_support( 'widgets-block-editor' );
	    }
	}

endif;

add_action( 'after_setup_theme', 'news_vibrant_setup' );

remove_action('load-update-core.php', 'wp_update_plugins');
add_filter('pre_site_transient_update_plugins', '__return_null');

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function news_vibrant_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'news_vibrant_content_width', 640 );
}
add_action( 'after_setup_theme', 'news_vibrant_content_width', 0 );

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Set the theme version
 *
 * @global int $news_vibrant_version
 * @since 1.0.0
 */
function news_vibrant_theme_version() {
	$news_vibrant_theme_info = wp_get_theme();
	$GLOBALS['news_vibrant_version'] = $news_vibrant_theme_info->get( 'Version' );
}
add_action( 'after_setup_theme', 'news_vibrant_theme_version', 0 );

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function news_vibrant_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'news_vibrant_pingback_header' );

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Additional features to allow styling of the templates.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Widget function file
 */
require get_template_directory() . '/inc/widgets/nv-widget-functions.php';

/**
 * Custom files for hook
 */
require get_template_directory() . '/inc/hooks/nv-header-hooks.php';
require get_template_directory() . '/inc/hooks/nv-widget-hooks.php';
require get_template_directory() . '/inc/hooks/nv-custom-hooks.php';
require get_template_directory() . '/inc/hooks/nv-footer-hooks.php';

/**
 * Custom files for post metabox
 */
require get_template_directory() . '/inc/metaboxes/nv-post-metabox.php';
require get_template_directory() . '/inc/metaboxes/nv-page-metabox.php';

/**
* Load theme dashboard
*/
require get_template_directory() . '/inc/admin/class-news-vibrant-admin.php';
require get_template_directory() . '/inc/admin/class-news-vibrant-notice.php';
require get_template_directory() . '/inc/admin/class-news-vibrant-dashboard.php';

/**
 * Load TGM
 */
require get_template_directory() . '/inc/tgm/cv-recommend-plugins.php';