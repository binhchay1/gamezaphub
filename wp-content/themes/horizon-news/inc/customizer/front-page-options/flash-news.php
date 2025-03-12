<?php
/**
 * Flash News Section
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_flash_news_section',
	array(
		'panel' => 'horizon_news_front_page_options',
		'title' => esc_html__( 'Flash News Section', 'horizon-news' ),
	)
);

// Flash News Section - Enable Section.
$wp_customize->add_setting(
	'horizon_news_enable_flash_news_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'horizon_news_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_enable_flash_news_section',
		array(
			'label'    => esc_html__( 'Enable Flash News Section', 'horizon-news' ),
			'section'  => 'horizon_news_flash_news_section',
			'settings' => 'horizon_news_enable_flash_news_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'horizon_news_enable_flash_news_section',
		array(
			'selector' => '#horizon_news_flash_news_section .section-link',
			'settings' => 'horizon_news_enable_flash_news_section',
		)
	);
}

// Flash News Section - Section Title.
$wp_customize->add_setting(
	'horizon_news_flash_news_title',
	array(
		'default'           => __( 'Flash News', 'horizon-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'horizon_news_flash_news_title',
	array(
		'label'           => esc_html__( 'Section Title', 'horizon-news' ),
		'section'         => 'horizon_news_flash_news_section',
		'settings'        => 'horizon_news_flash_news_title',
		'type'            => 'text',
		'active_callback' => 'horizon_news_is_flash_news_section_enabled',
	)
);

// Flash News Section - Speed Controller.
$wp_customize->add_setting(
	'horizon_news_flash_news_speed_controller',
	array(
		'default'           => 300,
		'sanitize_callback' => 'horizon_news_sanitize_number_range',
	)
);

$wp_customize->add_control(
	'horizon_news_flash_news_speed_controller',
	array(
		'label'           => esc_html__( 'Speed Controller', 'horizon-news' ),
		'description'     => esc_html__( 'Note: Default speed value is 600.', 'horizon-news' ),
		'section'         => 'horizon_news_flash_news_section',
		'settings'        => 'horizon_news_flash_news_speed_controller',
		'type'            => 'number',
		'input_attrs'     => array(
			'min' => 1,
		),
		'active_callback' => 'horizon_news_is_flash_news_section_enabled',
	)
);

// Flash News Section - Content Type.
$wp_customize->add_setting(
	'horizon_news_flash_news_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_flash_news_content_type',
	array(
		'label'           => esc_html__( 'Select Content Type', 'horizon-news' ),
		'section'         => 'horizon_news_flash_news_section',
		'settings'        => 'horizon_news_flash_news_content_type',
		'type'            => 'select',
		'active_callback' => 'horizon_news_is_flash_news_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'horizon-news' ),
			'category' => esc_html__( 'Category', 'horizon-news' ),
		),
	)
);

for ( $i = 1; $i <= 5; $i++ ) {
	// Flash News Section - Select Post.
	$wp_customize->add_setting(
		'horizon_news_flash_news_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'horizon_news_flash_news_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'horizon-news' ), $i ),
			'section'         => 'horizon_news_flash_news_section',
			'settings'        => 'horizon_news_flash_news_content_post_' . $i,
			'active_callback' => 'horizon_news_is_flash_news_section_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => horizon_news_get_post_choices(),
		)
	);

}

// Flash News Section - Select Category.
$wp_customize->add_setting(
	'horizon_news_flash_news_content_category',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_flash_news_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'horizon-news' ),
		'section'         => 'horizon_news_flash_news_section',
		'settings'        => 'horizon_news_flash_news_content_category',
		'active_callback' => 'horizon_news_is_flash_news_section_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => horizon_news_get_post_cat_choices(),
	)
);
