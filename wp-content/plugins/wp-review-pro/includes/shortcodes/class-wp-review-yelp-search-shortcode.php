<?php
/**
 * Shortcode [wp-review-yelp-search]
 *
 * @package WP_Review
 * @since 3.0.0
 */

/**
 * Class WP_Review_Yelp_Search_Shortcode
 */
class WP_Review_Yelp_Search_Shortcode {

	/**
	 * Shortcode name.
	 *
	 * @var string
	 */
	protected $name = 'wp-review-yelp-search';

	/**
	 * Shortcode alias.
	 *
	 * @var string
	 */
	protected $alias = 'wp_review_yelp_search';

	/**
	 * Class init.
	 */
	public function init() {
		add_shortcode( $this->name, array( $this, 'render' ) );
		add_shortcode( $this->alias, array( $this, 'render' ) );
	}

	/**
	 * Renders shortcode.
	 *
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'term'       => '',
				'location'   => '',
				'radius'     => '',
				'categories' => '',
				'locale'     => 'en_US',
				'limit'      => 3,
				'offset'     => '',
				'sort_by'    => 'best_match',
				'price'      => '',
				'open_now'   => false,
				'attributes' => '',
			),
			$atts,
			$this->name
		);

		$search_args = array();
		foreach ( $atts as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			}
			$search_args[ $key ] = $value;
		}

		$key      = $this->get_cache_key( $atts );
		$response = get_transient( $key );
		if ( ! $response ) {
			$yelp_api = new WP_Review_Yelp_API();
			$response = $yelp_api->search( $atts['term'], $atts['location'], $search_args );

			if ( is_wp_error( $response ) ) {
				return '<strong>ERROR: </strong>' . $response->get_error_message();
			}
			set_transient( $key, $response, wp_review_option( 'yelp_cache_expired_time', 7 ) * DAY_IN_SECONDS );
		}

		ob_start();
		wp_review_load_template( 'shortcodes/yelp-search.php', compact( 'response', 'atts' ) );
		return ob_get_clean();
	}

	/**
	 * Gets cache key.
	 *
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	protected function get_cache_key( $atts ) {
		return sprintf( '%s_%s', $this->name, serialize( $atts ) );
	}
}

$shortcode = new WP_Review_Yelp_Search_Shortcode();
$shortcode->init();
