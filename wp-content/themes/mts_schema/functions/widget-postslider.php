<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Post Slider
	Version: 2.0

-----------------------------------------------------------------------------------*/

if(!class_exists('mts_post_slider_widget')){
	class mts_post_slider_widget extends WP_Widget {

		public function __construct() {
			parent::__construct(
				'mts_post_slider_widget',
				sprintf( __('%sPost Slider', 'schema' ), MTS_THEME_WHITE_LABEL ? '' : 'MTS ' ),
				array( 'description' => __( 'Display posts from multiple categories in an animated slider.', 'schema' ) )
			);
		}

		public function form( $instance ) {
			$defaults = array(
				'title' => __( 'Featured Posts', 'schema' ),
				'cat' => array(),
				'slides_num' => 3,
				'show_title' => 0,
				'slider_nav' => 'bullets',
				'title_limit' => 40
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'schema' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e( 'Category:', 'schema' ); ?></label>
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
				<label for="<?php echo $this->get_field_id( 'slides_num' ); ?>"><?php _e( 'Number of Posts to show', 'schema' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'slides_num' ); ?>" name="<?php echo $this->get_field_name( 'slides_num' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $slides_num ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'slider_nav' ); ?>"><?php _e( 'Navigaton type:', 'schema' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'slider_nav' ); ?>" name="<?php echo $this->get_field_name( 'slider_nav' ); ?>" class="widefat">
					<option value="bullets"<?php selected( $instance['slider_nav'], 'bullets' ); ?>><?php _e( 'Bulleted', 'schema' ); ?></option>
					<option value="arrows"<?php selected( $instance['slider_nav'], 'arrows' ); ?>><?php _e( 'Arrows', 'schema' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('show_title'); ?>">
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php checked( 1, $show_title, true ); ?> />
					<?php _e( 'Show Title', 'schema' ); ?>
				</label>
			</p>

			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['cat'] = $new_instance['cat'];
			$instance['slides_num'] = intval( $new_instance['slides_num'] );
			$instance['slider_nav'] = $new_instance['slider_nav'];
			$instance['show_title'] = intval( $new_instance['show_title'] );

			return $instance;
		}

		public function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
			$cat = $instance['cat'];
			$slides_num = (int) $instance['slides_num'];
			$slider_nav = $instance['slider_nav'];
			$show_title = (int) $instance['show_title'];

			echo $before_widget;
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
			echo self::get_cat_posts( $cat, $slides_num, $slider_nav, $show_title );
			echo $after_widget;
		}

		public function get_cat_posts( $cat, $slides_num, $slider_nav, $show_title ) {
			// Enqueue owl carousel needed for
			// the widget's output
			wp_enqueue_script('owl-carousel');
			wp_enqueue_style('owl-carousel');

			if (is_array($cat)) {
				$cats = implode(',',$cat);
			} else {
				$cats = '';
			}

			$slider_args = apply_filters(
				'mts_widget_postslider_query_args',
				array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'cat'            => $cats,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'posts_per_page' => $slides_num,
				)
			);

			$posts = new WP_Query( $slider_args );
			?>
				<div class="slider-widget-container">
					<div class="slider-container loading">
						<div class="widget-slider widget-slider-<?php echo $slider_nav; ?>">
							<?php while ( $posts->have_posts()) : $posts->the_post(); ?>
							<div class="slide">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>">
									<?php
									if ( has_post_thumbnail() ) {
										$attr = array(
										'title'   => esc_attr( get_the_title() ),
										'alt'     => esc_attr( get_the_title() ),
										'loading' => 'lazy'
									);
									the_post_thumbnail( 'schema-widgetfull', $attr );
									} else {
										?>
										<img class="wp-post-image" src="<?php echo get_template_directory_uri() . '/images/nothumb-schema-widgetfull.png'; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
										<?php
									}
									if ( $show_title ) { ?>
										<div class="slide-caption">
											<h2 class="slide-title"><?php the_title(); ?></h2>
										</div>
									<?php } ?>
								</a>
							</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				</div><!-- slider-widget-container -->
			<?php
		}
	}
}
// Register widget
add_action( 'widgets_init', 'register_mts_post_slider_widget' );
function register_mts_post_slider_widget() {
	register_widget( 'mts_post_slider_widget' );
}
