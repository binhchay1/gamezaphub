<?php
if ( is_active_sidebar( 'primary-widgets-section' ) || is_active_sidebar( 'secondary-widgets-section' ) ) :
	$no_sidebar = is_active_sidebar( 'secondary-widgets-section' ) ? '' : 'no-frontpage-sidebar';
	?>
<div class="main-widget-section">
	<div class="ascendoor-wrapper">
		<div class="main-widget-section-wrap frontpage-right-sidebar <?php echo esc_attr( $no_sidebar ); ?>">
			<?php if ( is_active_sidebar( 'primary-widgets-section' ) ) { ?>
				<div class="primary-widgets-section ascendoor-widget-area">
					<?php dynamic_sidebar( 'primary-widgets-section' ); ?>
				</div>
			<?php } ?>
			<?php if ( is_active_sidebar( 'secondary-widgets-section' ) ) { ?>
				<div class="secondary-widgets-section ascendoor-widget-area">
					<?php dynamic_sidebar( 'secondary-widgets-section' ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php endif; ?>
