<?php

$blogarise_default = blogarise_get_default_theme_options();
require get_template_directory() . '/inc/ansar/customize/frontpage-options.php';

function blogarise_enqueue_custom_fonts()
{
    if (! get_theme_mod('enable_custom_typography', false)) return;

    $fonts_to_load = [];

    $site_font = get_theme_mod('site_title_fontfamily', 'Outfit, sans-serif');
    $menu_font = get_theme_mod('blogarise_menu_fontfamily', 'Outfit, sans-serif');

    $google_fonts = [
        'Outfit' => 'Outfit:wght@300;500;600;700&display=swap',
        'Josefin Sans' => 'Josefin+Sans:wght@300;500;600;700&display=swap',
        'Open Sans' => 'Open+Sans:wght@300;500;600;700&display=swap',
        'Kalam' => 'Kalam:wght@300;500;600;700&display=swap',
        'Rokkitt' => 'Rokkitt:wght@300;500;600;700&display=swap',
        'Jost' => 'Jost:wght@300;500;600;700&display=swap',
        'Poppins' => 'Poppins:wght@300;500;600;700&display=swap',
        'Lato' => 'Lato:wght@300;500;600;700&display=swap',
        'Noto Serif' => 'Noto+Serif:wght@300;500;600;700&display=swap',
        'Raleway' => 'Raleway:wght@300;500;600;700&display=swap',
        'Roboto' => 'Roboto:wght@300;500;600;700&display=swap',
    ];

    $all_fonts = [$site_font, $menu_font];
    foreach ($all_fonts as $font) {
        $key = explode(',', $font)[0];
        $key = trim($key);
        if (isset($google_fonts[$key])) {
            $fonts_to_load[$key] = $google_fonts[$key];
        }
    }

    foreach ($fonts_to_load as $font_name => $query) {
        wp_enqueue_style(
            'blogarise-font-' . sanitize_title($font_name),
            'https://fonts.googleapis.com/css2?family=' . esc_attr($query),
            [],
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'blogarise_enqueue_custom_fonts');

function blogarise_preload_fonts()
{
    if (! get_theme_mod('enable_custom_typography', false)) return;
?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php
}
add_action('wp_head', 'blogarise_preload_fonts', 1);

class Blogarise_Custom_Radio_Default_Image_Control extends WP_Customize_Control
{
    public $type = 'radio-image';

    public function enqueue()
    {
        wp_enqueue_script('jquery-ui-button');
    }

    public function render_content()
    {
        if (empty($this->choices)) return;

        $name = '_customize-radio-' . $this->id; ?>
        <span class="customize-control-title">
            <?php echo esc_attr($this->label); ?>
            <?php if (! empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </span>
        <div id="input_<?php echo $this->id; ?>" class="image">
            <?php foreach ($this->choices as $value => $label): ?>
                <input class="image-select" type="radio" value="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr($this->id . $value); ?>" name="<?php echo esc_attr($name); ?>" <?php $this->link();
                                                                                                                                                                                        checked($this->value(), $value); ?>>
                <label for="<?php echo esc_attr($this->id . $value); ?>">
                    <img src="<?php echo esc_url($label); ?>" alt="<?php echo esc_attr($value); ?>" title="<?php echo esc_attr($value); ?>">
                </label>
                </input>
            <?php endforeach; ?>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('[id="input_<?php echo $this->id; ?>"]').buttonset();
            });
        </script>
<?php }
}

function blogarise_sanitize_text_content($input, $setting)
{
    return stripslashes(wp_filter_post_kses(addslashes($input)));
}
