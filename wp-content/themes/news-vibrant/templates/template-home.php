<?php
/**
 * Template Name: Home Page
 *
 * This is the template that displays all widgets included in homepage widget area.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

get_header();

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Home Top Section Area
 * 
 * @since 1.0.0
 */
	if ( is_active_sidebar( 'news_vibrant_home_top_section_area' ) ) {
?>
		<div class="nv-home-top-section nv-clearfix">
			<?php dynamic_sidebar( 'news_vibrant_home_top_section_area' ); ?>
		</div><!-- .nv-home-top-section -->
<?php
	}

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Home Middle Section Area
 * 
 * @since 1.0.0
 */
	if ( is_active_sidebar( 'news_vibrant_home_middle_section_area' ) ) {
?>
		<div class="nv-home-middle-section nv-clearfix">
			<div class="middle-left-aside">
				<?php dynamic_sidebar( 'news_vibrant_home_middle_left_aside_area' ); ?>
			</div><!-- .middle-aside -->
			<div class="middle-primary">
				<?php dynamic_sidebar( 'news_vibrant_home_middle_section_area' ); ?>
			</div><!-- .middle-primary -->
			<div class="middle-right-aside">
				<?php dynamic_sidebar( 'news_vibrant_home_middle_right_aside_area' ); ?>
			</div><!-- .middle-aside -->
		</div><!-- .nv-home-middle-section -->
<?php
	}

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Home Bottom Section Area
 * 
 * @since 1.0.0
 */
	if ( is_active_sidebar( 'news_vibrant_home_bottom_section_area' ) ) {
?>
		<div class="nv-home-bottom-section">
			<?php dynamic_sidebar( 'news_vibrant_home_bottom_section_area' ); ?>
		</div><!-- .nv-home-bottom-section -->
<?php
	}

get_footer();