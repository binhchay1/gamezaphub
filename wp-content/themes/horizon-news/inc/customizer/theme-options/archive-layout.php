<?php
/**
 * Archive Layout
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_archive_layout',
	array(
		'title' => esc_html__( 'Archive Layout', 'horizon-news' ),
		'panel' => 'horizon_news_theme_options',
	)
);

// Archive Layout - Grid Style.
$wp_customize->add_setting(
	'horizon_news_archive_grid_style',
	array(
		'default'           => 'grid-column-2',
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_archive_grid_style',
	array(
		'label'   => esc_html__( 'Grid Style', 'horizon-news' ),
		'section' => 'horizon_news_archive_layout',
		'type'    => 'select',
		'choices' => array(
			'grid-column-2' => __( 'Column 2', 'horizon-news' ),
			'grid-column-3' => __( 'Column 3', 'horizon-news' ),
		),
	)
);
