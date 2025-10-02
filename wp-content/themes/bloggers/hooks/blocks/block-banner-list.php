<?php
/**
 * Banner List Block - Accessible Version
 * Fixed heading hierarchy and added proper aria-labels
 * 
 * @package Bloggers
 */

$blogarise_slider_category = blogarise_get_option('select_slider_news_category');
$blogarise_number_of_slides = blogarise_get_option('number_of_slides');
$blogarise_all_posts_main = blogarise_get_posts($blogarise_number_of_slides, $blogarise_slider_category);
$blogarise_count = 1;

if ($blogarise_all_posts_main->have_posts()) :
    while ($blogarise_all_posts_main->have_posts()) : $blogarise_all_posts_main->the_post();

    global $post;
    $blogarise_url = blogarise_get_freatured_image_url($post->ID, 'blogarise-slider-full');
    $slider_meta_enable = get_theme_mod('slider_meta_enable','true');
    $post_title = get_the_title();
    $post_url = get_the_permalink();

  ?>
  <div class="swiper-slide">
    <div class="bs-slide two back-img" style="background-image: url('<?php echo esc_url($blogarise_url); ?>');">
      <a href="<?php echo esc_url($post_url); ?>" 
         class="link-div" 
         aria-label="<?php echo esc_attr(sprintf(__('Read more: %s', 'bloggers'), $post_title)); ?>"></a>
      <div class="inner">
        <?php if($slider_meta_enable == true) { ?>
          <div class="bs-blog-category"><?php blogarise_post_categories(); ?></div>
        <?php } ?>

        <!-- Changed from h4 to h3 for proper heading hierarchy -->
        <h3 class="title wp-block-heading"> 
          <a href="<?php echo esc_url($post_url); ?>"
             aria-label="<?php echo esc_attr($post_title); ?>">
            <?php echo esc_html($post_title); ?>
          </a>
        </h3>
        
        <?php if($slider_meta_enable == true) { blogarise_post_meta(); } ?>
      </div>
    </div>
  </div>
      
  <?php 
    endwhile;
endif;
wp_reset_postdata();
?>
