<?php if (!function_exists('blogarise_banner_advertisement')):
    /**
     *
     * @since blogarise 1.0.0
     *
     */
    function blogarise_banner_advertisement(){

        if (('' != blogarise_get_option('banner_advertisement_section')) ) { ?>
            
                <?php $blogarise_banner_advertisement = blogarise_get_option('banner_advertisement_section');
                    $blogarise_banner_advertisement = absint($blogarise_banner_advertisement);
                    $blogarise_banner_advertisement = wp_get_attachment_image($blogarise_banner_advertisement, 'full');
                    $blogarise_banner_advertisement_url = blogarise_get_option('banner_advertisement_section_url');
                    $blogarise_banner_advertisement_url = isset($blogarise_banner_advertisement_url) ? esc_url($blogarise_banner_advertisement_url) : '#';
                    $blogarise_open_on_new_tab = blogarise_get_option('blogarise_open_on_new_tab');
                    $blogarise_open_on_new_tab = ('' != $blogarise_open_on_new_tab) ? '_blank' : '';
                    $center_logo_title = get_theme_mod('blogarise_center_logo_title',false); ?>
                    
                <div class="<?php echo esc_attr($center_logo_title == false ? 'col-lg-8' : 'col text-center mx-auto'); ?>">
                    <div class="<?php echo esc_attr($center_logo_title == false ? 'text-md-end' : 'text-center'); ?>">
                        <a class="pull-right img-fluid" href="<?php echo esc_url($blogarise_banner_advertisement_url); ?>" target="<?php echo esc_attr($blogarise_open_on_new_tab); ?>">
                            <?php echo $blogarise_banner_advertisement; ?>
                        </a>
                    </div>
                </div>
            <?php
        }

        if (is_active_sidebar('home-advertisement-widgets')): ?>
            <div class="col-lg-8">
                <div class="text-md-end">
                      <?php dynamic_sidebar('home-advertisement-widgets'); ?>
                </div>
            </div>
        <?php endif; 
    }
endif;

add_action('blogarise_action_banner_advertisement', 'blogarise_banner_advertisement', 10);