<?php /*** Option Panel
 *
 * @package blogarise
 */
$blogarise_default = blogarise_get_default_theme_options();
/*theme option panel info*/
require get_template_directory() . '/inc/ansar/customize/frontpage-options.php';

// Add Theme Options Panel.
$wp_customize->add_panel('themes_layout',
    array(
        'title' => esc_html__('General Layout', 'blogarise'),
        'priority' => 31,
        'capability' => 'edit_theme_options',
    )
);

//Sidebar Layout
$wp_customize->add_section( 'blogarise_theme_sidebar_setting' , array(
    'title' => __('Sidebar Width', 'blogarise'),
    'priority' => 15,
    'panel' => 'themes_layout',
) );

$wp_customize->add_setting('blogarise_theme_sidebar_width',
    array(
        'default'           => 280,
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control('blogarise_theme_sidebar_width',
    array(
        'label'    => esc_html__('Sidebar Width', 'blogarise'),
        'section'  => 'blogarise_theme_sidebar_setting',
        'type'     => 'number',
        'priority' => 50,
    )
);

$wp_customize->add_section('blog_layout_section',
    array(
        'title' => esc_html__('Blog Layout', 'blogarise'),
        'priority' => 30,
        'capability' => 'edit_theme_options',
        'panel' => 'themes_layout',
    )
);

$wp_customize->add_setting(
    'blog_layout_title',
    array(
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_text',
    )
);
$wp_customize->add_control(
'blog_layout_title',
    array(
        'type' => 'hidden',
        'label' => __('Blog Layout','blogarise'),
        'section' => 'blog_layout_section',

    )
);
    
$wp_customize->add_setting(
    'blogarise_content_layout', array(
    'default'           => 'align-content-right',
    'sanitize_callback' => 'blogarise_sanitize_radio',
    'transport'  => 'postMessage',
) );
$wp_customize->add_control(
    new Blogarise_Custom_Radio_Default_Image_Control( 
        // $wp_customize object
        $wp_customize,
        // $id
        'blogarise_content_layout',
        // $args
        array(
            'settings'      => 'blogarise_content_layout',
            'section'       => 'blog_layout_section',
            'choices'       => array(
                'align-content-left' => get_template_directory_uri() . '/images/fullwidth-left-sidebar.png',  
                'full-width-content'    => get_template_directory_uri() . '/images/fullwidth.png',
                'align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
                'grid-left-sidebar' => get_template_directory_uri() . '/images/grid-left-sidebar.png',
                'grid-fullwidth' => get_template_directory_uri() . '/images/grid-fullwidth.png',
                'grid-right-sidebar' => get_template_directory_uri() . '/images/grid-right-sidebar.png',
            )
        )
    )
);

// Single Layout Section.
$wp_customize->add_section('site_layout_settings',
    array(
        'title' => esc_html__('Single Layout', 'blogarise'),
        'priority' => 35,
        'capability' => 'edit_theme_options',
        'panel' => 'themes_layout',
    )
);
$wp_customize->add_setting(
    'blogarise_pro_single_page_heading',
    array(
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_text',
        'priority' => 1,
    )
);

$wp_customize->add_control(
'blogarise_pro_single_page_heading',
    array(
        'type' => 'hidden',
        'label' => __('Single Blog Pages','blogarise'),
        'section' => 'site_layout_settings',
    )
);

$wp_customize->add_setting(
    'blogarise_single_page_layout', array(
    'default'           => 'single-align-content-right',
    'sanitize_callback' => 'blogarise_sanitize_radio'
) );


$wp_customize->add_control(
    new Blogarise_Custom_Radio_Default_Image_Control( 
        // $wp_customize object
        $wp_customize,
        // $id
        'blogarise_single_page_layout',
        // $args
        array(
            'settings'      => 'blogarise_single_page_layout',
            'section'       => 'site_layout_settings',
            'choices'       => array(
                'single-align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
                'single-align-content-left' => get_template_directory_uri() . '/images/fullwidth-left-sidebar.png',
                'single-full-width-content'    => get_template_directory_uri() . '/images/fullwidth.png',
            )
        )
    )
);

// Page Layout Section.
$wp_customize->add_section('page_layout_settings',
    array(
        'title' => esc_html__('Page', 'blogarise'),
        'priority' => 40,
        'capability' => 'edit_theme_options',
        'panel' => 'themes_layout',
    )
); 
$wp_customize->add_setting(
    'blogarise_page_heading',
    array(
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_text',
        'priority' => 1,
    )
);
$wp_customize->add_control(
'blogarise_page_heading',
    array(
        'type' => 'hidden',
        'label' => __('Page','blogarise'),
        'section' => 'page_layout_settings',
    )
);

$wp_customize->add_setting(
    'blogarise_page_layout', array(
    'default'           => 'page-align-content-right',
    'sanitize_callback' => 'blogarise_sanitize_radio',
    'transport'  => 'postMessage',
) );
$wp_customize->add_control(
    new Blogarise_Custom_Radio_Default_Image_Control( 
        // $wp_customize object
        $wp_customize,
        // $id
        'blogarise_page_layout',
        // $args
        array(
            'settings'      => 'blogarise_page_layout',
            'section'       => 'page_layout_settings',
            'choices'       => array(
                'page-align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
                'page-align-content-left' => get_template_directory_uri() . '/images/fullwidth-left-sidebar.png',
                'page-full-width-content'    => get_template_directory_uri() . '/images/fullwidth.png',
            )
        )
    )
);