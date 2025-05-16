<?php
/*
Plugin Name: Image Optimizer
Description: Tối ưu ảnh, tạo WebP và AVIF khi upload và hỗ trợ tối ưu hàng loạt từ giao diện admin.
Version: 1.3
Author: BroGPT & bạn hữu
*/

if (!defined('ABSPATH')) exit;

// ==== XỬ LÝ KHI UPLOAD ====
add_filter('wp_handle_upload', function ($upload) {
    $file_path = $upload['file'];
    io_process_image($file_path);
    return $upload;
});

// ==== XỬ LÝ ẢNH ====
function io_process_image($path) {
    if (!class_exists('Imagick') || !file_exists($path)) return;
    try {
        // Nén ảnh gốc
        $img = new Imagick($path);
        $img->stripImage();
        $img->setImageCompressionQuality(80);
        $img->writeImage($path);
        $img->clear(); $img->destroy();

        // Tạo WebP
        $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
        $img = new Imagick($path);
        $img->setImageFormat('webp');
        $img->setImageCompressionQuality(80);
        $img->writeImage($webp_path);
        $img->clear(); $img->destroy();

        // Tạo AVIF nếu server hỗ trợ
        if (Imagick::queryFormats('AVIF')) {
            $avif_path = preg_replace('/\.(jpe?g|png)$/i', '.avif', $path);
            $img = new Imagick($path);
            $img->setImageFormat('avif');
            $img->setImageCompressionQuality(80);
            $img->writeImage($avif_path);
            $img->clear(); $img->destroy();
        }
    } catch (Exception $e) {
        error_log('[IO] Error processing image: ' . $e->getMessage());
    }
}

// ==== TRANG ADMIN ====
add_action('admin_menu', function () {
    add_menu_page('Image Optimizer', 'Image Optimizer', 'manage_options', 'image-optimizer', 'io_render_admin_page', 'dashicons-format-image', 90);
});

function io_render_admin_page()
{
    $total = (new WP_Query([
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]))->found_posts;

    $optimized = (new WP_Query([
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'meta_key' => '_io_optimized',
        'meta_value' => 1,
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]))->found_posts;

    echo '<div class="wrap"><h1>Image Optimizer</h1>';
    echo "<p><strong>Tổng ảnh:</strong> <span id='io-total'>$total</span></p>";
    echo "<p><strong>Đã tối ưu:</strong> <span id='io-optimized'>$optimized</span></p>";
    echo '<button id="io-start" class="button button-primary">Bắt đầu tối ưu</button>';
    echo '<p id="io-status" style="margin-top: 10px;"></p>';
    echo '</div>';

    ?>
    <script>
    document.getElementById('io-start').addEventListener('click', () => {
        const btn = document.getElementById('io-start');
        const status = document.getElementById('io-status');
        const optimizedEl = document.getElementById('io-optimized');
        btn.disabled = true;
        let page = 1;
        let totalOptimized = parseInt(optimizedEl.textContent);

        function nextPage() {
            fetch(ajaxurl + '?action=io_batch_optimize&page=' + page)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        status.textContent = '❌ Lỗi: ' + data.error;
                        btn.disabled = false;
                        return;
                    }

                    if (data.count === 0 && data.done && page === 1) {
                        status.textContent = '✅ Không có ảnh nào cần tối ưu.';
                        btn.disabled = false;
                        return;
                    }

                    totalOptimized += data.count;
                    optimizedEl.textContent = totalOptimized;
                    status.textContent = `✅ Đã xử lý ${totalOptimized} ảnh...`;

                    if (data.done) {
                        status.textContent += ' Hoàn tất!';
                        btn.disabled = false;
                    } else {
                        page++;
                        setTimeout(nextPage, 300);
                    }
                })
                .catch(err => {
                    status.textContent = '❌ Lỗi kết nối.';
                    btn.disabled = false;
                });
        }

        status.textContent = '🔄 Đang xử lý...';
        nextPage();
    });
    </script>
    <?php
}

// ==== AJAX TỐI ƯU HÀNG LOẠT ====
add_action('wp_ajax_io_batch_optimize', function () {
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

    $images = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => 10,
        'paged' => $page,
        'meta_query' => [
            ['key' => '_io_optimized', 'compare' => 'NOT EXISTS']
        ]
    ]);

    $count = 0;
    foreach ($images as $img) {
        $path = get_attached_file($img->ID);
        if (file_exists($path)) {
            io_process_image($path);
            update_post_meta($img->ID, '_io_optimized', 1);
            $count++;
        }
    }

    wp_send_json([
        'count' => $count,
        'done' => count($images) < 10
    ]);
});
