'use strict';

import jQuery from 'jquery';
import moment from "moment/moment";

jQuery(function ($) {
  $(document)
      .on('change.tify.fields.ajax_date', '[data-control="datetime-js"] > *', function (e) {
        e.preventDefault();

        let $closest = $(this).closest('[data-control="datetime-js"]'),
            value = '',
            dateFormat = '';

        if ($('.FieldDatetimeJs-field--year', $closest).length) {
          value += $('.FieldDatetimeJs-field--year', $closest).val();
          dateFormat += "YYYY";
        }
        if ($('.FieldDatetimeJs-field--month', $closest).length) {
          value += "-" + ("0" + parseInt($('.FieldDatetimeJs-field--month', $closest).val(), 10)).slice(-2);

          if (dateFormat)
            dateFormat += "-";
          dateFormat += "MM";
        }
        if ($('.FieldDatetimeJs-field--day', $closest).length) {
          value += "-" + ("0" + parseInt($('.FieldDatetimeJs-field--day', $closest).val(), 10)).slice(-2);
          if (dateFormat)
            dateFormat += "-";
          dateFormat += "DD";
        }
        if ($('.FieldDatetimeJs-field--hour', $closest).length) {
          value += " " + ("0" + parseInt($('.FieldDatetimeJs-field--hour', $closest).val(), 10)).slice(-2);
          if (dateFormat)
            dateFormat += " ";
          dateFormat += "HH";
        }
        if ($('.FieldDatetimeJs-field--minute', $closest).length) {
          value += ":" + ("0" + parseInt($('.FieldDatetimeJs-field--minute', $closest).val(), 10)).slice(-2);

          if (dateFormat)
            dateFormat += ":";
          dateFormat += "mm";
        }
        if ($('.FieldDatetimeJs-field--second', $closest).length) {
          value += ":" + ("0" + parseInt($('.FieldDatetimeJs-field--second', $closest).val(), 10)).slice(-2);
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

        $('.FieldDatetimeJs-field--value', $closest).val(value);

        $closest.trigger('tify_fields_ajax_date_change');
      });
});