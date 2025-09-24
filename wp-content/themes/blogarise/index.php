<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * @package BlogArise
 */
get_header(); ?>
<main id="content" class="index-class">
    <!--container-->
    <div class="container">
        <!--row-->
        <div class="row">
            <?php do_action('blogarise_action_main_content_layouts') ;?>
        </div><!--/row-->
    </div><!--/container-->
</main>                
<?php
get_footer();