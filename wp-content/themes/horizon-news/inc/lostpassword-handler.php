<?php
$lostpassword_error = '';
$lostpassword_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lostpassword']) && wp_verify_nonce($_POST['lostpassword_nonce'], 'lostpassword_action')) {
        $user_login = sanitize_text_field($_POST['user_login']);
        $user = get_user_by('login', $user_login);
        if (!$user) {
            $user = get_user_by('email', $user_login);
        }

        if (!$user) {
            $lostpassword_error = __('Tên đăng nhập hoặc email không tồn tại.', 'your-theme');
        } else {
            $reset_key = get_password_reset_key($user);
            if (is_wp_error($reset_key)) {
                $lostpassword_error = __('Không thể tạo liên kết khôi phục. Vui lòng thử lại.', 'your-theme');
            } else {
                $reset_url = add_query_arg(
                    array(
                        'key' => $reset_key,
                        'login' => rawurlencode($user->user_login),
                    ),
                    home_url('/resetpassword')
                );

                $message = __('Ai đó đã yêu cầu đặt lại mật khẩu cho tài khoản của bạn:', 'your-theme') . "\r\n\r\n";
                $message .= sprintf(__('Tên đăng nhập: %s', 'your-theme'), $user->user_login) . "\r\n\r\n";
                $message .= __('Để đặt lại mật khẩu, hãy nhấp vào liên kết sau:', 'your-theme') . "\r\n\r\n";
                $message .= $reset_url . "\r\n\r\n";
                $message .= __('Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này.', 'your-theme');

                $subject = sprintf(__('[%s] Đặt lại mật khẩu', 'your-theme'), get_bloginfo('name'));
                if (wp_mail($user->user_email, $subject, $message)) {
                    $lostpassword_success = __('Liên kết khôi phục đã được gửi đến email của bạn.', 'your-theme');
                } else {
                    $lostpassword_error = __('Không thể gửi email. Vui lòng thử lại.', 'your-theme');
                }
            }
        }
    }
}
?>
