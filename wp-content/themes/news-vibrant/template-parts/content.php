<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

if ( has_post_thumbnail() ) {
	$post_class = 'has-thumbnail';
} else {
	$post_class = 'no-thumbnail';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="nv-article-thumb">
			<?php the_post_thumbnail( 'full' ); ?>
		</div><!-- .nv-article-thumb -->
	<?php } ?>

	<div class="nv-archive-post-content-wrapper">

		<header class="entry-header">
			<?php
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

				if ( 'post' === get_post_type() ) :
			?>
					<div class="entry-meta">
						<?php news_vibrant_inner_posted_on(); ?>
					</div><!-- .entry-meta -->
			<?php
				endif;
			?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php
				the_excerpt();
				$news_vibrant_archive_read_more_text = get_theme_mod( 'news_vibrant_archive_read_more_text', __( 'Continue Reading', 'news-vibrant' ) );
			?>
			<span class="nv-archive-more"><a href="<?php the_permalink(); ?>" class="nv-button"><i class="fa fa-arrow-circle-o-right"></i><?php echo esc_html( $news_vibrant_archive_read_more_text ); ?></a></span>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php news_vibrant_entry_footer(); ?>
		</footer><!-- .entry-footer -->

	</div> <!-- nv-archive-post-content-wrapper -->
	
</article><!-- #post-<?php the_ID(); ?> -->