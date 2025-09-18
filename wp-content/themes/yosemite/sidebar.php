<?php
	$sidebar = mts_custom_sidebar();
    if ( $sidebar != 'mts_nosidebar' ) {
?>
<aside class="sidebar c-4-12" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
	<div id="sidebars" class="g">
		<div class="sidebar">
			<?php if (!dynamic_sidebar($sidebar)) : ?>
				<div id="sidebar-search" class="widget panel">
					<h3 class="widget-title"><?php _e('Search', 'mythemeshop'); ?></h3>
					<?php get_search_form(); ?>
				</div>
				<div id="sidebar-archives" class="widget panel">
					<h3 class="widget-title"><?php _e('Archives', 'mythemeshop') ?></h3>
					<ul>
						<?php wp_get_archives( 'type=monthly' ); ?>
					</ul>
				</div>
				<div id="sidebar-meta" class="widget panel">
					<h3 class="widget-title"><?php _e('Meta', 'mythemeshop') ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div><!--sidebars-->
</aside>
<?php } ?>