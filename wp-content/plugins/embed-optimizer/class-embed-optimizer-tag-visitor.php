<?php
/**
 * Tag visitor for Embed Optimizer.
 *
 * @package embed-optimizer
 * @since 0.2.0
 */

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// @codeCoverageIgnoreEnd

/**
 * Tag visitor that optimizes embeds.
 *
 * @phpstan-import-type DOMRect from OD_URL_Metric
 *
 * @since 0.2.0
 * @access private
 */
final class Embed_Optimizer_Tag_Visitor {

	/**
	 * Whether the lazy-loading script was added to the body.
	 *
	 * @var bool
	 */
	private $added_lazy_script = false;

	/**
	 * Determines whether the processor is currently at a figure.wp-block-embed tag.
	 *
	 * @since 0.3.0
	 *
	 * @param OD_HTML_Tag_Processor $processor Processor.
	 * @return bool Whether at the tag.
	 */
	private function is_embed_figure( OD_HTML_Tag_Processor $processor ): bool {
		return (
			'FIGURE' === $processor->get_tag()
			&&
			true === $processor->has_class( 'wp-block-embed' )
		);
	}

	/**
	 * Determines whether the processor is currently at a div.wp-block-embed__wrapper tag (which is a child of figure.wp-block-embed).
	 *
	 * @since 0.3.0
	 *
	 * @param OD_HTML_Tag_Processor $processor Processor.
	 * @return bool Whether the tag should be measured and stored in URL Metrics.
	 */
	private function is_embed_wrapper( OD_HTML_Tag_Processor $processor ): bool {
		return (
			'DIV' === $processor->get_tag()
			&&
			true === $processor->has_class( 'wp-block-embed__wrapper' )
		);
	}

	/**
	 * Visits a tag.
	 *
	 * This visitor has two entry points, the `figure.wp-block-embed` tag and its child the `div.wp-block-embed__wrapper`
	 * tag. For example:
	 *
	 *     <figure class="wp-block-embed is-type-video is-provider-wordpress-tv wp-block-embed-wordpress-tv wp-embed-aspect-16-9 wp-has-aspect-ratio">
	 *         <div class="wp-block-embed__wrapper">
	 *             <iframe title="VideoPress Video Player" aria-label='VideoPress Video Player' width='750' height='422' src='https://video.wordpress.com/embed/vaWm9zO6?hd=1&amp;cover=1' frameborder='0' allowfullscreen allow='clipboard-write'></iframe>
	 *             <script src='https://v0.wordpress.com/js/next/videopress-iframe.js?m=1674852142'></script>
	 *         </div>
	 *     </figure>
	 *
	 * For the `div.wp-block-embed__wrapper` tag, the only thing this tag visitor does is flag it for tracking in URL
	 * Metrics (by returning true). When visiting the parent `figure.wp-block-embed` tag, it does all the actual
	 * processing. In particular, it will use the element metrics gathered for the child `div.wp-block-embed__wrapper`
	 * element to set the min-height style on the `figure.wp-block-embed` to avoid layout shifts. Additionally, when
	 * the embed is in the initial viewport for any breakpoint, it will add preconnect links for key resources.
	 * Otherwise, if the embed is not in any initial viewport, it will add lazy-loading logic.
	 *
	 * @since 0.2.0
	 * @since 0.4.0 Adds preconnect links for each viewport group and skips if the element is not in the viewport for that group.
	 *
	 * @param OD_Tag_Visitor_Context $context Tag visitor context.
	 * @return bool Whether the tag should be tracked in URL Metrics.
	 */
	public function __invoke( OD_Tag_Visitor_Context $context ): bool {
		$processor = $context->processor;

		/*
		 * The only thing we need to do if it is a div.wp-block-embed__wrapper tag is return true so that the tag
		 * will get measured and stored in the URL Metrics.
		 */
		if ( $this->is_embed_wrapper( $processor ) ) {
			return true;
		}

		// Short-circuit if not a figure.wp-block-embed tag.
		if ( ! $this->is_embed_figure( $processor ) ) {
			return false;
		}

		$this->reduce_layout_shifts( $context );
		$this->add_preconnect_links( $context );
		$this->lazy_load_embeds( $context );

		/*
		 * At this point the tag is a figure.wp-block-embed, and we can return false because this does not need to be
		 * measured and stored in URL Metrics. Only the child div.wp-block-embed__wrapper tag is measured and stored
		 * so that this visitor can look up the height to set as a min-height on the figure.wp-block-embed. For more
		 * information on what the return values mean for tag visitors, see <https://github.com/WordPress/performance/issues/1342>.
		 */
		return false;
	}

	/**
	 * Gets the XPath for the embed wrapper DIV which is the sole child of the embed block FIGURE.
	 *
	 * @since 0.3.0
	 *
	 * @param string $embed_block_xpath XPath for the embed block FIGURE tag. For example: `/HTML/BODY/DIV/*[1][self::FIGURE]`.
	 * @return string XPath for the child DIV. For example: `/HTML/BODY/DIV/*[1][self::FIGURE]/*[1][self::DIV]`
	 */
	private static function get_embed_wrapper_xpath( string $embed_block_xpath ): string {
		return $embed_block_xpath . '/*[1][self::DIV]';
	}

	/**
	 * Reduces layout shifts.
	 *
	 * @since 0.3.0
	 *
	 * @param OD_Tag_Visitor_Context $context Tag visitor context, with the cursor currently at an embed block.
	 */
	private function reduce_layout_shifts( OD_Tag_Visitor_Context $context ): void {
		$processor           = $context->processor;
		$embed_wrapper_xpath = self::get_embed_wrapper_xpath( $processor->get_xpath() );

		/**
		 * Collection of the minimum heights for the element with each group keyed by the minimum viewport width.
		 *
		 * @var array<int, array{group: OD_URL_Metric_Group, height: int}> $minimums
		 */
		$minimums = array();

		$elements = $context->url_metric_group_collection->get_xpath_elements_map()[ $embed_wrapper_xpath ] ?? array();
		foreach ( $elements as $element ) {
			/**
			 * Resized bounding client rect.
			 *
			 * @var DOMRect|null $resized_bounding_client_rect
			 */
			$resized_bounding_client_rect = $element->get( 'resizedBoundingClientRect' );
			if ( ! is_array( $resized_bounding_client_rect ) ) {
				continue;
			}
			$group = $element->get_url_metric_group();
			if ( null === $group ) {
				continue; // Technically could be null but in practice it never will be.
			}
			$group_min_width = $group->get_minimum_viewport_width();
			if ( ! isset( $minimums[ $group_min_width ] ) ) {
				$minimums[ $group_min_width ] = array(
					'group'  => $group,
					'height' => $resized_bounding_client_rect['height'],
				);
			} else {
				$minimums[ $group_min_width ]['height'] = min(
					$minimums[ $group_min_width ]['height'],
					$resized_bounding_client_rect['height']
				);
			}
		}

		// Add style rules to set the min-height for each viewport group.
		if ( count( $minimums ) > 0 ) {
			$element_id = $processor->get_attribute( 'id' );
			if ( ! is_string( $element_id ) ) {
				$element_id = 'embed-optimizer-' . md5( $processor->get_xpath() );
				$processor->set_attribute( 'id', $element_id );
			}

			$style_rules = array();
			foreach ( $minimums as $minimum ) {
				$style_rule = sprintf(
					'#%s { min-height: %dpx; }',
					$element_id,
					$minimum['height']
				);

				$media_feature = od_generate_media_query( $minimum['group']->get_minimum_viewport_width(), $minimum['group']->get_maximum_viewport_width() );
				if ( null !== $media_feature ) {
					$style_rule = sprintf(
						'@media %s { %s }',
						$media_feature,
						$style_rule
					);
				}
				$style_rules[] = $style_rule;
			}

			$processor->append_head_html( sprintf( "<style>\n%s\n</style>\n", join( "\n", $style_rules ) ) );
		}
	}

	/**
	 * Gets preconnect URLs based on embed type.
	 *
	 * The following embeds have been chosen for optimization due to their relative popularity among all embed types.
	 * The list of hosts being preconnected to was obtained by inserting an embed into a post and then looking
	 * at the network log on the frontend as the embed renders. Each should include the host of the iframe src
	 * as well as URLs for assets used by the embed, _if_ the URL looks like it is not geotargeted (e.g. '-us')
	 * or load-balanced (e.g. 's0.example.com'). For the load balancing case, attempt to load the asset by
	 * incrementing the number appearing in the subdomain (e.g. s1.example.com). If the asset still loads, then
	 * it is a likely case of a load balancing domain name which cannot be safely preconnected since it could
	 * not end up being the load balanced domain used for the embed. Lastly, these domains are only for the URLs
	 * for GET requests, as POST requests are not likely to be part of the critical rendering path.
	 *
	 * @since 0.4.1
	 *
	 * @param OD_HTML_Tag_Processor $processor Processor, with the cursor currently at an embed block.
	 * @return array<non-empty-string> Array of URLs to preconnect to.
	 */
	private function get_preconnect_urls( OD_HTML_Tag_Processor $processor ): array {
		$urls      = array();
		$has_class = static function ( string $wanted_class ) use ( $processor ): bool {
			return true === $processor->has_class( $wanted_class );
		};

		if ( $has_class( 'wp-block-embed-youtube' ) ) {
			$urls[] = 'https://www.youtube.com';
			$urls[] = 'https://i.ytimg.com';
		} elseif ( $has_class( 'wp-block-embed-twitter' ) ) {
			$urls[] = 'https://syndication.twitter.com';
			$urls[] = 'https://pbs.twimg.com';
		} elseif ( $has_class( 'wp-block-embed-vimeo' ) ) {
			$urls[] = 'https://player.vimeo.com';
			$urls[] = 'https://f.vimeocdn.com';
			$urls[] = 'https://i.vimeocdn.com';
		} elseif ( $has_class( 'wp-block-embed-spotify' ) ) {
			$urls[] = 'https://apresolve.spotify.com';
			$urls[] = 'https://embed-cdn.spotifycdn.com';
			$urls[] = 'https://encore.scdn.co';
			$urls[] = 'https://i.scdn.co';
		} elseif ( $has_class( 'wp-block-embed-videopress' ) || $has_class( 'wp-block-embed-wordpress-tv' ) ) {
			$urls[] = 'https://video.wordpress.com';
			$urls[] = 'https://public-api.wordpress.com';
			$urls[] = 'https://videos.files.wordpress.com';
			$urls[] = 'https://v0.wordpress.com'; // This does not appear to be a load-balanced domain since v1.wordpress.com is not valid.
		} elseif ( $has_class( 'wp-block-embed-instagram' ) ) {
			$urls[] = 'https://www.instagram.com';
			$urls[] = 'https://static.cdninstagram.com';
			$urls[] = 'https://scontent.cdninstagram.com';
		} elseif ( $has_class( 'wp-block-embed-tiktok' ) ) {
			$urls[] = 'https://www.tiktok.com';
			// Note: The other domains used for TikTok embeds include https://lf16-tiktok-web.tiktokcdn-us.com,
			// https://lf16-cdn-tos.tiktokcdn-us.com, and https://lf16-tiktok-common.tiktokcdn-us.com among others
			// which either appear to be geo-targeted ('-us') _or_ load-balanced ('lf16'). So these are not added
			// to the preconnected hosts.
		} elseif ( $has_class( 'wp-block-embed-amazon' ) ) {
			$urls[] = 'https://read.amazon.com';
			$urls[] = 'https://m.media-amazon.com';
		} elseif ( $has_class( 'wp-block-embed-soundcloud' ) ) {
			$urls[] = 'https://w.soundcloud.com';
			$urls[] = 'https://widget.sndcdn.com';
			// Note: There is also https://i1.sndcdn.com which is for the album art, but the '1' indicates it may be geotargeted/load-balanced.
		} elseif ( $has_class( 'wp-block-embed-pinterest' ) ) {
			$urls[] = 'https://assets.pinterest.com';
			$urls[] = 'https://widgets.pinterest.com';
			$urls[] = 'https://i.pinimg.com';
		}

		return $urls;
	}

	/**
	 * Adds preconnect links for embed resources.
	 *
	 * @since 0.4.1
	 *
	 * @param OD_Tag_Visitor_Context $context Tag visitor context, with the cursor currently at an embed block.
	 */
	private function add_preconnect_links( OD_Tag_Visitor_Context $context ): void {
		$processor           = $context->processor;
		$embed_wrapper_xpath = self::get_embed_wrapper_xpath( $processor->get_xpath() );

		foreach ( $this->get_preconnect_urls( $processor ) as $preconnect_url ) {
			foreach ( $context->url_metric_group_collection as $group ) {
				if ( $group->get_element_max_intersection_ratio( $embed_wrapper_xpath ) < PHP_FLOAT_EPSILON ) {
					continue;
				}

				$context->link_collection->add_link(
					array(
						'rel'  => 'preconnect',
						'href' => $preconnect_url,
					),
					$group->get_minimum_viewport_width(),
					$group->get_maximum_viewport_width()
				);
			}
		}
	}

	/**
	 * Optimizes an embed based on whether it is displayed in any initial viewport.
	 *
	 * @since 0.4.1
	 *
	 * @param OD_Tag_Visitor_Context $context Tag visitor context, with the cursor currently at an embed block.
	 */
	private function lazy_load_embeds( OD_Tag_Visitor_Context $context ): void {
		$processor = $context->processor;

		// Lazy-loading can only be done once there are URL Metrics collected for both mobile and desktop.
		if (
			$context->url_metric_group_collection->get_first_group()->count() === 0
			||
			$context->url_metric_group_collection->get_last_group()->count() === 0
		) {
			return;
		}

		$embed_wrapper_xpath = self::get_embed_wrapper_xpath( $processor->get_xpath() );

		$max_intersection_ratio = $context->url_metric_group_collection->get_element_max_intersection_ratio( $embed_wrapper_xpath );
		if ( $max_intersection_ratio < PHP_FLOAT_EPSILON && embed_optimizer_update_markup( $processor, false ) && ! $this->added_lazy_script ) {
			$processor->append_body_html( wp_get_inline_script_tag( embed_optimizer_get_lazy_load_script(), array( 'type' => 'module' ) ) );
			$this->added_lazy_script = true;
		}
	}
}
