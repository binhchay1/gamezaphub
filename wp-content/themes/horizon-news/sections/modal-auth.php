<div id="signin-modal" class="modal">
    <div class="modal-content">
        <span class="close">×</span>
        <h2>Đăng nhập - Tạo tài khoản</h2>
        <p class="modal-subtitle">
            <span class="checkmark">✔</span> Tham gia cùng chúng tôi để trải nghiệm nhiều tính năng sẽ được cập nhật trong tương lai
        </p>
        <form id="signin-form">
            <label for="email">Hòm thư</label>
            <input type="email" id="email" name="email" autocomplete="email" placeholder="Hòm thư">
            <input type="password" id="password" name="password" autocomplete="password" placeholder="Mật khẩu" class="hidden">

            <p id="notification-modal" class="hidden">Một thư kích hoạt đã được gửi đến hòm thư <span></span>. Vui lòng làm theo hướng dẫn trong hòm thư để hoàn tất việc đăng ký</p>

            <button type="button" class="continue-btn">Tiếp tục</button>
            <p class="terms">
                Bằng cách tiếp tục, bạn hướng dẫn chúng tôi chia sẻ địa chỉ email của bạn với GameZapHub. Thông tin của bạn sẽ được sử dụng cho các quảng cáo được cá nhân hóa và bạn đồng ý với <a href="/privacy-policy">Chính sách bảo mật</a> và <a href="/dieu-khoan-dich-vu">Điều khoản dịch vụ</a> của GameZapHub.
            </p>
            <div class="divider">hoặc</div>
            <a href="<?php echo esc_url(google_login_url()); ?>" class="button-signin-social">
                <img src="<?php echo get_template_directory_uri() . '/assets/img/favicon-gg.ico'; ?>" alt="Google Icon" class="google-icon">Đăng nhập với Google
            </a>
        </form>
    </div>
</div>