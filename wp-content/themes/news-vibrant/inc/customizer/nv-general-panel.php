<?php
/**
 * News Vibrant General Settings panel at Theme Customizer
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

add_action( 'customize_register', 'news_vibrant_general_settings_register' );

function news_vibrant_general_settings_register( $wp_customize ) {

	$wp_customize->get_section( 'title_tagline' )->panel = 'news_vibrant_general_settings_panel';
    $wp_customize->get_section( 'title_tagline' )->priority = '5';
    $wp_customize->get_section( 'colors' )->panel    = 'news_vibrant_general_settings_panel';
    $wp_customize->get_section( 'colors' )->priority = '10';
    $wp_customize->get_section( 'background_image' )->panel = 'news_vibrant_general_settings_panel';
    $wp_customize->get_section( 'background_image' )->priority = '15';
    $wp_customize->get_section( 'static_front_page' )->panel = 'news_vibrant_general_settings_panel';
    $wp_customize->get_section( 'static_front_page' )->priority = '20';

    /**
     * Add General Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'news_vibrant_general_settings_panel',
	    array(
	        'priority'       => 5,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'General Settings', 'news-vibrant' ),
	    )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Color option for theme
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'news_vibrant_theme_color',
        array(
            'default'     => '#34b0fa',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    ); 
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'news_vibrant_theme_color',
            array(
                'label'      => __( 'Theme Color', 'news-vibrant' ),
                'section'    => 'colors',
                'priority'   => 5
            )
        )
    );

    /**
     * Title Color
     *
     * @since 1.0.0
     */

    $wp_customize->add_setting(
        'news_vibrant_site_title_color',
        array(
            'default'     => '#34b0fa',
            'transport'     => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
 
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'news_vibrant_site_title_color',
            array(
                'label'      => __( 'Header Text Color', 'news-vibrant' ),
                'section'    => 'colors',
                'priority'   => 5
            )
        )
    );
    
/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Website layout section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'news_vibrant_website_layout_section',
        array(
            'title'         => __( 'Website Layout', 'news-vibrant' ),
            'description'   => __( 'Choose a site to display your website more effectively.', 'news-vibrant' ),
            'priority'      => 55,
            'panel'         => 'news_vibrant_general_settings_panel',
        )
    );
    
    $wp_customize->add_setting(
        'news_vibrant_site_layout',
        array(
            'default'           => 'fullwidth_layout',
            'sanitize_callback' => 'news_vibrants_sanitize_select',
        )       
    );
    $wp_customize->add_control(
        'news_vibrant_site_layout',
        array(
            'type'          => 'radio',
            'priority'      => 5,
            'label'         => __( 'Site Layout', 'news-vibrant' ),
            'section'       => 'news_vibrant_website_layout_section',
            'choices'       => array(
                'fullwidth_layout'  => __( 'FullWidth Layout', 'news-vibrant' ),
                'boxed_layout'      => __( 'Boxed Layout', 'news-vibrant' )
            ),
        )
    );

    /**
     * Toggle option for block base widget editor.
     * 
     * @since 1.0.14
     */
    $wp_customize->add_setting( 'news_vibrant_block_base_widget_option', 
        array(
            'default'           => false,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'news_vibrant_sanitize_checkbox'
        )
    );
    $wp_customize->add_control( new News_Vibrant_Customize_Toggle_Control(
        $wp_customize, 'news_vibrant_block_base_widget_option', 
            array(
                'label'         => __( 'Block Widget Editor Option', 'news-vibrant' ),
                'description'   => __( 'Enable/disable Block-based Widgets Editor(since WordPress 5.8).', 'news-vibrant' ),
                'priority'      => 20,
                'section'       => 'news_vibrant_website_layout_section',
            )
        )
    );
/*------------------------------------------------------------------------------------------*/
    /**
     * Title and tagline checkbox
     *
     * @since 1.0.1
     */
    $wp_customize->add_setting( 
        'news_vibrant_site_title_option', 
        array(
            'default'           => true,
            'sanitize_callback' => 'news_vibrant_sanitize_checkbox'
        )
    );
    $wp_customize->add_control( 
        'news_vibrant_site_title_option', 
        array(
            'label'     => __( 'Display Site Title and Tagline', 'news-vibrant' ),
            'section'   => 'title_tagline',
            'type'      => 'checkbox'
        )
    );

}