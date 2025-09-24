<?php add_action( 'widgets_init','blogarise_featured_latest_news'); 
function blogarise_featured_latest_news() 
{ 
	return   register_widget( 'blogarise_featured_latest_news' );
}

class blogarise_featured_latest_news extends WP_Widget {

	function __construct() {
		parent::__construct(
			'blogarise_featured_latest_news', //Base ID
			__('AR: Recent Post', 'blogarise'), //Name
			array( 'description' => __( 'Display your recent posts on your website', 'blogarise' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		
		$instance['title'] = (isset($instance['title'])?$instance['title']:'');
		$instance['number_of_posts'] = (isset($instance['number_of_posts'])?$instance['number_of_posts']:3);
		$instance['tumb_size'] = (isset($instance['tumb_size'])?$instance['tumb_size']:'');
		$instance['image_show']=(isset($instance['image_show'])?$instance['image_show']:true);
		
		echo $args['before_widget'];
		
		if($instance['title'])
	
		echo $args['before_title'] . $instance['title'] . $args['after_title'];
		 
		$loop = new WP_Query(array( 'post_type' => 'post','ignore_sticky_posts' => 1, 'showposts' => $instance['number_of_posts'] )); ?>
		<div class="bs-recent-blog-post">
		<?php	if( $loop->have_posts() ) : 
			while ( $loop->have_posts() ) : $loop->the_post();?>
			<div class="small-post">
				<div class="small-post-content">
					<h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
					<div class="bs-blog-meta">
						<?php blogarise_date_content(); ?>
					</div>
				</div>
				<?php if($instance['image_show']==true): if(has_post_thumbnail()):?>
				<div class="img-small-post back-img hlgr right <?php echo esc_attr($instance['tumb_size']) ?>">
					<a href="<?php the_permalink(); ?>" class="post-thumbnail"> <?php $defalt_arg =array('class' => "img-fluid" ); the_post_thumbnail($instance['tumb_size'], $defalt_arg); ?>
					</a>
				</div>
				<?php endif; endif; ?>
				
			</div>
		<?php endwhile; 
			endif; ?>
		</div>	
		<?php
			
		echo $args['after_widget']; 	
	}

	public function form( $instance ) {

		$instance['title'] = (isset($instance['title'])?$instance['title']:'Recent Post');
		$instance['number_of_posts'] = (isset($instance['number_of_posts'])?$instance['number_of_posts']:'4');
		
		$instance['tumb_size'] = (isset($instance['tumb_size'])?$instance['tumb_size']:'');
		$instance['image_show']=(isset($instance['image_show'])?$instance['image_show']:true);
		?>
		
		<p>
		<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title','blogarise' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
		<label for="<?php echo esc_attr($this->get_field_id( 'number_of_posts' )); ?>"><?php esc_html_e( 'Number of posts to show','blogarise' ); ?></label> 
		<input size="3" maxlength="2"id="<?php echo esc_attr($this->get_field_id( 'number_of_posts' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number_of_posts' )); ?>" type="text" value="<?php echo esc_attr( $instance['number_of_posts'] ); ?>" />
		</p>	
		<p>
		<label for="<?php echo esc_attr($this->get_field_id( 'tumb_size' )); ?>"><?php esc_html_e( 'Featured post image size','blogarise' ); ?></label><br/> 
		<select id="<?php echo esc_attr($this->get_field_id( 'tumb_size' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tumb_size' )); ?>">
			<option value>-- <?php esc_html_e('Select post image size','blogarise'); ?> --</option>
			<option value="thumbnail" <?php echo esc_attr($instance['tumb_size']=='thumbnail'?'selected':''); ?>><?php esc_html_e('Thumbnail','blogarise'); ?></option>
			<option value="full" <?php echo esc_attr($instance['tumb_size']=='full'?'selected':''); ?>><?php esc_html_e('Full','blogarise'); ?></option>
		</select>
	</p>
	
	<p>
		<label for="<?php echo esc_attr($this->get_field_id( 'image_show' )); ?>"><?php esc_html_e( 'Enable feature image','blogarise' ); ?></label> 
		<input type="checkbox" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'image_show' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'image_show' )); ?>" <?php if($instance['image_show']==true) echo esc_attr('checked'); ?> >
	</p>
		
	<?php 
	}

	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
		$instance['number_of_posts'] = ( ! empty( $new_instance['number_of_posts'] ) ) ? strip_tags( $new_instance['number_of_posts'] ) : '';
		
		$instance['tumb_size'] = ( ! empty( $new_instance['tumb_size'] ) ) ? strip_tags( $new_instance['tumb_size'] ) : '';
		$instance['image_show'] = ( ! empty( $new_instance['image_show'] ) ) ? $new_instance['image_show'] : '';
		
		return $instance;
	}

} // class 