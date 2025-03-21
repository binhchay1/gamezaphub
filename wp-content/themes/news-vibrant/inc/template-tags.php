<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'news_vibrant_posted_on' ) ) :

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */

	function news_vibrant_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		echo '<span class="posted-on"><a href="'. esc_url( get_permalink() ) .'" rel="bookmark">' . wp_kses_post( $time_string ) . '</a></span><span class="byline"><span class="author vcard"><a class="url fn n" href="'. esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) .'">' . esc_html( get_the_author() ) .'</a></span></span>';

	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_post_date' ) ) :

	/**
	 * Post date for hoemapge posts
	 */

	function news_vibrant_post_date() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
		echo '<span class="posted-on"><a href="'. esc_url( get_permalink() ) .'" rel="bookmark">' . wp_kses_post( $time_string ) . '</a></span>';
	}

endif;
/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_post_author' ) ) :

	/**
	 * Post author for homepage posts
	 */

	function news_vibrant_post_author() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'news-vibrant' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
		echo '<span class="byline"> ' . $byline . '</span>';
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_post_comment' ) ) :
	
	/**
	 * Comment for homepage post
	 */

	function news_vibrant_post_comment() {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( '0 ', 'news-vibrant' ), esc_html__( '1 ', 'news-vibrant' ), esc_html__( '% ', 'news-vibrant' ) );
		echo '</span>';
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'news_vibrant_inner_posted_on' ) ) :

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */

	function news_vibrant_inner_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		echo '<span class="posted-on"><a href="'. esc_url( get_permalink() ) .'" rel="bookmark">' . wp_kses_post( $time_string ) . '</a></span><span class="byline"><span class="author vcard"><a class="url fn n" href="'. esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) .'">' . esc_html( get_the_author() ) .'</a></span></span>';


		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( '0 ', 'news-vibrant' ), esc_html__( '1 ', 'news-vibrant' ), esc_html__( '% ', 'news-vibrant' ) );
			echo '</span>';
		}

	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'news_vibrant_entry_footer' ) ) :

	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */

	function news_vibrant_entry_footer() {

		if ( is_single() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list();
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'news-vibrant' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
		
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'news-vibrant' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_categorized_blog' ) ) :

	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */

	function news_vibrant_categorized_blog() {
		$all_the_cool_cats = get_transient( 'news_vibrant_categories' );
		if ( false === $all_the_cool_cats ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'news_vibrant_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 || is_preview() ) {
			// This blog has more than 1 category so news_vibrant_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so news_vibrant_categorized_blog should return false.
			return false;
		}
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

add_action( 'edit_category', 'news_vibrant_category_transient_flusher' );
add_action( 'save_post',     'news_vibrant_category_transient_flusher' );

if ( ! function_exists( 'news_vibrant_category_transient_flusher' ) ) :

	/**
	 * Flush out the transients used in news_vibrant_categorized_blog.
	 */

	function news_vibrant_category_transient_flusher() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		delete_transient( 'news_vibrant_categories' );
	}

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_post_categories_list' ) ):

	/**
	 * Categories list in multiple color background
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_post_categories_list() {
		global $post;
		$post_id = $post->ID;
		$categories_list = get_the_category( $post_id );
		if ( !empty( $categories_list ) ) {
?>
		<div class="post-cats-list">
			<?php 
				foreach ( $categories_list as $cat_data ) {
					$cat_name = $cat_data->name;
					$cat_id = $cat_data->term_id;
					$cat_link = get_category_link( $cat_id );
			?>
				<span class="category-button nv-cat-<?php echo esc_attr( $cat_id ); ?>"><a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat_name ); ?></a></span>
			<?php 
				}
			?>
		</div>
<?php
		}
	}
	
endif;