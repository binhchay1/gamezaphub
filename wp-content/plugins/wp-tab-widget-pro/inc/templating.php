<?php

/**
 * Get template
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function wpt_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	$located = wpt_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.0' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'wpt_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'wpt_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'wpt_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Like wpt_get_template, but returns the HTML instead of outputting.
 * @see wpt_get_template
 * @since 1.0
 */
function wpt_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	wpt_get_template( $template_name, $args, $template_path, $default_path );
	return ob_get_clean();
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function wpt_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = wpt_template_path();
	}

	if ( ! $default_path ) {
		$default_path = wpt_plugin_path() . '/templates/';
	}
	
	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template/
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'wpt_locate_template', $template, $template_name, $template_path );
}

/**
 * Get the plugin path.
 * @return string
 */
function wpt_plugin_path() {
	return untrailingslashit( WPT_PLUGIN_DIR );
}

/**
 * Get the template path.
 * @return string
 */
function wpt_template_path() {
	return apply_filters( 'wpt_template_path', 'wp-tab-widget/' );
}