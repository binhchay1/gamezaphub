<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Related Posts
	Version: 2.0.1

-----------------------------------------------------------------------------------*/

class mts_related_posts_widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'mts_related_posts_widget',
			__('MyThemeShop: Related Posts','mythemeshop'),
			array( 'description' => __( 'Display the related posts from current post\'s categories. Will appear on single posts only.','mythemeshop' ) )
		);
	}

	public function form( $instance ) {
		$defaults = array(
			'title_length' => 5,
			'comment_num' => 0,
			'date' => 1,
			'show_thumb6' => 1,
			'box_layout' => 'horizontal-small',
			'show_excerpt' => 0,
			'excerpt_length' => 10
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Related Posts','mythemeshop' );
		$title_length = isset( $instance[ 'title_length' ] ) ? intval( $instance[ 'title_length' ] ) : 7;
		$qty = isset( $instance[ 'qty' ] ) ? esc_attr( $instance[ 'qty' ] ) : 5;
		$comment_num = isset( $instance[ 'comment_num' ] ) ? esc_attr( $instance[ 'comment_num' ] ) : 1;
		$date = isset( $instance[ 'date' ] ) ? esc_attr( $instance[ 'date' ] ) : 1;
		$show_thumb6 = isset( $instance[ 'show_thumb6' ] ) ? esc_attr( $instance[ 'show_thumb6' ] ) : 1;
		$box_layout = $instance['box_layout'];
		$show_excerpt = isset( $instance[ 'show_excerpt' ] ) ? esc_attr( $instance[ 'show_excerpt' ] ) : 1;
		$excerpt_length = isset( $instance[ 'excerpt_length' ] ) ? intval( $instance[ 'excerpt_length' ] ) : 10;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','mythemeshop' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'qty' ); ?>"><?php _e( 'Number of Posts to show','mythemeshop' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'qty' ); ?>" name="<?php echo $this->get_field_name( 'qty' ); ?>" type="number" min="1" step="1" value="<?php echo $qty; ?>" />
		</p>

		<p>
	       <label for="<?php echo $this->get_field_id( 'title_length' ); ?>"><?php _e( 'Title Length:', 'mythemeshop' ); ?>
	       <input id="<?php echo $this->get_field_id( 'title_length' ); ?>" name="<?php echo $this->get_field_name( 'title_length' ); ?>" type="number" min="1" step="1" value="<?php echo $title_length; ?>" />
	       </label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id("show_thumb6"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_thumb6"); ?>" name="<?php echo $this->get_field_name("show_thumb6"); ?>" value="1" <?php if (isset($instance['show_thumb6'])) { checked( 1, $instance['show_thumb6'], true ); } ?> />
				<?php _e( 'Show Thumbnails', 'mythemeshop'); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('box_layout'); ?>"><?php _e('Posts layout:', 'mythemeshop'); ?></label>
			<select id="<?php echo $this->get_field_id('box_layout'); ?>" name="<?php echo $this->get_field_name('box_layout'); ?>">
				<option value="horizontal-small" <?php selected($box_layout, 'horizontal-small', true); ?>><?php _e('Horizontal', 'mythemeshop'); ?></option>
				<option value="vertical-small" <?php selected($box_layout, 'vertical-small', true); ?>><?php _e('Vertical', 'mythemeshop'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id("date"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>" value="1" <?php checked( 1, $instance['date'], true ); ?> />
				<?php _e( 'Show post date', 'mythemeshop'); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id("comment_num"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>" value="1" <?php checked( 1, $instance['comment_num'], true ); ?> />
				<?php _e( 'Show number of comments', 'mythemeshop'); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id("show_excerpt"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>" value="1" <?php checked( 1, $instance['show_excerpt'], true ); ?> />
				<?php _e( 'Show excerpt', 'mythemeshop'); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e( 'Excerpt Length:', 'mythemeshop' ); ?>
				<input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" min="1" step="1" value="<?php echo $excerpt_length; ?>" />
			</label>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['qty'] = intval( $new_instance['qty'] );
		$instance['title_length'] = intval( $new_instance['title_length'] );
		$instance['comment_num'] = intval( $new_instance['comment_num'] );
		$instance['date'] = intval( $new_instance['date'] );
		$instance['show_thumb6'] = intval( $new_instance['show_thumb6'] );
		$instance['box_layout'] = $new_instance['box_layout'];
		$instance['show_excerpt'] = intval( $new_instance['show_excerpt'] );
		$instance['excerpt_length'] = intval( $new_instance['excerpt_length'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$title_length = $instance['title_length'];
		$comment_num = $instance['comment_num'];
		$date = $instance['date'];
		$qty = (int) $instance['qty'];
		$show_thumb6 = (int) $instance['show_thumb6'];
		$box_layout = isset($instance['box_layout']) ? $instance['box_layout'] : 'horizontal-small';
		$show_excerpt = $instance['show_excerpt'];
		$excerpt_length = $instance['excerpt_length'];

		if(is_singular()){
			$before_widget = preg_replace('/class="([^"]+)"/i', 'class="$1 '.(isset($instance['box_layout']) ? $instance['box_layout'] : 'horizontal-small').'"', $before_widget); // Add horizontal/vertical class to widget
			echo $before_widget;
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
			echo self::get_cat_posts( $qty, $title_length, $comment_num, $date, $show_thumb6, $box_layout, $show_excerpt, $excerpt_length );
			echo $after_widget;
		}
	}

	public function get_cat_posts( $qty, $title_length, $comment_num, $date, $show_thumb6, $box_layout, $show_excerpt, $excerpt_length ) {

	$no_image = ( $show_thumb6 ) ? '' : ' no-thumb';

		if ( 'horizontal-small' === $box_layout ) {
			$thumbnail_w   = 70;
			$thumbnail_h   = 70;
			$open_li_item  = '<li class="post-box horizontal-small horizontal-container'.$no_image.'"><div class="horizontal-container-inner">';
			$close_li_item = '</div></li>';
		} else {
			$thumbnail_w   = 265;
			$thumbnail_h   = 174;
			$open_li_item  = '<li class="post-box vertical-small'.$no_image.'">';
			$close_li_item = '</li>';
		}

	global $post;
	$thePostID = $post->ID;
	$cats = get_the_category($thePostID);
	$cat_ids = array();
	foreach($cats as $individual_cat)
		$cat_ids[] = $individual_cat->cat_ID;
		$posts = new WP_Query(
			array(
				'category__in' => $cat_ids,
				'post__not_in' => array($thePostID),
				'posts_per_page' => $qty,
				'orderby' => 'rand'
				));

		echo '<ul class="related-posts-widget">';
		while ( $posts->have_posts() ) { $posts->the_post(); ?>
			<?php echo $open_li_item; ?>
				<?php if ( $show_thumb6 == 1 ) : ?>
				<div class="post-img">
					<a href="<?php the_permalink()?>" title="<?php the_title(); ?>">
						<?php if (has_post_thumbnail()) {
							$post_id = get_post_thumbnail_id();
							$widget_image = wp_get_attachment_image_src( $post_id, 'full' );
							$widget_image = $widget_image[0];
							$thumbnail = bfi_thumb( $widget_image, array( 'width' => $thumbnail_w, 'height' => $thumbnail_h, 'crop' => true ) );
						} else {
							$thumbnail = get_template_directory_uri().'/images/nothumb-'.$thumbnail_w.'x'.$thumbnail_h.'.png';
						}
						echo '<img src="'.$thumbnail.'" class="wp-post-image">' ?>
					</a>
				</div>
				<?php endif; ?>
				<div class="post-data">
					<div class="post-data-container">
						<div class="post-title">
							<a href="<?php the_permalink()?>" title="<?php the_title(); ?>"><?php echo mts_truncate( get_the_title(), $title_length, 'words' ); ?></a>
						</div>
						<?php if ( $date == 1 || $comment_num == 1 ) : ?>
							<div class="post-info">
								<?php if ( $date == 1 ) : ?>
									<span class="thetime updated"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __(' ago','mythemeshop'); ?></span>
								<?php endif; ?>
								<?php if ( $comment_num == 1 ) : ?>
									<span class="thecomment"><i class="fa fa-comments"></i> <?php echo comments_number('0','1','%');?></span>
								<?php endif; ?>
							</div> <!--end .post-info-->
						<?php endif; ?>
						<?php if ( $show_excerpt == 1 ) : ?>
							<div class="post-excerpt">
								<?php echo mts_excerpt($excerpt_length); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php echo $close_li_item; ?>
		<?php }
		echo '</ul>'."\r\n";
	}
}
function mts_init_related_posts_widget(){
	register_widget( "mts_related_posts_widget" );
}
add_action( 'widgets_init', 'mts_init_related_posts_widget' );
