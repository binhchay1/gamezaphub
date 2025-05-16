<?php

function custom_email_content_type()
{
    return 'text/html';
}
add_filter('wp_mail_content_type', 'custom_email_content_type');

add_action('wp_ajax_send_verification_email', 'send_verification_email_callback');
add_action('wp_ajax_nopriv_send_verification_email', 'send_verification_email_callback');

function send_verification_email_callback()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'auth_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
        wp_die();
    }

    $email = sanitize_email($_POST['email']);

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email']);
        wp_die();
    }

    $token = wp_generate_password(20, false);
    $verification_link = add_query_arg(
        ['email' => $email, 'token' => $token],
        home_url('/verify-email/')
    );

    set_transient('email_verification_' . md5($email), $token, 15 * MINUTE_IN_SECONDS);

    $subject = 'Xác thực email của bạn';
    $message = custom_email_template($email, $verification_link);
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    $sent = wp_mail($email, $subject, $message, $headers);

    if ($sent) {
        wp_send_json_success(['message' => 'Verification email sent']);
    } else {
        wp_send_json_error(['message' => 'Failed to send email']);
    }

    wp_die();
}

function custom_email_template($email, $verification_link)
{
    ob_start();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Xác thực email</title>
    </head>

    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
            <h2 style="color: #0073aa;">Xác thực email của bạn</h2>
            <p>Xin chào,</p>
            <p>Chúng tôi nhận được yêu cầu đăng ký với email <strong><?php echo esc_html($email); ?></strong>. Vui lòng nhấp vào liên kết bên dưới để xác thực:</p>
            <p><a href="<?php echo esc_url($verification_link); ?>" style="display: inline-block; padding: 10px 20px; background-color: #0073aa; color: #fff; text-decoration: none; border-radius: 5px;">Xác thực ngay</a></p>
            <p>Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này.</p>
            <p>Trân trọng,<br>Đội ngũ [Tên website của bạn]</p>
        </div>
    </body>

    </html>
<?php
    return ob_get_clean();
}

add_action('template_redirect', 'handle_email_verification');
function handle_email_verification()
{
    if (is_page('verify-email') && isset($_GET['email']) && isset($_GET['token'])) {
        $email = sanitize_email($_GET['email']);
        $token = sanitize_text_field($_GET['token']);
        $stored_token = get_transient('email_verification_' . md5($email));

        if ($stored_token && $stored_token === $token) {
            delete_transient('email_verification_' . md5($email));
            wp_redirect(home_url('/verification-success/'));
            exit;
        } else {
            wp_redirect(home_url('/verification-failed/'));
            exit;
        }
    }
}
