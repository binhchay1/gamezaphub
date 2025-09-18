<?php
/*
 *
 * Available variables:
 *  	$terms_orderby
 *  	$terms_order
 *  	$terms_num
 *
 *  	$widget_number
 *  	$tab_key
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<ul class="wptp-list">
	<?php
	$cats = get_categories(
		apply_filters(
			'wpt_cats_query_args',
			array(
				'parent' => 0,
				'orderby' => $terms_orderby,
				'order' => $terms_order,
				'number' => $terms_num
			),
			$widget_number,
			$tab_key
		)
	);
	if($cats) {
		foreach ($cats as $cat): ?>
			<li class="wptp-list-item"><a href="<?php echo get_term_link($cat); ?>"><?php echo $cat->name; ?></a></li>
			<?php
		endforeach;
	} else {
		_e('No Categories found', 'wp-tab-widget');
	}
	?>
</ul>
<div class="clear"></div>