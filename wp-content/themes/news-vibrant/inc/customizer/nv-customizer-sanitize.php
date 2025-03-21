<?php
/**
 * File to sanitize customizer field
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/**
 * Sanitize checkbox value
 *
 * @since 1.0.1
 */
function news_vibrant_sanitize_checkbox( $input ) {
    //returns true if checkbox is checked
    return ( ( isset( $input ) && true == $input ) ? true : false );
}

/**
 * Sanitize repeater value
 *
 * @since 1.0.0
 */
function news_vibrant_sanitize_repeater( $input ) {
    $input_decoded = json_decode( $input, true );
        
    if ( !empty( $input_decoded ) ) {
        foreach ( $input_decoded as $boxes => $box ) {
            foreach ( $box as $key => $value ) {
                $input_decoded[$boxes][$key] = wp_kses_post( $value );
            }
        }
        return json_encode( $input_decoded );
    }
    
    return $input;
}
    
/**
 * Sanitize select.
 * 
 * @since 1.5.0
 */
function news_vibrants_sanitize_select( $input, $setting ) {
    // Ensure input is a slug.
    $input = sanitize_key( $input );

    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * sanitize function for multiple checkboxes
 *
 * @since 1.0.0
 */
function news_vibrant_sanitize_mulitple_checkbox( $values ) {

    $multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

    return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Render the site title for the selective refresh partial.
 *
 * @since News Vibrant 1.0.0
 * @see news_vibrant_customize_register()
 *
 * @return void
 */
function news_vibrant_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since News Vibrant 1.0.0
 * @see news_vibrant_customize_register()
 *
 * @return void
 */
function news_vibrant_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since News Vibrant 1.0.0
 * @see news_vibrant_footer_settings_register()
 *
 * @return void
 */
function news_vibrant_customize_partial_copyright() {
    return get_theme_mod( 'news_vibrant_copyright_text' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since News Vibrant 1.0.0
 * @see news_vibrant_design_settings_register()
 *
 * @return void
 */
function news_vibrant_customize_partial_related_title() {
    return get_theme_mod( 'news_vibrant_related_posts_title' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since News Vibrant 1.0.0
 * @see news_vibrant_design_settings_register()
 *
 * @return void
 */
function news_vibrant_customize_partial_archive_more() {
    return get_theme_mod( 'news_vibrant_archive_read_more_text' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since News Vibrant 1.0.0
 * @see news_vibrant_header_settings_register()
 *
 * @return void
 */
function news_vibrant_customize_partial_ticker_caption() {
    return get_theme_mod( 'news_vibrant_ticker_caption' );
}


/**
 * This Selective refresh for a social icons
 */
function social_media_icons_render_callback(){
    return get_theme_mod('social_media_icons');
}
/*------------------------------------------------ Callback Functions -----------------------------------------------------------------------*/
if ( ! function_exists( 'news_vibrant_top_header_option_active_callback' ) ):
    /**
	 * Check if top header option is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */

    function news_vibrant_top_header_option_active_callback( $control ) {
        if ( false !== $control->manager->get_setting( 'news_vibrant_top_header_option' )->value() ) {
            return true;
        } else {
            return false;
        }
    }
endif;

if ( ! function_exists( 'news_vibrant_featured_posts_top_header_active_callback' ) ):
    /**
     * Active callback function for featured post section at top header
     *
     * @since 1.0.0
     */
    function news_vibrant_featured_posts_top_header_active_callback( $control ) {
        if ( true == $control->manager->get_setting( 'news_vibrant_top_header_option' )->value() && true == $control->manager->get_setting( 'news_vibrant_top_featured_option' )->value() ) {
            return true;
        } else {
            return false;
        }
    }
endif;

if ( ! function_exists( 'news_vibrant_ticker_option_active_callback' ) ):
    /**
     * Active callback function for ticker section at top header
     *
     * @since 1.0.0
     */
    function news_vibrant_ticker_option_active_callback( $control ) {
        if ( true === $control->manager->get_setting( 'news_vibrant_ticker_option' )->value() ) {
            return true;
        } else {
            return false;
        }
    }
endif;

if ( ! function_exists( 'news_vibrant_related_posts_option_active_callback' ) ):
    /**
     * Active callback function for related posts options
     *
     * @since 1.0.0
     */
    function news_vibrant_related_posts_option_active_callback( $control ) {
        if ( true === $control->manager->get_setting( 'news_vibrant_related_posts_option' )->value() ) {
            return true;
        } else {
            return false;
        }
    }
endif;

if ( ! function_exists( 'news_vibrant_footer_widget_option_active_callback' ) ):
    /**
     * Active callback function footer widget at top header
     *
     * @since 1.0.0
     */
    function news_vibrant_footer_widget_option_active_callback( $control ) {
        if ( true === $control->manager->get_setting( 'news_vibrant_footer_widget_option' )->value() ) {
            return true;
        } else {
            return false;
        }
    }
endif;