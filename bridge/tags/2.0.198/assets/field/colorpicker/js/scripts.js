"use strict";

jQuery(document).ready(function ($) {
    $(document).on('tify_field.colorpicker.init', function (event, obj) {
        var options = $.parseJSON(
            decodeURIComponent(
                $(obj).data('options')
            )
        );

        options = $.extend({change: function(color) { $(obj).val(color.toHexString()); }}, options);
        $(obj).spectrum(options);
    });

    $('.tiFyField-colorpicker').each(function () {
        $(document).trigger('tify_field.colorpicker.init', $(this));
    });
});