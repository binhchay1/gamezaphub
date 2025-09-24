<?php 
/**
 * BlogArise functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package BlogArise
 */

 	$blogarise_theme_path = get_template_directory() . '/inc/ansar/';

	require( $blogarise_theme_path . '/blogarise-custom-navwalker.php' );
	require( $blogarise_theme_path . '/default_menu_walker.php' );
	require( $blogarise_theme_path . '/font/font.php');
	require( $blogarise_theme_path . '/template-tags.php');
	require( $blogarise_theme_path . '/template-functions.php');
	require( $blogarise_theme_path. '/widgets/widgets-common-functions.php');
	require( $blogarise_theme_path . '/custom-control/custom-control.php');
	require( $blogarise_theme_path . '/custom-control/font/font-control.php');
	require_once get_template_directory() . '/inc/ansar/customizer-admin/blogarise-admin-plugin-install.php';
	require_once( trailingslashit( get_template_directory() ) . 'inc/ansar/customize-pro/class-customize.php' );

	// Theme version.
	$blogarise_theme = wp_get_theme();
	define( 'blogarise_THEME_VERSION', $blogarise_theme->get( 'Version' ) );
	define ( 'blogarise_THEME_NAME', $blogarise_theme->get( 'Name' ) );

	/*-----------------------------------------------------------------------------------*/
	/*	Enqueue scripts and styles.
	/*-----------------------------------------------------------------------------------*/
	require( $blogarise_theme_path .'/enqueue.php');
	/* ----------------------------------------------------------------------------------- */
	/* Customizer */
	/* ----------------------------------------------------------------------------------- */
	require( $blogarise_theme_path . '/customize/customizer.php');

	/* ----------------------------------------------------------------------------------- */
	/* Customizer */
	/* ----------------------------------------------------------------------------------- */

	require( $blogarise_theme_path  . '/widgets/widgets-init.php');

	/* ----------------------------------------------------------------------------------- */
	/* Widget */
	/* ----------------------------------------------------------------------------------- */

	require( $blogarise_theme_path  . '/hooks/hooks-init.php');

	/* custom-color file. */
	require( get_template_directory() . '/css/colors/theme-options-color.php');

	require get_template_directory().'/inc/ansar/hooks/blocks/header/header-init.php';

	/* Style For Sidebar*/
	require_once  get_template_directory()  . '/css/custom-style.php';


if ( ! function_exists( 'blogarise_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function blogarise_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on blogarise, use a find and replace
	 * to change 'blogarise' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'blogarise', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Add featured image sizes
        add_image_size('blogarise-slider-full', 1280, 720, true); // width, height, crop
        add_image_size('blogarise-featured', 1024, 0, false ); // width, height, crop
        add_image_size('blogarise-medium', 720, 380, true); // width, height, crop

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary menu', 'blogarise' ),
        'footer' => __( 'Footer menu', 'blogarise' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	$args = array(
    'default-color' => '#eee',
    'default-image' => '',
	);
	add_theme_support( 'custom-background', $args );

    // Set up the woocommerce feature.
    add_theme_support( 'woocommerce');

     // Woocommerce Gallery Support
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

    // Added theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	/* Add theme support for gutenberg block */
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	//Custom logo
	add_theme_support( 'custom-logo');
	
	// custom header Support
		$args = array(
			'width'			=> '1600',
			'height'		=> '300',
			'flex-height'		=> false,
			'flex-width'		=> false,
			'header-text'		=> true,
			'default-text-color'	=> '000',
			'wp-head-callback'       => 'blogarise_header_color',
		);
		add_theme_support( 'custom-header', $args );


	/*
     * Enable support for Post Formats on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/post-formats/
     */
    add_theme_support( 'post-formats', array( 'image', 'video', 'gallery', 'audio' ) );
	
	// Enable default block styles for Gutenberg blocks
	add_theme_support( 'wp-block-styles' );

	//Editor Styling 
	add_editor_style( array( 'css/editor-style.css') );

}
endif;
add_action( 'after_setup_theme', 'blogarise_setup' );


	function blogarise_the_custom_logo() {
	
		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}

	}

	add_filter('get_custom_logo','blogarise_logo_class');


	function blogarise_logo_class($html)
	{
	$html = str_replace('custom-logo-link', 'navbar-brand', $html);
	return $html;
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function blogarise_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'blogarise_content_width', 640 );
	}
	add_action( 'after_setup_theme', 'blogarise_content_width', 0 );


	/**
	 * Load Jetpack compatibility file.
	 */
	if (defined('JETPACK__VERSION')) {
	    require get_template_directory() . '/inc/jetpack.php';
	}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blogarise_widgets_init() {

	$blogarise_footer_column_layout = esc_attr(get_theme_mod('blogarise_footer_column_layout',3));
	
	$blogarise_footer_column_layout = 12 / $blogarise_footer_column_layout;
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Widget Area', 'blogarise' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );


	
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'blogarise' ),
		'id'            => 'footer_widget_area',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="col-md-'.$blogarise_footer_column_layout.' rotateInDownLeft animated bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );

}
add_action( 'widgets_init', 'blogarise_widgets_init' );