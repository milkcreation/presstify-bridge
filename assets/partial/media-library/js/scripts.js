/* global wp */
'use strict';

import jQuery from 'jquery';
import 'presstify-framework/observer/js/scripts';

jQuery(function ($) {
  $.widget('tify.tifyMediaLibrary', {
    widgetEventPrefix: 'media-library:',
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initControls();
      this._initEvents();
    },
    // INTIALISATIONS.
    // -----------------------------------------------------------------------------------------------------------------
    // Initialisation des attributs de configuration.
    _initOptions: function () {
      $.extend(
          true,
          this.options,
          this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
      );
    },
    // Initialisation des événements.
    _initControls: function () {
      let self = this,
          o = this.option();

      this.library = wp.media(o);

      this.library.on('select', function () {
        let selection = self.library.state().get('selection'),
            items = [];

        selection.map(function (attachment) {
          items.push(attachment.toJSON());
        });
        self._trigger('select', null, [items]);
      });
    },
    // Initialisation des événements.
    _initEvents: function () {
      this._on(this.el, {'click [data-control="media-library.open"]': this._onOpen});
    },
    // EVENENEMENTS.
    // -----------------------------------------------------------------------------------------------------------------
    _onOpen: function (e) {
      e.preventDefault();
      this.open();
    },
    // ACCESSEURS.
    // -----------------------------------------------------------------------------------------------------------------
    open: function () {
      this.library.open();
    }
  });

  $(document).ready(function () {
    $('[data-control="media-library"]').tifyMediaLibrary();

    $.tify.observe('[data-control="media-library"]', function (i, target) {
      $(target).tifyMediaLibrary();
    });
  });
});