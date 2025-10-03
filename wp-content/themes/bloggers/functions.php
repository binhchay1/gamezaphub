<?php

/**
 * Theme functions and definitions
 *
 * @package bloggers
 */
if (! function_exists('bloggers_enqueue_styles')) :
	/**
	 * @since 0.1
	 */
	function bloggers_enqueue_styles()
	{
		if (!is_admin()) {
			wp_dequeue_style('wp-block-library');
		}

		wp_enqueue_style('blogarise-style-parent', get_template_directory_uri() . '/style.css');
		wp_enqueue_style('bloggers-style', get_stylesheet_directory_uri() . '/style.css', array('blogarise-style-parent'), '1.0');
		wp_dequeue_style('blogarise-default', get_template_directory_uri() . '/css/colors/default.css');
		wp_enqueue_style('bloggers-default-css', get_stylesheet_directory_uri() . "/css/colors/default.css");

		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), null, 'all');
		wp_enqueue_style('bloggers-owl', get_stylesheet_directory_uri() . "/css/owl.carousel.css", array(), null, 'all');

		if (is_rtl()) {
			wp_enqueue_style('blogarise_style_rtl', trailingslashit(get_template_directory_uri()) . 'style-rtl.css');
		}
	}

endif;
add_action('wp_enqueue_scripts', 'bloggers_enqueue_styles', 9999);

/**
 * Defer non-critical CSS
 */
if (!function_exists('bloggers_defer_css')) :
	function bloggers_defer_css($html, $handle, $href, $media)
	{
		$defer_styles = array('bootstrap', 'bloggers-owl');

		if (in_array($handle, $defer_styles)) {
			$html = '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
			$html .= '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>';
		}

		return $html;
	}
endif;
add_filter('style_loader_tag', 'bloggers_defer_css', 10, 4);

function bloggers_theme_setup()
{

	load_theme_textdomain('bloggers', get_stylesheet_directory() . '/languages');

	require(get_stylesheet_directory() . '/font.php');

	$args = array(
		'default-image'      => get_stylesheet_directory_uri() . '/images/head-image.jpg',
		'width'              => 1600,
		'height'             => 600,
		'flex-height'        => false,
		'flex-width'         => false,
		'header-text'        => true,
	);

	add_theme_support('custom-header', $args);

	add_theme_support('title-tag');

	add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'bloggers_theme_setup');

include_once('hooks/custom-block-wp.php');

require_once(get_stylesheet_directory() . '/inc/speed-optimizations.php');

require_once(get_stylesheet_directory() . '/inc/critical-performance.php');

require_once(get_stylesheet_directory() . '/inc/performance-fixes.php');

require_once(get_stylesheet_directory() . '/inc/template-tags.php');

add_action('customize_register', 'bloggers_customizer_rid_values', 1000);
function bloggers_customizer_rid_values($wp_customize)
{
	$wp_customize->remove_control('blogarise_content_layout');
	$wp_customize->remove_control('blogarise_title_font_size');
}

if (! function_exists('bloggers_admin_scripts')) :
	function bloggers_admin_scripts()
	{
		wp_enqueue_style('bloggers-admin-style-css', get_stylesheet_directory_uri() . '/css/customizer-controls.css');
	}
endif;
add_action('admin_enqueue_scripts', 'bloggers_admin_scripts');

/**
 * banner additions.
 */
require get_stylesheet_directory() . '/hooks/hook-front-page-main-banner-section.php';

if (!function_exists('bloggers_get_block')) :
	/**
	 *
	 * @param null
	 *
	 * @return null
	 *
	 * @since bloggers 1.0.0
	 *
	 */
	function bloggers_get_block($block = 'grid', $section = 'post')
	{
		get_template_part('hooks/blocks/block-' . $section, $block);
	}
endif;

function bloggers_limit_content_chr($content, $limit = 100)
{
	return mb_strimwidth(strip_tags($content), 0, $limit, '...');
}

$args = array(
	'default-color' => '#FFF6E6',
	'default-image' => '',
);
add_theme_support('custom-background', $args);

function bloggers_bg_image_wrapper()
{
?>
	<div class="bloggers-background-wrapper">
		<div class="squares">
			<span class="square"></span>
			<span class="square"></span>
			<span class="square"></span>
			<span class="square"></span>
			<span class="square"></span>
		</div>
		<div class="circles">
			<span class="circle"></span>
			<span class="circle"></span>
			<span class="circle"></span>
			<span class="circle"></span>
			<span class="circle"></span>
		</div>
		<div class="triangles">
			<span class="triangle"></span>
			<span class="triangle"></span>
			<span class="triangle"></span>
			<span class="triangle"></span>
			<span class="triangle"></span>
		</div>
	</div>
<?php
}
add_action('wp_footer', 'bloggers_bg_image_wrapper');

add_filter('all_plugins', function ($plugins) {
	foreach ($plugins as $key => &$plugin) {
		if (is_object($plugin) && !isset($plugin->plugin)) {
			$plugin->plugin = $key;
		}
	}
	return $plugins;
});

/**
 * Enqueue Performance Optimizer Script
 * Prevent forced reflows and optimize layout performance
 * MUST load before other scripts to wrap operations
 */
if (!function_exists('bloggers_enqueue_performance_optimizer')) :
	function bloggers_enqueue_performance_optimizer()
	{
		wp_enqueue_script(
			'bloggers-performance-optimizer',
			get_stylesheet_directory_uri() . '/js/performance-optimizer.js',
			array(),
			'1.0.0',
			false
		);

		wp_script_add_data('bloggers-performance-optimizer', 'defer', true);
	}
endif;
add_action('wp_enqueue_scripts', 'bloggers_enqueue_performance_optimizer', 1);

/**
 * NOTE: accessibility-improvements.js is NO LONGER NEEDED
 * All accessibility fixes are now handled server-side in /inc/performance-fixes.php
 * and /inc/template-tags.php for better PageSpeed Insights scores
 */
