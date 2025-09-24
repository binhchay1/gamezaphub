<?php if (!function_exists('blogarise_header_type_section')) :
/**
 *  Header
 *
 * @since blogarise
 *
 */
    function blogarise_header_type_section(){
        $header_menu_layout = get_theme_mod('header_menu_layout','default');

        if($header_menu_layout == 'default'){ 
            blogarise_header_default_section();
        }
    }
endif;
add_action('blogarise_action_blogarise_header_type_section', 'blogarise_header_type_section', 6);