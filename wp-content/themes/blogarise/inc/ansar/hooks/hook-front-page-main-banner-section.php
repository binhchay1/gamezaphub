<?php
if (!function_exists('blogarise_front_page_banner_section')) :
    /**
     *
     * @since blogarise
     *
     */
    function blogarise_front_page_banner_section()
    {
        if (is_front_page() || is_home()) {
        $blogarise_enable_main_slider = blogarise_get_option('show_main_news_section');
        $select_vertical_slider_news_category = blogarise_get_option('select_vertical_slider_news_category');
        $all_posts_vertical = blogarise_get_posts($select_vertical_slider_news_category);
        if ($blogarise_enable_main_slider): ?>
         <div class="col-12 cc">
            <div class="homemain bs swiper-container">
                <div class="swiper-wrapper">
                   <?php blogarise_get_block('list', 'banner'); ?>         
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                </div>
            </div>
        <!--==/ Home Slider ==-->
        <?php endif; ?>
        <!-- end slider-section -->
        <?php }
    }
endif;
add_action('blogarise_action_front_page_main_section_1', 'blogarise_front_page_banner_section', 40); ?>
