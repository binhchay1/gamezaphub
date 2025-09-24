<?php
/**
 * The template for displaying the content.
 * @package blogarise
 */
?>
<div id="blog-list">
    <?php while(have_posts()){ the_post();   
        $url = blogarise_get_freatured_image_url($post->ID, 'blogarise-medium');
        if($url) { ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class('bs-blog-post list-blog'); ?>>
            <?php } else { ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class('bs-blog-post list-blog d-flex'); ?>>
            <?php } blogarise_post_image_display_type($post); ?>
            <article class="small col text-xs">
              <?php 
                $blogarise_global_category_enable = get_theme_mod('blogarise_global_category_enable','true');
                if($blogarise_global_category_enable == 'true') { blogarise_post_categories(); } ?>
                <h4 class="title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                <?php blogarise_post_meta();
                    blogarise_posted_content(); ?>
            </article>
        </div>
    <?php }
    blogarise_page_pagination(); ?>
</div>