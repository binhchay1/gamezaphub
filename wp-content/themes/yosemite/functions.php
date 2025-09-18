<?php
/*-----------------------------------------------------------------------------------*/
/*  Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
define('MTS_THEME_NAME', 'yosemite');
define('MTS_THEME_VERSION', '1.3.1');
require_once(dirname(__FILE__) . '/theme-options.php');
if (! isset($content_width)) $content_width = 667;

/*-----------------------------------------------------------------------------------*/
/*  Load Options
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option(MTS_THEME_NAME);

/*-----------------------------------------------------------------------------------*/
/*  Load Translation Text Domain
/*-----------------------------------------------------------------------------------*/
load_theme_textdomain('mythemeshop', get_template_directory() . '/lang');

// Custom translations
if (!empty($mts_options['translate'])) {
    $mts_translations = get_option('mts_translations_' . MTS_THEME_NAME); //$mts_options['translations'];
    function mts_custom_translate($translated_text, $text, $domain)
    {
        if ($domain == 'mythemeshop' || $domain == 'nhp-opts') {
            global $mts_translations;
            if (!empty($mts_translations[$text])) {
                $translated_text = $mts_translations[$text];
            }
        }
        return $translated_text;
    }
    add_filter('gettext', 'mts_custom_translate', 20, 3);
}

if (function_exists('add_theme_support')) add_theme_support('automatic-feed-links');

/*-----------------------------------------------------------------------------------*/
/*  Disable theme updates from WordPress.org theme repository
/*-----------------------------------------------------------------------------------*/
function mts_disable_theme_update($r, $url)
{
    if (0 !== strpos($url, 'http://api.wordpress.org/themes/update-check'))
        return $r; // Not a theme update request
    $themes = unserialize($r['body']['themes']);
    unset($themes[get_option('template')]);
    unset($themes[get_option('stylesheet')]);
    $r['body']['themes'] = serialize($themes);
    return $r;
}
add_filter('http_request_args', 'mts_disable_theme_update', 5, 2);
add_filter('auto_update_theme', '__return_false');

// a shortcut function for wp mega menu plugin
function mts_is_wp_mega_menu_active()
{
    return in_array(array('wp-mega-menu/wp-mega-menu.php', 'my-wp-mega-menu/my-wp-mega-menu.php'), apply_filters('active_plugins', get_option('active_plugins')));
}

/*-----------------------------------------------------------------------------------*/
/*  Post Thumbnail Support
/*-----------------------------------------------------------------------------------*/
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    function mts_add_image_sizes()
    {
        set_post_thumbnail_size(265, 174, true);
        add_image_size('wp_review_small', 70, 70, true); // small thumb
        add_image_size('wp_review_large', 265, 174, true); // large thumb
    }
    add_action('init', 'mts_add_image_sizes', 11);
}

function mts_get_thumbnail_url($size = 'full')
{
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);
        return $image[0];
    }

    // use no preview fallback
    if (file_exists(get_template_directory() . '/images/nothumb-' . $size . '.png'))
        return get_template_directory_uri() . '/images/nothumb-' . $size . '.png';
    else
        return '';
}

/*-----------------------------------------------------------------------------------*/
/*  CREATE AND SHOW COLUMN FOR FEATURED THUMBNAILS IN POST LIST
/*-----------------------------------------------------------------------------------*/

//Get Featured image
function mts_get_featured_image($post_ID)
{
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
        return $post_thumbnail_img[0];
    }
}
function mts_columns_head($defaults)
{
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}
function mts_columns_content($column_name, $post_ID)
{
    if ($column_name == 'featured_image') {
        $post_featured_image = mts_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img width="100" height="100" src="' . $post_featured_image . '" />';
        }
    }
}
add_filter('manage_posts_columns', 'mts_columns_head');
add_action('manage_posts_custom_column', 'mts_columns_content', 10, 2);

/*-----------------------------------------------------------------------------------*/
/*  Custom Menu Support
/*-----------------------------------------------------------------------------------*/
add_theme_support('menus');
if (function_exists('register_nav_menus')) {
    register_nav_menus(
        array(
            'primary-menu' => __('Primary Menu', 'mythemeshop')
        )
    );
}

/*-----------------------------------------------------------------------------------*/
/*  Post Formats Support
/*-----------------------------------------------------------------------------------*/
add_theme_support('post-formats', array('gallery', 'image', 'audio', 'video'));

/*-----------------------------------------------------------------------------------*/
/*  Enable Widgetized sidebar and Footer
/*-----------------------------------------------------------------------------------*/
if (function_exists('register_sidebar')) {
    function mts_register_sidebars()
    {
        $mts_options = get_option(MTS_THEME_NAME);

        // Default sidebar
        register_sidebar(array(
            'name' => 'Sidebar',
            'description'   => __('Default sidebar.', 'mythemeshop'),
            'id' => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget panel %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Custom sidebars
        if (!empty($mts_options['mts_custom_sidebars']) && is_array($mts_options['mts_custom_sidebars'])) {
            foreach ($mts_options['mts_custom_sidebars'] as $sidebar) {
                if (!empty($sidebar['mts_custom_sidebar_id']) && !empty($sidebar['mts_custom_sidebar_id']) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-') {
                    register_sidebar(array('name' => '' . $sidebar['mts_custom_sidebar_name'] . '', 'id' => '' . sanitize_title(strtolower($sidebar['mts_custom_sidebar_id'])) . '', 'before_widget' => '<div id="%1$s" class="widget panel %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>'));
                }
            }
        }
    }

    add_action('widgets_init', 'mts_register_sidebars');
}

function mts_custom_sidebar()
{
    $mts_options = get_option(MTS_THEME_NAME);

    // Default sidebar
    $sidebar = 'Sidebar';

    if (is_home() && !empty($mts_options['mts_sidebar_for_home'])) $sidebar = $mts_options['mts_sidebar_for_home'];
    if (is_single() && !empty($mts_options['mts_sidebar_for_post'])) $sidebar = $mts_options['mts_sidebar_for_post'];
    if (is_page() && !empty($mts_options['mts_sidebar_for_page'])) $sidebar = $mts_options['mts_sidebar_for_page'];

    // Archives
    if (is_archive() && !empty($mts_options['mts_sidebar_for_archive'])) $sidebar = $mts_options['mts_sidebar_for_archive'];
    if (is_category() && !empty($mts_options['mts_sidebar_for_category'])) $sidebar = $mts_options['mts_sidebar_for_category'];
    if (is_tag() && !empty($mts_options['mts_sidebar_for_tag'])) $sidebar = $mts_options['mts_sidebar_for_tag'];
    if (is_date() && !empty($mts_options['mts_sidebar_for_date'])) $sidebar = $mts_options['mts_sidebar_for_date'];
    if (is_author() && !empty($mts_options['mts_sidebar_for_author'])) $sidebar = $mts_options['mts_sidebar_for_author'];

    // Other
    if (is_search() && !empty($mts_options['mts_sidebar_for_search'])) $sidebar = $mts_options['mts_sidebar_for_search'];
    if (is_404() && !empty($mts_options['mts_sidebar_for_notfound'])) $sidebar = $mts_options['mts_sidebar_for_notfound'];

    // Page/post specific custom sidebar
    if (is_page() || is_single()) {
        wp_reset_postdata();
        global $post;
        $custom = get_post_meta($post->ID, '_mts_custom_sidebar', true);
        if (!empty($custom)) $sidebar = $custom;
    }

    return $sidebar;
}

/*---------------------------------------------------------------------------------------------------*/
/*  Add "no-widget-title" class to widget if there is no title ( used for styling calendar widget )
/*---------------------------------------------------------------------------------------------------*/

function mts_widget_title($params)
{

    global $wp_registered_widgets;
    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];

    $title_class = (!isset($widget_opt[$widget_num]['title']) || empty($widget_opt[$widget_num]['title'])) ? 'no-widget-title' : '';

    $params[0]['before_widget'] = preg_replace('/class="/', "class=\"{$title_class} ", $params[0]['before_widget'], 1);

    return $params;
}
add_filter('dynamic_sidebar_params', 'mts_widget_title');

/*-----------------------------------------------------------------------------------*/
/*  Load Widgets, Actions and Libraries
/*-----------------------------------------------------------------------------------*/

// BFI Thumb Resize
include_once("functions/bfi-thumb.php");

// Add the 125x125 Ad Block Custom Widget
include_once("functions/widget-ad125.php");

// Add the 300x250 Ad Block Custom Widget
include_once("functions/widget-ad300.php");

// Add the Latest Tweets Custom Widget
include_once("functions/widget-tweets.php");

// Add Recent Posts Widget
include_once("functions/widget-recentposts.php");

// Add Related Posts Widget
include_once("functions/widget-relatedposts.php");

// Add Author Posts Widget
include_once("functions/widget-authorposts.php");

// Add Popular Posts Widget
include_once("functions/widget-popular.php");

// Add Facebook Like box Widget
include_once("functions/widget-fblikebox.php");

// Add Social Profile Widget
include_once("functions/widget-social.php");

// Add Category Posts Widget
include_once("functions/widget-catposts.php");

// Add Category Posts Widget
include_once("functions/widget-postslider.php");

// Add Welcome message
include_once("functions/welcome-message.php");

// Template Functions
include_once("functions/theme-actions.php");

// Post/page editor meta boxes
include_once("functions/metaboxes.php");

// TGM Plugin Activation
include_once("functions/plugin-activation.php");

// AJAX Contact Form - mts_contact_form()
include_once('functions/contact-form.php');

// Custom block WP
include_once("functions/custom-block-wp.php");

// Rank Math SEO.
include_once(get_theme_file_path('functions/rank-math-notice.php'));

// Performance Optimizations
include_once(get_theme_file_path('functions/performance-optimization.php'));

// SEO Enhancements
include_once(get_theme_file_path('functions/seo-enhancements.php'));

// Accessibility Fixes for PageSpeed Insights
include_once(get_theme_file_path('functions/accessibility-fixes.php'));

// Emergency Disable - Fix Infinite Loading Issues
include_once(get_theme_file_path('functions/emergency-disable.php'));

// Loading Fix - Resolve Infinite Loading Issues
include_once(get_theme_file_path('functions/loading-fix.php'));

// Conflict Resolver - Fix Conflicts with WP Rocket and Smush Pro
include_once(get_theme_file_path('functions/conflict-resolver.php'));

// Custom menu walker
if (mts_is_wp_mega_menu_active()) {
    add_filter('wpmm_container_selector', 'architect_megamenu_parent_element');
} else {
    // Custom menu walker
    include_once('functions/nav-menu.php');
}

function architect_megamenu_parent_element($selector)
{
    return '#header';
}

/*-----------------------------------------------------------------------------------*/
/*  RTL language support - also in mts_load_footer_scripts()
/*-----------------------------------------------------------------------------------*/
if (! empty($mts_options['mts_rtl'])) {
    function mts_rtl()
    {
        global $wp_locale, $wp_styles;
        $wp_locale->text_direction = 'rtl';
        if (! is_a($wp_styles, 'WP_Styles')) {
            $wp_styles = new WP_Styles();
            $wp_styles->text_direction = 'rtl';
        }
    }
    add_action('init', 'mts_rtl');
}

/*-----------------------------------------------------------------------------------*/
/*  Filters customize wp_title
/*-----------------------------------------------------------------------------------*/
function mts_wp_title($title, $sep)
{
    global $paged, $page;

    if (is_feed())
        return $title;

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page()))
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2)
        $title = "$title $sep " . sprintf(__('Page %s', 'mythemeshop'), max($paged, $page));

    return $title;
}
add_filter('wp_title', 'mts_wp_title', 10, 2);

/*-----------------------------------------------------------------------------------*/
/*  Javascript
/*-----------------------------------------------------------------------------------*/
function mts_nojs_js_class()
{
    echo '<script type="text/javascript">document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/,\'js\' );</script>';
}
add_action('wp_head', 'mts_nojs_js_class', 1);

function mts_add_scripts()
{
    $mts_options = get_option(MTS_THEME_NAME);

    wp_enqueue_script('jquery');

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_register_script('customscript', get_template_directory_uri() . '/js/customscript.js', true);
    if (! empty($mts_options['mts_show_primary_nav']) && ! empty($mts_options['mts_show_secondary_nav'])) {
        $nav_menu = 'both';
    } else {
        if (! empty($mts_options['mts_show_primary_nav'])) {
            $nav_menu = 'primary';
        } elseif (! empty($mts_options['mts_show_secondary_nav'])) {
            $nav_menu = 'secondary';
        } else {
            $nav_menu = 'none';
        }
    }
    wp_localize_script(
        'customscript',
        'mts_customscript',
        array(
            'responsive' => (empty($mts_options['mts_responsive']) ? false : true),
            'nav_menu' => $nav_menu
        )
    );
    wp_enqueue_script('customscript');

    if (!empty($mts_options['mts_thumbnail_effect'])) {
        wp_register_script('imagesLoaded', get_template_directory_uri() . '/js/imagesLoaded.js', true);
        wp_enqueue_script('imagesLoaded');
        wp_register_script('thumbsAnim', get_template_directory_uri() . '/js/thumbsAnim.js', true);
        wp_enqueue_script('thumbsAnim');
    }

    // Slider
    wp_register_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), null, true);
    wp_localize_script('owl-carousel', 'slideropts', array('rtl_support' => $mts_options['mts_rtl']));
    wp_enqueue_script('owl-carousel');

    global $is_IE;
    if ($is_IE) {
        wp_register_script('html5shim', "http://html5shim.googlecode.com/svn/trunk/html5.js");
        wp_enqueue_script('html5shim');
    }
}
add_action('wp_enqueue_scripts', 'mts_add_scripts');

function mts_load_footer_scripts()
{
    $mts_options = get_option(MTS_THEME_NAME);

    if (! empty($mts_options['mts_lightbox'])) {
        wp_register_script('prettyPhoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', true);
        wp_enqueue_script('prettyPhoto');
    }

    if (! empty($mts_options['mts_rtl'])) {
        wp_register_style('mts_rtl', get_template_directory_uri() . '/css/rtl.css', 'style', true);
        wp_enqueue_style('mts_rtl');
    }

    wp_register_script('mts_ajax', get_template_directory_uri() . '/js/ajax.js', true);
    if (! empty($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] >= 2 && (!is_singular() || is_page_template('page-blog.php'))) {
        wp_enqueue_script('mts_ajax');

        wp_register_script('historyjs', get_template_directory_uri() . '/js/history.js', true);
        wp_enqueue_script('historyjs');

        global $wp_query;
        $max = $wp_query->max_num_pages;
        $paged = (get_query_var('paged') > 1) ? get_query_var('paged') : 1;
        if ($max == 0) {
            $my_query = new WP_Query(
                array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'paged' => $paged,
                    'ignore_sticky_posts' => 1
                )
            );
            $max = $my_query->max_num_pages;
            wp_reset_query();
        }
        $autoload = ($mts_options['mts_pagenavigation_type'] == 3);
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_loadposts',
            array(
                'startPage' => $paged,
                'maxPages' => $max,
                'nextLink' => next_posts($max, false),
                'autoLoad' => $autoload,
                'i18n_loadmore' => __('Load More Posts', 'mythemeshop'),
                'i18n_loading' => __('Loading...', 'mythemeshop'),
                'i18n_nomore' => __('No more posts.', 'mythemeshop')
            )
        );
    }
    if (! empty($mts_options['mts_ajax_search'])) {
        wp_enqueue_script('mts_ajax');
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_search',
            array(
                'url' => admin_url('admin-ajax.php'),
                'ajax_search' => '1'
            )
        );
    }

    wp_register_script('jquery-parallax', get_template_directory_uri() . '/js/parallax.js');
    wp_enqueue_script('jquery-parallax');
}
add_action('wp_footer', 'mts_load_footer_scripts');

if (!empty($mts_options['mts_ajax_search'])) {
    add_action('wp_ajax_mts_search', 'ajax_mts_search');
    add_action('wp_ajax_nopriv_mts_search', 'ajax_mts_search');
}

/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS
/*-----------------------------------------------------------------------------------*/
function mts_enqueue_css()
{
    $mts_options = get_option(MTS_THEME_NAME);

    wp_enqueue_style('stylesheet', get_stylesheet_directory_uri() . '/style.css', 'style');

    wp_register_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), null);
    wp_enqueue_style('owl-carousel');

    if (! empty($mts_options['mts_lightbox'])) {
        wp_register_style('prettyPhoto', get_template_directory_uri() . '/css/prettyPhoto.css', 'style');
        wp_enqueue_style('prettyPhoto');
    }

    if (array_intersect(apply_filters('active_plugins', get_option('active_plugins')), array('wp-review/wp-review.php', 'wp-review-pro/wp-review.php', 'def'))) {
        wp_deregister_style('wp_review_tab_widget');
    }

    if (in_array('wp-tab-widget/wp-tab-widget.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        wp_deregister_style('wpt_widget');
    }

    wp_register_style('fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', 'style');
    wp_enqueue_style('fontawesome');

    if (! empty($mts_options['mts_responsive'])) {
        wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', 'style');
    }

    $mts_bg = '';
    if ($mts_options['mts_bg_pattern_upload'] != '') {
        $mts_bg = $mts_options['mts_bg_pattern_upload'];
    } else {
        if (!empty($mts_options['mts_bg_pattern'])) {
            $mts_bg = get_template_directory_uri() . '/images/' . $mts_options['mts_bg_pattern'] . '.png';
        }
    }

    $mts_sclayout = '';
    $mts_shareit_left = '';
    $mts_shareit_right = '';
    $mts_author = '';
    $mts_header_section = '';
    if (is_page() || is_single()) {
        $mts_sidebar_location = get_post_meta(get_the_ID(), '_mts_sidebar_location', true);
    } else {
        $mts_sidebar_location = '';
    }
    if ($mts_sidebar_location != 'right' && ($mts_options['mts_layout'] == 'sclayout' || $mts_sidebar_location == 'left')) {
        $mts_sclayout = '.article { float: right;}
        .sidebar.c-4-12 { float: left; } .latestPost-review-wrapper { right: 0; left: auto;}';
        if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'floating') {
            $mts_shareit_right = '.shareit { margin: 0 650px 0; border-left: 0; } .ss-full-width .shareit { margin: 0 0 0 -140px; )';
        }
    }
    if (empty($mts_options['mts_header_section2'])) {
        $mts_header_section = '.logo-wrap { display: none; } #navigation ul { margin-top: 20px; } .header-social { margin-top: 23px; } .header-search { margin-top: 15px; }';
    }
    if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'floating') {
        $mts_shareit_left = '.shareit { top: 345px; left: auto; z-index: 0; margin: 0 0 0 -130px; width: 90px; position: fixed; overflow: hidden; padding: 5px; border:none; border-right: 0;}
        .share-item {margin: 2px;}';
    }
    if (! empty($mts_options['mts_author_comment'])) {
        $mts_author = '.bypostauthor .fn > span:after { content: "' . __('Author', 'mythemeshop') . '"; margin-left: 10px; padding: 3px 10px; font-size: 13px; color: #333; -webkit-border-radius: 2px; border-radius: 2px; border-radius: 5px; border: 1px solid #D3D3D3; box-shadow: 0px 1px 1px #E7E7E7; }';
    }
    $custom_css = "
        body {background-color:{$mts_options['mts_bg_color']}; background-image: url( {$mts_bg} );}
        a:hover, .button, #logo a, .toggle-menu .toggle-caret .fa, input:focus, textarea:focus, select:focus, .widget-title, .sidebar a { color: {$mts_options['mts_color_scheme']}; }
        .toggle-menu .toggle-caret .fa, input:focus, textarea:focus, select:focus { border-color: {$mts_options['mts_color_scheme']}; }
        .wp_review_tab_widget_content .tab_title.selected a, .wp_review_tab_widget_content .tab_title.selected a, .wpt_widget_content .tab_title.selected a, .wpt_widget_content #tags-tab-content ul li a, .nav-growpop .icon-wrap { border-color: {$mts_options['mts_color_scheme']} !important; }
        .social-list a:hover,.toggle-menu .toggle-caret:hover .fa, .no-widget-title #wp-calendar caption, .sidebar .social-profile-icons ul li a:hover, .tagcloud a:hover, .pace .pace-progress, .nav-growpop .icon-wrap, .widget #wp-subscribe { background-color: {$mts_options['mts_color_scheme']}; }
        .wp_review_tab_widget_content .tab_title.selected a, .wp_review_tab_widget_content .tab_title.selected a, .wpt_widget_content .tab_title.selected a, .wpt_widget_content #tags-tab-content ul li a:hover { background-color: {$mts_options['mts_color_scheme']} !important; }

        {$mts_sclayout}
        {$mts_shareit_left}
        {$mts_shareit_right}
        {$mts_author}
        {$mts_header_section}
        {$mts_options['mts_custom_css']}
            ";
    wp_add_inline_style('stylesheet', $custom_css);
}
add_action('wp_enqueue_scripts', 'mts_enqueue_css', 99);

/*-----------------------------------------------------------------------------------*/
/*  Wrap videos in .responsive-video div
/*-----------------------------------------------------------------------------------*/
function mts_responsive_video($data)
{
    return '<div class="flex-video">' . $data . '</div>';
}
add_filter('embed_oembed_html', 'mts_responsive_video');

/*-----------------------------------------------------------------------------------*/
/*  Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');
add_filter('the_content_rss', 'do_shortcode');

/*-----------------------------------------------------------------------------------*/
/*  Custom Comments template
/*-----------------------------------------------------------------------------------*/
function mts_comments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    $mts_options = get_option(MTS_THEME_NAME); ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" itemscope itemtype="https://schema.org/UserComments">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment->comment_author_email, 90); ?>
                <?php printf('<span class="fn" itemprop="creator" itemscope itemtype="https://schema.org/Person"><span itemprop="name">%s</span></span>', get_comment_author_link()) ?>
                <?php if (! empty($mts_options['mts_comment_date'])) { ?>
                    <span class="ago"><?php comment_date(get_option('date_format')); ?></span>
                <?php } ?>
                <span class="comment-meta">
                    <?php edit_comment_link(__('( Edit )', 'mythemeshop'), '  ', '') ?>
                </span>
            </div>
            <?php if ($comment->comment_approved == '0') : ?>
                <em><?php _e('Your comment is awaiting moderation.', 'mythemeshop') ?></em>
                <br />
            <?php endif; ?>
            <div class="commentmetadata">
                <div class="commenttext" itemprop="commentText">
                    <?php comment_text() ?>
                    <span class="reply">
                        <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                    </span>
                </div>
            </div>
        </div>
    </li>
    <?php }

/*-----------------------------------------------------------------------------------*/
/*  Excerpt
/*-----------------------------------------------------------------------------------*/

// Increase max length
function mts_excerpt_length($length)
{
    return 100;
}
add_filter('excerpt_length', 'mts_excerpt_length', 20);

// Remove [...] and shortcodes
function mts_custom_excerpt($output)
{
    return preg_replace('/\[[^\]]*]/', '', $output);
}
add_filter('get_the_excerpt', 'mts_custom_excerpt');

// Truncate string to x letters/words
function mts_truncate($str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;')
{
    if ($units == 'letters') {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, 0, $length) . $ellipsis;
        } else {
            return $str;
        }
    } else {
        $words = explode(' ', $str);
        if (count($words) > $length) {
            return implode(" ", array_slice($words, 0, $length)) . $ellipsis;
        } else {
            return $str;
        }
    }
}

if (! function_exists('mts_excerpt')) {
    function mts_excerpt($limit = 40)
    {
        return mts_truncate(get_the_excerpt(), $limit, 'words');
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Remove more link from the_content and use custom read more
/*-----------------------------------------------------------------------------------*/
add_filter('the_content_more_link', 'mts_remove_more_link', 10, 2);
function mts_remove_more_link($more_link, $more_link_text)
{
    return '';
}
function mts_post_has_moretag()
{
    global $post;
    return strpos($post->post_content, '<!--more-->');
}

if (! function_exists('mts_readmore')) {
    function mts_readmore()
    {
    ?>
        <div class="readMore">
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="button">
                <?php _e('Continue', 'mythemeshop'); ?>&nbsp;&nbsp;&#8594;
            </a>
        </div>
        <?php
    }
}

/*-----------------------------------------------------------------------------------*/
/* removes the WordPress version from your header for security
/*-----------------------------------------------------------------------------------*/
function mts_remove_wpversion()
{
    return '<!--Theme by MyThemeShop.com-->';
}
add_filter('the_generator', 'mts_remove_wpversion');

/*-----------------------------------------------------------------------------------*/
/* Removes Trackbacks from the comment count
/*-----------------------------------------------------------------------------------*/
add_filter('get_comments_number', 'mts_comment_count', 0);
function mts_comment_count($count)
{
    if (! is_admin()) {
        global $id;
        $comments = get_comments('status=approve&post_id=' . $id);
        $comments_by_type = separate_comments($comments);
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}

/*-----------------------------------------------------------------------------------*/
/* adds a class to the post if there is a thumbnail
/*-----------------------------------------------------------------------------------*/
function has_thumb_class($classes)
{
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $classes[] = 'has_thumb';
    }
    return $classes;
}
add_filter('post_class', 'has_thumb_class');

/*-----------------------------------------------------------------------------------*/
/* AJAX Search results
/*-----------------------------------------------------------------------------------*/
function ajax_mts_search()
{
    $query = $_REQUEST['q']; // It goes through esc_sql() in WP_Query
    $search_query = new WP_Query(array('s' => $query, 'posts_per_page' => 3, 'post_status' => 'publish'));
    $search_count = new WP_Query(array('s' => $query, 'posts_per_page' => -1, 'post_status' => 'publish'));
    $search_count = $search_count->post_count;
    if (!empty($query) && $search_query->have_posts()) :
        //echo '<h5>Results for: '. $query.'</h5>';
        echo '<ul class="ajax-search-results">';
        while ($search_query->have_posts()) : $search_query->the_post();
        ?><li>
                <a href="<?php the_permalink(); ?>">
                    <?php $post_id = get_the_ID();
                    $widget_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
                    $widget_image = $widget_image[0];
                    $widget_image_url = bfi_thumb($widget_image, array('width' => '70', 'height' => '70', 'crop' => true));
                    if (has_post_thumbnail()) {
                        echo '<img src="' . $widget_image_url . '" width="70" height="70" class="wp-post-image">';
                    } ?>
                    <?php echo mts_truncate(get_the_title(), '5', 'words'); ?>
                </a>
                <div class="meta">
                    <span class="thetime"><?php the_time('F j, Y'); ?></span>
                </div> <!-- / .meta -->
            </li>
<?php
        endwhile;
        echo '</ul>';
        echo '<div class="ajax-search-meta"><span class="results-count">' . $search_count . ' ' . __('Results', 'mythemeshop') . '</span><a href="' . get_search_link($query) . '" class="results-link">Show all results</a></div>';
    else:
        echo '<div class="no-results">' . __('No results found.', 'mythemeshop') . '</div>';
    endif;

    exit; // required for AJAX in WP
}

/*-----------------------------------------------------------------------------------*/
/* Redirect feed to feedburner.
/*-----------------------------------------------------------------------------------*/

if ($mts_options['mts_feedburner'] != '') {
    function mts_rss_feed_redirect()
    {
        $mts_options = get_option(MTS_THEME_NAME);
        global $feed;
        $new_feed = $mts_options['mts_feedburner'];
        if (!is_feed()) {
            return;
        }
        if (preg_match('/feedburner/i', $_SERVER['HTTP_USER_AGENT'])) {
            return;
        }
        if ($feed != 'comments-rss2') {
            if (function_exists('status_header')) status_header(302);
            header("Location:" . $new_feed);
            header("HTTP/1.1 302 Temporary Redirect");
            exit();
        }
    }
    add_action('template_redirect', 'mts_rss_feed_redirect');
}

/*-----------------------------------------------------------------------------------*/
/* Single Post Pagination - Numbers + Previous/Next
/*-----------------------------------------------------------------------------------*/
function mts_wp_link_pages_args($args)
{
    global $page, $numpages, $more, $pagenow;
    if (!$args['next_or_number'] == 'next_and_number')
        return $args;
    $args['next_or_number'] = 'number';
    if (!$more)
        return $args;
    if ($page - 1)
        $args['before'] .= _wp_link_page($page - 1)
            . $args['link_before'] . $args['previouspagelink'] . $args['link_after'] . '</a>';
    if ($page < $numpages)

        $args['after'] = _wp_link_page($page + 1)
            . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
            . $args['after'];
    return $args;
}
add_filter('wp_link_pages_args', 'mts_wp_link_pages_args');

/*-----------------------------------------------------------------------------------*/
/* add <!-- next-page --> button to tinymce
/*-----------------------------------------------------------------------------------*/
add_filter('mce_buttons', 'wysiwyg_editor');
function wysiwyg_editor($mce_buttons)
{
    $pos = array_search('wp_more', $mce_buttons, true);
    if ($pos !== false) {
        $tmp_buttons = array_slice($mce_buttons, 0, $pos + 1);
        $tmp_buttons[] = 'wp_page';
        $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos + 1));
    }
    return $mce_buttons;
}

/*-----------------------------------------------------------------------------------*/
/*  Add title attribute to previous/next project links
/*-----------------------------------------------------------------------------------*/
function mts_add_title_to_next_post_link($link)
{
    if (get_post_type() == 'portfolio') {
        $next_post = get_next_post();
        $title = $next_post->post_title;
        $link = str_replace("rel=", " title='" . $title . "' rel=", $link);
    }
    return $link;
}
add_filter('next_post_link', 'mts_add_title_to_next_post_link');

function mts_add_title_to_previous_post_link($link)
{
    if (get_post_type() == 'portfolio') {
        $previous_post = get_previous_post();
        $title = $previous_post->post_title;
        $link = str_replace("rel=", " title='" . $title . "' rel=", $link);
    }
    return $link;
}
add_filter('previous_post_link', 'mts_add_title_to_previous_post_link');

/*-----------------------------------------------------------------------------------*/
/*  Alternative post templates
/*-----------------------------------------------------------------------------------*/
function mts_get_post_template($default = 'default')
{
    global $post;
    $single_template = $default;
    $posttemplate = get_post_meta($post->ID, '_mts_posttemplate', true);

    if (empty($posttemplate) || ! is_string($posttemplate))
        return $single_template;

    if (file_exists(dirname(__FILE__) . '/singlepost-' . $posttemplate . '.php')) {
        $single_template = dirname(__FILE__) . '/singlepost-' . $posttemplate . '.php';
    }

    return $single_template;
}
function mts_set_post_template($single_template)
{
    return mts_get_post_template($single_template);
}
add_filter('single_template', 'mts_set_post_template');

/*-----------------------------------------------------------------------------------*/
/*  Custom Gravatar Support
/*-----------------------------------------------------------------------------------*/
function mts_custom_gravatar($avatar_defaults)
{
    $mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$mts_avatar] = 'Custom Gravatar ( /images/gravatar.png )';
    return $avatar_defaults;
}
add_filter('avatar_defaults', 'mts_custom_gravatar');

/*-----------------------------------------------------------------------------------*/
/*  WP Review Support
/*-----------------------------------------------------------------------------------*/

// Set default colors for new reviews
function new_default_review_colors($colors)
{
    $colors = array(
        'color' => '#FFB300',
        'fontcolor' => '#fff',
        'bgcolor1' => '#333333',
        'bgcolor2' => '#333333',
        'bordercolor' => '#333333'
    );
    return $colors;
}
add_filter('wp_review_default_colors', 'new_default_review_colors');

// Set default location for new reviews
function new_default_review_location($position)
{
    $position = 'top';
    return $position;
}
add_filter('wp_review_default_location', 'new_default_review_location');

/*----------------------------------------------------------------------------------------*/
/*  Recalculate $content_width variable
/*----------------------------------------------------------------------------------------*/
add_action('template_redirect', 'mts_content_width');
function mts_content_width()
{
    global $content_width;
    // full width
    if (mts_custom_sidebar() == 'mts_nosidebar') {
        $content_width = 1000;
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Wrap comment form fields
/*-----------------------------------------------------------------------------------*/
add_action('comment_form_before_fields', 'mts_wrap_fields');
add_action('comment_form_after_fields', 'mts_wrap_fields');
function mts_wrap_fields()
{
    if ('comment_form_before_fields' === current_filter()) {
        echo '<div class="comment-form-fields">';
    } else {
        echo '</div>';
    }
}

/*-----------------------------------------------------------------------------------*/
/*  WP Mega Menu Configuration
/*-----------------------------------------------------------------------------------*/

/* Change image size */
function megamenu_thumbnails($thumbnail_html, $post_id)
{
    if (has_post_thumbnail()) {
        $thumb_id = get_post_thumbnail_id();
        $widget_image = wp_get_attachment_image_src($thumb_id, 'full');
        $widget_image = $widget_image[0];
        $thumbnail = bfi_thumb($widget_image, array('width' => '265', 'height' => '174', 'crop' => true));
    } else {
        $thumbnail = get_template_directory_uri() . '/images/nothumb-265x174.png';
    }
    $thumbnail_html = '<div class="wpmm-thumbnail">';
    $thumbnail_html .= '<a title="' . get_the_title($post_id) . '" href="' . get_permalink($post_id) . '">';
    $thumbnail_html .= '<img src="' . $thumbnail . '" class="wp-post-image">';
    $thumbnail_html .= '</a>';

    // WP Review
    $thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');

    $thumbnail_html .= '</div>';

    return $thumbnail_html;
}
add_filter('wpmm_thumbnail_html', 'megamenu_thumbnails', 10, 2);

/*-----------------------------------------------------------------------------------*/
/*  WP Tab & WP Review Widget Title Length
/*-----------------------------------------------------------------------------------*/
add_filter('wpt_title_length_default', 'tabswidget_title_length');
function tabswidget_title_length()
{
    return 5;
}

/**
 * Retrieves the attachment ID from the file URL
 *
 * @param $image_url
 *
 * @return string
 */
function mts_get_image_id_from_url($image_url)
{
    if (is_numeric($image_url)) return $image_url;
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));
    if (isset($attachment[0])) {
        return $attachment[0];
    } else {
        return false;
    }
}

/**
 * Remove new line tags from string
 *
 * @param $text
 *
 * @return string
 */
function mts_escape_text_tags($text)
{
    return (string) str_replace(array("\r", "\n"), '', strip_tags($text));
}

/**
 * Remove new line tags from string
 *
 * @return string
 */
function mts_single_post_schema()
{
    if (is_singular('post')) {
        global $post, $mts_options;
        if (has_post_thumbnail($post->ID) && !empty($mts_options['mts_logo'])) {
            $logo_id = mts_get_image_id_from_url($mts_options['mts_logo']);
            if ($logo_id) {

                $images  = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $logo    = wp_get_attachment_image_src($logo_id, 'full');
                $excerpt = mts_escape_text_tags($post->post_excerpt);
                $content = $excerpt === "" ? mb_substr(mts_escape_text_tags($post->post_content), 0, 110) : $excerpt;
                $args = array(
                    "@context" => "https://schema.org",
                    "@type"    => "BlogPosting",
                    "mainEntityOfPage" => array(
                        "@type" => "WebPage",
                        "@id"   => get_permalink($post->ID)
                    ),
                    "headline" => (function_exists('_wp_render_title_tag') ? wp_get_document_title() : wp_title('', false, 'right')),
                    "image"    => array(
                        "@type"  => "ImageObject",
                        "url"     => $images[0],
                        "width"  => $images[1],
                        "height" => $images[2]
                    ),
                    "datePublished" => get_the_time(DATE_ISO8601, $post->ID),
                    "dateModified"  => get_post_modified_time(DATE_ISO8601, __return_false(), $post->ID),
                    "author" => array(
                        "@type" => "Person",
                        "name"  => mts_escape_text_tags(get_the_author_meta('display_name', $post->post_author))
                    ),
                    "publisher" => array(
                        "@type" => "Organization",
                        "name"  => get_bloginfo('name'),
                        "logo"  => array(
                            "@type"  => "ImageObject",
                            "url"     => $logo[0],
                            "width"  => $logo[1],
                            "height" => $logo[2]
                        )
                    ),
                    "description" => (class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value('metadesc') : $content)
                );
                echo '<script type="application/ld+json">', PHP_EOL;
                echo wp_json_encode($args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), PHP_EOL;
                echo '</script>', PHP_EOL;
            }
        }
    }
}
add_action('wp_head', 'mts_single_post_schema');

// Rank Math SEO.
if (is_admin() && ! apply_filters('mts_disable_rmu', false)) {
    if (! defined('RMU_ACTIVE')) {
        include_once('functions/rm-seo.php');
    }
    $rm_upsell = MTS_RMU::init();
}


function mts_str_convert($text)
{
    $string = '';
    for ($i = 0; $i < strlen($text) - 1; $i += 2) {
        $string .= chr(hexdec($text[$i] . $text[$i + 1]));
    }
    return $string;
}

function mts_theme_connector()
{
    define('MTS_THEME_S', '6D65');
    if (! defined('MTS_THEME_INIT')) {
        mts_set_theme_constants();
    }
}

function mts_trigger_theme_activation()
{
    $last_version = get_option(MTS_THEME_NAME . '_version', '0.1');
    if (version_compare($last_version, '1.2.0') === -1) { // Update if < 1.2.0 (do not change this value)
        mts_theme_activation();
    }
    if (version_compare($last_version, MTS_THEME_VERSION) === -1) {
        update_option(MTS_THEME_NAME . '_version', MTS_THEME_VERSION);
    }
}

add_action('init', 'mts_theme_connector', 9);
add_action('mts_connect_deactivate', 'mts_theme_action');
add_action('after_switch_theme', 'mts_theme_activation', 10, 2);
add_action('admin_init', 'mts_trigger_theme_activation');
