'use strict';

import jQuery from 'jquery';

jQuery(function ($) {
  $('body').attr('data-sidebar', true);

  $(window).resize(function () {
    $('[data-control="sidebar"]').each(function () {
      let headerHeight = $('[data-control="sidebar.header"]', $(this)).height(),
          footerHeight = $('[data-control="sidebar.footer"]', $(this)).height(),
          sidebarHeight = $(this).height();

      $('[data-control="sidebar.body"]').height(sidebarHeight - (headerHeight + footerHeight));
    });
  }).trigger('resize');

  $(document)
      .on('click', '[data-control="sidebar.toggle"]', function (e) {
        e.preventDefault();

        let $sidebar,
            target = $(this).data('target');

        if ($(target).length) {
          $sidebar = $(target);
        } else if ($(this).closest('[data-control="sidebar"]').length) {
          $sidebar = $(this).closest('[data-control="sidebar"]');
        }

        if ($sidebar.length) {
          if ($sidebar.attr('aria-closed') === 'true') {
            $sidebar.attr('aria-closed', 'false');
          } else {
            $sidebar.attr('aria-closed', 'true');
          }
        }
      })
      .on('click', function (e) {
        if (
            !$(e.target).closest('[data-control="sidebar"][aria-closed="false"]').length &&
            !$(e.target).closest('[data-control="sidebar.toggle"]').length
        ) {
          $('[data-control="sidebar"][aria-closed="false"][aria-outside_close="true"]').each(function () {
            $(this).attr('aria-closed', 'true');
          });
        }
        return true;
      });
});