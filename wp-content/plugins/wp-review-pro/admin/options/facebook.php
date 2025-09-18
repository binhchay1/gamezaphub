<?php
/**
 * Facebook options
 *
 * @package WP_Review
 */

$app_id       = wp_review_option( 'facebook_app_id' );
$app_secret   = wp_review_option( 'facebook_app_secret' );
$user_token   = wp_review_option( 'facebook_user_token' );
$user_id      = wp_review_option( 'facebook_user_id' );
$expired_time = wp_review_option( 'facebook_cache_expired_time', 7 );
?>
<div class="wp-review-field no-flex">
	<?php
	printf(
		/* translators: Facebook App link. */
		esc_html__( '%s to create Facebook App. Remember to add your domain to app.', 'wp-review' ),
		'<a href="https://mythemeshop.com/kb/wp-review-pro/facebook-reviews/" target="_blank">' . esc_html__( 'Click here', 'wp-review' ) . '</a>'
	);
	?>
</div>

<div class="wp-review-field">
	<div class="wp-review-field-label">
		<label for="wp_review_facebook_app_id"><?php esc_html_e( 'App ID', 'wp-review' ); ?></label>
	</div>

	<div class="wp-review-field-option">
		<input name="wp_review_options[facebook_app_id]" id="wp_review_facebook_app_id" type="text" value="<?php echo esc_attr( $app_id ); ?>" class="all-options">
	</div>
</div>

<div class="wp-review-field">
	<div class="wp-review-field-label">
		<label for="wp_review_facebook_app_secret"><?php esc_html_e( 'App secret', 'wp-review' ); ?></label>
	</div>

	<div class="wp-review-field-option">
		<input name="wp_review_options[facebook_app_secret]" id="wp_review_facebook_app_secret" class="large-text all-options" type="password" value="<?php echo esc_attr( $app_secret ); ?>">
	</div>
</div>

<p class="description"><?php esc_html_e( 'Please re-generate access token in shortcodes and widgets each time you change App ID or App secret.', 'wp-review' ); ?></p>

<div class="wp-review-field">
	<div class="wp-review-field-label">
		<label for="wp_review_facebook_cache_expired_time"><?php esc_html_e( 'Cache expired time', 'wp-review' ); ?></label>
	</div>

	<div class="wp-review-field-option">
		<input name="wp_review_options[facebook_cache_expired_time]" id="wp_review_facebook_cache_expired_time" type="number" value="<?php echo intval( $expired_time ); ?>" min="0" step="1">
		<?php esc_html_e( 'day(s)', 'wp-review' ); ?>
	</div>
</div>
