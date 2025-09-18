<?php
/**
 * Class WP_Review_Importer_AhReview
 *
 * @package WP_Review
 */

/**
 * Class WP_Review_Importer_AhReview
 */
class WP_Review_Importer_AhReview implements WP_Review_Importer_Interface {

	/**
	 * Runs import.
	 *
	 * @param int   $numposts Number of posts.
	 * @param int   $offset   Offset.
	 * @param array $options  Options.
	 * @return WP_Review_Importer_Response
	 */
	public function run( $numposts, $offset, $options ) {
		$pids = get_posts(
			array(
				'post_type'              => 'any',
				'nopaging'               => $numposts,
				'meta_key'               => 'ta_post_review_rating',
				'fields'                 => 'ids',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		$posts_count = count( $pids );
		if ( ! $posts_count ) {
			return new WP_Review_Importer_Response( __( 'There is no review.', 'wp-review' ), true, 0, true );
		}

		$subset = array_slice( $pids, $offset, $numposts );
		foreach ( $subset as $post_id ) {
			$rating = get_post_meta( $post_id, 'ta_post_review_rating', true );

			update_post_meta( $post_id, 'wp_review_type', 'star' );
			update_post_meta( $post_id, 'wp_review_userReview', $options['default_user_review_type'] );
			update_post_meta( $post_id, 'wp_review_total', $rating );
		}

		$new_offset = $offset + count( $subset );
		if ( $new_offset < $posts_count ) {
			return new WP_Review_Importer_Response(
				// translators: %1$s: new offset, %2$s: posts count.
				sprintf( __( 'Imported %1$s of %2$s.', 'wp-review' ), $new_offset, $posts_count ),
				false,
				$new_offset
			);
		}

		return new WP_Review_Importer_Response(
			// translators: posts count.
			sprintf( __( 'Imported ratings from %s posts.', 'wp-review' ), $posts_count )
		);
	}
}
