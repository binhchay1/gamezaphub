(function ($) {
    "use strict";
    $(document).ready(function () {
        "use strict";

        const modal = document.getElementById('signin-modal');
        const btn = document.getElementById('signin-button');
        const close = document.getElementsByClassName('close')[0];
        var isLoginMail = false;

        if (btn) {
            btn.onclick = function (event) {
                event.preventDefault();
                if (modal) {
                    modal.style.display = 'flex';
                }

            }
        }

        if (close) {
            close.onclick = function () {
                if (modal) {
                    modal.style.display = 'none';
                }
            }
        }

        if (modal) {
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        }

        $('.continue-btn').on('click', function () {
            let email = $('#email').val();
            let password = $('#password').val();
            $('#error-area').hide();

            if (!email || !isValidEmail(email)) {
                $('#error-area').text('Hòm thư không hợp lệ');
                $('#error-area').show();
                return;
            }

            if (isLoginMail == true) {
                $.ajax({
                    url: ajax_url_admin.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'login_handle',
                        nonce: ajax_url_admin.nonce,
                        email: email,
                        password: password
                    },
                    success: function (response) {

                    }
                });
            } else {
                $.ajax({
                    url: ajax_url_admin.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'check_email',
                        email: email,
                        nonce: ajax_url_admin.nonce
                    },
                    success: function (response) {
                        if (response.status == 'exists') {
                            $('#password').removeClass('hidden');
                            isLoginMail = true;
                        } else {
                            $('#notification-modal').removeClass('hidden');
                            $('#continue-btn').addClass('hidden');

                            $.ajax({
                                url: ajax_url_admin.ajax_url,
                                type: 'POST',
                                data: {
                                    action: 'send_verification_email',
                                    email: email,
                                    nonce: ajax_url_admin.nonce
                                },
                                success: function () {

                                }
                            });
                        }
                    }
                });
            }
        });
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
})(jQuery);