<?php
/**
 * Customizer callback functions for active_callback.
 *
 * @package Blogarise
 */

/*select page for slider*/
if (!function_exists('blogarise_main_banner_section_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function blogarise_main_banner_section_status($control) {
        if (true == $control->manager->get_setting('show_main_news_section')->value()) {
            return true;
        } else {
            return false;
        }
    }

endif;

/*select page for Featured links*/
if (!function_exists('blogarise_featued_links_section_status')) :

    /**
     * Check if Featured links section is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function blogarise_featued_links_section_status($control) {
        if (true == $control->manager->get_setting('show_featured_links_section')->value()) {
            return true;
        } else {
            return false;
        }
    }

endif;