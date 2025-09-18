<?php
/**
 * Enqueue WP Pointer.
 */
function mts_schema_pointer_header() {
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	if ( ! in_array( 'mts_schema_pointer', $dismissed, true ) ) {
		add_action( 'admin_print_footer_scripts', 'mts_schema_pointer_footer' );

		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_style( 'wp-pointer' );
	}
}
add_action( 'admin_enqueue_scripts', 'mts_schema_pointer_header' );

if ( ! function_exists( 'mts_schema_pointer_footer' ) ) {
	/**
	 * Display WP Pointer configuration.
	 */
	function mts_schema_pointer_footer() {
		$pointer_content  = '<h3>' . esc_html__( 'Awesomeness!', 'schema' ) . '</h3>';
		$pointer_content .= '<p>' . esc_html__( 'You have just Installed Schema WordPress Theme by MyThemeShop.', 'schema' ) . '</p>';
		$pointer_content .= '<p>' . wp_kses( esc_html__( 'You can Trigger The Awesomeness using Amazing Option Panel in Theme Options.', 'schema' ), array( 'strong' => '' ) ) . '</p>';

		if ( ! MTS_THEME_WHITE_LABEL ) {
			$pointer_content .= '<p>' . esc_html__( 'If you face any problem, head over to', 'schema' ) . ' <a href="http://community.mythemeshop.com/">' . esc_html__( 'Support Forums', 'schema' ) . '</a></p>';
		}
	?>
	<script type="text/javascript">// <![CDATA[
	jQuery(document).ready(function($) {
		$( '#menu-appearance' ).pointer({
			content: '<?php echo wp_kses_post( $pointer_content ); ?>',
			position: {
				edge: 'left',
				align: 'center'
			},
			close: function() {
				$.post( ajaxurl, {
					pointer: 'mts_schema_pointer',
					action: 'dismiss-wp-pointer'
				});
			}
		}).pointer( 'open' );
	});
	// ]]></script>
	<?php
	}
}

?>
