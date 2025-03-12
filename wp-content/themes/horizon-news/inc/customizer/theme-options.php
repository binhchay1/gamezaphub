<?php

/**
 * Theme Options
 *
 * @package Horizon News
 */

$wp_customize->add_panel(
	'horizon_news_theme_options',
	array(
		'title'    => esc_html__( 'Theme Options', 'horizon-news' ),
		'priority' => 130,
	)
);

// Header Option.
require get_template_directory() . '/inc/customizer/theme-options/header-options.php';

// Typography.
require get_template_directory() . '/inc/customizer/theme-options/typography.php';

// Excerpt.
require get_template_directory() . '/inc/customizer/theme-options/excerpt.php';

// Breadcrumb.
require get_template_directory() . '/inc/customizer/theme-options/breadcrumb.php';

// Archive Layout.
require get_template_directory() . '/inc/customizer/theme-options/archive-layout.php';

// Sidebar Position.
require get_template_directory() . '/inc/customizer/theme-options/sidebar-position.php';

// Post Options.
require get_template_directory() . '/inc/customizer/theme-options/post-options.php';

// Pagination.
require get_template_directory() . '/inc/customizer/theme-options/pagination.php';

// Footer Options.
require get_template_directory() . '/inc/customizer/theme-options/footer-options.php';
