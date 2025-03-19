<?php
/*
Template Name: Register Page
*/
get_header();

// Include logic xử lý
require_once get_template_directory() . '/inc/register-handler.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2><?php _e('Đăng ký', 'your-theme'); ?></h2>
        <?php if (!empty($register_error)) : ?>
            <p class="error"><?php echo esc_html($register_error); ?></p>
        <?php endif; ?>
        <form method="post" class="register-form" id="register-form">
            <?php wp_nonce_field('register_action', 'register_nonce'); ?>
            <div class="form-group">
                <input type="text" name="username" id="reg_username" placeholder="<?php _e('Tên đăng nhập', 'horizon-new'); ?>" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" id="reg_email" placeholder="<?php _e('Địa chỉ hòm thư', 'horizon-new'); ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="reg_password" placeholder="<?php _e('Mật khẩu', 'horizon-new'); ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="first_name" id="first_name" placeholder="<?php _e('Tên', 'horizon-new'); ?>">
            </div>
            <div class="form-group">
                <input type="text" name="last_name" id="last_name" placeholder="<?php _e('Họ', 'horizon-new'); ?>">
            </div>
            <button type="submit" name="register"><?php _e('Đăng ký', 'horizon-new'); ?></button>
        </form>
        <p class="auth-links">
            <a href="<?php echo home_url('/dang-ky'); ?>"><?php _e('Bạn đã có tài khoản? Đăng nhập', 'horizon-new'); ?> →</a>
        </p>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#register-form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $error = $form.find('.error');
            var $success = $form.find('.success');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: $form.serialize() + '&action=custom_register',
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.data.redirect;
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