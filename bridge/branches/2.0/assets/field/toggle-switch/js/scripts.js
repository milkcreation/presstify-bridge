'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'presstify-framework/observer/js/scripts';

jQuery(function ($) {
  // Attribution de la valeur à l'élément.
  let _hook = $.valHooks.div;

  $.valHooks.div = {
    get: function (elem) {
      if (typeof $(elem).tifyToggleSwitch('instance') === 'undefined') {
        return _hook && _hook.get && _hook.get(elem) || undefined;
      }
      return $(elem).data('value');
    },
    set: function (elem, value) {
      if (typeof $(elem).tifyToggleSwitch('instance') === 'undefined') {
        return _hook && _hook.set && _hook.set(elem, value) || undefined;
      }
      $(elem).data('value', value);
    }
  };

  $.widget('tify.tifyToggleSwitch', {
    widgetEventPrefix: 'toggle-switch:',
    id: undefined,
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this.el.data('value', $('input[type="radio"]:checked', this.el).val());

      this._initEvents();
    },
    // Initialisation des événements déclenchement.
    _initEvents: function () {
      let self = this;

      this._trigger('init');

      this._on(this.el, {'change input[type="radio"]': function (e) {
        self.el.data('value', $(e.target).val());
        self._trigger('change');
      }});
    },

  });

  /*$(document).on('change', '.FieldToggleSwitch-radio', function () {
    $(this)
        .closest('.FieldToggleSwitch')
        .trigger('toggle-switch:change', $(this).val());
  });*/

  $(document).ready(function ($) {
    $('[data-control="toggle-switch"]').tifyToggleSwitch();

    $.tify.observe('[data-control="toggle-switch"]', function (i, target) {
      $(target).tifyToggleSwitch();
    });
  });
});