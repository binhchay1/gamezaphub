<?php
/**
 * News Vibrant Theme Customizer
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function news_vibrant_customize_register( $wp_customize ) {

    // Register Custom Control
    $wp_customize->register_control_type( 'News_Vibrant_Customize_Toggle_Control' );
    $wp_customize->register_control_type( 'News_Vibrant_Customize_Control_Radio_Image' );

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
    $wp_customize->selective_refresh->add_partial( 
        'blogname', 
            array(
                'selector'          => '.site-title a',
                'render_callback'   => 'news_vibrant_customize_partial_blogname',
            )
    );

    $wp_customize->selective_refresh->add_partial( 
        'blogdescription', 
            array(
                'selector' => '.site-description',
                'render_callback' => 'news_vibrant_customize_partial_blogdescription',
            )
    );

    /**
     * Register custom section types.
     *
     * @since 1.0.6
     */
    $wp_customize->register_section_type( 'News_Vibrant_Customize_Section_Upsell' );

    /**
     * Register theme upsell sections.
     *
     * @since 1.0.6
     */
    $wp_customize->add_section( new News_Vibrant_Customize_Section_Upsell(
        $wp_customize,
            'theme_upsell',
            array(
                'title'     => esc_html__( 'News Vibrant Pro', 'news-vibrant' ),
                'pro_text'  => esc_html__( 'Buy Pro', 'news-vibrant' ),
                'pro_url'   => 'https://codevibrant.com/wpthemes/news-vibrant-pro/',
                'priority'  => 1,
            )
        )
    );
}
add_action( 'customize_register', 'news_vibrant_customize_register' );

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function news_vibrant_customize_preview_js() {
	wp_enqueue_script( 'news_vibrant_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'news_vibrant_customize_preview_js' );

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue required scripts/styles for customizer panel
 *
 * @since 1.0.0
 */
function news_vibrant_customize_backend_scripts() {

    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );    
    wp_enqueue_style( 'news_vibrant_admin_customizer_style', get_template_directory_uri() . '/assets/css/nv-customizer-style.css' );

    wp_enqueue_script( 'news_vibrant_admin_customizer', get_template_directory_uri() . '/assets/js/nv-customizer-controls.js', array( 'jquery', 'customize-controls' ), '20170616', true );
}
add_action( 'customize_controls_enqueue_scripts', 'news_vibrant_customize_backend_scripts', 10 );

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Load required files for customizer section
 *
 * @since 1.0.0
 */
require get_template_directory() . '/inc/customizer/nv-general-panel.php';          // General Settings
require get_template_directory() . '/inc/customizer/nv-header-panel.php';  		    // Header Settings
require get_template_directory() . '/inc/customizer/nv-additional-panel.php';       // Additional Settings
require get_template_directory() . '/inc/customizer/nv-design-panel.php';           // Design Settings
require get_template_directory() . '/inc/customizer/nv-footer-panel.php';           // Footer Settings

require get_template_directory() . '/inc/customizer/nv-custom-classes.php';         // Custom Classes
require get_template_directory() . '/inc/customizer/nv-customizer-sanitize.php';    // Customizer Sanitize