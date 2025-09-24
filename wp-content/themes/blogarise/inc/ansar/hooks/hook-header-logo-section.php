<?php
if (!function_exists('blogarise_header_logo_section')) :
/**
 *  Header
 *
 * @since blogarise
 *
 */
function blogarise_header_logo_section(){ ?>
    <!-- Main Menu Area-->
      <?php $background_image = get_theme_support( 'custom-header', 'default-image' );
        if ( has_header_image() ) { $background_image = get_header_image(); } ?>
      <div class="bs-header-main" style='background-image: url("<?php echo esc_url( $background_image ); ?>" );'>
        <div class="inner">
          <div class="container">
            <div class="row">
              <div class="navbar-header">
                  <!-- Display the Custom Logo -->
                  <div class="site-logo">
                      <?php if(get_theme_mod('custom_logo') !== ""){ the_custom_logo(); } ?>
                  </div>
                  <?php if (display_header_text()) : ?>
                    <div class="site-branding-text">
                      <p class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></p>
                      <p class="site-description"><?php echo esc_html(get_bloginfo( 'description' )); ?></p>
                    </div>
                  <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /Main Menu Area-->
<?php 
}
endif;
add_action('blogarise_action_header_logo_section', 'blogarise_header_logo_section', 4);