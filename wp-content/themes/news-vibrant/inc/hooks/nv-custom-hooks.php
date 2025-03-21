<?php
/**
 * Custom hooks functions are define.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_related_posts_start' ) ) :

	/**
	 * Related Posts start
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_related_posts_start() {
		echo '<div class="nv-related-section-wrapper">';
	}

endif;


if ( ! function_exists( 'news_vibrant_related_posts_section' ) ) :

	/**
	 * Related Posts section
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_related_posts_section() {
		$news_vibrant_related_option = get_theme_mod( 'news_vibrant_related_posts_option', true );
		if ( false == $news_vibrant_related_option ) {
			return;
		}
		$news_vibrant_related_title = get_theme_mod( 'news_vibrant_related_posts_title', __( 'Related Posts', 'news-vibrant' ) );
		if ( !empty( $news_vibrant_related_title ) ) {
			echo '<h2 class="nv-related-title nv-clearfix">'. esc_html( $news_vibrant_related_title ) .'</h2>';
		}
		global $post;
        if ( empty( $post ) ) {
            $post_id = '';
        } else {
            $post_id = $post->ID;
        }
        $categories = get_the_category( $post_id );
        if ( $categories ) {
            $category_ids = array();
            foreach( $categories as $category_ed ) {
                $category_ids[] = $category_ed->term_id;
            }
        }
		$news_vibrant_post_count = apply_filters( 'news_vibrant_related_posts_count', 3 );
		
		$related_args = array(
			'no_found_rows'            	=> true,
            'update_post_meta_cache'   	=> false,
            'update_post_term_cache'   	=> false,
            'ignore_sticky_posts'      	=> 1,
            'orderby'                  	=> 'rand',
            'post__not_in'             	=> array( $post_id ),
            'category__in'				=> $category_ids,
			'posts_per_page' 		   	=> $news_vibrant_post_count
		);
		$related_query = new WP_Query( $related_args );
		if ( $related_query->have_posts() ) {
			echo '<div class="nv-related-posts-wrap nv-clearfix">';
			while ( $related_query->have_posts() ) {
				$related_query->the_post();
	?>
				<div class="nv-single-post nv-clearfix">
					<div class="nv-post-thumb">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'news-vibrant-block-medium' ); ?>
						</a>
					</div><!-- .nv-post-thumb -->
					<div class="nv-post-content">
						<h3 class="nv-post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="nv-post-meta">
							<?php news_vibrant_posted_on(); ?>
						</div>
					</div><!-- .nv-post-content -->
				</div><!-- .nv-single-post -->
	<?php
			}
			echo '</div><!-- .nv-related-posts-wrap -->';
		}
		wp_reset_postdata();
	}

endif;


if ( ! function_exists( 'news_vibrant_related_posts_end' ) ) :

	/**
	 * Related Posts end
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_related_posts_end() {
		echo '</div><!-- .nv-related-section-wrapper -->';
	}
	
endif;

/**
 * Managed functions for related posts section
 *
 * @since 1.0.0
 */
add_action( 'news_vibrant_related_posts', 'news_vibrant_related_posts_start', 5 );
add_action( 'news_vibrant_related_posts', 'news_vibrant_related_posts_section', 10 );
add_action( 'news_vibrant_related_posts', 'news_vibrant_related_posts_end', 15 );