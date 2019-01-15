jQuery(document).ready(function ($) {
    $('[aria-control="slider"]').on('init', function (event, slick) {
        $(this).addClass('is-ready');
    });
    $('[aria-control="slider"]').each(function () {
        $(this).slick();
    });
});