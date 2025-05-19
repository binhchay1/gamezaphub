<?php

// Posts Grid Widget.
require get_template_directory() . '/inc/widgets/posts-grid-widget.php';

// Posts List Widget.
require get_template_directory() . '/inc/widgets/posts-list-widget.php';

// Posts Small List Widget.
require get_template_directory() . '/inc/widgets/posts-small-list-widget.php';

// Posts Tile Widget.
require get_template_directory() . '/inc/widgets/posts-tile-widget.php';

// Posts Grid and List Widget.
require get_template_directory() . '/inc/widgets/posts-grid-and-list-widget.php';

// Social Icons Widget.
require get_template_directory() . '/inc/widgets/social-icons-widget.php';

// Categories Widget.
require get_template_directory() . '/inc/widgets/categories-widget.php';

// Popular Widget.
require get_template_directory() . '/inc/widgets/popular-widget.php';

/**
 * Register Widgets
 */
function horizon_news_pro_register_widgets()
{
	register_widget('Horizon_News_Posts_Grid_Widget');

	register_widget('Horizon_News_Posts_List_Widget');

	register_widget('Horizon_News_Posts_Small_List_Widget');

	register_widget('Horizon_News_Posts_Tile_Widget');

	register_widget('Horizon_News_Posts_Grid_And_List_Widget');

	register_widget('Horizon_News_Social_Icons_Widget');

	register_widget('Horizon_News_Categories_Widget');

	register_widget('Horizon_News_Popular_Widget');
}
add_action('widgets_init', 'horizon_news_pro_register_widgets');
