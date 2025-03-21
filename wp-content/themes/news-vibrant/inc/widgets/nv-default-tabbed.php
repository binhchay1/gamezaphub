<?php
/**
 * CV: Default Tabbed
 *
 * Widget to display latest posts and comment in tabbed layout.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

class News_Vibrant_Default_Tabbed extends WP_widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'news_vibrant_default_tabbed',
            'description' => __( 'A widget shows recent posts and comment in tabbed layout.', 'news-vibrant' )
        );
        parent::__construct( 'news_vibrant_default_tabbed', __( 'CV: Default Tabbed', 'news-vibrant' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        
        $fields = array(

            'latest_tab_title' => array(
                'news_vibrant_widgets_name'         => 'latest_tab_title',
                'news_vibrant_widgets_title'        => __( 'Latest Tab title', 'news-vibrant' ),
                'news_vibrant_widgets_default'      => __( 'Latest', 'news-vibrant' ),
                'news_vibrant_widgets_field_type'   => 'text'
            ),

            'comments_tab_title' => array(
                'news_vibrant_widgets_name'         => 'comments_tab_title',
                'news_vibrant_widgets_title'        => __( 'Comments Tab title', 'news-vibrant' ),
                'news_vibrant_widgets_default'      => __( 'Comments', 'news-vibrant' ),
                'news_vibrant_widgets_field_type'   => 'text'
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

        $news_vibrant_latest_title    = empty( $instance['latest_tab_title'] ) ? __( 'Latest', 'news-vibrant' ) : $instance['latest_tab_title'];
        $news_vibrant_comments_title  = empty( $instance['comments_tab_title'] ) ? __( 'Comments', 'news-vibrant' ) : $instance['comments_tab_title'];

        echo $before_widget;
    ?>
            <div class="nv-default-tabbed-wrapper nv-clearfix" id="nv-tabbed-widget">
                
                <ul class="widget-tabs nv-clearfix" id="nv-widget-tab">
                    <li><a href="#latest"><?php echo esc_html( $news_vibrant_latest_title ); ?></a></li>
                    <li><a href="#comments"><?php echo esc_html( $news_vibrant_comments_title ); ?></a></li>
                </ul><!-- .widget-tabs -->

                <div id="latest" class="nv-tabbed-section nv-clearfix">
                    <?php
                        $news_vibrant_post_count = apply_filters( 'news_vibrant_latest_tabbed_posts_count', 5 );
                        $latest_args = array(
                            'posts_per_page' => $news_vibrant_post_count
                        );
                        $latest_query = new WP_Query( $latest_args );
                        if ( $latest_query->have_posts() ) {
                            while ( $latest_query->have_posts() ) {
                                $latest_query->the_post();
                    ?>
                                <div class="nv-single-post nv-clearfix">
                                    <div class="nv-post-thumb">
                                        <a href="<?php the_permalink(); ?>"> <?php the_post_thumbnail( 'news-vibrant-block-thumb' ); ?> </a>
                                    </div><!-- .nv-post-thumb -->
                                    <div class="nv-post-content">
                                        <h3 class="nv-post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <div class="nv-post-meta">
                                            <?php
                                                news_vibrant_post_date();
                                                news_vibrant_post_comment();
                                            ?>
                                        </div>
                                    </div><!-- .nv-post-content -->
                                </div><!-- .nv-single-post -->
                    <?php
                            }
                        }
                        wp_reset_postdata();
                    ?>
                </div><!-- #latest -->

                <div id="comments" class="nv-tabbed-section nv-clearfix">
                    <ul>
                        <?php
                            $news_vibrant_comments_count = apply_filters( 'news_vibrant_comment_tabbed_posts_count', 5 );
                            $news_vibrant_tabbed_comments = get_comments( array( 'number' => $news_vibrant_comments_count ) );
                            foreach( $news_vibrant_tabbed_comments as $comment  ) {
                        ?>
                                <li class="nv-single-comment nv-clearfix">
                                    <?php
                                        $title = get_the_title( $comment->comment_post_ID );
                                        echo '<div class="nv-comment-avatar">'. get_avatar( $comment, '55' ) .'</div>';
                                    ?>
                                    <div class="nv-comment-desc-wrap">
                                        <strong><?php echo esc_html( $comment->comment_author ); ?></strong>
                                        <?php esc_html_e( '&nbsp;commented on', 'news-vibrant' ); ?> 
                                        <a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) ); ?>" rel="external nofollow" title="<?php echo esc_attr( $title ); ?>"> <?php echo esc_html( $title ); ?></a> : <?php echo esc_html( wp_html_excerpt( $comment->comment_content, 50 ) ); ?>
                                    </div><!-- .nv-comment-desc-wrap -->
                                </li>
                        <?php
                            }
                        ?>
                    </ul>
                </div><!-- #comments -->

            </div><!-- .nv-default-tabbed-wrapper -->
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
            $news_vibrant_widgets_field_value = !empty( $instance[$news_vibrant_widgets_name] ) ? wp_kses_post( $instance[$news_vibrant_widgets_name] ) : '';
            news_vibrant_widgets_show_widget_field( $this, $widget_field, $news_vibrant_widgets_field_value );
        }
    }
}