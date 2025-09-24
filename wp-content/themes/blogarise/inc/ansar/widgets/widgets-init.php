<?php

// Load widget base.
require_once get_template_directory() . '/inc/ansar/widgets/widgets-base.php';

/* Theme Widget sidebars. */
require get_template_directory() . '/inc/ansar/widgets/widgets-common-functions.php';

/* Theme Widgets*/

require get_template_directory() . '/inc/ansar/widgets/widget-author-info.php';
require get_template_directory() . '/inc/ansar/widgets/widget-recent-post.php';


/* Register site widgets */
if ( ! function_exists( 'blogarise_widgets' ) ) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function blogarise_widgets() {
         
        register_widget( 'blogarise_author_info');

    }
endif;
add_action( 'widgets_init', 'blogarise_widgets' );