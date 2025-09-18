<?php
/**
 * Template part for review item in widget
 *
 * @var array $options
 *
 * @package WP_Review
 */

$in_widget  = ! empty( $options['widget_id'] );
$thumb_size = $options['thumb_size'];
if ( 'small' !== $thumb_size && 'large' !== $thumb_size ) {
	$thumb_size = 'small';
}
?>
<li class="item">
	<a title="<?php the_title(); ?>" rel="nofollow" href="<?php the_permalink(); ?>">
		<div class="thumbnail thumb_<?php echo esc_attr( $options['thumb_size'] ); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'wp_review_' . $options['thumb_size'] ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( WP_REVIEW_ASSETS . 'images/' . $thumb_size . 'thumb.png' ); ?>" alt="<?php the_title(); ?>" class="wp-post-image">
			<?php endif; ?>
		</div>
	</a>
	<div class="title-right">
		<div class="entry-title">
			<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
				<?php
				if ( $options['title_length'] ) {
					echo esc_html( wp_trim_words( get_the_title(), $options['title_length'], '&hellip;' ) );
				} else {
					the_title();
				}
				?>
			</a>
			<div class="review-count">
				<?php
				if ( $in_widget ) {
					$args = array(
						'in_widget'      => $in_widget,
						'circle_size'    => 20,
						'color'          => '#fff',
						'inactive_color' => '#dedcdc',
					);
				} else {
					$args = array();
				}
				wp_review_show_total( true, 'review-total-only ' . $options['thumb_size'] . '-thumb', null, $args );
				?>
			</div>

			<?php wp_review_extra_info( get_the_ID(), intval( $options['show_date'] ) ); // Using `show_date` to keep compatibility. ?>
		</div>
	</div>
</li>
