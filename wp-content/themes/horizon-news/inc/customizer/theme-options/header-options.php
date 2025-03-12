<?php
/**
 * Header Options
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_header_options',
	array(
		'panel' => 'horizon_news_theme_options',
		'title' => esc_html__( 'Header Options', 'horizon-news' ),
	)
);

// Header Options - Enable Topbar.
$wp_customize->add_setting(
	'horizon_news_enable_topbar',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_switch',
		'default'           => false,
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_enable_topbar',
		array(
			'label'   => esc_html__( 'Enable Topbar', 'horizon-news' ),
			'section' => 'horizon_news_header_options',
		)
	)
);

// Header Options - Advertisement.
$wp_customize->add_setting(
	'horizon_news_header_advertisement',
	array(
		'default'           => '',
		'sanitize_callback' => 'horizon_news_sanitize_image',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'horizon_news_header_advertisement',
		array(
			'label'    => esc_html__( 'Advertisement', 'horizon-news' ),
			'section'  => 'horizon_news_header_options',
			'settings' => 'horizon_news_header_advertisement',
		)
	)
);

	// Header Options - Advertisement URL.
$wp_customize->add_setting(
	'horizon_news_header_advertisement_url',
	array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'horizon_news_header_advertisement_url',
	array(
		'label'    => esc_html__( 'Advertisement URL', 'horizon-news' ),
		'section'  => 'horizon_news_header_options',
		'settings' => 'horizon_news_header_advertisement_url',
		'type'     => 'url',
	)
);
