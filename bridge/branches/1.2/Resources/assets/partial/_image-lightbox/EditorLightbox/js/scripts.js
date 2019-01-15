jQuery(document).ready(function ($) {
    var config = (typeof EditorLightbox !== 'undefined') ? EditorLightbox : {};

    // Médias des articles
    $('a').has('img[class*="wp-image-"]').tiFyImageLightbox(config);

    // Galeries Wordpress
    $('[id^="gallery-"]').each(function () {
        $(this).tiFyImageLightbox(config, {group: 'a'});
    });
});