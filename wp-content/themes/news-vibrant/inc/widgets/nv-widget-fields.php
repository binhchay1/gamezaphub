<?php
/**
 * Define custom fields for widgets
 * 
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

function news_vibrant_widgets_show_widget_field( $instance = '', $widget_field = '', $news_vibrant_widget_field_value = '' ) {
    
    extract( $widget_field );

    switch ( $news_vibrant_widgets_field_type ) {

        /**
         * text widget field
         */
        case 'text'
        ?>
            <p>
                <span class="field-label"><label for="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>"><?php echo esc_html( $news_vibrant_widgets_title ); ?></label></span>
                <input class="widefat" id="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ) ); ?>" type="text" value="<?php echo esc_html( $news_vibrant_widget_field_value ); ?>" />

                <?php if ( isset( $news_vibrant_widgets_description ) ) { ?>
                    <br />
                    <em><?php echo wp_kses_post( $news_vibrant_widgets_description ); ?></em>
                <?php } ?>
            </p>
        <?php
            break;

        /**
         * url widget field
         */
        case 'url' :
        ?>
            <p>
                <span class="field-label"><label for="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>"><?php echo esc_html( $news_vibrant_widgets_title ); ?></label></span>
                <input class="widefat" id="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ) ); ?>" type="text" value="<?php echo esc_url( $news_vibrant_widget_field_value ); ?>" />

                <?php if ( isset( $news_vibrant_widgets_description ) ) { ?>
                    <br />
                    <em><?php echo wp_kses_post( $news_vibrant_widgets_description ); ?></em>
                <?php } ?>
            </p>
        <?php
            break;
        
        /**
         * checkbox widget field
         */
        case 'checkbox' :
            ?>
            <p>
                <input id="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ) ); ?>" type="checkbox" value="1" <?php checked( '1', $news_vibrant_widget_field_value ); ?>/>
                <label for="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>"><?php echo esc_html( $news_vibrant_widgets_title ); ?></label>

                <?php if ( isset( $news_vibrant_widgets_description ) ) { ?>
                    <br />
                    <em><?php echo wp_kses_post( $news_vibrant_widgets_description ); ?></em>
                <?php } ?>
            </p>
            <?php
            break;

        /**
         * category dropdown widget field
         */
        case 'category_dropdown' :
            if ( empty( $news_vibrant_widget_field_value ) ) {
                $news_vibrant_widget_field_value = $news_vibrant_widgets_default;
            }
            $select_field = 'name="'. esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ) ) .'" id="'. esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ) .'" class="widefat"';
        ?>
                <p>
                    <label for="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>"><?php echo esc_html( $news_vibrant_widgets_title ); ?>:</label>
                    <?php
                        $dropdown_args = wp_parse_args( array(
                            'taxonomy'          => 'category',
                            'show_option_none'  => __( '- - Select Category - -', 'news-vibrant' ),
                            'selected'          => esc_attr( $news_vibrant_widget_field_value ),
                            'show_option_all'   => '',
                            'orderby'           => 'id',
                            'order'             => 'ASC',
                            'show_count'        => 0,
                            'hide_empty'        => 1,
                            'child_of'          => 0,
                            'exclude'           => '',
                            'hierarchical'      => 1,
                            'depth'             => 0,
                            'tab_index'         => 0,
                            'hide_if_empty'     => false,
                            'option_none_value' => 0,
                            'value_field'       => 'slug',
                        ) );

                        $dropdown_args['echo'] = false;

                        $dropdown = wp_dropdown_categories( $dropdown_args );
                        $dropdown = str_replace( '<select', '<select ' . $select_field, $dropdown );
                        echo $dropdown;
                    ?>
                </p>
        <?php
            break;

        /**
         * number widget field
         */
        case 'number' :
            if ( empty( $news_vibrant_widget_field_value ) ) {
                $news_vibrant_widget_field_value = $news_vibrant_widgets_default;
            }
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>"><?php echo esc_html( $news_vibrant_widgets_title ); ?></label>
                <input name="<?php echo esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ) ); ?>" type="number" step="1" min="1" id="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>" value="<?php echo esc_html( $news_vibrant_widget_field_value ); ?>" class="small-text" />

                <?php if ( isset( $news_vibrant_widgets_description ) ) { ?>
                    <br />
                    <em><?php echo wp_kses_post( $news_vibrant_widgets_description ); ?></em>
                <?php } ?>
            </p>
        <?php
            break;

        /**
         * multi check boxes widget field
         */
        case 'multicheckboxes':
        ?>
            <p><span class="field-label"><label><?php echo esc_html( $news_vibrant_widgets_title ); ?></label></span></p>
            <ul class="cv-multicheckbox-wrapper">
        <?php
            foreach ( $news_vibrant_widgets_field_options as $checkbox_option_name => $checkbox_option_title ) {
                if ( isset( $news_vibrant_widget_field_value[$checkbox_option_name] ) ) {
                    $current_category = 1;
                } else {
                    $current_category = 0;
                }
        ?>
                <li>
                    <input id="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name .'-'. $checkbox_option_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ).'['.$checkbox_option_name.']' ); ?>" type="checkbox" value="1" <?php checked( '1', $current_category ); ?>/>
                    <label for="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name .'-'. $checkbox_option_name ) ); ?>"><?php echo esc_html( $checkbox_option_title ); ?></label>
                </li>
        <?php
                }
        ?>
            </ul>
        <?php
                if ( isset( $news_vibrant_widgets_description ) ) {
        ?>
                    <em><?php echo wp_kses_post( $news_vibrant_widgets_description ); ?></em>
            <?php
                }
            break;

        /**
         * selector widget field
         */
        case 'selector':
            if ( empty( $news_vibrant_widget_field_value ) ) {
                $news_vibrant_widget_field_value = $news_vibrant_widgets_default;
            }
        ?>
            <p><span class="field-label"><label class="field-title"><?php echo esc_html( $news_vibrant_widgets_title ); ?></label></span></p>
        <?php            
            echo '<div class="selector-labels">';
            foreach ( $news_vibrant_widgets_field_options as $option => $val ) {
                $class = ( $news_vibrant_widget_field_value == $option ) ? 'selector-selected': '';
                echo '<label class="'.esc_attr( $class ).'" data-val="'.esc_attr( $option ).'"><img src="'.esc_url( $val ).'"/></label>'; 
            }
            echo '</div><input data-default="'.esc_attr( $news_vibrant_widget_field_value ).'" type="hidden" value="'.esc_attr( $news_vibrant_widget_field_value ).'" name="'.esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ) ).'"/>';
            break;

        /**
         * upload widget field
         */
        case 'upload':
            $image = $image_class = "";
            if ( $news_vibrant_widget_field_value ) { 
                $image = '<img src="'.esc_url( $news_vibrant_widget_field_value ).'" style="max-width:100%;"/>';    
                $image_class = ' hidden';
            }
            ?>
            <div class="attachment-media-view">

            <p><span class="field-label"><label for="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>"><?php echo esc_html( $news_vibrant_widgets_title ); ?>:</label></span></p>
            
                <div class="placeholder<?php echo esc_attr( $image_class ); ?>">
                    <?php esc_html_e( 'No image selected', 'news-vibrant' ); ?>
                </div>
                <div class="thumbnail thumbnail-image">
                    <?php echo $image; ?>
                </div>

                <div class="actions nv-clearfix">
                    <button type="button" class="button nv-delete-button align-left"><?php esc_html_e( 'Remove', 'news-vibrant' ); ?></button>
                    <button type="button" class="button nv-upload-button alignright"><?php esc_html_e( 'Select Image', 'news-vibrant' ); ?></button>
                    
                    <input name="<?php echo esc_attr( $instance->get_field_name( $news_vibrant_widgets_name ) ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $news_vibrant_widgets_name ) ); ?>" class="upload-id" type="hidden" value="<?php echo esc_url( $news_vibrant_widget_field_value ) ?>"/>
                </div>

            <?php if ( isset( $news_vibrant_widgets_description ) ) { ?>
                <br />
                <em><?php echo wp_kses_post( $news_vibrant_widgets_description ); ?></em>
            <?php } ?>

            </div>
            <?php
            break;
    }
}

function news_vibrant_widgets_updated_field_value( $widget_field, $new_field_value ) {

    extract( $widget_field );
    
    if ( $news_vibrant_widgets_field_type == 'number') {
        return absint( $new_field_value );
    } elseif ( $news_vibrant_widgets_field_type == 'url' ) {
        return esc_url( $new_field_value );
    } elseif ( $news_vibrant_widgets_field_type == 'multicheckboxes' ) {
        $multicheck_list = array();
        if ( is_array( $new_field_value ) ) {
            foreach( $new_field_value as $key => $value ) {
                $multicheck_list[esc_attr( $key )] = esc_attr( $value );
            }
        }
        return $multicheck_list;
    } else {
        return sanitize_text_field( $new_field_value );
    }
}