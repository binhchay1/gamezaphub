<?php

namespace Smush\Core\Integrations;

use Smush\Core\Controller;
use Smush\Core\LCP\LCP_Data_Store_Home;
use Smush\Core\LCP\LCP_Data_Store_Post_Meta;

class WP_Rocket_Integration extends Controller {
	public function __construct() {
		$this->register_action( 'wp_smush_lcp_data_updated', array( $this, 'clear_cache_when_lcp_changes' ), 10, 2 );
	}

	public function clear_cache_when_lcp_changes( $lcp_data, $data_store ) {
		if ( $data_store->get_type() === LCP_Data_Store_Post_Meta::TYPE ) {
			if ( function_exists( 'rocket_clean_post' ) ) {
				$post_id = $data_store->get_object_id();
				rocket_clean_post( $post_id );
			}
		} else if ( $data_store->get_type() === LCP_Data_Store_Home::TYPE ) {
			if ( function_exists( 'rocket_clean_home' ) ) {
				rocket_clean_home();
			}
		}
	}
}