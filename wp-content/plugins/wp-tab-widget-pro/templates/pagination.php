<?php
/*
 *
 * Available variables:
 *  	$page
 *  	$last_page
 *		$class_prefix
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( 'wptp' === $class_prefix ) { // New numbered pagination
?>
<ul class="wptp-pagination">
<?php 
	global $wp_query;
	$big = 999999999; // need an unlikely integer
	$num_links = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => $page,
		'total' => $last_page,
		'type' => 'array',
		'prev_next' => true,
		'prev_text' => '<i class="fa fa-angle-left wptp-button-icon wptp-prev-icon"></i><span class="wptp-button-text">'.__('Prev', 'wp-tab-widget').'</span>',
		'next_text' => '<span class="wptp-button-text">'.__('Next', 'wp-tab-widget').'</span><i class="fa fa-angle-right wptp-button-icon wptp-next-icon"></i>',
		'end_size'  => 1,
		'mid_size' => 0,
	));

	if ( $num_links ) {
		foreach ($num_links as $links) {
			if ( strpos( $links, "prev" ) || strpos( $links, "next" ) )
				echo '<li class="wptp-button">' . $links . "</li>\n";
			else
				echo '<li class="wptp-number">' . $links . "</li>\n";
		}
	} 
?>
</ul>
<?php
} else { // Old pagination
?>
	<div class="wpt-pagination">     
		<?php if ( $page > 1 ) : ?>               
			<a href="#" class="previous"><span><?php _e('&laquo; Previous', 'wp-tab-widget'); ?></span></a>      
		<?php endif; ?>        
		<?php if ( $page != $last_page ) : ?>     
			<a href="#" class="next"><span><?php _e('Next &raquo;', 'wp-tab-widget'); ?></span></a>      
		<?php endif; ?>          
	</div>                   
<?php
}
?>
<div class="clear"></div>
<input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />