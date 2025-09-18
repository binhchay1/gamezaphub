<?php

/**
 * SEO Enhancements for Yosemite Theme
 * 
 * @package Yosemite
 * @version 1.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced Meta Tags
 */
function mts_enhanced_meta_tags()
{
    global $post, $mts_options;

    if (is_singular()) {
        $description = get_post_meta($post->ID, '_mts_meta_description', true);
        if (empty($description)) {
            $description = get_the_excerpt();
            if (empty($description)) {
                $description = wp_trim_words(strip_tags($post->post_content), 20);
            }
        }

        if (!empty($description)) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }

        $keywords = get_post_meta($post->ID, '_mts_meta_keywords', true);
        if (!empty($keywords)) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }

        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";

        mts_add_open_graph_tags($description);

        mts_add_twitter_card_tags($description);

        mts_add_article_schema();
    } elseif (is_home() || is_front_page()) {
        $site_description = get_bloginfo('description');
        if (!empty($site_description)) {
            echo '<meta name="description" content="' . esc_attr($site_description) . '">' . "\n";
        }

        echo '<link rel="canonical" href="' . esc_url(home_url()) . '">' . "\n";

        mts_add_website_schema();
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $description = term_description();

        if (empty($description)) {
            $description = sprintf(__('Posts in %s category', 'mythemeshop'), $term->name);
        }

        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        echo '<link rel="canonical" href="' . esc_url(get_term_link($term)) . '">' . "\n";

        mts_add_collection_page_schema($term);
    }
}

/**
 * Add Open Graph Tags
 */
function mts_add_open_graph_tags($description)
{
    global $post, $mts_options;

    echo '<meta property="og:type" content="article">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";

    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image[0]) . '">' . "\n";
            echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '">' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '">' . "\n";
        }
    } elseif (!empty($mts_options['mts_logo'])) {
        echo '<meta property="og:image" content="' . esc_url($mts_options['mts_logo']) . '">' . "\n";
    }

    echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
    echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
    echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '">' . "\n";

    $categories = get_the_category();
    foreach ($categories as $category) {
        echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
    }

    $tags = get_the_tags();
    if ($tags) {
        foreach ($tags as $tag) {
            echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
        }
    }
}

/**
 * Add Twitter Card Tags
 */
function mts_add_twitter_card_tags($description)
{
    global $mts_options;

    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
        if ($image) {
            echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '">' . "\n";
        }
    } elseif (!empty($mts_options['mts_logo'])) {
        echo '<meta name="twitter:image" content="' . esc_url($mts_options['mts_logo']) . '">' . "\n";
    }

    if (!empty($mts_options['mts_twitter_site'])) {
        echo '<meta name="twitter:site" content="' . esc_attr($mts_options['mts_twitter_site']) . '">' . "\n";
    }
}

/**
 * Add Article Schema
 */
function mts_add_article_schema()
{
    global $post, $mts_options;

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words(strip_tags($post->post_content), 20),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author(),
            'url' => get_author_posts_url(get_the_author_meta('ID'))
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url()
        )
    );

    if (!empty($mts_options['mts_logo'])) {
        $logo_id = mts_get_image_id_from_url($mts_options['mts_logo']);
        if ($logo_id) {
            $logo = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo) {
                $schema['publisher']['logo'] = array(
                    '@type' => 'ImageObject',
                    'url' => $logo[0],
                    'width' => $logo[1],
                    'height' => $logo[2]
                );
            }
        }
    }

    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if ($image) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2]
            );
        }
    }

    $categories = get_the_category();
    if ($categories) {
        $schema['articleSection'] = array();
        foreach ($categories as $category) {
            $schema['articleSection'][] = $category->name;
        }
    }

    $tags = get_the_tags();
    if ($tags) {
        $schema['keywords'] = array();
        foreach ($tags as $tag) {
            $schema['keywords'][] = $tag->name;
        }
    }

    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

/**
 * Add Website Schema
 */
function mts_add_website_schema()
{
    global $mts_options;

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'description' => get_bloginfo('description')
    );

    $schema['potentialAction'] = array(
        '@type' => 'SearchAction',
        'target' => home_url('/?s={search_term_string}'),
        'query-input' => 'required name=search_term_string'
    );

    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

/**
 * Add CollectionPage Schema for archives
 */
function mts_add_collection_page_schema($term)
{
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $term->name,
        'description' => term_description($term->term_id),
        'url' => get_term_link($term)
    );

    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

/**
 * Enhanced Breadcrumbs with Schema
 */
function mts_enhanced_breadcrumbs()
{
    if (is_front_page()) {
        return;
    }

    if (function_exists('rank_math_the_breadcrumbs') && RankMath\Helper::get_settings('general.breadcrumbs')) {
        rank_math_the_breadcrumbs();
        return;
    }

    $breadcrumbs = array();
    $breadcrumbs[] = array(
        'name' => __('Home', 'mythemeshop'),
        'url' => home_url(),
        'position' => 1
    );

    $position = 2;

    if (is_singular()) {
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            $breadcrumbs[] = array(
                'name' => $category->name,
                'url' => get_category_link($category->term_id),
                'position' => $position++
            );
        }

        $breadcrumbs[] = array(
            'name' => get_the_title(),
            'url' => get_permalink(),
            'position' => $position
        );
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $breadcrumbs[] = array(
            'name' => $term->name,
            'url' => get_term_link($term),
            'position' => $position
        );
    }

    echo '<nav class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
    foreach ($breadcrumbs as $crumb) {
        echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<a href="' . esc_url($crumb['url']) . '" itemprop="item">';
        echo '<span itemprop="name">' . esc_html($crumb['name']) . '</span>';
        echo '</a>';
        echo '<meta itemprop="position" content="' . $crumb['position'] . '" />';
        echo '</span>';

        if ($crumb !== end($breadcrumbs)) {
            echo ' <span class="separator"><i class="fa fa-angle-right"></i></span> ';
        }
    }
    echo '</nav>';
}

/**
 * Add meta box for custom SEO fields
 */
function mts_add_seo_meta_box()
{
    add_meta_box(
        'mts_seo_meta_box',
        __('SEO Settings', 'mythemeshop'),
        'mts_seo_meta_box_callback',
        array('post', 'page'),
        'normal',
        'high'
    );
}

/**
 * SEO Meta box callback
 */
function mts_seo_meta_box_callback($post)
{
    wp_nonce_field('mts_seo_meta_box', 'mts_seo_meta_box_nonce');

    $meta_description = get_post_meta($post->ID, '_mts_meta_description', true);
    $meta_keywords = get_post_meta($post->ID, '_mts_meta_keywords', true);
    $meta_title = get_post_meta($post->ID, '_mts_meta_title', true);

?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="mts_meta_title"><?php _e('Meta Title', 'mythemeshop'); ?></label>
            </th>
            <td>
                <input type="text" id="mts_meta_title" name="mts_meta_title" value="<?php echo esc_attr($meta_title); ?>" class="regular-text" />
                <p class="description"><?php _e('Custom title for search engines. Leave empty to use post title.', 'mythemeshop'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="mts_meta_description"><?php _e('Meta Description', 'mythemeshop'); ?></label>
            </th>
            <td>
                <textarea id="mts_meta_description" name="mts_meta_description" rows="3" class="large-text"><?php echo esc_textarea($meta_description); ?></textarea>
                <p class="description"><?php _e('Custom description for search engines. Recommended length: 150-160 characters.', 'mythemeshop'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="mts_meta_keywords"><?php _e('Meta Keywords', 'mythemeshop'); ?></label>
            </th>
            <td>
                <input type="text" id="mts_meta_keywords" name="mts_meta_keywords" value="<?php echo esc_attr($meta_keywords); ?>" class="regular-text" />
                <p class="description"><?php _e('Comma-separated keywords for this post.', 'mythemeshop'); ?></p>
            </td>
        </tr>
    </table>
<?php
}

/**
 * Save SEO meta box data
 */
function mts_save_seo_meta_box($post_id)
{
    if (!isset($_POST['mts_seo_meta_box_nonce']) || !wp_verify_nonce($_POST['mts_seo_meta_box_nonce'], 'mts_seo_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['mts_meta_title'])) {
        update_post_meta($post_id, '_mts_meta_title', sanitize_text_field($_POST['mts_meta_title']));
    }

    if (isset($_POST['mts_meta_description'])) {
        update_post_meta($post_id, '_mts_meta_description', sanitize_textarea_field($_POST['mts_meta_description']));
    }

    if (isset($_POST['mts_meta_keywords'])) {
        update_post_meta($post_id, '_mts_meta_keywords', sanitize_text_field($_POST['mts_meta_keywords']));
    }
}

/**
 * Initialize SEO enhancements
 */
function mts_init_seo_enhancements()
{
    add_action('wp_head', 'mts_enhanced_meta_tags', 1);
    add_action('add_meta_boxes', 'mts_add_seo_meta_box');
    add_action('save_post', 'mts_save_seo_meta_box');
}

add_action('init', 'mts_init_seo_enhancements');
