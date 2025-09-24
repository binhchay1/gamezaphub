<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package BlogArise
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<?php wp_body_open(); ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content">
<?php esc_html_e( 'Skip to content', 'blogarise' ); ?></a>
<?php $background_image = get_theme_support( 'custom-header', 'default-image' );
    if ( has_header_image() ) { $background_image = get_header_image(); } ?>

<!--wrapper-->
<div class="wrapper" id="custom-background-css">
    <?php if ( has_header_image() ) { ?> <img src="<?php echo esc_url( $background_image ); ?>"> <?php } ?>
    <!--==================== TOP BAR ====================-->
    <?php do_action('blogarise_action_blogarise_header_type_section'); ?>
    <!--mainfeatured start-->
    <div class="mainfeatured">
        <!--container-->
        <div class="container">
            <!--row-->
            <div class="row">              
                <?php do_action('blogarise_action_front_page_main_section_1'); ?>  
            </div><!--/row-->
        </div><!--/container-->
    </div>
    <!--mainfeatured end-->
<?php do_action('blogarise_action_featured_ads_section'); ?>        