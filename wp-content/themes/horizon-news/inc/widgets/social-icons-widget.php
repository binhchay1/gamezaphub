<?php

/**
 * Social Icons Widget
 *
 * @since 1.0.0
 */
class Horizon_News_Social_Icons_Widget extends WP_Widget {

	/**
	 * Sets up a new Social Icons widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			// widget ID.
			'horizon_news_social_icons',
			// widget name.
			__( 'Ascendoor Social Icons', 'horizon-news' ),
			// widget description.
			array( 'description' => __( 'Adds a social icon menu.', 'horizon-news' ) )
		);
	}

	/**
	 * Outputs the content for the current Social Icons widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Social Icons widget instance.
	 */
	public function widget( $args, $instance ) {
		// Get menu.
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( ! $nav_menu ) {
			return;
		}

		// Check if the menu has a theme_location. If not, assign a default theme location.
		if ( empty( $nav_menu->theme_location ) ) {
			$nav_menu->theme_location = 'social';
		}

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		wp_nav_menu(
			array(
				'menu'           => $nav_menu,
				'theme_location' => $nav_menu->theme_location,
				'menu_class'     => 'social-links',
				'link_before'    => '<span class="screen-reader-text">',
				'link_after'     => '</span>',
			)
		);

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating settings for the current Navigation Menu widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	/**
	 * Outputs the settings form for the Social Icons widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 * @global WP_Customize_Manager $wp_customize
	 */
	public function form( $instance ) {
		$title    = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus.
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		if ( ! $menus ) {
			/* translators: %s: URL to create a new menu. */
			echo '<p>' . sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.', 'horizon-news' ), esc_url( admin_url( 'nav-menus.php' ) ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'horizon-news' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php esc_html_e( 'Select Menu:', 'horizon-news' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
				<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'horizon-news' ); ?></option>
				<?php
				foreach ( $menus as $menu ) {
					echo '<option value="' . esc_attr( $menu->term_id ) . '"' . selected( $nav_menu, $menu->term_id, false ) . '>' . esc_html( $menu->name ) . '</option>';
				}
				?>
			</select>
		</p>
		<?php
	}
}
