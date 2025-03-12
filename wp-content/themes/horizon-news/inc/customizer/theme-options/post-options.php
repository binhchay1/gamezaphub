<?php
/**
 * Post Options
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_post_options',
	array(
		'title' => esc_html__( 'Post Options', 'horizon-news' ),
		'panel' => 'horizon_news_theme_options',
	)
);

// Post Options - Hide Date.
$wp_customize->add_setting(
	'horizon_news_post_hide_date',
	array(
		'default'           => false,
		'sanitize_callback' => 'horizon_news_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_post_hide_date',
		array(
			'label'   => esc_html__( 'Hide Date', 'horizon-news' ),
			'section' => 'horizon_news_post_options',
		)
	)
);

// Post Options - Hide Author.
$wp_customize->add_setting(
	'horizon_news_post_hide_author',
	array(
		'default'           => false,
		'sanitize_callback' => 'horizon_news_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_post_hide_author',
		array(
			'label'   => esc_html__( 'Hide Author', 'horizon-news' ),
			'section' => 'horizon_news_post_options',
		)
	)
);

// Post Options - Hide Category.
$wp_customize->add_setting(
	'horizon_news_post_hide_category',
	array(
		'default'           => false,
		'sanitize_callback' => 'horizon_news_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_post_hide_category',
		array(
			'label'   => esc_html__( 'Hide Category', 'horizon-news' ),
			'section' => 'horizon_news_post_options',
		)
	)
);

// Post Options - Hide Tag.
$wp_customize->add_setting(
	'horizon_news_post_hide_tags',
	array(
		'default'           => false,
		'sanitize_callback' => 'horizon_news_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_post_hide_tags',
		array(
			'label'   => esc_html__( 'Hide Tag', 'horizon-news' ),
			'section' => 'horizon_news_post_options',
		)
	)
);

// Post Options - Related Post Label.
$wp_customize->add_setting(
	'horizon_news_post_related_post_label',
	array(
		'default'           => __( 'Related Posts', 'horizon-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'horizon_news_post_related_post_label',
	array(
		'label'    => esc_html__( 'Related Posts Label', 'horizon-news' ),
		'section'  => 'horizon_news_post_options',
		'settings' => 'horizon_news_post_related_post_label',
		'type'     => 'text',
	)
);
