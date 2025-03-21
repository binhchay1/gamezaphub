<?php
/**
 * CV: Carousel
 *
 * Widget show the posts from selected categories in carousel layouts.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

class News_Vibrant_Carousel extends WP_widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'news_vibrant_carousel',
            'description' => __( 'Displays posts from selected categories in carousel layouts.', 'news-vibrant' )
        );
        parent::__construct( 'news_vibrant_carousel', __( 'CV: Carousel', 'news-vibrant' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

        $news_vibrant_categories_lists = news_vibrant_categories_lists();
        
        $fields = array(

            'block_title' => array(
                'news_vibrant_widgets_name'         => 'block_title',
                'news_vibrant_widgets_title'        => __( 'Block title', 'news-vibrant' ),
                'news_vibrant_widgets_description'  => __( 'Enter your block title. (Optional - Leave blank to hide title.)', 'news-vibrant' ),
                'news_vibrant_widgets_field_type'   => 'text'
            ),

            'block_cat_slugs' => array(
                'news_vibrant_widgets_name'         => 'block_cat_slugs',
                'news_vibrant_widgets_title'        => __( 'Block Categories', 'news-vibrant' ),
                'news_vibrant_widgets_field_type'   => 'multicheckboxes',
                'news_vibrant_widgets_field_options' => $news_vibrant_categories_lists
            ),

            'block_layout' => array(
                'news_vibrant_widgets_name'         => 'block_layout',
                'news_vibrant_widgets_title'        => __( 'Block Layouts', 'news-vibrant' ),
                'news_vibrant_widgets_default'      => 'layout1',
                'news_vibrant_widgets_field_type'   => 'selector',
                'news_vibrant_widgets_field_options' => array(
                    'layout1' => esc_url( get_template_directory_uri() . '/assets/images/full-width1.png' )
                )
            ),

        );
        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        if ( empty( $instance ) ) {
            return ;
        }

        $news_vibrant_block_title      = empty( $instance['block_title'] ) ? '' : $instance['block_title'];
        $news_vibrant_block_cat_slugs  = empty( $instance['block_cat_slugs'] ) ? '' : $instance['block_cat_slugs'];
        $news_vibrant_block_layout     = empty( $instance['block_layout'] ) ? 'layout1' : $instance['block_layout'];

        if ( !empty( $news_vibrant_block_cat_slugs ) ) {
            $checked_cats = array();
            foreach ( $news_vibrant_block_cat_slugs as $cat_key => $cat_value ) {
                $checked_cats[] = $cat_key;
            }
        } else {
            return;
        }        
        $get_checked_cat_slugs = implode( ",", $checked_cats );
        $news_vibrant_post_count = apply_filters( 'news_vibrant_carousel_default_posts_count', 10 );
        $news_vibrant_block_args = array(
            'post_type'      => 'post',
            'category_name'  => wp_kses_post( $get_checked_cat_slugs ),
            'posts_per_page' => absint( $news_vibrant_post_count )
        );

        echo $before_widget;
    ?>
        <div class="nv-block-wrapper carousel-posts nv-clearfix <?php echo esc_attr( $news_vibrant_block_layout ); ?>">
            <div class="nv-block-title-nav-wrap">
                <?php 
                    if ( !empty( $news_vibrant_block_title ) ) {
                        echo $before_title . esc_html( $news_vibrant_block_title ) . $after_title;
                    }
                ?>
                    <div class="carousel-nav-action">
                        <div class="nv-navPrev carousel-controls"><i class="fa fa-angle-left"></i></div>
                        <div class="nv-navNext carousel-controls"><i class="fa fa-angle-right"></i></div>
                    </div>                    
                </div> <!-- nv-full-width-title-nav-wrap -->
            <div class="nv-block-posts-wrapper">
                <?php
                    switch ( $news_vibrant_block_layout ) {
                        
                        default:
                            news_vibrant_carousel_default_layout_section( $news_vibrant_block_args );
                            break;
                    }
                ?>
            </div><!-- .nv-block-posts-wrapper -->
        </div><!--- .nv-block-wrapper -->
    <?php
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param   array   $new_instance   Values just sent to be saved.
     * @param   array   $old_instance   Previously saved values from database.
     *
     * @uses    news_vibrant_widgets_updated_field_value()     defined in nv-widget-fields.php
     *
     * @return  array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            extract( $widget_field );

            // Use helper function to get updated field values
            $instance[$news_vibrant_widgets_name] = news_vibrant_widgets_updated_field_value( $widget_field, $new_instance[$news_vibrant_widgets_name] );
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param   array $instance Previously saved values from database.
     *
     * @uses    news_vibrant_widgets_show_widget_field()       defined in nv-widget-fields.php
     */
    public function form( $instance ) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            // Make array elements available as variables
            extract( $widget_field );
            $news_vibrant_widgets_field_value = !empty( $instance[$news_vibrant_widgets_name] ) ? $instance[$news_vibrant_widgets_name] : '';
            news_vibrant_widgets_show_widget_field( $this, $widget_field, $news_vibrant_widgets_field_value );
        }
    }
}