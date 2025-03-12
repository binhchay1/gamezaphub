<?php
/**
 * Excerpt
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_excerpt_options',
	array(
		'panel' => 'horizon_news_theme_options',
		'title' => esc_html__( 'Excerpt', 'horizon-news' ),
	)
);

// Excerpt - Excerpt Length.
$wp_customize->add_setting(
	'horizon_news_excerpt_length',
	array(
		'default'           => 20,
		'sanitize_callback' => 'horizon_news_sanitize_number_range',
		'validate_callback' => 'horizon_news_validate_excerpt_length',
	)
);

$wp_customize->add_control(
	'horizon_news_excerpt_length',
	array(
		'label'       => esc_html__( 'Excerpt Length (no. of words)', 'horizon-news' ),
		'description' => esc_html__( 'Note: Min 1 & Max 200. Please input the valid number and save. Then refresh the page to see the change.', 'horizon-news' ),
		'section'     => 'horizon_news_excerpt_options',
		'settings'    => 'horizon_news_excerpt_length',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 200,
			'step' => 1,
		),
	)
);
