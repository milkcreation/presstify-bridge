"use strict";

jQuery(document).ready(function ($) {
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

            let $Sidebar = $($(this).data('toggle'));

            if ($Sidebar.attr('aria-closed') === 'true') {
                $Sidebar.attr('aria-closed', 'false');
            } else {
                $Sidebar.attr('aria-closed', 'true');
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