<?php
/*
Template Name: Login Page
*/
get_header();

// Include logic xử lý
require_once get_template_directory() . '/inc/login-handler.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2><?php _e('Member Login', 'your-theme'); ?></h2>
        <?php if (!empty($login_error)) : ?>
            <p class="error"><?php echo esc_html($login_error); ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['loggedout'])) : ?>
            <p class="success"><?php _e('You are now logged out.', 'your-theme'); ?></p>
        <?php endif; ?>
        <form method="post" class="login-form" id="login-form">
            <?php wp_nonce_field('login_action', 'login_nonce'); ?>
            <div class="form-group">
                <input type="text" name="username" id="username" placeholder="<?php _e('Email', 'your-theme'); ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="<?php _e('Password', 'your-theme'); ?>" required>
            </div>
            <button type="submit" name="login"><?php _e('LOGIN', 'your-theme'); ?></button>
        </form>
        <p class="auth-links">
            <a href="<?php echo home_url('/lostpassword'); ?>"><?php _e('Forget Username/Password?', 'your-theme'); ?></a>
        </p>
        <p class="auth-links">
            <a href="<?php echo home_url('/register'); ?>"><?php _e('Create your Account', 'your-theme'); ?> →</a>
        </p>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#login-form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $error = $form.find('.error');
            var $success = $form.find('.success');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: $form.serialize() + '&action=custom_login',
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