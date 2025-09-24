<?php
/**
 * The template for displaying the Single content.
 * @package Blogarise
 */
?>
<!--==================== breadcrumb section ====================-->
  <!--col-lg-->
  <?php $blogarise_single_page_layout = get_theme_mod('blogarise_single_page_layout','single-align-content-right');
  if($blogarise_single_page_layout == "single-align-content-left") { ?>
    <aside class="col-lg-3">
      <?php get_sidebar();?>
    </aside>
  <?php } ?>

  <div class="<?php echo esc_attr($blogarise_single_page_layout == 'single-full-width-content' ? 'col-lg-12' : 'col-lg-9') ?>">
    <?php do_action('blogarise_action_main_single_content'); ?>
  </div>

  <?php if($blogarise_single_page_layout == "single-align-content-right") { ?>
    <!--sidebar-->
        <!--col-lg-3-->
          <aside class="col-lg-3">
            <?php get_sidebar();?>
          </aside>
        <!--/col-lg-3-->
    <!--/sidebar-->
  <?php } ?>