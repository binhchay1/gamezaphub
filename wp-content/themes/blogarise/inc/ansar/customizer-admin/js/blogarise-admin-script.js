(function ($) {
  $(document).ready(function () {
    $('.blogarise-btn-get-started').on('click', function (e) {
      e.preventDefault();
      if (!blogarise_ajax_object.can_install) {
        alert('Sorry, you are not allowed to access this page.');
        return;
      }
      var $btn = $(this);
      $btn.html('Processing.. Please wait').addClass('updating-message');
      $.post(blogarise_ajax_object.ajax_url, {
        action: 'install_act_plugin',
        security: blogarise_ajax_object.install_nonce
      }, function (response) {
        if (response.success) {
          window.location.href = 'admin.php?page=ansar-demo-import';
        } else {
          alert(response.data?.message || 'Installation failed.');
          $btn.html('Try Again').removeClass('updating-message');
        }
      }).fail(function () {
        alert('Something went wrong. Please try again.');
        $btn.html('Try Again').removeClass('updating-message');
      });
    });
  });
$(document).on('click', '.notice-get-started-class .notice-dismiss', function () {
  var type = $(this).closest('.notice-get-started-class').data('notice');
  $.post(ajaxurl, {
    action: 'blogarise_dismissed_notice_handler',
    type: type,
  });
});
})(jQuery);