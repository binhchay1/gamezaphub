<?php function blogarise_header_default_section() { ?>
  <!--header-->
  <header class="bs-headthree">
    <!--top-bar-->
    <div class="bs-head-detail d-none d-lg-block">
      <?php do_action('blogarise_action_header_top_section'); ?>
    </div>
    <!--/top-bar-->
    <div class="clearfix"></div>
    <!-- Main Menu Area-->
    <div class="bs-header-main" style="background-image: url('');">
      <?php $banner_advertisement_section = blogarise_get_option('banner_advertisement_section');?>
      <div class="inner<?php if(empty($banner_advertisement_section)){ echo ' responsive'; } ?>">
        <div class="container">
          <div class="row align-items-center">
            <?php $center_logo_title = get_theme_mod('blogarise_center_logo_title',false); ?>
            <div class="navbar-header col d-none<?php echo esc_attr($center_logo_title == false ? ' text-md-start d-lg-block col-md-4' : ' d-lg-block col-md-12 text-center mx-auto'); ?>">
              <!-- Display the Custom Logo -->
              <div class="site-logo">
                  <?php if(get_theme_mod('custom_logo') !== ""){ the_custom_logo(); } ?>
              </div>
              <div class="site-branding-text <?php echo esc_attr( display_header_text() ? ' ' : 'd-none'); ?>">
                <?php if (is_front_page() || is_home()) { ?>
                  <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></h1>
                <?php } else { ?>
                  <p class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></p>
                <?php } ?>
                  <p class="site-description"><?php echo esc_html(get_bloginfo( 'description' )); ?></p>
              </div> 
            </div>
              <?php /* Banner Ad */ if (!empty(blogarise_get_option('banner_advertisement_section'))) { do_action('blogarise_action_banner_advertisement'); } ?>
          </div>
        </div>
      </div>
    </div>
    <!-- /Main Menu Area-->
    <div class="bs-menu-full">
      <nav class="navbar navbar-expand-lg navbar-wp">
        <div class="container">
          <!-- m-header -->
          <div class="m-header align-items-center justify-content-justify"> 
            <!-- navbar-toggle -->
            <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbar-wp" aria-controls="navbar-wp" aria-expanded="false"
                  aria-label="<?php esc_attr_e('Toggle navigation','blogarise'); ?>">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            <div class="navbar-header">
              <!-- Display the Custom Logo -->
              <div class="site-logo">
                <?php if(get_theme_mod('custom_logo') !== ""){ the_custom_logo(); } ?>
              </div>
              <div class="site-branding-text <?php echo esc_attr( display_header_text() ? ' ' : 'd-none'); ?>">
                <div class="site-title">
                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a>
                </div>
                <p class="site-description"><?php echo esc_html(get_bloginfo( 'description' )); ?></p>
              </div>
            </div>
            <div class="right-nav">
            <?php blogarise_menu_search(); ?>
            </div>
          </div>
          <!-- /m-header -->
          <!-- Navigation -->
          <div class="collapse navbar-collapse" id="navbar-wp">
            <?php wp_nav_menu( array(
              'theme_location' => 'primary',
              'container'  => 'nav-collapse collapse navbar-inverse-collapse',
              'menu_class' => 'nav navbar-nav'.(is_rtl() ? ' sm-rtl' :''),
              'fallback_cb' => 'blogarise_fallback_page_menu',
              'walker' => new blogarise_nav_walker()
            ) ); ?>
          </div>
          <!-- Right nav -->
          <div class="desk-header right-nav pl-3 ml-auto my-2 my-lg-0 position-relative align-items-center">
            <?php blogarise_menu_btns(); ?>
          </div>
          <!-- /Right nav -->
        </div>
      </nav>
    </div>
    <!--/main Menu Area-->
  </header>
  <!--/header-->
<?php } ?>