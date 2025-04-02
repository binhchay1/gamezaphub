<?php

/**
 * Require Google Package.
 */
require_once get_template_directory() . '/vendor/autoload.php';

function initialize_custom_session()
{
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'initialize_custom_session');

function check_email()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'auth_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
        wp_die();
    }

    global $wpdb;
    $email = sanitize_email($_POST['email']);
    $table_name = $wpdb->prefix . 'custom_users';
    $response = [
        'status' => '',
        'code' => 200
    ];

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE email = %s",
        $email
    ));

    if ($exists == 0) {
        $response['status'] = 'exists';
    } else {
        $response['status'] = 'none';
    }

    wp_send_json_success($response);
}
add_action('wp_ajax_check_email', 'check_email');
add_action('wp_ajax_nopriv_check_email', 'check_email');

function custom_user_ajax_login()
{
    global $wpdb;

    check_ajax_referer('auth_nonce', 'nonce');

    $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập đầy đủ thông tin.'));
    }

    $table_name = $wpdb->prefix . 'custom_users';
    $user = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE username = %s",
            $username
        )
    );

    if ($user && password_verify($password, $user->password)) {
        session_start();
        $_SESSION['custom_user'] = array(
            'id' => $user->id,
            'username' => $user->username,
            'logged_in' => true
        );

        wp_send_json_success(array(
            'message' => 'Đăng nhập thành công!',
            'redirect' => home_url()
        ));
    } else {
        wp_send_json_error(array('message' => 'Tên đăng nhập hoặc mật khẩu không đúng.'));
    }
}
add_action('wp_ajax_custom_user_login', 'custom_user_ajax_login');
add_action('wp_ajax_nopriv_custom_user_login', 'custom_user_ajax_login');

function is_custom_user_logged_in()
{
    if (!session_id()) {
        session_start();
    }
    
    return isset($_SESSION['custom_user']) && $_SESSION['custom_user']['logged_in'] === true;
}

function get_custom_user()
{
    return isset($_SESSION['custom_user']) ? $_SESSION['custom_user'] : null;
}

function restrict_admin_for_custom_users()
{
    if (is_admin() && !current_user_can('administrator') && !wp_doing_ajax()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'restrict_admin_for_custom_users');

function custom_user_logout()
{
    if (isset($_GET['custom_logout'])) {
        unset($_SESSION['custom_user']);
        wp_redirect(home_url());
        exit;
    }
}
add_action('init', 'custom_user_logout');

function get_google_client()
{
    try {
        $client = new Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(home_url('/google-callback'));
        $client->addScope('email');
        $client->addScope('profile');
        return $client;
    } catch (Exception $e) {
        error_log('Google Client Error: ' . $e->getMessage());
        return null;
    }
}

function google_login_url()
{
    $client = get_google_client();
    if ($client) {
        return $client->createAuthUrl();
    }
    return '#';
}

function handle_google_callback()
{
    global $wpdb;

    if (!session_id()) {
        session_start();
    }

    if (isset($_GET['code']) && strpos($_SERVER['REQUEST_URI'], '/google-callback') !== false) {
        $client = get_google_client();

        $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $access_token = $client->getAccessToken();

        if ($access_token) {
            $oauth_service = new Google_Service_Oauth2($client);
            $user_info = $oauth_service->userinfo->get();

            $email = $user_info->email;
            $username = $user_info->givenName ?: explode('@', $email)[0];

            $table_name = $wpdb->prefix . 'custom_users';
            $existing_user = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM $table_name WHERE email = %s", $email)
            );

            if ($existing_user) {
                $_SESSION['custom_user'] = array(
                    'id' => $existing_user->id,
                    'username' => $existing_user->username,
                    'email' => $existing_user->email,
                    'logged_in' => true
                );
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'name' => $username,
                        'email' => $email,
                        'password' => ''
                    )
                );
                $user_id = $wpdb->insert_id;

                $_SESSION['custom_user'] = array(
                    'id' => $user_id,
                    'username' => $username,
                    'email' => $email,
                    'logged_in' => true
                );
            }

            error_log("Session sau khi gán: " . print_r($_SESSION, true));
            session_write_close();
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action('template_redirect', 'handle_google_callback');
