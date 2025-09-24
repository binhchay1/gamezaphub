<?php
/**
 * Blogarise Theme Customizer
 *
 * @package Blogarise
 */

if (!function_exists('blogarise_get_option')):
/**
 * Get theme option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function blogarise_get_option($key) {

	if (empty($key)) {
		return;
	}

	$value = '';

	$default       = blogarise_get_default_theme_options();
	$default_value = null;

	if (is_array($default) && isset($default[$key])) {
		$default_value = $default[$key];
	}

	if (null !== $default_value) {
		$value = get_theme_mod($key, $default_value);
	} else {
		$value = get_theme_mod($key);
	}

	return $value;
}
endif;

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-callback.php';

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-default.php';


$repeater_path = trailingslashit( get_template_directory() ) . '/inc/ansar/customizer-repeater/functions.php';
if ( file_exists( $repeater_path ) ) {
    require_once( $repeater_path );
}

function banner_slider_option($control) {
    $banner_slider_option = $control->manager->get_setting('banner_options_main')->value();
    if($banner_slider_option == 'banner_slider_section_option'){
        return true;
    } else{
        return false;
    }
}

function banner_slider_category_function($control){
    $no_option = $control->manager->get_setting('banner_options_main')->value();
    $banner_slider_category_option = $control->manager->get_setting('banner_slider_section_option')->value();
    if ($banner_slider_category_option == 'banner_slider_category_option' && $no_option == 'banner_slider_section_option') {
        return true;
    } else { return false;}
}

function header_video_act_call($control){
    $video_banner_section = $control->manager->get_setting('banner_options_main')->value();

    if($video_banner_section == 'header_video'){
        return true;
    } else {
        return false;
    }
}

function video_banner_section_function($control){
    $video_banner_section = $control->manager->get_setting('banner_options_main')->value();

    if($video_banner_section == 'video_banner_section'){
        return true;
    } else {
        return false;
    }
}

function slider_callback($control){
    $banner_slider_option = $control->manager->get_setting('banner_options_main')->value();
    $banner_slider_section_option = $control->manager->get_setting('banner_slider_section_option')->value();
    if ($banner_slider_option == 'banner_slider_section_option' && $banner_slider_section_option == 'latest_post_show') {
        return true;
    } else {
        return false;
    }
}

function overlay_text($control){
    $banner_slider_option = $control->manager->get_setting('banner_options_main')->value();
    if($banner_slider_option == 'header_video' || $banner_slider_option == 'video_banner_section'){
        return true;
    } else {
       return false;
    }
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function Blogarise_Customize_register($wp_customize) {

	// Load customize controls.
	require get_template_directory().'/inc/ansar/customize/customizer-control.php';

    // Load customize sanitize.
	require get_template_directory().'/inc/ansar/customize/customizer-sanitize.php';

    $wp_customize->get_setting( 'custom_logo')->sanitize_callback  	= 'esc_url_raw';
    $wp_customize->get_setting( 'custom_logo')->transport  			= 'postMessage';
	$wp_customize->get_setting('blogname')->transport               = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport        = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport       = 'postMessage';

    // use get control
    $wp_customize->get_control( 'header_textcolor')->label      = __( 'Site Info Color', 'blogarise' );
    $wp_customize->get_control( 'header_textcolor')->section    = 'colors';   
    $wp_customize->get_control( 'header_textcolor')->priority   = 1;   
    $wp_customize->get_control( 'header_textcolor')->default    = '#000';
    $wp_customize->get_setting('background_color')->transport   = 'refresh';

	if (isset($wp_customize->selective_refresh)) {
		// site logo
		$wp_customize->selective_refresh->add_partial('custom_logo', array(
			'selector'        => '.site-logo', 
			'render_callback' => 'custom_logo_selective_refresh'
		));
		// site title
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a, .site-title-footer a',
            'render_callback' => 'Blogarise_Customize_partial_blogname',
        ));
		// site tagline
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description, .site-description-footer',
            'render_callback' => 'Blogarise_Customize_partial_blogdescription',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_header_social_icons', array(
            'selector'        => '.bs-head-detail .bs-social'
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_footer_social_icons', array(
            'selector'        => 'footer .bs-social ',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_scrollup_enable', array(
            'selector'        => '.bs_upscr',
        ));
        $wp_customize->selective_refresh->add_partial('you_missed_title', array(
            'selector'        => '.missed .bs-widget-title .title',
            'render_callback' => 'Blogarise_Customize_partial_you_missed_title',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_related_post_title', array(
            'selector'        => '.single-class .col-lg-9 .bs-widget-title .title',
            'render_callback' => 'Blogarise_Customize_partial_blogarise_related_post_title',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_menu_search', array(
            'selector'        => '.bs-default .desk-header .msearch',
            'render_callback' => 'Blogarise_Customize_partial_blogarise_menu_search',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_lite_dark_switcher', array(
            'selector'        => '.bs-menu-full .desk-header.right-nav',
            'render_callback' => 'blogarise_customize_partial_right_nav',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_subsc_link', array(
            'selector'        => '.bs-menu-full .desk-header.right-nav',
            'render_callback' => 'blogarise_customize_partial_right_nav',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_subsc_open_in_new', array(
            'selector'        => '.bs-menu-full .desk-header.right-nav',
            'render_callback' => 'blogarise_customize_partial_right_nav',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_menu_search', array(
            'selector'        => '.bs-menu-full .desk-header.right-nav',
            'render_callback' => 'blogarise_customize_partial_right_nav',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_menu_subscriber', array(
            'selector'        => '.bs-menu-full .desk-header.right-nav',
            'render_callback' => 'blogarise_customize_partial_right_nav',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_footer_copyright', array(
            'selector'        => '.bs-footer-copyright .copyright-text', 
            'render_callback' => 'Blogarise_Customize_partial_footer_copyright',
        ));
        $wp_customize->selective_refresh->add_partial('hide_copyright', array(
            'selector'        => '.bs-footer-copyright', 
            'render_callback' => 'Blogarise_Customize_partial_hide_copyright',
        ));
        $wp_customize->selective_refresh->add_partial('header_social_icon_enable', array(
            'selector'        => '.bs-head-detail .col-md-4.col-xs-12, .bs-header-main .container > .row > .col-lg-4:not(.navbar-header, .d-lg-flex)',
            'render_callback' => 'blogarise_customize_partial_head_social_icon',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_header_social_icons', array(
            'selector'        => '.bs-head-detail .col-md-4.col-xs-12, .bs-header-main .container > .row > .col-lg-4:not(.navbar-header, .d-lg-flex)',
            'render_callback' => 'blogarise_customize_partial_head_social_icon',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_drop_caps_enable', array(
            'selector'        => '.content-right .bs-blog-post .bs-blog-meta, .content-full .bs-blog-post .bs-blog-meta', 
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_single_post_admin_details', array(
            'selector'        => '.bs-blog-post .bs-header .bs-blog-meta ',
        ));  
        $wp_customize->selective_refresh->add_partial('banner_advertisement_section_url', array(
            'selector'        => '.bs-header-main .attachment-full.size-full ',
        )); 
        $wp_customize->selective_refresh->add_partial('breaking_news_title', array(
            'selector'        => '.mg-latest-news .bn_title .title',
            'render_callback' => 'Blogarise_Customize_partial_breaking_news_title',
        ));
        $wp_customize->selective_refresh->add_partial('brk_news_enable', array(
            'selector'        => '.bs-head-detail',
            'render_callback' => 'Blogarise_Customize_partial_brk_news_enable',
        ));
        $wp_customize->selective_refresh->add_partial('blogarise_content_layout', array(
            'selector'        => '.index-class .container > .row, .archive-class > .container > .row', 
			'render_callback' => 'blogarise_customize_partial_content_layout',
        ));		
        $wp_customize->selective_refresh->add_partial('blogarise_page_layout', array(
			'selector'        => '.page-class > .container > .row',
			'render_callback' => 'blogarise_customize_partial_page_layout',
		));
		$wp_customize->selective_refresh->add_partial('you_missed_enable', array(
			'selector'        => 'div.missed',
			'render_callback' => 'blogarise_customize_partial_you_missed_enable',
		));
	}

    $default = blogarise_get_default_theme_options();

	/*Theme option panel info*/

    require get_template_directory().'/inc/ansar/customize/header-options.php';

	require get_template_directory().'/inc/ansar/customize/theme-options.php';

	/*theme general layout panel*/
	require get_template_directory().'/inc/ansar/customize/theme-layout.php';

	/*theme Frontpage panel*/
	require get_template_directory().'/inc/ansar/customize/frontpage-featured.php';

}
add_action('customize_register', 'Blogarise_Customize_register');


function custom_logo_selective_refresh() {
    if( get_theme_mod( 'custom_logo' ) === "" ) return;
    echo '<div class="site-logo">'.the_custom_logo().'</div>';
}
/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function Blogarise_Customize_partial_blogname() {
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function Blogarise_Customize_partial_blogdescription() {
	bloginfo('description');
}

function Blogarise_Customize_partial_header_data_enable() {
    return get_theme_mod( 'header_data_enable' );
}

function Blogarise_Customize_partial_footer_social_icon_enable() {
    return get_theme_mod( 'blogarise_footer_social_icons' ); 
}

function blogarise_customize_partial_right_nav() {
	blogarise_menu_btns();
}

function Blogarise_Customize_partial_sidebar_menu() {
    return get_theme_mod( 'sidebar_menu' ); 
}

function Blogarise_Customize_partial_blogarise_menu_subscriber() {
    return get_theme_mod( 'blogarise_menu_subscriber' ); 
}

function blogarise_customize_partial_you_missed_enable() {
	return do_action('blogarise_action_footer_missed_section');
}

function Blogarise_Customize_partial_brk_news_enable() {
    return do_action('blogarise_action_header_top_section'); 
}

function Blogarise_Customize_partial_breaking_news_title() {
    return get_theme_mod( 'breaking_news_title' ); 
}

function Blogarise_Customize_partial_footer_copyright() {
    return get_theme_mod( 'blogarise_footer_copyright' ); 
}

function Blogarise_Customize_partial_blogarise_related_post_title() {
    return get_theme_mod( 'blogarise_related_post_title' ); 
}

function Blogarise_Customize_partial_you_missed_title() {
    return get_theme_mod( 'you_missed_title' ); 
}

function blogarise_customize_partial_content_layout() {
	return do_action('blogarise_action_main_content_layouts');
}

function Blogarise_Customize_partial_hide_copyright() {
	return do_action('blogarise_action_footer_copyright');
}

function blogarise_customize_partial_head_social_icon() {
	return do_action('blogarise_action_header_social_section');
}

function blogarise_customize_partial_page_layout() {
	return get_template_part('template-parts/content', 'page');
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function Blogarise_Customize_preview_js() {
	wp_enqueue_script('blogarise-customizer', get_template_directory_uri().'/js/customizer.js', array('customize-preview'), '20151215', true);

    // Pass the PHP variable to the JavaScript file
    wp_localize_script( 'blogarise-customizer', 'php_obj', array(
        'current_theme' => get_stylesheet(),
    ) );
}
add_action('customize_preview_init', 'Blogarise_Customize_preview_js');


/************************* Related Post Callback function *********************************/

    function blogarise_rt_post_callback ( $control ) {
        if( true == $control->manager->get_setting ('blogarise_enable_related_post')->value()){
            return true;
        }
        else {
            return false;
        }       
    }

/************************* Theme Customizer with Sanitize function *********************************/
function blogarise_theme_option( $wp_customize ) {
    function blogarise_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }

    /*--- Site title Font size **/
    $wp_customize->add_setting('blogarise_title_font_size',
        array(
            'default'           => 60,
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint'
        )
    );

    $wp_customize->add_control('blogarise_title_font_size',
        array(
            'label'    => esc_html__('Site Title Size', 'blogarise'),
            'section'  => 'title_tagline',
            'type'     => 'number',
            'priority' => 50,
        )
    );

    $wp_customize->add_setting('blogarise_center_logo_title',
        array(
            'default' => false,
            'transport' => 'postMessage',
            'sanitize_callback' => 'blogarise_sanitize_checkbox',
        )
    );
    $wp_customize->add_control('blogarise_center_logo_title',
        array(
            'label' => esc_html__('Display Center Site Title and Tagline', 'blogarise'),
            'section' => 'title_tagline',
            'type' => 'checkbox',
            'priority' => 55,
        )
    );

    $wp_customize->add_setting('header_textcolor_dark_layout',
        array(
            'default' => '#fff',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'blogarise_sanitize_alpha_color',
        )
    );
    $wp_customize->add_control('header_textcolor_dark_layout',
        array(
            'label' => esc_html__('Site Title/Tagline Color (Dark Mode)', 'blogarise'),
            'section' => 'colors',
            'type' => 'color',
            'priority' => 2,
        )
    );

    $wp_customize->add_setting('blogarise_skin_mode_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        new Blogarise_Section_Title(
            $wp_customize,
            'blogarise_skin_mode_title',
            array(
                'label' => esc_html__('Theme Layout', 'blogarise'),
                'section' => 'colors',
                'priority' => 10,

            )
        )
    );

    $wp_customize->add_setting(
        'blogarise_skin_mode', array(
        'default'           => 'defaultcolor',
        'sanitize_callback' => 'blogarise_sanitize_radio'
    ) );
    $wp_customize->add_control(
        new Blogarise_Custom_Radio_Default_Image_Control( 
            // $wp_customize object
            $wp_customize,
            // $id
            'blogarise_skin_mode',
            // $args
            array(
                'settings'      => 'blogarise_skin_mode',
                'section'       => 'colors',
                'priority' => 20,
                'choices'       => array(
                    'defaultcolor'    => get_template_directory_uri() . '/images/color/white.png',
                    'dark' => get_template_directory_uri() . '/images/color/black.png',
                )
            )
        )
    );

    $wp_customize->add_setting('blogarise_primary_menu_color',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        new Blogarise_Section_Title(
            $wp_customize,
            'blogarise_primary_menu_color',
            array(
                'label' => esc_html__('Primary Menu Color', 'blogarise'),
                'section' => 'colors',
                'priority' => 30,

            )
        )
    );

    $wp_customize->add_setting('primary_menu_bg_color',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'blogarise_sanitize_alpha_color',
        )
    );
    $wp_customize->add_control('primary_menu_bg_color',
    array(
        'label' => esc_html__('Background Color', 'blogarise'),
        'section' => 'colors',
        'type' => 'color',
        'priority' => 40,
    ));

}
add_action('customize_register','blogarise_theme_option');

if ( ! function_exists( 'blogarise_get_social_icon_default' ) ) {

    function blogarise_get_social_icon_default() {
        return apply_filters(
            'blogarise_get_social_icon_default',
            json_encode(
                array(
                    array(
                        'icon_value' => 'fab fa-facebook',
                        'link'       => '#',
                        'id'         => 'customizer_repeater_header_social_001',
                    ),
                    array(
                        'icon_value' => 'fa-brands fa-x-twitter',
                        'link'       =>  '#',
                        'id'         => 'customizer_repeater_header_social_003',
                    ),
                    array(
                        'icon_value' => 'fab fa-instagram',
                        'link'       =>  '#',
                        'id'         => 'customizer_repeater_header_social_005',
                    ),
                    array(
                        'icon_value' => 'fab fa-youtube',
                        'link'       =>  '#',
                        'id'         => 'customizer_repeater_header_social_006',
                    ),
                    
                    array(
                        'icon_value' => 'fab fa-telegram',
                        'link'       => '#',
                        'id'         => 'customizer_repeater_header_social_008',
                    ),
                )
            )
        );
    }
}