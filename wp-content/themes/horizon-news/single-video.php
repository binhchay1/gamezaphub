<?php

/**
 * Template for displaying single video attachment
 */

get_header();

$slug = get_query_var('video_slug');

$video = get_posts([
    'name'  => $slug,
    'post_type'   => 'attachment',
    'post_status' => 'inherit',
    'numberposts' => 1,
    'post_mime_type' => 'video',
]);

if (!$video) {
    echo '<p>Video không tồn tại.</p>';
    get_footer();
    exit;
}

$video = $video[0];
$video_url = wp_get_attachment_url($video->ID);
$thumbnail_url = get_the_post_thumbnail_url($video->ID, 'large');
$upload_date = $video->post_date;
$date = new DateTime($upload_date, new DateTimeZone('Asia/Ho_Chi_Minh'));
$uploadDateIso = $date->format(DateTime::ATOM);
$description = get_the_excerpt() ?: get_the_content();
?>

<main id="primary" class="site-main">
    <div class="single-video-page">
        <div class="video-hero">
            <video controls poster="<?= esc_url($thumbnail_url); ?>" class="video-center">
                <source src="<?= esc_url($video_url); ?>" type="<?= esc_attr($video->post_mime_type); ?>">
                Trình duyệt của bạn không hỗ trợ video.
            </video>
        </div>

        <div class="video-meta">
            <div class="video-author mag-post-meta">
                Bởi <?php horizon_news_posted_by(); ?> — Phát hành ngày <?php horizon_news_posted_on(); ?>
            </div>

            <h1 class="video-title">
                <?= horizon_news_unslugify($video->post_title) ?>
            </h1>

            <div class="video-description">
                <?= wpautop(wp_kses_post($video->post_content)); ?>
            </div>
        </div>
    </div>
</main>


<?php
if (horizon_news_is_sidebar_enabled()) {
    get_sidebar();
}
?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "VideoObject",
        "name": "<?= esc_js(horizon_news_unslugify($video->post_title)); ?>",
        "description": "<?= esc_js(wp_strip_all_tags($description)); ?>",
        "thumbnailUrl": "<?= esc_url($thumbnail_url); ?>",
        "uploadDate": "<?= esc_js($uploadDateIso); ?>",
        "embedUrl": "<?= esc_url($video_url); ?>"
    }
</script>
<?php
get_footer();
?>