<?php add_action('admin_enqueue_scripts', 'blogarise_author_widget_scripts');

function blogarise_author_widget_scripts() {    

    wp_enqueue_media();

    wp_enqueue_script('news_author_widget_script', get_template_directory_uri() . '/js/widget-image.js', false, '1.0', true);

}
class blogarise_author_info extends WP_Widget {  



    public function __construct() {
        parent::__construct(
            'blogarise-author-widget',
            __( 'AR: Author Info', 'blogarise' )
        );
    }

    function widget($args, $instance) {

        extract($args);

        $defaults = array(
            'title'    => '',
            'name'     => '',
            'desc'     => '',
            'image_uri'=> '',
            'facebook' => '',
            'twt'      => '',
            'insta'    => '',
            'youtube'  => '',
            'open_btnone_new_window' => 0,
        );
        
        $instance = wp_parse_args( (array) $instance, $defaults );

        echo $before_widget;
        
        $blogarise_btnone_target = '_self';
        if( !empty($instance['open_btnone_new_window']) ):
            $blogarise_btnone_target = '_blank';
        endif;

        echo $args['before_title'] . $instance['title'] . $args['after_title']; ?>
                    
            <div class="bs-author text-center">
                
                <?php if (!empty($instance['image_uri']) ) { ?> 
                    <img class="rounded-circle" src="<?php echo esc_url($instance['image_uri']); ?>" alt="<?php echo apply_filters('widget_title', $instance['name']); ?>" />
                <?php } ?>
                <h4><?php echo esc_html($instance['name']); ?></h4>
                <p><?php echo esc_html($instance['desc']); ?></p>
                
                <ul class="bs-social justify-content-center">
        
                    <?php if($instance['facebook'] !=''){?>
                        <li><a class="" <?php if($instance['open_btnone_new_window']) { ?> target="_blank" <?php } ?>href="<?php echo esc_url($instance['facebook']); ?>"><i class="fab  fa-facebook"></i></a></li>
                    <?php } if($instance['twt'] !=''){ ?>
                        <li><a class="" <?php if($instance['open_btnone_new_window']) { ?>target="_blank" <?php } ?>href="<?php echo esc_url($instance['twt']);?>"><i class="fa-brands fa-x-twitter"></i></a></li>
                    <?php } if($instance['insta'] !=''){ ?>
                        <li><a class="" <?php if($instance['open_btnone_new_window']) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($instance['insta']); ?>"><i class="fab fa-instagram"></i></a></li>
                    <?php } if($instance['youtube'] !=''){ ?>
                        <li><a class="" <?php if($instance['open_btnone_new_window']) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($instance['youtube']); ?>"><i class="fab fa-youtube"></i></a></li>
                    <?php } ?>
                </ul>   
            </div>
            <?php
        echo $after_widget;

    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        // Basic text fields
        $instance['title'] = sanitize_text_field( $new_instance['title'] ?? '' );
        $instance['name']  = sanitize_text_field( $new_instance['name'] ?? '' );

        // Description - lets keep it plain text but use textarea sanitizer
        $instance['desc'] = sanitize_textarea_field( $new_instance['desc'] ?? '' );

        // Image URL and social URLs — sanitize as URLs
        $instance['image_uri'] = esc_url_raw( $new_instance['image_uri'] ?? '' );
        $instance['facebook']  = esc_url_raw( $new_instance['facebook'] ?? '' );
        $instance['twt']       = esc_url_raw( $new_instance['twt'] ?? '' );
        $instance['insta']     = esc_url_raw( $new_instance['insta'] ?? '' );
        $instance['youtube']   = esc_url_raw( $new_instance['youtube'] ?? '' );

        // Checkbox — ensure it's always 0 or 1 (avoids undefined index / null)
        $instance['open_btnone_new_window'] = ! empty( $new_instance['open_btnone_new_window'] ) ? 1 : 0;

        return $instance;
    }

    function form($instance) {
        $defaults = array(
            'title' => 'Author Details',
            'name'  => '',
            'desc'  => '',
            'image_uri' => '',
            'facebook' => '',
            'twt' => '',
            'insta' => '',
            'youtube' => '',
            'open_btnone_new_window' => 0,
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
           <p>
          <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title','blogarise' ); ?></label> 
          <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
          </p>
            <p>
            <label for="<?php echo esc_attr($this->get_field_id('image_uri')); ?>"><?php esc_html_e('Author Image', 'blogarise'); ?></label><br/>

            <?php

            if ( !empty($instance['image_uri']) ) :

                echo '<img class="custom_media_image_team" src="' . $instance['image_uri'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" alt="'.__( 'Uploaded image', 'blogarise' ).'" /><br />';

            endif;

            ?>

            <input type="text" class="widefat custom_media_url_team" name="<?php echo esc_attr($this->get_field_name('image_uri')); ?>" id="<?php echo esc_attr($this->get_field_id('image_uri')); ?>" value="<?php if( !empty($instance['image_uri']) ): echo $instance['image_uri']; endif; ?>" style="margin-top:5px;">
            <input type="button" class="button button-primary custom_media_button_team" id="custom_media_button_team" name="<?php echo esc_attr($this->get_field_name('image_uri')); ?>" value="<?php esc_attr_e('Upload Image','blogarise'); ?>" style="margin-top:5px;">
        </p>

        <p>
          <label for="<?php echo esc_attr($this->get_field_id( 'name' )); ?>"><?php esc_html_e( 'Name','blogarise' ); ?></label> 
          <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'name' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'name' )); ?>" type="text" value="<?php echo esc_attr( $instance['name'] ); ?>" />
        </p>

          <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'desc' )); ?>"><?php esc_html_e( 'Description','blogarise' ); ?></label> 
           <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id( 'desc' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'desc' )); ?>"><?php echo esc_textarea( $instance['desc'] ); ?></textarea>
          </p>
      
            
        <table>
            <tr>
                <td>
                    <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php esc_html_e('Facebook Link', 'blogarise'); ?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" value="<?php if( !empty($instance['facebook']) ): echo esc_attr($instance['facebook']); endif; ?>" class="widefat"/>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="<?php echo esc_attr($this->get_field_id('twt')); ?>"><?php esc_html_e('Twitter Link', 'blogarise'); ?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="<?php echo esc_attr($this->get_field_name('twt')); ?>" id="<?php echo esc_attr($this->get_field_id('twt')); ?>" value="<?php if( !empty($instance['twt']) ): echo esc_attr($instance['twt']); endif; ?>" class="widefat"/>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="<?php echo esc_attr($this->get_field_id('insta')); ?>"><?php esc_html_e('Instagram Link', 'blogarise'); ?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="<?php echo esc_attr($this->get_field_name('insta')); ?>" id="<?php echo esc_attr($this->get_field_id('insta')); ?>" value="<?php if( !empty($instance['insta']) ): echo esc_attr($instance['insta']); endif; ?>" class="widefat"/>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('YouTube Link', 'blogarise'); ?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" value="<?php if( !empty($instance['youtube']) ): echo esc_attr($instance['youtube']); endif; ?>" class="widefat"/>
                </td>
            </tr>
        </table>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('open_btnone_new_window')); ?>">
                <input type="checkbox" 
                    id="<?php echo esc_attr($this->get_field_id('open_btnone_new_window')); ?>"
                    name="<?php echo esc_attr($this->get_field_name('open_btnone_new_window')); ?>"
                    value="1" <?php checked( $instance['open_btnone_new_window'], 1 ); ?> />
                <?php esc_html_e( 'Open link in new tab','blogarise' ); ?>
            </label>
        </p>
    <?php

    }
}