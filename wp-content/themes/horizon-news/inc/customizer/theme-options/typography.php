<?php

/**
 * Typography
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_typography',
	array(
		'panel' => 'horizon_news_theme_options',
		'title' => esc_html__( 'Typography', 'horizon-news' ),
	)
);

// Typography - Site Title Font.
$wp_customize->add_setting(
	'horizon_news_site_title_font',
	array(
		'default'           => 'Mukta',
		'sanitize_callback' => 'horizon_news_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'horizon_news_site_title_font',
	array(
		'label'    => esc_html__( 'Site Title Font Family', 'horizon-news' ),
		'section'  => 'horizon_news_typography',
		'settings' => 'horizon_news_site_title_font',
		'type'     => 'select',
		'choices'  => horizon_news_get_all_google_font_families(),
	)
);

// Typography - Site Description Font.
$wp_customize->add_setting(
	'horizon_news_site_description_font',
	array(
		'default'           => 'Inter',
		'sanitize_callback' => 'horizon_news_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'horizon_news_site_description_font',
	array(
		'label'    => esc_html__( 'Site Description Font Family', 'horizon-news' ),
		'section'  => 'horizon_news_typography',
		'settings' => 'horizon_news_site_description_font',
		'type'     => 'select',
		'choices'  => horizon_news_get_all_google_font_families(),
	)
);

// Typography - Header Font.
$wp_customize->add_setting(
	'horizon_news_header_font',
	array(
		'default'           => 'Inter',
		'sanitize_callback' => 'horizon_news_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'horizon_news_header_font',
	array(
		'label'    => esc_html__( 'Header Font Family', 'horizon-news' ),
		'section'  => 'horizon_news_typography',
		'settings' => 'horizon_news_header_font',
		'type'     => 'select',
		'choices'  => horizon_news_get_all_google_font_families(),
	)
);

// Typography - Body Font.
$wp_customize->add_setting(
	'horizon_news_body_font',
	array(
		'default'           => 'Inter',
		'sanitize_callback' => 'horizon_news_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'horizon_news_body_font',
	array(
		'label'    => esc_html__( 'Body Font Family', 'horizon-news' ),
		'section'  => 'horizon_news_typography',
		'settings' => 'horizon_news_body_font',
		'type'     => 'select',
		'choices'  => horizon_news_get_all_google_font_families(),
	)
);
