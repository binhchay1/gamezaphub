<?php
/**
 * CV: Featured Slider
 *
 * Widget to display posts from selected categories in featured slider ( slider + featured section )
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

class News_Vibrant_Featured_Slider extends WP_widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'news_vibrant_featured_slider',
            'description' => __( 'Displays posts from selected categories in slider with featured section.', 'news-vibrant' )
        );
        parent::__construct( 'news_vibrant_featured_slider', __( 'CV: Featured Slider', 'news-vibrant' ), $widget_ops );
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
                'news_vibrant_widgets_title'        => __( 'Slider Categories', 'news-vibrant' ),
                'news_vibrant_widgets_field_type'   => 'multicheckboxes',
                'news_vibrant_widgets_field_options' => $news_vibrant_categories_lists
            ),

            'featured_cat_slugs' => array(
                'news_vibrant_widgets_name'         => 'featured_cat_slugs',
                'news_vibrant_widgets_title'        => __( 'Featured Post Categories', 'news-vibrant' ),
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
        if ( empty( $instance ) ) {
            return ;
        }

        $news_vibrant_slider_cat_slugs    = empty( $instance['slider_cat_slugs'] ) ? '' : $instance['slider_cat_slugs'];
        $news_vibrant_featured_cat_slugs  = empty( $instance['featured_cat_slugs'] ) ? '' : $instance['featured_cat_slugs'];

        echo $before_widget;
    ?>
        <div class="nv-block-wrapper nv-clearfix">
            <div class="slider-posts">
                <?php
                    if ( !empty( $news_vibrant_slider_cat_slugs ) ) {
                        $checked_cats = array();
                        foreach ( $news_vibrant_slider_cat_slugs as $cat_key => $cat_value ) {
                            $checked_cats[] = $cat_key;
                        }
                        $get_checked_cat_slugs = implode( ",", $checked_cats );
                        $news_vibrant_post_count = apply_filters( 'news_vibrant_slider_posts_count', 4 );
                        $news_vibrant_slider_args = array(
                            'post_type'      => 'post',
                            'category_name'  => wp_kses_post( $get_checked_cat_slugs ),
                            'posts_per_page' => absint( $news_vibrant_post_count )
                        );
                        $news_vibrant_slider_query = new WP_Query( $news_vibrant_slider_args );
                        if ( $news_vibrant_slider_query->have_posts() ) {
                            echo '<ul class="cS-hidden nv-main-slider">';
                            while ( $news_vibrant_slider_query->have_posts() ) {
                                $news_vibrant_slider_query->the_post();
                                if ( has_post_thumbnail() ) {
                    ?>
                                    <li>
                                        <div class="nv-single-slide-wrap">
                                            <div class="nv-slide-thumb">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail( 'news-vibrant-slider-medium' ); ?>
                                                </a>
                                            </div><!-- .nv-slide-thumb -->
                                            <div class="nv-slide-content-wrap">
                                                <?php news_vibrant_post_categories_list(); ?>
                                                <h3 class="post-title large-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                <div class="nv-post-meta">
                                                    <?php
                                                        news_vibrant_post_author();
                                                        news_vibrant_post_date();
                                                    ?>
                                                </div>
                                            </div> <!-- nv-slide-content-wrap -->
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
            <div class="featured-posts">
                <div class="featured-posts-wrapper">
                <?php
                    if ( !empty( $news_vibrant_featured_cat_slugs ) ) {
                        $checked_cats = array();
                        foreach( $news_vibrant_featured_cat_slugs as $cat_key => $cat_value ){
                            $checked_cats[] = $cat_key;
                        }
                        $get_checked_cat_slugs = implode( ",", $checked_cats );
                        $news_vibrant_post_count = apply_filters( 'news_vibrant_slider_featured_posts_count', 4 );
                        $news_vibrant_slider_args = array(
                                'post_type'      => 'post',
                                'category_name'  => wp_kses_post( $get_checked_cat_slugs ),
                                'posts_per_page' => absint( $news_vibrant_post_count )
                            );
                        $news_vibrant_slider_query = new WP_Query( $news_vibrant_slider_args );
                        if ( $news_vibrant_slider_query->have_posts() ) {
                            while( $news_vibrant_slider_query->have_posts() ) {
                                $news_vibrant_slider_query->the_post();
                    ?>
                                <div class="nv-single-post-wrap nv-clearfix">
                                    <div class="nv-single-post">
                                        <div class="nv-post-thumb">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php
                                                    if ( has_post_thumbnail() ) {
                                                        the_post_thumbnail( 'news-vibrant-featured-medium' );
                                                    }
                                                ?>
                                            </a>
                                        </div><!-- .nv-post-thumb -->
                                        <div class="nv-post-content">
                                            <?php news_vibrant_post_categories_list(); ?>
                                            <h3 class="nv-post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <div class="nv-post-meta"> <?php news_vibrant_post_date(); ?> </div>
                                        </div><!-- .nv-post-content -->
                                    </div> <!-- nv-single-post -->
                                </div><!-- .nv-single-post-wrap -->
                                    
                    <?php
                            }
                        }
                        wp_reset_postdata();
                    }
                ?>
                </div>
            </div><!-- .featured-posts -->
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