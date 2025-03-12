<?php
/**
 * Pagination
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_pagination',
	array(
		'panel' => 'horizon_news_theme_options',
		'title' => esc_html__( 'Pagination', 'horizon-news' ),
	)
);

// Pagination - Enable Pagination.
$wp_customize->add_setting(
	'horizon_news_enable_pagination',
	array(
		'default'           => true,
		'sanitize_callback' => 'horizon_news_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_enable_pagination',
		array(
			'label'    => esc_html__( 'Enable Pagination', 'horizon-news' ),
			'section'  => 'horizon_news_pagination',
			'settings' => 'horizon_news_enable_pagination',
			'type'     => 'checkbox',
		)
	)
);

// Pagination - Pagination Type.
$wp_customize->add_setting(
	'horizon_news_pagination_type',
	array(
		'default'           => 'default',
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_pagination_type',
	array(
		'label'           => esc_html__( 'Pagination Type', 'horizon-news' ),
		'section'         => 'horizon_news_pagination',
		'settings'        => 'horizon_news_pagination_type',
		'active_callback' => 'horizon_news_is_pagination_enabled',
		'type'            => 'select',
		'choices'         => array(
			'default' => __( 'Default (Older/Newer)', 'horizon-news' ),
			'numeric' => __( 'Numeric', 'horizon-news' ),
		),
	)
);
