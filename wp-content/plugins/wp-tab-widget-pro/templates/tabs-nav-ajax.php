<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tabs_count = count( $tabs );
if ( $tabs_count <= 1 ) {
	$tabs_count = 1;
} elseif( $tabs_count > 3 ) {
	$tabs_count = 4;
}
?>

<ul class="<?php echo $class_prefix; ?>-tabs <?php echo "has-$tabs_count-tabs"; ?>">
	<?php foreach ( $tabs as $tab => $label ): ?>
		<?php if (!empty($tab) && $tab != '0'): ?>
			<li class="tab_title">
				<a href="#" id="<?php echo $tab; ?>-tab">
					<?php if($label['icon'] != __('Select Tab Icon', 'wp-tab-widget')) { ?><i class="fa fa-<?php echo $label['icon']; ?>"></i> <?php } ?><?php echo $label['tab_name']; ?>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul> <!--end .tabs-->
<div class="clear"></div>
