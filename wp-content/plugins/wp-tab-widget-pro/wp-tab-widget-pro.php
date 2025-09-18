<?php
/*
Plugin Name: WP Tab Widget Pro
Plugin URI: http://mythemeshop.com/plugins/wp-tab-widget-pro/
Description: WP Tab Widget Pro is the most powerful plugin for showing recommended content on blog posts to increase engagement and keep visitors on your site purpose.
Author: MyThemeShop
Version: 1.0.8
Author URI: http://mythemeshop.com/
Text Domain: wp-tab-widget
*/

if ( !defined( 'WPT_PLUGIN_DIR') )
    define( 'WPT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( !defined( 'WPT_PLUGIN_URL') )
    define( 'WPT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( !defined( 'WPT_PLUGIN_BASE') )
    define( 'WPT_PLUGIN_BASE', plugin_basename(__FILE__) );

// Make it load WP Tab Widget first
function wptp_load_plugin_first() {
	$this_plugin = 'wp-tab-widget/wp-tab-widget.php';
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
	if ($this_plugin_key) { // if it's 0 it's the first plugin already, no need to continue
		array_splice($active_plugins, $this_plugin_key, 1);
		array_unshift($active_plugins, $this_plugin);
		update_option('active_plugins', $active_plugins);
	}
}
add_action("activated_plugin", "wptp_load_plugin_first");

if ( function_exists('wpt_add_views_meta_for_posts') ) {
	// add notice
	add_action( 'admin_notices', 'wptp_deactivate_plugin_notice' );

	function wptp_deactivate_plugin_notice() {
		?>
		<div class="error">
			<p><?php _e( 'Please deactivate WP Tab Widget plugin first to use the Premium features!', 'wp-tab-widget' ); ?></p>
		</div>
		<?php
	}
}


if ( !class_exists('wpt_widget') ) {

	class wpt_widget extends WP_Widget {

		function __construct() {

            include_once( WPT_PLUGIN_DIR . 'inc/templating.php');
            include_once( WPT_PLUGIN_DIR . 'inc/color.php');

			// add image sizes and load language file
			add_action( 'init', array( $this, 'wpt_init' ) );

			// ajax functions
			add_action( 'wp_ajax_wpt_widget_content', array( $this, 'ajax_wpt_widget_content' ) );
			add_action( 'wp_ajax_nopriv_wpt_widget_content', array( $this, 'ajax_wpt_widget_content' ) );

			add_action( 'wp_ajax_wpt_get_tab_options', array( $this, 'ajax_get_tab_options' ) );
			add_action( 'wp_ajax_wpt_preview_widget', array( $this, 'ajax_preview_widget' ) );

			// css
			add_action( 'wp_enqueue_scripts', array( $this, 'wpt_register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpt_admin_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpt_register_admin_styles' ) );

			add_action( 'admin_footer-widgets.php', array( $this, 'add_modal' ) );//need one for customizer too

			$widget_ops = array( 'classname' => 'widget_wpt', 'description' => __('Display popular posts, recent posts, comments, and tags in tabbed format.', 'wp-tab-widget' ) );
			$control_ops = array( 'width' => 300, 'height' => 350 );
			parent::__construct( 'wpt_widget', __('WP Tab Widget by MyThemeShop', 'wp-tab-widget' ), $widget_ops, $control_ops );
		}

		function get_field_id( $field_name ) {
			return 'widget-' . $this->id_base . '-' . $this->number . '-' . trim( str_replace( array( '[]', '[', ']' ), array( '', '-', '' ), $field_name ), '-' );
		}

		function get_field_name($field_name) {
			if ( false === $pos = strpos( $field_name, '[' ) ) {
				return 'widget-' . $this->id_base . '[' . $this->number . '][' . $field_name . ']';
			} else {
				return 'widget-' . $this->id_base . '[' . $this->number . '][' . substr_replace( $field_name, '][', $pos, strlen( '[' ) );
			}
		}

		function wpt_init() {

			load_plugin_textdomain( 'wp-tab-widget', false, dirname( WPT_PLUGIN_BASE ) . '/languages/' );

			add_image_size( 'wp_review_small', 65, 65, true ); // small thumb
			add_image_size( 'wp_review_large', 320, 240, true ); // large thumb
		}

		function wpt_admin_scripts( $hook ) {

			if ( 'widgets.php' !== $hook )
				return;

			wp_enqueue_script(
				'wpt_select_2',
				plugins_url('js/select2.min.js', __FILE__),
				array('jquery'),
				null,
				true
			);

			wp_enqueue_script(
				'wpt_widget_admin',
				plugins_url('js/wpt-admin.js', __FILE__),
				array(
					'jquery',
					'jquery-ui-core',
					'jquery-ui-sortable',
					'jquery-ui-accordion'
				),
				null,
				false
			);

			wp_enqueue_script(
				'wpt-bootstrap-modal',
				plugins_url( "js/bootstrap-modal.js", __FILE__ ),
				array( 'jquery' ),
				null,
				true
			);

			wp_enqueue_script(
				'wpt-sly',
				plugins_url( "js/sly.min.js", __FILE__ ),
				array( 'jquery' ),
				null,
				true
			);

			wp_enqueue_script(
				'wpt-admin-dialog',
				plugins_url( "js/wpt-modal.js", __FILE__ ),
				array(
					'wpt-bootstrap-modal',
					'wp-color-picker',
					'wpt-sly'
				),
				null,
				true
			);
		}

		function wpt_register_scripts() {
			// JS
			wp_register_script(
				'wpt_widget',
				plugins_url( 'js/wp-tab-widget.js', __FILE__),
				array( 'jquery' )
			);
			wp_localize_script(
				'wpt_widget',
				'wpt',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' )
				)
			);

			// CSS
			wp_register_style(
				'wpt_font_awesome',
				plugins_url('css/font-awesome.min.css', __FILE__)
			);
			wp_register_style(
				'wpt_widget_pro',
				plugins_url('css/wp-tab-widget-pro.css', __FILE__)
			);
		}

		function wpt_register_admin_styles( $hook ) {

			if ( 'widgets.php' !== $hook )
				return;

			wp_enqueue_style( 'wpt_font_awesome', plugins_url('css/font-awesome.min.css', __FILE__) );
			wp_enqueue_style( 'jquery-ui-style', plugins_url('css/jquery-ui.min.css', __FILE__) );
			wp_enqueue_style( 'wpt_select2', plugins_url('css/select2.css', __FILE__) );
			wp_enqueue_style( 'wpt_admin_widget', plugins_url('css/wp-tab-admin.css', __FILE__) );

			wp_enqueue_style( 'wpt-bootstrap-modal', plugins_url( "css/bootstrap-modal.css", __FILE__ ) );
			wp_enqueue_style( 'wpt-dialog', plugins_url( "css/wpt-modal.css", __FILE__ ) );

			wp_enqueue_script( 'wpt_widget', plugins_url( 'js/wp-tab-widget.js', __FILE__), array( 'jquery' ) );
			wp_localize_script(
				'wpt_widget',
				'wpt',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' )
				)
			);

			// CSS
			wp_enqueue_style( 'wpt_widget_pro', plugins_url('css/wp-tab-widget-pro.css', __FILE__) );
		}

		function add_modal() {
			?>
			<form style="display: none;" id="wpt-modal" class="wpt-modal wpbs-modal fade" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="wpbs-modal-dialog">
					<div class="wpbs-modal-content">
						<div class="wpbs-modal-header">
							<button type="button" class="wpbs-modal-close" data-dismiss="wpbsmodal" aria-hidden="true"><i class="dashicons dashicons-no-alt"></i><?php _e('Close', 'wp-tab-widget')?></button>
							<h2 id="wpt-modal-title" class="wpbs-modal-title"><?php _e( 'Styling Options', 'wp-tab-widget' ); ?></h2>
						</div><!-- /.wpbs-modal-header -->
						<div class="wpbs-modal-body">
							<div class="wpbs-modal-tabs-wrap">
								<ul class="wpbs-modal-tabs">
									<li class="wpbs-modal-tab wpt-active"><a href="#wpbs-modal-style-presets-tab"><?php _e( 'Style presets', 'wp-tab-widget' ); ?></a></li>
									<li class="wpbs-modal-tab"><a href="#wpbs-modal-nav-presets-tab"><?php _e( 'Pagination presets', 'wp-tab-widget' ); ?></a></li>
									<li class="wpbs-modal-tab"><a href="#wpbs-modal-loader-presets-tab"><?php _e( 'Loader', 'wp-tab-widget' ); ?></a></li>
									<li class="wpbs-modal-tab"><a href="#wpbs-modal-settings-tab"><?php _e( 'Customize Colors', 'wp-tab-widget' ); ?></a></li>
								</ul>
							</div>
							<div class="wpbs-modal-inside">
								<div class="wpbs-modal-left">
									<div id="wpbs-modal-style-presets-tab" class="wpbs-modal-tab-content wpt-active">
										<h3><?php _e( 'Select Style', 'wp-tab-widget' ); ?><span class="wptp-disabled-notice"><?php _e( 'Pagination and Color options are not available for selected layout', 'wp-tab-widget' ); ?></span></h3>
										<div id="wpbs-modal-style-presets" class="wpt-modal-section wpt-frame">
											<ul class="presets-list">
											<?php
											$style_presets = $this->get_presets('general');
											foreach ( $style_presets as $key => $preset ) {
												echo '<li class="presets-list-item">';
													echo '<a href="#" data-preset="'.$key.'" data-colors="'.esc_attr( json_encode( $preset['colors'] ) ).'" class="presets-list-preset preset-main">';
														echo '<img src="'.$preset['image'].'" />';
													echo '</a>';
												echo '</li>';
											}
											?>
											</ul>
										</div>
										<div class="wpt-scrollbar">
											<div class="handle">
												<div class="mousearea"></div>
											</div>
										</div>
										<div class="wpt-controls">
											<button class="wpt-btn wpt-prev-page"><i class="fa fa-chevron-left"></i></button>
											<button class="wpt-btn wpt-next-page"><i class="fa fa-chevron-right"></i></button>
										</div>
									</div>
									<div id="wpbs-modal-nav-presets-tab" class="wpbs-modal-tab-content">
										<h3><?php _e( 'Select Style', 'wp-tab-widget' ); ?></h3>
										<div id="wpbs-modal-nav-presets" class="wpt-modal-section wpt-frame">
											<ul class="presets-list">
												<li class="presets-list-item">
												<?php
												$pagination_presets = $this->get_presets('pagination');
												$max = count( $pagination_presets );
												$counter = 1;
												foreach ( $pagination_presets as $key => $preset ) {
													echo '<div class="presets-list-item1">';
														echo '<a href="#" data-preset="'.$key.'" data-colors="'.esc_attr( json_encode( $preset['colors'] ) ).'" class="presets-list-preset preset-nav">';
															echo '<img src="'.$preset['image'].'" />';
														echo '</a>';
													echo '</div>';
													if ( $counter % 5 == 0 ) echo '</li><li class="presets-list-item">';

													$counter++;
												}
												if ( $max % 5 == 0 ) echo '</li>';
												?>
											</ul>
										</div>
										<div class="wpt-scrollbar">
											<div class="handle">
												<div class="mousearea"></div>
											</div>
										</div>
										<div class="wpt-controls">
											<button class="wpt-btn wpt-prev-page"><i class="fa fa-chevron-left"></i></button>
											<button class="wpt-btn wpt-next-page"><i class="fa fa-chevron-right"></i></button>
										</div>
									</div>
									<div id="wpbs-modal-loader-presets-tab" class="wpbs-modal-tab-content">
										<h3><?php _e( 'Select Loader', 'wp-tab-widget' ); ?></h3>
										<div id="wpbs-modal-loader-presets" class="wpt-modal-section">
											<ul class="presets-list">
											<?php
											$loaders = $this->get_presets('loaders');
											foreach ( $loaders as $class => $img ) {
												echo '<li class="presets-list-item">';
													echo '<a href="#" data-preset="'.$class.'" class="presets-list-preset preset-loader">';
														echo '<div class="wpt-loading '.$class.'">';
															echo '<div class="wpt-loader"></div>';
														echo '</div>';
														//echo '<img src="'.$img.'" />';
													echo '</a>';
												echo '</li>';
											}
											?>
											</ul>
										</div>
									</div>
									<div id="wpbs-modal-settings-tab" class="wpbs-modal-tab-content">
										<h3><?php _e( 'Customize Colors', 'wp-tab-widget' ); ?></h3>
										<div id="wpbs-modal-settings" class="wpt-modal-section">
											<input type="hidden" value="" name="modal-option-preset-main" id="modal-option-preset-main" />
											<input type="hidden" value="" name="modal-option-preset-nav" id="modal-option-preset-nav" />
											<input type="hidden" value="" name="modal-option-preset-loader" id="modal-option-preset-loader" />
											<div id="modal-style-colors" style="display:none;">
												<div class="wpt-modal-option modal-option-color-bg">
													<p><?php _e( 'Content Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[bg]" id="modal-option-color-bg" class="wpt-modal-color-picker" />
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-color">
													<p><?php _e( 'Content Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[color]" id="modal-option-color-color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-link_color">
													<p><?php _e( 'Content Link Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[link_color]" id="modal-option-color-link_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-link_hover_color">
													<p><?php _e( 'Content Link Hover Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[link_hover_color]" id="modal-option-color-link_hover_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-list_hover_bg">
													<p><?php _e( 'Content List Hover Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[list_hover_bg]" id="modal-option-color-list_hover_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-tab_bg">
													<p><?php _e( 'Tab Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[tab_bg]" id="modal-option-color-tab_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-tab_color">
													<p><?php _e( 'Tab Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[tab_color]" id="modal-option-color-tab_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-tab_hover_bg">
													<p><?php _e( 'Tab Hover Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[tab_hover_bg]" id="modal-option-color-tab_hover_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-tab_hover_color">
													<p><?php _e( 'Tab Hover Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[tab_hover_color]" id="modal-option-color-tab_hover_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-tab_active_bg">
													<p><?php _e( 'Tab Active Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[tab_active_bg]" id="modal-option-color-tab_active_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-tab_active_color">
													<p><?php _e( 'Tab Active Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[tab_active_color]" id="modal-option-color-tab_active_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
											</div>
											<div id="modal-pagination-colors" style="display:none;">
												<div class="wpt-modal-option modal-option-color-nav_bg">
													<p><?php _e( 'Pagination Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_bg]" id="modal-option-color-nav_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-nav_color">
													<p><?php _e( 'Pagination Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_color]" id="modal-option-color-nav_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-nav_button_bg">
													<p><?php _e( 'Pagination Prev/Next Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_button_bg]" id="modal-option-color-nav_button_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-nav_button_color">
													<p><?php _e( 'Pagination Prev/Next Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_button_color]" id="modal-option-color-nav_button_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-nav_hover_bg">
													<p><?php _e( 'Pagination Hover Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_hover_bg]" id="modal-option-color-nav_hover_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-nav_hover_color">
													<p><?php _e( 'Pagination Hover Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_hover_color]" id="modal-option-color-nav_hover_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-nav_active_bg">
													<p><?php _e( 'Pagination Active Background Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_active_bg]" id="modal-option-color-nav_active_bg" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
												<div class="wpt-modal-option modal-option-color-nav_active_color">
													<p><?php _e( 'Pagination Active Text Color:', 'wp-tab-widget' ); ?></p>
													<input type="text" value="" name="modal-option-color[nav_active_color]" id="modal-option-color-nav_active_color" class="wpt-modal-color-picker"/>
													<a href="#" class="wpt-default-color" data-color=""><?php _e( 'Default', 'wp-tab-widget' ); ?></a>
												</div>
											</div>
										</div>
										<button class="wpt-btn wpt-reload-preview"><?php _e( 'Update Preview', 'wp-tab-widget' ); ?></button>
									</div>
								</div>
								<div class="wpbs-modal-right">
									<h3><?php _e( 'Preview', 'wp-tab-widget' ); ?></h3>
									<div id="wpbs-modal-preview" class="wpt-modal-section">
									</div>
								</div>
							</div>
						</div><!-- /.wpbs-modal-body -->
						<div class="wpbs-modal-footer">
							<button type="button" id="wpt-modal-cancel" class="wpt-cancel wpt-btn left" data-dismiss="wpbsmodal" aria-hidden="true"><i class="dashicons dashicons-no-alt"></i><?php _e( 'Cancel', 'wp-tab-widget' ); ?></button>
							<button type="button" id="wpt-modal-update" class="wpt-update wpt-btn right"><i class="dashicons dashicons-yes"></i><?php _e( 'Save Changes', 'wp-tab-widget' ); ?></button>
						</div><!-- /.wpbs-modal-footer -->
					</div><!-- /.wpbs-modal-content -->
				</div><!-- /.wpbs-modal-dialog -->
			</form><!-- /.wpbs-modal -->
			<?php
		}

		function form( $instance ) {

			$defaults = wptp_get_defaults();

			$default_tabs = $defaults['tabs'];

			if ( empty( $instance ) ) {
				$initial_tabs = array();
				$initial_tabs['latest'] = $defaults['tabs']['latest'];
				$initial_tabs['popular'] = $defaults['tabs']['popular'];
				$defaults['tabs'] = $initial_tabs;
			}

			$instance = wp_parse_args( (array) $instance, $defaults );
			extract( $instance );

			$template = wp_parse_args( (array) $template, $defaults['template'] );

			$icons = $this->get_icons();
			?>

			<div class="wpt_options_form">
				<div class="wpt-styles">
					<div class="left">
						<div class="colorset-preview">
							<span class="colorset-label"><?php _e( 'Color Scheme', 'wp-tab-widget' ); ?>: </span>
							<span class="colorset-colors<?php if ( empty( $template['style'] ) ) { echo ' colorset-none';} ?>">
								<span class="colorset-colors-none"><?php _e( 'Default', 'wp-tab-widget' )?></span>
								<span class="colorset-colors-1 colorset-color" style="background: <?php echo $template['tab_bg']; ?>;"></span>
								<span class="colorset-colors-2 colorset-color" style="background: <?php echo $template['tab_active_bg']; ?>;"></span>
								<span class="colorset-colors-3 colorset-color" style="background: <?php echo $template['bg']; ?>;"></span>
								<span class="colorset-colors-4 colorset-color" style="background: <?php echo $template['color']; ?>;"></span>
							</span>
						</div>
					</div>
					<button class="button button-primary button-large wpt-style-popup"><?php _e( 'Customize Style', 'wp-tab-widget' ); ?></button>

					<input type="hidden" id="<?php echo $this->get_field_id('template[style]'); ?>" class="wpt-input-style" name="<?php echo $this->get_field_name('template[style]'); ?>" value="<?php echo $template['style']; ?>">
					<input type="hidden" id="<?php echo $this->get_field_id('template[pagination_style]'); ?>" class="wpt-input-pagination_style" name="<?php echo $this->get_field_name('template[pagination_style]'); ?>" value="<?php echo $template['pagination_style']; ?>">
					<input type="hidden" id="<?php echo $this->get_field_id('template[loader]'); ?>" class="wpt-input-loader" name="<?php echo $this->get_field_name('template[loader]'); ?>" value="<?php echo $template['loader']; ?>">
					<div class="wpt-colors">
						<input type="hidden" id="<?php echo $this->get_field_id('template[bg]'); ?>" class="wpt-input-bg" name="<?php echo $this->get_field_name('template[bg]'); ?>" value="<?php echo $template['bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[color]'); ?>" class="wpt-input-color" name="<?php echo $this->get_field_name('template[color]'); ?>" value="<?php echo $template['color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[link_color]'); ?>" class="wpt-input-link_color" name="<?php echo $this->get_field_name('template[link_color]'); ?>" value="<?php echo $template['link_color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[link_hover_color]'); ?>" class="wpt-input-link_hover_color" name="<?php echo $this->get_field_name('template[link_hover_color]'); ?>" value="<?php echo $template['link_hover_color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[list_hover_bg]'); ?>" class="wpt-input-list_hover_bg" name="<?php echo $this->get_field_name('template[list_hover_bg]'); ?>" value="<?php echo $template['list_hover_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[tab_bg]'); ?>" class="wpt-input-tab_bg" name="<?php echo $this->get_field_name('template[tab_bg]'); ?>" value="<?php echo $template['tab_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[tab_color]'); ?>" class="wpt-input-tab_color" name="<?php echo $this->get_field_name('template[tab_color]'); ?>" value="<?php echo $template['tab_color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[tab_hover_bg]'); ?>" class="wpt-input-tab_hover_bg" name="<?php echo $this->get_field_name('template[tab_hover_bg]'); ?>" value="<?php echo $template['tab_hover_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[tab_hover_color]'); ?>" class="wpt-input-tab_hover_color" name="<?php echo $this->get_field_name('template[tab_hover_color]'); ?>" value="<?php echo $template['tab_hover_color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[tab_active_bg]'); ?>" class="wpt-input-tab_active_bg" name="<?php echo $this->get_field_name('template[tab_active_bg]'); ?>" value="<?php echo $template['tab_active_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[tab_active_color]'); ?>" class="wpt-input-tab_active_color" name="<?php echo $this->get_field_name('template[tab_active_color]'); ?>" value="<?php echo $template['tab_active_color']; ?>">

						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_bg]'); ?>" class="wpt-input-nav_bg" name="<?php echo $this->get_field_name('template[nav_bg]'); ?>" value="<?php echo $template['nav_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_color]'); ?>" class="wpt-input-nav_color" name="<?php echo $this->get_field_name('template[nav_color]'); ?>" value="<?php echo $template['nav_color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_button_bg]'); ?>" class="wpt-input-nav_button_bg" name="<?php echo $this->get_field_name('template[nav_button_bg]'); ?>" value="<?php echo $template['nav_button_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_button_color]'); ?>" class="wpt-input-nav_button_color" name="<?php echo $this->get_field_name('template[nav_button_color]'); ?>" value="<?php echo $template['nav_button_color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_hover_bg]'); ?>" class="wpt-input-nav_hover_bg" name="<?php echo $this->get_field_name('template[nav_hover_bg]'); ?>" value="<?php echo $template['nav_hover_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_hover_color]'); ?>" class="wpt-input-nav_hover_color" name="<?php echo $this->get_field_name('template[nav_hover_color]'); ?>" value="<?php echo $template['nav_hover_color']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_active_bg]'); ?>" class="wpt-input-nav_active_bg" name="<?php echo $this->get_field_name('template[nav_active_bg]'); ?>" value="<?php echo $template['nav_active_bg']; ?>">
						<input type="hidden" id="<?php echo $this->get_field_id('template[nav_active_color]'); ?>" class="wpt-input-nav_active_color" name="<?php echo $this->get_field_name('template[nav_active_color]'); ?>" value="<?php echo $template['nav_active_color']; ?>">
					</div>
				</div>
				<div class="wpt-accordion">
				<strong style="float: left; width: 100%; margin-bottom: 5px;"><?php _e('Active Tabs', 'wp-tab-widget'); ?>:</strong>
				<?php

				foreach( (array) $tabs as $key => $values ):

					$def_key = strpos( $key, '-' ) ? substr( $key, 0, strpos( $key, '-' ) ) : $key;
					$tabs[ $key ] = wp_parse_args( $tabs[ $key ], $default_tabs[ $def_key ] );
					?>
					<div class="item-wrap <?php echo $key; ?>">
						<h3><?php echo $tabs[$key]['tab_name']; ?></h3>
						<div>
							<p class="wpt-opt-full-width">
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-tab_name">
									<?php _e('Tab Name', 'wp-tab-widget'); ?><br/>
									<input type="text" id="<?php echo $this->get_field_id("tabs[$key]"); ?>-tab_name" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[tab_name]" value="<?php echo $tabs[$key]['tab_name']; ?>">
								</label>
							</p>
							<p class="wpt-opt-full-width">
								<select class="nhpopts-iconselect" id="<?php echo $this->get_field_id("tabs[$key]"); ?>-icon" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[icon]" style="width: 100%;">
									<option><?php _e('Select Tab Icon', 'wp-tab-widget'); ?></option>
									<?php
									foreach ( $icons as $icon_category => $_icons ) {
											echo '<optgroup label="'.$icon_category.'">';
										foreach ($_icons as $icon) {
											echo '<option value="'.$icon.'"'.selected( $tabs[$key]['icon'], $icon, false).'><i class="fa fa-'.$icon.'"></i> '.ucwords(str_replace('-', ' ', $icon)).'</option>';
										}
										echo '</optgroup>';
									} ?>
								</select>
							</p>
						<?php if ( strpos ( $key, 'cpt' ) !== false ): ?>
							<p class="wpt-opt-full-width">
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-post_type">
									<?php _e('Post Type', 'wp-tab-widget'); ?><br/>
									<?php
									$args = array(
									   'public'   => true,
									   '_builtin' => false
									);
									$output = 'objects';
									$operator = 'and';
									$post_types = get_post_types( $args, $output, $operator );
									?>
									<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-post_type" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[post_type]" class="wptp-select">
										<?php
										foreach ( $post_types as $slug => $pt ) : ?>
											<option value="<?php echo esc_attr( $pt->name ); ?>" <?php selected($tabs[$key]['post_type'], $pt->name, true); ?>><?php echo $pt->label; ?></option>
										<?php endforeach; ?>
									</select>
								</label>
							</p>
						<?php endif; ?>
						<?php if ( strpos( $key, 'popular' ) !== false || strpos ( $key, 'latest' ) !== false || strpos ( $key, 'products' ) !== false || strpos ( $key, 'cpt' ) !== false ): ?>
							<p>
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-num_post">
									<?php _e('Number of Posts', 'wp-tab-widget'); ?><br/>
									<input type="text" id="<?php echo $this->get_field_id("tabs[$key]"); ?>-num_post" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[num_post]" value="<?php echo $tabs[$key]['num_post']; ?>">
								</label>
							</p>
							<p>
								<label>
									<?php _e('Title length (words):', 'wp-tab-widget'); ?><br />
									<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[title_length]" type="number" min="1" step="1" value="<?php echo $tabs[$key]['title_length']; ?>"/>
								</label>
							</p>
							<?php if ( strpos( $key, 'products' ) !== false ) : ?>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_price]" value="0">
									<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_price]" value="1" <?php checked( $tabs[$key]['show_price'], 1 ); ?>>
									<?php _e('Show Price', 'wp-tab-widget'); ?>
								</label>
							</p>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_rating]" value="0">
									<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_rating]" value="1" <?php checked( $tabs[$key]['show_rating'], 1 ); ?>>
									<?php _e('Show Rating', 'wp-tab-widget'); ?>
								</label>
							</p>
							<?php else : ?>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_date]" value="0">
									<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_date]" value="1" <?php checked( $tabs[$key]['show_date'], 1 ); ?>>
									<?php _e('Show Date', 'wp-tab-widget'); ?>
								</label>
							</p>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_comment]" value="0">
									<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_comment]" value="1" <?php checked( $tabs[$key]['show_comment'], 1 ); ?>>
									<?php _e('Show Comment', 'wp-tab-widget'); ?>
								</label>
							</p>
							<?php endif; ?>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_thumbnail]" value="0">
									<input class="checkbox wpt_show_thumbnails" type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_thumbnail]" value="1" <?php checked( $tabs[$key]['show_thumbnail'], 1 ); ?>>
									<?php _e('Show Thumbnail', 'wp-tab-widget'); ?>
								</label>
								<span class="wpt_thumbnail_size"<?php echo (empty($tabs[$key]['show_thumbnail']) ? ' style="display: none;"' : ''); ?>>
									<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size"><?php _e('Thumbnail size:', 'wp-tab-widget'); ?></label>
									<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[thumb_size]" class="wptp-has-child-opt">
										<option value="small" <?php selected($tabs[$key]['thumb_size'], 'small', true); ?>><?php _e('Small', 'wp-tab-widget'); ?></option>
										<option value="large" <?php selected($tabs[$key]['thumb_size'], 'large', true); ?>><?php _e('Large', 'wp-tab-widget'); ?></option>
										<option value="custom" <?php selected($tabs[$key]['thumb_size'], 'custom', true); ?>><?php _e('Custom', 'wp-tab-widget'); ?></option>
									</select>

									<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" data-parent-select-value="small">
										<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-small_thumb_align"><?php _e('Thumbnail align:', 'wp-tab-widget'); ?></label>
										<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-small_thumb_align" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[small_thumb_align]">
											<option value="left" <?php selected($tabs[$key]['small_thumb_align'], 'left', true); ?>><?php _e('Left', 'wp-tab-widget'); ?></option>
											<option value="right" <?php selected($tabs[$key]['small_thumb_align'], 'right', true); ?>><?php _e('Right', 'wp-tab-widget'); ?></option>
										</select>
									</span>

									<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" data-parent-select-value="custom">
										<label><?php _e('Thumbnail Size (px):', 'wp-tab-widget'); ?></label>
										<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_thumb_w]" type="number" min="1" step="1" class="wptp-width-height-opt" value="<?php echo $tabs[$key]['custom_thumb_w']; ?>"/>
										X
										<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_thumb_h]" type="number" min="1" step="1" class="wptp-width-height-opt" value="<?php echo $tabs[$key]['custom_thumb_h']; ?>"/>
									</span>

									<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" data-parent-select-value="custom">
										<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_thumb_align"><?php _e('Thumbnail align:', 'wp-tab-widget'); ?></label>
										<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_thumb_align" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_thumb_align]">
											<option value="left" <?php selected($tabs[$key]['custom_thumb_align'], 'small', true); ?>><?php _e('Left', 'wp-tab-widget'); ?></option>
											<option value="right" <?php selected($tabs[$key]['custom_thumb_align'], 'large', true); ?>><?php _e('Right', 'wp-tab-widget'); ?></option>
											<option value="none" <?php selected($tabs[$key]['custom_thumb_align'], 'none', true); ?>><?php _e('None', 'wp-tab-widget'); ?></option>
										</select>
									</span>
								</span>
							</p>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_excerpt]" value="0">
									<input type="checkbox" class="checkbox wpt_show_excerpt" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_excerpt]" value="1" <?php checked( $tabs[$key]['show_excerpt'], 1 ); ?>/>
									<?php _e( 'Show Post Excerpt', 'wp-tab-widget'); ?>
								</label>
								<span class="wpt_excerpt_length"<?php echo (empty($tabs[$key]['show_excerpt']) ? ' style="display: none;"' : ''); ?>>
									<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-excerpt_length">
										<?php _e('Excerpt length[words]', 'wp-tab-widget'); ?>
										<br />
										<input type="number" min="1" step="1" id="<?php echo $this->get_field_id("tabs[$key]"); ?>-excerpt_length" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[excerpt_length]" value="<?php echo $tabs[$key]['excerpt_length']; ?>" />
									</label>
								</span>
							</p>

							<?php if ( strpos( $key, 'popular' ) !== false ) : ?>
							<p>
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-popular_query_by"><?php _e('Show Popularity by:', 'wp-tab-widget'); ?></label>
								<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-popular_query_by" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[popular_query_by]">
									<option value="views" <?php selected($tabs[$key]['popular_query_by'], 'views', true); ?>><?php _e('Views', 'wp-tab-widget'); ?></option>
									<option value="comments" <?php selected($tabs[$key]['popular_query_by'], 'comments', true); ?>><?php _e('Comments', 'wp-tab-widget'); ?></option>
								</select>
							</p>

							<p>
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-days">
									<?php _e('Post Duration', 'wp-tab-widget'); ?>
								</label>
								<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-days" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[days]" class="wptp-has-child-opt">
									<option value="" <?php selected( $tabs[$key]['days'], '' ); ?>><?php _e('All Time', 'wp-tab-widget') ?></option>
									<option value="1" <?php selected( $tabs[$key]['days'], '1' ); ?>><?php _e('This Week', 'wp-tab-widget') ?></option>
									<option value="2" <?php selected( $tabs[$key]['days'], '2' ); ?>><?php _e('Last Week', 'wp-tab-widget') ?></option>
									<option value="3" <?php selected( $tabs[$key]['days'], '3' ); ?>><?php _e('Last Month', 'wp-tab-widget') ?></option>
									<option value="custom" <?php selected($tabs[$key]['days'], 'custom', true); ?>><?php _e('Custom', 'wp-tab-widget'); ?></option>
								</select>
								<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-days" data-parent-select-value="custom">
									<label><?php _e('Number of days:', 'wp-tab-widget'); ?></label>
									<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_days]" type="number" min="1" step="1" value="<?php echo $tabs[$key]['custom_days']; ?>"/>
								</span>
							</p>
							<?php endif; ?>

							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="0">
									<input type="checkbox" class="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="1" <?php checked( $tabs[$key]['allow_pagination'], 1 ); ?>/>
									<?php _e( 'Allow pagination', 'wp-tab-widget'); ?>
								</label>
							</p>

						<?php endif; ?>
						<?php if ( strpos($key, 'comments') !== false ): ?>
							<p>
								<label>
									<?php _e('Number of Comments', 'wp-tab-widget'); ?><br/>
									<input type="text" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[num_comment]" value="<?php echo $tabs[$key]['num_comment']; ?>">
								</label>
							</p>
							<p>
								<label>
									<?php _e('Title length (words):', 'wp-tab-widget'); ?><br />
									<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[title_length]" type="number" min="1" step="1" value="<?php echo $tabs[$key]['title_length']; ?>"/>
								</label>
							</p>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_avatar]" value="0">
									<input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_avatar]" value="1" <?php checked( $tabs[$key]['show_avatar'], 1 ); ?>>
									<?php _e('Show Avatar', 'wp-tab-widget'); ?>
								</label>
							</p>
							<p>
								<label>
									<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="0">
									<input type="checkbox" class="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="1" <?php checked( $tabs[$key]['allow_pagination'], 1 ); ?>/>
									<?php _e( 'Allow pagination', 'wp-tab-widget'); ?>
								</label>
							</p>
						<?php endif; ?>
						<?php if ( strpos($key, 'custom') !== false ): ?>
							<p class="wpt-opt-full-width">
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_content"><?php _e('Content', 'wp-tab-widget'); ?></label><br />
								<textarea id="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_content" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_content]"><?php echo esc_textarea( $tabs[$key]['custom_content'] ); ?></textarea>
							</p>
						<?php endif; ?>
						<?php if ( strpos($key, 'cats') !== false || strpos ( $key, 'tags' ) !== false ): ?>
						<p>
							<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_orderby"><?php _e('Order by:', 'wp-tab-widget'); ?></label>
							<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_orderby" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[terms_orderby]">
								<option value="name" <?php selected($tabs[$key]['terms_orderby'], 'name', true); ?>><?php _e('Name', 'wp-tab-widget'); ?></option>
								<option value="id" <?php selected($tabs[$key]['terms_orderby'], 'id', true); ?>><?php _e('ID', 'wp-tab-widget'); ?></option>
								<option value="count" <?php selected($tabs[$key]['terms_orderby'], 'count', true); ?>><?php _e('Posts Count', 'wp-tab-widget'); ?></option>
							</select>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_order"><?php _e('Order:', 'wp-tab-widget'); ?></label>
							<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_order" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[terms_order]">
								<option value="ASC" <?php selected($tabs[$key]['terms_order'], 'ASC', true); ?>><?php _e('ASC', 'wp-tab-widget'); ?></option>
								<option value="DESC" <?php selected($tabs[$key]['terms_order'], 'DESC', true); ?>><?php _e('DESC', 'wp-tab-widget'); ?></option>
							</select>
						</p>
						<p>
							<label>
								<?php _e('Number of terms (0=all):', 'wp-tab-widget'); ?><br/>
								<input type="number" min="0" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[terms_num]" value="<?php echo $tabs[$key]['terms_num']; ?>">
							</label>
						</p>
					<?php endif; ?>
						</div>
						<a href="#" class="wpt-remove-item"><i class="dashicons dashicons-no-alt"></i> <?php _e('Delete', 'wp-tab-widget'); ?></a>
					</div>
				<?php endforeach; ?>
				</div>

				<div class="wpt-add-wrapper">
					<select class="wpt-dropdown-tabs" name="dropdown-tabs">
						<option value="popular"><?php _e('Popular', 'wp-tab-widget'); ?></option>
						<option value="latest"><?php _e('Latest', 'wp-tab-widget'); ?></option>
						<option value="cats"><?php _e('Categories', 'wp-tab-widget'); ?></option>
						<option value="tags"><?php _e('Tags', 'wp-tab-widget'); ?></option>
						<option value="comments"><?php _e('Comments', 'wp-tab-widget'); ?></option>
						<option value="custom"><?php _e('Custom Content', 'wp-tab-widget'); ?></option>
						<option value="products"><?php _e('Products', 'wp-tab-widget'); ?></option>
						<option value="cpt"><?php _e('Custom Post Type', 'wp-tab-widget'); ?></option>
					</select>
					<button class="button wpt-add-item">+ <?php _e('Add New Tab', 'wp-tab-widget'); ?></button>
				</div>

				<div class="wpt-general">
					<p>
						<label>
							<input type="hidden" name="<?php echo $this->get_field_name("use_ajax"); ?>" value="0">
							<input type="checkbox" name="<?php echo $this->get_field_name("use_ajax"); ?>" value="1" <?php checked( (bool) $use_ajax ); ?>>
							<?php _e('Load tabs content with AJAX', 'wp-tab-widget'); ?>
						</label>
					</p>
				</div>
			</div><!-- .wpt_options_form -->
			<?php
		}

		function ajax_get_tab_options() {
			$tab = $_POST['wpt_tab'];
			$tab_arr = explode( '-', $tab );
			$key = $tab;
			$defaults = wptp_get_defaults();
			$tabs = $defaults['tabs'];

			$icons = $this->get_icons();
			// Get correct widget instance number
			$this->number = empty( $_POST['wpt_multi_number'] ) ? $_POST['wpt_number'] : $_POST['wpt_multi_number'];
			?>
			<div class="item-wrap <?php echo $key; ?>">
				<h3><?php echo $tabs[$tab_arr[0]]['tab_name']; ?></h3>
				<div>
					<p class="wpt-opt-full-width">
						<label>
							<?php _e('Tab Name', 'wp-tab-widget'); ?><br/>
							<input type="text" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[tab_name]" value="<?php echo $tabs[$tab_arr[0]]['tab_name']; ?>">
						</label>
					</p>
					<p class="wpt-opt-full-width">
						<select class="nhpopts-iconselect" id="<?php echo $this->get_field_id("tabs[$key]"); ?>-icon" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[icon]" style="width: 100%;">
							<option><?php _e('Select Tab Icon', 'wp-tab-widget'); ?></option>
							<?php
							foreach ( $icons as $icon_category => $_icons ) {
									echo '<optgroup label="'.$icon_category.'">';
								foreach ($_icons as $icon) {
									echo '<option value="'.$icon.'"'.selected( $tabs[$key]['icon'], $icon, false).'><i class="fa fa-'.$icon.'"></i> '.ucwords(str_replace('-', ' ', $icon)).'</option>';
								}
								echo '</optgroup>';
							} ?>
						</select>
					</p>
					<?php if ( strpos ( $key, 'cpt' ) !== false ): ?>
						<p class="wpt-opt-full-width">
							<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-post_type">
								<?php _e('Post Type', 'wp-tab-widget'); ?><br/>
								<?php
								$args = array(
								   'public'   => true,
								   '_builtin' => false
								);
								$output = 'objects';
								$operator = 'and';
								$post_types = get_post_types( $args, $output, $operator );
								?>
								<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-post_type" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[post_type]" class="wptp-select">
									<?php
									foreach ( $post_types as $slug => $pt ) : ?>
										<option value="<?php echo $tabs[$tab_arr[0]]['post_type']; ?>" <?php selected($tabs[$key]['post_type'], $pt->name, true); ?>><?php echo $pt->label; ?></option>
									<?php endforeach; ?>
								</select>
							</label>
						</p>
					<?php endif; ?>
					<?php if ( strpos( $key, 'popular' ) !== false || strpos ( $key, 'latest' ) !== false || strpos ( $key, 'products' ) !== false || strpos ( $key, 'cpt' ) !== false ): ?>

						<p>
							<label>
								<?php _e('Number of Posts', 'wp-tab-widget'); ?><br/>
								<input type="text" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[num_post]" value="<?php echo $tabs[$tab_arr[0]]['num_post']; ?>">
							</label>
						</p>
						<p>
							<label>
								<?php _e('Title length (words):', 'wp-tab-widget'); ?><br />
								<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[title_length]" type="number" min="1" step="1" value="<?php echo $tabs[$tab_arr[0]]['title_length']; ?>"/>
							</label>
						</p>
						<?php if ( strpos( $key, 'products' ) !== false ) : ?>
						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_price]" value="0">
								<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_price]" value="1" <?php checked( $tabs[$tab_arr[0]]['show_price'], 1 ); ?>>
								<?php _e('Show Price', 'wp-tab-widget'); ?>
							</label>
						</p>
						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_rating]" value="0">
								<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_rating]" value="1" <?php checked( $tabs[$tab_arr[0]]['show_rating'], 1 ); ?>>
								<?php _e('Show Rating', 'wp-tab-widget'); ?>
							</label>
						</p>
						<?php else: ?>
						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_date]" value="0">
								<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_date]" value="1" <?php checked( $tabs[$tab_arr[0]]['show_date'], 1 ); ?>>
								<?php _e('Show Date', 'wp-tab-widget'); ?>
							</label>
						</p>
						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_comment]" value="0">
								<input type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_comment]" value="1" <?php checked( $tabs[$tab_arr[0]]['show_comment'], 1 ); ?>>
								<?php _e('Show Comment', 'wp-tab-widget'); ?>
							</label>
						</p>
						<?php endif; ?>
						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_thumbnail]" value="0">
								<input class="checkbox wpt_show_thumbnails" type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_thumbnail]" value="1" <?php checked( $tabs[$tab_arr[0]]['show_thumbnail'], 1 ); ?>>
								<?php _e('Show Thumbnail', 'wp-tab-widget'); ?>
							</label>
							<span class="wpt_thumbnail_size"<?php echo (empty($tabs[$tab_arr[0]]['show_thumbnail']) ? ' style="display: none;"' : ''); ?>>
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size"><?php _e('Thumbnail size:', 'wp-tab-widget'); ?></label>
								<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[thumb_size]" class="wptp-has-child-opt">
									<option value="small" <?php selected($tabs[$tab_arr[0]]['thumb_size'], 'small', true); ?>><?php _e('Small', 'wp-tab-widget'); ?></option>
									<option value="large" <?php selected($tabs[$tab_arr[0]]['thumb_size'], 'large', true); ?>><?php _e('Large', 'wp-tab-widget'); ?></option>
									<option value="custom" <?php selected($tabs[$tab_arr[0]]['thumb_size'], 'custom', true); ?>><?php _e('Custom', 'wp-tab-widget'); ?></option>
								</select>

								<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" data-parent-select-value="small">
									<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-small_thumb_align"><?php _e('Thumbnail align:', 'wp-tab-widget'); ?></label>
									<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-small_thumb_align" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[small_thumb_align]">
										<option value="left" <?php selected($tabs[$tab_arr[0]]['small_thumb_align'], 'left', true); ?>><?php _e('Left', 'wp-tab-widget'); ?></option>
										<option value="right" <?php selected($tabs[$tab_arr[0]]['small_thumb_align'], 'right', true); ?>><?php _e('Right', 'wp-tab-widget'); ?></option>
									</select>
								</span>

								<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" data-parent-select-value="custom">
									<label><?php _e('Thumbnail Size (px):', 'wp-tab-widget'); ?></label>
									<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_thumb_w]" type="number" min="1" step="1" class="wptp-width-height-opt" value="<?php echo $tabs[$tab_arr[0]]['custom_thumb_w']; ?>"/>
									X
									<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_thumb_h]" type="number" min="1" step="1" class="wptp-width-height-opt" value="<?php echo $tabs[$tab_arr[0]]['custom_thumb_h']; ?>"/>
								</span>

								<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-thumb_size" data-parent-select-value="custom">
									<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_thumb_align"><?php _e('Thumbnail align:', 'wp-tab-widget'); ?></label>
									<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_thumb_align" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_thumb_align]">
										<option value="left" <?php selected($tabs[$tab_arr[0]]['custom_thumb_align'], 'small', true); ?>><?php _e('Left', 'wp-tab-widget'); ?></option>
										<option value="right" <?php selected($tabs[$tab_arr[0]]['custom_thumb_align'], 'large', true); ?>><?php _e('Right', 'wp-tab-widget'); ?></option>
										<option value="none" <?php selected($tabs[$tab_arr[0]]['custom_thumb_align'], 'none', true); ?>><?php _e('None', 'wp-tab-widget'); ?></option>
									</select>
								</span>
							</span>
						</p>

						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_excerpt]" value="0">
								<input type="checkbox" class="checkbox wpt_show_excerpt" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_excerpt]" value="1" <?php checked( $tabs[$tab_arr[0]]['show_excerpt'], 1 ); ?>/>
								<?php _e( 'Show Post Excerpt', 'wp-tab-widget'); ?>
							</label>
							<span class="wpt_excerpt_length"<?php echo (empty($tabs[$tab_arr[0]]['show_excerpt']) ? ' style="display: none;"' : ''); ?>>
								<label>
									<?php _e('Excerpt length (words):', 'wp-tab-widget'); ?><br />
									<input type="number" min="1" step="1" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[excerpt_length]" value="<?php echo $tabs[$tab_arr[0]]['excerpt_length']; ?>" />
								</label>
							</span>
						</p>

						<?php if ( strpos( $key, 'popular' ) !== false ) : ?>
							<p>
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-popular_query_by"><?php _e('Determine popularity by:', 'wp-tab-widget'); ?></label>
								<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-popular_query_by" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[popular_query_by]">
									<option value="views" <?php selected($tabs[$tab_arr[0]]['popular_query_by'], 'views', true); ?>><?php _e('Views', 'wp-tab-widget'); ?></option>
									<option value="comments" <?php selected($tabs[$tab_arr[0]]['popular_query_by'], 'comments', true); ?>><?php _e('Comments', 'wp-tab-widget'); ?></option>
								</select>
							</p>
							<p>
								<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-days">
									<?php _e('Post Duration', 'wp-tab-widget'); ?>
								</label>
								<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-days" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[days]" class="wptp-has-child-opt">
									<option value="" <?php selected( $tabs[$tab_arr[0]]['days'], '' ); ?>><?php _e('All Time', 'wp-tab-widget') ?></option>
									<option value="1" <?php selected( $tabs[$tab_arr[0]]['days'], '1' ); ?>><?php _e('This Week', 'wp-tab-widget') ?></option>
									<option value="2" <?php selected( $tabs[$tab_arr[0]]['days'], '2' ); ?>><?php _e('Last Week', 'wp-tab-widget') ?></option>
									<option value="3" <?php selected( $tabs[$tab_arr[0]]['days'], '3' ); ?>><?php _e('Last Month', 'wp-tab-widget') ?></option>
									<option value="custom" <?php selected($tabs[$tab_arr[0]]['days'], 'custom', true); ?>><?php _e('Custom', 'wp-tab-widget'); ?></option>
								</select>
								<span data-parent-select-id="<?php echo $this->get_field_id("tabs[$key]"); ?>-days" data-parent-select-value="custom">
									<label><?php _e('Number of days:', 'wp-tab-widget'); ?></label>
									<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_days]" type="number" min="1" step="1" value="<?php echo $tabs[$tab_arr[0]]['custom_days']; ?>"/>
								</span>
							</p>
						<?php endif; ?>

						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="0">
								<input type="checkbox" class="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="1" <?php checked( $tabs[$tab_arr[0]]['allow_pagination'], 1 ); ?>/>
								<?php _e( 'Allow pagination', 'wp-tab-widget'); ?>
							</label>
						</p>

					<?php endif; ?>

					<?php if ( strpos($key, 'comments') !== false ): ?>
						<p>
							<label>
								<?php _e('Number of Comments', 'wp-tab-widget'); ?><br/>
								<input type="text" id="<?php echo $this->get_field_id('tabs'); ?>" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[num_comment]" value="<?php echo $tabs[$tab_arr[0]]['num_comment']; ?>">
							</label>
						</p>
						<p>
							<label>
								<?php _e('Title length (words):', 'wp-tab-widget'); ?><br />
								<input name="<?php echo $this->get_field_name("tabs[$key]"); ?>[title_length]" type="number" min="1" step="1" value="<?php echo $tabs[$tab_arr[0]]['title_length']; ?>"/>
							</label>
						</p>
						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_avatar]" value="0">
								<input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[show_avatar]" value="1" <?php checked( $tabs[$tab_arr[0]]['show_avatar'], 1 ); ?>>
								<?php _e('Show Avatar', 'wp-tab-widget'); ?>
							</label>
						</p>
						<p>
							<label>
								<input type="hidden" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="0">
								<input type="checkbox" class="checkbox" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[allow_pagination]" value="1" <?php checked( $tabs[$tab_arr[0]]['allow_pagination'], 1 ); ?>/>
								<?php _e( 'Allow pagination', 'wp-tab-widget'); ?>
							</label>
						</p>
					<?php endif; ?>
					<?php if ( strpos($key, 'custom') !== false ): ?>
						<p class="wpt-opt-full-width">
							<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_content"><?php _e('Content', 'wp-tab-widget'); ?></label><br />
							<textarea id="<?php echo $this->get_field_id("tabs[$key]"); ?>-custom_content" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[custom_content]"><?php echo esc_textarea( $tabs[$tab_arr[0]]['custom_content'] ); ?></textarea>
						</p>
					<?php endif; ?>
					<?php if ( strpos($key, 'cats') !== false || strpos ( $key, 'tags' ) !== false ): ?>
						<p>
							<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_orderby"><?php _e('Order by:', 'wp-tab-widget'); ?></label>
							<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_orderby" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[terms_orderby]">
								<option value="name" <?php selected($tabs[$tab_arr[0]]['terms_orderby'], 'name', true); ?>><?php _e('Name', 'wp-tab-widget'); ?></option>
								<option value="id" <?php selected($tabs[$tab_arr[0]]['terms_orderby'], 'id', true); ?>><?php _e('ID', 'wp-tab-widget'); ?></option>
								<option value="count" <?php selected($tabs[$tab_arr[0]]['terms_orderby'], 'count', true); ?>><?php _e('Posts Count', 'wp-tab-widget'); ?></option>
							</select>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_order"><?php _e('Order:', 'wp-tab-widget'); ?></label>
							<select id="<?php echo $this->get_field_id("tabs[$key]"); ?>-terms_order" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[terms_order]">
								<option value="ASC" <?php selected($tabs[$tab_arr[0]]['terms_order'], 'ASC', true); ?>><?php _e('ASC', 'wp-tab-widget'); ?></option>
								<option value="DESC" <?php selected($tabs[$tab_arr[0]]['terms_order'], 'DESC', true); ?>><?php _e('DESC', 'wp-tab-widget'); ?></option>
							</select>
						</p>
						<p>
							<label>
								<?php _e('Number of terms (0=all):', 'wp-tab-widget'); ?><br/>
								<input type="number" min="0" name="<?php echo $this->get_field_name("tabs[$key]"); ?>[terms_num]" value="<?php echo $tabs[$tab_arr[0]]['terms_num']; ?>">
							</label>
						</p>
					<?php endif; ?>
				</div>
				<a href="#" class="wpt-remove-item"><i class="dashicons dashicons-no-alt"></i> <?php _e('Delete', 'wp-tab-widget'); ?></a>
			</div>
			<?php
			die();
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$defaults = wptp_get_defaults();
			$default_tabs = $defaults['tabs'];
			foreach(  $new_instance['tabs'] as $key => $values ) {
				$def_key = strpos( $key, '-' ) ? substr( $key, 0, strpos( $key, '-' ) ) : $key;
				$new_instance['tabs'][ $key ] = wp_parse_args( $new_instance['tabs'][ $key ], $default_tabs[ $def_key ] );
			}

			$instance['tabs'] = (array) $new_instance['tabs'];
			$instance['template'] = (array) $new_instance['template'];
			$instance['use_ajax'] = !empty( $new_instance['use_ajax'] ) ? '1' : '0';

			return $instance;
		}

		function widget( $args, $instance ) {

			if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = 'wpt_widget-9999999999';

			extract( $args );

			wp_enqueue_script('wpt_widget');
			wp_enqueue_style('wpt_font_awesome');
			//$style_handle = 'wpt_widget';
			?>
			<?php echo $before_widget; ?>
			<?php // Maybe add widget title ?>

			<?php
			$use_ajax = isset($instance['use_ajax']) ? $instance['use_ajax'] : '1';
			$style            = $instance['template']['style'];
			$pagination_style = $instance['template']['pagination_style'];
			$class_prefix = !empty( $style ) ? 'wptp' : 'wpt';
			$class = $class_prefix.'_widget_content '.$instance['template']['loader'];
			if ( !empty( $style ) ) {
				//$style_handle = 'wpt_widget_pro';
				$class .= ' wptp-style-'.$style;
			}
			if ( !empty( $pagination_style ) ) {
				$class .= ' wptp-pagination-style-'.$pagination_style;
			}
			//wp_enqueue_style($style_handle);
			wp_enqueue_style('wpt_widget_pro');
			?>

			<div id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>" class="<?php echo esc_attr( $class );?>" data-style="<?php echo $style; ?>" data-pagination-style="<?php echo $pagination_style; ?>">
				<div class="wpt-loader"></div>
				<?php

				$general_args = $instance;
				$general_args['class_prefix'] = $class_prefix;
				$general_args['number'] = $this->number;

				if ( '1' === $use_ajax ) {
					wpt_get_template(
						'tabs-nav-ajax.php',
						$general_args
					);
				} else {
					wpt_get_template(
						'tabs-nav.php',
						$general_args
					);
				}

				if ( '1' === $use_ajax ) {
					wpt_get_template(
						'content-ajax.php',
						$general_args
					);
				} else {
					wpt_get_template(
						'content.php',
						$general_args
					);
				}

				$template_args = $instance['template'];
				$template_args['widget_id'] = $widget_id;

				if ( ! empty( $style ) ) {
					wpt_get_template(
						'styles/main-'.$style.'.php',
						$template_args
					);
				}

				if ( ! empty( $pagination_style ) ) {
					wpt_get_template(
						'styles/pagination-'.$pagination_style.'.php',
						$template_args
					);
				}
				?>
			</div><!--end .wpt_widget_content -->

			<?php echo $after_widget; ?>
			<?php
		}

		function ajax_wpt_widget_content() {
			$tab = $_POST['tab'];
      $number = isset( $_POST['widget_number'] ) ? intval( $_POST['widget_number'] ) : '';
			$page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
      $style = isset( $_POST['style'] ) ? $_POST['style'] : '';
      $preview = isset( $_POST['preview'] ) ? $_POST['preview'] : '';

			if ( $page < 1 ) $page = 1;

			$wpt_widgets = new wpt_widget();
			$settings = $wpt_widgets->get_settings();

			if ( '1' === $preview ) {
				$args = array( 'tabs' => get_option('wptp_preview_tabs') );
			} else {
				if ( isset( $settings[ $number ] ) ) {
					$args = $settings[ $number ];
				}
			}

			$class_prefix = !empty( $style ) ? 'wptp' : 'wpt';

			$tab_args = array_merge( $args['tabs'][ $tab ], array( 'page' => $page, 'class_prefix' => $class_prefix, 'widget_number' => $number, 'tab_key' => $tab ) );

			$tab_arr = explode( '-', $tab );

			wpt_get_template(
				'tab/'.$tab_arr[0].'.php',
				$tab_args
			);

			die(); // required to return a proper result
		}

		function ajax_preview_widget() {
			$data = $_POST['form_data'];
			$template = $_POST['template_data'];

			parse_str( $data, $options );

			$key = 'widget-'.$options['id_base'];
      $num = isset( $options['widget_number'] ) ? $options['widget_number'] : '';
			if ( isset( $options[ $key ][ $num ] ) ) {
				$instance = $options[ $key ][ $num ];
			} else {
				$instance = $options[ $key ][ $options['multi_number'] ];
			}
			// fix slashes
			foreach ( $instance as $key => $value ) {

				if ( is_string( $value ) ) {

					$instance[ $key ] = stripslashes( $value );
				}
			}

			if ( !empty( $template ) ) {

				parse_str( $template, $template_options );

				foreach ( $template_options as $key => $value ) {

					if ( is_string( $value ) ) {

						$template_options[ $key ] = stripslashes( $value );
					}
				}

				$w_template_options = $template_options['modal-option-color'];
				$w_template_options['style'] = $template_options['modal-option-preset-main'];
				$w_template_options['pagination_style'] = $template_options['modal-option-preset-nav'];
				$w_template_options['loader'] = $template_options['modal-option-preset-loader'];

				$instance['template'] = $w_template_options;
			}

			update_option('wptp_preview_tabs', $instance['tabs']);

			the_widget( $options['id_base'], $instance, array('widget_id' => $options['widget-id'] ) );

			die();
		}

		function get_icons() {
			$icons = array(
				__( 'Web Application Icons', 'wp-tab-widget' ) => array(
					'adjust', 'anchor', 'archive', 'area-chart', 'arrows', 'arrows-h', 'arrows-v', 'asterisk', 'at', 'balance-scale', 'ban', 'bar-chart', 'barcode', 'bars', 'battery-empty', 'battery-full', 'battery-half', 'battery-quarter', 'battery-three-quarters', 'bed', 'beer', 'bell', 'bell-o', 'bell-slash', 'bell-slash-o', 'bicycle', 'binoculars', 'birthday-cake', 'bolt', 'bomb', 'book', 'bookmark', 'bookmark-o', 'briefcase', 'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 'bus', 'calculator', 'calendar', 'calendar-check-o', 'calendar-minus-o', 'calendar-o', 'calendar-plus-o', 'calendar-times-o', 'camera', 'camera-retro', 'car', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'cart-arrow-down', 'cart-plus', 'cc', 'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'child', 'circle', 'circle-o', 'circle-o-notch', 'circle-thin', 'clock-o', 'clone', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 'commenting', 'commenting-o', 'comments', 'comments-o', 'compass', 'copyright', 'creative-commons', 'credit-card', 'crop', 'crosshairs', 'cube', 'cubes', 'cutlery', 'database', 'desktop', 'diamond', 'dot-circle-o', 'download', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'envelope-square', 'eraser', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'eyedropper', 'fax', 'female', 'fighter-jet', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-pdf-o', 'file-powerpoint-o', 'file-video-o', 'file-word-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flask', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'futbol-o', 'gamepad', 'gavel', 'gift', 'glass', 'globe', 'graduation-cap', 'hand-lizard-o', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'hdd-o', 'headphones', 'heart', 'heart-o', 'heartbeat', 'history', 'home', 'hourglass', 'hourglass-end', 'hourglass-half', 'hourglass-o', 'hourglass-start', 'i-cursor', 'inbox', 'industry', 'info', 'info-circle', 'key', 'keyboard-o', 'language', 'laptop', 'leaf', 'lemon-o', 'level-down', 'level-up', 'life-ring', 'lightbulb-o', 'line-chart', 'location-arrow', 'lock', 'magic', 'magnet', 'male', 'map', 'map-marker', 'map-o', 'map-pin', 'map-signs', 'meh-o', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'money', 'moon-o', 'motorcycle', 'mouse-pointer', 'music', 'newspaper-o', 'object-group', 'object-ungroup', 'paint-brush', 'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 'pencil-square-o', 'phone', 'phone-square', 'picture-o', 'pie-chart', 'plane', 'plug', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'quote-left', 'quote-right', 'random', 'recycle', 'refresh', 'registered', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 'search', 'search-minus', 'search-plus', 'server', 'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'ship', 'shopping-cart', 'sign-in', 'sign-out', 'signal', 'sitemap', 'sliders', 'smile-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'space-shuttle', 'spinner', 'spoon', 'square', 'square-o', 'star', 'star-half', 'star-half-o', 'star-o', 'sticky-note', 'sticky-note-o', 'street-view', 'suitcase', 'sun-o', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'television', 'terminal', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 'toggle-off', 'toggle-on', 'trademark', 'trash', 'trash-o', 'tree', 'trophy', 'truck', 'tty', 'umbrella', 'university', 'unlock', 'unlock-alt', 'upload', 'user', 'user-plus', 'user-secret', 'user-times', 'users', 'video-camera', 'volume-down', 'volume-off', 'volume-up', 'wheelchair', 'wifi', 'wrench'
				),
				__( 'Hand Icons', 'wp-tab-widget' ) => array(
					'hand-lizard-o', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up'
				),
				__( 'Transportation Icons', 'wp-tab-widget' ) => array(
					'ambulance', 'bicycle', 'bus', 'car', 'fighter-jet', 'motorcycle', 'plane', 'rocket', 'ship', 'space-shuttle', 'subway', 'taxi', 'train', 'truck', 'wheelchair'
				),
				__( 'Gender Icons', 'wp-tab-widget' ) => array(
					'genderless', 'mars', 'mars-double', 'mars-stroke', 'mars-stroke-h', 'mars-stroke-v', 'mercury', 'neuter', 'transgender', 'transgender-alt', 'venus', 'venus-double', 'venus-mars'
				),
				__( 'File Type Icons', 'wp-tab-widget' ) => array(
					'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-o', 'file-pdf-o', 'file-powerpoint-o', 'file-text', 'file-text-o', 'file-video-o', 'file-word-o'
				),
				__( 'Spinner Icons', 'wp-tab-widget' ) => array(
					'circle-o-notch', 'cog', 'refresh', 'spinner'
				),
				__( 'Form Control Icons', 'wp-tab-widget' ) => array(
					'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o'
				),
				__( 'Payment Icons', 'wp-tab-widget' ) => array(
					'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'credit-card', 'google-wallet', 'paypal'
				),
				__( 'Chart Icons', 'wp-tab-widget' ) => array(
					'area-chart', 'bar-chart', 'line-chart', 'pie-chart'
				),
				__( 'Currency Icons', 'wp-tab-widget' ) => array(
					'btc', 'eur', 'gbp', 'gg', 'gg-circle', 'ils', 'inr', 'jpy', 'krw', 'money', 'rub', 'try', 'usd'
				),
				__( 'Text Editor Icons', 'wp-tab-widget' ) => array(
					'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain-broken', 'clipboard', 'columns', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'floppy-o', 'font', 'header', 'indent', 'italic', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'paragraph', 'repeat', 'scissors', 'strikethrough', 'subscript', 'superscript', 'table', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo'
				),
				__( 'Directional Icons', 'wp-tab-widget' ) => array(
					'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'exchange', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up'
				),
				__( 'Video Player Icons', 'wp-tab-widget' ) => array(
					'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 'play', 'play-circle', 'play-circle-o', 'random', 'step-backward', 'step-forward', 'stop', 'youtube-play'
				),
				__( 'Brand Icons', 'wp-tab-widget' ) => array(
					'500px', 'adn', 'amazon', 'android', 'angellist', 'apple', 'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 'black-tie', 'btc', 'buysellads', 'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'chrome', 'codepen', 'connectdevelop', 'contao', 'css3', 'dashcube', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 'drupal', 'empire', 'expeditedssl', 'facebook', 'facebook-official', 'facebook-square', 'firefox', 'flickr', 'fonticons', 'forumbee', 'foursquare', 'get-pocket', 'gg', 'gg-circle', 'git', 'git-square', 'github', 'github-alt', 'github-square', 'google', 'google-plus', 'google-plus-square', 'google-wallet', 'gratipay', 'hacker-news', 'houzz', 'html5', 'instagram', 'internet-explorer', 'ioxhost', 'joomla', 'jsfiddle', 'lastfm', 'lastfm-square', 'leanpub', 'linkedin', 'linkedin-square', 'linux', 'maxcdn', 'meanpath', 'medium', 'odnoklassniki', 'odnoklassniki-square', 'opencart', 'openid', 'opera', 'optin-monster', 'pagelines', 'paypal', 'pied-piper', 'pied-piper-alt', 'pinterest', 'pinterest-p', 'pinterest-square', 'qq', 'rebel', 'reddit', 'reddit-square', 'renren', 'safari', 'sellsy', 'share-alt', 'share-alt-square', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'skype', 'slack', 'slideshare', 'soundcloud', 'spotify', 'stack-exchange', 'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'stumbleupon-circle', 'tencent-weibo', 'trello', 'tripadvisor', 'tumblr', 'tumblr-square', 'twitch', 'twitter', 'twitter-square', 'viacoin', 'vimeo', 'vimeo-square', 'vine', 'vk', 'weibo', 'weixin', 'whatsapp', 'wikipedia-w', 'windows', 'wordpress', 'xing', 'xing-square', 'y-combinator', 'yahoo', 'yelp', 'youtube', 'youtube-play', 'youtube-square'
				),
				__( 'Medical Icons', 'wp-tab-widget' ) => array(
					'ambulance', 'h-square', 'heart', 'heart-o', 'heartbeat', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 'wheelchair'
				)
			);

			return apply_filters( 'wpt_icons', $icons );
		}

		function get_presets( $type = 'general' ) {
			$images = apply_filters(
				'wpt_presets',
				array(
					'general' => array(
						'' => array(
							'image' => WPT_PLUGIN_URL.'img/style-0.jpg',
							'colors' => array(
								'bg'=> '',
								'color'=> '',
								'link_color'=> '',
								'link_hover_color'=> '',
								'list_hover_bg'=> '',
								'tab_bg'=> '',
								'tab_color' => '',
								'tab_hover_bg' => '',
								'tab_hover_color' => '',
								'tab_active_bg' => '',
								'tab_active_color' => '',
							)
						),
						'1' => array(
							'image' => WPT_PLUGIN_URL.'img/style-1.jpg',
							'colors' => array(
								'bg'=> '#fafafa',
								'color'=> '#91969D',
								'link_color'=> '#525A66',
								'link_hover_color'=> '#E94F6F',
								'list_hover_bg'=> '#fcfcfc',
								'tab_bg'=> '#ffffff',
								'tab_color' => '#e94e6f',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '',
								'tab_active_color' => '#293241',
							)
						),
						'2' => array(
							'image' => WPT_PLUGIN_URL.'img/style-2.jpg',
							'colors' => array(
								'bg'=> '#fafafa',
								'color'=> '#293241',
								'link_color'=> '#293241',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '#ffffff',
								'tab_bg'=> '#efefef',
								'tab_color' => '#e73b5f',
								'tab_hover_bg' => '#ffffff',
								'tab_hover_color' => '#293241',
								'tab_active_bg' => '#ffffff',
								'tab_active_color' => '#293241',
							)
						),
						'3' => array(
							'image' => WPT_PLUGIN_URL.'img/style-3.jpg',
							'colors' => array(
								'bg'=> '#fafafa',
								'color'=> '#293241',
								'link_color'=> '#293241',
								'link_hover_color'=> '#e73b5f',
								'list_hover_bg'=> '#fcfcfc',
								'tab_bg'=> '#ffffff',
								'tab_color' => '#293241',
								'tab_hover_bg' => '#ffffff',
								'tab_hover_color' => '#e73b5f',
								'tab_active_bg' => '#e73b5f',
								'tab_active_color' => '#ffffff',
							)
						),
						'4' => array(
							'image' => WPT_PLUGIN_URL.'img/style-4.jpg',
							'colors' => array(
								'bg'=> '#ffffff',
								'color'=> '#293241',
								'link_color'=> '#293241',
								'link_hover_color'=> '#e73b5f',
								'list_hover_bg'=> '',
								'tab_bg'=> '#f4f4f4',
								'tab_color' => '#293241',
								'tab_hover_bg' => '#f4f4f4',
								'tab_hover_color' => '#e73b5f',
								'tab_active_bg' => '#ffffff',
								'tab_active_color' => '#e73b5f',
							)
						),
						'5' => array(
							'image' => WPT_PLUGIN_URL.'img/style-5.jpg',
							'colors' => array(
								'bg'=> '#ffffff',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '#fafafa',
								'tab_color' => '#7d828b',
								'tab_hover_bg' => '#fafafa',
								'tab_hover_color' => '#3d4653',
								'tab_active_bg' => '#fafafa',
								'tab_active_color' => '#e73b5f',
							)
						),
						'6' => array(
							'image' => WPT_PLUGIN_URL.'img/style-6.jpg',
							'colors' => array(
								'bg'=> '#ffffff',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#E94F6F',
								'list_hover_bg'=> '',
								'tab_bg'=> '#fafafa',
								'tab_color' => '#7d828b',
								'tab_hover_bg' => '#ffffff',
								'tab_hover_color' => '#e73b5f',
								'tab_active_bg' => '#ffffff',
								'tab_active_color' => '#e73b5f',
							)
						),
						'7' => array(
							'image' => WPT_PLUGIN_URL.'img/style-7.jpg',
							'colors' => array(
								'bg'=> '#293241',
								'color'=> '#686F79',
								'link_color'=> '#A9ADB3',
								'link_hover_color'=> '#ffffff',
								'list_hover_bg'=> '#323c4c',
								'tab_bg'=> '#323c4c',
								'tab_color' => '#C1C4C9',
								'tab_hover_bg' => '#323c4c',
								'tab_hover_color' => '#ffffff',
								'tab_active_bg' => '#424f63',
								'tab_active_color' => '#ffffff',
							)
						),
						'8' => array(
							'image' => WPT_PLUGIN_URL.'img/style-8.jpg',
							'colors' => array(
								'bg'=> '#ffffff',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '#323c4c',
								'tab_color' => '#adb1b7',
								'tab_hover_bg' => '#293241',
								'tab_hover_color' => '#ffffff',
								'tab_active_bg' => '#ffffff',
								'tab_active_color' => '#293241',
							)
						),
						'9' => array(
							'image' => WPT_PLUGIN_URL.'img/style-9.jpg',
							'colors' => array(
								'bg'=> '#ffffff',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '#323c4c',
								'tab_color' => '#adb1b7',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#ffffff',
								'tab_active_bg' => '#ffffff',
								'tab_active_color' => '#293241',
							)
						),
						'10' => array(
							'image' => WPT_PLUGIN_URL.'img/style-10.jpg',
							'colors' => array(
								'bg'=> '#333C4D',
								'color'=> '#6F7682',
								'link_color'=> '#ADB1B7',
								'link_hover_color'=> '#ffffff',
								'list_hover_bg'=> '',
								'tab_bg'=> '#e73b5f',
								'tab_color' => '#ffffff',
								'tab_hover_bg' => '',
								'tab_hover_color' => '',
								'tab_active_bg' => '#ffffff',
								'tab_active_color' => '#e73b5f',
							)
						),
						'11' => array(
							'image' => WPT_PLUGIN_URL.'img/style-11.png',
							'colors' => array(
								'bg'=> '',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '',
								'tab_color' => '#7f848d',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '#e73b5f',
								'tab_active_color' => '#ffffff',
							)
						),
						'12' => array(
							'image' => WPT_PLUGIN_URL.'img/style-12.png',
							'colors' => array(
								'bg'=> '',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '',
								'tab_color' => '#7f848d',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '',
								'tab_active_color' => '#e73b5f',
							)
						),
						'13' => array(
							'image' => WPT_PLUGIN_URL.'img/style-13.png',
							'colors' => array(
								'bg'=> '',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '',
								'tab_color' => '#7f848d',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '',
								'tab_active_color' => '#e73b5f',
							)
						),
						'14' => array(
							'image' => WPT_PLUGIN_URL.'img/style-14.png',
							'colors' => array(
								'bg'=> '',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '',
								'tab_color' => '#7f848d',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '',
								'tab_active_color' => '#e73b5f',
							)
						),
						'15' => array(
							'image' => WPT_PLUGIN_URL.'img/style-15.png',
							'colors' => array(
								'bg'=> '',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '',
								'tab_color' => '#7f848d',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '',
								'tab_active_color' => '#e73b5f',
							)
						),
						'16' => array(
							'image' => WPT_PLUGIN_URL.'img/style-16.png',
							'colors' => array(
								'bg'=> '',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '',
								'tab_color' => '#7f848d',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '',
								'tab_active_color' => '#e73b5f',
							)
						),
						'17' => array(
							'image' => WPT_PLUGIN_URL.'img/style-17.png',
							'colors' => array(
								'bg'=> '#ffffff',
								'color'=> '#9499A0',
								'link_color'=> '#535B67',
								'link_hover_color'=> '#293241',
								'list_hover_bg'=> '',
								'tab_bg'=> '#e73b5f',
								'tab_color' => '#ffffff',
								'tab_hover_bg' => '#ce2d4f',
								'tab_hover_color' => '#ffffff',
								'tab_active_bg' => '#ffffff',
								'tab_active_color' => '#293241',
							)
						),
						'18' => array(
							'image' => WPT_PLUGIN_URL.'img/style-18.jpg',
							'colors' => array(
								'bg'=> '#fafafa',
								'color'=> '#91969D',
								'link_color'=> '#525A66',
								'link_hover_color'=> '#E94F6F',
								'list_hover_bg'=> '#ffffff',
								'tab_bg'=> '#ebebeb',
								'tab_color' => '#878a90',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '#fafafa',
								'tab_active_color' => '#3E4654',
							)
						),
						'19' => array(
							'image' => WPT_PLUGIN_URL.'img/style-19.jpg',
							'colors' => array(
								'bg'=> '#293241',
								'color'=> '#686F79',
								'link_color'=> '#A9ADB3',
								'link_hover_color'=> '#ffffff',
								'list_hover_bg'=> '#384355',
								'tab_bg'=> '#48566c',
								'tab_color' => '#C1C4C9',
								'tab_hover_bg' => '#323c4c',
								'tab_hover_color' => '#ffffff',
								'tab_active_bg' => '#e73b5f',
								'tab_active_color' => '#ffffff',
							)
						),
						'20' => array(
							'image' => WPT_PLUGIN_URL.'img/style-20.png',
							'colors' => array(
								'bg'=> '#fafafa',
								'color'=> '#91969D',
								'link_color'=> '#525A66',
								'link_hover_color'=> '#E94F6F',
								'list_hover_bg'=> '#ffffff',
								'tab_bg'=> '#ebebeb',
								'tab_color' => '#878a90',
								'tab_hover_bg' => '',
								'tab_hover_color' => '#3e4654',
								'tab_active_bg' => '#fafafa',
								'tab_active_color' => '#3E4654',
							)
						),
					),
					'pagination' => array(
						'1' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-1.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#ffffff',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '',
								'nav_hover_color' => '#e73b5f',
								'nav_active_bg'=> '',
								'nav_active_color' => '#e73b5f',
							)
						),
						'2' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-2.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#fafafa',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '',
								'nav_hover_color' => '#e73b5f',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'3' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-3.png',
							'colors' => array(
								'nav_bg'=> '#fafafa',
								'nav_color'=> '#e73b5f',
								'nav_button_bg'=> '#fafafa',
								'nav_button_color'=> '#e73b5f',
								'nav_hover_bg'=> '#ffffff',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '#ffffff',
								'nav_active_color' => '#293241',
							)
						),
						'4' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-4.png',
							'colors' => array(
								'nav_bg'=> '#fafafa',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#fafafa',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#ffffff',
								'nav_hover_color' => '#e73b5f',
								'nav_active_bg'=> '#ffffff',
								'nav_active_color' => '#e73b5f',
							)
						),
						'5' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-5.png',
							'colors' => array(
								'nav_bg'=> '#fafafa',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#fafafa',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#ffffff',
								'nav_hover_color' => '#e73b5f',
								'nav_active_bg'=> '#ffffff',
								'nav_active_color' => '#e73b5f',
							)
						),
						'6' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-6.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#ffffff',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#fafafa',
								'nav_hover_color' => '#e73b5f',
								'nav_active_bg'=> '#fafafa',
								'nav_active_color' => '#e73b5f',
							)
						),
						'7' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-7.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#e73b5f',
								'nav_button_bg'=> '#ffffff',
								'nav_button_color'=> '#e73b5f',
								'nav_hover_bg'=> '#293241',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#293241',
								'nav_active_color' => '#ffffff',
							)
						),
						'8' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-8.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#e73b5f',
								'nav_button_bg'=> '#ffffff',
								'nav_button_color'=> '#e73b5f',
								'nav_hover_bg'=> '#293241',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#293241',
								'nav_active_color' => '#ffffff',
							)
						),
						'9' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-9.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#e73b5f',
								'nav_button_bg'=> '#ffffff',
								'nav_button_color'=> '#e73b5f',
								'nav_hover_bg'=> '',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '#fafafa',
								'nav_active_color' => '#293241',
							)
						),
						'10' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-10.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#fcfcfc',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'11' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-11.png',
							'colors' => array(
								'nav_bg'=> '#ffffff',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#384355',
								'nav_button_color'=> '#afb4bb',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'12' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-12.png',
							'colors' => array(
								'nav_bg'=> '#fafafa',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#fafafa',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#ffffff',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '#ffffff',
								'nav_active_color' => '#293241',
							)
						),
						'13' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-13.png',
							'colors' => array(
								'nav_bg'=> '#fafafa',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#fafafa',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#ffffff',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '#ffffff',
								'nav_active_color' => '#293241',
							)
						),
						'14' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-14.png',
							'colors' => array(
								'nav_bg'=> '#ebebeb',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#ebebeb',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#f6f6f6',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '#f6f6f6',
								'nav_active_color' => '#293241',
							)
						),
						'15' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-15.png',
							'colors' => array(
								'nav_bg'=> '#ebebeb',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#ebebeb',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#f6f6f6',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '#f6f6f6',
								'nav_active_color' => '#293241',
							)
						),
						'16' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-16.png',
							'colors' => array(
								'nav_bg'=> '#ebebeb',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '#ebebeb',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#f6f6f6',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '#f6f6f6',
								'nav_active_color' => '#293241',
							)
						),
						'17' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-17.png',
							'colors' => array(
								'nav_bg'=> '#323c4c',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#293241',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'18' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-18.png',
							'colors' => array(
								'nav_bg'=> '#323c4c',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#323c4c',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#384355',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#384355',
								'nav_active_color' => '#ffffff',
							)
						),
						'19' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-19.png',
							'colors' => array(
								'nav_bg'=> '#323c4c',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#323c4c',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'20' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-20.png',
							'colors' => array(
								'nav_bg'=> '#323c4c',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#323c4c',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'21' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-21.png',
							'colors' => array(
								'nav_bg'=> '#323c4c',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#323c4c',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#384355',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#384355',
								'nav_active_color' => '#ffffff',
							)
						),
						'22' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-22.png',
							'colors' => array(
								'nav_bg'=> '#e73b5f',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#e73b5f',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#323c4c',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#323c4c',
								'nav_active_color' => '#ffffff',
							)
						),
						'23' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-23.png',
							'colors' => array(
								'nav_bg'=> '#323c4c',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#323c4c',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'24' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-24.png',
							'colors' => array(
								'nav_bg'=> '#48566c',
								'nav_color'=> '#ffffff',
								'nav_button_bg'=> '#48566c',
								'nav_button_color'=> '#ffffff',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'25' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-25.png',
							'colors' => array(
								'nav_bg'=> '',
								'nav_color'=> '#414141',
								'nav_button_bg'=> '',
								'nav_button_color'=> '#414141',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'26' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-26.png',
							'colors' => array(
								'nav_bg'=> '',
								'nav_color'=> '#414141',
								'nav_button_bg'=> '',
								'nav_button_color'=> '#414141',
								'nav_hover_bg'=> '#384355',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#384355',
								'nav_active_color' => '#ffffff',
							)
						),
						'27' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-27.png',
							'colors' => array(
								'nav_bg'=> '',
								'nav_color'=> '#414141',
								'nav_button_bg'=> '',
								'nav_button_color'=> '#414141',
								'nav_hover_bg'=> '',
								'nav_hover_color' => '#293241',
								'nav_active_bg'=> '',
								'nav_active_color' => '#293241',
							)
						),
						'28' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-28.png',
							'colors' => array(
								'nav_bg'=> '',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
						'29' => array(
							'image' => WPT_PLUGIN_URL.'img/pagination-29.png',
							'colors' => array(
								'nav_bg'=> '',
								'nav_color'=> '#293241',
								'nav_button_bg'=> '',
								'nav_button_color'=> '#293241',
								'nav_hover_bg'=> '#e73b5f',
								'nav_hover_color' => '#ffffff',
								'nav_active_bg'=> '#e73b5f',
								'nav_active_color' => '#ffffff',
							)
						),
					),
					'loaders' => array(
						'wpt-load8' => '',//WPT_PLUGIN_URL.'img/style-0.jpg',
						'wpt-load1' => '',//WPT_PLUGIN_URL.'img/style-0.jpg',
						'wpt-load4' => '',//WPT_PLUGIN_URL.'img/style-0.jpg',
						'wpt-load5' => '',//WPT_PLUGIN_URL.'img/style-0.jpg',
						'wpt-load6' => '',//WPT_PLUGIN_URL.'img/style-0.jpg',
						'wpt-load7' => '',//WPT_PLUGIN_URL.'img/style-0.jpg',
					)
				)
			);

			return $images[ $type ];
		}
	}
}
add_action( 'widgets_init', function() {
	register_widget( 'wpt_widget' );
} );

// post view count
// AJAX is used to support caching plugins
add_filter('the_content', 'wpt_pro_view_count_js'); // outputs JS for AJAX call on single
add_action('wp_ajax_wpt_view_count', 'ajax_wpt_pro_view_count');
add_action('wp_ajax_nopriv_wpt_view_count','ajax_wpt_pro_view_count');
// prevent additional ajax call if theme has view counter already
add_action('mts_view_count_after_update', 'wpt_pro_update_view_count');

function wpt_pro_view_count_js( $content ) {
	global $post;
	$id = $post->ID;
	$use_ajax = apply_filters( 'mts_view_count_cache_support', true );

	$exclude_admins = apply_filters( 'mts_view_count_exclude_admins', false ); // pass in true or a user capaibility
	if ($exclude_admins === true) $exclude_admins = 'edit_posts';
	if ($exclude_admins && current_user_can( $exclude_admins )) return $content; // do not count post views here

	if (is_single()) {
		if ( ! has_filter('the_content', 'mts_view_count_js') && $use_ajax) { // prevent additional ajax call if theme has view counter already
			// enqueue jquery
			wp_enqueue_script( 'jquery' );

			$url = admin_url( 'admin-ajax.php' );
			$content .= "
						<script type=\"text/javascript\">
						jQuery(document).ready(function($) {
							$.post('{$url}', {action: 'wpt_view_count', id: '{$id}'});
						});
						</script>";

		}

		// if there's no general filter set and ajax is OFF
		if (! has_filter('the_content', 'mts_view_count_js') && ! $use_ajax) {
			wpt_pro_update_view_count($id);
		}
	}

	return $content;
}


function ajax_wpt_pro_view_count() {
	// do count
	$post_id = $_POST['id'];
	wpt_pro_update_view_count( $post_id );
}
function wpt_pro_update_view_count( $post_id ) {
	$count = get_post_meta( $post_id, '_wpt_view_count', true );
	update_post_meta( $post_id, '_wpt_view_count', $count + 1 );
}

// Add meta for all existing posts that don't have it
// to make them show up in Popular tab
function wpt_pro_add_views_meta_for_posts() {
	$allposts = get_posts( 'numberposts=-1&post_type=post&post_status=any' );

	foreach( $allposts as $postinfo ) {
		add_post_meta( $postinfo->ID, '_wpt_view_count', 0, true );
	}
}

// Reset post count for specific post or all posts
function wpt_pro_reset_post_count($post_id = 0) {
	if ($post_id == 0) {
		$allposts = get_posts( 'numberposts=-1&post_type=post&post_status=any' );
		foreach( $allposts as $postinfo ) {
			update_post_meta( $postinfo->ID, '_wpt_view_count', '0' );
		}
	} else {
		update_post_meta( $post_id, '_wpt_view_count', '0' );
	}
}

// add post meta on plugin activation
function wpt_pro_plugin_activation() {
	wpt_pro_add_views_meta_for_posts();
}
register_activation_hook( __FILE__, 'wpt_pro_plugin_activation' );

// unregister MTS Tabs Widget and Tabs Widget v2
add_action('widgets_init', 'wpt_pro_unregister_mts_tabs_widget', 100);
function wpt_pro_unregister_mts_tabs_widget() {
	unregister_widget('mts_Widget_Tabs_2');
	unregister_widget('mts_Widget_Tabs');
}

if ( ! function_exists( 'wpt_excerpt' ) ) {
	function wpt_excerpt($limit = 10) {
		$limit++;
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		} else {
			$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		return $excerpt;
	}
}

if ( ! function_exists( 'wpt_post_title' ) ) {
	function wpt_post_title($limit = 10, $id = false) {
		$title = $id ? get_the_title( $id ) : get_the_title();
		$limit++;
		$title = explode(' ', $title, $limit);
		if (count($title)>=$limit) {
			array_pop($title);
			$title = implode(" ",$title).'...';
		} else {
			$title = implode(" ",$title);
		}
		return $title;
	}
}
if ( ! function_exists( 'wpt_truncate' ) ) {
	function wpt_truncate($str, $length = 24) {
		if (mb_strlen($str) > $length) {
			return mb_substr($str, 0, $length).'...';
		} else {
			return $str;
		}
	}
}

function wptp_get_defaults() {
	$defaults = array(
		'tabs' => array(
			'popular' => array(
				'tab_name' => __('Popular', 'wp-tab-widget'),
				'icon' => '',
				'popular_query_by' => 'views',
				'num_post' => '5',
				'days' => '',
				'custom_days' => '90',
				'show_thumbnail' => '1',
				'thumb_size' => 'small',
				'small_thumb_align' => 'left',
				'custom_thumb_align' => 'left',
				'custom_thumb_w' => '65',
				'custom_thumb_h' => '65',
				'show_date' => '1',
				'show_comment' => '1',
				'show_excerpt' => '0',
				'excerpt_length' => '15',
				'allow_pagination' => '1',
				'title_length' => '10'
			),
			'latest' => array(
				'tab_name' => __('Latest', 'wp-tab-widget'),
				'icon' => '',
				'num_post' => '5',
				'show_thumbnail' => '1',
				'thumb_size' => 'small',
				'small_thumb_align' => 'left',
				'custom_thumb_align' => 'left',
				'custom_thumb_w' => '65',
				'custom_thumb_h' => '65',
				'show_date' => '1',
				'show_comment' => '1',
				'show_excerpt' => '0',
				'excerpt_length' => '15',
				'allow_pagination' => '1',
				'title_length' => '10'
			),
			'products' => array(
				'tab_name' => __('Products', 'wp-tab-widget'),
				'icon' => '',
				'num_post' => '5',
				'show_thumbnail' => '1',
				'thumb_size' => 'small',
				'small_thumb_align' => 'left',
				'custom_thumb_align' => 'left',
				'custom_thumb_w' => '65',
				'custom_thumb_h' => '65',
				'show_price' => '1',
				'show_rating' => '1',
				'show_excerpt' => '0',
				'excerpt_length' => '15',
				'allow_pagination' => '1',
				'title_length' => '10'
			),
			'cpt' => array(
				'tab_name' => __('Latest', 'wp-tab-widget'),
				'icon' => '',
				'num_post' => '5',
				'show_thumbnail' => '1',
				'thumb_size' => 'small',
				'small_thumb_align' => 'left',
				'custom_thumb_align' => 'left',
				'custom_thumb_w' => '65',
				'custom_thumb_h' => '65',
				'show_rating' => '1',
				'show_excerpt' => '0',
				'excerpt_length' => '15',
				'allow_pagination' => '1',
				'title_length' => '10'
			),
			'tags' => array(
				'tab_name' => __('Tags', 'wp-tab-widget'),
				'icon' => '',
				'terms_num' => '0',
				'terms_orderby' => 'name',
				'terms_order' => 'ASC',
			),
			'comments' => array(
				'tab_name' => __('Comments', 'wp-tab-widget'),
				'icon' => '',
				'num_comment' => '3',
				'show_avatar' => '1',
				'title_length' => '10',
				'allow_pagination' => '0',
			),
			'cats' => array(
				'tab_name' => __('Categories', 'wp-tab-widget'),
				'icon' => '',
				'terms_num' => '0',
				'terms_orderby' => 'name',
				'terms_order' => 'ASC',
			),
			'custom' => array(
				'tab_name' => __('Custom Content', 'wp-tab-widget'),
				'icon' => '',
				'custom_content' => '',
			),
		),
		'template' => array(
			'style' => '',
			'pagination_style' => '',
			'loader' => 'wpt-load8',
			'bg'=> '',
			'color'=> '',
			'link_color'=> '',
			'link_hover_color'=> '',
			'list_hover_bg'=> '',
			'tab_bg'=> '',
			'tab_color' => '',
			'tab_hover_bg' => '',
			'tab_hover_color' => '',
			'tab_active_bg' => '',
			'tab_active_color' => '',
			'nav_bg' => '',
			'nav_color' => '',
			'nav_button_bg' => '',
			'nav_button_color' => '',
			'nav_hover_bg'=> '',
			'nav_hover_color' => '',
			'nav_active_bg'=> '',
			'nav_active_color' => '',
		),
		'use_ajax' => '1'
	);

	return apply_filters( 'wpt_defaults', $defaults );
}

register_activation_hook( __FILE__, 'wptp_plugin_activate' );
function wptp_plugin_activate() {

	// Transfer free versions widget settings
	if ( false === get_option('wpt_upgraded') && !is_plugin_active( 'wp-tab-widget/wp-tab-widget.php' ) ) {

		$settings = get_option('widget_wpt_widget');
		$defaults = wptp_get_defaults();

		if ( $settings ) {

			foreach ( $settings as $key => $value ) {

				if ( isset( $value['tab_order'] ) ) {

					$available_tabs = array(
						'popular' => __('Popular', 'wp-tab-widget'),
						'recent' => __('Recent', 'wp-tab-widget'),
						'comments' => __('Comments', 'wp-tab-widget'),
						'tags' => __('Tags', 'wp-tab-widget')
					);

					array_multisort( (array) $value['tab_order'], $available_tabs );

					$tabs = (array) $value['tabs'];
					$new_tabs = array();

					foreach ( $available_tabs as $tab => $label ) {

						if ( ! empty( $tabs[ $tab ] ) ) {

							switch ( $tab ) {

								case 'popular':
									$options = array(
										'tab_name' => $label,
										'num_post' => $value['post_num'],
										'show_thumbnail' => ( $value['show_thumb'] ? '1' : '0' ),
										'thumb_size' => $value['thumb_size'],
										'show_date' => ( $value['show_date'] ? '1' : '0' ),
										'show_comment' => ( $value['show_comment_num'] ? '1' : '0' ),
										'show_excerpt' => ( $value['show_excerpt'] ? '1' : '0' ),
										'excerpt_length' => $value['excerpt_length'],
										'allow_pagination' => ( $value['allow_pagination'] ? '1' : '0' ),
										'title_length' => $value['title_length']
									);
									$new_tabs['popular'] = wp_parse_args( $options, $defaults['tabs']['popular'] );
								break;

								case 'recent':
									$options = array(
										'tab_name' => $label,
										'num_post' => $value['post_num'],
										'show_thumbnail' => ( $value['show_thumb'] ? '1' : '0' ),
										'thumb_size' => $value['thumb_size'],
										'show_date' => ( $value['show_date'] ? '1' : '0' ),
										'show_comment' => ( $value['show_comment_num'] ? '1' : '0' ),
										'show_excerpt' => ( $value['show_excerpt'] ? '1' : '0' ),
										'excerpt_length' => $value['excerpt_length'],
										'allow_pagination' => ( $value['allow_pagination'] ? '1' : '0' ),
										'title_length' => $value['title_length']
									);
									$new_tabs['latest'] = wp_parse_args( $options, $defaults['tabs']['latest'] );
								break;

								case 'comments':
									$options = array(
										'tab_name' => $label,
										'num_comment' => $value['post_num'],
										'show_avatar' => ( $value['show_avatar'] ? '1' : '0' ),
										'allow_pagination' => ( $value['allow_pagination'] ? '1' : '0' ),
									);
									$new_tabs['comments'] = wp_parse_args( $options, $defaults['tabs']['comments'] );
								break;

								case 'tags':
									$options = array(
										'tab_name' => $label,
									);
									$new_tabs['tags'] = wp_parse_args( $options, $defaults['tabs']['tags'] );
								break;
							}
						}
					}

					$settings[ $key ] = array( 'tabs' => $new_tabs, 'template' => $defaults['template'] );
				}
			}

			update_option('widget_wpt_widget', $settings );
			update_option('wpt_upgraded', true );
			//_wp_sidebars_changed();
		}
	}
}
