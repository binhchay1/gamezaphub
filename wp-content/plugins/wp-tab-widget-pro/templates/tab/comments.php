<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<ul class="wptp-list">
	<?php
	$comment_num = (empty($num_comment) ? 5 : intval($num_comment));
	if ($comment_num > 20 || $comment_num < 1) {
		$comment_num = 5;
	}

	$no_comments = false;
	$avatar_size = 65;
	$comment_length = 90; // max length for comments
	$comm_title_length = ! empty($title_length) ? $title_length : apply_filters( 'wpt_comment_title_length_default', '10' );
	$comment_args = apply_filters(
		'wpt_comments_tab_args',
		array(
			'type' => 'comments',
			'status' => 'approve'
		)
	);
	$comment_args = apply_filters( 'wpt_comments_tab_query_args', $comment_args, $widget_number, $tab_key );
	$comments_total = new WP_Comment_Query();
	$comments_total_number = $comments_total->query( array_merge( array('count' => 1 ), $comment_args ) );
	$last_page = (int) ceil($comments_total_number / $comment_num);
	$comments_query = new WP_Comment_Query();
	$offset = ($page-1) * $comment_num;
	$comments = $comments_query->query( array_merge( array( 'number' => $comment_num, 'offset' => $offset ), $comment_args ) );
	if ( $comments ) : foreach ( $comments as $comment ) : ?>
		<li class="wptp-list-item">
			<?php if ( '1' === $show_avatar ) : ?>
				<div class="<?php echo $class_prefix;?>_avatar">
					<a href="<?php echo get_comment_link($comment->comment_ID); ?>">
						<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>
					</a>
				</div>
			<?php endif; ?>
			<div class="<?php echo $class_prefix;?>_comment_meta">
				<a href="<?php echo get_comment_link($comment->comment_ID); ?>">
					<span class="<?php echo $class_prefix;?>_comment_author"><?php echo get_comment_author( $comment->comment_ID ); ?> </span> - <span class="<?php echo $class_prefix;?>_comment_post"><?php echo wpt_post_title( $comm_title_length, $comment->comment_post_ID ); ?></span>
				</a>
			</div>
			<div class="<?php echo $class_prefix;?>_comment_content">
				<p><?php echo wpt_truncate(strip_tags(apply_filters( 'get_comment_text', $comment->comment_content )), $comment_length);?></p>
			</div>
			<div class="clear"></div>
		</li>
	<?php endforeach; else : ?>
		<li class="wptp-list-item">
			<div class="no-comments"><?php _e('No comments yet.', 'wp-tab-widget'); ?></div>
		</li>
		<?php $no_comments = true;
	endif; ?>
</ul>
<div class="clear"></div>
<?php if ( '1' === $allow_pagination && !$no_comments ) : ?>
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