<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package bloggers
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<?php wp_body_open(); ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content">
<?php _e( 'Skip to content', 'bloggers' ); ?></a>
<?php $background_image = get_theme_support( 'custom-header', 'default-image' );
  if ( has_header_image() ) { $background_image = get_header_image(); } ?>
  <div class="wrapper" id="custom-background-css">
    <!--header-->
    <header class="bs-default" style="background-image:url('<?php echo esc_url( $background_image ); ?>')"> 
      <!-- Main Menu Area-->
      <div class="bs-header-main d-none d-lg-block">
        <div class="inner">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-4">
                <?php do_action('blogarise_action_header_social_section'); ?>
              </div>
              <div class="navbar-header col-lg-4">
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
              <div class="col-lg-4 d-none d-lg-flex justify-content-end">
                <!-- Right nav -->
                <div class="info-right right-nav d-flex align-items-center justify-content-center justify-content-md-end">
                  <?php $blogarise_menu_search  = get_theme_mod('blogarise_menu_search','true'); 
                    $blogarise_subsc_link = get_theme_mod('blogarise_subsc_link', '#'); 
                    $blogarise_menu_subscriber  = get_theme_mod('blogarise_menu_subscriber','true');
                    $blogarise_subsc_open_in_new  = get_theme_mod('blogarise_subsc_open_in_new', true);
                  if($blogarise_menu_search == true) { ?>
                <a class="msearch ml-auto" data-bs-target="#exampleModal" href="#" data-bs-toggle="modal">
                  <i class="fa fa-search"></i>
                </a> 
              <?php } if($blogarise_menu_subscriber == true) { ?>
                <a class="subscribe-btn" href="<?php echo esc_url($blogarise_subsc_link); ?>" <?php if($blogarise_subsc_open_in_new) { ?> target="_blank" <?php } ?> ><i class="fas fa-bell"></i></a>
              <?php } $blogarise_lite_dark_switcher = get_theme_mod('blogarise_lite_dark_switcher','true');
                if($blogarise_lite_dark_switcher == true){ ?>
                <label class="switch" for="switch">
                  <input type="checkbox" name="theme" id="switch">
                  <span class="slider"></span>
                </label>
              <?php } ?>     
                </div>
                <!-- /Right nav -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /Main Menu Area-->
      <div class="bs-menu-full">
        <div class="container">
          <nav class="navbar navbar-expand-lg navbar-wp"> 
            <!-- Right nav -->
            <div class="m-header align-items-center">
              <!-- navbar-toggle -->
              <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbar-wp" aria-controls="navbar-wp" aria-expanded="false"
                aria-label="Toggle navigation"> 
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
                <?php $blogarise_menu_search  = get_theme_mod('blogarise_menu_search','true'); 
                  if($blogarise_menu_search == true) { ?>
                    <a class="msearch ml-auto" data-bs-target="#exampleModal" href="#" data-bs-toggle="modal"> <i class="fa fa-search"></i> </a>
                <?php } ?>
              </div>
            </div>
            <!-- /Right nav -->
            <!-- Navigation -->
            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="navbar-wp">
              <?php $blogarise_menu_align_setting = get_theme_mod('blogarise_menu_align_setting','mx-auto');
                if(is_rtl()) { wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'  => 'nav-collapse collapse',
                        'menu_class' => 'nav navbar-nav sm-rtl',
                        'fallback_cb' => 'blogarise_fallback_page_menu',
                        'walker' => new blogarise_nav_walker()
                  ) ); 
                } else
                {
                  wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'  => 'nav-collapse collapse',
                    'menu_class' => $blogarise_menu_align_setting . ' nav navbar-nav',
                    'fallback_cb' => 'blogarise_fallback_page_menu',
                    'walker' => new blogarise_nav_walker()
                  ) );
              } ?>
            </div>
            <!-- Right nav -->
             
            <!-- /Right nav -->
          </nav>
        </div>
      </div>
      <!--/main Menu Area-->
    </header>
<!--mainfeatured start-->
<div class="mainfeatured mt-5">
    <!--container-->
    <div class="container">
        <!--row-->
        <div class="row">              
    <?php do_action('bloggers_action_front_page_main_section_1'); ?> 
        </div><!--/row-->
    </div><!--/container-->
</div>
<!--mainfeatured end-->
<?php
do_action('blogarise_action_featured_ads_section');
?>   