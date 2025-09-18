<?php
/**
 * WP Product Review importer
 *
 * @package WP_Review
 */

/**
 * Class WP_Review_Importer_WP_Product_Review
 */
class WP_Review_Importer_WP_Product_Review implements WP_Review_Importer_Interface {

	/**
	 * Runs import.
	 *
	 * @param int   $numposts Number of posts.
	 * @param int   $offset   Offset.
	 * @param array $options  Import options.
	 * @return WP_Review_Importer_Response.
	 */
	public function run( $numposts, $offset, $options ) {
		$posts       = $this->get_posts();
		$posts_count = count( $posts );
		if ( ! $posts_count ) {
			return new WP_Review_Importer_Response( __( 'There is no review.', 'wp-review' ), true, 0, true );
		}

		$subset = array_slice( $posts, $offset, $numposts );
		foreach ( $subset as $post_id ) {
			$this->import_reviews( $post_id );
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

	/**
	 * Gets posts have reviews.
	 *
	 * @return array
	 */
	protected function get_posts() {
		return get_posts(
			array(
				'post_type'              => 'any',
				'nopaging'               => true,
				'fields'                 => 'ids',
				'meta_key'               => 'cwp_meta_box_check',
				'meta_value'             => 'yes',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
	}

	/**
	 * Imports reviews.
	 *
	 * @param int $post_id Post ID.
	 */
	protected function import_reviews( $post_id ) {
		$price = get_post_meta( $post_id, 'cwp_rev_price', true );
		update_post_meta( $post_id, 'wp_review_product_price', $price );

		update_post_meta( $post_id, 'wp_review_type', 'percentage' );

		$heading = get_post_meta( $post_id, 'cwp_rev_product_name', true );
		update_post_meta( $post_id, 'wp_review_heading', $heading );

		$pros = get_post_meta( $post_id, 'wppr_pros', true ) ? get_post_meta( $post_id, 'wppr_pros', true ) : array();
		update_post_meta( $post_id, 'wp_review_pros', implode( "\n", $pros ) );

		$cons = get_post_meta( $post_id, 'wppr_cons', true ) ? get_post_meta( $post_id, 'wppr_cons', true ) : array();
		update_post_meta( $post_id, 'wp_review_cons', implode( "\n", $cons ) );

		$their_items = get_post_meta( $post_id, 'wppr_options', true ) ? get_post_meta( $post_id, 'wppr_options', true ) : array();
		$our_items   = array();
		foreach ( $their_items as $item ) {
			$our_items[] = array(
				'wp_review_item_title' => $item['name'],
				'wp_review_item_star'  => $item['value'],
			);
		}
		update_post_meta( $post_id, 'wp_review_item', $our_items );
	}
}
