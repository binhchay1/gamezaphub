<?php
/**
 * CV: Block Posts
 *
 * Widget show the block posts from selected category in different layouts.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

class News_Vibrant_Block_Posts extends WP_widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'news_vibrant_block_posts nv-clearfix',
            'description' => __( 'Displays block posts from selected category in different layouts.', 'news-vibrant' )
        );
        parent::__construct( 'news_vibrant_block_posts', __( 'CV: Block Posts', 'news-vibrant' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        
        $fields = array(

            'block_title' => array(
                'news_vibrant_widgets_name'         => 'block_title',
                'news_vibrant_widgets_title'        => __( 'Block title', 'news-vibrant' ),
                'news_vibrant_widgets_description'  => __( 'Enter your block title. (Optional - Leave blank to hide title.)', 'news-vibrant' ),
                'news_vibrant_widgets_field_type'   => 'text'
            ),

            'block_cat_slug' => array(
                'news_vibrant_widgets_name'         => 'block_cat_slug',
                'news_vibrant_widgets_title'        => __( 'Block Category', 'news-vibrant' ),
                'news_vibrant_widgets_default'      => '',
                'news_vibrant_widgets_field_type'   => 'category_dropdown'
            ),

            'block_layout' => array(
                'news_vibrant_widgets_name'         => 'block_layout',
                'news_vibrant_widgets_title'        => __( 'Block Layouts', 'news-vibrant' ),
                'news_vibrant_widgets_default'      => 'layout1',
                'news_vibrant_widgets_field_type'   => 'selector',
                'news_vibrant_widgets_field_options' => array(
                    'layout1' => esc_url( get_template_directory_uri() . '/assets/images/block-layout1.png' ),
                    'layout2' => esc_url( get_template_directory_uri() . '/assets/images/block-layout2.png' ),
                    'layout3' => esc_url( get_template_directory_uri() . '/assets/images/block-layout3.png' ),
                    'layout4' => esc_url( get_template_directory_uri() . '/assets/images/block-grid-alternate.png' )
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

        $news_vibrant_block_title    = empty( $instance['block_title'] ) ? '' : $instance['block_title'];
        $news_vibrant_block_cat_slug = empty( $instance['block_cat_slug'] ) ? '' : $instance['block_cat_slug'];
        $news_vibrant_block_layout   = empty( $instance['block_layout'] ) ? 'layout1' : $instance['block_layout'];

        $widget_title_args = array(
            'title'    => $news_vibrant_block_title,
            'cat_slug' => $news_vibrant_block_cat_slug
        );

        echo $before_widget;
    ?>
        <div class="nv-block-wrapper block-posts nv-clearfix <?php echo esc_attr( $news_vibrant_block_layout ); ?>">
            <?php 
                if ( !empty( $news_vibrant_block_title ) ) {
                    do_action( 'news_vibrant_widget_title', $widget_title_args );
                }
            ?>
            <div class="nv-block-posts-wrapper">
            	<?php
            		switch ( $news_vibrant_block_layout ) {
            			case 'layout2':
            				news_vibrant_block_second_layout_section( $news_vibrant_block_cat_slug );
            				break;

            			case 'layout3':
            				news_vibrant_block_box_layout_section( $news_vibrant_block_cat_slug );
            				break;

            			case 'layout4':
            				news_vibrant_block_alternate_grid_section( $news_vibrant_block_cat_slug );
            				break;
            			
            			default:
            				news_vibrant_block_default_layout_section( $news_vibrant_block_cat_slug );
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
            $news_vibrant_widgets_field_value = !empty( $instance[$news_vibrant_widgets_name] ) ? wp_kses_post( $instance[$news_vibrant_widgets_name] ) : '';
            news_vibrant_widgets_show_widget_field( $this, $widget_field, $news_vibrant_widgets_field_value );
        }
    }
}