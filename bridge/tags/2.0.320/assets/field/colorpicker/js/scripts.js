'use strict';

import jQuery from 'jquery';
import 'spectrum-colorpicker/spectrum';
import 'presstify-framework/observer/js/scripts';

/*
if (tify.locale.iso[1] !== undefined) {
  try {
    require('spectrum-colorpicker/i18n/jquery.spectrum-' + tify.locale.iso[1]);
  } catch (e) {
    console.log('Unavailable spectrum language ' + tify.locale.iso[1]);
  }
}
*/

jQuery(function ($) {

  $.widget('tify.tifyColorpicker', {
    widgetEventPrefix: 'colorpicker:',
    options: {
      classes: {}
    },
    controls: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initControls();
    },
    // INITIALISATIONS.
    // -----------------------------------------------------------------------------------------------------------------
    // Initialisation des attributs de configuration.
    _initOptions: function () {
      $.extend(
          true,
          this.options,
          this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
      );
    },
    // Initialisation des agents de contrôle.
    _initControls: function () {
      let self = this;
          /*o = $.extend({
            change: function (color) {
              $(obj).val(color.toHexString());
            }
          }, self.option());*/

      this.el.spectrum(self.option());
    }
  });

  $(document).ready(function () {
    $('[data-control="colorpicker"]').tifyColorpicker();

    $.tify.observe('[data-control="colorpicker"]', function (i, target) {
       $(target).tifyColorpicker();
    });
  });
});