<?php
/**
 * Override Parent Theme Functions
 * Replace H4 tags with P tags for better heading hierarchy
 * 
 * @package Bloggers
 */

// ============================================================================
// FORCE REMOVE PARENT HOOKS FIRST
// ============================================================================

add_action('after_setup_theme', 'bloggers_remove_parent_hooks', 999);
function bloggers_remove_parent_hooks()
{
    // Remove parent theme functions
    remove_action('blogarise_action_single_author_box', 'blogarise_single_author_box', 40);
    remove_action('blogarise_action_single_related_box', 'blogarise_single_related_box', 40);
}

// ============================================================================
// 1. OVERRIDE AUTHOR BOX - Change H4 to P
// ============================================================================

if (!function_exists('blogarise_single_author_box')) :
    /**
     * Override: Changed <h4> to <p> for author name
     */
    function blogarise_single_author_box() 
    {
        $blogarise_enable_single_admin_details = esc_attr(get_theme_mod('blogarise_enable_single_admin_details', true));
        
        if ($blogarise_enable_single_admin_details == true) { ?>
            <div class="bs-info-author-block py-4 px-3 mb-4 flex-column justify-content-center text-center">
                <a class="bs-author-pic mb-3" 
                   href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                   aria-label="<?php echo esc_attr(sprintf(__('View %s profile', 'bloggers'), get_the_author())); ?>">
                    <?php echo get_avatar(get_the_author_meta('ID'), 150); ?>
                </a>
                <div class="flex-grow-1">
                    <!-- Changed from H4 to P -->
                    <p class="title" style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">
                        <?php esc_html_e('By', 'blogarise'); ?> 
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                           aria-label="<?php echo esc_attr(sprintf(__('View all posts by %s', 'bloggers'), get_the_author())); ?>">
                            <?php the_author(); ?>
                        </a>
                    </p>
                    <p><?php the_author_meta('description'); ?></p>
                </div>
            </div>
        <?php }
    }
endif;
add_action('blogarise_action_single_author_box', 'blogarise_single_author_box', 40);

// ============================================================================
// 2. OVERRIDE RELATED POSTS BOX - Change H4 to P
// ============================================================================

if (!function_exists('blogarise_single_related_box')) :
    /**
     * Override: Changed <h4 class="title sm mb-0"> to <p> for post titles
     */
    function blogarise_single_related_box() 
    {
        $blogarise_enable_related_post = esc_attr(get_theme_mod('blogarise_enable_related_post', 'true'));
        $blogarise_enable_single_post_category = get_theme_mod('blogarise_enable_single_post_category', 'true');
        $blogarise_enable_single_post_date = get_theme_mod('blogarise_enable_single_post_date', 'true');
        
        if ($blogarise_enable_related_post == true) { ?>
            <div class="py-4 px-3 mb-4 bs-card-box bs-single-related">
                <!--Start bs-realated-slider -->
                <!-- bs-sec-title -->
                <div class="bs-widget-title mb-3">
                    <?php $blogarise_related_post_title = get_theme_mod('blogarise_related_post_title', esc_html__('Related Post', 'blogarise')); ?>
                    <h4 class="title"><?php echo esc_html($blogarise_related_post_title); ?></h4>
                </div>
                <!-- // bs-sec-title -->
                <div class="related-post">
                    <div class="row">
                        <!-- featured_post -->
                        <?php 
                        global $post;
                        $categories = get_the_category($post->ID);
                        $number_of_related_posts = 3;

                        if ($categories) {
                            $cat_ids = array();
                            foreach ($categories as $category) {
                                $cat_ids[] = $category->term_id;
                            }
                            
                            $args = array(
                                'category__in' => $cat_ids,
                                'post__not_in' => array($post->ID),
                                'posts_per_page' => $number_of_related_posts,
                                'ignore_sticky_posts' => 1
                            );
                            
                            $related_posts = new WP_Query($args);
                            
                            while ($related_posts->have_posts()) {
                                $related_posts->the_post();
                                global $post;
                                $url = blogarise_get_freatured_image_url($post->ID, 'blogarise-featured');
                                $post_title = get_the_title();
                                ?>
                                <!-- blog -->
                                <div class="col-md-4">
                                    <div class="bs-blog-post three md back-img bshre mb-md-0" 
                                         <?php if (has_post_thumbnail()) { ?>
                                             style="background-image: url('<?php echo esc_url($url); ?>');" 
                                         <?php } ?>>
                                        <a class="link-div" 
                                           href="<?php the_permalink(); ?>"
                                           aria-label="<?php echo esc_attr(sprintf(__('Read more: %s', 'bloggers'), $post_title)); ?>"></a>
                                        <div class="inner">
                                            <?php 
                                            if ($blogarise_enable_single_post_category == true) { 
                                                blogarise_post_categories(); 
                                            }
                                            $blogarise_enable_single_post_admin_details = esc_attr(get_theme_mod('blogarise_enable_single_post_admin_details', 'true')); 
                                            ?>
                                            
                                            <!-- Changed from H4 to P with inline styles to maintain appearance -->
                                            <p class="title sm mb-0" style="font-size: 1.125rem; font-weight: 600; line-height: 1.4;"> 
                                                <a href="<?php the_permalink(); ?>" 
                                                   title="<?php the_title_attribute(array('before' => 'Permalink to: ', 'after' => '')); ?>"
                                                   aria-label="<?php echo esc_attr($post_title); ?>">
                                                    <?php the_title(); ?>
                                                </a> 
                                            </p>
                                            
                                            <div class="bs-blog-meta">
                                                <?php 
                                                if ($blogarise_enable_single_post_admin_details == true) { 
                                                    blogarise_author_content(); 
                                                }
                                                if ($blogarise_enable_single_post_date == true) { 
                                                    blogarise_date_content(); 
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- blog -->
                                <?php
                            }
                            wp_reset_postdata();
                        } ?>
                        <!-- // featured_post -->
                    </div>
                </div>
                <!--End bs-realated-slider -->
            </div>
        <?php 
        }
    }
endif;
add_action('blogarise_action_single_related_box', 'blogarise_single_related_box', 40);

/**
 * NOTES:
 * 
 * 1. Both functions are PLUGGABLE in parent theme (wrapped with if (!function_exists()))
 * 2. Child theme loads BEFORE parent theme, so these overrides will work
 * 3. Changed H4 to P for better heading hierarchy
 * 4. Added inline styles to maintain visual appearance
 * 5. Added proper aria-labels for accessibility
 * 
 * CHANGES:
 * - Line 146 (Author Box): <h4 class="title"> → <p class="title" style="...">
 * - Line 198 (Related Posts): <h4 class="title sm mb-0"> → <p class="title sm mb-0" style="...">
 */


