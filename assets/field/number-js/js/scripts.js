'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/widgets/button';
import 'jquery-ui/ui/widgets/spinner';
import 'presstify-framework/observer/js/scripts';

jQuery(function ($) {
  // Attribution de la valeur à l'élément.
  let _hook = $.valHooks.div;

  $.valHooks.div = {
    get: function (elem) {
      if (typeof $(elem).tifyNumberJs('instance') === 'undefined') {
        return _hook && _hook.get && _hook.get(elem) || undefined;
      }
      return $(elem).data('value');
    },
    set: function (elem, value) {
      if (typeof $(elem).tifyNumberJs('instance') === 'undefined') {
        return _hook && _hook.set && _hook.set(elem, value) || undefined;
      }
      $(elem).data('value', value);
    }
  };

  $.widget('tify.tifyNumberJs', {
    widgetEventPrefix: 'number-js:',
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initUiSpinner();
      this._initEvents();
    },
    // Initialisation des attributs de configuration.
    _initOptions: function () {
      $.extend(
          true,
          this.options,
          this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
      );
    },
    // Initialisation du widget jQueryUi associé.
    _initUiSpinner: function () {
      let self = this,
          exists = this.uispinner || undefined,
          o = this.option('spinner') || {};

      if (exists === undefined) {
        this.uispinner = $('[data-control="number-js.input"]', this.el).spinner(o);

        this.uispinner
            .on('spin', function (event, ui) {
              self.el.data('value', ui.value);
            })
            .on('spinchange', function (event) {
              self.el.data('value', $(event.target).val());
            });
      }
    },
    // Initialisation des événements.
    _initEvents: function () {
      let self = this;

      // Délégation d'appel des événements d'autaocomplete.
      // @see https://api.jqueryui.com/spinner/#events
      // ex. $('[data-control="number-js"]').on('number-js:change', function (event, ui) {
      //    console.log(ui);
      // });
      if (this.uispinner !== undefined) {
        let events = [
          'change', 'create', 'start', 'stop'
        ];
        events.forEach(function (eventname) {
          self.uispinner.on('spin' + eventname, function (event, ui) {
            self._trigger(eventname, event, ui);
          });
        });
        self.uispinner.on('spin', function (event, ui) {
          self._trigger('spin', event, ui);
        });
      }
    },
  });

  $(document).ready(function () {
    $('[data-control="number-js"]').tifyNumberJs();

    $.tify.observe('[data-control="number-js"]', function (i, target) {
      $(target).tifyNumberJs();
    });
  });
});