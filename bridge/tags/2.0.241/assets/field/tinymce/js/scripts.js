/* global tify, tinymce, tinyMCE */
'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'presstify-framework/observer/js/scripts';

jQuery(function ($) {
  if (typeof (tinyMCE) === 'undefined') {
    require('tinymce/tinymce');
    require('tinymce/themes/silver/index');
  }

  if (tify.locale.language !== undefined) {
    try {
      require('tinymce-i18n/langs5/' + tify.locale.language);
    } catch (e) {
      console.log('Unavailable tinyMCE language ' + tify.locale.language);
    }
  }

  $.widget('tify.tifyTinymce', {
    widgetEventPrefix: 'tinymce:',
    id: undefined,
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initControls();
    },
    // INTIALISATIONS
    // -----------------------------------------------------------------------------------------------------------------
    // Initialisation des attributs de configuration.
    _initOptions: function () {
      $.extend(
          true,
          this.options,
          this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
      );
    },
    // Initialisation des agents de control
    _initControls : function () {
      let o = {branding: false};

      if (tify.locale.language !== undefined) {
        o.language = tify.locale.language;
      }

      this.el.addClass('tifyTinymce'+ this.uuid);

      tinymce.init($.extend({selector: '.tifyTinymce'+ this.uuid}, o, this.option()));
    }
  });

  $(document).ready(function () {
    $('[data-control="tinymce"]').tifyTinymce();

    $.tify.observe('[data-control="tinymce"]', function (i, target) {
      $(target).tifyTinymce();
    });
  });
});