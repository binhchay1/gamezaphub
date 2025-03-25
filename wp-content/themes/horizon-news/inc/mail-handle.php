<?php

function custom_email_content_type()
{
    return 'text/html';
}
add_filter('wp_mail_content_type', 'custom_email_content_type');

function get_custom_email_template($username = '', $verification_link = '')
{
    ob_start();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Xác thực tài khoản</title>
    </head>

    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee;">
            <h2 style="color: #0073aa;">Chào mừng bạn đến với Gamzaphub.com</h2>
            <p>Xin chào <?php echo esc_html($username); ?>,</p>
            <p>Cảm ơn bạn đã đăng ký! Vui lòng nhấn vào nút dưới đây để xác thực email của bạn:</p>
            <p style="text-align: center;">
                <a href="<?php echo esc_url($verification_link); ?>"
                    style="display: inline-block; padding: 10px 20px; background-color: #0073aa; color: #fff; text-decoration: none; border-radius: 5px;">
                    Xác thực ngay
                </a>
            </p>
            <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
            <p>Trân trọng,<br>Đội ngũ Gamzaphub.com</p>
        </div>
    </body>

    </html>
<?php
    return ob_get_clean();
}

function send_verification_email($email, $username, $token)
{
    $verification_link = home_url("/verify-email?token=$token");
    $subject = 'Xác thực email của bạn - [Tên Website]';
    $message = get_custom_email_template($username, $verification_link);

    $sent = wp_mail($email, $subject, $message);
    return $sent;
}

function handle_email_verification()
{
    global $wpdb;

    if (isset($_GET['token'])) {
        $token = sanitize_text_field($_GET['token']);
        $table_name = $wpdb->prefix . 'email_verifications';

        $verification = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE token = %s",
                $token
            )
        );

        if ($verification) {
            $users_table = $wpdb->prefix . 'custom_users';
            $email_exists = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM $users_table WHERE email = %s",
                    $verification->email
                )
            );

            if (!$email_exists) {
                $username = explode('@', $verification->email)[0];
                $password = wp_generate_password();
                $wpdb->insert(
                    $users_table,
                    array(
                        'username' => $username,
                        'email' => $verification->email,
                        'password' => password_hash($password, PASSWORD_DEFAULT)
                    )
                );
            }

            $wpdb->delete($table_name, array('token' => $token));

            wp_redirect(home_url('/custom-login?verified=1'));
            exit;
        } else {
            wp_redirect(home_url('/custom-login?verified=0'));
            exit;
        }
    }
}
add_action('template_redirect', 'handle_email_verification');
