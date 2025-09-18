<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="inside">
	<?php foreach ( $tabs as $key => $value ): ?>
		<?php if ( !empty( $key ) && $key != '0'): ?>
			<div class="wpt_acc_title">
				<a href="#" id="<?php echo $key; ?>-tab">
					<?php if($value['icon'] != __('Select Tab Icon', 'wp-tab-widget')) { ?><i class="fa fa-<?php echo $value['icon']; ?>"></i> <?php } ?><?php echo $value['tab_name']; ?>
				</a>
			</div>
			<div id="<?php echo $key; ?>-tab-content" class="tab-content">
			<?php
			if ( strpos( $key, 'comment' ) != false || strpos( $key, 'tags' ) != false ) {
				echo '<ul></ul>';
			}
			?>
			</div><!--end .tab-content-->
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="clear"></div>
</div> <!--end .inside -->
<div class="clear"></div>