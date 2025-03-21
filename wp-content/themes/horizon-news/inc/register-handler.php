<?php
$register_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register']) && wp_verify_nonce($_POST['register_nonce'], 'register_action')) {
        $user_data = array(
            'user_login' => sanitize_text_field($_POST['username']),
            'user_email' => sanitize_email($_POST['email']),
            'user_pass'  => $_POST['password'],
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name'  => sanitize_text_field($_POST['last_name']),
            'role'       => 'subscriber',
        );
        $user_id = wp_insert_user($user_data);
        if (is_wp_error($user_id)) {
            $register_error = $user_id->get_error_message();
        } else {
            wp_new_user_notification($user_id, null, 'both');
            wp_redirect(home_url('/login?registered=1'));
            exit;
        }
    }
}
?>
