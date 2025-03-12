<?php
/**
 * Sidebar Position
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_sidebar_position',
	array(
		'title' => esc_html__( 'Sidebar Position', 'horizon-news' ),
		'panel' => 'horizon_news_theme_options',
	)
);

// Sidebar Position - Global Sidebar Position.
$wp_customize->add_setting(
	'horizon_news_sidebar_position',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'horizon_news_sidebar_position',
	array(
		'label'   => esc_html__( 'Global Sidebar Position', 'horizon-news' ),
		'section' => 'horizon_news_sidebar_position',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'horizon-news' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'horizon-news' ),
		),
	)
);

// Sidebar Position - Post Sidebar Position.
$wp_customize->add_setting(
	'horizon_news_post_sidebar_position',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'horizon_news_post_sidebar_position',
	array(
		'label'   => esc_html__( 'Post Sidebar Position', 'horizon-news' ),
		'section' => 'horizon_news_sidebar_position',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'horizon-news' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'horizon-news' ),
		),
	)
);

// Sidebar Position - Page Sidebar Position.
$wp_customize->add_setting(
	'horizon_news_page_sidebar_position',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'horizon_news_page_sidebar_position',
	array(
		'label'   => esc_html__( 'Page Sidebar Position', 'horizon-news' ),
		'section' => 'horizon_news_sidebar_position',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'horizon-news' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'horizon-news' ),
		),
	)
);
