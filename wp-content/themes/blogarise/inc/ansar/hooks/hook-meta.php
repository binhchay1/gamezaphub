<?php //Category fields meta starts
if (!function_exists('blogarise_taxonomy_add_new_meta_field')) :
// Add term page
    function blogarise_taxonomy_add_new_meta_field()
    {
        // this will add the custom meta field to the add new term page

        $cat_color = array(
            'category-color-1' => __('Category Color 1', 'blogarise'),
            'category-color-2' => __('Category Color 2', 'blogarise'),
            'category-color-3' => __('Category Color 3', 'blogarise'),
            'category-color-4' => __('Category Color 4', 'blogarise'),

        );
        ?>
        <div class="form-field">
            <label for="term_meta[color_class_term_meta]"><?php esc_html_e('Color Class', 'blogarise'); ?></label>
            <select id="term_meta[color_class_term_meta]" name="term_meta[color_class_term_meta]">
                <?php foreach ($cat_color as $key => $value): ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php esc_html_e('Select category color class. You can set appropriate categories color on "Categories" section of the theme customizer.', 'blogarise'); ?></p>
        </div>
        <?php
    }
endif;
add_action('category_add_form_fields', 'blogarise_taxonomy_add_new_meta_field', 10, 2);


if (!function_exists('blogarise_taxonomy_edit_meta_field')) :
// Edit term page
    function blogarise_taxonomy_edit_meta_field($term)
    {

        // put the term ID into a variable
        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option("category_color_$t_id");

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label
                        for="term_meta[color_class_term_meta]"><?php esc_html_e('Color Class', 'blogarise'); ?></label></th>
            <td>
                <?php
                $cat_color = array(
                    'category-color-1' => __('Category Color 1', 'blogarise'),
                    'category-color-2' => __('Category Color 2', 'blogarise'),
                    'category-color-3' => __('Category Color 3', 'blogarise'),
                    'category-color-4' => __('Category Color 4', 'blogarise'),
                );
                ?>
               <select id="term_meta[color_class_term_meta]" name="term_meta[color_class_term_meta]">
                <?php foreach ($cat_color as $key => $value): ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
                <p class="description"><?php esc_html_e('Select category color class. You can set appropriate categories color on "Categories" section of the theme customizer.', 'blogarise'); ?></p>
            </td>
        </tr>
        <?php
    }
endif;
add_action('category_edit_form_fields', 'blogarise_taxonomy_edit_meta_field', 10, 2);




if (!function_exists('blogarise_save_taxonomy_color_class_meta')) :
// Save extra taxonomy fields callback function.
    function blogarise_save_taxonomy_color_class_meta($term_id)
    {
        if (isset($_POST['term_meta'])) {
            $t_id = $term_id;
            $term_meta = get_option("category_color_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            foreach ($cat_keys as $key) {
                if (isset ($_POST['term_meta'][$key])) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option("category_color_$t_id", $term_meta);
        }
    }

endif;
add_action('edited_category', 'blogarise_save_taxonomy_color_class_meta', 10, 2);
add_action('create_category', 'blogarise_save_taxonomy_color_class_meta', 10, 2);