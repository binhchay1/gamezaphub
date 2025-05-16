<?php
/*
Template Name: Profile
*/
get_header();

// Xử lý cập nhật thông tin người dùng
if (!is_user_logged_in()) {
    wp_redirect(home_url('/dang-nhap'));
    exit;
}

$current_user = wp_get_current_user();
$update_success = '';
$update_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile']) && wp_verify_nonce($_POST['profile_nonce'], 'update_profile_action')) {
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $new_password = $_POST['new_password'];

    // Cập nhật thông tin
    $user_data = array(
        'ID'           => $current_user->ID,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'user_email'   => $email,
    );

    $user_id = wp_update_user($user_data);

    if (is_wp_error($user_id)) {
        $update_error = $user_id->get_error_message();
    } else {
        // Cập nhật mật khẩu nếu có
        if (!empty($new_password)) {
            wp_set_password($new_password, $current_user->ID);
            // Đăng xuất người dùng sau khi đổi mật khẩu để yêu cầu đăng nhập lại
            wp_logout();
            wp_redirect(home_url('/dang-nhap?password_updated=1'));
            exit;
        }
        $update_success = 'Thông tin của bạn đã được cập nhật thành công.';
    }
}
?>

<div class="profile-container">
    <div class="profile-form">
        <h2>Thông Tin Cá Nhân</h2>
        <?php if (!empty($update_success)) : ?>
            <p class="success"><?php echo esc_html($update_success); ?></p>
        <?php endif; ?>
        <?php if (!empty($update_error)) : ?>
            <p class="error"><?php echo esc_html($update_error); ?></p>
        <?php endif; ?>
        <form method="post" class="profile-form" id="profile-form">
            <?php wp_nonce_field('update_profile_action', 'profile_nonce'); ?>
            <div class="form-group">
                <label for="first_name">Họ</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Tên</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo esc_attr($current_user->user_email); ?>" required>
            </div>
            <div class="form-group">
                <label for="new_password">Mật Khẩu Mới (để trống nếu không muốn thay đổi)</label>
                <input type="password" name="new_password" id="new_password" placeholder="Nhập mật khẩu mới">
            </div>
            <button type="submit" name="update_profile">Cập Nhật</button>
        </form>
    </div>
</div>

<style>
    .profile-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .profile-form {
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
    }

    .profile-form h2 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 30px;
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        background: #f5f5f5;
        outline: none;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        border-color: #9d50bb;
    }

    .profile-form button {
        width: 100%;
        padding: 12px;
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s;
    }

    .profile-form button:hover {
        background: #218838;
    }

    .error,
    .success {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        text-align: center;
    }

    .error {
        background: #f8d7da;
        color: #721c24;
    }

    .success {
        background: #d4edda;
        color: #155724;
    }

    .right-sidebar .ascendoor-wrapper .ascendoor-page {
        display: inherit;
    }
</style>

<?php get_footer(); ?>