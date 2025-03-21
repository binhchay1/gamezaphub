<?php
/**
 * Custom hooks functions are define about footer section.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_footer_start' ) ) :

	/**
	 * Footer start
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_footer_start() {
		echo '<footer id="colophon" class="site-footer" role="contentinfo">';
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_footer_widget_section' ) ) :

	/**
	 * Footer widget section
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_footer_widget_section() {
		get_sidebar( 'footer' );
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_bottom_footer_start' ) ) :

	/**
	 * Bottom footer start
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_bottom_footer_start() {
		echo '<div class="bottom-footer nv-clearfix">';
		echo '<div class="cv-container">';
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_footer_site_info_section' ) ) :

	/**
	 * Bottom footer side info
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_footer_site_info_section() {
?>
		<div class="site-info">
			<span class="nv-copyright-text">
				<?php 
					$news_vibrant_copyright_text = get_theme_mod( 'news_vibrant_copyright_text', __( 'News Vibrant', 'news-vibrant' ) );
					echo esc_html( $news_vibrant_copyright_text );
				?>
			</span>
			<span class="sep"> | </span> <span> <a href="/about/"> Về chúng tôi GameZapHub </a> </span> - <span><a href="/contact/"> Liên hệ </a></span> - <span><a href="/privacy-policy/"> Chính sách </a></span>
			
		</div><!-- .site-info -->
<?php
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_footer_menu_section' ) ) :

	/**
	 * Bottom footer menu
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_footer_menu_section() {
?>
		<nav id="footer-navigation" class="footer-navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'news_vibrant_footer_menu', 'menu_id' => 'footer-menu', 'fallback_cb' => false ) );
			?>
		</nav><!-- #site-navigation -->
<?php
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_bottom_footer_end' ) ) :

	/**
	 * Bottom footer end
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_bottom_footer_end() {
		echo '</div><!-- .cv-container -->';
		echo '</div> <!-- bottom-footer -->';
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_footer_end' ) ) :

	/**
	 * Footer end
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_footer_end() {
		echo '</footer><!-- #colophon -->';
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_go_top' ) ) :

	/**
	 * Go to Top Icon
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_go_top() {
		echo '<div id="nv-scrollup" class="animated arrow-hide"><i class="fa fa-chevron-up"></i></div>';
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

/**
 * Managed functions for footer hook
 *
 * @since 1.0.0
 */
add_action( 'news_vibrant_footer', 'news_vibrant_footer_start', 5 );
add_action( 'news_vibrant_footer', 'news_vibrant_footer_widget_section', 10 );
add_action( 'news_vibrant_footer', 'news_vibrant_bottom_footer_start', 15 );
add_action( 'news_vibrant_footer', 'news_vibrant_footer_site_info_section', 20 );
add_action( 'news_vibrant_footer', 'news_vibrant_footer_menu_section', 25 );
add_action( 'news_vibrant_footer', 'news_vibrant_bottom_footer_end', 30 );
add_action( 'news_vibrant_footer', 'news_vibrant_footer_end', 35 );
add_action( 'news_vibrant_footer', 'news_vibrant_go_top', 40 );