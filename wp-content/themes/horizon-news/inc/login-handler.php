<?php
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login']) && wp_verify_nonce($_POST['login_nonce'], 'login_action')) {
        $creds = array(
            'user_login'    => sanitize_text_field($_POST['username']),
            'user_password' => $_POST['password'],
            'remember'      => true,
        );
        $user = wp_signon($creds, false);
        if (is_wp_error($user)) {
            $login_error = $user->get_error_message();
        } else {
            $user_data = get_userdata($user->ID);
            $user_roles = $user_data->roles;
            $redirect = in_array('administrator', $user_roles) ? admin_url() : (wp_get_referer() ? wp_get_referer() : home_url('/'));
            wp_redirect($redirect);
            exit;
        }
    }
}
?>