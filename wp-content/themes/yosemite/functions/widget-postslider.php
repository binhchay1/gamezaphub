<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Post Slider
	Version: 2.0

-----------------------------------------------------------------------------------*/


class mts_post_slider_widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'mts_post_slider_widget',
			__('MyThemeShop: Post Slider','mythemeshop'),
			array( 'description' => __( 'Display posts from multiple categories in an animated slider.','mythemeshop' ) )
		);
	}

	public function form( $instance ) {
		$defaults = array(
			'title' => __( 'Featured Posts','mythemeshop' ),
			'cat' => array(),
			'slides_num' => 3,
			'show_title' => 0,
			'title_limit' => 40
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		extract($instance);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','mythemeshop' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e( 'Category:','mythemeshop' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>[]" class="widefat" multiple="multiple">
			<?php
				$cat_list = get_categories();
				foreach ( $cat_list as $category ) {
					$selected = (is_array($cat) && in_array($category->term_id, $cat))?' selected="selected"':'';
					echo '<option value="'.$category->term_id.'"'.$selected.'>'.$category->name.'</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'slides_num' ); ?>"><?php _e( 'Number of Posts to show','mythemeshop' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'slides_num' ); ?>" name="<?php echo $this->get_field_name( 'slides_num' ); ?>" type="number" min="1" step="1" value="<?php echo $slides_num; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_title'); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php checked( 1, $show_title, true ); ?> />
				<?php _e( 'Show Title', 'mythemeshop'); ?>
			</label>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cat'] = $new_instance['cat'];
		$instance['slides_num'] = intval( $new_instance['slides_num'] );
		$instance['show_title'] = intval( $new_instance['show_title'] );

		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$cat = $instance['cat'];
		$slides_num = (int) $instance['slides_num'];
		$show_title = (int) $instance['show_title'];

		echo $before_widget;
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
		echo self::get_cat_posts( $cat, $slides_num, $show_title );
		echo $after_widget;
	}

	public function get_cat_posts( $cat, $slides_num, $show_title ) {
		// Enqueue flexslider needed for
		// the widget's output
		wp_enqueue_script('owl-carousel');
		wp_enqueue_style('owl-carousel');

		if (is_array($cat)) {
			$cats = implode(',',$cat);
		} else {
			$cats = '';
		}

		$posts = new WP_Query(
			"cat=".$cats."&orderby=date&order=DESC&posts_per_page=".$slides_num
		);
		?>
			<div class="slider-widget-container">
				<div class="slider-container loading">
					<ul class="widget-slider slides">
						<?php
						while ( $posts->have_posts()) : $posts->the_post();
							if (has_post_thumbnail()) {
								$post_id = get_post_thumbnail_id();
								$widget_image = wp_get_attachment_image_src( $post_id, 'full' );
								$widget_image = $widget_image[0];
								$thumbnail = bfi_thumb( $widget_image, array( 'width' => '305', 'height' => '200', 'crop' => true ) );
							} else {
								$thumbnail = get_template_directory_uri().'/images/nothumb-305x200.png';
							}
						?>
						<li>
							<a href="<?php the_permalink(); ?>">
								<?php echo '<img src="'.$thumbnail.'" class="wp-post-image">'; ?>
							</a>
							<?php if ( $show_title ) { ?>
							<div class="flex-caption">
								<h2 class="slidertitle"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
							</div>
							<?php } ?>
						</li>
						<?php endwhile; wp_reset_query(); ?>
					</ul>
				</div>
			</div><!-- slider-widget-container -->
		<?php
	}
}
function mts_init_post_slider_widget(){
	register_widget( "mts_post_slider_widget" );
}
add_action( 'widgets_init', 'mts_init_post_slider_widget' );
