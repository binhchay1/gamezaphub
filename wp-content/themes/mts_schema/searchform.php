<?php
/**
 * The template for displaying the search form.
 *
 * @package Schema
 */

$mts_options = get_option( MTS_THEME_NAME );
?>

<form method="get" id="searchform" class="search-form" action="<?php echo esc_attr( home_url() ); ?>" _lpchecked="1">
	<fieldset>
		<input type="text" name="s" id="s" value="<?php the_search_query(); ?>" placeholder="<?php esc_html_e( 'Search the site', 'schema' ); ?>" <?php if ( ! empty( $mts_options['mts_ajax_search'] ) ) echo ' autocomplete="off"'; // PHPCS:ignore ?> />
		<button id="search-image" class="sbutton" type="submit" value="<?php esc_html_e( 'Search', 'schema' ); ?>"><?php esc_html_e( 'Search', 'schema' ); ?></button>
	</fieldset>
</form>
