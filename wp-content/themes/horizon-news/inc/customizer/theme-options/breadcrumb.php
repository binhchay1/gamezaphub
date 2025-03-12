<?php
/**
 * Breadcrumb
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_breadcrumb',
	array(
		'title' => esc_html__( 'Breadcrumb', 'horizon-news' ),
		'panel' => 'horizon_news_theme_options',
	)
);

// Breadcrumb - Enable Breadcrumb.
$wp_customize->add_setting(
	'horizon_news_enable_breadcrumb',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_switch',
		'default'           => true,
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_enable_breadcrumb',
		array(
			'label'   => esc_html__( 'Enable Breadcrumb', 'horizon-news' ),
			'section' => 'horizon_news_breadcrumb',
		)
	)
);

// Breadcrumb - Separator.
$wp_customize->add_setting(
	'horizon_news_breadcrumb_separator',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '/',
	)
);

$wp_customize->add_control(
	'horizon_news_breadcrumb_separator',
	array(
		'label'           => esc_html__( 'Separator', 'horizon-news' ),
		'active_callback' => 'horizon_news_is_breadcrumb_enabled',
		'section'         => 'horizon_news_breadcrumb',
	)
);
