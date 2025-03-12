<?php
/**
 * Banner Section
 *
 * @package Horizon News
 */

$wp_customize->add_section(
	'horizon_news_banner_section',
	array(
		'panel' => 'horizon_news_front_page_options',
		'title' => esc_html__( 'Banner Section', 'horizon-news' ),
	)
);

// Banner Section - Enable Section.
$wp_customize->add_setting(
	'horizon_news_enable_banner_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'horizon_news_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Horizon_News_Toggle_Switch_Custom_Control(
		$wp_customize,
		'horizon_news_enable_banner_section',
		array(
			'label'    => esc_html__( 'Enable Banner Section', 'horizon-news' ),
			'section'  => 'horizon_news_banner_section',
			'settings' => 'horizon_news_enable_banner_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'horizon_news_enable_banner_section',
		array(
			'selector' => '#horizon_news_banner_section .section-link',
			'settings' => 'horizon_news_enable_banner_section',
		)
	);
}

// Banner Section - Trending Heading.
$wp_customize->add_setting(
	'horizon_news_banner_trending_section',
	array(
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	new Horizon_News_Title_Control(
		$wp_customize,
		'horizon_news_banner_trending_section',
		array(
			'label'           => __( 'Trending Posts Settings', 'horizon-news' ),
			'section'         => 'horizon_news_banner_section',
			'settings'        => 'horizon_news_banner_trending_section',
			'active_callback' => 'horizon_news_is_banner_section_enabled',
		)
	)
);

// Trending Posts Section - Section Title.
$wp_customize->add_setting(
	'horizon_news_trending_posts_title',
	array(
		'default'           => __( 'Trending Posts', 'horizon-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'horizon_news_trending_posts_title',
	array(
		'label'           => esc_html__( 'Trending Posts Section Title', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_trending_posts_title',
		'type'            => 'text',
		'active_callback' => 'horizon_news_is_banner_section_enabled',
	)
);

// Trending Section - Content Type.
$wp_customize->add_setting(
	'horizon_news_trending_posts_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_trending_posts_content_type',
	array(
		'label'           => esc_html__( 'Select Trending Content Type', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_trending_posts_content_type',
		'type'            => 'select',
		'active_callback' => 'horizon_news_is_banner_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'horizon-news' ),
			'category' => esc_html__( 'Category', 'horizon-news' ),
		),
	)
);

for ( $i = 1; $i <= 6; $i++ ) {
	// Trending Section - Select Post.
	$wp_customize->add_setting(
		'horizon_news_trending_posts_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'horizon_news_trending_posts_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'horizon-news' ), $i ),
			'section'         => 'horizon_news_banner_section',
			'settings'        => 'horizon_news_trending_posts_content_post_' . $i,
			'active_callback' => 'horizon_news_is_trending_posts_section_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => horizon_news_get_post_choices(),
		)
	);

}

// Trending Section - Select Category.
$wp_customize->add_setting(
	'horizon_news_trending_posts_content_category',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_trending_posts_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_trending_posts_content_category',
		'active_callback' => 'horizon_news_is_trending_posts_section_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => horizon_news_get_post_cat_choices(),
	)
);

// Banner Section - Main News.
$wp_customize->add_setting(
	'horizon_news_banner_main_news_section',
	array(
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	new Horizon_News_Title_Control(
		$wp_customize,
		'horizon_news_banner_main_news_section',
		array(
			'label'           => __( 'Main News Settings', 'horizon-news' ),
			'section'         => 'horizon_news_banner_section',
			'settings'        => 'horizon_news_banner_main_news_section',
			'active_callback' => 'horizon_news_is_banner_section_enabled',
		)
	)
);

// Banner Section - Section Title.
$wp_customize->add_setting(
	'horizon_news_main_news_title',
	array(
		'default'           => __( 'Main News', 'horizon-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'horizon_news_main_news_title',
	array(
		'label'           => esc_html__( 'Main News Section Title', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_main_news_title',
		'type'            => 'text',
		'active_callback' => 'horizon_news_is_banner_section_enabled',
	)
);

// Banner Section - Main News Content Type.
$wp_customize->add_setting(
	'horizon_news_main_news_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_main_news_content_type',
	array(
		'label'           => esc_html__( 'Select Main News Content Type', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_main_news_content_type',
		'type'            => 'select',
		'active_callback' => 'horizon_news_is_banner_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'horizon-news' ),
			'category' => esc_html__( 'Category', 'horizon-news' ),
		),
	)
);

for ( $i = 1; $i <= 3; $i++ ) {
	// Banner Section - Select Post.
	$wp_customize->add_setting(
		'horizon_news_main_news_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'horizon_news_main_news_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'horizon-news' ), $i ),
			'section'         => 'horizon_news_banner_section',
			'settings'        => 'horizon_news_main_news_content_post_' . $i,
			'active_callback' => 'horizon_news_is_banner_section_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => horizon_news_get_post_choices(),
		)
	);

}

// Banner Section - Select Category.
$wp_customize->add_setting(
	'horizon_news_main_news_content_category',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_main_news_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_main_news_content_category',
		'active_callback' => 'horizon_news_is_banner_section_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => horizon_news_get_post_cat_choices(),
	)
);

// Banner Section - Editor Pick Heading.
$wp_customize->add_setting(
	'horizon_news_banner_editor_pick_section',
	array(
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	new Horizon_News_Title_Control(
		$wp_customize,
		'horizon_news_banner_editor_pick_section',
		array(
			'label'           => __( 'Editor Pick Settings', 'horizon-news' ),
			'section'         => 'horizon_news_banner_section',
			'settings'        => 'horizon_news_banner_editor_pick_section',
			'active_callback' => 'horizon_news_is_banner_section_enabled',
		)
	)
);

// Editor Pick Section - Section Title.
$wp_customize->add_setting(
	'horizon_news_editor_pick_title',
	array(
		'default'           => __( 'Editor Pick', 'horizon-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'horizon_news_editor_pick_title',
	array(
		'label'           => esc_html__( 'Editor Pick Section Title', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_editor_pick_title',
		'type'            => 'text',
		'active_callback' => 'horizon_news_is_banner_section_enabled',
	)
);

// Editor Pick Section - Content Type.
$wp_customize->add_setting(
	'horizon_news_editor_pick_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_editor_pick_content_type',
	array(
		'label'           => esc_html__( 'Select Editor Pick Content Type', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_editor_pick_content_type',
		'type'            => 'select',
		'active_callback' => 'horizon_news_is_banner_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'horizon-news' ),
			'category' => esc_html__( 'Category', 'horizon-news' ),
		),
	)
);

for ( $i = 1; $i <= 3; $i++ ) {
	// Editor Pick Section - Select Post.
	$wp_customize->add_setting(
		'horizon_news_editor_pick_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'horizon_news_editor_pick_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'horizon-news' ), $i ),
			'section'         => 'horizon_news_banner_section',
			'settings'        => 'horizon_news_editor_pick_content_post_' . $i,
			'active_callback' => 'horizon_news_is_editor_pick_section_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => horizon_news_get_post_choices(),
		)
	);

}

// Editor Pick Section - Select Category.
$wp_customize->add_setting(
	'horizon_news_editor_pick_content_category',
	array(
		'sanitize_callback' => 'horizon_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'horizon_news_editor_pick_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'horizon-news' ),
		'section'         => 'horizon_news_banner_section',
		'settings'        => 'horizon_news_editor_pick_content_category',
		'active_callback' => 'horizon_news_is_editor_pick_section_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => horizon_news_get_post_cat_choices(),
	)
);
