<?php if (!function_exists('blogarise_featured_ads_section')) :
/**
 *  Header
 *
 * @since blogarise
 *
 */
function blogarise_featured_ads_section() {

  if (is_front_page() || is_home()) {

    $show_featured_links_section = get_theme_mod('show_featured_links_section',false);
    $fatured_post_image_one = get_theme_mod('fatured_post_image_one'); 
    $fatured_post_image_one_atc = wp_get_attachment_image($fatured_post_image_one);
    $featured_post_one_btn_txt = get_theme_mod('featured_post_one_btn_txt');
    $featured_post_one_url = get_theme_mod('featured_post_one_url');
    $featured_post_one_url_new_tab = get_theme_mod('featured_post_one_url_new_tab', true);

    $fatured_post_image_two = get_theme_mod('fatured_post_image_two');
    $fatured_post_image_two_atc = wp_get_attachment_image($fatured_post_image_two);
    $featured_post_two_btn_txt = get_theme_mod('featured_post_two_btn_txt');
    $featured_post_two_url = get_theme_mod('featured_post_two_url');
    $featured_post_two_url_new_tab = get_theme_mod('featured_post_two_url_new_tab', true);

    $fatured_post_image_three = get_theme_mod('fatured_post_image_three');
    $fatured_post_image_three_atc = wp_get_attachment_image($fatured_post_image_three);
    $featured_post_three_btn_txt = get_theme_mod('featured_post_three_btn_txt');
    $featured_post_three_url = get_theme_mod('featured_post_three_url');
    $featured_post_three_url_new_tab = get_theme_mod('featured_post_three_url_new_tab', true);

    if($show_featured_links_section == 'true') {
      ?>
      <div class="promoss mb-4">
        <div class="container">
          <div class="row">  
            <!-- promo box -->
            <div class="col-md-4 one">
              <?php blogarise_featured_ads_list ($fatured_post_image_one, $featured_post_one_url_new_tab, $featured_post_one_url ,$featured_post_one_btn_txt); ?>
            </div>
            <!-- /promo box -->
            <!-- promo box -->
            <div class="col-md-4 two">
              <?php blogarise_featured_ads_list ($fatured_post_image_two, $featured_post_two_url_new_tab, $featured_post_two_url ,$featured_post_two_btn_txt); ?>
            </div>
            <!-- promo box -->
            <!-- /promo box -->
            <div class="col-md-4 three">
              <?php blogarise_featured_ads_list ($fatured_post_image_three, $featured_post_three_url_new_tab, $featured_post_three_url ,$featured_post_three_btn_txt); ?>
            </div>
          </div><!-- /row -->
        </div><!-- /container -->
      </div>  
      <?php 
    } 
  } 
}
endif;
add_action('blogarise_action_featured_ads_section', 'blogarise_featured_ads_section', 5);


if (!function_exists('blogarise_featured_ads_list')) :
  /**
   *  Header
   *
   * @since Blogarise
   *
   */
  function blogarise_featured_ads_list($image, $target , $link ,$text  ) { ?>
    <div class="bs-widget promo three bshre" style="background-image: url('<?php echo esc_url($image); ?>');">
      <div class="inner-content">
        <div class="text">
          <h5>
            <?php if (!empty($text)) {
              echo '<a '. esc_attr($target).' href="'. esc_url($link).'">'. esc_html($text).'</a>';
            } ?>
          </h5>
        </div>
      </div>
    </div>
    <?php
  }
endif;
add_action('blogarise_action_featured_ads_list', 'blogarise_featured_ads_list', 5);