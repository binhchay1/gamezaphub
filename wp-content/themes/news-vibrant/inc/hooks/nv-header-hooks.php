<?php
/**
 * Custom hooks functions are define about header section.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_top_header_start' ) ) :

	/**
	 * Top header start
	 *
	 * @since 1.0.0
	 */

	function news_vibrant_top_header_start() {
		echo '<div class="nv-top-header-wrap">';
		echo '<div class="cv-container">';
	}

endif;


if ( ! function_exists( 'news_vibrant_top_left_section' ) ) :

	/**
	 * Top header left section
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_top_left_section() {
		$news_vibrant_date_option = get_theme_mod( 'news_vibrant_top_date_option', true );
?>
		<div class="nv-top-left-section-wrapper">
			<?php
				if ( $news_vibrant_date_option == true ) {
					echo '<div class="date-section">'. esc_html( date_i18n('l, F d, Y') ) .'</div>';
				}

				if ( has_nav_menu( 'news_vibrant_top_menu' ) ) {
			?>
				<nav id="top-navigation" class="top-navigation" role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'news_vibrant_top_menu', 'menu_id' => 'top-menu', 'fallback_cb' => false ) );
					?>
				</nav><!-- #site-navigation -->
			<?php } ?>
		</div><!-- .nv-top-left-section-wrapper -->
<?php
	}

endif;


if ( ! function_exists( 'news_vibrant_top_right_section' ) ) :

	/**
	 * Top header right section
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_top_right_section() {
?>
		<div class="nv-top-right-section-wrapper">
			<?php
				$news_vibrant_top_social_option = get_theme_mod( 'news_vibrant_top_social_option', true );
				if ( true == $news_vibrant_top_social_option ) {
					news_vibrant_social_media();
				}
			?>
		</div><!-- .nv-top-right-section-wrapper -->
<?php
	}

endif;


if ( ! function_exists( 'news_vibrant_featured_post_toggle' ) ) :

	/**
	 * Top featured posts section toggle
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_featured_post_toggle() {
		$news_vibrant_top_featured_option = get_theme_mod( 'news_vibrant_top_featured_option', true );
		$news_vibrant_top_posts_cat_slugs = get_theme_mod( 'news_vibrant_top_posts_cat_slugs', '' );
		if ( true == $news_vibrant_top_featured_option && !empty( $news_vibrant_top_posts_cat_slugs ) ) {
			echo '<div class="nv-top-featured-toggle toggle-icon" title="'. esc_attr( 'Feature Posts', 'news-vibrant' ) .'"><i class="fa fa-chevron-up"> </i></div>';
		}
	}

endif;


if ( ! function_exists( 'news_vibrant_top_header_end' ) ) :

	/**
	 * Top header end
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_top_header_end() {
		echo '</div><!-- .cv-container -->';
		echo '</div><!-- .nv-top-header-wrap -->';
	}

endif;

/**
 * Managed functions for top header hook
 *
 * @since 1.0.0
 */
add_action( 'news_vibrant_top_header', 'news_vibrant_top_header_start', 5 );
add_action( 'news_vibrant_top_header', 'news_vibrant_top_left_section', 10 );
add_action( 'news_vibrant_top_header', 'news_vibrant_top_right_section', 15 );
add_action( 'news_vibrant_top_header', 'news_vibrant_featured_post_toggle', 20 );
add_action( 'news_vibrant_top_header', 'news_vibrant_top_header_end', 25 );

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_top_featured_section_start' ) ) :

	/**
	 * header featured section start
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_top_featured_section_start() {
		echo '<div class="nv-top-featured-wrapper">';
		echo '<div class="cv-container">';
	}

endif;


if ( ! function_exists( 'news_vibrant_top_featured_content' ) ) :

	/**
	 * header featured posts content
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_top_featured_content() {
?>
		<div class="nv-featured-posts-wrapper">
        <?php
        	$news_vibrant_top_posts_cat_slugs = get_theme_mod( 'news_vibrant_top_posts_cat_slugs', '' );
            if ( !empty( $news_vibrant_top_posts_cat_slugs ) ) {
                $checked_cats = array();
                foreach ( $news_vibrant_top_posts_cat_slugs as $cat_key => $cat_value ) {
                    $checked_cats[] = $cat_value;
                }
                $get_checked_cat_slugs = implode( ",", $checked_cats );
                $news_vibrant_post_count = apply_filters( 'news_vibrant_featured_posts_count', 4 );
                $news_vibrant_posts_args = array(
                        'post_type'      => 'post',
                        'category_name'  => wp_kses_post( $get_checked_cat_slugs ),
                        'posts_per_page' => absint( $news_vibrant_post_count )
                    );
                $news_vibrant_posts_query = new WP_Query( $news_vibrant_posts_args );
                if ( $news_vibrant_posts_query->have_posts() ) {
                    while ( $news_vibrant_posts_query->have_posts() ) {
                        $news_vibrant_posts_query->the_post();
        ?>
                    <div class="nv-single-post-wrap nv-clearfix">
                        <div class="nv-single-post">
                            <div class="nv-post-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                        if ( has_post_thumbnail() ) {
                                            the_post_thumbnail( 'news-vibrant-block-thumb' );
                                        }
                                    ?>
                                </a>
                            </div><!-- .nv-post-thumb -->
                            <div class="nv-post-content">
                                <h3 class="nv-post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="nv-post-meta"> <?php news_vibrant_post_date(); ?> </div>
                            </div><!-- .nv-post-content -->
                        </div> <!-- nv-single-post -->
                    </div><!-- .nv-single-post-wrap -->
        <?php
                    }
                }
                wp_reset_postdata();
            }
        ?>
        </div><!-- .nv-featured-posts-wrapper -->
<?php
	}

endif;


if ( ! function_exists( 'news_vibrant_top_featured_section_end' ) ) :

	/**
	 * header featured section end
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_top_featured_section_end() {
		echo '</div><!-- .cv-container -->';
		echo '</div><!-- .nv-top-featured-wrapper -->';
	}

endif;

/**
 * Managed functions for top featured section hook
 *
 * @since 1.0.0
 */
add_action( 'news_vibrant_top_featured_section', 'news_vibrant_top_featured_section_start', 5 );
add_action( 'news_vibrant_top_featured_section', 'news_vibrant_top_featured_content', 10 );
add_action( 'news_vibrant_top_featured_section', 'news_vibrant_top_featured_section_end', 15 );

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_header_section_start' ) ) :

	/**
	 * header section start
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_header_section_start() {
		echo '<header id="masthead" class="site-header" role="banner">';
	}

endif;


if ( ! function_exists( 'news_vibrant_header_logo_ads_section_start' ) ) :

	/**
	 * header logo and ads section start
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_header_logo_ads_section_start() {
		echo '<div class="nv-logo-section-wrapper">';
		echo '<div class="cv-container">';
	}

endif;


if ( ! function_exists( 'news_vibrant_site_branding_section' ) ) :

	/**
	 * site branding section
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_site_branding_section() {
?>
		<div class="site-branding">

			<?php if ( the_custom_logo() ) { ?>
				<div class="site-logo">
					<?php the_custom_logo(); ?>
				</div><!-- .site-logo -->
			<?php } ?>

			<?php
			if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
			
		</div><!-- .site-branding -->
<?php
	}

endif;


if ( ! function_exists( 'news_vibrant_header_ads_section' ) ) :

	/**
	 * header ads area
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_header_ads_section() {
?>
		<div class="nv-header-ads-area">
			<?php
				if ( is_active_sidebar( 'news_vibrant_header_ads_area' ) ) {
					dynamic_sidebar( 'news_vibrant_header_ads_area' );
				}
			?>
		</div><!-- .nv-header-ads-area -->
<?php
	}

endif;


if ( ! function_exists( 'news_vibrant_header_logo_ads_section_end' ) ) :

	/**
	 * header logo and ads section end
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_header_logo_ads_section_end() {
		echo '</div><!-- .cv-container -->';
		echo '</div><!-- .nv-logo-section-wrapper -->';
	}

endif;


if ( ! function_exists( 'news_vibrant_primary_menu_section' ) ) :

	/**
	 * header primary menu section
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_primary_menu_section() {
?>
		<div id="nv-menu-wrap" class="nv-header-menu-wrapper">
			<div class="nv-header-menu-block-wrap">
				<div class="cv-container">
					<?php
						$news_vibrant_home_icon_option = get_theme_mod( 'news_vibrant_home_icon_option', true );
						if ( true == $news_vibrant_home_icon_option ) {
					?>
							<div class="nv-home-icon">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <i class="fa fa-home"> </i> </a>
							</div><!-- .nv-home-icon -->
					<?php } ?>
					<div class="nv-header-menu-wrap">
	                    <a href="javascript:void(0)" class="menu-toggle hide"> <i class="fa fa-navicon"> </i> </a>
						<nav id="site-navigation" class="main-navigation" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'news_vibrant_primary_menu', 'menu_id' => 'primary-menu' ) );
							?>
						</nav><!-- #site-navigation -->
					</div><!-- .nv-header-menu-wrap -->
					<?php
						$news_vibrant_search_icon_option = get_theme_mod( 'news_vibrant_search_icon_option', 'show' );
						if ( $news_vibrant_search_icon_option == 'show' ) {
					?>
						<div class="nv-header-search-wrapper">                    
			                <span class="search-main"><a href="javascript:void(0);"><i class="fa fa-search"></i></a></span>
			                <div class="search-form-main nv-clearfix">
				                <?php get_search_form(); ?>
				            </div>
						</div><!-- .nv-header-search-wrapper -->
					<?php } ?>
				</div>
			</div>
		</div><!-- .nv-header-menu-wrapper -->
<?php
	}

endif;


if ( ! function_exists( 'news_vibrant_header_section_end' ) ) :

	/**
	 * header section end
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_header_section_end() {
		echo '</header><!-- .site-header -->';
	}

endif;

/**
 * Managed functions for ticker section
 *
 * @since 1.0.0
 */
add_action( 'news_vibrant_header_section', 'news_vibrant_header_section_start', 5 );
add_action( 'news_vibrant_header_section', 'news_vibrant_header_logo_ads_section_start', 10 );
add_action( 'news_vibrant_header_section', 'news_vibrant_site_branding_section', 15 );
add_action( 'news_vibrant_header_section', 'news_vibrant_header_ads_section', 20 );
add_action( 'news_vibrant_header_section', 'news_vibrant_header_logo_ads_section_end', 25 );
add_action( 'news_vibrant_header_section', 'news_vibrant_primary_menu_section', 30 );
add_action( 'news_vibrant_header_section', 'news_vibrant_header_section_end', 35 );

/*-----------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'news_vibrant_ticker_section_start' ) ) :

	/**
	 * Ticker section start
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_ticker_section_start() {
		echo '<div class="nv-ticker-wrapper">';
		echo '<div class="cv-container">';
		echo '<div class="nv-ticker-block nv-clearfix">';
	}

endif;


if ( ! function_exists( 'news_vibrant_ticker_content' ) ) :

	/**
	 * Ticker content area
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_ticker_content() {
		$news_vibrant_ticker_caption = get_theme_mod( 'news_vibrant_ticker_caption', __( 'Breaking News', 'news-vibrant' ) );
?>
		<span class="ticker-caption"><?php echo esc_html( $news_vibrant_ticker_caption ); ?></span>
        <div class="ticker-content-wrapper">
			<?php
				$news_vibrant_ticker_cat_id = apply_filters( 'news_vibrant_ticker_cat_id', null );
				$ticker_args = array(
					'cat' => $news_vibrant_ticker_cat_id,
					'posts_per_page' => 5,
				);
				$ticker_query = new WP_Query( $ticker_args );
				if ( $ticker_query->have_posts() ) {
					echo '<ul id="newsTicker" class="cS-hidden">';
					while ( $ticker_query->have_posts() ) {
						$ticker_query->the_post();
					?>			
						<li>
							<div class="news-ticker-title">
								<a href="<?php the_permalink(); ?>"><?php the_title();?></a>
							</div>
						</li>
					<?php
					}
					echo '</ul>';
				}
			?>
		</div><!-- .ticker-content-wrapper -->
<?php
	}

endif;


if ( ! function_exists( 'news_vibrant_ticker_section_end' ) ) :

	/**
	 * Ticker section end
	 *
	 * @since 1.0.0
	 */
	function news_vibrant_ticker_section_end() {
		echo '</div><!-- .nv-ticker-block -->';
		echo '</div><!-- .cv-container -->';
		echo '</div><!-- .nv-ticker-wrapper -->';
	}
	
endif;

/**
 * Managed functions for ticker section
 *
 * @since 1.0.0
 */
add_action( 'news_vibrant_ticker_section', 'news_vibrant_ticker_section_start', 5 );
add_action( 'news_vibrant_ticker_section', 'news_vibrant_ticker_content', 10 );
add_action( 'news_vibrant_ticker_section', 'news_vibrant_ticker_section_end', 15 );