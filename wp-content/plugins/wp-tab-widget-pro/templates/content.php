<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="inside">
	<?php $i=0; foreach ( $tabs as $key => $value ): ?>
		<?php if ( !empty( $key ) && $key != '0'): ?>
			<div class="wpt_acc_title">
				<a href="#" id="<?php echo $key; ?>-tab">
					<?php if($value['icon'] != __('Select Tab Icon', 'wp-tab-widget')) { ?><i class="fa fa-<?php echo $value['icon']; ?>"></i> <?php } ?><?php echo $value['tab_name']; ?>
				</a>
			</div>
			<div id="<?php echo $key; ?>-tab-content" class="tab-content" data-loaded="1" <?php if ( 0 === $i ) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
			<?php
			$wpt_widgets = new wpt_widget();
			$settings = $wpt_widgets->get_settings();

			if ( isset( $settings[ $number ] ) ) {
				$args = $settings[ $number ];
			}

			$tab_args = array_merge( $args['tabs'][ $key ], array( 'page' => 1, 'class_prefix' => $class_prefix, 'widget_number' => $number, 'tab_key' => $key ) );
			$tab_arr = explode( '-', $key );
			wpt_get_template(
				'tab/'.$tab_arr[0].'.php',
				$tab_args
			);
			?>
			</div><!--end .tab-content-->
		<?php $i++; endif; ?>
	<?php endforeach; ?>
	<div class="clear"></div>
</div> <!--end .inside -->
<div class="clear"></div>