/* global jQuery */
"use strict";

jQuery(document).ready(function ($) {
  $(document).on('click', '[data-toggle="notice.trigger"]', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let $self = $(this),
        $target = $self.data('target') ? $($self.data('target')) : $self.closest('[data-control="notice"]');

    if ($target.length) {
      let o = JSON.parse(decodeURIComponent($target.data('options'))) || {};

      if (o.ajax) {
        $target.attr('aria-loading', 'true');
        $.ajax(o.ajax)
            .done(function (resp) {
              if (resp.success) {
                $target.attr('aria-loading', 'false');
                $target.attr('aria-fade', 'out');
                $self.trigger('cookie-notice:done');
              }
            })
            .always(function () {
              $self.trigger('cookie-notice:always');
            });
      }
    }
  });
});