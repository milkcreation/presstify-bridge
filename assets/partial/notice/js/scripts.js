"use strict";

jQuery(function ($) {
  $(document).ready(function ($) {
    $(document).on('click', '[data-control="notice"] [data-toggle="notice.dismiss"]', function (e) {
      e.preventDefault();

      $(this).closest('[data-control="notice"]').attr('aria-hide', 'true');
    });

    $('[data-control="notice"][data-timeout]').each(function () {
      let $el = $(this),
          time = parseInt($el.data('timeout')) || 0;

      if (time !== 0) {
        setTimeout(function () {
          $el.attr('aria-hide', 'true');
        }, time);
      }
    });

    $(document).tifyObserver({
      selector: '[data-control="notice"]',
      func: function (i, target) {
        let $el = $(target),
            time = parseInt($el.data('timeout')) || 0;

        if (time !== 0) {
          setTimeout(function () {
            $el.attr('aria-hide', 'true');
          }, time);
        }
      }
    });
  });
});