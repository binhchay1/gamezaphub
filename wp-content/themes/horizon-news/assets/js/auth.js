(function ($) {
    "use strict";
    $(document).ready(function () {
        "use strict";

        const modal = document.getElementById('signin-modal');
        const btn = document.getElementById('signin-button');
        const span = document.getElementsByClassName('close')[0];

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

            $.ajax({
                url: ajax_url_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'check_email',
                    email: email
                },
                success: function (response) {
                    if(response.status == 'none') {

                    } else {

                    }
                }
            });
        });
    });
})(jQuery);