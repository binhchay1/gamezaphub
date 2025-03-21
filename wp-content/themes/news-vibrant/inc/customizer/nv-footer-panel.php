<?php
/**
 * News Vibrant Footer Settings panel at Theme Customizer
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

add_action( 'customize_register', 'news_vibrant_footer_settings_register' );

function news_vibrant_footer_settings_register( $wp_customize ) {

	/**
     * Add Additional Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'news_vibrant_footer_settings_panel',
	    array(
	        'priority'       => 30,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Footer Settings', 'news-vibrant' ),
	    )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
	 * Widget Area Section
	 *
	 * @since 1.0.0
	 */
	$wp_customize->add_section(
        'news_vibrant_footer_widget_section',
        array(
            'title'		=> __( 'Widget Area', 'news-vibrant' ),
            'panel'     => 'news_vibrant_footer_settings_panel',
            'priority'  => 5,
        )
    );

    /**
     * Toggle option for Top Header
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'news_vibrant_footer_widget_option',
        array(
            'default'           => true,
            'sanitize_callback' => 'news_vibrant_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new News_Vibrant_Customize_Toggle_Control(
        $wp_customize, 'news_vibrant_footer_widget_option',
            array(
                'label'         => __( 'Footer Widget Section', 'news-vibrant' ),
                'description'   => __( 'Show/Hide option for footer widget area section.', 'news-vibrant' ),
                'section'       => 'news_vibrant_footer_widget_section',
                'priority'      => 5,
            )
        )
    );

    /**
     * Field for Image Radio
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'footer_widget_layout',
        array(
            'default'           => 'column_three',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new News_Vibrant_Customize_Control_Radio_Image(
        $wp_customize, 'footer_widget_layout',
            array(
                'label'         => __( 'Footer Widget Layout', 'news-vibrant' ),
                'description'   => __( 'Choose layout from available layouts', 'news-vibrant' ),
                'section'       => 'news_vibrant_footer_widget_section',
                'choices'       => array(
                    'column_four' => array(
                        'label' => __( 'Columns Four', 'news-vibrant' ),
                        'url'   => '%s/assets/images/footer-4.png'
                    ),
                    'column_three' => array(
                        'label' => __( 'Columns Three', 'news-vibrant' ),
                        'url'   => '%s/assets/images/footer-3.png'
                    ),
                    'column_two' => array(
                        'label' => __( 'Columns Two', 'news-vibrant' ),
                        'url'   => '%s/assets/images/footer-2.png'
                    ),
                    'column_one' => array(
                        'label' => __( 'Column One', 'news-vibrant' ),
                        'url'   => '%s/assets/images/footer-1.png'
                    )
                ),
                'active_callback'  => 'news_vibrant_footer_widget_option_active_callback',
	            'priority'         => 10
            )
        )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
	 * Bottom Section
	 *
	 * @since 1.0.0
	 */
	$wp_customize->add_section(
        'news_vibrant_footer_bottom_section',
        array(
            'title'		=> __( 'Bottom Section', 'news-vibrant' ),
            'panel'     => 'news_vibrant_footer_settings_panel',
            'priority'  => 10,
        )
    );

    /**
     * Text field for copyright
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'news_vibrant_copyright_text',
        array(
            'default'    => __( 'News Vibrant', 'news-vibrant' ),
            'transport'  => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control(
        'news_vibrant_copyright_text',
        array(
            'type'      => 'text',
            'label'     => __( 'Copyright Text', 'news-vibrant' ),
            'section'   => 'news_vibrant_footer_bottom_section',
            'priority'  => 5
        )
    );
    $wp_customize->selective_refresh->add_partial( 
        'news_vibrant_copyright_text', 
        array(
            'selector'          => 'span.nv-copyright-text',
            'render_callback'   => 'news_vibrant_customize_partial_copyright',
        )
    );
}