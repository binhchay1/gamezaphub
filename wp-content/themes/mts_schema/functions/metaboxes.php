<?php
/**
 * Add a "Sidebar" selection metabox.
 */
function mts_add_sidebar_metabox() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'mts_sidebar_metabox',       // id.
			__( 'Sidebar', 'schema' ),   // title.
			'mts_inner_sidebar_metabox', // callback.
			$screen,                     // post_type.
			'side',                      // context (normal, advanced, side).
			'high'                       // priority (high, core, default, low).
		);
	}
}
add_action( 'add_meta_boxes', 'mts_add_sidebar_metabox' );

if ( ! function_exists( 'mts_inner_sidebar_metabox' ) ) {
	/**
	 * Print the box content.
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	function mts_inner_sidebar_metabox( $post ) {
			global $wp_registered_sidebars;

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'mts_inner_sidebar_metabox', 'mts_inner_sidebar_metabox_nonce' );

		/*
		* Use get_post_meta() to retrieve an existing value
		* from the database and use the value for the form.
		*/
		$custom_sidebar        = get_post_meta( $post->ID, '_mts_custom_sidebar', true );
		$sidebar_location      = get_post_meta( $post->ID, '_mts_sidebar_location', true );
		$content_layout        = get_post_meta( $post->ID, '_content_layout', true );
		$disable_header        = get_post_meta( $post->ID, '_disable_header', true );
		$disable_title         = get_post_meta( $post->ID, '_disable_title', true );
		$disable_breadcrumb    = get_post_meta( $post->ID, '_disable_breadcrumb', true );
		$disable_post_meta     = get_post_meta( $post->ID, '_disable_post_meta', true );
		$disable_related_posts = get_post_meta( $post->ID, '_disable_related_posts', true );
		$disable_author_box    = get_post_meta( $post->ID, '_disable_author_box', true );
		$disable_footer        = get_post_meta( $post->ID, '_disable_footer', true );

		// Select custom sidebar from dropdown.
		echo '<select name="mts_custom_sidebar" id="mts_custom_sidebar" style="margin-top:13px;margin-bottom: 13px;">';
		echo '<option value="" ' . selected( '', $custom_sidebar ) . '>-- ' . esc_html__( 'Default', 'schema' ) . ' --</option>';

		// Exclude built-in sidebars.
		$hidden_sidebars = array( 'sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header', 'shop-sidebar', 'product-sidebar' );

		foreach ( $wp_registered_sidebars as $sidebar ) {
			if ( ! in_array( $sidebar['id'], $hidden_sidebars, true ) ) {
				echo '<option value="' . esc_attr( $sidebar['id'] ) . '" ' . selected( $sidebar['id'], $custom_sidebar, false ) . '>' . esc_attr( $sidebar['name'] ) . '</option>';
			}
		}
		echo '<option value="mts_nosidebar" ' . selected( 'mts_nosidebar', $custom_sidebar ) . '>-- ' . esc_html__( 'No sidebar --', 'schema' ) . '</option>';
		echo '</select><br />';

		// Select single layout (left/right sidebar).
		echo '<div class="mts_sidebar_location_fields">';
		echo '<label for="mts_sidebar_location_default" style="display: inline-block; margin-right: 20px;margin-bottom:5px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_default" value=""' . checked( '', $sidebar_location, false ) . '>' . esc_html__( 'Default side', 'schema' ) . '</label>';
		echo '<label for="mts_sidebar_location_left" style="display: inline-block; margin-right: 20px;margin-bottom:5px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_left" value="left"' . checked( 'left', $sidebar_location, false ) . '>' . esc_html__( 'Left', 'schema' ) . '</label>';
		echo '<label for="mts_sidebar_location_right" style="display: inline-block; margin-right: 20px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_right" value="right"' . checked( 'right', $sidebar_location, false ) . '>' . esc_html__( 'Right', 'schema' ) . '</label>';
		echo '</div>';

		// Select content layout from dropdown.
		echo '<p style="margin-top:13px;"><strong>' . esc_html__( 'Content Layout', 'schema' ) . '</strong></p>';
		echo '<select name="content_layout" id="content_layout" style="margin-bottom: 10px;">';
		echo '<option value="default" ' . selected( 'default', $content_layout ) . '>' . esc_html__( 'Default', 'schema' ) . ' </option>';
		echo '<option value="boxed" ' . selected( 'boxed', $content_layout ) . '>' . esc_html__( 'Boxed', 'schema' ) . '</option>';
		echo '<option value="fullcontent" ' . selected( 'fullcontent', $content_layout ) . '>' . esc_html__( 'Full Width / Contained', 'schema' ) . '</option>';
		echo '<option value="fullstretched" ' . selected( 'fullstretched', $content_layout ) . '>' . esc_html__( 'Full Width / Stretched', 'schema' ) . '</option>';
		echo '</select><br />';

		// Disable sections.
		echo '<p style="margin-top:13px;margin-bottom:13px;"><strong>' . esc_html__( 'Disable Settings', 'schema' ) . '</strong></p>';
		echo '<div class="disable_sections">';
		echo '<label for="disable_header" style="display: block; margin-bottom: 5px;"><input type="checkbox" name="disable_header" id="disable_header" value="header"' . checked( 'header', $disable_header, false ) . '>' . esc_html__( 'Disable Header', 'schema' ) . '</label>';
		echo '<label for="disable_title" style="display: block; margin-bottom: 5px;"><input type="checkbox" name="disable_title" id="disable_title" value="title"' . checked( 'title', $disable_title, false ) . '>' . esc_html__( 'Disable Title', 'schema' ) . '</label>';
		echo '<label for="disable_breadcrumb" style="display: block; margin-bottom: 5px;"><input type="checkbox" name="disable_breadcrumb" id="disable_breadcrumb" value="breadcrumb"' . checked( 'breadcrumb', $disable_breadcrumb, false ) . '>' . esc_html__( 'Disable Breadcrumb', 'schema' ) . '</label>';
		// Show these options on pages only.
		$current_screen = get_current_screen();
		if ( 'post' === $current_screen->post_type ) {
			echo '<label for="disable_post_meta" style="display: block; margin-bottom: 5px;"><input type="checkbox" name="disable_post_meta" id="disable_post_meta" value="post_meta"' . checked( 'post_meta', $disable_post_meta, false ) . '>' . esc_html__( 'Disable Post Meta', 'schema' ) . '</label>';
			echo '<label for="disable_related_posts" style="display: block; margin-bottom: 5px;"><input type="checkbox" name="disable_related_posts" id="disable_related_posts" value="related_posts"' . checked( 'related_posts', $disable_related_posts, false ) . '>' . esc_html__( 'Disable Related Posts', 'schema' ) . '</label>';
			echo '<label for="disable_author_box" style="display: block; margin-bottom: 5px;"><input type="checkbox" name="disable_author_box" id="disable_author_box" value="author_box"' . checked( 'author_box', $disable_author_box, false ) . '>' . esc_html__( 'Disable Author Box', 'schema' ) . '</label>';
		}
		echo '<label for="disable_footer" style="display: block; margin-bottom: 5px;"><input type="checkbox" name="disable_footer" id="disable_footer" value="footer"' . checked( 'footer', $disable_footer, false ) . '>' . esc_html__( 'Disable Footer', 'schema' ) . '</label>';
		echo '</div>';

		?>
		<script type="text/javascript">
			jQuery(document).ready(function( $) {
				function mts_toggle_sidebar_location_fields() {
					$( '.mts_sidebar_location_fields').toggle(( $( '#mts_custom_sidebar').val() != 'mts_nosidebar'));
				}
				mts_toggle_sidebar_location_fields();
				$( '#mts_custom_sidebar').change(function() {
					mts_toggle_sidebar_location_fields();
				});
			});
		</script>
		<?php
	}
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 *
 * @return int
 */
function mts_save_custom_sidebar( $post_id ) {

	/**
	 * We need to verify this came from our screen and with proper authorization,
	 * because save_post can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['mts_inner_sidebar_metabox_nonce'] ) ) {  // PHPCS:ignore
		return $post_id;
	}

	$nonce = $_POST['mts_inner_sidebar_metabox_nonce'];  // PHPCS:ignore

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'mts_inner_sidebar_metabox' ) ) {
		return $post_id;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Check the user's permissions.
	if ( 'page' === $_POST['post_type'] ) {  // PHPCS:ignore

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	/* OK, its safe for us to save the data now. */

	// Sanitize user input.
	// PHPCS:disable
	$sidebar_name          = sanitize_text_field( $_POST['mts_custom_sidebar'] );
	$sidebar_location      = sanitize_text_field( $_POST['mts_sidebar_location'] );
	$content_layout        = sanitize_text_field( $_POST['content_layout'] );
	$disable_header        = sanitize_text_field( $_POST['disable_header'] );
	$disable_title         = sanitize_text_field( $_POST['disable_title'] );
	$disable_breadcrumb    = sanitize_text_field( $_POST['disable_breadcrumb'] );
	$disable_post_meta     = sanitize_text_field( $_POST['disable_post_meta'] );
	$disable_related_posts = sanitize_text_field( $_POST['disable_related_posts'] );
	$disable_author_box    = sanitize_text_field( $_POST['disable_author_box'] );
	$disable_footer        = sanitize_text_field( $_POST['disable_footer'] );
	// PHPCS:enable

	// Save checkbox fields.
	$checkbox_fields = array(
		'disable_header',
		'disable_title',
		'disable_breadcrumb',
		'disable_post_meta',
		'disable_related_posts',
		'disable_author_box',
		'disable_footer',
	);
	foreach ( $checkbox_fields as $checkbox_field ) {
		if ( ! empty( $_POST[ $checkbox_field ] ) ) {
			update_post_meta(
				$post_id,
				'_' . $checkbox_field,
				sanitize_text_field( wp_unslash( $_POST[ $checkbox_field ] ) )
			);
		} else {
			delete_post_meta( $post_id, '_' . $checkbox_field );
		}
	}

	// Update the meta field in the database.
	update_post_meta( $post_id, '_mts_custom_sidebar', $sidebar_name );
	update_post_meta( $post_id, '_mts_sidebar_location', $sidebar_location );
	update_post_meta( $post_id, '_content_layout', $content_layout );
}
add_action( 'save_post', 'mts_save_custom_sidebar' );


/**
 * Add "Post Template" selection meta box
 */
function mts_add_posttemplate_metabox() {
	add_meta_box(
		'mts_posttemplate_metabox',         // id.
		__( 'Template', 'schema' ),         // title.
		'mts_inner_posttemplate_metabox',   // callback.
		'post',                             // post_type.
		'side',                             // context (normal, advanced, side).
		'high'                              // priority (high, core, default, low).
	);
}
// add_action( 'add_meta_boxes', 'mts_add_posttemplate_metabox');

/**
 * Print the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function mts_inner_posttemplate_metabox( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'mts_inner_posttemplate_metabox', 'mts_inner_posttemplate_metabox_nonce' );

		/*
		* Use get_post_meta() to retrieve an existing value
		* from the database and use the value for the form.
		*/
		$posttemplate = get_post_meta( $post->ID, '_mts_posttemplate', true );

		// Select post template.
		echo '<select name="mts_posttemplate" style="margin-bottom: 10px;">';
		echo '<option value="" ' . selected( '', $posttemplate ) . '>' . esc_html__( 'Default Post Template', 'schema' ) . '</option>';
		echo '<option value="parallax" ' . selected( 'parallax', $posttemplate ) . '>' . esc_html__( 'Parallax Template', 'schema' ) . '</option>';
		echo '<option value="zoomout" ' . selected( 'zoomout', $posttemplate ) . '>' . esc_html__( 'Zoom Out Effect Template', 'schema' ) . '</option>';
		echo '</select><br />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 *
 * @return int
 */
function mts_save_posttemplate( $post_id ) {

	/**
	 * We need to verify this came from our screen and with proper authorization,
	 * because save_post can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['mts_inner_posttemplate_metabox_nonce'] ) ) // PHPCS:ignore
	return $post_id;

	$nonce = $_POST['mts_inner_posttemplate_metabox_nonce']; // PHPCS:ignore

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'mts_inner_posttemplate_metabox' ) ) {
		return $post_id;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Check the user's permissions.
	if ( 'page' === $_POST['post_type'] ) {  // PHPCS:ignore

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	/* OK, its safe for us to save the data now. */

	// Sanitize user input.
	$posttemplate = sanitize_text_field( $_POST['mts_posttemplate'] );  // PHPCS:ignore

	// Update the meta field in the database.
	update_post_meta( $post_id, '_mts_posttemplate', $posttemplate );
}
add_action( 'save_post', 'mts_save_posttemplate' );

/**
 * Add "Page Header Animation" metabox.
 */
function mts_add_postheader_metabox() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'mts_postheader_metabox',           // id.
			__( 'Header Animation', 'schema' ), // title.
			'mts_inner_postheader_metabox',     // callback.
			$screen,                            // post_type.
			'side',                             // context (normal, advanced, side).
			'high'                              // priority (high, core, default, low).
		);
	}
}
add_action( 'add_meta_boxes', 'mts_add_postheader_metabox' );

/**
 * Print the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function mts_inner_postheader_metabox( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'mts_inner_postheader_metabox', 'mts_inner_postheader_metabox_nonce' );

		/*
		* Use get_post_meta() to retrieve an existing value
		* from the database and use the value for the form.
		*/
		$postheader = get_post_meta( $post->ID, '_mts_postheader', true );

		// Select post header effect.
		echo '<select name="mts_postheader" style="margin-bottom: 10px;">';
		echo '<option value="" ' . selected( '', $postheader ) . '>' . esc_html__( 'None', 'schema' ) . '</option>';
		echo '<option value="parallax" ' . selected( 'parallax', $postheader ) . '>' . esc_html__( 'Parallax Effect', 'schema' ) . '</option>';
		echo '<option value="zoomout" ' . selected( 'zoomout', $postheader ) . '>' . esc_html__( 'Zoom Out Effect', 'schema' ) . '</option>';
		echo '</select><br />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 *
 * @return int
 *
 * @see mts_get_post_header_effect
 */
function mts_save_postheader( $post_id ) {

	/**
	 * We need to verify this came from our screen and with proper authorization,
	 * because save_post can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['mts_inner_postheader_metabox_nonce'] ) ) {  // PHPCS:ignore
		return $post_id;
	}

	$nonce = $_POST['mts_inner_postheader_metabox_nonce'];  // PHPCS:ignore

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'mts_inner_postheader_metabox' ) ) {
		return $post_id;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Check the user's permissions.
	if ( 'page' === $_POST['post_type'] ) {  // PHPCS:ignore

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	/* OK, its safe for us to save the data now. */

	// Sanitize user input.
	$postheader = sanitize_text_field( $_POST['mts_postheader'] );  // PHPCS:ignore

	// Update the meta field in the database.
	update_post_meta( $post_id, '_mts_postheader', $postheader );
}
add_action( 'save_post', 'mts_save_postheader' );
