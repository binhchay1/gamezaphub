<?php
/**
 * Theme functions and definitions
 *
 * @package bloggers
 */
if ( ! function_exists( 'bloggers_enqueue_styles' ) ) :
	/**
	 * @since 0.1
	 */
	function bloggers_enqueue_styles() {
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
		wp_enqueue_style( 'blogarise-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'bloggers-style', get_stylesheet_directory_uri() . '/style.css', array( 'blogarise-style-parent' ), '1.0' );
		wp_dequeue_style( 'blogarise-default',get_template_directory_uri() .'/css/colors/default.css');
		wp_enqueue_style( 'bloggers-default-css', get_stylesheet_directory_uri()."/css/colors/default.css" );
        wp_enqueue_style( 'bloggers-dark', get_stylesheet_directory_uri()."/css/colors/dark.css" );
        wp_enqueue_style( 'bloggers-owl', get_stylesheet_directory_uri()."/css/owl.carousel.css" );

		if(is_rtl()){
		wp_enqueue_style( 'blogarise_style_rtl', trailingslashit( get_template_directory_uri() ) . 'style-rtl.css' );
	    }
		
	}

endif;
add_action( 'wp_enqueue_scripts', 'bloggers_enqueue_styles', 9999 );

function bloggers_theme_setup() {

    //Load text domain for translation-ready
    load_theme_textdomain('bloggers', get_stylesheet_directory() . '/languages');

	require( get_stylesheet_directory() . '/font.php' );

    $args = array(
        'default-image'      => get_stylesheet_directory_uri() . '/images/head-image.jpg',
        'width'              => 1600,
        'height'             => 600,
        'flex-height'        => false,
        'flex-width'         => false,
        'header-text'        => true,
    );

    add_theme_support( 'custom-header', $args );

    add_theme_support( 'title-tag' );
    
	add_theme_support( 'automatic-feed-links' );

}
add_action( 'after_setup_theme', 'bloggers_theme_setup' );

include_once('hooks/custom-block-wp.php');

add_action( 'customize_register', 'bloggers_customizer_rid_values', 1000 );
function bloggers_customizer_rid_values($wp_customize) {

  $wp_customize->remove_control('blogarise_content_layout');
  $wp_customize->remove_control('blogarise_title_font_size');      

}

if ( ! function_exists( 'bloggers_admin_scripts' ) ) :
function bloggers_admin_scripts() {

    wp_enqueue_style('bloggers-admin-style-css', get_stylesheet_directory_uri() . '/css/customizer-controls.css');
}
endif;
add_action( 'admin_enqueue_scripts', 'bloggers_admin_scripts' );

/**
* banner additions.
*/
require get_stylesheet_directory().'/hooks/hook-front-page-main-banner-section.php';

if (!function_exists('bloggers_get_block')) :
    /**
     *
     * @param null
     *
     * @return null
     *
     * @since bloggers 1.0.0
     *
     */
    function bloggers_get_block($block = 'grid', $section = 'post') {

        get_template_part('hooks/blocks/block-' . $section, $block);

    }
endif;


function bloggers_limit_content_chr( $content, $limit=100 ) {
    return mb_strimwidth( strip_tags($content), 0, $limit, '...' );
}

$args = array(
    'default-color' => '#FFF6E6',
    'default-image' => '',
	);
add_theme_support( 'custom-background', $args );


function bloggers_bg_image_wrapper(){
	?>
	<div class="bloggers-background-wrapper">
		<div class="squares">
			<span class="square"></span>
			<span class="square"></span>
			<span class="square"></span>
			<span class="square"></span>
			<span class="square"></span>
		</div>
		<div class="circles">
			<span class="circle"></span>
			<span class="circle"></span>
			<span class="circle"></span>
			<span class="circle"></span>
			<span class="circle"></span>
		</div>
		<div class="triangles">
			<span class="triangle"></span>
			<span class="triangle"></span>
			<span class="triangle"></span>
			<span class="triangle"></span>
			<span class="triangle"></span>
		</div>
	</div>
	<?php
} 
add_action('wp_footer','bloggers_bg_image_wrapper');