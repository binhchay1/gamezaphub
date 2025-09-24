<?php

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class Custom_Tab_Control extends WP_Customize_Control {
		
	/**
	 * The type of control being rendered
	 */
	public $type = 'custom-tab-control';

	public $controls_general;

	public $controls_design;

	public $controls_custom;

	

	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
	}

	public function enqueue(){

	wp_enqueue_style('custom_tab_control_css', get_template_directory_uri().'/inc/ansar/custom-control/custom_tab_control/css/custom_tab_control.css','4.0.13', 'all');
   
    wp_enqueue_script( 'custom_tab_control_js', get_template_directory_uri().'/inc/ansar/custom-control/custom_tab_control/js/custom_tab_control.js', array('jquery'), false, true );

	}

	public function render_content() {
	?>

	<div class="control-tabs">
		<div class="control-tab control-tab-general active" data-connected="<?php echo esc_attr( $this->controls_general ); ?>"><?php echo esc_html__( 'General','blogarise' ); ?>
		</div>
		<div class="control-tab control-tab-design" data-connected="<?php echo esc_attr( $this->controls_design ); ?>"><?php echo esc_html__( 'Style','blogarise'); ?>
		</div>
		<div class="control-tab control-tab-custom" data-connected="<?php echo esc_attr( $this->controls_custom ); ?>"><?php echo esc_html__( 'Sharing Social Icon','blogarise'); ?>
		</div>
	</div>
	<?php
	}
}
