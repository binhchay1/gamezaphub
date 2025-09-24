<?php
/**
 * The template for displaying search results pages.
 *
 * @package BlogArise
 */

get_header(); ?>
<!--==================== main content section ====================-->
<div id="content">
    <!--container-->
    <div class="container">
        <!--==================== Breadcrumb section ====================-->
        <?php do_action('blogarise_action_archive_page_title'); ?>
        <!--row-->
        <div class="row">
            <div class="col-lg-<?php echo ( !is_active_sidebar( 'sidebar-1' ) ? '12' :'8' ); ?>">
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php if ( have_posts() ) { /* Start the Loop */
                        while ( have_posts() ) { the_post();
                            $url = blogarise_get_freatured_image_url($post->ID, 'blogarise-medium');?>
                            <div id="post-<?php the_ID(); ?>" <?php post_class('bs-posts-sec bs-posts-modul-6 bs-blog-post list-blog'.($url ? '' : ' d-flex')); ?>>
                                <?php blogarise_post_image_display_type($post); ?>
                                <article class="d-md-flex bs-posts-sec-post">
                                    <div class="bs-sec-top-post py-3 col">
                                        <?php blogarise_post_categories(); ?>
                                        <h4 class="entry-title title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                                          <!-- Show meta for posts and other types, hide for pages in search results -->
                                    <?php if ( is_search() && get_post_type() === 'page' ) {}
                                        else {
                                            blogarise_post_meta();
                                        } ?>
                                        <div class="bs-content">
                                            <p><?php echo wp_trim_words( get_the_excerpt(), 30 ); ?></p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <?php 
                        } 
                        blogarise_page_pagination();
                    } else { ?> 
                        <!-- bs-posts-sec bs-posts-modul-6 -->
                        <div class="bs-posts-sec bs-posts-modul-6 bs-blog-post list-blog d-flex">    
                            <div class="inner">
                                <h2><?php esc_html_e( "Nothing Found", 'blogarise' ); ?></h2>
                                <div class="">
                                    <div class="bs-content">
                                        <p><?php esc_html_e( "Sorry, but nothing matched your search criteria. Please try again with some different keywords.", 'blogarise' ); ?></p>
                                    </div>
                                <?php get_search_form(); ?>
                                </div>
                            </div>
                        <!-- // bs-posts-sec block_6 -->
                        </div>
                    <?php } ?> 
                </div>
                <!--col-lg-12-->
            </div>
            <aside class="col-lg-4">
                <?php get_sidebar();?>
            </aside>
        </div><!--/row-->
    </div><!--/container-->
</div>
<?php
get_footer();