<?php
/*
Template Name: Lost Password Page
*/
get_header();

// Include logic xử lý
require_once get_template_directory() . '/inc/lostpassword-handler.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2><?php _e('Quên mật khẩu', 'horizon-new'); ?></h2>
        <?php if (!empty($lostpassword_error)) : ?>
            <p class="error"><?php echo esc_html($lostpassword_error); ?></p>
        <?php endif; ?>
        <?php if (!empty($lostpassword_success)) : ?>
            <p class="success"><?php echo esc_html($lostpassword_success); ?></p>
        <?php endif; ?>
        <form method="post" class="lostpassword-form" id="lostpassword-form">
            <?php wp_nonce_field('lostpassword_action', 'lostpassword_nonce'); ?>
            <div class="form-group">
                <input type="text" name="user_login" id="user_login" placeholder="<?php _e('Tên đăng nhập hoặc địa chỉ hòm thư', 'horizon-new'); ?>" required>
            </div>
            <button type="submit" name="lostpassword"><?php _e('GỬI YÊU CẦU', 'horizon-new'); ?></button>
        </form>
        <p class="auth-links">
            <a href="<?php echo home_url('/dang-ky'); ?>"><?php _e('Quay lại đăng ký', 'horizon-new'); ?> →</a>
        </p>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#lostpassword-form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $error = $form.find('.error');
            var $success = $form.find('.success');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: $form.serialize() + '&action=custom_lostpassword',
                success: function(response) {
                    if (response.success) {
                        $error.remove();
                        $success.remove();
                        $form.prepend('<p class="success">' + response.data.message + '</p>');
                    } else {
                        $error.remove();
                        $success.remove();
                        $form.prepend('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    $error.remove();
                    $success.remove();
                    $form.prepend('<p class="error">Đã có lỗi xảy ra. Vui lòng thử lại.</p>');
                }
            });
        });
    });
</script>

<?php
wp_enqueue_style('auth-style', get_template_directory_uri() . '/assets/css/auth.css', array(), '1.0');
get_footer();
?>