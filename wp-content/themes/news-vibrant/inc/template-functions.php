<?php
/**
 * Additional features to allow styling of the templates
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function news_vibrant_body_classes( $classes ) {

    global $post;
    // Adds a class of group-blog to blogs with more than 1 published author.
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    /**
     * Sidebar option for post/page/archive
     *
     * @since 1.0.0
     */
    if ( 'post' === get_post_type() ) {
        $sidebar_meta_option = get_post_meta( $post->ID, 'nv_single_post_sidebar', true );
    }

    if ( 'page' === get_post_type() ) {
        $sidebar_meta_option = get_post_meta( $post->ID, 'nv_single_post_sidebar', true );
    }
     
    if ( is_home() ) {
        $home_id = get_option( 'page_for_posts' );
        $sidebar_meta_option = get_post_meta( $home_id, 'nv_single_post_sidebar', true );
    }
    
    if ( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
        $sidebar_meta_option = 'default_sidebar';
    }
    $archive_sidebar        = get_theme_mod( 'news_vibrant_archive_sidebar', 'right_sidebar' );
    $post_default_sidebar   = get_theme_mod( 'news_vibrant_default_post_sidebar', 'right_sidebar' );        
    $page_default_sidebar   = get_theme_mod( 'news_vibrant_default_page_sidebar', 'right_sidebar' );
    
    if ( $sidebar_meta_option == 'default_sidebar' ) {
        if ( is_single() ) {
            if ( $post_default_sidebar == 'right_sidebar' ) {
                $classes[] = 'right-sidebar';
            } elseif ( $post_default_sidebar == 'left_sidebar' ) {
                $classes[] = 'left-sidebar';
            } elseif ( $post_default_sidebar == 'no_sidebar' ) {
                $classes[] = 'no-sidebar';
            } elseif ( $post_default_sidebar == 'no_sidebar_center' ) {
                $classes[] = 'no-sidebar-center';
            }
        } elseif ( is_page() && !is_page_template( 'templates/home-template.php' ) ) {
            if ( $page_default_sidebar == 'right_sidebar' ) {
                $classes[] = 'right-sidebar';
            } elseif ( $page_default_sidebar == 'left_sidebar' ) {
                $classes[] = 'left-sidebar';
            } elseif ( $page_default_sidebar == 'no_sidebar' ) {
                $classes[] = 'no-sidebar';
            } elseif ( $page_default_sidebar == 'no_sidebar_center' ) {
                $classes[] = 'no-sidebar-center';
            }
        } elseif ( $archive_sidebar == 'right_sidebar' ) {
            $classes[] = 'right-sidebar';
        } elseif ( $archive_sidebar == 'left_sidebar' ) {
            $classes[] = 'left-sidebar';
        } elseif ( $archive_sidebar == 'no_sidebar' ) {
            $classes[] = 'no-sidebar';
        } elseif ( $archive_sidebar == 'no_sidebar_center' ) {
            $classes[] = 'no-sidebar-center';
        }
    } elseif ( $sidebar_meta_option == 'right_sidebar' ) {
        $classes[] = 'right-sidebar';
    } elseif ( $sidebar_meta_option == 'left_sidebar' ) {
        $classes[] = 'left-sidebar';
    } elseif ( $sidebar_meta_option == 'no_sidebar' ) {
        $classes[] = 'no-sidebar';
    } elseif ( $sidebar_meta_option == 'no_sidebar_center' ) {
        $classes[] = 'no-sidebar-center';
    }

    /**
     * option for web site layout 
     */
    $news_vibrant_website_layout = esc_attr( get_theme_mod( 'news_vibrant_site_layout', 'fullwidth_layout' ) );
    
    if ( !empty( $news_vibrant_website_layout ) ) {
        $classes[] = $news_vibrant_website_layout;
    }

    /**
     * Class for archive
     */
    if ( is_archive() ) {
        $news_vibrant_archive_layout = get_theme_mod( 'news_vibrant_archive_layout', 'classic' );
        if ( !empty( $news_vibrant_archive_layout ) ) {
            $classes[] = 'archive-'.$news_vibrant_archive_layout;
        }
    }

    return $classes;
}
add_filter( 'body_class', 'news_vibrant_body_classes' );

/*---------------------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_fonts_url' ) ) :

    /**
     * Register Google fonts for News Vibrant.
     *
     * @return string Google fonts URL for the theme.
     * @since 1.0.0
     */

    function news_vibrant_fonts_url() {
        $fonts_url = '';
        $font_families = array();

        /*
         * Translators: If there are characters in your language that are not supported
         * by Roboto Condensed, translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Roboto Condensed font: on or off', 'news-vibrant' ) ) {
            $font_families[] = 'Roboto Condensed:300italic,400italic,700italic,400,300,700';
        }

        /*
         * Translators: If there are characters in your language that are not supported
         * by Titillium Web, translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Titillium Web font: on or off', 'news-vibrant' ) ) {
            $font_families[] = 'Titillium Web:400,600,700,300';
        }       

        if ( $font_families ) {
            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return $fonts_url;
    }

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue scripts and styles for only admin
 *
 * @since 1.0.0
 */
add_action( 'admin_enqueue_scripts', 'news_vibrant_admin_scripts' );

function news_vibrant_admin_scripts( $hook ) {

    //var_dump($hook);

    global $news_vibrant_version;

    if ( 'widgets.php' != $hook && 'customize.php' != $hook && 'edit.php' != $hook && 'post.php' != $hook && 'post-new.php' != $hook && 'appearance_page_one-click-demo-import' != $hook ) {
        return;
    }
    
    wp_enqueue_script( 'jquery-ui-button' );
    wp_enqueue_script( 'news-vibrant-admin-script', get_template_directory_uri() .'/assets/js/nv-admin-scripts.js', array( 'jquery' ), esc_attr( $news_vibrant_version ), true );

    wp_enqueue_style( 'news-vibrant-admin-style', get_template_directory_uri() . '/assets/css/nv-admin-style.css', array(), esc_attr( $news_vibrant_version ) );
}

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function news_vibrant_scripts() {
    
    global $news_vibrant_version;

    wp_enqueue_style( 'news-vibrant-fonts', news_vibrant_fonts_url(), array(), null );

    wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );

    wp_enqueue_style( 'lightslider-style', get_template_directory_uri().'/assets/library/lightslider/css/lightslider.min.css', array(), '1.1.6' );

    wp_enqueue_style( 'news-vibrant-style', get_stylesheet_uri(), array(), esc_attr( $news_vibrant_version ) );
    
    wp_enqueue_style( 'news-vibrant-responsive-style', get_template_directory_uri().'/assets/css/nv-responsive.css', array(), '1.0.0' );

    wp_enqueue_script( 'news-vibrant-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), esc_attr( $news_vibrant_version ), true );

    $menu_sticky_option = get_theme_mod( 'news_vibrant_menu_sticky_option', true );
    if ( true == $menu_sticky_option ) {
          wp_enqueue_script( 'jquery-sticky', get_template_directory_uri(). '/assets/library/sticky/jquery.sticky.js', array( 'jquery' ), '20150416', true );
    
          wp_enqueue_script( 'nv-sticky-menu-setting', get_template_directory_uri(). '/assets/library/sticky/sticky-setting.js', array( 'jquery-sticky' ), '20150309', true );
    }

    wp_enqueue_script( 'news-vibrant-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), esc_attr( $news_vibrant_version ), true );

    wp_enqueue_script( 'lightslider', get_template_directory_uri().'/assets/library/lightslider/js/lightslider.min.js', array('jquery'), '1.1.6', true );

    wp_enqueue_script( 'jquery-ui-tabs' );

    wp_enqueue_script( 'news-vibrant-custom-script', get_template_directory_uri().'/assets/js/nv-custom-scripts.js', array('jquery'), esc_attr( $news_vibrant_version ), true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'news_vibrant_scripts' );

/*---------------------------------------------------------------------------------------------------------------*/

if ( !function_exists( 'news_vibrant_social_media' ) ):

    /**
     * Social media function
     *
     * @since 1.0.0
     */

    function news_vibrant_social_media() {
        $get_social_media_icons = get_theme_mod( 'social_media_icons', '' );
        $get_decode_social_media = json_decode( $get_social_media_icons );
        if ( ! empty( $get_decode_social_media ) ) {
            echo '<div class="nv-social-icons-wrapper">';
            foreach ( $get_decode_social_media as $single_icon ) {
                $icon_class = $single_icon->social_icon_class;
                $icon_url = $single_icon->social_icon_url;
                if ( !empty( $icon_url ) ) {
                    echo '<span class="social-link"><a href="'. esc_url( $icon_url ) .'" target="_blank"><i class="'. esc_attr( $icon_class ) .'"></i></a></span>';
                }
            }
            echo '</div><!-- .nv-social-icons-wrapper -->';
        }
    }

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( !function_exists( 'news_vibrant_categories_lists' ) ):

    /**
     * Category list
     *
     * @return array();
     */

    function news_vibrant_categories_lists() {
        $news_vibrant_cat_args = array(
            'type'       => 'post',
            'child_of'   => 0,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => 1,
            'taxonomy'   => 'category',
        );
        $news_vibrant_categories = get_categories( $news_vibrant_cat_args );
        $news_vibrant_categories_lists = array();
        foreach ( $news_vibrant_categories as $category ) {
            $news_vibrant_categories_lists[esc_attr( $category->slug )] = esc_html( $category->name );
        }
        return $news_vibrant_categories_lists;
    }

endif;

/*------------------------------------------------------------------------------------------------*/

add_filter( 'nav_menu_css_class', 'news_vibrant_category_nav_class', 10, 2 );

if ( ! function_exists( '' ) ) :

    /**
     * Add cat id in menu class
     */
    function news_vibrant_category_nav_class( $classes, $item ) {
        if ( 'category' == $item->object ) {
            $category = get_category( $item->object_id );
            $classes[] = 'nv-cat-' . absint( $category->term_id );
        }
        return $classes;
    }

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( '' ) ) :

    /**
     * Get minified css and removed space
     *
     * @since 1.0.0
     */

    function news_vibrant_css_strip_whitespace( $css ) {
        $replace = array(
            "#/\*.*?\*/#s" => "",  // Strip C style comments.
            "#\s\s+#"      => " ", // Strip excess whitespace.
        );
        $search = array_keys( $replace );
        $css = preg_replace( $search, $replace, $css );

        $replace = array(
            ": "  => ":",
            "; "  => ";",
            " {"  => "{",
            " }"  => "}",
            ", "  => ",",
            "{ "  => "{",
            ";}"  => "}", // Strip optional semicolons.
            ",\n" => ",", // Don't wrap multiple selectors.
            "\n}" => "}", // Don't wrap closing braces.
            "} "  => "}\n", // Put each rule on it's own line.
        );
        $search = array_keys( $replace );
        $css = str_replace( $search, $replace, $css );

        return trim( $css );
    }

endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_hover_color' ) ) :

    /**
     * Generate darker color
     * Source: http://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php
     *
     * @since 1.0.0
     */

    function news_vibrant_hover_color( $hex, $steps ) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max( -255, min( 255, $steps ) );

        // Normalize into a six character long hex string
        $hex = str_replace( '#', '', $hex );
        if ( strlen( $hex ) == 3) {
            $hex = str_repeat( substr( $hex,0,1 ), 2 ).str_repeat( substr( $hex, 1, 1 ), 2 ).str_repeat( substr( $hex,2,1 ), 2 );
        }

        // Split into three parts: R, G and B
        $color_parts = str_split( $hex, 2 );
        $return = '#';

        foreach ( $color_parts as $color ) {
            $color   = hexdec( $color ); // Convert to decimal
            $color   = max( 0, min( 255, $color + $steps ) ); // Adjust color
            $return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
        }

        return $return;
    }

endif;

/*---------------------------------------------------------------------------------------------------------------------------------*/

add_action( 'wp_enqueue_scripts', 'news_vibrant_dynamic_styles' );

if ( ! function_exists( 'news_vibrant_dynamic_styles' ) ) :

    /**
     * Dynamic style about template
     *
     * @since 1.0.0
     */

    function news_vibrant_dynamic_styles() {

        $get_categories = get_categories( array( 'hide_empty' => 1 ) );
        $news_vibrant_theme_color = get_theme_mod( 'news_vibrant_theme_color', '#34b0fa' );
        $news_vibrant_theme_hover_color = news_vibrant_hover_color( $news_vibrant_theme_color, '-50' );

        $news_vibrant_site_title_option = get_theme_mod( 'news_vibrant_site_title_option', true );
        $news_vibrant_site_title_color = get_theme_mod( 'news_vibrant_site_title_color', '#34b0fa' );

        $output_css = '';

        foreach ( $get_categories as $category ) {

            $cat_color = get_theme_mod( 'news_vibrant_category_color_'.strtolower( $category->name ), '#00a9e0' );

            $cat_hover_color = news_vibrant_hover_color( $cat_color, '-50' );
            $cat_id = $category->term_id;
            
            if ( !empty( $cat_color ) ) {
                $output_css .= ".category-button.nv-cat-". esc_attr( $cat_id ) ." a { background: ". esc_attr( $cat_color ) ."}\n";

                $output_css .= ".category-button.nv-cat-". esc_attr( $cat_id ) ." a:hover { background: ". esc_attr( $cat_hover_color ) ."}\n";

                $output_css .= ".nv-block-title:hover .nv-cat-". esc_attr( $cat_id ) ." { color: ". esc_attr( $cat_color ) ."}\n";

                $output_css .= ".nv-block-title.nv-cat-". esc_attr( $cat_id ) ." { border-left-color: ". esc_attr( $cat_color ) ."}\n";

                $output_css .= "#site-navigation ul li.nv-cat-". absint( $cat_id ) ." a:before { background-color: ". esc_attr( $cat_color ) ." }\n";
            }
        }

        $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.navigation .nav-links a:hover,.bttn:hover,button,input[type='button']:hover,input[type='reset']:hover,input[type='submit']:hover,.widget_search .search-submit,.widget_tag_cloud .tagcloud a:hover,.edit-link .post-edit-link,.reply .comment-reply-link,.home .nv-home-icon a,.nv-home-icon a:hover,#site-navigation ul li a:before,.nv-header-search-wrapper .search-form-main .search-submit,.ticker-caption,.comments-link:hover a,.news_vibrant_featured_slider .slider-posts .lSAction > a:hover,.news_vibrant_default_tabbed ul.widget-tabs li,.news_vibrant_default_tabbed ul.widget-tabs li.ui-tabs-active,.news_vibrant_default_tabbed ul.widget-tabs li:hover,.nv-block-title-nav-wrap .carousel-nav-action .carousel-controls:hover,.news_vibrant_social_media .social-link a,.news_vibrant_social_media .social-link a:hover,.nv-archive-more .nv-button:hover,.error404 .page-title, #nv-scrollup{ background: ". esc_attr( $news_vibrant_theme_color ) ."}\n";
        
        $output_css .= "a,a:hover,a:focus,a:active,.widget a:hover,.widget a:hover::before,.widget li:hover::before,.entry-footer a:hover,.comment-author .fn .url:hover,#cancel-comment-reply-link,#cancel-comment-reply-link:before,.logged-in-as a,.nv-featured-posts-wrapper .nv-single-post-wrap .nv-post-content .nv-post-meta span:hover, .nv-featured-posts-wrapper .nv-single-post-wrap .nv-post-content .nv-post-meta span a:hover,.search-main a:hover,.nv-ticker-block .lSAction>a:hover,.nv-slide-content-wrap .post-title a:hover,.news_vibrant_featured_posts .nv-single-post .nv-post-content .nv-post-title a:hover,.news_vibrant_carousel .nv-single-post .nv-post-title a:hover,.news_vibrant_block_posts .layout3 .nv-primary-block-wrap .nv-single-post .nv-post-title a:hover,.news_vibrant_featured_slider .featured-posts .nv-single-post .nv-post-content .nv-post-title a:hover,.nv-featured-posts-wrapper .nv-single-post-wrap .nv-post-content .nv-post-title a:hover,.nv-post-title.large-size a:hover,.nv-post-title.small-size a:hover,.nv-post-meta span:hover,.nv-post-meta span a:hover,.news_vibrant_featured_posts .nv-single-post-wrap .nv-post-content .nv-post-meta span:hover,.news_vibrant_featured_posts .nv-single-post-wrap .nv-post-content .nv-post-meta span a:hover,.nv-post-title.small-size a:hover,#top-footer .widget a:hover,#top-footer .widget a:hover:before,#top-footer .widget li:hover:before, #footer-navigation ul li a:hover, .entry-title a:hover, .entry-meta span a:hover, .entry-meta span:hover,.menu-toggle:hover{ color: ". esc_attr( $news_vibrant_theme_color ) ."}\n";

        $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.widget_search .search-submit,#top-footer .widget-title,.nv-archive-more .nv-button:hover{ border-color: ". esc_attr( $news_vibrant_theme_color ) ."}\n";

        $output_css .= ".comment-list .comment-body,.nv-header-search-wrapper .search-form-main,.comments-link:hover a::after{ border-top-color: ". esc_attr( $news_vibrant_theme_color ) ."}\n";

        $output_css .= ".nv-header-search-wrapper .search-form-main:before{ border-bottom-color: ". esc_attr( $news_vibrant_theme_color ) ."}\n";

        $output_css .= ".nv-block-title,.widget-title,.page-header .page-title,.nv-related-title{ border-left-color: ". esc_attr( $news_vibrant_theme_color ) ."}\n";

        if ( $news_vibrant_site_title_option === true ) {
            $output_css .=".site-title a, .site-description {
                color:". esc_attr( $news_vibrant_site_title_color ) .";
            }\n";
        } else {
            $output_css .=".site-title, .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
            }\n";
        }

        $refine_output_css = news_vibrant_css_strip_whitespace( $output_css );

        wp_add_inline_style( 'news-vibrant-style', $refine_output_css );
    }

endif;

/*---------------------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_get_sidebar' ) ):

    /**
     * Function define about page/post/archive sidebar
     *
     * @since 1.0.0
 */

    function news_vibrant_get_sidebar() {
        global $post;

        if ( 'post' === get_post_type() ) {
            $sidebar_meta_option = get_post_meta( $post->ID, 'nv_single_post_sidebar', true );
        }

        if ( 'page' === get_post_type() ) {
            $sidebar_meta_option = get_post_meta( $post->ID, 'nv_single_post_sidebar', true );
        }
         
        if ( is_home() ) {
            $set_id = get_option( 'page_for_posts' );
            $sidebar_meta_option = get_post_meta( $set_id, 'nv_single_post_sidebar', true );
        }
        
        if ( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
            $sidebar_meta_option = 'default_sidebar';
        }
        
        $archive_sidebar      = get_theme_mod( 'news_vibrant_archive_sidebar', 'right_sidebar' );
        $post_default_sidebar = get_theme_mod( 'news_vibrant_default_post_sidebar', 'right_sidebar' );
        $page_default_sidebar = get_theme_mod( 'news_vibrant_default_page_sidebar', 'right_sidebar' );
        
        if ( $sidebar_meta_option == 'default_sidebar' ) {
            if ( is_single() ) {
                if ( $post_default_sidebar == 'right_sidebar' ) {
                    get_sidebar();
                } elseif ( $post_default_sidebar == 'left_sidebar' ) {
                    get_sidebar( 'left' );
                }
            } elseif ( is_page() ) {
                if ( $page_default_sidebar == 'right_sidebar' ) {
                    get_sidebar();
                } elseif ( $page_default_sidebar == 'left_sidebar' ) {
                    get_sidebar( 'left' );
                }
            } elseif ( $archive_sidebar == 'right_sidebar' ) {
                get_sidebar();
            } elseif ( $archive_sidebar == 'left_sidebar' ) {
                get_sidebar( 'left' );
            }
        } elseif ( $sidebar_meta_option == 'right_sidebar' ) {
            get_sidebar();
        } elseif ( $sidebar_meta_option == 'left_sidebar' ) {
            get_sidebar( 'left' );
        }
    }

endif;

/*---------------------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_font_awesome_social_icon_array' ) ) :

    /**
     * Define font awesome social media icons
     *
     * @return array();
     * @since 1.0.0
     */

    function news_vibrant_font_awesome_social_icon_array() {
        return array(
            "fa fa-facebook-square","fa fa-facebook-f","fa fa-facebook","fa fa-facebook-official","fa fa-twitter-square","fa fa-twitter","fa fa-yahoo","fa fa-google","fa fa-google-wallet","fa fa-google-plus-circle","fa fa-google-plus-official","fa fa-instagram","fa fa-linkedin-square","fa fa-linkedin","fa fa-pinterest-p","fa fa-pinterest","fa fa-pinterest-square","fa fa-google-plus-square","fa fa-google-plus","fa fa-youtube-square","fa fa-youtube","fa fa-youtube-play","fa fa-vimeo","fa fa-vimeo-square",
        );
    }
    
endif;

/*---------------------------------------------------------------------------------------------------------------*/

add_filter( 'wp_kses_allowed_html', 'news_vibrant_required_data_attributes' , 10, 4 );

if ( ! function_exists( 'news_vibrant_required_data_attributes' ) ) :
    
    /**
     * Added required attributes while using wp_kses by using `wp_kses_allowed_html` filter.
     *
     * @since 1.1.19
     */
    function news_vibrant_required_data_attributes( $required_attributes, $context ) {

        $required_attributes['time']['class'] = true;
        $required_attributes['time']['datetime'] = true;

        return $required_attributes;
    }

endif;

/*---------------------------------------------------------------------------------------------------------------*/

add_action( 'init', 'news_vibrant_show_hide_to_boolean');

function news_vibrant_show_hide_to_boolean() {

    $news_vibrant_new_setup = get_theme_mod( 'news_vibrant_new_toggle_setup', false );

    if ( true !== $news_vibrant_new_setup ) {

        $req_settigns_ids = array(
            'news_vibrant_widget_cat_link_option'   => 'show',
            'news_vibrant_widget_cat_color_option'  => 'show',
            'news_vibrant_related_posts_option'     => 'show',
            'news_vibrant_footer_widget_option'     => 'show',
            'news_vibrant_block_base_widget_option' => 'hide',
            'news_vibrant_top_header_option'        => 'show',
            'news_vibrant_top_date_option'          => 'show',
            'news_vibrant_top_social_option'        => 'show',
            'news_vibrant_top_featured_option'      => 'show',
            'news_vibrant_menu_sticky_option'       => 'show',
            'news_vibrant_home_icon_option'         => 'show',
            'news_vibrant_search_icon_option'       => 'show',
            'news_vibrant_ticker_option'            => 'show',
        );

        foreach( $req_settigns_ids as $key => $value ) {

            $news_vibrant_setting_value = get_theme_mod( $key, $value );
            if ( ! is_bool( $news_vibrant_setting_value ) ) {
                if ( 'hide' == $news_vibrant_setting_value ) {
                    set_theme_mod( $key, false );
                } else {
                    set_theme_mod( $key, true );
                }
            }

        }

        set_theme_mod( 'news_vibrant_new_toggle_setup', true );

    }
}