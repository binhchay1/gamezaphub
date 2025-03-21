<?php
/**
 * News Vibrant Additional Settings panel at Theme Customizer
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

add_action( 'customize_register', 'news_vibrant_additional_settings_register' );

function news_vibrant_additional_settings_register( $wp_customize ) {

	/**
     * Add Additional Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'news_vibrant_additional_settings_panel',
	    array(
	        'priority'       => 20,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Additional Settings', 'news-vibrant' ),
	    )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
	 * Social Icons Section
	 *
	 * @since 1.0.0
	 */
	$wp_customize->add_section(
        'news_vibrant_social_icons_section',
        array(
            'title'		=> __( 'Social Icons', 'news-vibrant' ),
            'panel'     => 'news_vibrant_additional_settings_panel',
            'priority'  => 5,
        )
    );

    /**
     * Repeater field for social media icons
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
        'social_media_icons', 
        array(
            'sanitize_callback' => 'news_vibrant_sanitize_repeater',
            'default' => json_encode(array(
                array(
                    'social_icon_class' => 'fa fa-facebook-f',
                    'social_icon_url'   => '',
                )
            ))
        )
    );
    $wp_customize->add_control( new News_Vibrant_Repeater_Controler(
        $wp_customize, 
            'social_media_icons', 
            array(
                'label'         => __( 'Social Media Icons', 'news-vibrant' ),
                'section'       => 'news_vibrant_social_icons_section',
                'settings'      => 'social_media_icons',
                'priority'      => 5,
                'news_vibrant_box_label'       => __( 'Social Media Icon','news-vibrant' ),
                'news_vibrant_box_add_control' => __( 'Add Icon','news-vibrant' )
            ),
            array(
                'social_icon_class' => array(
                    'type'        => 'social_icon',
                    'label'       => __( 'Social Media Logo', 'news-vibrant' ),
                    'description' => __( 'Choose social media icon.', 'news-vibrant' )
                ),
                'social_icon_url' => array(
                    'type'        => 'url',
                    'label'       => __( 'Social Icon Url', 'news-vibrant' ),
                    'description' => __( 'Enter social media url.', 'news-vibrant' )
                )
            )
        ) 
    );
    $wp_customize->selective_refresh->add_partial('social_media_icons',
        array(
            'selector'          =>'.nv-social-icons-wrapper',
            'render_callback'   =>'social_media_icons_render_callback',
        )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
   	/**
   	 * Category Color Section
   	 *
   	 * @since 1.0.0
   	 */
    $wp_customize->add_section(
        'news_vibrant_categories_color_section',
        array(
            'title'         => __( 'Categories Color', 'news-vibrant' ),
            'priority'      => 10,
            'panel'         => 'news_vibrant_additional_settings_panel',
        )
    );

	$priority = 5;
	$categories = get_categories( array( 'hide_empty' => 1 ) );
	$wp_category_list = array();

	foreach ( $categories as $category_list ) {

		$wp_customize->add_setting( 
			'news_vibrant_category_color_'.esc_html( strtolower( $category_list->name ) ),
			array(
				'default'              => '#00a9e0',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => 'sanitize_hex_color'
			)
		);

		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize, 
			'news_vibrant_category_color_'.esc_html( strtolower( $category_list->name ) ),
				array(
					'label'    => sprintf( esc_html__( ' %s', 'news-vibrant' ), esc_html( $category_list->name ) ),
					'section'  => 'news_vibrant_categories_color_section',
					'priority' => $priority
				)
			)
		);
		$priority++;
	}
/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Widget Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'news_vibrant_widget_settings_section',
        array(
            'title'     => __( 'Widget Settings', 'news-vibrant' ),
            'panel'     => 'news_vibrant_additional_settings_panel',
            'priority'  => 15,
        )
    );

    /**
     * Toggle option for category link at widget title
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'news_vibrant_widget_cat_link_option',
        array(
            'default'           => true,
            'sanitize_callback' => 'news_vibrant_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new News_Vibrant_Customize_Toggle_Control(
        $wp_customize, 'news_vibrant_widget_cat_link_option',
            array(
                'label'         => __( 'Category Link', 'news-vibrant' ),
                'description'   => __( 'Enable/Disable option for category link for widget title in block layout widget.', 'news-vibrant' ),
                'section'       => 'news_vibrant_widget_settings_section',
                'priority'      => 5,
            )
        )
    );

    /**
     * Toggle option for category color at widget title
     *
     * @since 1..0
     */
    $wp_customize->add_setting(
        'news_vibrant_widget_cat_color_option',
        array(
            'default'           => true,
            'sanitize_callback' => 'news_vibrant_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new News_Vibrant_Customize_Toggle_Control(
        $wp_customize,
            'news_vibrant_widget_cat_color_option',
            array(
                'label'         => __( 'Category Color', 'news-vibrant' ),
                'description'   => __( 'Enable/Disable option for category color for widget title in block layout widget.', 'news-vibrant' ),
                'section'       => 'news_vibrant_widget_settings_section',
                'priority'      => 10,
            )
        )
    );

}