<?php

function custom_table_block_render($block_content, $block)
{
    if ($block['blockName'] === 'core/table') {
        $block_content = '<div class="custom-table-wrapper">' .
            str_replace(
                '<table>',
                '<table class="custom-table wp-block-table">',
                $block_content
            ) .
            '</div>';
    }
    return $block_content;
}
add_filter('render_block', 'custom_table_block_render', 10, 2);

function custom_gallery_block_render($block_content, $block)
{
    if ($block['blockName'] !== 'core/gallery') {
        return $block_content;
    }

    $images = array();
    if (!empty($block['innerBlocks'])) {
        foreach ($block['innerBlocks'] as $inner_block) {
            if ($inner_block['blockName'] === 'core/image') {
                $img_id = $inner_block['attrs']['id'] ?? null;
                if ($img_id) {
                    $images[] = array(
                        'id' => $img_id,
                        'url' => wp_get_attachment_url($img_id),
                        'alt' => get_post_meta($img_id, '_wp_attachment_image_alt', true),
                        'caption' => wp_get_attachment_caption($img_id)
                    );
                }
            }
        }
    }

    if (empty($images)) {
        return $block_content;
    }

    $gallery_id = 'custom-gallery-' . uniqid();

    ob_start();
?>
    <div class="custom-gallery-container"
        id="<?php echo esc_attr($gallery_id); ?>"
        data-gallery-id="<?php echo esc_attr($gallery_id); ?>"
        data-gallery-images='<?php echo esc_attr(json_encode($images)); ?>'
        data-gallery-current="0"
        data-gallery-items-per-view="5">
        <div class="main-image">
            <img src="<?php echo esc_url($images[0]['url']); ?>"
                alt="<?php echo esc_attr($images[0]['alt']); ?>"
                data-index="0"
                class="main-gallery-image">
            <button class="zoom-btn" onclick="openModal('<?php echo esc_js($gallery_id); ?>', 0)">Phóng to</button>
            <div class="nav-buttons">
                <button class="prev-btn" onclick="changeImage('<?php echo esc_js($gallery_id); ?>', -1)">←</button>
                <button class="next-btn" onclick="changeImage('<?php echo esc_js($gallery_id); ?>', 9999)">→</button>
            </div>
        </div>
        <div class="thumbnail-wrapper">
            <div class="thumbnail-container">
                <?php foreach ($images as $index => $image): ?>
                    <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <img src="<?php echo esc_url($image['url']); ?>"
                            alt="<?php echo esc_attr($image['alt']); ?>"
                            data-index="<?php echo $index; ?>"
                            onclick="changeImage('<?php echo esc_js($gallery_id); ?>', <?php echo $index; ?>)"
                            class="thumbnail-image">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="gallery-modal" id="modal-<?php echo esc_attr($gallery_id); ?>">
        <span class="close" onclick="closeModal('<?php echo esc_js($gallery_id); ?>')">×</span>
        <img class="modal-content-img" src="" alt="Full Screen Image">
    </div>

<?php
    return ob_get_clean();
}
add_filter('render_block', 'custom_gallery_block_render', 10, 2);

function custom_scripts()
{
    $js_file = get_stylesheet_directory() . '/js/custom.js';
    if (file_exists($js_file)) {
        wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true);
        wp_script_add_data('custom-scripts', 'defer', true);
    }
}
add_action('wp_enqueue_scripts', 'custom_scripts');

add_filter('render_block', function ($block_content, $block) {
    if ($block['blockName'] !== 'core/video') {
        return $block_content;
    }

    $video_src = '';
    if (!empty($block['attrs']['src'])) {
        $video_src = esc_url($block['attrs']['src']);
    } elseif (!empty($block['attrs']['id'])) {
        $video_src = wp_get_attachment_url($block['attrs']['id']);
    }

    if (empty($video_src)) {
        return $block_content;
    }

    ob_start();
?>
    <div class="video-player">
        <video id="my-video-<?php echo uniqid(); ?>" class="video">
            <source src="<?php echo $video_src; ?>" type="video/mp4">
            Trình duyệt của bạn không hỗ trợ thẻ video.
        </video>
        <div class="now-playing">Đang phát</div>
        <div class="video-controls">
            <button class="play-pause control-btn">▶</button>
            <button class="volume-btn control-btn">🔊</button>
            <input type="range" class="volume-bar" value="1" min="0" max="1" step="0.01">
            <input type="range" class="progress-bar" value="0" min="0">
            <span class="current-time">0:00</span> / <span class="duration">0:00</span>
        </div>
    </div>
<?php
    return ob_get_clean();
}, 10, 2);

function custom_render_block_quote($block_content, $block)
{
    if ($block['blockName'] !== 'core/quote') {
        return $block_content;
    }

    $block_content = str_replace('wp-block-quote', 'wp-block-quote custom-blockquote-tip', $block_content);

    return $block_content;
}
add_filter('render_block', 'custom_render_block_quote', 10, 2);
