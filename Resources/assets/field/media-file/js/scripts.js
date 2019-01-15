var tiFyFieldMediaFileFrame;

jQuery(document).ready(function ($) {
    $(document).on('click', '[aria-control="media_file"]', function (e) {
        e.preventDefault();

        var $closest = $(this),
            o = $.parseJSON(decodeURIComponent($(this).data('options')));

        tiFyFieldMediaFileFrame = wp.media.frames.file_frame = wp.media(o);

        tiFyFieldMediaFileFrame.on('select', function () {
            attachment = tiFyFieldMediaFileFrame.state().get('selection').first().toJSON();
            $closest.attr('aria-active', 'true');
            $('[aria-control="infos"]', $closest).val(attachment.title + ' → ' + attachment.filename);
            $('[aria-control="input"]', $closest).val(attachment.id);
        });

        tiFyFieldMediaFileFrame.open();
    });

    $(document).on('click', '[aria-control="media_file"] [aria-control="reset"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $closest = $(this).parent();

        $closest.attr('aria-active', 'false');
        $('[aria-control="infos"]', $closest).val('');
        $('[aria-control="input"]', $closest).val('');
    });
});