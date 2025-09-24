<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package BlogArise
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
$blogarise_sidebar_stickey = get_theme_mod('blogarise_sidebar_stickey',true); ?>

<div id="sidebar-right" class="bs-sidebar <?php if($blogarise_sidebar_stickey == true) { ?> bs-sticky <?php } ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div>