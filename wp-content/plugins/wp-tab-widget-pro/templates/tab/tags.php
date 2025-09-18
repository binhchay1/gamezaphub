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
<ul>
	<?php
	$tags = get_tags(
		apply_filters(
			'wpt_tags_query_args',
			array(
				'get' => 'all',
				'orderby' => $terms_orderby,
				'order' => $terms_order,
				'number' => $terms_num
			),
			$widget_number,
			$tab_key
		)
	);
	if($tags) {
		foreach ($tags as $tag): ?>
			<li><a href="<?php echo get_term_link($tag); ?>"><?php echo $tag->name; ?></a></li>
			<?php
		endforeach;
	} else {
		_e('No Tags found', 'wp-tab-widget');
	}
	?>
</ul>