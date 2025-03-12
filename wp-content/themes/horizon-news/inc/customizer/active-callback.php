<?php

/**
 * Active Callbacks
 *
 * @package Horizon News
 */

// Theme Options.
function horizon_news_is_pagination_enabled( $control ) {
	return ( $control->manager->get_setting( 'horizon_news_enable_pagination' )->value() );
}
function horizon_news_is_breadcrumb_enabled( $control ) {
	return ( $control->manager->get_setting( 'horizon_news_enable_breadcrumb' )->value() );
}


// Header Options.
function horizon_news_is_topbar_enabled( $control ) {
	return ( $control->manager->get_Setting( 'horizon_news_enable_topbar' )->value() );
}

// Flash News Section.
function horizon_news_is_flash_news_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'horizon_news_enable_flash_news_section' )->value() );
}
function horizon_news_is_flash_news_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_flash_news_content_type' )->value();
	return ( horizon_news_is_flash_news_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function horizon_news_is_flash_news_section_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_flash_news_content_type' )->value();
	return ( horizon_news_is_flash_news_section_enabled( $control ) && ( 'category' === $content_type ) );
}

// Banner Section.
function horizon_news_is_banner_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'horizon_news_enable_banner_section' )->value() );
}
// Banner Section - Trending Posts.
function horizon_news_is_trending_posts_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_trending_posts_content_type' )->value();
	return ( horizon_news_is_banner_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function horizon_news_is_trending_posts_section_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_trending_posts_content_type' )->value();
	return ( horizon_news_is_banner_section_enabled( $control ) && ( 'category' === $content_type ) );
}
// Banner Section - Main News.
function horizon_news_is_banner_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_main_news_content_type' )->value();
	return ( horizon_news_is_banner_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function horizon_news_is_banner_section_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_main_news_content_type' )->value();
	return ( horizon_news_is_banner_section_enabled( $control ) && ( 'category' === $content_type ) );
}
// Banner Section - Editor Pick.
function horizon_news_is_editor_pick_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_editor_pick_content_type' )->value();
	return ( horizon_news_is_banner_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function horizon_news_is_editor_pick_section_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'horizon_news_editor_pick_content_type' )->value();
	return ( horizon_news_is_banner_section_enabled( $control ) && ( 'category' === $content_type ) );
}

// Check if static home page is enabled.
function horizon_news_is_static_homepage_enabled( $control ) {
	return ( 'page' === $control->manager->get_setting( 'show_on_front' )->value() );
}
