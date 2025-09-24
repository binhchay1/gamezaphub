<?php /*** Option Panel
 *
 * @package blogarise
 */

$blogarise_default = blogarise_get_default_theme_options();
/*theme option panel info*/
require get_template_directory() . '/inc/ansar/customize/frontpage-options.php';

/**
 * Create a Radio-Image control
 * 
 * This class incorporates code from the Kirki Customizer Framework and from a tutorial
 * written by Otto Wood.
 * 
 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
 * is licensed under the terms of the GNU GPL, Version 2 (or later).
 * 
 * @link https://github.com/reduxframework/kirki/
 * @link http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
 */
class Blogarise_Custom_Radio_Default_Image_Control extends WP_Customize_Control {
    
    /**
     * Declare the control type.
     *
     * @access public
     * @var string
     */
    public $type = 'radio-image';
    
    /**
     * Enqueue scripts and styles for the custom control.
     * 
     * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
     * 
     * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
     * at 'customize_controls_print_styles'.
     *
     * @access public
     */
    public function enqueue() {
        wp_enqueue_script( 'jquery-ui-button' );
    }
    
    /**
     * Render the control to be displayed in the Customizer.
     */
    public function render_content() {
        if ( empty( $this->choices ) ) {
            return;
        }           
        
        $name = '_customize-radio-' . $this->id;
        ?>
        <span class="customize-control-title">
            <?php echo esc_attr( $this->label ); ?>
            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php endif; ?>
        </span>
        <div id="input_<?php echo $this->id; ?>" class="image">
            <?php foreach ($this->choices as $value => $label ): ?>
                <input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo esc_attr($this->id . $value); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
                    <label for="<?php echo esc_attr($this->id . $value); ?>">
                        <img src="<?php echo esc_html( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $value ); ?>">
                    </label>
                </input>
            <?php endforeach; ?>
        </div>
        <script>jQuery(document).ready(function($) { $( '[id="input_<?php echo $this->id; ?>"]' ).buttonset(); });</script>
        <?php
    }
}

//Theme Option Panel
$wp_customize->add_panel('theme_option_panel',
    array(
        'title' => esc_html__('Theme Options', 'blogarise'),
        'priority' => 30,
        'capability' => 'edit_theme_options',
    )
);
// Typography Section.
$wp_customize->add_section( 'blogarise_typography_section' , array(
    'title'      => __('Typography Settings', 'blogarise'),
    'panel' => 'theme_option_panel',
    'priority'       => 10,
) );

//Blog Page Settings
$wp_customize->add_section('site_post_date_author_settings',
    array(
        'title' => esc_html__('Blog Page', 'blogarise'),
        'priority' => 20,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

//Single Page Settings
$wp_customize->add_section('site_single_posts_settings',
    array(
        'title' => esc_html__('Single Page', 'blogarise'),
        'priority' => 30,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

//Breadcrumb Settings
$wp_customize->add_section('blogarise_breadcrumb_settings',
    array(
        'title' => esc_html__('Breadcrumb Settings', 'blogarise'),
        'priority' => 40,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

//Sidebar Settings
$wp_customize->add_section('sidebar_settings',
    array(
        'title' => esc_html__('Sidebar Settings', 'blogarise'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

//You Missed seciton
$wp_customize->add_section('you_missed_section',
    array(
        'title' => esc_html__('You Missed Section', 'blogarise'),
        'priority' => 60,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Footer Options.
$wp_customize->add_section('footer_options', array(
    'title' => __('Footer Options','blogarise'),
    'priority' => 70,
    'panel' => 'theme_option_panel',
) );

//Footer Copyright
$wp_customize->add_section('footer_copyright', array(
    'title' => __('Footer Copyright','blogarise'),
    'priority' => 80,
    'panel' => 'theme_option_panel',
) );

//Post Image Settings
$wp_customize->add_section( 'post_image_options' , array(
    'title' => __('Post Image Settings', 'blogarise'),
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
    'priority' => 85,
) );
//Scroll Bar Section
$wp_customize->add_section( 'general_options' , array(
    'title' => __('Scroller', 'blogarise'),
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
    'priority' => 90,
) );

//========== Typography ===============//
$wp_customize->add_setting( 'enable_custom_typography',
    array(
        'default' => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'blogarise_sanitize_checkbox'
    )
);     
$wp_customize->add_control( new Blogarise_Toggle_Control( $wp_customize, 'enable_custom_typography',
    array(
        'label' => esc_html__( 'Typography Enable/Disable','blogarise'),
        'section' => 'blogarise_typography_section'
    )
) );

$wp_customize->add_setting('blogarise_site_title_font',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
); 
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_site_title_font',
        array(
            'label'             => esc_html__( 'Site Title Font', 'blogarise' ),
            'section'           => 'blogarise_typography_section',
        )
    )
);

$font_family = array(
    'Josefin Sans'=> 'Josefin Sans', 
    'Open Sans'=>'Open Sans', 
    'Kalam'=>'Kalam', 
    'Rokkitt'=>'Rokkitt', 
    'Jost, sans-serif' => 'Jost', 
    'Poppins' => 'Poppins', 
    'Lato' => 'Lato', 
    'Noto Serif'=>'Noto Serif', 
    'Raleway'=>'Raleway', 
    'Roboto' => 'Roboto',
    'Outfit, sans-serif' => 'Outfit',
);

$font_weight = array('300' => '300', '500' => '500', '600' => '600', '700' => '700');

$wp_customize->add_setting(
    'site_title_fontfamily',
    array(
        'default'           =>  'Outfit, sans-serif',
        'capability'        =>  'edit_theme_options',
        'sanitize_callback' =>  'sanitize_text_field',
    )   
);
$wp_customize->add_control('site_title_fontfamily', array(
    'label' => __('Font family','blogarise'),
    'section' => 'blogarise_typography_section',
    'setting' => 'site_title_fontfamily',
    'type'    =>  'select',
    'choices'=>$font_family,
));

$wp_customize->add_setting(
    'site_title_fontweight',
    array(
        'default'           =>  '700',
        'capability'        =>  'edit_theme_options',
        'sanitize_callback' =>  'sanitize_text_field',
    )   
);
$wp_customize->add_control('site_title_fontweight', array(
    'label' => __('Font Weight','blogarise'),
    'section' => 'blogarise_typography_section',
    'setting' => 'site_title_fontweight',
    'type'    =>  'select',
    'choices'=>$font_weight,
));

$wp_customize->add_setting('blogarise_menu_font',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
); 
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_menu_font',
        array(
            'label'             => esc_html__( 'Menu Font', 'blogarise' ),
            'section'           => 'blogarise_typography_section',
        )
    )
);

$wp_customize->add_setting(
'blogarise_menu_fontfamily',
    array(
        'default'           =>  'Outfit, sans-serif',
        'capability'        =>  'edit_theme_options',
        'sanitize_callback' =>  'sanitize_text_field',
    )   
);
$wp_customize->add_control('blogarise_menu_fontfamily', array(
    'label' => __('Font family','blogarise'),
    'section' => 'blogarise_typography_section',
    'setting' => 'blogarise_menu_fontfamily',
    'type'    =>  'select',
    'choices'=>$font_family,
));

//========== Blog Page ===============//
$wp_customize->add_setting('blogarise_post_meta_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
); 
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_post_meta_heading',
        array(
            'label'             => esc_html__( 'Post Meta', 'blogarise' ),
            'section'           => 'site_post_date_author_settings',
        )
    )
);

// Settings = Drop Caps
$wp_customize->add_setting('blogarise_drop_caps_enable',
    array(
        'default' => false,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_drop_caps_enable', 
    array(
        'label' => esc_html__('Drop Caps (First Big Letter)', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_post_date_author_settings',
    )
));

// Setting - global content alignment of news.
$wp_customize->add_setting('blogarise_global_category_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_global_category_enable', 
    array(
        'label' => esc_html__('Category', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_post_date_author_settings',
    )
)); 

$wp_customize->add_setting('global_post_date_author_setting',
    array(
        'default' => $blogarise_default['global_post_date_author_setting'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_select',
    )
);
$wp_customize->add_control('global_post_date_author_setting',
    array(
        'label' => esc_html__('Date and Author', 'blogarise'),
        'section' => 'site_post_date_author_settings',
        'type' => 'select',
        'choices' => array(
            'show-date-author' => esc_html__('Show Date and Author', 'blogarise'),
            'show-date-only' => esc_html__('Show Date Only', 'blogarise'),
            'show-author-only' => esc_html__('Show Author Only', 'blogarise'),
            'hide-date-author' => esc_html__('Hide All', 'blogarise'),
        ),
    )
);

$wp_customize->add_setting('blogarise_global_comment_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_global_comment_enable', 
    array(
        'label' => esc_html__('Comments', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_post_date_author_settings',
    )
));

$wp_customize->add_setting('blogarise_blog_content_settings',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_blog_content_settings',
        array(
            'label'             => esc_html__( 'Choose Content Option', 'blogarise' ),
            'section'           => 'site_post_date_author_settings',
        )
    )
); 

$wp_customize->add_setting('blogarise_blog_content', 
array(
    'default'           =>'excerpt',
    'sanitize_callback' => 'blogarise_sanitize_select',
    )
);
$wp_customize->add_control('blogarise_blog_content', 
array(            
    'section'   => 'site_post_date_author_settings',
    'type'      => 'radio',
    'choices'   =>  array(
        'excerpt'   => __('Excerpt', 'blogarise'),
        'content'   => __('Full Content', 'blogarise'),
        )
    )
);

$wp_customize->add_setting('blogarise_post_pagination_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_post_pagination_heading',
        array(
            'label'             => esc_html__( 'Pagination', 'blogarise' ),
            'section'           => 'site_post_date_author_settings',
        )
    )
);

// Setting - Single posts.
$wp_customize->add_setting('blogarise_pagination_remove',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_pagination_remove', 
    array(
        'label' => esc_html__('Hide/Show Next Pagination', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_post_date_author_settings',
    )
)); 

//========== Single Page ===============//
//Page Hedding
$wp_customize->add_setting('blogarise_single_page_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_single_page_heading',
        array(
            'label'             => esc_html__( 'Featured Post', 'blogarise' ),
            'section'           => 'site_single_posts_settings',
            'priority' => 10,
        )
    )
);

// Setting - Single posts.
$wp_customize->add_setting('blogarise_single_post_category',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_single_post_category', 
    array(
        'label' => esc_html__('Hide/Show Categories', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 20,
    )
));

$wp_customize->add_setting('blogarise_single_post_admin_details',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_single_post_admin_details', 
    array(
        'label' => esc_html__('Hide/Show Author Details', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 30,
    )
));

$wp_customize->add_setting('blogarise_single_post_date',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_single_post_date', 
    array(
        'label' => esc_html__('Hide/Show Date', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 40,
    )
));

$wp_customize->add_setting('blogarise_single_post_tag',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_single_post_tag', 
    array(
        'label' => esc_html__('Hide/Show Tag', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 50,
    )
));

// Setting - related posts.
$wp_customize->add_setting('single_show_featured_image',
    array(
        'default' => $blogarise_default['single_show_featured_image'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'single_show_featured_image', 
    array(
        'label' => __('Hide/Show Featured Image', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 60,
    )
));

$wp_customize->add_setting('single_show_share_icon',
    array(
        'default' => $blogarise_default['single_show_share_icon'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'single_show_share_icon', 
    array(
        'label' => __('Hide/Show Sharing Icons', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 70,
    )
));

//Page Hedding
$wp_customize->add_setting('blogarise_single_post_author_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_single_post_author_heading',
        array(
            'label'             => esc_html__( 'Author', 'blogarise' ),
            'section'           => 'site_single_posts_settings',
            'priority'          => 80,
        )
    )
);

$wp_customize->add_setting('blogarise_enable_single_admin_details',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);

$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_enable_single_admin_details', 
    array(
        'label' => esc_html__('Hide/Show Author Details', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 90,
    )
));

//Page Hedding
$wp_customize->add_setting('blogarise_single_related_post_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new Blogarise_Section_Title(
        $wp_customize,
        'blogarise_single_related_post_heading',
        array(
            'label'             => esc_html__( 'Related Post', 'blogarise' ),
            'section'           => 'site_single_posts_settings',
            'priority' => 95,
        )
    )
);

$wp_customize->add_setting('blogarise_enable_related_post',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_enable_related_post', 
    array(
        'label' => esc_html__('Enable Related Posts', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 100,
    )
));

$wp_customize->add_setting('blogarise_related_post_title', 
    array(
        'default' => esc_html__('Related Posts', 'blogarise'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control('blogarise_related_post_title', 
    array(
        'label' => esc_html__('Title', 'blogarise'),
        'type' => 'text',
        'section' => 'site_single_posts_settings',
        'priority' => 110,
    )
);

/************************* Meta Hide Show *********************************/
$wp_customize->add_setting('blogarise_enable_single_post_category',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_enable_single_post_category', 
    array(
        'label' => esc_html__('Hide/Show Categories', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 120,
    )
));

$wp_customize->add_setting('blogarise_enable_single_post_date',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_enable_single_post_date', 
    array(
        'label' => esc_html__('Hide/Show Date', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 130,
    )
));

$wp_customize->add_setting('blogarise_enable_single_post_admin_details',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_enable_single_post_admin_details', 
    array(
        'label' => esc_html__('Hide/Show Author Details', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 140,
    )
));

$wp_customize->add_setting('blogarise_enable_single_post_comments',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_enable_single_post_comments', 
    array(
        'label' => esc_html__('Hide/Show Comments', 'blogarise'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
        'priority' => 150,
    )
));

//========== Bredcrumb Settings ===============//
$wp_customize->add_setting('breadcrumb_settings',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'breadcrumb_settings', 
    array(
        'label' => esc_html__('Hide/Show Breadcrumb', 'blogarise'),
        'type' => 'toggle',
        'section' => 'blogarise_breadcrumb_settings',
    )
));

//Type Of Bredcrumb 
$wp_customize->add_setting( 'blogarise_site_breadcrumb_type', array(
    'sanitize_callback' => 'blogarise_sanitize_select',
    'default'   => 'default',
));
$wp_customize->add_control( 'blogarise_site_breadcrumb_type', array(
    'type'      => 'select',
    'section'   => 'blogarise_breadcrumb_settings',
    'label'     => esc_html__( 'Breadcrumb type', 'blogarise' ),
    'description' => esc_html__( 'If you use other than "default" one you will need to install and activate respective plugins Breadcrumb NavXT, Yoast SEO and Rank Math SEO', 'blogarise' ),
    'choices'   => array(
        'default' => __( 'Default', 'blogarise' ),
        'navxt'  => __( 'NavXT', 'blogarise' ),
        'yoast'  => __( 'Yoast SEO', 'blogarise' ),
        'rankmath'  => __( 'Rank Math', 'blogarise' )
    )
));

//========== Sidebar Stickey Settings ===============//
$wp_customize->add_setting('blogarise_sidebar_stickey',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_sidebar_stickey', 
    array(
        'label' => esc_html__('Sidebar Sticky', 'blogarise'),
        'type' => 'toggle',
        'section' => 'sidebar_settings',
    )
));

//========== You Missed Section ===============//
$wp_customize->add_setting('you_missed_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'you_missed_enable', 
    array(
        'label' => esc_html__('Hide / Show', 'blogarise'),
        'type' => 'toggle',
        'section' => 'you_missed_section',
    )
));

// Title
$wp_customize->add_setting(
    'you_missed_title',
    array(
        'default' => esc_html__('You Missed','blogarise'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    )
);
$wp_customize->add_control(
    'you_missed_title',
    array(
        'label' => __('Title','blogarise'),
        'section' => 'you_missed_section',
        'type' => 'text',
    )
);

// date in header display type
$wp_customize->add_setting( 'post_image_type', array(
    'default'           => $blogarise_default['post_image_type'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'blogarise_sanitize_select',
) );
$wp_customize->add_control( 'post_image_type', array(
    'type'     => 'radio',
    'label'    => esc_html__( 'Post Image Display Type:', 'blogarise' ),
    'choices'  => array(
        'post_fix_height'          => esc_html__( 'Fix Height Post Image', 'blogarise' ),
        'post_auto_height' => esc_html__( 'Auto Height Post Image', 'blogarise' ),
    ),
    'section'  => 'post_image_options',
) );

//========== Scroller ===============//
$wp_customize->add_setting('blogarise_scrollup_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'blogarise_scrollup_enable', 
    array(
        'label' => esc_html__('Hide / Show Scroller', 'blogarise'),
        'type' => 'toggle',
        'section' => 'general_options',
    )
));

$wp_customize->add_setting(
    'scrollup_layout', array(
    'default' => 'fas fa-angle-up',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'blogarise_sanitize_select',
    'transport' => 'postMessage',
) );  
$wp_customize->add_control(
    new Blogarise_Custom_Radio_Default_Image_Control( 
        // $wp_customize object
        $wp_customize,
        // $id
        'scrollup_layout',
        // $args
        array(
            'settings'      => 'scrollup_layout',
            'section'       => 'general_options',
            'choices'       => array(
                'fas fa-angle-up' => get_template_directory_uri() . '/images/fu1.svg',
                'fas fa-angle-double-up'    => get_template_directory_uri() . '/images/fu2.svg',
                'fas fas fa-arrow-up'    => get_template_directory_uri() . '/images/fu3.svg',
                'fas fa-long-arrow-alt-up'    => get_template_directory_uri() . '/images/fu4.svg',
            )
        )
    )
);

//========== Footer Options ===============//
$wp_customize->add_setting('blogarise_footer_logo_width',
    array(
        'default'           => 210,
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('blogarise_footer_logo_width',
    array(
        'label'    => esc_html__('Logo Width', 'blogarise'),
        'section'  => 'footer_options',
        'type'     => 'number',
    )
);

$wp_customize->add_setting('blogarise_footer_logo_height',
    array(
        'default'           => 70,
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control('blogarise_footer_logo_height',
    array(
        'label'    => esc_html__('Logo Height', 'blogarise'),
        'section'  => 'footer_options',
        'type'     => 'number',
    )
);

//Footer Background image
$wp_customize->add_setting( 
    'blogarise_footer_widget_background', array(
    'sanitize_callback' => 'esc_url_raw',
    'transport'         => 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'blogarise_footer_widget_background', array(
    'label'    => __( 'Background Image', 'blogarise' ),
    'section'  => 'footer_options',
    'settings' => 'blogarise_footer_widget_background',
) ) );

//Background Overlay 
$wp_customize->add_setting(
    'blogarise_footer_overlay_color', array( 'sanitize_callback' => 'blogarise_sanitize_alpha_color',
    'transport'         => 'postMessage',
    
) );
$wp_customize->add_control(new Blogarise_Customize_Alpha_Color_Control( $wp_customize,'blogarise_footer_overlay_color', array(
    'label'      => __('Overlay Color', 'blogarise' ),
    'palette' => true,
    'section' => 'footer_options')
) );

$wp_customize->add_setting(
    'blogarise_footer_text_color', array( 'sanitize_callback' => 'blogarise_sanitize_alpha_color',
    'transport'         => 'postMessage',
    
) );
$wp_customize->add_control( new Blogarise_Customize_Alpha_Color_Control( $wp_customize, 'blogarise_footer_text_color', array(
    'label'      => __('Text Color', 'blogarise' ),
    'palette' => true,
    'section' => 'footer_options')
));

$wp_customize->add_setting(
    'blogarise_footer_column_layout', array(
    'default' => 3,
    'transport' => 'postMessage',
    'sanitize_callback' => 'blogarise_sanitize_select',
) );
$wp_customize->add_control(
    'blogarise_footer_column_layout', array(
    'type' => 'select',
    'label' => __('Select Column Layout','blogarise'),
    'section' => 'footer_options',
    'choices' => array(1=>1, 2=>2,3=>3,4=>4),
) );

//Enable and disable social icon
$wp_customize->add_setting('footer_social_icon_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'footer_social_icon_enable', 
    array(
        'label' => esc_html__('Hide / Show Social Icon', 'blogarise'),
        'type' => 'toggle',
        'section' => 'footer_options',
    )
));

$wp_customize->add_setting(
    'blogarise_footer_social_icons',
    array(
        'default'           => blogarise_get_social_icon_default(),
        'sanitize_callback' => 'blogarise_repeater_sanitize',
    )
);
$wp_customize->add_control(
    new blogarise_Repeater_Control(
        $wp_customize,
        'blogarise_footer_social_icons',
        array(
            'label'                            => esc_html__( 'Social Icons', 'blogarise' ),
            'section'                          => 'footer_options',
            'add_field_label'                  => esc_html__( 'Add New Social', 'blogarise' ),
            'item_name'                        => esc_html__( 'Social', 'blogarise' ),
            'customizer_repeater_icon_control' => true,
            'customizer_repeater_link_control' => true,
            'customizer_repeater_checkbox_control' => true,
        )
    )
);

$wp_customize->add_setting( 'blogarise_social_footer_upgrade_to_pro', array(
    'capability'            => 'edit_theme_options',
    'sanitize_callback' => 'wp_filter_nohtml_kses',
));
$wp_customize->add_control(
    new blogarise_social_section_upgrade(
    $wp_customize,
    'blogarise_social_footer_upgrade_to_pro',
        array(
            'section'               => 'footer_options',
            'settings'              => 'blogarise_social_footer_upgrade_to_pro',
        )
    )
);

//========== Footer Copyright ===============//
//Enable and disable social icon
$wp_customize->add_setting('hide_copyright',
    array(
        'default' => true,
        'sanitize_callback' => 'blogarise_sanitize_checkbox',
        'transport' => 'postMessage',
    )
);
$wp_customize->add_control(new Blogarise_Toggle_Control( $wp_customize, 'hide_copyright', 
    array(
        'label' => esc_html__('Hide / Show Copyright', 'blogarise'),
        'type' => 'toggle',
        'section' => 'footer_copyright',
    )
));

$wp_customize->add_setting('blogarise_footer_copyright', array(
    'sanitize_callback' => 'sanitize_text_field',
    'default' => __('Copyright &copy; All rights reserved','blogarise'),
    'transport' => 'postMessage',
) );
$wp_customize->add_control('blogarise_footer_copyright', array(
    'label' => __('Copyright Text','blogarise'),
    'section' => 'footer_copyright',
    'type' => 'text',
) );

$wp_customize->add_setting(
    'blogarise_footer_copy_bg', array( 'sanitize_callback' => 'blogarise_sanitize_alpha_color',
    'transport' => 'postMessage',    
) );
$wp_customize->add_control( 'blogarise_footer_copy_bg', array(
    'label'      => __('Background Color', 'blogarise' ),
    'type' => 'color',
    'section' => 'footer_copyright')
);

$wp_customize->add_setting(
    'blogarise_footer_copy_text', array( 'sanitize_callback' => 'blogarise_sanitize_alpha_color',
    'transport' => 'postMessage',    
) );
$wp_customize->add_control( 'blogarise_footer_copy_text', array(
    'label'      => __('Text Color', 'blogarise' ),
    'type' => 'color',
    'section' => 'footer_copyright')
);

function blogarise_social_sanitize_checkbox( $input ) {
    return ( ( isset( $input ) && true == $input ) ? true : false );
}

function blogarise_template_page_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

function blogarise_header_info_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}
    
if ( ! function_exists( 'blogarise_sanitize_text_content' ) ) :

/**
 * Sanitize text content.
 *
 * @since 1.0.0
 *
 * @param string               $input Content to be sanitized.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return string Sanitized content.
 */
function blogarise_sanitize_text_content( $input, $setting ) {
    return ( stripslashes( wp_filter_post_kses( addslashes( $input ) ) ) );
}
endif;
    
function blogarise_header_sanitize_checkbox( $input ) {
    return ( ( isset( $input ) && true == $input ) ? true : false );
}