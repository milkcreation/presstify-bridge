jQuery(document).ready(function ($) {
    $('[aria-control="number_js"]').each(function() {
        var options = JSON.parse(
            decodeURIComponent($(this).data('options'))
        );
        $(this).spinner(options);
    });
});