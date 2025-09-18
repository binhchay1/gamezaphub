<?php
/**
 * Shortcode [wp-review-badge]
 *
 * @package WP_Review
 * @since   3.3.10
 */

/**
 * Class WP_Review_Badge_Shortcode
 */
class WP_Review_Badge_Shortcode {

	/**
	 * Shortcode name.
	 *
	 * @var string
	 */
	protected $name = 'wp-review-badge';

	/**
	 * Shortcode alias.
	 *
	 * @var string
	 */
	protected $alias = 'wp_review_badge';

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
				'id'       => '',
				'type'     => '',
				'top'      => '',
				'left'     => '',
				'right'    => '',
				'bottom'   => '',
				'bg_color' => '',
			),
			$atts,
			$this->name
		);

		$post = get_post( $atts['id'] );
		if ( ! $post ) {
			return '';
		}

		$rating_data = 'comment' === $atts['type'] ? mts_get_post_comments_reviews( $post->ID ) : mts_get_post_reviews( $post->ID );
		$fixed       = ! empty( $atts['top'] ) || ! empty( $atts['left'] ) || ! empty( $atts['right'] ) || ! empty( $atts['bottom'] );

		ob_start();
		wp_review_load_template( 'shortcodes/review-badge.php', compact( 'rating_data', 'fixed', 'atts' ) );
		return ob_get_clean();
	}
}

$shortcode = new WP_Review_Badge_Shortcode();
$shortcode->init();
