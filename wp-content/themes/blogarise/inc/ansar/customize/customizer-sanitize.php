<?php
/**
 * Sanitization functions.
 *
 * @package blogarise
 */

if ( ! function_exists( 'blogarise_sanitize_checkbox' ) ) :

    /**
     * Sanitize checkbox.
     *
     * @since 1.0.0
     *
     * @param bool $checked Whether the checkbox is checked.
     * @return bool Whether the checkbox is checked.
     */
    function blogarise_sanitize_checkbox( $checked ) {
        return ( ( isset( $checked ) && true === $checked ) ? true : false );
    }

endif;


if ( ! function_exists( 'blogarise_sanitize_select' ) ) :

    /**
     * Sanitize select.
     *
     * @since 1.0.0
     *
     * @param mixed                $input The value to sanitize.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return mixed Sanitized value.
     */
    function blogarise_sanitize_select( $input, $setting ) {

        // Ensure input is a slug.
        $input = sanitize_text_field( $input );

        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;

        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

    }

endif;


if ( ! function_exists( 'blogarise_sanitize_dropdown_pages' ) ) :

    /**
     * Sanitize dropdown pages.
     *
     * @since 1.0.0
     *
     * @param int                  $page_id Page ID.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return int|string Page ID if the page is published; otherwise, the setting default.
     */
    function blogarise_sanitize_dropdown_pages( $page_id, $setting ) {

        // Ensure $input is an absolute integer.
        $page_id = absint( $page_id );

        // If $page_id is an ID of a published page, return it; otherwise, return the default.
        return ( 'publish' === get_post_status( $page_id ) ? $page_id : $setting->default );

    }

endif;

if ( ! function_exists( 'blogarise_sanitize_image' ) ) :

    /**
     * Sanitize image.
     *
     * @since 1.0.0
     *
     * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
     *
     * @param string               $image Image filename.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return string The image filename if the extension is allowed; otherwise, the setting default.
     */
    function blogarise_sanitize_image( $image, $setting ) {

        /**
         * Array of valid image file types.
         *
         * The array includes image mime types that are included in wp_get_mime_types().
         */
        $mimes = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif'          => 'image/gif',
            'png'          => 'image/png',
            'bmp'          => 'image/bmp',
            'tif|tiff'     => 'image/tiff',
            'ico'          => 'image/x-icon',
        );

        // Return an array with file extension and mime_type.
        $file = wp_check_filetype( $image, $mimes );

        // If $image has a valid mime_type, return it; otherwise, return the default.
        return ( $file['ext'] ? $image : $setting->default );

    }

endif;

if ( ! function_exists( 'blogarise_sanitize_radio' ) ) :
    function blogarise_sanitize_radio( $val, $setting ) {
        $val = sanitize_key( $val );
        $choices = $setting->manager->get_control( $setting->id )->choices;
        return array_key_exists( $val, $choices ) ? $val : $setting->default;
    }
endif;


if ( ! function_exists( 'blogarise_sanitize_alpha_color' ) ) :
    function blogarise_sanitize_alpha_color( $value ) {
        // Check if the value is a valid hexadecimal color
        if ( preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $value ) ) {
            return sanitize_hex_color( $value );
        }
        
        // Check if the value is a valid RGB color
        if ( preg_match( '/^rgb\((\d{1,3}),(\d{1,3}),(\d{1,3})\)$/i', $value, $matches ) ) {
            $red = intval( $matches[1] );
            $green = intval( $matches[2] );
            $blue = intval( $matches[3] );
            
            return "rgb($red, $green, $blue)";
        }
        
        // Check if the value is a valid RGBA color
        if ( preg_match( '/^rgba\((\d{1,3}),(\d{1,3}),(\d{1,3}),([\d\.]+)\)$/i', $value, $matches ) ) {
            $red = intval( $matches[1] );
            $green = intval( $matches[2] );
            $blue = intval( $matches[3] );
            $alpha = floatval( $matches[4] );
            
            // Ensure alpha value is between 0 and 1
            $alpha = max( 0, min( 1, $alpha ) );
            
            return "rgba($red, $green, $blue, $alpha)";
        }
        
        // If none of the above formats match, return a default value
        return '';
    }
endif;