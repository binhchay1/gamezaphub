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

	// Slick style.
	wp_enqueue_style('horizon-news-slick-style', get_template_directory_uri() . '/assets/css/slick' . $min . '.css', array(), '1.8.1');

	// Fontawesome style.
	wp_enqueue_style('horizon-news-fontawesome-style', get_template_directory_uri() . '/assets/css/fontawesome' . $min . '.css', array(), '6.4.2');

	// Google fonts.
	wp_enqueue_style('horizon-news-google-fonts', wptt_get_webfont_url(horizon_news_get_fonts_url()), array(), null);

	// Main style.
	wp_enqueue_style('horizon-news-style', get_template_directory_uri() . '/style.css', array(), HORIZON_NEWS_VERSION);

	// Custom style
	wp_enqueue_style('custom-by-binh', get_template_directory_uri() . '/assets/css/custom.css', array(), '1.0.0');

	// Navigation script.
	wp_enqueue_script('horizon-news-navigation-script', get_template_directory_uri() . '/assets/js/navigation' . $min . '.js', array(), HORIZON_NEWS_VERSION, true);

	// Slick script.
	wp_enqueue_script('horizon-news-slick-script', get_template_directory_uri() . '/assets/js/slick' . $min . '.js', array('jquery'), '1.8.1', true);

	// jQuery marquee script.
	wp_enqueue_script('horizon-news-marquee-script', get_template_directory_uri() . '/assets/js/jquery.marquee' . $min . '.js', array('jquery'), '1.6.0', true);

	// Custom script.
	wp_enqueue_script('horizon-news-custom-script', get_template_directory_uri() . '/assets/js/custom' . $min . '.js', array('jquery'), HORIZON_NEWS_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
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

function custom_table_block_styles()
{
	wp_enqueue_style(
		'custom-table-styles',
		get_template_directory_uri() . '/assets/css/custom-table.css',
		array(),
		'1.0'
	);
}
add_action('enqueue_block_assets', 'custom_table_block_styles');

function custom_table_block_render($block_content, $block)
{
	if ($block['blockName'] === 'core/table') {
		$block_content = '<div class="custom-table-wrapper">' .
			str_replace(
				'<table>',
				'<table class="custom-table wp-block-table">',
				$block_content
			) .
			'</div>';
	}
	return $block_content;
}
add_filter('render_block', 'custom_table_block_render', 10, 2);

function add_owl_css_to_all_editors()
{
	$owl_js = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';
	$owl_css = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css';

	wp_enqueue_script('custom-owl-js', get_template_directory_uri() . '/assets/js/owl-custom.js', array('jquery', 'owl-frontend-js'), '1.0', true);
	wp_enqueue_style('owl-editor-css', $owl_css, array(), '2.3.4');
	wp_enqueue_script('owl-editor-css', $owl_js, array('jquery'), '2.3.4');
}
add_action('enqueue_block_editor_assets', 'add_owl_css_to_all_editors');

function add_owl_assets_to_frontend()
{
	$owl_js = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';
	$owl_css = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css';

	wp_enqueue_script('custom-owl-js', get_template_directory_uri() . '/assets/js/owl-custom.js', array('jquery', 'owl-frontend-js'), '1.0', true);
	wp_enqueue_style('owl-frontend-css', $owl_css, array(), '2.3.4');
	wp_enqueue_script('owl-frontend-js', $owl_js, array('jquery'), '2.3.4', true);

	if (get_query_var('custom_games') == 1) {
		wp_enqueue_style('custom-single-games-css', get_template_directory_uri() . '/assets/css/single-games.css', array(), '1.0');
		wp_enqueue_script('custom-single-games-js', get_template_directory_uri() . '/assets/js/single-games.js', array('jquery'), '1.0');
	}
}
add_action('wp_enqueue_scripts', 'add_owl_assets_to_frontend');

// Thêm rewrite rules cho các path
function add_custom_rewrite_rules()
{
	add_rewrite_rule(
		'^games/([^/]+)/?$',
		'index.php?custom_games=1&game_slug=$matches[1]',
		'top'
	);

	add_rewrite_rule(
		'^developers/([^/]+)/?$',
		'index.php?custom_developer=1&developer_slug=$matches[1]',
		'top'
	);

	add_rewrite_rule(
		'^publishers/([^/]+)/?$',
		'index.php?custom_publisher=1&publisher_slug=$matches[1]',
		'top'
	);
}
add_action('init', 'add_custom_rewrite_rules');

function add_custom_query_vars($vars)
{
	$vars[] = 'custom_games';
	$vars[] = 'game_slug';

	$vars[] = 'custom_developers';
	$vars[] = 'developer_slug';

	$vars[] = 'custom_publishers';
	$vars[] = 'publisher_slug';

	return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');

function flush_rewrite_rules_on_activation()
{
	add_custom_rewrite_rules();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');

function custom_games_template($template)
{
	if (get_query_var('custom_games') == 1) {
		$new_template = locate_template(array('single-games.php'));
		if (!empty($new_template)) {
			return $new_template;
		}
	} elseif (get_query_var('custom_developers') == 1) {
		$new_template = locate_template(array('single-developers.php'));
		if (!empty($new_template)) {
			return $new_template;
		}
	} elseif (get_query_var('custom_publishers') == 1) {
		$new_template = locate_template(array('single-publishers.php'));
		if (!empty($new_template)) {
			return $new_template;
		}
	}
	return $template;
}
add_filter('template_include', 'custom_games_template');

function get_game_data($slug)
{
	$transient_key = 'game_data_' . md5($slug);
	$cached_data = get_transient($transient_key);
	if ($cached_data !== false) {
		return $cached_data;
	}

	global $wpdb;
	$post = array();
	$meta_key = 'lasso_final_url';
	$query = $wpdb->prepare(
		"SELECT p.ID, p.post_title
         FROM {$wpdb->posts} p
         WHERE p.post_type = 'lasso-urls'
         AND EXISTS (
             SELECT 1
             FROM {$wpdb->postmeta} pm
             WHERE pm.post_id = p.ID
             AND pm.meta_key = %s
             AND pm.meta_value LIKE %s
         )",
		$meta_key,
		'%/games/' . esc_sql($slug) . '%'
	);

	$results = $wpdb->get_results($query);
	if ($results) {
		$post_data = $results[0];
		$post_id = $post_data->ID;
		$meta_results = $wpdb->get_results($wpdb->prepare(
			"SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d",
			$post_id
		));
		$post = array('ID' => $post_id, 'post_title' => $post_data->post_title, 'meta' => array());
		foreach ($meta_results as $meta) {
			$post['meta'][$meta->meta_key] = $meta->meta_value;
		}

		$post['meta']['screen_shots'] = unserialize($post['meta']['screen_shots']);
		$post['meta']['genres'] = unserialize($post['meta']['genres']);
		$post['meta']['platforms'] = unserialize($post['meta']['platforms']);
		$post['meta']['developers'] = unserialize($post['meta']['developers']);
		$post['meta']['publishers'] = unserialize($post['meta']['publishers']);

		$lasso_id = $post['ID'];
		$post_ids = $wpdb->get_col($wpdb->prepare(
			"SELECT detection_id FROM wp_lasso_link_locations WHERE post_id = %d AND display_type = 'Single'",
			$lasso_id
		));

		$args = array(
			'post_type' => 'post',
			'post__in' => $post_ids,
			'posts_per_page' => 5,
			'orderby' => 'post__in',
			'post_status' => 'publish',
			'no_found_rows' => true,
		);
		$related_query = new WP_Query($args);
		$post['related_posts'] = $related_query;

		set_transient($transient_key, $post, 24 * HOUR_IN_SECONDS);
		return $post;
	}

	set_transient($transient_key, false, 24 * HOUR_IN_SECONDS);
	return false;
}

function clear_game_data_transient($slug)
{
	$transient_key = 'game_data_' . md5($slug);
	delete_transient($transient_key);
}

add_action('save_post', function ($post_id) {
	$slug = get_post_field('post_name', $post_id);
	if ($slug) {
		clear_game_data_transient($slug);
	}
});

add_action('init', function () {
	if (is_ssl()) {
		add_filter('secure_auth_cookie', '__return_true');
		add_filter('secure_signed_cookie', '__return_true');
	}
});

add_action('init', function () {
	if (!defined('COOKIE_DOMAIN')) {
		define('COOKIE_DOMAIN', '.gamezpub.com');
	}
});

add_action('template_redirect', function () {
	if (is_404()) {
		status_header(200);
		nocache_headers();
	}
});

add_filter('get_the_archive_title', function ($title) {
	if (is_category() or is_tag()) {
		$title = single_cat_title('', false);
	}

	return '<h1 class="page-title">' . $title . '</h1>';
});

add_filter('get_the_archive_description', function ($description) {
	if (is_category() or is_tag()) {
		$description = strip_tags(category_description());
	}
	return '<div class="archive-description">' . $description . '</div>';
});