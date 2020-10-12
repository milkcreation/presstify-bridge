/* global tify */
'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/widgets/datepicker';


jQuery(function ($) {
  try {
    require('jquery-ui/ui/i18n/datepicker-'+ tify.locale.iso[1]);
  } catch (ex) {
    console.log('unavailable datepicker language.');
  }

  $.widget('tify.tifyDatepicker', {
    widgetEventPrefix: 'datepicker:',
    options: {
      'classes' : {
        'wrapper': 'FieldDatepicker-wrapper',
        'clearer': 'FieldDatepicker-clearer',
      }
    },
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initControls();
      this._initEvents();
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
      let o = this.option();

      if (this.uidatepicker === undefined) {
        this.uidatepicker = this.el.datepicker(o);
      }

      this.wrapper = this.el.wrap('<div class="'+ this.option('classes.wrapper') +'"/>')
          .closest('.'+ this.option('classes.wrapper'));

      this.el.css('width', '100%');

      this.clearer = $('<div class="'+ this.option('classes.clearer') +'" data-control="datepicker.clear"/>')
          .appendTo(this.wrapper);

      this._doDisplayClearer();
    },
    // Initialisation des événements déclenchement.
    _initEvents: function () {
      this._trigger('init');

      this._on(this.clearer, {'click': this._doClear});
      this._on(this.el, {'change': this._doDisplayClearer});
    },

    _doClear: function (e) {
      e.preventDefault();

      this.el.val('');
      this.clearer.hide();

      this._trigger('clear');
    },
    _doDisplayClearer : function () {
      if (this.el.val()) {
        this.clearer.show();
      } else {
        this.clearer.hide();
      }
    }
  });

  $(document).ready(function () {
    $('[data-control="datepicker"]').tifyDatepicker();

    $(document).on('focus', '[data-control="datepicker"]', function () {
      $(this).tifyDatepicker();
    });
  });
});