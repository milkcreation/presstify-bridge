var taboox_video_gallery_src_frame, taboox_video_gallery_poster_frame;

jQuery(document).ready(function ($) {
    $('.tiFyTabMetaboxPostTypeVideoGallery-add').click(function (e) {
        e.preventDefault()

        var $list = $(this).prev(),
            $spinner = $(this).next(),
            name = $(this).data('name'),
            max = $(this).data('max');

        if (max > 0 && $('li', $list).length >= max) {
            alert(tify_taboox_video_gallery.maxAttempt);
            return false;
        }

        $spinner.css('visibility', 'visible');

        $.post(
            tify.ajax_url,
            {
                action: 'tify_tab_metabox_post_type_video_gallery_add_item',
                name: name
            }
        ).done(function (resp) {
            $list.append(resp);
            $spinner.css('visibility', 'hidden');
        });
    });

    $(document).on('click', '.tiFyTabMetaboxPostTypeVideoGallery-itemRemove', function (e) {
        $container = $(this).parent();
        $container.fadeOut(function () {
            $container.remove();
        });
    });

    $(document).on('click', '.tiFyTabMetaboxPostTypeVideoGallery-itemSrcAdd', function (e) {
        e.preventDefault();

        var $target = $(this).prev();

        taboox_video_gallery_src_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('media_title'),
            editing: true,
            button: {
                text: $(this).data('media_button_text'),
            },
            multiple: false,
            library: {
                type: 'video'
            }
        });

        taboox_video_gallery_src_frame.on('select', function () {
            attachment = taboox_video_gallery_src_frame.state().get('selection').first().toJSON();
            $target.html(attachment.url);
        });

        taboox_video_gallery_src_frame.open();
    });

    // Ajout d'une jaquette pour la vidéo
    $(document).on('click', '.tiFyTabMetaboxPostTypeVideoGallery-itemPosterAdd', function (e) {
        e.preventDefault();

        var $target = $(this);

        taboox_video_gallery_poster_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('media_title'),
            editing: true,
            button: {
                text: $(this).data('media_button_text'),
            },
            multiple: false,
            library: {type: 'image'} // ['image/gif','image/png']
        });

        taboox_video_gallery_poster_frame.on('select', function () {
            attachment = taboox_video_gallery_poster_frame.state().get('selection').first().toJSON();
            $target.css('background-image', 'url(' + attachment.url + '');
            $target.next().val(attachment.id);
        });

        taboox_video_gallery_poster_frame.open();
    });

    $(".tiFyTabMetaboxPostTypeVideoGallery-items").sortable({
        placeholder: "ui-sortable-placeholder",
        axis: 'y'
    });
});
