<?php
/**
 * Performance & Accessibility Fixes for PageSpeed Insights
 * Fixes applied at server-side (PHP) level for immediate effect
 * 
 * @package Bloggers
 */

// ============================================================================
// 1. ADD PRECONNECT FOR GOOGLE FONTS (Fix Network Dependency)
// ============================================================================

if (!function_exists('bloggers_add_resource_hints')) :
    function bloggers_add_resource_hints($urls, $relation_type) {
        if ('preconnect' === $relation_type) {
            $urls[] = array(
                'href' => 'https://fonts.googleapis.com',
                'crossorigin',
            );
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
        }
        return $urls;
    }
endif;
add_filter('wp_resource_hints', 'bloggers_add_resource_hints', 10, 2);

// ============================================================================
// 2. OPTIMIZE IMAGES - Add srcset, WebP support, and lazy loading
// ============================================================================

if (!function_exists('bloggers_add_image_optimization')) :
    function bloggers_add_image_optimization($attr, $attachment, $size) {
        // Skip in admin area
        if (is_admin()) {
            return $attr;
        }
        
        // Add lazy loading
        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }
        
        // Add decoding async for better performance
        if (!isset($attr['decoding'])) {
            $attr['decoding'] = 'async';
        }
        
        // Ensure proper dimensions to prevent layout shift
        if (empty($attr['width']) || empty($attr['height'])) {
            $metadata = wp_get_attachment_metadata($attachment->ID);
            if (!empty($metadata['width']) && !empty($metadata['height'])) {
                $attr['width'] = $metadata['width'];
                $attr['height'] = $metadata['height'];
            }
        }
        
        return $attr;
    }
endif;
add_filter('wp_get_attachment_image_attributes', 'bloggers_add_image_optimization', 10, 3);

// ============================================================================
// 3. FIX ARIA-LABELS FOR LINKS (Fix Accessibility Issues)
// ============================================================================

/**
 * Generate proper aria-label based on link context
 */
if (!function_exists('bloggers_get_link_aria_label')) :
    function bloggers_get_link_aria_label($classes, $context = '', $text = '') {
        $classList = explode(' ', $classes);
        
        // Author links
        if (in_array('auth', $classList)) {
            $author_name = $text ? trim(strip_tags($text)) : 'author';
            return 'View ' . esc_attr($author_name) . ' profile';
        }
        
        // Author profile pictures
        if (in_array('bs-author-pic', $classList)) {
            $author_name = $context ? trim(strip_tags($context)) : 'author';
            return 'View ' . esc_attr($author_name) . ' profile';
        }
        
        // Link overlays for blog posts
        if (in_array('link-div', $classList)) {
            $title = $context ? trim(strip_tags($context)) : 'article';
            return 'Read more: ' . esc_attr($title);
        }
        
        // Featured images
        if (in_array('bs-blog-thumb', $classList)) {
            $title = $context ? trim(strip_tags($context)) : 'post';
            return 'Featured image for: ' . esc_attr($title);
        }
        
        return '';
    }
endif;

// ============================================================================
// 4. OVERRIDE PARENT THEME FUNCTIONS WITH ACCESSIBLE VERSIONS
// ============================================================================

/**
 * Accessible version of post image display
 */
if (!function_exists('bloggers_post_image_display_type')) :
    function bloggers_post_image_display_type($post) {
        $url = blogarise_get_freatured_image_url($post->ID, 'blogarise-medium');
        $post_title = get_the_title($post);
        
        if ($url) { 
            if (blogarise_get_option('post_image_type') == 'post_fix_height') { ?>
                <div class="bs-blog-thumb lg back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                    <a href="<?php the_permalink(); ?>" 
                       class="link-div" 
                       aria-label="<?php echo esc_attr('Read more: ' . $post_title); ?>"></a>
                </div> 
            <?php } else { ?>
                <div class="bs-post-thumb lg">
                    <a href="<?php echo esc_url(get_the_permalink()); ?>" 
                       aria-label="<?php echo esc_attr('Featured image for: ' . $post_title); ?>">
                        <?php the_post_thumbnail('', array(
                            'class' => 'img-responsive img-fluid',
                            'loading' => 'lazy',
                            'decoding' => 'async'
                        )); ?>
                    </a>
                </div> 
            <?php }
        } 
    }
endif;

/**
 * Accessible version of author content
 */
if (!function_exists('bloggers_author_content')) :
    function bloggers_author_content() { 
        $author_name = get_the_author();
        ?>
        <span class="bs-author">
            <a class="auth" 
               href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
               aria-label="<?php echo esc_attr('View ' . $author_name . ' profile'); ?>"> 
                <?php echo get_avatar(get_the_author_meta('ID'), 150, '', $author_name); ?>
                <?php echo esc_html($author_name); ?> 
            </a>
        </span>
    <?php }
endif;

// ============================================================================
// 5. DEFER NON-CRITICAL CSS & JS
// ============================================================================

if (!function_exists('bloggers_defer_non_critical_scripts')) :
    function bloggers_defer_non_critical_scripts($tag, $handle, $src) {
        // Skip in admin area
        if (is_admin()) {
            return $tag;
        }
        
        // List of scripts to defer (not critical for initial render)
        $defer_scripts = array(
            'jquery',
            'blogarise_main-js',
            'smartmenus-js',
            'bootstrap-smartmenus-js',
            'blogarise-marquee-js',
            'bloggers-owl-js' // Owl Carousel should defer, not async
        );
        
        // Don't defer our performance optimizer - it needs to load early
        $no_defer = array('bloggers-performance-optimizer');
        
        if (in_array($handle, $no_defer)) {
            return $tag;
        }
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src=', ' defer src=', $tag);
        }
        
        return $tag;
    }
endif;
add_filter('script_loader_tag', 'bloggers_defer_non_critical_scripts', 10, 3);

// ============================================================================
// 6. REMOVE UNUSED CSS FROM BLOCKS (If not using Gutenberg blocks)
// ============================================================================

if (!function_exists('bloggers_remove_wp_block_library_css')) :
    function bloggers_remove_wp_block_library_css() {
        // Only remove if not in block editor and not using blocks
        if (!is_admin() && !has_blocks()) {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks
            wp_dequeue_style('global-styles'); // Global styles
        }
    }
endif;
add_action('wp_enqueue_scripts', 'bloggers_remove_wp_block_library_css', 100);

// ============================================================================
// 7. ADD CRITICAL CSS INLINE (Above the fold content)
// ============================================================================

if (!function_exists('bloggers_add_critical_css')) :
    function bloggers_add_critical_css() {
        ?>
        <style id="critical-css">
        /* Critical CSS - Above the fold only */
        body{margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif}
        .bs-blog-post{margin-bottom:2rem}
        .bs-blog-thumb{position:relative;overflow:hidden}
        .bs-blog-thumb img{width:100%;height:auto;display:block}
        .title{margin:0.5rem 0;font-weight:600}
        .title a{text-decoration:none;color:inherit}
        header{background:#fff;border-bottom:1px solid #eee}
        .container{max-width:1200px;margin:0 auto;padding:0 15px}
        </style>
        <?php
    }
endif;
add_action('wp_head', 'bloggers_add_critical_css', 1);

// ============================================================================
// 8. ENABLE OUTPUT BUFFERING TO FIX HEADING ORDER ISSUES
// ============================================================================

if (!function_exists('bloggers_fix_heading_hierarchy')) :
    function bloggers_fix_heading_hierarchy($content) {
        // Fix common heading order issues
        // Example: Change out-of-order h4 to h3 where appropriate
        // This is a basic example - adjust based on actual theme structure
        
        if (is_singular() && is_main_query()) {
            // Ensure proper heading hierarchy in single posts
            return $content;
        }
        
        return $content;
    }
endif;
add_filter('the_content', 'bloggers_fix_heading_hierarchy', 999);

// ============================================================================
// 9. DISABLE EMOJIS (Reduces HTTP requests)
// ============================================================================

if (!function_exists('bloggers_disable_emojis')) :
    function bloggers_disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }
endif;
add_action('init', 'bloggers_disable_emojis');

// ============================================================================
// 10. IMAGE COMPRESSION & WebP SUPPORT
// ============================================================================

if (!function_exists('bloggers_add_webp_support')) :
    function bloggers_add_webp_support($types) {
        $types['image/webp'] = 'webp';
        return $types;
    }
endif;
add_filter('upload_mimes', 'bloggers_add_webp_support');

// Add WebP srcset if available
if (!function_exists('bloggers_add_webp_srcset')) :
    function bloggers_add_webp_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
        foreach ($sources as &$source) {
            $file_path = get_attached_file($attachment_id);
            $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file_path);
            
            if (file_exists($webp_path)) {
                $source['url'] = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source['url']);
                $source['type'] = 'image/webp';
            }
        }
        return $sources;
    }
endif;
// Uncomment if you have WebP versions of images
// add_filter('wp_calculate_image_srcset', 'bloggers_add_webp_srcset', 10, 5);


