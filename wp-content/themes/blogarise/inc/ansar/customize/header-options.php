<?php // Add Header Options Panel.

$blogarise_default = blogarise_get_default_theme_options();

$wp_customize->add_panel('header_option_panel',
    array(
        'title' => esc_html__('Header Options', 'blogarise'),
        'priority' => 30,
        'capability' => 'edit_theme_options',
    )
);

$wp_customize->add_section( 'header_options' , array(
    'title' => __('Top Bar', 'blogarise'),
    'capability' => 'edit_theme_options',
    'panel' => 'header_option_panel',
    'priority' => 10,
) );

$wp_customize->add_setting(
'top_bar_tabs',
array(
    'default'           => '',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field'
    )
);
$wp_customize->add_control( new Custom_Tab_Control ( $wp_customize,'top_bar_tabs',
    array(
        'label'                 => '',
        'type' => 'custom-tab-control',
        'section'               => 'header_options',
        'controls_general'      => json_encode( array( '#customize-control-breaking_news_settings', 
                                                        '#customize-control-brk_news_enable',
                                                        '#customize-control-breaking_news_title',
        ) ),
        'controls_design'       => json_encode( array( 
                                                        '#customize-control-top_bar_header_background_color',
        ) ),
    ) 
));

$wp_customize->add_setting(
    'breaking_news_settings',
    array(
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_text',
        'priority' => 1,
    )
);
$wp_customize->add_control(
'breaking_news_settings',
    array(
        'type' => 'hidden',
        'label' => __('Breaking','blogarise'),
        'section' => 'header_options',
    )
);
$wp_customize->add_setting('brk_news_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new blogarise_Toggle_Control( $wp_customize, 'brk_news_enable', 
    array(
        'label' => esc_html__('Hide / Show', 'blogarise'),
        'type' => 'toggle',
        'section' => 'header_options',
    )
));

$wp_customize->add_setting(
'breaking_news_title',
    array(
        'default' => esc_html__('Breaking','blogarise'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ) 
);
$wp_customize->add_control(
'breaking_news_title',
    array(
        'label' => __('Title','blogarise'),
        'section' => 'header_options',
        'type' => 'text',
    )
);

$wp_customize->add_setting(
    'top_bar_header_background_color', array( 
        'sanitize_callback' => 'blogarise_sanitize_alpha_color',
        'transport' => 'postMessage',
    ) 
);
$wp_customize->add_control( 'top_bar_header_background_color', array(
    'label'      => __('Background Color', 'blogarise' ),
    'type' => 'color',
    'section' => 'header_options')
);

$wp_customize->add_section( 'social_options' , array(
    'title' => __('Social icons', 'blogarise'),
    'capability' => 'edit_theme_options',
    'panel' => 'header_option_panel',
    'priority' => 10,
) );

$wp_customize->add_setting(
    'social_settings',
    array(
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_text',
        'priority' => 1,
    )
);
$wp_customize->add_control(
'social_settings',
    array(
        'type' => 'hidden',
        'label' => __('Social icons','blogarise'),
        'section' => 'social_options',
    )
);

$wp_customize->add_setting('header_social_icon_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'header_social_icon_enable', 
    array(
        'label' => esc_html__('Hide / Show Social icons', 'blogarise'),
        'type' => 'toggle',
        'section' => 'social_options',
    )
));

$wp_customize->add_setting(
    'blogarise_header_social_icons',
    array(
        'default'           => blogarise_get_social_icon_default(),
        'sanitize_callback' => 'blogarise_repeater_sanitize',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(
    new blogarise_Repeater_Control(
        $wp_customize,
        'blogarise_header_social_icons',
        array(
            'label'                            => esc_html__( 'Social icons', 'blogarise' ),
            'section'                          => 'social_options',
            'add_field_label'                  => esc_html__( 'Add New', 'blogarise' ),
            'item_name'                        => esc_html__( 'Social', 'blogarise' ),
            'customizer_repeater_icon_control' => true,
            'customizer_repeater_link_control' => true,
            'customizer_repeater_checkbox_control' => true,
        )
    )
);

//Pro Button
class blogarise_social_section_upgrade extends WP_Customize_Control {
    public function render_content() { ?>
        <h3 class="customizer_blogarise_social_upgrade_to_pro" style="display: none;">
<?php esc_html_e('To add More Social Icon? Then','blogarise'); ?><a href="<?php echo esc_url( 'https://themeansar.com/blogarise-pro' ); ?>" target="_blank">
            <?php esc_html_e('Upgrade to Pro','blogarise'); ?> </a>  
        </h3>
    <?php
    }
}
    
$wp_customize->add_setting( 'blogarise_social_upgrade_to_pro', array(
    'capability'            => 'edit_theme_options',
    'sanitize_callback' => 'wp_filter_nohtml_kses',
));
$wp_customize->add_control(
    new blogarise_social_section_upgrade(
    $wp_customize,
    'blogarise_social_upgrade_to_pro',
        array(
            'section'               => 'social_options',
            'settings'              => 'blogarise_social_upgrade_to_pro',
        )
    )
);

// Advertisement Section.
$wp_customize->add_section('frontpage_advertisement_settings',
    array(
        'title' => esc_html__('Banner Advertisement', 'blogarise'),
        'priority' => 10,
        'capability' => 'edit_theme_options',
        'panel' => 'header_option_panel',
    )
);
// Setting banner_advertisement_section.
$wp_customize->add_setting('banner_advertisement_section',
    array(
        'default' => $blogarise_default['banner_advertisement_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    new WP_Customize_Cropped_Image_Control($wp_customize, 'banner_advertisement_section',
        array(
            'label' => esc_html__('Banner Section Advertisement', 'blogarise'),
            'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'blogarise'), 930, 100),
            'section' => 'frontpage_advertisement_settings',
            'width' => 930,
            'height' => 100,
            'flex_width' => true,
            'flex_height' => true,
            'priority' => 120,
        )
    )
);

/*banner_advertisement_section_url*/
$wp_customize->add_setting('banner_advertisement_section_url',
    array(
        'default' => $blogarise_default['banner_advertisement_section_url'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
        'default' => '#',
    )
);
$wp_customize->add_control('banner_advertisement_section_url',
    array(
        'label' => esc_html__('URL Link', 'blogarise'),
        'section' => 'frontpage_advertisement_settings',
        'type' => 'url',
        'priority' => 130,
    )
);
$wp_customize->add_setting('blogarise_open_on_new_tab',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new blogarise_Toggle_Control( $wp_customize, 'blogarise_open_on_new_tab', 
    array(
        'label' => esc_html__('Open link in a new tab', 'blogarise'),
        'type' => 'toggle',
        'section' => 'frontpage_advertisement_settings',
        'priority' => 140,
    )
));
//Menu Settings
$wp_customize->add_section( 'menu_options' , array(
    'title' => __('Menu', 'blogarise'),
    'capability' => 'edit_theme_options',
    'panel' => 'header_option_panel',
    'priority' => 10,
) );

$wp_customize->add_setting(
'menu_settings',
    array(
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_text',
        'priority' => 1,
    )
);
$wp_customize->add_control(
'menu_settings',
    array(
        'type' => 'hidden',
        'label' => __('Menu','blogarise'),
        'section' => 'menu_options',
    )
);
$wp_customize->add_setting('blogarise_menu_search',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_menu_search', 
    array(
        'label' => esc_html__('Hide / Show Search', 'blogarise'),
        'type' => 'toggle',
        'section' => 'menu_options',
    )
));
$wp_customize->add_setting('blogarise_menu_subscriber',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_menu_subscriber', 
    array(
        'label' => esc_html__('Hide / Show Subscribe Button', 'blogarise'),
        'type' => 'toggle',
        'section' => 'menu_options',
    )
));

$wp_customize->add_setting('blogarise_subsc_link',
    array(
        'default' => '#',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control('blogarise_subsc_link',
    array(
        'label' => esc_html__('Button Link', 'blogarise'),
        'section' => 'menu_options',
        'type' => 'url',
    )
);

$wp_customize->add_setting('blogarise_subsc_open_in_new',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_subsc_open_in_new', 
    array(
        'label' => esc_html__('Open link in new tab', 'blogarise'),
        'type' => 'toggle',
        'section' => 'menu_options',
    )
));

$wp_customize->add_setting('blogarise_lite_dark_switcher',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_lite_dark_switcher', 
    array(
        'label' => esc_html__('Hide / Show Dark and Lite Mode Switcher', 'blogarise'),
        'type' => 'toggle',
        'section' => 'menu_options',
    )
));