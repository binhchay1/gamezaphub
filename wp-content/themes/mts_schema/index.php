<?php
/**
 * The main template file.
 *
 * Used to display the homepage when home.php doesn't exist.
 *
 * @package Schema
 */

$mts_options = get_option( MTS_THEME_NAME );
get_header();
?>

<div id="page">
	<div class="article">
		<?php
		// Elementor `archive` location.
		if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
			?>
			<div id="content_box">
				<?php
				if ( ! is_paged() ) {
					if ( is_home() && '1' === $mts_options['mts_featured_slider'] ) {
						?>
						<div class="primary-slider-container clearfix loading">
							<div id="slider" class="primary-slider">
								<?php
								if ( empty( $mts_options['mts_custom_slider'] ) ) {

									// Prevent implode error.
									if ( empty( $mts_options['mts_featured_slider_cat'] ) || ! is_array( $mts_options['mts_featured_slider_cat'] ) ) {
										$mts_options['mts_featured_slider_cat'] = array( '0' );
									}

									$slider_cat   = implode( ',', $mts_options['mts_featured_slider_cat'] );
									$slider_query = new WP_Query( 'cat=' . $slider_cat . '&posts_per_page=' . $mts_options['mts_featured_slider_num'] );

									while ( $slider_query->have_posts() ) :
										$slider_query->the_post();
										?>
										<div class="primary-slider-item">
											<a href="<?php echo esc_url( get_the_permalink() ); ?>">
												<?php
												if ( has_post_thumbnail() ) {
													$attr = array(
														'title'   => esc_attr( get_the_title() ),
														'alt'     => esc_attr( get_the_title() ),
														'loading' => 'lazy'
													);
													the_post_thumbnail( 'schema-slider', $attr );
												} else {
													?>
													<img src="<?php echo get_template_directory_uri() . '/images/nothumb-schema-slider.png'; ?>" alt="<?php the_title(); ?>" class="wp-post-image" width="772" height="350" loading="lazy" />
													<?php
												}
												?>
												<div class="slide-caption">
													<h2 class="slide-title"><?php the_title(); ?></h2>
												</div>
											</a>
										</div>
										<?php
									endwhile;
									wp_reset_postdata();

								} else {
									foreach ( $mts_options['mts_custom_slider'] as $slide ) :
										?>
										<div class="primary-slider-item">
											<a href="<?php echo esc_url( $slide['mts_custom_slider_link'] ); ?>">
												<?php
												$attr = array(
													'title'   => $slide['mts_custom_slider_title'],
													'alt'     => $slide['mts_custom_slider_title'],
													'loading' => 'lazy'
												);
												echo wp_get_attachment_image( $slide['mts_custom_slider_image'], 'schema-slider', false, $attr ); ?>
												<div class="slide-caption">
													<h2 class="slide-title"><?php echo esc_html( $slide['mts_custom_slider_title'] ); ?></h2>
												</div>
											</a>
										</div>
										<?php
									endforeach;
								}
								?>
							</div><!-- .primary-slider -->
						</div><!-- .primary-slider-container -->

						<?php
					}

					$featured_categories = array();
					if ( ! empty( $mts_options['mts_featured_categories'] ) ) {
						foreach ( $mts_options['mts_featured_categories'] as $section ) {
							$category_id           = $section['mts_featured_category'];
							$featured_categories[] = $category_id;
							$posts_num             = $section['mts_featured_category_postsnum'];

							if ( 'latest' === $category_id ) {
								$j = 0;
								if ( have_posts() ) :
									while ( have_posts() ) :
										the_post();
										?>
										<article class="latestPost excerpt">
											<?php mts_archive_post(); ?>
										</article>
										<?php
									endwhile;
								endif;

								++$j;
								if ( 0 !== $j ) { // No pagination if there is no posts.
									mts_pagination();
								}
							} else {
								?>
								<h3 class="featured-category-title"><a href="<?php echo esc_url( get_category_link( $category_id ) ); ?>" title="<?php echo esc_attr( get_cat_name( $category_id ) ); ?>"><?php echo esc_html( get_cat_name( $category_id ) ); ?></a></h3>
								<?php
								$j         = 0;
								$cat_query = new WP_Query( 'cat=' . $category_id . '&posts_per_page=' . $posts_num );

								if ( $cat_query->have_posts() ) :
									while ( $cat_query->have_posts() ) :
										$cat_query->the_post();
										?>
										<article class="latestPost excerpt">
											<?php mts_archive_post(); ?>
										</article>
										<?php
									endwhile;
								endif;
								wp_reset_postdata();
							}
						}
					}
				} else { // Paged.

					$j = 0;
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							?>
								<article class="latestPost excerpt">
								<?php mts_archive_post(); ?>
							</article>
							<?php
						endwhile;
					endif;

					++$j;
					if ( 0 !== $j ) { // No pagination if there is no posts.
						mts_pagination();
					}
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	get_sidebar();
	get_footer();
