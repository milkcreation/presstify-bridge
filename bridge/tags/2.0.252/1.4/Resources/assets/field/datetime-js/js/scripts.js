jQuery(document).ready(function ($) {
    $(document)
        .on('change.tify.fields.ajax_date', '[aria-control="datetime_js"]', function (e) {
            e.preventDefault();

            var $closest = $(this).closest('[aria-control="datetime_js"]');
            var value = "", dateFormat = "";
            if ($('.tiFyField-DatetimeJsField--year', $closest).length) {
                value += $('.tiFyField-DatetimeJsField--year', $closest).val();
                dateFormat += "YYYY";
            }
            if ($('.tiFyField-DatetimeJsField--month', $closest).length) {
                value += "-" + ("0" + parseInt($('.tiFyField-DatetimeJsField--month', $closest).val(), 10)).slice(-2);

                if (dateFormat)
                    dateFormat += "-";
                dateFormat += "MM";
            }
            if ($('.tiFyField-DatetimeJsField--day', $closest).length) {
                value += "-" + ("0" + parseInt($('.tiFyField-DatetimeJsField--day', $closest).val(), 10)).slice(-2);
                if (dateFormat)
                    dateFormat += "-";
                dateFormat += "DD";
            }
            if ($('.tiFyField-DatetimeJsField--hour', $closest).length) {
                value += " " + ("0" + parseInt($('.tiFyField-DatetimeJsField--hour', $closest).val(), 10)).slice(-2);
                if (dateFormat)
                    dateFormat += " ";
                dateFormat += "HH";
            }
            if ($('.tiFyField-DatetimeJsField--minute', $closest).length) {
                value += ":" + ("0" + parseInt($('.tiFyField-DatetimeJsField--minute', $closest).val(), 10)).slice(-2);

                if (dateFormat)
                    dateFormat += ":";
                dateFormat += "mm";
            }
            if ($('.tiFyField-DatetimeJsField--second', $closest).length) {
                value += ":" + ("0" + parseInt($('.tiFyField-DatetimeJsField--second', $closest).val(), 10)).slice(-2);
                if (dateFormat)
                    dateFormat += ":";
                dateFormat += "ss";
            }

            // Test d'intégrité
            if (moment(value, dateFormat, true).isValid()) {
                $closest.removeClass('invalid');
            } else {
                $closest.addClass('invalid');
            }

            $('.tiFyField-DatetimeJsField--value', $closest).val(value);

            $closest.trigger('tify_fields_ajax_date_change');
        });
});