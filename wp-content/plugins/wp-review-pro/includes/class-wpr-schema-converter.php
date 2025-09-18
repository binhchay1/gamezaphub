<?php
/**
 * Class WPR_Schema_Converter
 */

/**
 * Class WPR_Schema_Converter
 */
class WPR_Schema_Converter {

	/**
	 * Type mapping.
	 *
	 * @var array
	 */
	protected $mapping = array();

	/**
	 * Converts schema.
	 *
	 * @param string $source Source type.
	 * @param string $dest   Destination type.
	 * @param int    $page   Page.
	 *
	 * @return array|WP_Error
	 */
	public function convert( $source, $dest, $page = 1 ) {
		if ( $page < 2 ) {
			delete_option( 'wp_review_converted_posts' );
		}

		$converted   = get_option( 'wp_review_converted_posts', array() );
		$per_request = 10;
		$result      = array(
			'source'    => $source,
			'dest'      => $dest,
			'page'      => $page,
			'converted' => $converted,
			'count'     => count( $converted ),
			'skip'      => 0,
			'done'      => false,
			'found'     => 0,
		);

		$meta_query = array(
			array(
				'key'   => 'wp_review_schema',
				'value' => $source,
			)
		);

		if ( 'Thing' === $source ) {
			$meta_query['relation'] = 'OR';
			$meta_query[]           = array(
				'key'     => 'wp_review_schema',
				'compare' => 'NOT EXISTS',
			);
		}

		$query = new WP_Query(
			array(
				'post_type'              => 'any',
				'posts_per_page'         => $per_request,
				'paged'                  => $page,
				'cache_results'          => false,
				'update_post_term_cache' => false,
				'fields'                 => 'ids',
				'ignore_sticky_posts'    => true,
				'meta_query'             => $meta_query,
			)
		);

		if ( ! $query->have_posts() ) {
			$result['done'] = true;
			return $result;
		}

		$post_ids = $query->get_posts();
		foreach ( $post_ids as $post_id ) {
			if ( $this->convert_post( $post_id, $source, $dest ) ) {
				$result['count']++;
				$result['converted'][] = $post_id;
			} else {
				$result['skip']++;
			}
		}

		if ( count( $post_ids ) < $per_request ) {
			$result['done'] = true;
		} else {
			$result['page']++;
		}

		$result['found'] = ( $page - 1 ) * $per_request + count( $post_ids );

		update_option( 'wp_review_converted_posts', $result['converted'] );

		return $result;
	}

	protected function convert_post( $post_id, $source, $dest ) {
		if ( 'Thing' === $source ) {
			return update_post_meta( $post_id, 'wp_review_schema', $dest );
		}
		$mapping = $this->get_mapping();
		if ( empty( $mapping[ $source ][ $dest ] ) ) {
			return false;
		}

		$schema_data = get_post_meta( $post_id, 'wp_review_schema_options', true );
		if ( ! isset( $schema_data[ $source ] ) || ! is_array( $schema_data[ $source ] ) ) {
			return false;
		}

		if ( ! isset( $schema_data[ $dest ] ) || ! is_array( $schema_data[ $dest ] ) ) {
			$schema_data[ $dest ] = array();
		}

		foreach ( $mapping[ $source ][ $dest ] as $key => $value ) {
			if ( ! empty( $schema_data[ $source ][ $key ] ) ) {
				$schema_data[ $dest ][ $value ] = $schema_data[ $source ][ $key ];
			}
		}

		update_post_meta( $post_id, 'wp_review_schema_options', $schema_data );
		update_post_meta( $post_id, 'wp_review_schema', $dest );

		return true;
	}

	public function get_mapping() {
		if ( ! empty( $this->mapping ) ) {
			return $this->mapping;
		}

		return array(
			'Article'  => array(
				'Book' => array(
					'headline'       => 'name',
					'description'    => 'description',
					'image'          => 'image',
					'author'         => 'author',
					'publisher'      => 'publisher',
					'publisher_logo' => 'publisher_logo',
				),
				'Course' => array(
					'headline'       => 'name',
					'description'    => 'description',
					'image'          => 'image',
					'author'         => 'author',
					'publisher'      => 'provider',
					'publisher_logo' => 'provider_logo',
				),
				'CreativeWorkSeason' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
					'publisher'   => 'productionCompany',
				),
				'CreativeWorkSeries' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
				'Episode' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
					'publisher'   => 'productionCompany',
				),
				'Event' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'publisher'   => 'performer',
				),
				'Game' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
				'LocalBusiness' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
				),
				'Movie' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
				'MusicPlaylist' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
				'MusicRecording' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
				'Organization' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
				),
				'Product' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
				),
				'Recipe' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
				'Restaurant' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
				),
				'SoftwareApplication' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
				'Store' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
				),
				'TVSeries' => array(
					'headline'    => 'name',
					'description' => 'description',
					'image'       => 'image',
					'author'      => 'author',
				),
			),
			'Painting' => array(
				'Book' => array(
					'name'          => 'name',
					'url'           => 'url',
					'image'         => 'image',
					'author'        => 'author',
					'datePublished' => 'datePublished',
				),
				'Course' => array(
					'name'   => 'name',
					'url'    => 'url',
					'image'  => 'image',
					'author' => 'author',
				),
				'CreativeWorkSeason' => array(
					'name'          => 'name',
					'url'           => 'url',
					'image'         => 'image',
					'author'        => 'author',
					'datePublished' => 'startDate',
				),
				'CreativeWorkSeries' => array(
					'name'          => 'name',
					'url'           => 'url',
					'image'         => 'image',
					'author'        => 'author',
					'datePublished' => 'startDate',
				),
				'Episode' => array(
					'name'        => 'name',
					'url'         => 'url',
					'image'       => 'image',
					'author'      => 'author',
					'genre'       => 'genre',
					'dateCreated' => 'dateCreated',
				),
				'Event' => array(
					'name'        => 'name',
					'url'         => 'url',
					'image'       => 'image',
					'dateCreated' => 'startDate',
				),
				'Game' => array(
					'name'   => 'name',
					'url'    => 'url',
					'image'  => 'image',
					'author' => 'author',
				),
				'LocalBusiness' => array(
					'name'  => 'name',
					'url'   => 'url',
					'image' => 'image',
				),
				'Movie' => array(
					'name'        => 'name',
					'url'         => 'url',
					'image'       => 'image',
					'author'      => 'author',
					'dateCreated' => 'dateCreated',
					'genre'       => 'genre',
				),
				'MusicPlaylist' => array(
					'name'   => 'name',
					'url'    => 'url',
					'image'  => 'image',
					'author' => 'author',
					'genre'  => 'genre',
				),
				'MusicRecording' => array(
					'name'   => 'name',
					'url'    => 'url',
					'image'  => 'image',
					'author' => 'author',
					'genre'  => 'genre',
				),
				'Organization' => array(
					'name'  => 'name',
					'url'   => 'url',
					'image' => 'image',
				),
				'Product' => array(
					'name'  => 'name',
					'url'   => 'url',
					'image' => 'image',
				),
				'Recipe' => array(
					'name'   => 'name',
					'url'    => 'url',
					'image'  => 'image',
					'author' => 'author',
				),
				'Restaurant' => array(
					'name'  => 'name',
					'url'   => 'url',
					'image' => 'image',
				),
				'SoftwareApplication' => array(
					'name'   => 'name',
					'url'    => 'url',
					'image'  => 'image',
					'author' => 'author',
				),
				'Store' => array(
					'name'  => 'name',
					'url'   => 'url',
					'image' => 'image',
				),
				'TVSeries' => array(
					'name'   => 'name',
					'url'    => 'url',
					'image'  => 'image',
					'author' => 'author',
				),
			),
			'Place'    => array(
				'Book' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Course' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'CreativeWorkSeason' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'CreativeWorkSeries' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Episode' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Event' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Game' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'LocalBusiness' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Movie' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'MusicPlaylist' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'MusicRecording' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Organization' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Product' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Recipe' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Restaurant' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'SoftwareApplication' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Store' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'TVSeries' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
			),
			'WebSite'  => array(
				'Book' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Course' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'CreativeWorkSeason' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'CreativeWorkSeries' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Episode' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Event' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Game' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'LocalBusiness' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Movie' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'MusicPlaylist' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'MusicRecording' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Organization' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Product' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Recipe' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Restaurant' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'SoftwareApplication' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'Store' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
				'TVSeries' => array(
					'name'        => 'name',
					'url'         => 'url',
					'description' => 'description',
					'image'       => 'image',
				),
			),
		);
	}
}
