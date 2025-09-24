<?php function blogarise_scripts() {

	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');

	wp_style_add_data('bootstrap', 'rtl', 'replace' );

	wp_enqueue_style( 'blogarise-style', get_stylesheet_uri() );

	wp_style_add_data( 'blogarise-style', 'rtl', 'replace' );

	wp_enqueue_style('blogarise-default', get_template_directory_uri() . '/css/colors/default.css');

	wp_enqueue_style('all-css',get_template_directory_uri().'/css/all.css');

	wp_enqueue_style('dark', get_template_directory_uri() . '/css/colors/dark.css');

	wp_enqueue_style('swiper-bundle-css', get_template_directory_uri() . '/css/swiper-bundle.css');
	
	wp_enqueue_style('smartmenus',get_template_directory_uri().'/css/jquery.smartmenus.bootstrap.css');	

	wp_enqueue_style('animate',get_template_directory_uri().'/css/animate.css');

	wp_enqueue_style('blogarise-custom-css', get_template_directory_uri() . '/inc/ansar/customize/css/customizer.css', array(), '1.0', 'all');

	/* Js script */

	wp_enqueue_script( 'blogarise-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'));

	wp_enqueue_script('blogarise_bootstrap_script', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'));

	wp_enqueue_script('swiper-bundle', get_template_directory_uri() . '/js/swiper-bundle.js', array('jquery'));

	wp_enqueue_script('blogarise_main-js', get_template_directory_uri() . '/js/main.js' , array('jquery'));

	wp_enqueue_script('sticksy-js', get_template_directory_uri() . '/js/sticksy.min.js' , array('jquery'));

	wp_enqueue_script('smartmenus-js', get_template_directory_uri() . '/js/jquery.smartmenus.js');

	wp_enqueue_script('bootstrap-smartmenus-js', get_template_directory_uri() . '/js/jquery.smartmenus.bootstrap.js' , array('jquery'));
	wp_enqueue_script('blogarise-marquee-js', get_template_directory_uri() . '/js/jquery.marquee.js' , array('jquery'));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
    wp_enqueue_script('jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.min.js', array('jquery'));

}
add_action('wp_enqueue_scripts', 'blogarise_scripts');
function blogarise_admin_enqueue( $hook ) {

	wp_enqueue_script( 'media-upload' );

	wp_enqueue_media();

	wp_enqueue_style( 'blogarise-admin-style', get_template_directory_uri() . '/css/admin-style.css' );

}
add_action( 'admin_enqueue_scripts', 'blogarise_admin_enqueue' );
//Custom Color
function blogarise_custom_js() {
	
	wp_enqueue_script('blogarise-dark', get_template_directory_uri() . '/js/dark.js' , array('jquery'));

	theme_options_color();
	
	wp_enqueue_script('blogarise_custom-js', get_template_directory_uri() . '/js/custom.js' , array('jquery'));

    $enable_custom_typography = get_theme_mod('enable_custom_typography',false);
    if( $enable_custom_typography == 'true') {
		custom_typography_function();
    }
}
add_action('wp_footer','blogarise_custom_js');

/**
 * Fix skip link focus in IE11.
 
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function blogarise_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'blogarise_skip_link_focus_fix' );

function blogarise_customizer_scripts() {
	
	wp_enqueue_style( 'blogarise-customizer-styles', get_template_directory_uri() . '/css/customizer-controls.css' );
}
add_action( 'customize_controls_print_footer_scripts', 'blogarise_customizer_scripts' );

if ( ! function_exists( 'blogarise_admin_scripts' ) ) :
function blogarise_admin_scripts() {
    wp_enqueue_script( 'blogarise-admin-script', get_template_directory_uri() . '/inc/ansar/customizer-admin/js/blogarise-admin-script.js', array( 'jquery' ), '', true );
    wp_localize_script( 'blogarise-admin-script', 'blogarise_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
    wp_enqueue_style('blogarise-admin-style-css', get_template_directory_uri() . '/css/customizer-controls.css');
}
endif;
add_action( 'admin_enqueue_scripts', 'blogarise_admin_scripts' );