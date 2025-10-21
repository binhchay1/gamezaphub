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
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Google Fonts Optimization - Only load weights used in CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Rubik:wght@400;500;600;700&display=swap">
    </noscript>

    <script>
        ! function(e) {
            "use strict";
            var t = function(t, n, o) {
                var i, r = e.document,
                    a = r.createElement("link");
                if (n) i = n;
                else {
                    var l = (r.body || r.getElementsByTagName("head")[0]).childNodes;
                    i = l[l.length - 1]
                }
                var d = r.styleSheets;
                a.rel = "stylesheet", a.href = t, a.media = "only x",
                    function e(t) {
                        if (r.body) return t();
                        setTimeout(function() {
                            e(t)
                        })
                    }(function() {
                        i.parentNode.insertBefore(a, n ? i : i.nextSibling)
                    });
                var f = function(e) {
                    for (var t = a.href, n = d.length; n--;)
                        if (d[n].href === t) return e();
                    setTimeout(function() {
                        f(e)
                    })
                };
                return a.addEventListener && a.addEventListener("load", function() {
                    this.media = o || "all"
                }), a.onloadcssdefined = f, f(function() {
                    a.media !== o && (a.media = o)
                }), a
            };
            "undefined" != typeof exports ? exports.loadCSS = t : e.loadCSS = t
        }("undefined" != typeof global ? global : this);
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#content">
            <?php _e('Skip to content', 'bloggers'); ?></a>
        <?php $background_image = get_theme_support('custom-header', 'default-image');
        if (has_header_image()) {
            $background_image = get_header_image();
        } ?>
        <div class="wrapper" id="custom-background-css">
            <!--header-->
            <header class="bs-default" style="background-image:url('<?php echo esc_url($background_image); ?>')">
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
                                        <?php if (get_theme_mod('custom_logo') !== "") {
                                            the_custom_logo();
                                        } ?>
                                    </div>
                                    <div class="site-branding-text <?php echo esc_attr(display_header_text() ? ' ' : 'd-none'); ?>">
                                        <?php if (is_front_page() || is_home()) { ?>
                                            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a></h1>
                                        <?php } else { ?>
                                            <p class="site-title"> <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a></p>
                                        <?php } ?>
                                        <p class="site-description"><?php echo esc_html(get_bloginfo('description')); ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-4 d-none d-lg-flex justify-content-end">
                                    <!-- Right nav -->
                                    <div class="info-right right-nav d-flex align-items-center justify-content-center justify-content-md-end">
                                        <?php $blogarise_menu_search  = get_theme_mod('blogarise_menu_search', 'true');
                                        $blogarise_subsc_link = get_theme_mod('blogarise_subsc_link', '#');
                                        $blogarise_menu_subscriber  = get_theme_mod('blogarise_menu_subscriber', 'true');
                                        $blogarise_subsc_open_in_new  = get_theme_mod('blogarise_subsc_open_in_new', true);
                                        if ($blogarise_menu_search == true) { ?>
                                            <a class="msearch ml-auto" aria-label="Tìm kiếm" data-bs-target="#exampleModal" href="#" data-bs-toggle="modal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                                </svg>
                                            </a>
                                        <?php }
                                        if ($blogarise_menu_subscriber == true) { ?>
                                            <a class="subscribe-btn" href="<?php echo esc_url($blogarise_subsc_link); ?>" <?php if ($blogarise_subsc_open_in_new) { ?> target="_blank" <?php } ?>>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z" />
                                                </svg>
                                            </a>
                                        <?php }
                                        $blogarise_lite_dark_switcher = get_theme_mod('blogarise_lite_dark_switcher', 'true');
                                        if ($blogarise_lite_dark_switcher == true) { ?>
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
                                        <?php if (get_theme_mod('custom_logo') !== "") {
                                            the_custom_logo();
                                        } ?>
                                    </div>
                                    <div class="site-branding-text <?php echo esc_attr(display_header_text() ? ' ' : 'd-none'); ?>">
                                        <div class="site-title">
                                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a>
                                        </div>
                                        <p class="site-description"><?php echo esc_html(get_bloginfo('description')); ?></p>
                                    </div>
                                </div>
                                <div class="right-nav">
                                    <?php $blogarise_menu_search  = get_theme_mod('blogarise_menu_search', 'true');
                                    if ($blogarise_menu_search == true) { ?>
                                        <a class="msearch ml-auto" aria-label="Tìm kiếm" data-bs-target="#exampleModal" href="#" data-bs-toggle="modal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                            </svg>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- /Right nav -->
                            <!-- Navigation -->
                            <!-- Navigation -->
                            <div class="collapse navbar-collapse" id="navbar-wp">
                                <?php $blogarise_menu_align_setting = get_theme_mod('blogarise_menu_align_setting', 'mx-auto');
                                if (is_rtl()) {
                                    wp_nav_menu(array(
                                        'theme_location' => 'primary',
                                        'container'  => 'nav-collapse collapse',
                                        'menu_class' => 'nav navbar-nav sm-rtl',
                                        'fallback_cb' => 'blogarise_fallback_page_menu',
                                        'walker' => new blogarise_nav_walker()
                                    ));
                                } else {
                                    wp_nav_menu(array(
                                        'theme_location' => 'primary',
                                        'container'  => 'nav-collapse collapse',
                                        'menu_class' => $blogarise_menu_align_setting . ' nav navbar-nav',
                                        'fallback_cb' => 'blogarise_fallback_page_menu',
                                        'walker' => new blogarise_nav_walker()
                                    ));
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