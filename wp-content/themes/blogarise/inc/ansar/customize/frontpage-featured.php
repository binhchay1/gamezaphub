<?php

/**
 * Option Panel
 *
 * @package Blogarise
 */

$blogarise_default = blogarise_get_default_theme_options();

/**
 * Frontpage options section
 *
 * @package blogarise
 */

//Editor Choice
$wp_customize->add_section('blogarise_featured_links_section',
    array(
        'title' => esc_html__('Featured Links', 'blogarise'),
        'priority' => 36,
        'capability' => 'edit_theme_options',
    )
);

$wp_customize->add_setting('show_featured_links_section',
    array(
        'default' => false,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'show_featured_links_section', 
    array(
        'label' => __('Hide/Show Featured Links', 'blogarise'),
        'type' => 'toggle',
        'section' => 'blogarise_featured_links_section',
        'priority' => 100,
    )
));

//Featured Post One
$wp_customize->add_setting('featured_post_one',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'featured_post_one',
        array(
            'label'             => esc_html__( 'Featured Post', 'blogarise' ),
            'section'           => 'blogarise_featured_links_section',
            'priority'          => 100,
            'active_callback' => 'blogarise_featued_links_section_status'
        )
    )
);

$wp_customize->add_setting('fatured_post_image_one',
array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control($wp_customize, 'fatured_post_image_one',
        array(
            'label' => esc_html__('Image', 'blogarise'),
            'section' => 'blogarise_featured_links_section',
            'priority'          => 110,
            'active_callback' => 'blogarise_featued_links_section_status'
        )
    )
); 

$wp_customize->add_setting('featured_post_one_btn_txt',
    array(
        'default' => $blogarise_default['featured_post_one_btn_txt'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('featured_post_one_btn_txt',
    array(
        'label' => esc_html__('Button Text', 'blogarise'),
        'section' => 'blogarise_featured_links_section',
        'type' => 'url',
        'priority' => 120,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
); 

$wp_customize->add_setting('featured_post_one_url',
    array(
        'default' => $blogarise_default['featured_post_one_url'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control('featured_post_one_url',
    array(
        'label' => esc_html__('Button Link', 'blogarise'),
        'section' => 'blogarise_featured_links_section',
        'type' => 'url',
        'priority' => 130,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
);

$wp_customize->add_setting('featured_post_one_url_new_tab',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'featured_post_one_url_new_tab', 
    array(
        'label' => esc_html__('Open link in new tab', 'blogarise'),
        'type' => 'toggle',
        'section' => 'blogarise_featured_links_section',
        'priority' => 140,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
)); 

//Featured Post Two
$wp_customize->add_setting('featured_post_two',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'featured_post_two',
        array(
            'label'             => esc_html__( 'Featured Post', 'blogarise' ),
            'section'           => 'blogarise_featured_links_section',
            'priority'          => 150,
            'active_callback' => 'blogarise_featued_links_section_status'
        )
    )
);

$wp_customize->add_setting('fatured_post_image_two',
    array(
        'default' => $blogarise_default['fatured_post_image_two'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control($wp_customize, 'fatured_post_image_two',
        array(
            'label' => esc_html__('Image', 'blogarise'),
            'section' => 'blogarise_featured_links_section',
            'flex_width' => true,
            'flex_height' => true,
            'priority'          => 160,
            'active_callback' => 'blogarise_featued_links_section_status'
        )
    )
);

$wp_customize->add_setting('featured_post_two_btn_txt',
    array(
        'default' => $blogarise_default['featured_post_two_btn_txt'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('featured_post_two_btn_txt',
    array(
        'label' => esc_html__('Button Text', 'blogarise'),
        'section' => 'blogarise_featured_links_section',
        'type' => 'url',
        'priority' => 170,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
);

$wp_customize->add_setting('featured_post_two_url',
    array(
        'default' => $blogarise_default['featured_post_two_url'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control('featured_post_two_url',
    array(
        'label' => esc_html__('Button Link', 'blogarise'),
        'section' => 'blogarise_featured_links_section',
        'type' => 'url',
        'priority' => 180,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
);

$wp_customize->add_setting('featured_post_two_url_new_tab',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'featured_post_two_url_new_tab', 
    array(
        'label' => esc_html__('Open link in new tab', 'blogarise'),
        'type' => 'toggle',
        'section' => 'blogarise_featured_links_section',
        'priority' => 190,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
)); 

//Featured Post Three
$wp_customize->add_setting('featured_post_three',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'featured_post_three',
        array(
            'label'             => esc_html__( 'Featured Post', 'blogarise' ),
            'section'           => 'blogarise_featured_links_section',
            'priority'          => 200,
            'active_callback' => 'blogarise_featued_links_section_status'
        )
    )
);

$wp_customize->add_setting('fatured_post_image_three',
    array(
        'default' => $blogarise_default['fatured_post_image_three'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control($wp_customize, 'fatured_post_image_three',
        array(
            'label' => esc_html__('Image', 'blogarise'),
            'section' => 'blogarise_featured_links_section',
            'flex_width' => true,
            'flex_height' => true,
            'priority'          => 210,
            'active_callback' => 'blogarise_featued_links_section_status'
        )
    )
);

$wp_customize->add_setting('featured_post_three_btn_txt',
    array(
        'default' => $blogarise_default['featured_post_three_btn_txt'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('featured_post_three_btn_txt',
    array(
        'label' => esc_html__('Button Text', 'blogarise'),
        'section' => 'blogarise_featured_links_section',
        'type' => 'url',
        'priority' => 220,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
);

$wp_customize->add_setting('featured_post_three_url',
    array(
        'default' => $blogarise_default['featured_post_three_url'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control('featured_post_three_url',
    array(
        'label' => esc_html__('Button Link', 'blogarise'),
        'section' => 'blogarise_featured_links_section',
        'type' => 'url',
        'priority' => 230,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
);

$wp_customize->add_setting('featured_post_three_url_new_tab',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'featured_post_three_url_new_tab', 
    array(
        'label' => esc_html__('Open link in new tab', 'blogarise'),
        'type' => 'toggle',
        'section' => 'blogarise_featured_links_section',
        'priority' => 240,
        'active_callback' => 'blogarise_featued_links_section_status'
    )
)); 