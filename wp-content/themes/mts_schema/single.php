<?php
/**
 * The template for displaying all single posts.
 *
 * @package Schema
 */

$disable_title         = get_post_meta( get_the_ID(), '_disable_title', true );
$disable_breadcrumb    = get_post_meta( get_the_ID(), '_disable_breadcrumb', true );
$disable_post_meta     = get_post_meta( get_the_ID(), '_disable_post_meta', true );
$disable_author_box    = get_post_meta( get_the_ID(), '_disable_author_box', true );
$disable_related_posts = get_post_meta( get_the_ID(), '_disable_related_posts', true );

$mts_options      = get_option( MTS_THEME_NAME );
$header_animation = mts_get_post_header_effect();

get_header();
?>

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
			// Elementor `single` location.
			if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>
						<div id="post-<?php the_ID(); ?>" <?php post_class( 'g post' ); ?>>
							<?php
							if ( '1' === $mts_options['mts_breadcrumb'] && empty( $disable_breadcrumb ) ) {
								mts_the_breadcrumb();
							}
							// Single post parts ordering.
							if ( isset( $mts_options['mts_single_post_layout'] ) && is_array( $mts_options['mts_single_post_layout'] ) && array_key_exists( 'enabled', $mts_options['mts_single_post_layout'] ) ) {
								$single_post_parts = $mts_options['mts_single_post_layout']['enabled'];
							} else {
								$single_post_parts = array(
									'content' => 'content',
									'related' => 'related',
									'author'  => 'author',
								);
							}
							foreach ( $single_post_parts as $part => $label ) {
								switch ( $part ) {
									case 'content':
										?>
										<div class="single_post">
											<header>
												<?php
												if ( ! empty( $mts_options['mts_show_featured'] ) ) {
													the_post_thumbnail( 'schema-featured' );
												}
												if ( empty( $disable_title ) ) {
													?>
													<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
													<?php
												}

												if ( empty( $disable_post_meta ) ) {
													mts_the_postinfo( 'single' );
												}
												?>
											</header><!--.headline_area-->
											<div class="post-single-content box mark-links entry-content">
												<?php
												if ( '' !== $mts_options['mts_posttop_adcode'] ) {
													$toptime = $mts_options['mts_posttop_adcode_time'];
													if ( strcmp( date( 'Y-m-d', strtotime( "-$toptime day" ) ), get_the_time( 'Y-m-d' ) ) >= 0 ) {
														?>
														<div class="topad">
															<?php echo do_shortcode( $mts_options['mts_posttop_adcode'] ); ?>
														</div>
														<?php
													}
												}

												if ( isset( $mts_options['mts_social_button_position'] ) && 'top' === $mts_options['mts_social_button_position'] ) {
													mts_social_buttons();
												}
												?>
												<div class="thecontent">
													<?php the_content(); ?>
												</div>

												<?php
												wp_link_pages( array(
													'before' => '<div class="pagination">',
													'after' => '</div>',
													'link_before' => '<span class="current"><span class="currenttext">',
													'link_after' => '</span></span>',
													'next_or_number' => 'next_and_number',
													'nextpagelink' => __( 'Next', 'schema' ),
													'previouspagelink' => __( 'Previous', 'schema' ),
													'pagelink' => '%',
													'echo' => 1,
												) );

												if ( '' !== $mts_options['mts_postend_adcode'] ) {
													$endtime = $mts_options['mts_postend_adcode_time'];
													if ( strcmp( date( 'Y-m-d', strtotime( "-$endtime day" ) ), get_the_time( 'Y-m-d' ) ) >= 0 ) {
														?>
														<div class="bottomad">
															<?php echo do_shortcode( $mts_options['mts_postend_adcode'] ); ?>
														</div>
														<?php
													}
												}

												if ( isset( $mts_options['mts_social_button_position'] ) && 'top' !== $mts_options['mts_social_button_position'] ) {
													mts_social_buttons();
												}
												?>
											</div><!--.post-single-content-->
										</div><!--.single_post-->
										<?php
										break;

									case 'tags':
										mts_the_tags( '<div class="tags"><span class="tagtext">' . __( 'Tags', 'schema' ) . ':</span>', ', ' );
										break;

									case 'related':
										if ( empty( $disable_related_posts ) ) {
											mts_related_posts();
										}
										break;

									case 'author':
										if ( empty( $disable_author_box ) ) {
											?>
											<div class="postauthor">
												<h4><?php esc_html_e( 'About The Author', 'schema' ); ?></h4>
												<?php
												if ( function_exists( 'get_avatar' ) ) {
													echo get_avatar( get_the_author_meta( 'email' ), '100' );
												}
												?>
												<h5 class="vcard author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author_meta( 'display_name' ); ?></a></h5>
												<p><?php the_author_meta( 'description' ); ?></p>
											</div>
										<?php
										}
										break;
								}
							}
							?>
						</div><!--.g post-->
						<?php
						comments_template( '', true );
					endwhile; /* end loop */
				endif;
			}
			?>
		</div>
	</article>
	<?php
	get_sidebar();
	get_footer();
