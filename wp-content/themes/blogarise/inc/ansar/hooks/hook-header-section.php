<?php
/**
 *  Header
 *
 * @since Blogarise
 *
 */

if (!function_exists('blogarise_header_topbar_section')) :
  function blogarise_header_topbar_section() { ?>
    <div class="container">
      <div class="row align-items-center">
        <!-- mg-latest-news -->
        <div class="col-md-8 col-xs-12">
          <?php do_action('blogarise_action_header_ticker_section'); ?>
        </div>
        <!--/col-md-6-->
        <div class="col-md-4 col-xs-12">
          <?php do_action('blogarise_action_header_social_section'); ?>
        </div>
        <!--/col-md-6-->
      </div>
    </div>
  <?php }
endif;
add_action('blogarise_action_header_top_section', 'blogarise_header_topbar_section', 2);

if (!function_exists('blogarise_header_social_section')) :
function blogarise_header_social_section() {
  $header_social_icon_enable = get_theme_mod('header_social_icon_enable','1');
  if($header_social_icon_enable == 1) { ?>
  <ul class="bs-social info-left">
  <?php
    $social_icons = get_theme_mod( 'blogarise_header_social_icons', blogarise_get_social_icon_default() );
    $social_icons = json_decode( $social_icons );
    if ( $social_icons != '' ) {
      foreach ( $social_icons as $social_item ) {
        $social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'blogarise_translate_single_string', $social_item->icon_value, 'Header section' ) : '';
        $open_new_tab = ! empty( $social_item->open_new_tab ) ? apply_filters( 'blogarise_translate_single_string', $social_item->open_new_tab, 'Header section' ) : '';
        $social_link = ! empty( $social_item->link ) ? apply_filters( 'blogarise_translate_single_string', $social_item->link, 'Header section' ) : ''; ?>
        <li>
          <a <?php if ($open_new_tab == 'yes') { echo 'target="_blank"'; } ?> href="<?php echo esc_url( $social_link ); ?>">
            <i class="<?php echo esc_attr( $social_icon ); ?>"></i>
          </a>
        </li>
        <?php
      }
    }
  ?>
  </ul>
  <?php }
}
endif;
add_action('blogarise_action_header_social_section', 'blogarise_header_social_section', 2);

if (!function_exists('blogarise_header_ticker_section')) :
  function blogarise_header_ticker_section() {
    $brk_news_enable = get_theme_mod('brk_news_enable',true); 
    if($brk_news_enable == true) { ?>
      <div class="mg-latest-news">
        <?php $category = blogarise_get_option('select_flash_news_category');
        $number_of_posts = blogarise_get_option('number_of_flash_news');
        $breaking_news_title = blogarise_get_option('breaking_news_title');
        $all_posts = blogarise_get_posts($number_of_posts, $category);
        $count = 1; ?>
        <!-- mg-latest-news -->
        <?php if (!empty($breaking_news_title)): ?>
          <div class="bn_title">
            <h2 class="title"><?php echo esc_html($breaking_news_title); ?></h2>
          </div>
        <?php endif; ?>
        <!-- mg-latest-news_slider -->
                  
        <div class="mg-latest-news-slider marquee" <?php if(is_rtl()){ ?> data-direction='right' dir="ltr" <?php } ?> >
          <?php if ($all_posts->have_posts()) :
            while ($all_posts->have_posts()) : $all_posts->the_post();
              if(is_rtl()) { ?> 
                <a href="<?php the_permalink(); ?>">
                  <span><?php the_title(); ?></span>
                  <i class="fa-solid fa-circle-arrow-left"></i>
                </a>
              <?php } else { ?>
                <a href="<?php the_permalink(); ?>">
                <i class="fa-solid fa-circle-arrow-right"></i>
                  <span><?php the_title(); ?></span>
                </a>
              <?php }
              $count++;
            endwhile;
          endif;
          wp_reset_postdata(); ?>
        </div>
        <!-- // mg-latest-news_slider -->
      </div>
    <?php }
  }
endif;
add_action('blogarise_action_header_ticker_section', 'blogarise_header_ticker_section', 2);
  