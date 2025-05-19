<?php
if (! class_exists('Horizon_News_Popular_Widget')) {
    /**
     * Adds Horizon_News_Popular_Widget Widget.
     */
    class Horizon_News_Popular_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        public function __construct()
        {
            $horizon_news_grid_list_widget_ops = array(
                'classname'   => 'ascendoor-widget magazine-grid-list-section style-1',
                'description' => __('Retrive Popular Widgets', 'horizon-news'),
            );
            parent::__construct(
                'horizon_news_grid_list_widget',
                __('Ascendoor Popular Widget', 'horizon-news'),
                $horizon_news_grid_list_widget_ops
            );
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance)
        {
            if (! isset($args['widget_id'])) {
                $args['widget_id'] = $this->id;
            }
            $grid_list_title        = (! empty($instance['title'])) ? $instance['title'] : '';
            $grid_list_title        = apply_filters('widget_title', $grid_list_title, $instance, $this->id_base);
            $grid_list_button_label = (! empty($instance['button_label'])) ? $instance['button_label'] : '';
            $grid_list_post_offset  = isset($instance['offset']) ? absint($instance['offset']) : '';
            $grid_list_category     = isset($instance['category']) ? absint($instance['category']) : '';
            $grid_list_button_link  = (! empty($instance['button_link'])) ? $instance['button_link'] : esc_url(get_category_link($grid_list_category));

            echo $args['before_widget'];

            if (! empty($grid_list_title || $grid_list_button_label)) {
?>
                <div class="section-header">
                    <?php
                    echo $args['before_title'] . esc_html($grid_list_title) . $args['after_title'];
                    if (! empty($grid_list_button_label)) {
                    ?>
                        <a href="<?php echo esc_url($grid_list_button_link); ?>" class="mag-view-all-link">
                            <span><?php echo esc_html($grid_list_button_label); ?></span>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="magazine-section-body">
                <div class="magazine-grid-list-section-wrapper">
                    <?php
                    $magazine_grid_list_widgets_args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => absint(4),
                        'offset'         => absint($grid_list_post_offset),
                        'cat'            => absint($grid_list_category),
                    );

                    $query = new WP_Query($magazine_grid_list_widgets_args);
                    if ($query->have_posts()) :
                        $i = 1;
                        while ($query->have_posts()) :
                            $query->the_post();
                            $has_image        = has_post_thumbnail() ? 'has-image' : '';
                            $small_list_class = $i === 1 ? '' : 'small-list-design';
                            $custom_class = $i === 1 ? '' : 'custom-small-list-design';
                            $classes          = implode(' ', array($has_image, $small_list_class, $custom_class));
                    ?>
                            <div class="mag-post-single <?php echo esc_attr($classes); ?>">
                                <?php if (has_post_thumbnail()) { ?>
                                    <div class="mag-post-img">
                                        <?php if ($i === 1) { ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail(); ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php
                                            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                            if ($thumb_url): ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <img
                                                        src="<?php echo esc_url($thumb_url); ?>"
                                                        alt="<?php the_title_attribute(); ?>"
                                                        width="800" height="450"
                                                        fetchpriority="high"
                                                        decoding="async" />
                                                </a>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="mag-post-detail">
                                    <div class="mag-post-category">
                                        <?php horizon_news_categories_list(); ?>
                                    </div>
                                    <h3 class="mag-post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="mag-post-meta">
                                        <?php
                                        horizon_news_posted_by();
                                        horizon_news_posted_on();
                                        ?>
                                    </div>
                                    <?php if (1 === $i) : ?>
                                        <div class="mag-post-excerpt">
                                            <p><?php echo esc_html(wp_trim_words(get_the_content(), 25)); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                            $i++;
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        <?php
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance)
        {
            $grid_list_title        = isset($instance['title']) ? $instance['title'] : '';
            $grid_list_button_label = isset($instance['button_label']) ? $instance['button_label'] : '';
            $grid_list_button_link  = isset($instance['button_link']) ? $instance['button_link'] : '';
            $grid_list_post_offset  = isset($instance['offset']) ? absint($instance['offset']) : '';
            $grid_list_category     = isset($instance['category']) ? absint($instance['category']) : '';
        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Section Title:', 'horizon-news'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($grid_list_title); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('button_label')); ?>"><?php esc_html_e('View All Button:', 'horizon-news'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_label')); ?>" name="<?php echo esc_attr($this->get_field_name('button_label')); ?>" type="text" value="<?php echo esc_attr($grid_list_button_label); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('button_link')); ?>"><?php esc_html_e('View All Button URL:', 'horizon-news'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_link')); ?>" name="<?php echo esc_attr($this->get_field_name('button_link')); ?>" type="text" value="<?php echo esc_attr($grid_list_button_link); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('offset')); ?>"><?php esc_html_e('Number of posts to displace or pass over:', 'horizon-news'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('offset')); ?>" name="<?php echo esc_attr($this->get_field_name('offset')); ?>" type="number" step="1" min="0" value="<?php echo absint($grid_list_post_offset); ?>" size="3" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Select the category to show posts:', 'horizon-news'); ?></label>
                <select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" class="widefat" style="width:100%;">
                    <?php
                    $categories = horizon_news_get_post_cat_choices();
                    foreach ($categories as $category => $value) {
                    ?>
                        <option value="<?php echo absint($category); ?>" <?php selected($grid_list_category, $category); ?>><?php echo esc_html($value); ?></option>
                    <?php
                    }
                    ?>
                </select>
            </p>
<?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance)
        {
            $instance                 = $old_instance;
            $instance['title']        = sanitize_text_field($new_instance['title']);
            $instance['button_label'] = sanitize_text_field($new_instance['button_label']);
            $instance['button_link']  = esc_url_raw($new_instance['button_link']);
            $instance['offset']       = (int) $new_instance['offset'];
            $instance['category']     = (int) $new_instance['category'];
            return $instance;
        }
    }
}
