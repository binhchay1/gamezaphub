<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

?>

		</div><!-- .cv-container -->
	</div><!-- #content -->

	<?php
		/**
	     * news_vibrant_footer hook
	     * @hooked - news_vibrant_footer_start - 5
	     * @hooked - news_vibrant_footer_widget_section - 10
	     * @hooked - news_vibrant_bottom_footer_start - 15
	     * @hooked - news_vibrant_footer_site_info_section - 20
	     * @hooked - news_vibrant_footer_menu_section - 25
	     * @hooked - news_vibrant_bottom_footer_end - 30
	     * @hooked - news_vibrant_footer_end - 35
	     *
	     * @since 1.0.0
	     */
	    do_action( 'news_vibrant_footer' );
	?>
</div><!-- #page -->

<?php
	/**
     * news_vibrant_after_page hook
     *
     * @since 1.0.0
     */
    do_action( 'news_vibrant_after_page' );

    wp_footer();
?>

</body>
</html>