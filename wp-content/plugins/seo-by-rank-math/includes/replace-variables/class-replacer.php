<?php
/**
 * Variable replacement functionality.
 *
 * @since      1.0.33
 * @package    RankMath
 * @subpackage RankMath\Replace_Variables
 * @author     Rank Math <support@rankmath.com>
 *
 * @copyright Copyright (C) 2008-2019, Yoast BV
 * The following code is a derivative work of the code from the Yoast(https://github.com/Yoast/wordpress-seo/), which is licensed under GPL v3.
 */

namespace RankMath\Replace_Variables;

use RankMath\Helper;
use RankMath\Helpers\Str;
use RankMath\Paper\Paper;

defined( 'ABSPATH' ) || exit;

/**
 * Replacer class.
 */
class Replacer {

	/**
	 * Do not process the same string over and over again.
	 *
	 * @var array
	 */
	public static $replacements_cache = [];

	/**
	 * Non-cacheable replacements.
	 *
	 * @var array
	 */
	public static $non_cacheable_replacements;

	/**
	 * Default post data.
	 *
	 * @var array
	 */
	public static $defaults = [
		'ID'            => '',
		'name'          => '',
		'post_author'   => '',
		'post_content'  => '',
		'post_date'     => '',
		'post_excerpt'  => '',
		'post_modified' => '',
		'post_title'    => '',
		'taxonomy'      => '',
		'term_id'       => '',
		'term404'       => '',
		'filename'      => '',
	];

	/**
	 * Arguments.
	 *
	 * @var object
	 */
	public static $args;

	/**
	 * Process post content once.
	 *
	 * @var array
	 */
	public static $content_processed = [];

	/**
	 * Exclude variables.
	 *
	 * @var array
	 */
	public $exclude = [];

	/**
	 *  Replace `%variables%` with context-dependent value.
	 *
	 * @param string $variable The string containing the %variables%.
	 * @param array  $args     Context object, can be post, taxonomy or term.
	 * @param array  $exclude  Excluded variables won't be replaced.
	 *
	 * @return string
	 */
	public function replace( $variable, $args = [], $exclude = [] ) {
		$variable = wp_strip_all_tags( $variable );

		// Bail early.
		if ( ! Str::contains( '%', $variable ) ) {
			return $variable;
		}

		if ( Str::ends_with( ' %sep%', $variable ) ) {
			$variable = substr( $variable, 0, -5 );
		}

		$this->pre_replace( $args, $exclude );
		$replacements = $this->set_up_replacements( $variable );

		/**
		 * Filter: Allow customizing the replacements.
		 *
		 * @param array  $replacements The replacements.
		 * @param object $args The object some of the replacement values might come from,
		 *                    could be a post, taxonomy or term.
		 */
		$replacements = apply_filters( 'rank_math/replacements', $replacements, self::$args );

		// Do the replacements.
		if ( is_array( $replacements ) && [] !== $replacements ) {
			$variable = str_replace( array_keys( $replacements ), array_values( $replacements ), $variable );
		}

		if ( isset( $replacements['%sep%'] ) && Str::is_non_empty( $replacements['%sep%'] ) ) {
			$q_sep    = preg_quote( $replacements['%sep%'], '`' );
			$variable = preg_replace( '`' . $q_sep . '(?:\s*' . $q_sep . ')*`u', $replacements['%sep%'], $variable );
		}

		// Remove excess whitespace.
		return preg_replace( '[\s\s+]', ' ', $variable );
	}

	/**
	 * Run prior to replacement.
	 *
	 * @param array $args    Context object, can be post, taxonomy or term.
	 * @param array $exclude Excluded variables won't be replaced.
	 */
	private function pre_replace( $args, $exclude ) {
		if ( is_array( $exclude ) ) {
			$this->exclude = $exclude;
		}

		self::$args = (object) array_merge( self::$defaults, (array) $args );
		$this->process_content();
	}

	/**
	 * Process content only once, because it's expensive.
	 *
	 * @return void
	 */
	private function process_content() {
		if ( ! isset( self::$content_processed[ self::$args->ID ]['post_content'] ) ) {
			self::$content_processed[ self::$args->ID ]['post_content'] = Paper::should_apply_shortcode() ? do_shortcode( self::$args->post_content ) : Helper::strip_shortcodes( self::$args->post_content );
			self::$content_processed[ self::$args->ID ]['post_excerpt'] = Paper::should_apply_shortcode() ? do_shortcode( self::$args->post_excerpt ) : Helper::strip_shortcodes( self::$args->post_excerpt );
		}

		self::$args->post_content = self::$content_processed[ self::$args->ID ]['post_content'];
		self::$args->post_excerpt = self::$content_processed[ self::$args->ID ]['post_excerpt'];
	}

	/**
	 * Get the replacements for the variables.
	 *
	 * @param string $variable String to parse for variables.
	 *
	 * @return array Retrieved replacements.
	 */
	private function set_up_replacements( $variable ) {
		if ( $this->has_cache( $variable ) ) {
			return $this->get_cache( $variable );
		}

		$replacements = [];
		if ( ! preg_match_all( '/%(([a-z0-9_-]+)\(([^)]*)\)|[^\s]+)%/iu', $variable, $matches ) ) {
			$this->set_cache( $variable, $replacements );
			return $replacements;
		}

		foreach ( $matches[1] as $index => $variable_id ) {
			$value = $this->get_variable_value( $matches, $index, $variable_id );
			if ( false !== $value ) {
				$replacements[ $matches[0][ $index ] ] = $value;
			}

			unset( $value );
		}

		$this->set_cache( $variable, $replacements );
		return $replacements;
	}

	/**
	 * Get non-cacheable variables.
	 *
	 * @return array
	 */
	private function get_non_cacheable_variables() {
		if ( ! is_null( self::$non_cacheable_replacements ) ) {
			return self::$non_cacheable_replacements;
		}

		$non_cacheable = [];
		foreach ( rank_math()->variables->get_replacements() as $variable ) {
			if ( ! $variable->is_cacheable() ) {
				$non_cacheable[] = $variable->get_id();
			}
		}

		/**
		 * Filter: Allow changing the non-cacheable variables.
		 *
		 * @param array $non_cacheable The non-cacheable variable IDs.
		 */
		self::$non_cacheable_replacements = apply_filters( 'rank_math/replacements/non_cacheable', $non_cacheable );

		return self::$non_cacheable_replacements;
	}

	/**
	 * Check if we have cache for a string.
	 *
	 * @param string $variable String to check.
	 *
	 * @return bool
	 */
	private function has_cache( $variable ) {
		return isset( self::$replacements_cache[ md5( $variable ) ] );
	}

	/**
	 * Get cache for a string. Handles non-cacheable variables.
	 *
	 * @param string $variable String to get cache for.
	 *
	 * @return array
	 */
	private function get_cache( $variable ) {
		$non_cacheable = $this->get_non_cacheable_variables();
		$replacements  = self::$replacements_cache[ md5( $variable ) ];
		if ( empty( $non_cacheable ) ) {
			return $replacements;
		}

		foreach ( $replacements as $key => $value ) {
			$id = explode( '(', trim( $key, '%' ) )[0];
			if ( ! in_array( $id, $non_cacheable, true ) ) {
				continue;
			}

			$var_args = '';
			$parts    = explode( '(', trim( $key, '%)' ) );
			if ( isset( $parts[1] ) ) {
				$var_args = $this->normalize_args( $parts[1] );
			}

			$replacements[ $key ] = $this->get_variable_by_id( $id, $var_args )->run_callback( $var_args, self::$args );
		}

		return $replacements;
	}

	/**
	 * Set cache for a string.
	 *
	 * @param string $variable String to set cache for.
	 *
	 * @param array  $cache  Cache to set.
	 */
	private function set_cache( $variable, $cache ) {
		self::$replacements_cache[ md5( $variable ) ] = $cache;
	}

	/**
	 * Get variable value.
	 *
	 * @param array  $matches Regex matches found in the string.
	 * @param int    $index   Index of the matched.
	 * @param string $id      Variable id.
	 *
	 * @return mixed
	 */
	private function get_variable_value( $matches, $index, $id ) {
		// Don't set up excluded replacements.
		if ( isset( $matches[0][ $index ] ) && in_array( $matches[0][ $index ], $this->exclude, true ) ) {
			return false;
		}

		$has_args = ! empty( $matches[2][ $index ] ) && ! empty( $matches[3][ $index ] );
		$id       = $has_args ? $matches[2][ $index ] : $id;
		$var_args = $has_args ? $this->normalize_args( $matches[3][ $index ] ) : [];
		$variable = $this->get_variable_by_id( $id, $var_args );

		if ( is_null( $variable ) ) {
			return rank_math()->variables->remove_non_replaced ? '' : false;
		}

		return $variable->run_callback( $var_args, self::$args );
	}

	/**
	 * Find variable.
	 *
	 * @param string $id   Variable id.
	 * @param array  $args Array of arguments.
	 *
	 * @return Variable|null
	 */
	private function get_variable_by_id( $id, $args ) {
		if ( ! isset( rank_math()->variables ) ) {
			return null;
		}

		$replacements = rank_math()->variables->get_replacements();
		if ( isset( $replacements[ $id ] ) ) {
			return $replacements[ $id ];
		}

		if ( ! empty( $args ) && isset( $replacements[ $id . '_args' ] ) ) {
			return $replacements[ $id . '_args' ];
		}

		return null;
	}

	/**
	 * Convert arguments string to arguments array.
	 *
	 * @param  string $variable The string that needs to be converted.
	 *
	 * @return array
	 */
	private function normalize_args( $variable ) {
		$variable = wp_specialchars_decode( $variable );
		if ( ! Str::contains( '=', $variable ) ) {
			return $variable;
		}

		return wp_parse_args( $variable, [] );
	}
}
