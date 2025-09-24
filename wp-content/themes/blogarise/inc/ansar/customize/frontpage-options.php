<?php

/**
 * Option Panel
 *
 * @package blogarise
 */

$blogarise_default = blogarise_get_default_theme_options();

/**
 * Frontpage options section
 *
 * @package blogarise
 */

// Main banner Sider Section.
$wp_customize->add_section('frontpage_main_banner_section_settings',
    array(
        'title' => esc_html__('Featured Slider', 'blogarise'),
        'priority' => 35,
        'capability' => 'edit_theme_options',
    )
); 
$wp_customize->add_setting(
    'slider_tabs',
    array(
        'default'           => '',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field'
    )
); 
$wp_customize->add_control( new Custom_Tab_Control ( $wp_customize,'slider_tabs',
    array(
        'label'                 => '',
        'type' => 'custom-tab-control',
        'section'               => 'frontpage_main_banner_section_settings',
        'controls_general'      => json_encode  ( array( '#customize-control-select_slider_news_category',
                                                        '#customize-control-show_main_news_section', 
                                                    ) 
                                                ),

        'controls_design'       => json_encode  (array( '#customize-control-slider_overlay_enable',
                                                        '#customize-control-blogarise_slider_overlay_color', 
                                                        '#customize-control-blogarise_slider_overlay_text_color', 
                                                        '#customize-control-blogarise_slider_title_font_size', 
                                                        '#customize-control-slider_meta_enable',
                                                    )
                                                ),
    )
));

// Setting - show_main_news_section.
$wp_customize->add_setting('show_main_news_section',
    array(
        'default' => $blogarise_default['show_main_news_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control('show_main_news_section',
    array(
        'label' => esc_html__('Enable Slider Banner Section', 'blogarise'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'checkbox',
        'priority' => 10,

    )
); 
// Setting - drop down category for slider.
$wp_customize->add_setting('select_slider_news_category',
    array(
        'default' => $blogarise_default['select_slider_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
); 
$wp_customize->add_control(new Blogarise_Dropdown_Taxonomies_Control($wp_customize, 'select_slider_news_category',
    array(
        'label' => esc_html__('Category', 'blogarise'),
        'description' => esc_html__('Posts to be shown on banner slider section', 'blogarise'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 100,
        'active_callback' => 'blogarise_main_banner_section_status',
    ))
);
//SLider styling tabs
$wp_customize->add_setting('slider_overlay_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'slider_overlay_enable', 
    array(
        'label' => esc_html__('Hide / Show Slider Overlay', 'blogarise'),
        'type' => 'toggle',
        'section' => 'frontpage_main_banner_section_settings',
    )
));
//slider Overlay 
$wp_customize->add_setting(
    'blogarise_slider_overlay_color', array( 'sanitize_callback' => 'blogarise_sanitize_alpha_color','default' => '#00000099',
    
) );
$wp_customize->add_control(new Blogarise_Customize_Alpha_Color_Control( $wp_customize,'blogarise_slider_overlay_color', array(
    'label'      => __('Overlay Color', 'blogarise' ),
    'palette' => true,
    'section' => 'frontpage_main_banner_section_settings')
) );
$wp_customize->add_setting(
    'blogarise_slider_overlay_text_color', array( 'sanitize_callback' => 'sanitize_hex_color',
    
) );
$wp_customize->add_control( 'blogarise_slider_overlay_text_color', array(
    'label'      => __('Overlay Text Color', 'blogarise' ),
    'type' => 'color',
    'section' => 'frontpage_main_banner_section_settings')
);
$wp_customize->add_setting('blogarise_slider_title_font_size',
    array(
        'default'           => 38,
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control('blogarise_slider_title_font_size',
    array(
        'label'    => esc_html__('Slider title font Size', 'blogarise'),
        'section'  => 'frontpage_main_banner_section_settings',
        'type'     => 'number',
    )
);
$wp_customize->add_setting('slider_meta_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'slider_meta_enable', 
    array(
        'label' => esc_html__('Hide / Show Meta', 'blogarise'),
        'type' => 'toggle',
        'section' => 'frontpage_main_banner_section_settings',
    )
));