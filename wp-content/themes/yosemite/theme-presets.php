<?php
// make sure to not include translations
$args['presets']['default'] = array(
	'title' => 'With Sidebar',
	'demo' => 'http://demo.mythemeshop.com/yosemite-main/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/default/thumb.jpg', // could use external url, to minimize theme zip size
	'menus' => array( 'primary-menu' => 'Primary Menu' ), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 4 ),
);

$args['presets']['alt'] = array(
	'title' => 'Without Sidebar',
	'demo' => 'http://demo.mythemeshop.com/yosemite/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/alt/thumb.jpg', // could use external url, to minimize theme zip size
	'menus' => array( 'primary-menu' => 'Primary Menu' ), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 4 ),
);

global $mts_presets;
$mts_presets = $args['presets'];
