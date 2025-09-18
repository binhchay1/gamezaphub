<?php
/**
 * Interface WP_Review_Importer_Interface
 *
 * @package WP_Review
 */

/**
 * Interface WP_Review_Importer_Interface
 */
interface WP_Review_Importer_Interface {

	/**
	 * Runs import.
	 *
	 * @param int   $numposts Number of posts.
	 * @param int   $offset   Offset.
	 * @param array $options  Import options.
	 * @return WP_Review_Importer_Response.
	 */
	public function run( $numposts, $offset, $options );
}
