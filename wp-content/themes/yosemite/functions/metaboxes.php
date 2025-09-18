<?php

/*-----------------------------------------------------------------------------------*/
/*	Sidebar Selection meta box
/*-----------------------------------------------------------------------------------*/
function mts_add_sidebar_metabox() {
    $screens = array('post', 'page');
    foreach ($screens as $screen) {
        add_meta_box(
            'mts_sidebar_metabox',                  // id
            __('Sidebar', 'mythemeshop'),    // title
            'mts_inner_sidebar_metabox',            // callback
            $screen,                                // post_type
            'side',                                 // context (normal, advanced, side)
            'high'                               // priority (high, core, default, low)
                                                    // callback args ($post passed by default)
        );
    }
}
add_action('add_meta_boxes', 'mts_add_sidebar_metabox');


/**
 * Print the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function mts_inner_sidebar_metabox($post) {
    global $wp_registered_sidebars;
    
    // Add an nonce field so we can check for it later.
    wp_nonce_field('mts_inner_sidebar_metabox', 'mts_inner_sidebar_metabox_nonce');
    
    /*
    * Use get_post_meta() to retrieve an existing value
    * from the database and use the value for the form.
    */
    $custom_sidebar = get_post_meta( $post->ID, '_mts_custom_sidebar', true );
    $sidebar_location = get_post_meta( $post->ID, '_mts_sidebar_location', true );

    // Select custom sidebar from dropdown
    echo '<select name="mts_custom_sidebar" id="mts_custom_sidebar" style="margin-bottom: 10px;">';
    echo '<option value="" '.selected('', $custom_sidebar).'>-- '.__('Default', 'mythemeshop').' --</option>';
    
    // Exclude built-in sidebars
    $hidden_sidebars = array('widget-header','shop-sidebar', 'product-sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'footer-bottom', 'footer-bottom-2', 'footer-bottom-3', 'footer-bottom-4');    
    
    foreach ($wp_registered_sidebars as $sidebar) {
        if (!in_array($sidebar['id'], $hidden_sidebars)) {
            echo '<option value="'.esc_attr($sidebar['id']).'" '.selected($sidebar['id'], $custom_sidebar, false).'>'.$sidebar['name'].'</option>';
        }
    }
    echo '<option value="mts_nosidebar" '.selected('mts_nosidebar', $custom_sidebar).'>-- '.__('No sidebar --', 'mythemeshop').'</option>';    
    echo '</select><br />';
    
    // Select single layout (left/right sidebar)
    echo '<div class="mts_sidebar_location_fields">';
    echo '<label for="mts_sidebar_location_default" style="display: inline-block; margin-right: 20px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_default" value=""'.checked('', $sidebar_location, false).'>'.__('Default side', 'mythemeshop').'</label>';
    echo '<label for="mts_sidebar_location_left" style="display: inline-block; margin-right: 20px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_left" value="left"'.checked('left', $sidebar_location, false).'>'.__('Left', 'mythemeshop').'</label>';
    echo '<label for="mts_sidebar_location_right" style="display: inline-block; margin-right: 20px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_right" value="right"'.checked('right', $sidebar_location, false).'>'.__('Right', 'mythemeshop').'</label>';
    echo '</div>';
    
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            function mts_toggle_sidebar_location_fields() {
                $('.mts_sidebar_location_fields').toggle(($('#mts_custom_sidebar').val() != 'mts_nosidebar'));
            }
            mts_toggle_sidebar_location_fields();
            $('#mts_custom_sidebar').change(function() {
                mts_toggle_sidebar_location_fields();
            });
        });
    </script>
    <?php
    //debug
    //global $wp_meta_boxes;
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function mts_save_custom_sidebar( $post_id ) {
    
    /*
    * We need to verify this came from our screen and with proper authorization,
    * because save_post can be triggered at other times.
    */
    
    // Check if our nonce is set.
    if ( ! isset( $_POST['mts_inner_sidebar_metabox_nonce'] ) )
    return $post_id;
    
    $nonce = $_POST['mts_inner_sidebar_metabox_nonce'];
    
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'mts_inner_sidebar_metabox' ) )
      return $post_id;
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;
    
    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {
    
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
    
    } else {
    
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    
    /* OK, its safe for us to save the data now. */
    
    // Sanitize user input.
    $sidebar_name = sanitize_text_field( $_POST['mts_custom_sidebar'] );
    $sidebar_location = sanitize_text_field( $_POST['mts_sidebar_location'] );
    
    // Update the meta field in the database.
    update_post_meta( $post_id, '_mts_custom_sidebar', $sidebar_name );
    update_post_meta( $post_id, '_mts_sidebar_location', $sidebar_location );
}
add_action( 'save_post', 'mts_save_custom_sidebar' );

/*-----------------------------------------------------------------------------------*/
/*  Post Template Selection meta box
/*-----------------------------------------------------------------------------------*/
function mts_add_posttemplate_metabox() {
    add_meta_box(
        'mts_posttemplate_metabox',         // id
        __('Template', 'mythemeshop'),      // title
        'mts_inner_posttemplate_metabox',   // callback
        'post',                             // post_type
        'side',                             // context (normal, advanced, side)
        'high'                              // priority (high, core, default, low)
    );
}
add_action('add_meta_boxes', 'mts_add_posttemplate_metabox');


/**
 * Print the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function mts_inner_posttemplate_metabox($post) {
    global $wp_registered_sidebars;
    
    // Add an nonce field so we can check for it later.
    wp_nonce_field('mts_inner_posttemplate_metabox', 'mts_inner_posttemplate_metabox_nonce');
    
    /*
    * Use get_post_meta() to retrieve an existing value
    * from the database and use the value for the form.
    */
    $posttemplate = get_post_meta( $post->ID, '_mts_posttemplate', true );

    // Select post template
    echo '<select name="mts_posttemplate" style="margin-bottom: 10px;">';
    echo '<option value="" '.selected('', $posttemplate).'>'.__('Default Post Template', 'mythemeshop').'</option>';
    echo '<option value="parallax" '.selected('parallax', $posttemplate).'>'.__('Parallax Template', 'mythemeshop').'</option>';
    echo '</select><br />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function mts_save_posttemplate( $post_id ) {
    
    /*
    * We need to verify this came from our screen and with proper authorization,
    * because save_post can be triggered at other times.
    */
    
    // Check if our nonce is set.
    if ( ! isset( $_POST['mts_inner_posttemplate_metabox_nonce'] ) )
    return $post_id;
    
    $nonce = $_POST['mts_inner_posttemplate_metabox_nonce'];
    
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'mts_inner_posttemplate_metabox' ) )
      return $post_id;
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;
    
    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {
    
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
    
    } else {
    
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    
    /* OK, its safe for us to save the data now. */
    
    // Sanitize user input.
    $posttemplate = sanitize_text_field( $_POST['mts_posttemplate'] );
    
    // Update the meta field in the database.
    update_post_meta( $post_id, '_mts_posttemplate', $posttemplate );
}
add_action( 'save_post', 'mts_save_posttemplate' );

// Related function: mts_get_posttemplate( $single_template ) in functions.php

?>