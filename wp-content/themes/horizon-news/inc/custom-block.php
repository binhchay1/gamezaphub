<?php

function custom_table_block_styles()
{
    wp_enqueue_style('custom-table-styles', get_template_directory_uri() . '/assets/css/custom-table.css', array(), '1.0');
}
add_action('enqueue_block_assets', 'custom_table_block_styles');

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

function custom_gallery_block_scripts()
{
    wp_enqueue_script('custom-gallery-block', get_template_directory_uri() . '/assets/js/custom-gallery.js', array(), '1.0', true);
}
add_action('enqueue_block_editor_assets', 'custom_gallery_block_scripts');

function custom_gallery_frontend_scripts()
{
    wp_enqueue_script('custom-gallery-block', get_template_directory_uri() . '/assets/js/custom-gallery.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'custom_gallery_frontend_scripts');

function custom_video_player_scripts()
{
    wp_enqueue_style('custom-video-player-styles', get_template_directory_uri() . '/assets/css/custom-video.css', array(), '1.0');
    wp_enqueue_script('custom-video-player-script', get_template_directory_uri() . '/assets/js/video-player.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'custom_video_player_scripts');

add_filter('render_block', function ($block_content, $block) {
    // Chỉ áp dụng cho block core/video
    if ($block['blockName'] !== 'core/video') {
        return $block_content;
    }

    // Lấy nguồn video từ thuộc tính của block
    $video_src = '';
    if (!empty($block['attrs']['src'])) {
        $video_src = esc_url($block['attrs']['src']);
    } elseif (!empty($block['attrs']['id'])) {
        // Nếu video được upload vào media library, lấy URL từ attachment ID
        $video_src = wp_get_attachment_url($block['attrs']['id']);
    }

    // Nếu không có nguồn video, trả về nội dung gốc
    if (empty($video_src)) {
        return $block_content;
    }

    // Tạo HTML tùy chỉnh cho video player
    ob_start();
?>
    <div class="video-player">
        <video id="my-video-<?php echo uniqid(); ?>" class="video">
            <source src="<?php echo $video_src; ?>" type="video/mp4">
            Your browser does not support the video tag.
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
