'use strict';

import jQuery from 'jquery';

jQuery(function ($) {
  $(document).on('change', '.tiFyField-toggleSwitchRadio', function () {
    $(this)
        .closest('.tiFyField-toggleSwitch')
        .trigger('tify_field.toggleSwitch.change', $(this).val());
  });
});