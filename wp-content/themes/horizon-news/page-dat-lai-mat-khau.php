<?php
/*
Template Name: Reset Password Page
*/
get_header();

// Include logic xử lý
require_once get_template_directory() . '/inc/resetpassword-handler.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2><?php _e('Set New Password', 'your-theme'); ?></h2>
        <?php if (!empty($resetpassword_error)) : ?>
            <p class="error"><?php echo esc_html($resetpassword_error); ?></p>
        <?php endif; ?>
        <?php if (!empty($resetpassword_success)) : ?>
            <p class="success"><?php echo esc_html($resetpassword_success); ?></p>
        <?php endif; ?>
        <form method="post" class="resetpassword-form" id="resetpassword-form">
            <?php wp_nonce_field('resetpassword_action', 'resetpassword_nonce'); ?>
            <input type="hidden" name="key" value="<?php echo esc_attr($_GET['key'] ?? ''); ?>">
            <input type="hidden" name="login" value="<?php echo esc_attr($_GET['login'] ?? ''); ?>">
            <div class="form-group">
                <input type="password" name="new_password" id="new_password" placeholder="<?php _e('New Password', 'your-theme'); ?>" required>
            </div>
            <button type="submit" name="resetpassword"><?php _e('RESET PASSWORD', 'your-theme'); ?></button>
        </form>
        <p class="auth-links">
            <a href="<?php echo home_url('/login'); ?>"><?php _e('Back to Login', 'your-theme'); ?> →</a>
        </p>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#resetpassword-form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $error = $form.find('.error');
            var $success = $form.find('.success');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: $form.serialize() + '&action=custom_reset_password',
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