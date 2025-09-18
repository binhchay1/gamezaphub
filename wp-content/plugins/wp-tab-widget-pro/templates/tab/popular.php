<?php
/*
 *
 * Available variables:
 *		$tab_name
 *		$icon
 *		$popular_query_by ( views/comments )
 *  	$num_post
 *  	$show_date
 *  	$show_comment
 *		$show_thumbnail
 *  	$thumb_size
 *  	$small_thumb_align
 *  	$custom_thumb_align
 *  	$custom_thumb_w
 *  	$custom_thumb_h
 *		$show_excerpt
 *  	$excerpt_length
 *  	$allow_pagination
 *  	$title_length
 *  	$days
 *  	$custom_days
 *  	$page
 *
 *  	$widget_number
 *  	$tab_key
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<ul class="wptp-list">
	<?php
	$year = '';
	if ( $days == 1 ) {
		$year = date('Y');
		$week = date('W'); 
		$month = false;
	} elseif( $days == 2 ) {
		$year = date('Y');
		$week = date('W') - 1; 
		$month = false;
	} elseif( $days == 3 ) {
		$year = date('Y');
		$week = false; 
		$month = date('m') - 1;
	}

	$pop_post_num = (empty($num_post) ? 5 : intval($num_post));
	if ($pop_post_num > 20 || $pop_post_num < 1) { // max 20 posts
		$pop_post_num = 5;
	}

	$pop_excerpt_length = intval($excerpt_length);
	if ($pop_excerpt_length > 50 || $pop_excerpt_length < 1) {
		$pop_excerpt_length = 10;
	}

	$pop_title_length = ! empty($title_length) ? $title_length : apply_filters( 'wpt_popular_title_length_default', '15' );

	$thumb_class = $class_prefix.'_thumbnail '. $class_prefix . '_thumb_' .$thumb_size;
	if ( 'small' === $thumb_size ) $thumb_class .= ' ' . $class_prefix . '_thumb_align_' .$small_thumb_align;
	if ( 'custom' === $thumb_size ) $thumb_class .= ' ' . $class_prefix . '_thumb_align_' .$custom_thumb_align;

	$query_args = array(
		'ignore_sticky_posts' => 1,
		'posts_per_page' => $pop_post_num,
		'post_status' => 'publish',
		'order' => 'desc',
		'paged' => $page
	);
	if ( 'views' === $popular_query_by ) {
		$query_args = array_merge( $query_args, array( 'meta_key' => '_wpt_view_count', 'orderby' => 'meta_value_num' ) );
	} else {
		$query_args = array_merge( $query_args, array( 'orderby' => 'comment_count' ) );
	}

	if ( !empty( $year ) ) {
		$query_args = array_merge( $query_args, array( 'year' => $year, 'monthnum' => $month, 'w' => $week ) );
	} else {
		if ( 'custom' === $days ) {
			$query_args = array_merge( $query_args, array(
				'date_query' => array(
					'after' => "$custom_days day ago",
				))
			);
		}
	}

	$query_args = apply_filters( 'wpt_popular_posts_query_args', $query_args, $widget_number, $tab_key );

	$popular = new WP_Query( $query_args );
	$last_page = $popular->max_num_pages;
	while ($popular->have_posts()) : $popular->the_post();
		if ( !has_post_thumbnail() && 'custom' === $thumb_size ) $show_thumbnail = '0';
		?>
		<li class="wptp-list-item">
			<?php if ( '1' === $show_thumbnail ) : ?>
				<div class="<?php echo $thumb_class;?>">
					<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>">
						<?php if('custom' === $thumb_size): ?>
							<?php //if(has_post_thumbnail()): ?>
								<?php the_post_thumbnail( array( $custom_thumb_w, $custom_thumb_h ), array( 'title' => '' ) ); ?>
							<?php //endif; ?>
						<?php else: ?>
							<?php if(has_post_thumbnail()): ?>
								<?php the_post_thumbnail('wp_review_'.$thumb_size, array('title' => '')); ?>
							<?php else: ?>
								<img src="<?php echo WPT_PLUGIN_URL.'img/'.$thumb_size.'thumb.png'; ?>" alt="<?php the_title(); ?>"  class="wp-post-image" />
							<?php endif; ?>
						<?php endif; ?>
					</a>
				</div>
			<?php endif; ?>
			<div class="entry-title"><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php echo wpt_post_title( $pop_title_length ); ?></a></div>
			<?php if ( $show_date == 1 || $show_comment == 1) : ?>
				<div class="<?php echo $class_prefix;?>-postmeta">
					<?php if ( '1' === $show_date ) : ?>
						<?php the_time('M j, Y'); ?>
					<?php endif; ?>
					<?php if ( '1' === $show_date && '1' === $show_comment ) : ?>
						&bull;
					<?php endif; ?>
					<?php if ( '1' === $show_comment ) : ?>
						<?php echo comments_number(__('No Comment','wp-tab-widget'), __('One Comment','wp-tab-widget'), '<span class="comments-number">%</span> '.__('Comments','wp-tab-widget'));?>
					<?php endif; ?>
				</div> <!--end .entry-meta-->
			<?php endif; ?>
			
			<?php if ( '1' === $show_excerpt ) : ?>
				<div class="<?php echo $class_prefix;?>_excerpt">
					<p><?php echo wpt_excerpt($pop_excerpt_length); ?></p>
				</div>
			<?php endif; ?>
									
			<div class="clear"></div>
		</li>
	<?php endwhile; wp_reset_postdata(); ?>       
</ul>
<div class="clear"></div>
<?php if ( '1' === $allow_pagination ) : ?>
	<?php
	wpt_get_template(
		'pagination.php',
		array(
			'page' => $page,
			'last_page' => $last_page,
			'class_prefix' => $class_prefix,
		)
	);
	?>
<?php endif; ?>