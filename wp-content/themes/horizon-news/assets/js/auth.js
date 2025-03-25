(function ($) {
    "use strict";
    $(document).ready(function () {
        "use strict";

        const modal = document.getElementById('signin-modal');
        const btn = document.getElementById('signin-button');
        const span = document.getElementsByClassName('close')[0];
        var isLoginMail = false;

        btn.onclick = function (event) {
            event.preventDefault();
            modal.style.display = 'flex';
        }

        span.onclick = function () {
            modal.style.display = 'none';
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        $('.continue-btn').on('click', function () {
            let email = $('#email').val();
            let password = $('#password').val();

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
                        email: email
                    },
                    success: function (response) {
                        if (response.status == 'exists') {
                            $('#password').removeClass('hidden');
                            isLoginMail = true;
                        } else {
                            $('#notification-modal').removeClass('hidden');
                            $('#continue-btn').addClass('hidden');
                        }
                    }
                });
            }
        });
    });
})(jQuery);