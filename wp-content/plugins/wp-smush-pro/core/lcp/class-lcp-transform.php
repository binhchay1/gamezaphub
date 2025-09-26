<?php

namespace Smush\Core\LCP;

use Smush\Core\Parser\Composite_Element;
use Smush\Core\Parser\Element;
use Smush\Core\Settings;
use Smush\Core\Transform\Transform;

class LCP_Transform implements Transform {
	private $settings;

	public function __construct() {
		$this->settings = Settings::get_instance();
	}

	public function should_transform() {
		return $this->settings->is_lcp_preload_enabled();
	}

	public function transform_page( $page ) {
		// The loading attribute may already have been removed by the lazy load transform, but we still need to handle the cases where it hasn't been removed
		// For example: - Smush lazy loading is disabled - We are on a page that is excluded
		$lcp_element = $page->get_lcp_element();
		if ( is_a( $lcp_element, Composite_Element::class ) ) {
			foreach ( $lcp_element->get_elements() as $sub_element ) {
				$this->maybe_remove_loading_attribute( $sub_element );
			}
		} elseif ( is_a( $lcp_element, Element::class ) ) {
			$this->maybe_remove_loading_attribute( $lcp_element );
		}
	}

	/**
	 * @param $lcp_element Element
	 *
	 * @return void
	 */
	private function maybe_remove_loading_attribute( $lcp_element ) {
		if ( $lcp_element && $lcp_element->has_attribute( 'loading' ) ) {
			$loading_attribute = $lcp_element->get_attribute( 'loading' );
			if ( $loading_attribute->get_value() === 'lazy' ) {
				$lcp_element->remove_attribute( $loading_attribute );
			}
		}
	}

	public function transform_image_url( $url ) {
		return $url;
	}
}