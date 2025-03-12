<?php
/**
 * Add new colorpicker field to "Add new Category" screen
 *
 * @param String $taxonomy
 *
 * @return void
 */
function horizon_news_add_new_category_colorpicker_field() {
	?>
	<div class="form-field term-colorpicker-wrap">
		<label for="term-colorpicker"><?php esc_html_e( 'Color', 'horizon-news' ); ?></label>
		<input name="_category_color" value="#3a0ca3" class="colorpicker" id="term-colorpicker" />
		<p><?php esc_html_e( 'Category color to be used on frontend.', 'horizon-news' ); ?></p>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'horizon_news_add_new_category_colorpicker_field' );

/**
 * Add new colopicker field to "Edit Category" screen
 *
 * @param WP_Term_Object $term
 *
 * @return void
 */
function horizon_news_edit_category_colorpicker_field( $term ) {
	$color = get_term_meta( $term->term_id, '_category_color', true );
	$color = ( ! empty( $color ) ) ? "#{$color}" : '#3a0ca3';
	?>
	<tr class="form-field term-colorpicker-wrap">
		<th scope="row">
			<label for="term-colorpicker"><?php esc_html_e( 'Color', 'horizon-news' ); ?></label>
		</th>
		<td>
			<input name="_category_color" value="<?php echo esc_attr( $color ); ?>" class="colorpicker" id="term-colorpicker" />
			<p class="description"><?php esc_html_e( 'Category color to be used on frontend.', 'horizon-news' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'horizon_news_edit_category_colorpicker_field' );

/**
 * Term Metadata - Save Created and Edited Term Metadata
 *
 * @param integer $term_id
 *
 * @return void
 */
function horizon_news_save_termmeta( $term_id ) {
	if ( isset( $_POST['_category_color'] ) && ! empty( $_POST['_category_color'] ) ) {
		update_term_meta( $term_id, '_category_color', sanitize_hex_color_no_hash( $_POST['_category_color'] ) );
	} else {
		delete_term_meta( $term_id, '_category_color' );
	}
}
add_action( 'created_category', 'horizon_news_save_termmeta' );
add_action( 'edited_category', 'horizon_news_save_termmeta' );

/**
 * Enqueue colorpicker styles and scripts.
 *
 * @return void
 */
function horizon_news_category_colorpicker_enqueue_scripts() {
	$screen = get_current_screen();
	if ( null !== $screen && 'edit-category' !== $screen->id ) {
		return;
	}
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'horizon_news_category_colorpicker_enqueue_scripts' );

/**
 * Print javascript to initialize the colorpicker
 *
 * @return void
 */
function horizon_news_colorpicker_init_inline_script() {
	$screen = get_current_screen();
	if ( null !== $screen && 'edit-category' !== $screen->id ) {
		return;
	}
	?>
	<script>
		jQuery(document).ready(function($) {
			$('.colorpicker').wpColorPicker();
		});
	</script>
	<?php
}
add_action( 'admin_print_footer_scripts', 'horizon_news_colorpicker_init_inline_script', 20 );
