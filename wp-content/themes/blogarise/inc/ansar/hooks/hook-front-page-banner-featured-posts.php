<?php
if (!function_exists('blogarise_banner_featured_posts')):
    /**
     * Ticker Slider
     *
     * @since blogarise 1.0.0
     *
     */
    function blogarise_banner_featured_posts()
    {
        $color_class = 'category-color-1';
        ?>
        <?php
        $blogarise_enable_featured_news = blogarise_get_option('show_featured_news_section');
        if ($blogarise_enable_featured_news):
            $blogarise_featured_news_title = blogarise_get_option('featured_news_section_title');
	        $dir = 'ltr';
	        if(is_rtl()){
		        $dir = 'rtl';
	        }
            ?>
            <div class="ta-main-banner-featured-posts featured-posts" dir="<?php echo esc_attr($dir);?>">
                <?php if (!empty($blogarise_featured_news_title)): ?>
                    <h4 class="header-tater1 ">
                                <span class="header-tater <?php echo esc_attr($color_class); ?>">
                                    <?php echo esc_html($blogarise_featured_news_title); ?>
                                </span>
                    </h4>
                <?php endif; ?>


                <div class="section-wrapper">
                    <div class="ta-double-column list-style ta-container-row clearfix">
                        <?php
                        $blogarise_featured_category = blogarise_get_option('select_featured_news_category');
                        $blogarise_number_of_featured_news = blogarise_get_option('number_of_featured_news');

                        $featured_posts = blogarise_get_posts($blogarise_number_of_featured_news, $blogarise_featured_category);
                        if ($featured_posts->have_posts()) :
                            while ($featured_posts->have_posts()) :
                                $featured_posts->the_post();

                                global $post;
                                $url = blogarise_get_freatured_image_url($post->ID, 'thumbnail');
                                ?>

                                <div class="col-3 pad float-l " data-mh="ta-feat-list">
                                    <div class="read-single color-pad">
                                        <div class="data-bg read-img pos-rel col-4 float-l read-bg-img"
                                             data-background="<?php echo esc_url($url); ?>">
                                            <img src="<?php echo esc_url($url); ?>">

                                            <span class="min-read-post-format">
		  								<?php echo esc_attr(blogarise_post_format($post->ID)); ?>
                                        <?php blogarise_count_content_words($post->ID); ?>
                                        </span>

                                        </div>
                                        <div class="read-details col-75 float-l pad color-tp-pad">
                                            <div class="read-categories">
                                                <?php blogarise_post_categories(); ?>
                                            </div>
                                            <div class="read-title">
                                                <h4>
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h4>
                                            </div>

                                            <div class="entry-meta">
                                                <?php blogarise_post_meta(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile;
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>

        <?php endif;
    }
endif;

add_action('blogarise_action_banner_featured_posts', 'blogarise_banner_featured_posts', 10);