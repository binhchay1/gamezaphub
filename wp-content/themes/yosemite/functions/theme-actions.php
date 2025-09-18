<?php
$mts_options = get_option(MTS_THEME_NAME);
/*------------[ Meta ]-------------*/
if ( ! function_exists( 'mts_meta' ) ) {
	function mts_meta(){
	global $mts_options, $post;
?>
<?php if ($mts_options['mts_favicon'] != ''){ ?>
	<link rel="icon" href="<?php echo $mts_options['mts_favicon']; ?>" type="image/x-icon" />
<?php } ?>
<!--iOS/android/handheld specific -->
<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-icon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php if($mts_options['mts_prefetching'] == '1') { ?>
<?php if (is_front_page()) { ?>
	<?php $my_query = new WP_Query('posts_per_page=1'); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<link rel="prefetch" href="<?php the_permalink(); ?>">
	<link rel="prerender" href="<?php the_permalink(); ?>">
	<?php endwhile; wp_reset_query(); ?>
<?php } elseif (is_singular()) { ?>
	<link rel="prefetch" href="<?php echo home_url(); ?>">
	<link rel="prerender" href="<?php echo home_url(); ?>">
<?php } ?>
<?php } ?>
<?php }
}

/*------------[ Head ]-------------*/
if ( ! function_exists( 'mts_head' ) ){
	function mts_head() {
	global $mts_options;
?>
<?php echo $mts_options['mts_header_code']; ?>
<?php }
}
add_action('wp_head', 'mts_head');

/*------------[ Copyrights ]-------------*/
if ( ! function_exists( 'mts_copyrights_credit' ) ) {
    function mts_copyrights_credit() {
    global $mts_options;
?>
<!--start copyrights-->

<span><a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a> Copyright &copy; <?php echo date("Y") ?>.</span>
<span><?php echo $mts_options['mts_copyrights']; ?></span>
<!--end copyrights-->
<?php }
}

/*------------[ footer ]-------------*/
if ( ! function_exists( 'mts_footer' ) ) {
	function mts_footer() {
	global $mts_options;
?>
<?php if ($mts_options['mts_analytics_code'] != '') { ?>
<!--start footer code-->
<?php echo $mts_options['mts_analytics_code']; ?>
<!--end footer code-->
<?php } ?>
<?php }
}

// Last item in the breadcrumbs
if ( ! function_exists( 'get_itemprop_3' ) ) {
	function get_itemprop_3( $title = '', $position = '2' ) {
		echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		echo '<span itemprop="name">' . $title . '</span>';
		echo '<meta itemprop="position" content="' . $position . '" />';
		echo '</span>';
	}
}
if ( ! function_exists( 'mts_the_breadcrumb' ) ) {
	/**
	 * Display the breadcrumbs.
	 */
	function mts_the_breadcrumb() {
		if ( is_front_page() ) {
				return;
		}
		if ( function_exists( 'rank_math_the_breadcrumbs' ) && RankMath\Helper::get_settings( 'general.breadcrumbs' ) ) {
			rank_math_the_breadcrumbs();
			return;
		}
		$seperator = '<span><i class="fa fa-angle-right"></i></span>';
		echo '<div class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
		echo '<span itemprop="itemListElement" itemscope
	      itemtype="https://schema.org/ListItem" class="root"><a href="';
		echo esc_url( home_url() );
		echo '" itemprop="item"><span itemprop="name">' . esc_html__( 'Home', 'mythemeshop' );
		echo '</span><meta itemprop="position" content="1" /></a></span>' . $seperator;
		if ( is_single() ) {
			$categories = get_the_category();
			if ( $categories ) {
				$level         = 0;
				$hierarchy_arr = array();
				foreach ( $categories as $cat ) {
					$anc       = get_ancestors( $cat->term_id, 'category' );
					$count_anc = count( $anc );
					if ( 0 < $count_anc && $level < $count_anc ) {
						$level         = $count_anc;
						$hierarchy_arr = array_reverse( $anc );
						array_push( $hierarchy_arr, $cat->term_id );
					}
				}
				if ( empty( $hierarchy_arr ) ) {
					$category = $categories[0];
					echo '<span itemprop="itemListElement" itemscope
				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
				} else {
					foreach ( $hierarchy_arr as $cat_id ) {
						$category = get_term_by( 'id', $cat_id, 'category' );
						echo '<span itemprop="itemListElement" itemscope
					      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
					}
				}
				get_itemprop_3( get_the_title(), '3' );
			} else {
				get_itemprop_3( get_the_title() );
			}
		} elseif ( is_page() ) {
			$parent_id = wp_get_post_parent_id( get_the_ID() );
			if ( $parent_id ) {
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page          = get_page( $parent_id );
					$breadcrumbs[] = '<span itemprop="itemListElement" itemscope
				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $page->ID ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $page->ID ) ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
					$parent_id = $page->post_parent;
				}
				$breadcrumbs = array_reverse( $breadcrumbs );
				foreach ( $breadcrumbs as $crumb ) { echo $crumb; }
				get_itemprop_3( get_the_title(), 3 );
			} else {
				get_itemprop_3( get_the_title() );
			}
		} elseif ( is_category() ) {
			global $wp_query;
			$cat_obj       = $wp_query->get_queried_object();
			$this_cat_id   = $cat_obj->term_id;
			$hierarchy_arr = get_ancestors( $this_cat_id, 'category' );
			if ( $hierarchy_arr ) {
				$hierarchy_arr = array_reverse( $hierarchy_arr );
				foreach ( $hierarchy_arr as $cat_id ) {
					$category = get_term_by( 'id', $cat_id, 'category' );
					echo '<span itemprop="itemListElement" itemscope
				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
				}
			}
			get_itemprop_3( single_cat_title( '', false ) );
		} elseif ( is_author() ) {
			if ( get_query_var( 'author_name' ) ) :
				$curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
			else :
				$curauth = get_userdata( get_query_var( 'author' ) );
			endif;
			get_itemprop_3( esc_html( $curauth->nickname ) );
		} elseif ( is_search() ) {
			get_itemprop_3( get_search_query() );
		} elseif ( is_tag() ) {
			get_itemprop_3( single_tag_title( '', false ) );
		}
		echo '</div>';
	}
}

/*------------[ schema.org-enabled the_category() and the_tags() ]-------------*/
function mts_the_category( $separator = ', ' ) {
    $categories = get_the_category();
    $count = count($categories);
    foreach ( $categories as $i => $category ) {
        echo '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", 'mythemeshop' ), $category->name ) . '" ' . '>' . $category->name.'</a>';
        if ( $i < $count - 1 )
            echo $separator;
    }
}
function mts_the_tags($before = null, $sep = ', ', $after = '') {
    if ( null === $before )
        $before = __('Tags: ', 'mythemeshop');

    $tags = get_the_tags();
    if (empty( $tags ) || is_wp_error( $tags ) ) {
        return;
    }
    $tag_links = array();
    foreach ($tags as $tag) {
        $link = get_tag_link($tag->term_id);
        $tag_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $tag->name . '</a>';
    }
    echo $before.join($sep, $tag_links).$after;
}

/*------------[ pagination ]-------------*/
if (!function_exists('mts_pagination')) {
    function mts_pagination($pages = '', $range = 2) {
        $showitems = ($range * 2)+1;
        global $paged; if(empty($paged)) $paged = 1;
        if($pages == '') {
            global $wp_query; $pages = $wp_query->max_num_pages;
            if(!$pages){ $pages = 1; }
        }
        if(1 != $pages) {
            echo "<div class='pagination'><ul>";
            if($paged > 2 && $paged > $range+1 && $showitems < $pages)
                echo "<li><a href='".get_pagenum_link(1)."'><i class='fa fa-angle-double-left'></i></a></li>";
            if($paged > 1 && $showitems < $pages)
                echo "<li><a href='".get_pagenum_link($paged - 1)."' class='inactive'><i class='fa fa-angle-left'></i></a></li>";
            for ($i=1; $i <= $pages; $i++){
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                    echo ($paged == $i)? "<li class='current'><span class='currenttext'>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive'>".$i."</a></li>";
                }
            }
            if ($paged < $pages && $showitems < $pages)
                echo "<li><a href='".get_pagenum_link($paged + 1)."' class='inactive'><i class='fa fa-angle-right'></i></a></li>";
            if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
                echo "<li><a class='inactive' href='".get_pagenum_link($pages)."'><i class='fa fa-angle-double-right'></i></a></li>";
                echo "</ul></div>";
        }
    }
}

/*------------[ Related Posts ]-------------*/
if (!function_exists('mts_related_posts')) {
    function mts_related_posts() {
        global $post;
        $mts_options = get_option(MTS_THEME_NAME);
        if(!empty($mts_options['mts_related_posts'])) { ?>
    		<!-- Start Related Posts -->
    		<?php
            $empty_taxonomy = false;
            if (empty($mts_options['mts_related_posts_taxonomy']) || $mts_options['mts_related_posts_taxonomy'] == 'tags') {
                // related posts based on tags
                $tags = get_the_tags($post->ID);
                if (empty($tags)) {
                    $empty_taxonomy = true;
                } else {
                    $tag_ids = array();
                    foreach($tags as $individual_tag) {
                        $tag_ids[] = $individual_tag->term_id;
                    }
                    $args = array( 'tag__in' => $tag_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $mts_options['mts_related_postsnum'],
                        'ignore_sticky_posts' => 1,
                        'orderby' => 'rand'
                    );
                }
             } else {
                // related posts based on categories
                $categories = get_the_category($post->ID);
                if (empty($categories)) {
                    $empty_taxonomy = true;
                } else {
                    $category_ids = array();
                    foreach($categories as $individual_category)
                        $category_ids[] = $individual_category->term_id;
                    $args = array( 'category__in' => $category_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $mts_options['mts_related_postsnum'],
                        'ignore_sticky_posts' => 1,
                        'orderby' => 'rand'
                    );
                }
             }
            if (!$empty_taxonomy) {
    		$my_query = new wp_query( $args ); if( $my_query->have_posts() ) {
                echo '<div class="related-posts">';
                echo '<h4 class="section-title">'.__('Related Posts','mythemeshop').'</h4>';
                echo '<div class="related-posts-container clearfix">';
                while( $my_query->have_posts() ) { $my_query->the_post(); ?>
                    <article class="related-post post-box">
                        <div class="related-post-inner">
                            <div class="post-img">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) {
                                    $thumb_id  = get_post_thumbnail_id();
                                    $image     = wp_get_attachment_image_src( $thumb_id, 'full' );
                                    $image_url = $image[0];
                                    $thumbnail = bfi_thumb( $image_url, array( 'width' => 90, 'height' => 90, 'crop' => true ) );
                                } else {
                                    $thumbnail = get_template_directory_uri().'/images/nothumb-90x90.png';
                                }
                                echo '<img src="'.$thumbnail.'" class="wp-post-image">' ?>
                                </a>
                            </div>
                            <div class="post-data">
                                <div class="post-title"><a href="<?php the_permalink(); ?>"><?php echo mts_truncate( get_the_title(), '8', 'words' ); ?></a></div>
                                <div class="post-info"><span class="thetime"><?php the_time( get_option( 'date_format' ) ); ?></span></div>
                            </div>
                        </div>
                    </article><!--.related-post-->
                <?php } echo '</div></div>'; }} wp_reset_query(); ?>
            <!-- .related-posts -->
    	<?php }
    }
}


if ( ! function_exists('mts_the_postinfo' ) ) {
    function mts_the_postinfo( $section = 'home' ) {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( ! empty( $mts_options["mts_{$section}_headline_meta"] ) ) { ?>
			<div class="post-info">
				<?php if ( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['author']) ) { ?>
					<span class="theauthor"><i class="fa fa-user"></i> <span><?php the_author_posts_link(); ?></span></span>
				<?php } ?>
				<?php if( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['date']) ) { ?>
					<span class="thetime updated"><i class="fa fa-clock-o"></i> <span><?php the_time( get_option( 'date_format' ) ); ?></span></span>
				<?php } ?>
				<?php if( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['category']) ) { ?>
					<span class="thecategory"><i class="fa fa-tags"></i> <?php mts_the_category(', ') ?></span>
				<?php } ?>
				<?php if( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['comment']) ) { ?>
					<span class="thecomment"><i class="fa fa-comments"></i> <a href="<?php comments_link(); ?>"><?php echo comments_number();?></a></span>
				<?php } ?>
			</div>
		<?php }
    }
}

if (!function_exists('mts_social_buttons')) {
    function mts_social_buttons() {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( $mts_options['mts_social_buttons'] == '1' ) { ?>
    		<!-- Start Share Buttons -->
    		<div class="shareit  <?php echo $mts_options['mts_social_button_position']; ?>">
    			<?php if($mts_options['mts_twitter'] == '1') { ?>
    				<!-- Twitter -->
    				<span class="share-item twitterbtn">
    					<a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo $mts_options['mts_twitter_username']; ?>">Tweet</a>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_gplus'] == '1') { ?>
    				<!-- GPlus -->
    				<span class="share-item gplusbtn">
    					<g:plusone size="medium"></g:plusone>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_facebook'] == '1') { ?>
    				<!-- Facebook -->
    				<span class="share-item facebookbtn">
    					<div id="fb-root"></div>
    					<div class="fb-like" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_linkedin'] == '1') { ?>
    				<!--Linkedin -->
    				<span class="share-item linkedinbtn">
    					<script type="IN/Share" data-url="<?php get_permalink(); ?>"></script>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_stumble'] == '1') { ?>
    				<!-- Stumble -->
    				<span class="share-item stumblebtn">
    					<su:badge layout="1"></su:badge>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_pinterest'] == '1') { global $post; ?>
    				<!-- Pinterest -->
    				<span class="share-item pinbtn">
    					<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); echo $thumb['0']; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal">Pin It</a>
    				</span>
    			<?php } ?>
    		</div>
    		<!-- end Share Buttons -->
    	<?php }
    }
}

/*------------[ Class attribute for <article> element ]-------------*/
if ( ! function_exists( 'mts_article_class' ) ) {
    function mts_article_class() {
        $mts_options = get_option( MTS_THEME_NAME );
        $class = '';

        // sidebar or full width
        if ( mts_custom_sidebar() == 'mts_nosidebar' ) {
            $class = 'ss-full-width';
        } else {
            $class = 'article';
        }
        // single post/related posts layout
        //if ( is_singular( 'post' ) && $mts_options['mts_related_posts'] ) {
            //$class .= ' '.$mts_options['mts_single_post_layout'];
        //}

        echo $class;
    }
}

function mts_theme_action( $action = null ) {
    update_option( 'mts__thl', '1' );
    update_option( 'mts__pl', '1' );
}

function mts_theme_activation( $oldtheme_name = null, $oldtheme = null ) {
    // Check for Connect plugin version > 1.4
    if ( class_exists('mts_connection') && defined('MTS_CONNECT_ACTIVE') && MTS_CONNECT_ACTIVE ) {
        return;
    }
     $plugin_path = 'mythemeshop-connect/mythemeshop-connect.php';

    // Check if plugin exists
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    if ( ! array_key_exists( $plugin_path, $plugins ) ) {
        // auto-install it
        include_once( ABSPATH . 'wp-admin/includes/misc.php' );
        include_once( ABSPATH . 'wp-admin/includes/file.php' );
        include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        $skin     = new Automatic_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );
        $plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';
        $result = $upgrader->install( $plugin_file );
        // If install fails then revert to previous theme
        if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
            switch_theme( $oldtheme->stylesheet );
            return false;
        }
    } else {
        // Plugin is already installed, check version
        $ver = isset( $plugins[$plugin_path]['Version'] ) ? $plugins[$plugin_path]['Version'] : '1.0';
         if ( version_compare( $ver, '2.0.5' ) === -1 ) {
            include_once( ABSPATH . 'wp-admin/includes/misc.php' );
            include_once( ABSPATH . 'wp-admin/includes/file.php' );
            include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
            include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            $skin     = new Automatic_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );

            add_filter( 'pre_site_transient_update_plugins',  'mts_inject_connect_repo', 10, 2 );
            $result = $upgrader->upgrade( $plugin_path );
            remove_filter( 'pre_site_transient_update_plugins', 'mts_inject_connect_repo' );

            // If update fails then revert to previous theme
            if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
                switch_theme( $oldtheme->stylesheet );
                return false;
            }
        }
    }
    $activate = activate_plugin( $plugin_path );
}

function mts_inject_connect_repo( $pre, $transient ) {
    $plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';

    $return = new stdClass();
    $return->response = array();
    $return->response['mythemeshop-connect/mythemeshop-connect.php'] = new stdClass();
    $return->response['mythemeshop-connect/mythemeshop-connect.php']->package = $plugin_file;

    return $return;
}

add_action( 'wp_loaded', 'mts_maybe_set_constants' );
function mts_maybe_set_constants() {
    if ( ! defined( 'MTS_THEME_S' ) ) {
        mts_set_theme_constants();
    }
}

add_action( 'init', 'mts_nhp_sections_override', -11 );
function mts_nhp_sections_override() {
    define( 'MTS_THEME_INIT', 1 );
    if ( class_exists('mts_connection') && defined('MTS_CONNECT_ACTIVE') && MTS_CONNECT_ACTIVE ) {
        return;
    }
    if ( ! get_option( MTS_THEME_NAME, false ) ) {
        return;
    }
    add_filter( 'nhp-opts-sections', '__return_empty_array' );
    add_filter( 'nhp-opts-sections', 'mts_nhp_section_placeholder' );
    add_filter( 'nhp-opts-args', 'mts_nhp_opts_override' );
    add_filter( 'nhp-opts-extra-tabs', '__return_empty_array', 11, 1 );
}

function mts_nhp_section_placeholder( $sections ) {
    $sections[] = array(
        'icon' => 'fa fa-cogs',
        'title' => __('Not Connected', 'mythemeshop' ),
        'desc' => '<p class="description">' . __('You will find all the theme options here after connecting with your MyThemeShop account.', 'mythemeshop' ) . '</p>',
        'fields' => array()
    );
    return $sections;
}

function mts_nhp_opts_override( $opts ) {
    $opts['show_import_export'] = false;
    $opts['show_typography'] = false;
    $opts['show_translate'] = false;
    $opts['show_child_theme_opts'] = false;
    $opts['last_tab'] = 0;

    return $opts;
}

?>
