<?php
/**
 * Default theme options.
 *
 * @package blogarise
 */

if (!function_exists('blogarise_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function blogarise_get_default_theme_options() {

    $defaults = array();

    // Header options section
    $defaults['banner_advertisement_section'] = '';
    $defaults['banner_advertisement_section_url'] = '';
    $defaults['banner_advertisement_open_on_new_tab'] = 1;
   
    $defaults['select_flash_news_category'] = 0;
    $defaults['number_of_flash_news'] = 5;
    $defaults['breaking_news_title'] = __('Breaking','blogarise');

    // Frontpage Section.
    $defaults['show_main_news_section'] = 0;
 
    $defaults['select_vertical_slider_news_category'] = 0;
   
    $defaults['select_slider_news_category'] = 0;
     
    $defaults['number_of_slides'] = 5;
    $defaults['show_featured_news_section'] = 1;
    $defaults['featured_news_section_title'] = __('Featured Story', 'blogarise');
    $defaults['select_featured_news_category'] = 0;
    $defaults['number_of_featured_news'] = 6;

    //Featured Ads Section
    $defaults['fatured_post_image_one'] ="";
    $defaults['featured_post_one_btn_txt'] ="";
    $defaults['featured_post_one_url'] ="";
    $defaults['featured_post_one_url_new_tab']="";

    $defaults['fatured_post_image_two']="";
    $defaults['featured_post_two_btn_txt']="";
    $defaults['featured_post_two_url']="";
    $defaults['featured_post_two_url_new_tab']="";

    $defaults['fatured_post_image_three']="";
    $defaults['featured_post_three_btn_txt']="";
    $defaults['featured_post_three_url']="";
    $defaults['featured_post_three_url_new_tab']="";

    //layout options
    $defaults['blogarise_content_layout'] = 'align-content-left';
    $defaults['global_post_date_author_setting'] = 'show-date-author';
    $defaults['global_hide_post_date_author_in_list'] = 1;
    $defaults['global_widget_excerpt_setting'] = 'trimmed-content';
    $defaults['post_image_type'] = 'post_fix_height';
 
    $defaults['frontpage_latest_posts_section_title'] = __('You may have missed', 'blogarise');
    $defaults['frontpage_latest_posts_category'] = 0;
    $defaults['number_of_frontpage_latest_posts'] = 4;

    //Single
    $defaults['single_show_featured_image'] = true;
    $defaults['single_show_share_icon'] = true;

    // filter.
    $defaults = apply_filters('blogarise_filter_default_theme_options', $defaults);

	return $defaults;

}

endif;