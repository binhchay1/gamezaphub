<?php
/**
 * Custom hooks functions for different layout in widget section.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/

add_action( 'news_vibrant_widget_title', 'news_vibrant_widget_title_callback' );

if ( ! function_exists( 'news_vibrant_widget_title_callback' ) ) :

	/**
	 * Widget Title function
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_widget_title_callback( $news_vibrant_title_args ) {
		$news_vibrant_block_title     = $news_vibrant_title_args['title'];
		$news_vibrant_block_cat_slug  = $news_vibrant_title_args['cat_slug'];
		$news_vibrant_get_cat_info 	  = get_category_by_slug( $news_vibrant_block_cat_slug );
		$news_vibrant_block_cat_id 	  = $news_vibrant_get_cat_info->term_id;
		$news_vibrant_title_cat_link  = get_theme_mod( 'news_vibrant_widget_cat_link_option', true );
		$news_vibrant_title_cat_color = get_theme_mod( 'news_vibrant_widget_cat_color_option', true );

		if ( true == $news_vibrant_title_cat_color ) {
			$title_class = 'nv-title nv-cat-'. $news_vibrant_block_cat_id;
		} else {
			$title_class = 'nv-title';
		}
		
		if ( !empty( $news_vibrant_block_cat_id ) && true == $news_vibrant_title_cat_link ) {
			$news_vibrant_blcok_cat_link = get_category_link( $news_vibrant_block_cat_id );
			echo '<h2 class="nv-block-title"><a href="'. esc_url( $news_vibrant_blcok_cat_link ) .'"><span class="'. esc_attr( $title_class ) .'">'. esc_html( $news_vibrant_block_title ) .'</span></a></h2>';
		} else {
			echo '<h2 class="nv-block-title"><span class="'. esc_attr( $title_class ) .'">'. esc_html( $news_vibrant_block_title ) .'</span></h2>';
		}		
	}

endif;


/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_block_default_layout_section' ) ) :

	/**
	 * Block Default Layout
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_block_default_layout_section( $cat_slug ) {
		if ( empty( $cat_slug ) ) {
			return;
		}
		$news_vibrant_post_count = apply_filters( 'news_vibrant_block_default_posts_count', 6 );
		$block_args = array(
			'category_name'  => esc_attr( $cat_slug ),
			'posts_per_page' => absint( $news_vibrant_post_count ),
		);
		$block_query = new WP_Query( $block_args );
		$total_posts_count = $block_query->post_count;
		if ( $block_query->have_posts() ) {
			$post_count = 1;
			while( $block_query->have_posts() ) {
				$block_query->the_post();
				if ( $post_count == 1 ) {
					echo '<div class="nv-primary-block-wrap">';
					$title_size = 'large-size';
				} elseif ( $post_count == 2 ) {
					echo '<div class="nv-secondary-block-wrap">';
					$title_size = 'small-size';
				} else {
					$title_size = 'small-size';
				}
	?>
					<div class="nv-single-post nv-clearfix">
						<div class="nv-post-thumb">
							<a href="<?php the_permalink(); ?>">
								<?php 
									if ( $post_count == 1 ) {
										the_post_thumbnail( 'news-vibrant-slider-medium' );
									} else {
										the_post_thumbnail( 'news-vibrant-block-thumb' );
									}
								?>
							</a>
						</div><!-- .nv-post-thumb -->
						<div class="nv-post-content">
							<h3 class="nv-post-title <?php echo esc_attr( $title_size ); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="nv-post-meta">
                                <?php
                                	if ( $post_count == 1 ) {
                                		news_vibrant_post_author();
                                	}
                                    news_vibrant_post_date();
                                    news_vibrant_post_comment();
                                ?>
                            </div>
							<?php if ( $post_count == 1 ) { ?>
								<div class="nv-post-excerpt"><?php the_excerpt(); ?></div>
							<?php } ?>
						</div><!-- .nv-post-content -->
					</div><!-- .nv-single-post -->
	<?php
				if ( $post_count == 1 ) {
					echo '</div><!-- .nv-primary-block-wrap -->';
				} elseif ( $post_count == $total_posts_count ) {
					echo '</div><!-- .nv-secondary-block-wrap -->';
				}
			$post_count++;
			}
		}
		wp_reset_postdata();
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_block_second_layout_section' ) ) :

	/**
	 * Block Second Layout
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_block_second_layout_section( $cat_slug ) {
		if ( empty( $cat_slug ) ) {
			return;
		}
		$news_vibrant_post_count = apply_filters( 'news_vibrant_block_second_layout_posts_count', 6 );
		$block_args = array(
			'category_name'  => esc_attr( $cat_slug ),
			'posts_per_page' => absint( $news_vibrant_post_count ),
		);
		$block_query = new WP_Query( $block_args );
		$total_posts_count = $block_query->post_count;
		if ( $block_query->have_posts() ) {
			$post_count = 1;
			while( $block_query->have_posts() ) {
				$block_query->the_post();
				if ( $post_count == 1 ) {
					echo '<div class="nv-primary-block-wrap">';
				} elseif ( $post_count == 3 ) {
					echo '<div class="nv-secondary-block-wrap">';
				}
				if ( $post_count <= 2 ) {
					$title_size = 'large-size';
				} else {
					$title_size = 'small-size';
				}
	?>
					<div class="nv-single-post nv-clearfix">
						<div class="nv-post-thumb">
							<a href="<?php the_permalink(); ?>">
								<?php 
									if ( $post_count <= 2 ) {
										the_post_thumbnail( 'news-vibrant-slider-medium' );
									} else {
										the_post_thumbnail( 'news-vibrant-block-thumb' );
									}
								?>
							</a>
						</div><!-- .nv-post-thumb -->
						<div class="nv-post-content">
							<h3 class="nv-post-title <?php echo esc_attr( $title_size ); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="nv-post-meta">
                                <?php
                                	if ( $post_count <= 2 ) {
                                		news_vibrant_post_author();
                                	}
                                    news_vibrant_post_date();
                                    news_vibrant_post_comment();
                                ?>
                            </div>
							<?php if ( $post_count <= 2 ) { ?>
								<div class="nv-post-excerpt"><?php the_excerpt(); ?></div>
							<?php } ?>
						</div><!-- .nv-post-content -->
					</div><!-- .nv-single-post -->
	<?php
				if ( $post_count == 2 ) {
					echo '</div><!-- .nv-primary-block-wrap -->';
				} elseif ( $post_count == $total_posts_count ) {
					echo '</div><!-- .nv-secondary-block-wrap -->';
				}
				$post_count++;
			}
		}
		wp_reset_postdata();
	}

endif;
/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_block_box_layout_section' ) ) :

	/**
	 * Block Box Layout
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_block_box_layout_section( $cat_slug ) {
		if ( empty( $cat_slug ) ) {
			return;
		}
		$news_vibrant_post_count = apply_filters( 'news_vibrant_block_box_posts_count', 4 );
		$block_args = array(
			'category_name'  => esc_attr( $cat_slug ),
			'posts_per_page' => absint( $news_vibrant_post_count ),
		);
		$block_query = new WP_Query( $block_args );
		$total_posts_count = $block_query->post_count;
		if ( $block_query->have_posts() ) {
			$post_count = 1;
			while( $block_query->have_posts() ) {
				$block_query->the_post();
				if ( $post_count == 1 ) {
					echo '<div class="nv-primary-block-wrap">';
					$title_size = 'large-size';
				} elseif ( $post_count == 2 ) {
					echo '<div class="nv-secondary-block-wrap nv-clearfix">';
					$title_size = 'small-size';
				} else {
					$title_size = 'small-size';
				}
	?>
					<div class="nv-single-post">
						<div class="nv-post-thumb">
							<a href="<?php the_permalink(); ?>">
								<?php 
									if ( $post_count == 1 ) {
										the_post_thumbnail( 'full' );
									} else {
										the_post_thumbnail( 'news-vibrant-block-medium' );
									}
								?>
							</a>
						</div><!-- .nv-post-thumb -->
						<div class="nv-post-content">
							<h3 class="nv-post-title <?php echo esc_attr( $title_size ); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="nv-post-meta">
                                <?php
                                	if ( $post_count == 1 ) {
                                		news_vibrant_post_author();
                                	}
                                    news_vibrant_post_date();
                                    news_vibrant_post_comment();
                                ?>
                            </div>
						</div><!-- .nv-post-content -->
					</div><!-- .nv-single-post -->
	<?php
				if ( $post_count == 1 ) {
					echo '</div><!-- .nv-primary-block-wrap -->';
				} elseif ( $post_count == $total_posts_count ) {
					echo '</div><!-- .nv-secondary-block-wrap -->';
				}
			$post_count++;
			}
		}
		wp_reset_postdata();
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_block_alternate_grid_section' ) ) :

	/**
	 * Block alternate grid
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_block_alternate_grid_section( $cat_slug ) {
		if ( empty( $cat_slug ) ) {
			return;
		}
		$news_vibrant_post_count = apply_filters( 'news_vibrant_block_alternate_grid_posts_count', 3 );
		$block_args = array(
			'category_name'  => esc_attr( $cat_slug ),
			'posts_per_page' => absint( $news_vibrant_post_count ),
		);
		$block_query = new WP_Query( $block_args );
		$total_posts_count = $block_query->post_count;
		if ( $block_query->have_posts() ) {
			while( $block_query->have_posts() ) {
				$block_query->the_post();
	?>
				<div class="nv-alt-grid-post nv-single-post nv-clearfix">
					<div class="nv-post-thumb">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'news-vibrant-alternate-grid' ); ?>
						</a>
					</div><!-- .nv-post-thumb -->
					<div class="nv-post-content">
						<h3 class="nv-post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="nv-post-meta">
                            <?php
                                news_vibrant_post_date();
                                news_vibrant_post_comment();
                            ?>
                        </div>
						<div class="nv-post-excerpt"><?php the_excerpt(); ?></div>
					</div><!-- .nv-post-content -->
				</div><!-- .nv-single-post -->
	<?php
			}
		}
		wp_reset_postdata();
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_carousel_default_layout_section' ) ) :

	/**
	 * Carousel Default Layout
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_carousel_default_layout_section( $news_vibrant_block_args ) {
		$news_vibrant_block_query = new WP_Query( $news_vibrant_block_args );
		if ( $news_vibrant_block_query->have_posts() ) {
			echo '<ul id="blockCarousel" class="cS-hidden">';
			while( $news_vibrant_block_query->have_posts() ) {
				$news_vibrant_block_query->the_post();
	?>
				<li>
					<div class="nv-single-post nv-clearfix">
						<div class="nv-post-thumb">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'news-vibrant-carousel-portrait' ); ?>
							</a>
						</div><!-- .nv-post-thumb -->
						<div class="nv-post-content">
							<?php news_vibrant_post_categories_list(); ?>
							<h3 class="nv-post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="nv-post-meta"> <?php news_vibrant_post_date(); ?> </div>
						</div><!-- .nv-post-content -->
					</div><!-- .nv-single-post -->
				</li>
	<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
	}

endif;