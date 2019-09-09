/* globals tify, tinymce, MetaboxOptionsSlideshowAdmin */

"use strict";

/**
 * @param {{ajax_url:string}} tify
 */
jQuery(document).ready(function ($) {
    $(document).click('.tinymce', function() {
        tinymce.init({
            selector: '.tinymce',
            inline: true,
            toolbar: "bold italic",
            menubar: false
        });
    }).trigger('click');

    let getItem = function ($container) {
        let data = $.parseJSON(decodeURIComponent($container.data('options'))),
            count = $('.MetaboxOptions-slideshowListItem', $container).length;

        if ((data.max > 0) && (count === data.max)) {
            alert(MetaboxOptionsSlideshowAdmin.l10nMax);
            return false;
        }

        $('.MetaboxOptions-slideshowListOverlay', $container).show();

        $.post(tify.ajax_url, data)
            .done(function (resp) {
                let $item = $(resp).prependTo($('.MetaboxOptions-slideshowListItems', $container));

                initItem($item);
                orderItem($container);
                $(document).trigger('metabox_options_slideshow_item_loaded', $item);
            })
            .always(function () {
                $('.MetaboxOptions-slideshowListOverlay', $container).hide();
            });

        return false;
    };

    let initItem = function ($item) {
        $('[data-hide_unchecked]', $item).not(':checked').each(function () {
            var target = $(this).data('hide_unchecked');
            $(this).closest('.MetaboxOptions-slideshowListItemInputs').find(target).each(function () {
                $(this).hide();
            });
        });
    };

    let orderItem = function ($container) {
        $('.MetaboxOptions-slideshowListItem', $container).each(function () {
            $(this).find('.MetaboxOptions-slideshowListItemHelper--order > input').val(parseInt($(this).index() + 1));
        });
    };

    $('.MetaboxOptions-slideshowListItem').each(function () {
        initItem($(this));
    });

    $(document).on('change', '.MetaboxOptions-slideshowListItemInputs [data-hide_unchecked]', function () {
        var target = $(this).data('hide_unchecked');
        if ($(this).is(':checked')) {
            $(this).closest('.MetaboxOptions-slideshowListItemInputs').find(target).each(function () {
                $(this).show();
            });
        } else {
            $(this).closest('.MetaboxOptions-slideshowListItemInputs').find(target).each(function () {
                $(this).hide();
            });
        }
    });

    $(document).on('click', '.MetaboxOptions-slideshowListItemHelper--remove', function (e) {
        e.preventDefault();
        var $container = $(this).closest('.MetaboxOptions-slideshowListItem');

        $container.fadeOut(function () {
            $container.remove();
            orderItem();
        });
    });

    $('.MetaboxOptions-slideshowSelector--custom').click(function (e) {
        e.preventDefault();

        getItem($(this).closest('.MetaboxOptions-slideshow'));
    });

    $('.MetaboxOptions-slideshowListItems').sortable({
        axis: "y",
        update: function () {
            var container = $(this).closest('.MetaboxOptions-slideshow');
            orderItem(container);
        },
        handle: ".MetaboxOptions-slideshowListItemHelper--sort"
    });
});