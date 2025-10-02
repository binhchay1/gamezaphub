<?php
/**
 * Custom template tags for Bloggers Theme
 * Overrides parent theme functions with accessibility improvements
 *
 * @package Bloggers
 */

// ============================================================================
// OVERRIDE PARENT THEME AUTHOR FUNCTION WITH ACCESSIBLE VERSION
// ============================================================================

if (!function_exists('blogarise_author_content')) :
    /**
     * Accessible author content with proper aria-label
     */
    function blogarise_author_content() { 
        $author_name = get_the_author();
        $author_url = get_author_posts_url(get_the_author_meta('ID'));
        ?>
        <span class="bs-author">
            <a class="auth" 
               href="<?php echo esc_url($author_url); ?>"
               aria-label="<?php echo esc_attr(sprintf(__('View %s profile', 'bloggers'), $author_name)); ?>"> 
                <?php echo get_avatar(get_the_author_meta('ID'), 150, '', $author_name, array('loading' => 'lazy')); ?>
                <?php echo esc_html($author_name); ?> 
            </a>
        </span>
        <?php 
    }
endif;

// ============================================================================
// OVERRIDE PARENT THEME IMAGE DISPLAY WITH ACCESSIBLE VERSION
// ============================================================================

if (!function_exists('blogarise_post_image_display_type')) :
    /**
     * Accessible post image display with proper aria-labels and optimized images
     */
    function blogarise_post_image_display_type($post) {
        $url = blogarise_get_freatured_image_url($post->ID, 'blogarise-medium');
        $post_title = get_the_title($post);
        
        if ($url) { 
            if (blogarise_get_option('post_image_type') == 'post_fix_height') { 
                ?>
                <div class="bs-blog-thumb lg back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                    <a href="<?php the_permalink(); ?>" 
                       class="link-div" 
                       aria-label="<?php echo esc_attr(sprintf(__('Read more: %s', 'bloggers'), $post_title)); ?>"></a>
                </div> 
                <?php 
            } else { 
                ?>
                <div class="bs-post-thumb lg">
                    <a href="<?php echo esc_url(get_the_permalink()); ?>" 
                       class="bs-blog-thumb"
                       aria-label="<?php echo esc_attr(sprintf(__('Featured image for: %s', 'bloggers'), $post_title)); ?>">
                        <?php 
                        the_post_thumbnail('', array(
                            'class' => 'img-responsive img-fluid',
                            'loading' => 'lazy',
                            'decoding' => 'async',
                            'alt' => $post_title
                        )); 
                        ?>
                    </a>
                </div> 
                <?php 
            }
        } 
    }
endif;

// ============================================================================
// ADD ACCESSIBLE AUTHOR BOX FOR SINGLE POSTS
// ============================================================================

/**
 * Enhanced author info display with proper aria-labels
 */
if (!function_exists('bloggers_author_info_box')) :
    function bloggers_author_info_box() {
        $author_id = get_the_author_meta('ID');
        $author_name = get_the_author();
        $author_url = get_author_posts_url($author_id);
        $author_desc = get_the_author_meta('description');
        
        if (!$author_desc) {
            return;
        }
        ?>
        <div class="author-info-box">
            <div class="author-avatar">
                <a href="<?php echo esc_url($author_url); ?>" 
                   class="bs-author-pic" 
                   aria-label="<?php echo esc_attr(sprintf(__('View %s profile and posts', 'bloggers'), $author_name)); ?>">
                    <?php echo get_avatar($author_id, 150, '', $author_name, array('loading' => 'lazy')); ?>
                </a>
            </div>
            <div class="author-details">
                <h4 class="author-name">
                    <a href="<?php echo esc_url($author_url); ?>"
                       aria-label="<?php echo esc_attr(sprintf(__('View all posts by %s', 'bloggers'), $author_name)); ?>">
                        <?php echo esc_html($author_name); ?>
                    </a>
                </h4>
                <p class="author-description"><?php echo esc_html($author_desc); ?></p>
            </div>
        </div>
        <?php
    }
endif;

// ============================================================================
// ACCESSIBLE CATEGORY LINKS
// ============================================================================

if (!function_exists('bloggers_post_categories_accessible')) :
    /**
     * Output post categories with proper aria-labels
     */
    function bloggers_post_categories_accessible() {
        $categories = get_the_category();
        
        if (!$categories || !is_array($categories)) {
            return;
        }
        
        echo '<div class="bs-blog-category">';
        
        foreach ($categories as $category) {
            $category_name = esc_html($category->name);
            $category_url = esc_url(get_category_link($category->term_id));
            $color_class = blogarise_get_category_color_class($category->term_id);
            
            printf(
                '<a class="blogarise-categories %s" href="%s" aria-label="%s">%s</a>',
                esc_attr($color_class),
                $category_url,
                esc_attr(sprintf(__('View all posts in %s', 'bloggers'), $category_name)),
                $category_name
            );
        }
        
        echo '</div>';
    }
endif;

// ============================================================================
// ACCESSIBLE TAG LINKS
// ============================================================================

if (!function_exists('bloggers_post_tags_accessible')) :
    /**
     * Output post tags with proper aria-labels
     */
    function bloggers_post_tags_accessible() {
        $tags = get_the_tags();
        
        if (!$tags || !is_array($tags)) {
            return;
        }
        
        echo '<div class="tag-links">';
        
        foreach ($tags as $tag) {
            $tag_name = esc_html($tag->name);
            $tag_url = esc_url(get_tag_link($tag->term_id));
            
            printf(
                '<a href="%s" rel="tag" aria-label="%s">%s</a>',
                $tag_url,
                esc_attr(sprintf(__('View posts tagged with %s', 'bloggers'), $tag_name)),
                $tag_name
            );
        }
        
        echo '</div>';
    }
endif;

// ============================================================================
// ACCESSIBLE DATE LINKS
// ============================================================================

if (!function_exists('blogarise_date_content')) :
    /**
     * Output post date with accessible link
     */
    function blogarise_date_content() { 
        $month = get_post_time('F Y');
        ?>
        <span class="bs-blog-date">
            <a href="<?php echo esc_url(get_month_link(get_post_time('Y'), get_post_time('m'))); ?>"
               aria-label="<?php echo esc_attr(sprintf(__('View all posts from %s', 'bloggers'), $month)); ?>">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
            </a>
        </span>
        <?php
    }
endif;

// ============================================================================
// ACCESSIBLE READ MORE BUTTON
// ============================================================================

if (!function_exists('bloggers_read_more_link')) :
    /**
     * Generate accessible read more link
     */
    function bloggers_read_more_link() {
        $post_title = get_the_title();
        return sprintf(
            '<a href="%s" class="more-link" aria-label="%s">%s <span class="screen-reader-text">%s</span></a>',
            esc_url(get_permalink()),
            esc_attr(sprintf(__('Continue reading %s', 'bloggers'), $post_title)),
            __('Read More', 'bloggers'),
            esc_html($post_title)
        );
    }
endif;

// ============================================================================
// HELPER FUNCTION: Get optimized image attributes
// ============================================================================

if (!function_exists('bloggers_get_optimized_image_attrs')) :
    /**
     * Get optimized image attributes with lazy loading and proper dimensions
     */
    function bloggers_get_optimized_image_attrs($attachment_id, $size = 'full', $alt_text = '') {
        $attrs = array(
            'loading' => 'lazy',
            'decoding' => 'async',
            'class' => 'img-fluid'
        );
        
        if ($alt_text) {
            $attrs['alt'] = $alt_text;
        }
        
        $image_meta = wp_get_attachment_metadata($attachment_id);
        if (!empty($image_meta['width']) && !empty($image_meta['height'])) {
            $attrs['width'] = $image_meta['width'];
            $attrs['height'] = $image_meta['height'];
        }
        
        return $attrs;
    }
endif;


