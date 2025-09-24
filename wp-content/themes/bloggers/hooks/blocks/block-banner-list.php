<?php
$blogarise_slider_category = blogarise_get_option('select_slider_news_category');
$blogarise_number_of_slides = blogarise_get_option('number_of_slides');
$blogarise_all_posts_main = blogarise_get_posts($blogarise_number_of_slides, $blogarise_slider_category);
$blogarise_count = 1;

if ($blogarise_all_posts_main->have_posts()) :
    while ($blogarise_all_posts_main->have_posts()) : $blogarise_all_posts_main->the_post();

    global $post;
    $blogarise_url = blogarise_get_freatured_image_url($post->ID, 'blogarise-slider-full');
        
$blogarise_url = blogarise_get_freatured_image_url($post->ID, 'blogarise-slider-full');
$slider_meta_enable = get_theme_mod('slider_meta_enable','true');

  ?>
  <div class="swiper-slide">
    <div class="bs-slide two back-img" style="background-image: url('<?php echo esc_url($blogarise_url); ?>');">
      <a href="<?php the_permalink(); ?>" class="link-div"></a>
      <div class="inner">
        <?php if($slider_meta_enable == true) { ?><div class="bs-blog-category"><?php blogarise_post_categories(); ?></div> <?php } ?>

                   <h4 class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <?php if($slider_meta_enable == true) { blogarise_post_meta(); } ?>
      </div>
    </div>
  </div>
      
         <?php 
    endwhile;
endif;
wp_reset_postdata();
?>