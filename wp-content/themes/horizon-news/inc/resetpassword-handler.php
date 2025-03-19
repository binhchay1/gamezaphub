<?php
$resetpassword_error = '';
$resetpassword_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['resetpassword']) && wp_verify_nonce($_POST['resetpassword_nonce'], 'resetpassword_action')) {
        $key = sanitize_text_field($_POST['key']);
        $login = sanitize_text_field($_POST['login']);
        $new_password = $_POST['new_password'];

        $user = check_password_reset_key($key, $login);
        if (is_wp_error($user)) {
            $resetpassword_error = $user->get_error_message();
        } else {
            reset_password($user, $new_password);
            $resetpassword_success = __('Mật khẩu của bạn đã được đặt lại. Vui lòng đăng nhập.', 'your-theme');
            wp_redirect(home_url('/dang-nhap?password_reset=1'));
            exit;
        }
    }
}
?>