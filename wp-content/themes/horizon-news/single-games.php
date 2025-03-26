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

$slug = get_query_var('game_slug');
$game_data = get_game_data($slug);

if ($game_data) {
    $post = $game_data;
    $screen_shots = $post['meta']['screen_shots'];
    $genres = $post['meta']['genres'];
    $platforms = $post['meta']['platforms'];
    $developers = $post['meta']['developers'];
    $publishers = $post['meta']['publishers'];
    $lasso_id = $post['ID'];
    $related_query = $post['related_posts'];
} else {
    get_template_part('template-parts/content', 'none');
    get_footer();
    exit;
}

?>

<main id="primary" class="site-main">
    <header class="page-header d-flex justify-content-between">
        <h1 class="page-title"><?php echo esc_html($post['post_title']); ?></h1>
        <div class="rating-title">GR ★ <?php echo $post['meta']['rating'] ?>/5</div>
    </header>
    <div class="<?php echo esc_attr($grid_style); ?> d-flex main-information">
        <div class="custom-gallery-container">
            <div class="main-image">
                <img src="<?php echo esc_url($screen_shots[0]); ?>" alt="Main Image">
                <div>
                    <button class="zoom-btn">
                        <svg fill="white" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 242.133 242.133" xml:space="preserve">
                            <path id="XMLID_13_" d="M227.133,83.033c8.284,0,15-6.716,15-15V15c0-8.284-6.716-15-15-15H174.1c-8.284,0-15,6.716-15,15s6.716,15,15,15h16.821l-59.246,59.247c-5.858,5.857-5.858,15.355,0,21.213c2.929,2.929,6.768,4.394,10.606,4.394c3.839,0,7.678-1.465,10.606-4.394l59.245-59.245v16.818C212.133,76.317,218.849,83.033,227.133,83.033z" />
                            <path id="XMLID_14_" d="M110.46,131.673c-5.857-5.858-15.354-5.858-21.213,0L30,190.92V174.1c0-8.284-6.716-15-15-15s-15,6.716-15,15v53.032c0,8.284,6.715,15,15,15l53.033,0.001l0,0c8.283,0,15-6.716,15-15c0-8.284-6.715-15-15-15h-16.82l59.247-59.247C116.318,147.028,116.318,137.53,110.46,131.673z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="thumbnail-container">
                <?php foreach ($screen_shots as $index => $shot) { ?>
                    <div class="thumbnail"><img src="<?php echo esc_url($shot); ?>" data-index="<?php echo esc_attr($index); ?>" alt="Thumbnail"></div>
                <?php } ?>
            </div>
        </div>

        <div class="information-area">
            <div class="information-item">
                <img src="<?php echo esc_url($post['meta']['background_image']); ?>" alt="Background Image" />
            </div>

            <div class="information-item">
                <p>Thể loại:</p>
                <div class="genre information-subitem">
                    <?php foreach ($genres as $index => $genre) { ?>
                        <div class="genre-item"><?php echo esc_html($genre['name']); ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="information-item">
                <p style="white-space: nowrap;">Nền tảng:</p>
                <div class="systems information-subitem">
                    <?php foreach ($platforms as $index => $platform) { ?>
                        <div class="system-item"><?php echo esc_html($platform['platform']['name']); ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="information-item">
                <p>Tuổi:</p>
                <div class="esrb information-subitem">
                    <div class="esrb-item"><?php echo esc_html($post['meta']['esrb_rating_name']); ?></div>
                </div>
            </div>

            <div class="information-item">
                <p>Nhà phát triển:</p>
                <div class="developers information-subitem">
                    <div class="developers-item"><?php echo esc_html($developers[0]['name']); ?></div>
                </div>
            </div>

            <div class="information-item">
                <p>Nhà phát hành:</p>
                <div class="publishers information-subitem">
                    <div class="publishers-item"><?php echo esc_html($publishers[0]['name']); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="description-area mt-3">
        <h2 class="description-title">Mô tả</h2>
        <div class="description-content">
            <?php if (!empty($post['meta']['affiliate_desc'])) {
                echo wp_kses_post($post['meta']['affiliate_desc']);
            } ?>
        </div>
    </div>

    <?php if ($related_query->have_posts()) : ?>
        <div class="magazine-archive-layout grid-layout <?php echo esc_attr($grid_style); ?> mt-3">
            <?php
            while ($related_query->have_posts()) :
                $related_query->the_post();
                get_template_part('template-parts/content', get_post_type());
            endwhile;
            ?>
        </div>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</main>

<?php
if (horizon_news_is_sidebar_enabled()) {
    get_sidebar();
}
get_footer();
