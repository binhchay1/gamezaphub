<?php

/**
 * Horizon News functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Horizon News
 */

if (! defined('HORIZON_NEWS_VERSION')) {
	// Replace the version number of the theme on each release.
	define('HORIZON_NEWS_VERSION', '1.0.0');
}

function initialize_custom_session()
{
	if (!session_id()) {
		session_start();
	}
}
add_action('init', 'initialize_custom_session');

if (! function_exists('horizon_news_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function horizon_news_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Horizon News, use a find and replace
		 * to change 'horizon-news' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('horizon-news', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		add_theme_support('register_block_pattern');

		add_theme_support('register_block_style');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary'   => esc_html__('Primary', 'horizon-news'),
				'social'    => esc_html__('Social', 'horizon-news'),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'horizon_news_custom_background_args',
				array(
					'default-color' => 'efefef',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		/**
		 * Add theme support for gutenberg block.
		 */
		add_theme_support('align-wide');
		add_theme_support('responsive-embeds');
	}
endif;
add_action('after_setup_theme', 'horizon_news_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function horizon_news_content_width()
{
	$GLOBALS['content_width'] = apply_filters('horizon_news_content_width', 640);
}
add_action('after_setup_theme', 'horizon_news_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function horizon_news_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'horizon-news'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'horizon-news'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title"><span>',
			'after_title'   => '</span></h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__('Primary Widgets Section', 'horizon-news'),
			'id'            => 'primary-widgets-section',
			'description'   => esc_html__('Add primary widgets here.', 'horizon-news'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="section-title"><span>',
			'after_title'   => '</span></h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__('Secondary Widgets Section', 'horizon-news'),
			'id'            => 'secondary-widgets-section',
			'description'   => esc_html__('Add secondary widgets here.', 'horizon-news'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="section-title"><span>',
			'after_title'   => '</span></h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__('Above Footer Widgets Section', 'horizon-news'),
			'id'            => 'above-footer-widgets-section',
			'description'   => esc_html__('Add above footer widgets here.', 'horizon-news'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="section-title"><span>',
			'after_title'   => '</span></h3>',
		)
	);

	// Regsiter 3 footer widgets.
	register_sidebars(
		3,
		array(
			/* translators: %d: Footer Widget count. */
			'name'          => esc_html__('Footer Widget %d', 'horizon-news'),
			'id'            => 'footer-widget',
			'description'   => esc_html__('Add widgets here.', 'horizon-news'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="widget-title"><span>',
			'after_title'   => '</span></h6>',
		)
	);
}
add_action('widgets_init', 'horizon_news_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function horizon_news_scripts()
{
	// Append .min if SCRIPT_DEBUG is false.
	$min = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

	wp_enqueue_style('horizon-news-slick-style', get_template_directory_uri() . '/assets/css/slick' . $min . '.css', array(), '1.8.1');
	wp_enqueue_style('horizon-news-fontawesome-style', get_template_directory_uri() . '/assets/css/fontawesome' . $min . '.css', array(), '6.4.2');
	wp_enqueue_style('horizon-news-google-fonts', wptt_get_webfont_url(horizon_news_get_fonts_url()), array(), null);
	wp_enqueue_style('horizon-news-style', get_template_directory_uri() . '/style.css', array(), HORIZON_NEWS_VERSION);
	wp_enqueue_style('custom-by-binh', get_template_directory_uri() . '/assets/css/custom.css', array(), HORIZON_NEWS_VERSION);
	wp_enqueue_style('custom-mobile-by-binh', get_template_directory_uri() . '/assets/css/custom-mobile.css', array(), HORIZON_NEWS_VERSION);
	wp_enqueue_style('custom-table-by-binh', get_template_directory_uri() . '/assets/css/custom-table.css', array(), HORIZON_NEWS_VERSION);

	wp_enqueue_script('horizon-news-navigation-script', get_template_directory_uri() . '/assets/js/navigation' . $min . '.js', array(), HORIZON_NEWS_VERSION, true);
	wp_enqueue_script('custom-by-binh-js', get_template_directory_uri() . '/assets/js/auth.js', array('jquery'), HORIZON_NEWS_VERSION, true);
	wp_enqueue_script('horizon-news-slick-script', get_template_directory_uri() . '/assets/js/slick' . $min . '.js', array('jquery'), '1.8.1', true);
	wp_enqueue_script('horizon-news-marquee-script', get_template_directory_uri() . '/assets/js/jquery.marquee' . $min . '.js', array('jquery'), '1.6.0', true);
	wp_enqueue_script('horizon-news-custom-script', get_template_directory_uri() . '/assets/js/custom' . $min . '.js', array('jquery'), HORIZON_NEWS_VERSION, true);
	wp_enqueue_script('pre-loader-script', get_template_directory_uri() . '/assets/js/pre-loader.js', array(), HORIZON_NEWS_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_localize_script('custom-by-binh-js', 'ajax_url_admin', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('auth_nonce')
	));
}
add_action('wp_enqueue_scripts', 'horizon_news_scripts');

/**
 * Webfont Loader.
 */
require get_template_directory() . '/inc/wptt-webfont-loader.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Widgets.
 */
require get_template_directory() . '/inc/widgets/widgets.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Google Fonts
 */
require get_template_directory() . '/inc/google-fonts.php';

/**
 * Dynamic CSS
 */
require get_template_directory() . '/inc/dynamic-css.php';

/**
 * Breadcrumb
 */
require get_template_directory() . '/inc/class-breadcrumb-trail.php';

/**
 * Recommended Plugins
 */
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Category color.
 */
require get_template_directory() . '/inc/custom-category-color.php';

/**
 * Rewrite rules custom page.
 */
require get_template_directory() . '/inc/rewrite-rules-custom-page.php';

/**
 * Add owl carousel to page
 */
require get_template_directory() . '/inc/add-owl-carousel.php';

/**
 * Handle user authentication
 */
require get_template_directory() . '/inc/auth-handle.php';

/**
 * Handle create db
 */
require get_template_directory() . '/inc/db-handle.php';

/**
 * Handle send mail
 */
require get_template_directory() . '/inc/mail-handle.php';

/**
 * Custom for block in post
 */
require get_template_directory() . '/inc/custom-block.php';

/**
 * Config custom
 */
require get_template_directory() . '/inc/custom-config.php';

/**
 * Helper function
 */
require get_template_directory() . '/inc/helper-func.php';

/**
 * One Click Demo Import after import setup.
 */

if (class_exists('OCDI_Plugin')) {
	require get_template_directory() . '/inc/ocdi.php';
}

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}