/* global wp */

"use strict";

let tiFyFieldMediaFileFrame;

jQuery(document).ready(function ($) {
    $(document).on('click', '[data-control="media_file"]', function (e) {
        e.preventDefault();

        let $closest = $(this),
            o = $.parseJSON(decodeURIComponent($(this).data('options')));

        tiFyFieldMediaFileFrame = wp.media.frames.file_frame = wp.media(o);

        tiFyFieldMediaFileFrame.on('select', function () {
            let attachment = tiFyFieldMediaFileFrame.state().get('selection').first().toJSON();
            $closest.attr('aria-active', 'true');
            $('[data-control="infos"]', $closest).val(attachment.title + ' → ' + attachment.filename);
            $('[data-control="input"]', $closest).val(attachment.id);
        });

        tiFyFieldMediaFileFrame.open();
    });

    $(document).on('click', '[data-control="media_file"] [data-control="reset"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let $closest = $(this).parent();

        $closest.attr('aria-active', 'false');
        $('[data-control="infos"]', $closest).val('');
        $('[data-control="input"]', $closest).val('');
    });
});