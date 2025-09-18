<?php
/**
 * Convert schema options
 *
 * @package WP_Review
 */

$supported  = wp_review_get_supported_schema_types();
$deprecated = wp_review_get_deprecated_schema_types();

unset( $supported['none'] );
$supported  = array_keys( $supported );
$deprecated = array_keys( $deprecated );
?>
<div class="notice notice-info inline">
	<p>
		<?php
		printf(
			esc_html__( 'In recent %s, it removed support for adding reviews in Article, Painting, Place, Thing and WebSite Schema type, please use this tool to convert all the old posts into supported Schema types or you can manually edit each post.', 'wp-review' ),
			'<a href="https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html" target="_blank">' . esc_html__( 'Google updates', 'wp-review' ) . '</a>'
		);
		?>
	</p>
</div>

<div class="wp-review-field">
	<div class="wp-review-field-label">
		<label><?php esc_html_e( 'Convert option', 'wp-review' ); ?></label>
	</div>

	<div class="wp-review-field-option">
		<?php esc_html_e( 'From', 'wp-review' ); ?>
		<select id="wpr-convert-schema-source">
			<?php foreach ( $deprecated as $name ) : ?>
				<option value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>

		<?php esc_html_e( 'To', 'wp-review' ); ?>
		<select id="wpr-convert-schema-dest">
			<?php foreach ( $supported as $name ) : ?>
				<option value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>

		<!-- <select id="wpr-convert-schema-option" style="width: auto;">
			<option value=""><?php esc_html_e( 'Don\'t convert Author Rating schema', 'wp-review' ); ?></option>
			<option value="visitor"><?php esc_html_e( 'Convert Author Review Rating to Visitor Aggregate rating', 'wp-review' ); ?></option>
			<option value="comment"><?php esc_html_e( 'Convert Author Review Rating to Comment Aggregate rating', 'wp-review' ); ?></option>
		</select> -->

		<p>
			<?php submit_button( __( 'Convert', 'wp-review' ), 'large', 'wp-review-convert-schema', false ); ?>
		</p>

		<p class="description" id="wp-review-doing-convert-schema" style="display: none;"><?php esc_html_e( 'Converting...', 'wp-review' ); ?></p>
		<p class="description" id="wp-review-done-convert-schema" style="display: none;">
			<?php
			printf(
				esc_html__( 'Converted %s. Please edit them manually to add missing fields recommended by Google.', 'wp-review' ),
				'<a href="' . admin_url( 'edit.php?converted' ) . '" target="_blank">' . sprintf( esc_html__( '%s post(s)', 'wp-review' ), '<span id="wp-review-converted-count">0</span>' ) . '</a>'

			);
			?>
		</p>

		<input type="hidden" id="wp-review-convert-schema-nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp_review_convert_schema' ) ); ?>">
	</div>
</div>
