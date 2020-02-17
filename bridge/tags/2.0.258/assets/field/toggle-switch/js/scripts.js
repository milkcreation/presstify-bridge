'use strict';

import jQuery from 'jquery';

jQuery(function ($) {
  $(document).on('change', '.FieldToggleSwitch-radio', function () {
    $(this)
        .closest('.FieldToggleSwitch')
        .trigger('toggle-switch:change', $(this).val());
  });
});