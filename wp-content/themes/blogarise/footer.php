<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package BlogArise
 */ ?>

<!--==================== Missed ====================-->
<div class="missed">
<?php do_action('blogarise_action_footer_missed_section'); ?>
</div> 
<!-- end missed -->
  <!--==================== FOOTER AREA ====================-->
  <?php $blogarise_footer_widget_background = get_theme_mod('blogarise_footer_widget_background');
  $blogarise_footer_overlay_color = get_theme_mod('blogarise_footer_overlay_color'); ?>
  <footer class="footer<?php if($blogarise_footer_widget_background != '') { ?> back-img" style="background-image:url('<?php echo esc_url($blogarise_footer_widget_background);?>');" <?php } else { echo '"' ; }?>>
    <div class="overlay" <?php if($blogarise_footer_widget_background != '') { ?>style="background-color: <?php echo esc_html($blogarise_footer_overlay_color);?>;" <?php }?>>     
        <?php do_action('blogarise_action_footer_widget_area'); 
        do_action('blogarise_action_footer_bottom_area'); ?>
      <div class="bs-footer-copyright">
        <?php do_action('blogarise_action_footer_copyright') ;?>                    
      </div> 
    </div>
    <!--/overlay-->
  </footer>
  <!--/footer-->
</div>
<!--/wrapper-->
<?php blogarise_scrolltoup();
do_action('blogarise_action_search_model');
wp_footer(); ?>
</body>
</html>