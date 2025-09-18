<?php
/**
 * Template Name: Contact Page
 * The template for displaying the page with a slug of `contact`.
 *
 * @package Schema
 */

$mts_options        = get_option( MTS_THEME_NAME );
$disable_title      = get_post_meta( get_the_ID(), '_disable_title', true );
$disable_breadcrumb = get_post_meta( get_the_ID(), '_disable_breadcrumb', true );

get_header(); ?>

<div id="page" class="<?php mts_single_page_class(); ?>">
	<?php
	$header_animation = mts_get_post_header_effect();
	if ( 'parallax' === $header_animation ) {
		if ( mts_get_thumbnail_url() ) :
			?>
			<div id="parallax" <?php echo 'style="background-image: url(' . esc_url( mts_get_thumbnail_url() ) . ');"'; ?>></div>
			<?php
		endif;
	} elseif ( 'zoomout' === $header_animation ) {
		if ( mts_get_thumbnail_url() ) :
			?>
			<div id="zoom-out-effect"><div id="zoom-out-bg" <?php echo 'style="background-image: url(' . esc_url( mts_get_thumbnail_url() ) . ');"'; ?>></div></div>
			<?php
		endif;
	}
	?>
	<article class="<?php mts_article_class(); ?> clearfix">
		<div id="content_box" >
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<div id="post-<?php the_ID(); ?>" <?php post_class( 'g post' ); ?>>
						<div class="single_page clearfix">
							<?php
							if ( '1' === $mts_options['mts_breadcrumb'] && empty( $disable_breadcrumb ) ) {
								mts_the_breadcrumb();
							}
							if ( empty( $disable_title ) ) {
								?>
								<header>
									<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
								</header>
								<?php
							}
							?>
							<div class="post-content box mark-links entry-content">
								<?php
								if ( ! empty( $mts_options['mts_social_buttons_on_pages'] ) && isset( $mts_options['mts_social_button_position'] ) && 'top' === $mts_options['mts_social_button_position'] ) {
									mts_social_buttons();
								}

								the_content();

								mts_contact_form();

								wp_link_pages( array(
									'before'           => '<div class="pagination">',
									'after'            => '</div>',
									'link_before'      => '<span class="current"><span class="currenttext">',
									'link_after'       => '</span></span>',
									'next_or_number'   => 'next_and_number',
									'nextpagelink'     => __( 'Next', 'schema' ),
									'previouspagelink' => __( 'Previous', 'schema' ),
									'pagelink'         => '%',
									'echo'             => 1,
								) );

								if ( ! empty( $mts_options['mts_social_buttons_on_pages'] ) && isset( $mts_options['mts_social_button_position'] ) && 'top' !== $mts_options['mts_social_button_position'] ) {
									mts_social_buttons();
								}
								?>
							</div><!--.post-content box mark-links-->
						</div>
					</div>
					<?php
					comments_template( '', true );
				endwhile;
			endif;
			?>
		</div>
	</article>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
