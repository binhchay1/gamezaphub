<?php
/**
 * News Vibrant custom function and work related to widgets.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function news_vibrant_widgets_init() {
	
	/**
	 * Register right sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'news-vibrant' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * Register left sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'news-vibrant' ),
		'id'            => 'news_vibrant_left_sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * Register header ads area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Header Ads', 'news-vibrant' ),
		'id'            => 'news_vibrant_header_ads_area',
		'description'   => esc_html__( 'Add banner widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * Register home top section area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Home Top Section', 'news-vibrant' ),
		'id'            => 'news_vibrant_home_top_section_area',
		'description'   => esc_html__( 'Add widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="nv-block-title">',
		'after_title'   => '</h2>',
	) );

	/**
	 * Register home middle section area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Home Middle Section', 'news-vibrant' ),
		'id'            => 'news_vibrant_home_middle_section_area',
		'description'   => esc_html__( 'Add widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="nv-block-title">',
		'after_title'   => '</h2>',
	) );

	/**
	 * Register home middle left aside area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Home Middle Left Aside', 'news-vibrant' ),
		'id'            => 'news_vibrant_home_middle_left_aside_area',
		'description'   => esc_html__( 'Add widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="nv-block-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * Register home middle right aside area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Home Middle Right Aside', 'news-vibrant' ),
		'id'            => 'news_vibrant_home_middle_right_aside_area',
		'description'   => esc_html__( 'Add widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="nv-block-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * Register home bottom section area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Home Bottom Section', 'news-vibrant' ),
		'id'            => 'news_vibrant_home_bottom_section_area',
		'description'   => esc_html__( 'Add widgets here.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="nv-block-title">',
		'after_title'   => '</h2>',
	) );

	/**
	 * Register 4 different footer area 
	 *
	 * @since 1.0.0
	 */
	register_sidebars( 4 , array(
		'name'          => esc_html__( 'Footer %d', 'news-vibrant' ),
		'id'            => 'news_vibrant_footer_sidebar',
		'description'   => esc_html__( 'Added widgets are display at Footer Widget Area.', 'news-vibrant' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'news_vibrant_widgets_init' );

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Register different widgets
 *
 * @since 1.0.1
 */
add_action( 'widgets_init', 'news_vibrant_register_widgets' );

function news_vibrant_register_widgets() {

	// Block Posts
	register_widget( 'News_Vibrant_Block_Posts' );

	// Carousel
	register_widget( 'News_Vibrant_Carousel' );

	// Default Tabbed
	register_widget( 'News_Vibrant_Default_Tabbed' );

	// Featured Posts
	register_widget( 'News_Vibrant_Featured_Posts' );

	// Featured Slider
	register_widget( 'News_Vibrant_Featured_Slider' );

	// Recent Posts
	register_widget( 'News_Vibrant_Recent_Posts' );

	// Social Media
	register_widget( 'News_Vibrant_Social_Media' );
}

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Load widget required files
 *
 * @since 1.0.0
 */

require get_template_directory() . '/inc/widgets/nv-widget-fields.php';    // Widget fields
require get_template_directory() . '/inc/widgets/nv-featured-slider.php';  // Featured Slider widget
require get_template_directory() . '/inc/widgets/nv-featured-posts.php';   // Featured posts widget
require get_template_directory() . '/inc/widgets/nv-block-posts.php';      // Block posts widget
require get_template_directory() . '/inc/widgets/nv-carousel.php';  	   // Carousel widget
require get_template_directory() . '/inc/widgets/nv-social-media.php';     // Social Media widget
require get_template_directory() . '/inc/widgets/nv-recent-posts.php';     // Recent Posts widget
require get_template_directory() . '/inc/widgets/nv-default-tabbed.php';   // Default Tabbed widget