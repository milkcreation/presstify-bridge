/* global wp */

"use strict";

let tify_control_media_image_frame;

jQuery(document).ready(function ($) {
    $(document).on('click', '.tiFyField-mediaImageAdd', function (e) {
        e.preventDefault();

        let $this = $(this),
            $closest = $this.closest('.tiFyField-mediaImage'),
            title = $(this).data('media_library_title'),
            button = $(this).data('media_library_button');

        tify_control_media_image_frame = wp.media.frames.file_frame = wp.media({
            title: title,
            editing: true,
            button: {
                text: button,
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });

        tify_control_media_image_frame.on('select', function () {
            let attachment = tify_control_media_image_frame.state().get('selection').first().toJSON();

            $this.css('background-image', 'url(' + attachment.url + '');
            $('.tiFyField-mediaImageInput', $closest).val(attachment.id);
            $('.tiFyField-mediaImageReset:hidden', $closest).fadeIn();
            $closest.addClass('tiFyField-mediaImage--selected');
        });

        tify_control_media_image_frame.open();
    });

    $(document).on('click', '.tiFyField-mediaImageRemove', function (e) {
        e.preventDefault();

        let $this = $(this),
            $closest = $this.closest('.tiFyField-mediaImage');

        $closest.removeClass('tiFyField-mediaImage--selected');
        $('.tiFyField-mediaImageInput', $closest).val('');
        $('.tiFyField-mediaImageAdd', $closest).css('background-image', 'url(' + $('.tiFyField-mediaImage', $closest).data('default') + ')');
    });
});