/* global tify, tify_taboox_image_gallery, wp */

"use strict";

let tify_taboox_image_gallery_frame;

jQuery(document).ready(function ($) {
    $('.tiFyTabMetaboxPostTypeImageGallery-add').on('click', function (e) {
        e.preventDefault();

        let $list = $(this).prev(),
            name = $(this).data('name'),
            max = $(this).data('max');

        if (max > 0 && $('li', $list).length >= max) {
            alert(tify_taboox_image_gallery.maxAttempt);
            return false;
        }

        tify_taboox_image_gallery_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('media_title'),
            editing: true,
            button: {
                text: $(this).data('media_button_text'),
            },
            multiple: true,
            library: {
                type: 'image' // ['image/gif','image/png']
            }
        });

        tify_taboox_image_gallery_frame.on('select', function () {
            let selection = tify_taboox_image_gallery_frame.state().get('selection');
            selection.map(function (attachment) {
                let order = $('li', $list).length + 1;

                $.post(
                    tify.ajax_url,
                    {
                        action: 'tify_tab_metabox_post_type_image_gallery_add_item',
                        id: attachment.id,
                        name: name,
                        order: order
                    }
                ).done(function (resp) {
                    $list.append(resp);
                    orderRefresh($list);
                });
            });
        });
        tify_taboox_image_gallery_frame.open();
    });

    $('.tiFyTabMetaboxPostTypeImageGallery-items').sortable({
        placeholder: "ui-sortable-placeholder",
        containment: "parent",
        update: function () {
            orderRefresh($(this));
        }
    });
    $('.taboox_image_gallery-list').disableSelection();

    $(document).on('click', '.tiFyTabMetaboxPostTypeImageGallery-itemRemove', function () {
        let $container = $(this).parent(),
            $list = $container.parent();

        $container.fadeOut(function () {
            $container.remove();
            orderRefresh($list);
        });
    });

    function orderRefresh($list) {
        $('.tiFyTabMetaboxPostTypeImageGallery-itemOrder', $list).each(function () {
            $(this).val($(this).closest('.tiFyTabMetaboxPostTypeImageGallery-item').index() + 1);
        });
    }
});
