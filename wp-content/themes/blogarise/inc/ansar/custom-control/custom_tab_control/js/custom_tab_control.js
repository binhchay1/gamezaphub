jQuery(document).ready(function ($) {
  "use strict";

  $('.customize-control-custom-tab-control').each(function () {
    $(this).parent().find('li').not('.section-meta').not('.customize-control-custom-tab-control').addClass('custom-hide-control');
    var generals = $(this).find('.control-tab-general').data('connected');
    $.each(generals, function (i, v) {
      $(this).removeClass('custom-hide-control'); //show
    });
    $(this).find('.control-tab').on('click', function () {
      var visibles = $(this).data('connected');
      $(this).addClass('active');
      $(this).siblings().removeClass('active');
      $(this).parent().parent().parent().find('li').not('.section-meta').not('.customize-control-custom-tab-control').addClass('custom-hide-control');
      $.each(visibles, function (i, v) {
        $(this).removeClass('custom-hide-control'); //show
      });
    });
  });
});