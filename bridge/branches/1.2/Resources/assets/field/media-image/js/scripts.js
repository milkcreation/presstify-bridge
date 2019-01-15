var tify_control_media_image_frame;

jQuery(document).ready(function ($) {
    $(document).on('click', '.tiFyField-MediaImageAdd', function (e) {
        e.preventDefault();

        var $this = $(this),
            $closest = $this.closest('.tiFyField-MediaImage');

        var title = $(this).data('media_library_title'),
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
            attachment = tify_control_media_image_frame.state().get('selection').first().toJSON();

            $this.css('background-image', 'url(' + attachment.url + '');
            $('.tiFyField-MediaImageInput', $closest).val(attachment.id);
            $('.tiFyField-MediaImageReset:hidden', $closest).fadeIn();
            $closest.addClass('tiFyField-MediaImage--selected');
        });

        tify_control_media_image_frame.open();
    });

    $(document).on('click', '.tiFyField-MediaImageRemove', function (e) {
        e.preventDefault();

        var $this = $(this),
            $closest = $this.closest('.tiFyField-MediaImage');

        $closest.removeClass('tiFyField-MediaImage--selected');
        $('.tiFyField-MediaImageInput', $closest).val('');
        $('.tiFyField-MediaImageAdd', $closest).css('background-image', 'url(' + $('.tiFyField-MediaImage', $closest).data('default') + ')');
    });
});