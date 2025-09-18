<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Category Posts
	Version: 2.0.1

-----------------------------------------------------------------------------------*/
if(!class_exists('single_category_posts_widget')){
	class single_category_posts_widget extends WP_Widget {

		public function __construct() {
			parent::__construct(
		 		'single_category_posts_widget',
				sprintf( __('%sCategory Posts', 'schema' ), MTS_THEME_WHITE_LABEL ? '' : 'MTS ' ),
				array( 'description' => __( 'Display the most recent posts from a single category', 'schema' ) )
			);
		}

	 	public function form( $instance ) {
			$defaults = array(
				'title_length' => 7,
				'comment_num' => 1,
				'date' => 1,
				'show_thumb1' => 1,
				'box_layout' => 'horizontal-small',
				'show_excerpt' => 0,
				'excerpt_length' => 10
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Featured Category', 'schema' );
			$title_length = isset( $instance[ 'title_length' ] ) ? intval( $instance[ 'title_length' ] ) : 7;
			$cat = isset( $instance[ 'cat' ] ) ? intval( $instance[ 'cat' ] ) : 0;
			$qty = isset( $instance[ 'qty' ] ) ? intval( $instance[ 'qty' ] ) : 5;
			$comment_num = isset( $instance[ 'comment_num' ] ) ? intval( $instance[ 'comment_num' ] ) : 1;
			$date = isset( $instance[ 'date' ] ) ? intval( $instance[ 'date' ] ) : 1;
			$show_thumb1 = isset( $instance[ 'show_thumb1' ] ) ? intval( $instance[ 'show_thumb1' ] ) : 1;
			$box_layout = $instance['box_layout'];
			$show_excerpt = isset( $instance[ 'show_excerpt' ] ) ? esc_attr( $instance[ 'show_excerpt' ] ) : 1;
			$excerpt_length = isset( $instance[ 'excerpt_length' ] ) ? intval( $instance[ 'excerpt_length' ] ) : 10;
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'schema' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e( 'Category:', 'schema' ); ?></label>
				<?php wp_dropdown_categories( Array(
							'orderby'            => 'ID',
							'order'              => 'ASC',
							'show_count'         => 1,
							'hide_empty'         => 1,
							'hide_if_empty'      => true,
							'echo'               => 1,
							'selected'           => $cat,
							'hierarchical'       => 1,
							'name'               => $this->get_field_name( 'cat' ),
							'id'                 => $this->get_field_id( 'cat' ),
							'taxonomy'           => 'category',
						) ); ?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'qty' ); ?>"><?php _e( 'Number of Posts to show', 'schema' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'qty' ); ?>" name="<?php echo $this->get_field_name( 'qty' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $qty ); ?>" />
			</p>

			<p>
		       <label for="<?php echo $this->get_field_id( 'title_length' ); ?>"><?php _e( 'Title Length:', 'schema' ); ?>
		       <input id="<?php echo $this->get_field_id( 'title_length' ); ?>" name="<?php echo $this->get_field_name( 'title_length' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $title_length ); ?>" />
		       </label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id("show_thumb1"); ?>">
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_thumb1"); ?>" name="<?php echo $this->get_field_name("show_thumb1"); ?>" value="1" <?php if (isset($instance['show_thumb1'])) { checked( 1, $instance['show_thumb1'], true ); } ?> />
					<?php _e( 'Show Thumbnails', 'schema' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('box_layout'); ?>"><?php _e('Posts layout:', 'schema' ); ?></label>
				<select id="<?php echo $this->get_field_id('box_layout'); ?>" name="<?php echo $this->get_field_name('box_layout'); ?>">
					<option value="horizontal-small" <?php selected($box_layout, 'horizontal-small', true); ?>><?php _e('Horizontal', 'schema' ); ?></option>
					<option value="vertical-small" <?php selected($box_layout, 'vertical-small', true); ?>><?php _e('Vertical', 'schema' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id("date"); ?>">
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>" value="1" <?php checked( 1, $instance['date'], true ); ?> />
					<?php _e( 'Show post date', 'schema' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id("comment_num"); ?>">
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>" value="1" <?php checked( 1, $instance['comment_num'], true ); ?> />
					<?php _e( 'Show number of comments', 'schema' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id("show_excerpt"); ?>">
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>" value="1" <?php checked( 1, $instance['show_excerpt'], true ); ?> />
					<?php _e( 'Show excerpt', 'schema' ); ?>
				</label>
			</p>

			<p>
		       <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e( 'Excerpt Length:', 'schema' ); ?>
		       <input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $excerpt_length ); ?>" />
		       </label>
			</p>

			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['cat'] = intval( $new_instance['cat'] );
			$instance['title_length'] = intval( $new_instance['title_length'] );
			$instance['qty'] = intval( $new_instance['qty'] );
			$instance['comment_num'] = intval( $new_instance['comment_num'] );
			$instance['date'] = intval( $new_instance['date'] );
			$instance['show_thumb1'] = intval( $new_instance['show_thumb1'] );
			$instance['box_layout'] = $new_instance['box_layout'];
			$instance['show_excerpt'] = intval( $new_instance['show_excerpt'] );
			$instance['excerpt_length'] = intval( $new_instance['excerpt_length'] );
			return $instance;
		}

		public function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
			$cat = $instance['cat'];
			$title_length = $instance['title_length'];
			$comment_num = $instance['comment_num'];
			$date = $instance['date'];
			$qty = (int) $instance['qty'];
			$show_thumb1 = (int) $instance['show_thumb1'];
			$box_layout = isset($instance['box_layout']) ? $instance['box_layout'] : 'horizontal-small';
			$show_excerpt = $instance['show_excerpt'];
			$excerpt_length = $instance['excerpt_length'];

			$before_widget = preg_replace('/class="([^"]+)"/i', 'class="$1 '.(isset($instance['box_layout']) ? $instance['box_layout'] : 'horizontal-small').'"', $before_widget); // Add horizontal/vertical class to widget
			echo $before_widget;
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
			echo self::get_cat_posts( $cat, $title_length, $qty, $comment_num, $date, $show_thumb1, $box_layout, $show_excerpt, $excerpt_length );
			echo $after_widget;
		}

		public function get_cat_posts( $cat, $title_length, $qty, $comment_num, $date, $show_thumb1, $box_layout, $show_excerpt, $excerpt_length ) {

			$no_image = ( $show_thumb1 ) ? '' : ' no-thumb';

			if ( 'horizontal-small' === $box_layout ) {
				$thumbnail     = 'widgetthumb';
				$open_li_item  = '<li class="post-box horizontal-small horizontal-container'.$no_image.'"><div class="horizontal-container-inner">';
				$close_li_item = '</div></li>';
			} else {
				$thumbnail     = 'widgetfull';
				$open_li_item  = '<li class="post-box vertical-small'.$no_image.'">';
				$close_li_item = '</li>';
			}

			$posts = new WP_Query(
				"cat=".$cat."&orderby=date&order=DESC&posts_per_page=".$qty
			);

			echo '<ul class="category-posts">';

			while ( $posts->have_posts() ) { $posts->the_post(); ?>
				<?php echo $open_li_item; ?>
					<?php if ( $show_thumb1 == 1 ) : ?>
					<div class="post-img">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								$attr = array(
									'title'   => esc_attr( get_the_title() ),
									'alt'     => esc_attr( get_the_title() ),
									'loading' => 'lazy'
								);
								the_post_thumbnail( 'schema-' . $thumbnail, $attr );
							} else {
								?>
								<img class="wp-post-image" src="<?php echo get_template_directory_uri() . '/images/nothumb-schema-' . $thumbnail . '.png'; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
								<?php
							}
							?>
						</a>
					</div>
					<?php endif; ?>
					<div class="post-data">
						<div class="post-data-container">
							<div class="post-title">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php echo esc_html( mts_truncate( get_the_title(), $title_length, 'words' ) ); ?></a>
							</div>
							<?php if ( $date == 1 || $comment_num == 1 ) : ?>
							<div class="post-info">
								<?php if ( $date == 1 ) : ?>
								<span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
								<?php endif; ?>
								<?php if ( $comment_num == 1 ) : ?>
									<span class="thecomment"><?php comments_number( esc_html__( 'No Comments', 'schema' ), esc_html__( '1 Comment', 'schema' ), esc_html__( '% Comments', 'schema' ) ); ?></span>
								<?php endif; ?>
							</div><!--.post-info-->
							<?php endif; ?>
							<?php if ( $show_excerpt == 1 ) : ?>
							<div class="post-excerpt">
								<?php echo mts_excerpt( $excerpt_length ); ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				<?php echo $close_li_item; ?>
			<?php }
			wp_reset_postdata();
			echo '</ul>'."\r\n";
		}

	}
}
// Register widget
add_action( 'widgets_init', 'register_single_category_posts_widget' );
function register_single_category_posts_widget() {
	register_widget( 'single_category_posts_widget' );
}
