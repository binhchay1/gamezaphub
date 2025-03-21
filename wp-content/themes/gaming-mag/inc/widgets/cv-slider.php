<?php
/**
 * CV: Slider
 *
 * Widget to display posts from selected categories in slider section.
 *
 * @package News Vibrant
 * @subpackage Gaming Mag
 * @since 1.0.0
 */

class Gaming_Mag_Slider extends WP_widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'gaming_mag_slider',
            'description' => __( 'Displays posts from selected categories in the slider section.', 'gaming-mag' )
        );
        parent::__construct( 'gaming_mag_slider', __( 'CV: Slider', 'gaming-mag' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

        $news_vibrant_categories_lists = news_vibrant_categories_lists();

        $fields = array(
            'slider_cat_slugs' => array(
                'news_vibrant_widgets_name'         => 'slider_cat_slugs',
                'news_vibrant_widgets_title'        => __( 'Slider Categories', 'gaming-mag' ),
                'news_vibrant_widgets_field_type'   => 'multicheckboxes',
                'news_vibrant_widgets_field_options' => $news_vibrant_categories_lists
            )
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
        if( empty( $instance ) ) {
            return ;
        }

        $gaming_mag_slider_cat_slugs    = empty( $instance['slider_cat_slugs'] ) ? '' : $instance['slider_cat_slugs'];

        echo $before_widget;
    ?>
        <div class="cv-slider-wrapper cv-clearfix">
            <div class="slider-posts">
                <?php
                    if( !empty( $gaming_mag_slider_cat_slugs ) ) {
                        $checked_cats = array();
                        foreach( $gaming_mag_slider_cat_slugs as $cat_key => $cat_value ){
                            $checked_cats[] = $cat_key;
                        }
                        $get_checked_cat_slugs = implode( ",", $checked_cats );
                        $gaming_mag_post_count = apply_filters( 'gaming_mag_slider_posts_count', 5 );
                        $gaming_mag_slider_args = array(
                            'post_type'      => 'post',
                            'category_name'  => wp_kses_post( $get_checked_cat_slugs ),
                            'posts_per_page' => absint( $gaming_mag_post_count )
                        );
                        $gaming_mag_slider_query = new WP_Query( $gaming_mag_slider_args );
                        if( $gaming_mag_slider_query->have_posts() ) {
                            echo '<ul class="cvSlider cS-hidden">';
                            while( $gaming_mag_slider_query->have_posts() ) {
                                $gaming_mag_slider_query->the_post();
                                if( has_post_thumbnail() ) {
                    ?>
                                    <li>
                                        <div class="cv-single-slide-wrap">
                                            
                                            <div class="cv-slide-thumb">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail( 'gaming-mag-slider-full' ); ?>
                                                </a>
                                            </div><!-- .cv-slide-thumb -->

                                            <div class="cv-slide-content-wrap">
                                                <?php news_vibrant_post_categories_list(); ?>
                                                <h3 class="post-title large-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                <div class="cv-post-meta">
                                                    <?php
                                                        news_vibrant_post_author();
                                                        news_vibrant_post_date();
                                                    ?>
                                                </div>
                                            </div> <!-- cv-slide-content-wrap -->

                                        </div><!-- .single-slide-wrap -->
                                    </li>
                    <?php
                                }
                            }
                            echo '</ul>';
                        }
                        wp_reset_postdata();
                    }
                ?>
            </div><!-- .slider-posts -->
            
        </div><!--- .cv-slider-wrapper -->
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
     * @uses    news_vibrant_widgets_updated_field_value()     defined in cv-widget-fields.php
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
     * @uses    news_vibrant_widgets_show_widget_field()       defined in cv-widget-fields.php
     */
    public function form( $instance ) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            // Make array elements available as variables
            extract( $widget_field );
            $news_vibrant_widgets_field_value = !empty( $instance[$news_vibrant_widgets_name] ) ? wp_kses_post( $instance[$news_vibrant_widgets_name] ) : '';
            news_vibrant_widgets_show_widget_field( $this, $widget_field, $news_vibrant_widgets_field_value );
        }
    }
}