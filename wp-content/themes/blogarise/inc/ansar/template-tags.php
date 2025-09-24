<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package BlogArise
 */

if (!function_exists('blogarise_post_categories')) :
    function blogarise_post_categories($separator = '&nbsp') {
        $global_show_categories = blogarise_get_option('global_show_categories');
        if ($global_show_categories == 'no') {
            return;
        }

        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {

            global $post;

            $post_categories = get_the_category($post->ID);
            if ($post_categories) {
                echo '<div class="bs-blog-category">';
                $output = '';
                foreach ($post_categories as $post_category) {
                    $t_id = $post_category->term_id;
                    $color_id = "category_color_" . $t_id;

                    // retrieve the existing value(s) for this meta field. This returns an array
                    $term_meta = get_option($color_id);
                    $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';

                    $output .= '<a class="blogarise-categories ' . esc_attr($color_class) . '" href="' . esc_url(get_category_link($post_category)) . '" alt="' . esc_attr(sprintf(__('View all posts in %s', 'blogarise'), $post_category->name)) . '"> 
                                 ' . esc_html($post_category->name) . '
                             </a>';
                }
                $output .= '';
                echo $output;
                echo '</div>';
            }
        }
    }
endif;

if (!function_exists('blogarise_get_category_color_class')) :

    function blogarise_get_category_color_class($term_id) {

        $color_id = "category_color_" . $term_id;
        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option($color_id);
        $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : '';
        return $color_class;
    }
endif;

if ( ! function_exists( 'blogarise_date_content' ) ) :
    function blogarise_date_content() { ?>
      <span class="bs-blog-date">
        <a href="<?php echo esc_url(get_month_link(esc_html(get_post_time('Y')),esc_html(get_post_time('m')))); ?>"><time datetime=""><?php echo esc_html(get_the_date()); ?></time></a>
      </span>
<?php }
endif;

if ( ! function_exists( 'blogarise_author_content' ) ) :
function blogarise_author_content() { ?>
    <span class="bs-author">
        <a class="auth" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"> 
            <?php echo get_avatar( get_the_author_meta( 'ID') , 150); ?><?php the_author(); ?> 
        </a>
    </span>
<?php }
endif;

if (!function_exists('get_archive_title')) :
        
    function get_archive_title($title) {
        
        if (class_exists('WooCommerce')) {
            if (is_shop()) {
                $title = 'Shop';
            } elseif (is_product_category()) {
                $title = single_term_title('', false);
            } elseif (is_product_tag()) {
                $title = single_term_title('', false);
            }
        }
        
        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = get_the_author();
        } elseif (is_year()) {
            $title = get_the_date('Y');
        } elseif (is_month()) {
            $title = get_the_date('F Y');
        } elseif (is_day()) {
            $title = get_the_date('F j, Y');
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title('', false);
        } elseif(is_search()){   
            /* translators: %s: search term */
            $title = sprintf( esc_html__( 'Search Results for: %s', 'blogarise' ), esc_html( get_search_query() ) );
        } else {
            $title =  get_the_title();
        }
        
        return $title;
    }

endif;
add_filter('get_the_archive_title', 'get_archive_title');

if (!function_exists('blogarise_archive_page_title')) :
        
    function blogarise_archive_page_title($title) { ?>
          <div class="bs-card-box page-entry-title">
            <?php if(!empty(get_the_archive_title())){ ?>
                <div class="page-entry-title-box">
                <h1 class="entry-title title mb-0"><?php echo get_the_archive_title();?></h1>
                <?php if(is_search()) {
                    blogarise_search_count();
                }
                echo '</div>';
            }
            do_action('blogarise_breadcrumb_content'); ?>
        </div>
    <?php
    }
endif;
add_action('blogarise_action_archive_page_title', 'blogarise_archive_page_title');


if (!function_exists('blogarise_post_item_tag')) :

    function blogarise_post_item_tag($view = 'default') {
        global $post;

        if ('post' === get_post_type()) {

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x('', 'list item separator', 'blogarise'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html('Tags: %1$s') . '</span>', $tags_list); // WPCS: XSS OK.
            }
        }

        if (is_single()) {
            edit_post_link(
                sprintf(
                    wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'blogarise'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
        }

    }
endif;

if (!function_exists('blogarise_post_meta')) :

    function blogarise_post_meta() {
        $global_post_date = get_theme_mod('global_post_date_author_setting','show-date-author');
        $blogarise_global_comment_enable = get_theme_mod('blogarise_global_comment_enable','true'); 

        echo '<div class="bs-blog-meta">';
        if($global_post_date =='show-date-author') {
            blogarise_author_content(); blogarise_date_content();

        } elseif($global_post_date =='show-date-only') {
            blogarise_date_content();

        } elseif($global_post_date =='show-author-only') {
    
            blogarise_author_content(); 
        }

        if($blogarise_global_comment_enable == true) { ?>
            <span class="comments-link"> 
                <a href="<?php the_permalink(); ?>">
                    <?php
                    if ( get_comments_number() == 0 ) {
                        esc_html_e(  __('No Comments', 'blogarise') );
                    } else {
                        echo get_comments_number() . ' ';
                        esc_html_e( get_comments_number() == 1 ? __('Comment', 'blogarise') : __('Comments', 'blogarise') );
                    } ?>
                </a> 
            </span>
        <?php } ?>
        <?php blogarise_edit_link(); 
        echo '</div>';
    }
endif; 
if (!function_exists('blogarise_menu_search')) :
    function blogarise_menu_search() { 
        $blogarise_menu_search  = get_theme_mod('blogarise_menu_search','true');
        if($blogarise_menu_search == true) { ?>
            <a class="msearch ml-auto"  data-bs-target="#exampleModal"  href="#" data-bs-toggle="modal">
                <i class="fa fa-search"></i>
            </a> 
        <?php } 
    }
endif; 

if (!function_exists('blogarise_menu_subscriber')) :

    function blogarise_menu_subscriber() { 
        $blogarise_subsc_link = get_theme_mod('blogarise_subsc_link', '#'); 
        $blogarise_menu_subscriber  = get_theme_mod('blogarise_menu_subscriber','true');
        $blogarise_subsc_open_in_new  = get_theme_mod('blogarise_subsc_open_in_new', true);

        if($blogarise_menu_subscriber == true) { ?>
            <a class="subscribe-btn" href="<?php echo esc_url($blogarise_subsc_link); ?>" <?php if($blogarise_subsc_open_in_new) { ?> target="_blank" <?php } ?>  >
                <i class="fas fa-bell"></i>
            </a>
        <?php }
    }
endif; 

if (!function_exists('blogarise_lite_dark_switcher')) :

    function blogarise_lite_dark_switcher() { $blogarise_lite_dark_switcher = get_theme_mod('blogarise_lite_dark_switcher','true');
        
        if($blogarise_lite_dark_switcher == true){ 
          if ( isset( $_COOKIE["blogarise-site-mode-cookie"] ) ) {
            $blogarise_skin_mode = $_COOKIE["blogarise-site-mode-cookie"];
        } else {
            $blogarise_skin_mode = get_theme_mod( 'blogarise_skin_mode', 'defaultcolor' );
        } ?>
          <label class="switch" for="switch">
            <input type="checkbox" name="theme" id="switch" class="<?php echo esc_attr( $blogarise_skin_mode ); ?>" data-skin-mode="<?php echo esc_attr( $blogarise_skin_mode ); ?>" >
            <span class="slider"></span>
          </label>
        <?php }
    }
endif; 

if (!function_exists('blogarise_menu_btns')) :
    function blogarise_menu_btns() { 
        blogarise_menu_search();
        blogarise_menu_subscriber();
        blogarise_lite_dark_switcher();
    }
endif; 

if ( ! function_exists( 'blogarise_posted_content' ) ) :
    function blogarise_posted_content() { 
      $blogarise_blog_content  = get_theme_mod('blogarise_blog_content','excerpt');

        if ( 'excerpt' == $blogarise_blog_content ){
            $blogarise_excerpt = blogarise_the_excerpt( absint( 30 ) );
            if ( !empty( $blogarise_excerpt ) ) :                   
                echo wp_kses_post( wpautop( $blogarise_excerpt ) );
            endif; 
        } else{ 
            the_content( __('Read More','blogarise') );
        }
    } 
endif;

if ( ! function_exists( 'blogarise_the_excerpt' ) ) :

    /**
     * Generate excerpt.
     *
     */
    function blogarise_the_excerpt( $length = 0, $post_obj = null ) {

        global $post;

        if ( is_null( $post_obj ) ) {
            $post_obj = $post;
        }

        $length = absint( $length );

        if ( 0 === $length ) {
            return;
        }

        $source_content = $post_obj->post_content;

        if ( ! empty( get_the_excerpt($post_obj) ) ) {
            $source_content = get_the_excerpt($post_obj);
        } 
        // Check if non-breaking space exists in the text with variations
        if (preg_match('/\s*(&nbsp;|\xA0)\s*/u', $source_content)) {
            // Remove non-breaking space and its variations from the text
            $source_content = preg_replace('/\s*(&nbsp;|\xA0)\s*/u', ' ', $source_content);
        }

        $source_content = preg_replace( '`\[[^\]]*\]`', '', $source_content );
        $trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );
        return $trimmed_content;
    }
endif;

if ( ! function_exists( 'blogarise_breadcrumb_trail' ) ) :
    /**
     * Theme default breadcrumb function.
     *
     * @since 1.0.0
     */
    function blogarise_breadcrumb_trail() {
        if ( ! function_exists( 'breadcrumb_trail' ) ) {
            // load class file
            require_once get_template_directory() . '/inc/ansar/breadcrumb-trail/breadcrumb-trail.php';
        }

        $breadcrumb_args = array(
            'container' => 'div',
            'show_browse' => false,
        );
        breadcrumb_trail( $breadcrumb_args );
    }
    add_action( 'blogarise_breadcrumb_trail_content', 'blogarise_breadcrumb_trail' );
endif;

if( ! function_exists( 'blogarise_breadcrumb' ) ) :
    /**
     *
     * @package blogarise
     */
    function blogarise_breadcrumb() {
        if ( is_front_page() || is_home() ) return;
        $breadcrumb_settings = get_theme_mod('breadcrumb_settings','1');
        if($breadcrumb_settings == 1) {

        $blogarise_site_breadcrumb_type = get_theme_mod('blogarise_site_breadcrumb_type','default');
            ?>
            <div class="bs-breadcrumb-section">
                <div class="overlay">
                    <div class="container">
                        <div class="row">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <?php if($blogarise_site_breadcrumb_type == 'yoast') {
                                        if( function_exists( 'yoast_breadcrumb' ) ) {
                                            yoast_breadcrumb();
                                        }
                                    }
                                    elseif($blogarise_site_breadcrumb_type == 'navxt') {
                                        if( function_exists( 'bcn_display' ) ) {
                                            bcn_display();
                                        }
                                    }
                                    elseif($blogarise_site_breadcrumb_type == 'rankmath') {
                                        if( function_exists( 'rank_math_the_breadcrumbs' ) ) {
                                            rank_math_the_breadcrumbs();
                                        }
                                    }
                                    else {
                                        do_action( 'blogarise_breadcrumb_trail_content' );
                                    }
                                    ?> 
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        <?php } 
    }
endif;
add_action( 'blogarise_breadcrumb_content', 'blogarise_breadcrumb' );


if ( ! function_exists( 'blogarise_page_pagination' ) ) :
    function blogarise_page_pagination() { 
        
        $blogarise_pagination_remove = get_theme_mod('blogarise_pagination_remove','true'); ?>

        <div class="col-md-12 text-center d-md-flex justify-content-<?php if($blogarise_pagination_remove == 'true') { echo 'between';} else { echo 'center';} ?>">
            <?php //Previous / next page navigation
                    
            $prev_text =  (is_rtl()) ? "right" : "left";
            $next_text =  (is_rtl()) ? "left" : "right";
            the_posts_pagination( array(
                'prev_text'          => '<i class="fa fa-angle-'.$prev_text.'"></i>',
                'next_text'          => '<i class="fa fa-angle-'.$next_text.'"></i>',
                ) 
            );
            if($blogarise_pagination_remove == true) { ?>
                <div class="navigation"><p><?php posts_nav_link(); ?></p></div>
            <?php } ?>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'blogarise_search_count' ) ) :
    function blogarise_search_count() { 
        global $wp_query;
        $total_results = $wp_query->found_posts;
        ?>
        <!-- Results Count -->
        <p class="search-results-count">
            <?php
            if ( $total_results > 0 ) {
                // Translators: %s is the number of found results.
                echo sprintf(
                    _n( '%s result found', '%s results found', $total_results, 'blogarise' ),
                    number_format_i18n( $total_results )
                );
            } else {
                echo esc_html__( 'No results found', 'blogarise' );
            }
            ?>
        </p>
        <?php
    }
endif;