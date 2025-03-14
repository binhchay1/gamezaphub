<?php

/**
 * The template for displaying games pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Horizon News
 */

get_header();
$grid_style = get_theme_mod('horizon_news_archive_grid_style', 'grid-column-2');
?>

<?php
$slug = get_query_var('game_slug');
$meta_key = 'lasso_final_url';

$query = $wpdb->prepare(
    "SELECT p.ID, p.post_title, pm.meta_key, pm.meta_value
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'lasso-urls'
    AND pm.meta_key IS NOT NULL
    AND EXISTS (
        SELECT 1
        FROM {$wpdb->postmeta} pm2
        WHERE pm2.post_id = p.ID
        AND pm2.meta_key = %s
        AND pm2.meta_value LIKE %s
    )",
    $meta_key,
    '%/games/' . $slug . '%'
);

$results = $wpdb->get_results($query);

$posts = array();
foreach ($results as $row) {
    $post_id = $row->ID;
    if (!isset($posts[$post_id])) {
        $posts[$post_id] = array(
            'ID' => $post_id,
            'post_title' => $row->post_title,
            'meta' => array()
        );
    }
    if ($row->meta_key) {
        $posts[$post_id]['meta'][$row->meta_key] = $row->meta_value;
    }
}

?>

<main id="primary" class="site-main">
    <header class="page-header">
        <h1 class="page-title"><?php echo $posts['post_title'] ?></h1>
    </header>
    <div class="magazine-archive-layout grid-layout <?php echo esc_attr($grid_style); ?>">
        <div>

        </div>
        <div>
            
        </div>
    </div>
</main>
<?php
if (horizon_news_is_sidebar_enabled()) {
    get_sidebar();
}
get_footer();
