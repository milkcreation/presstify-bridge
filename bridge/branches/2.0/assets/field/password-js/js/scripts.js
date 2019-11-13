'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';

jQuery(function ($) {
  $.widget('tify.tifyPasswordJs', {
    widgetEventPrefix: 'password-js:',
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();

      this._on(this.el, {'click [data-control="password-js.toggle"]': this._onActionToggle});
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
    // Initialisation des événements.
    _onActionToggle: function (e) {
      e.preventDefault();

      let self = this,
          $el = $(e.currentTarget),
          ajax = self.option('ajax') || undefined,
          $target = $('[data-id="' + $el.data('target') + '"]'),
          $input = $('[data-control="password-js.input"]', $target),
          cypher = $input.attr('data-cypher');

      if ($target.attr('aria-hide') === 'true') {
        ajax = $.extend(true, ajax, {data: {action: 'decrypt', cypher: cypher}});
        $target.addClass('loading');
        $input.prop('disabled', true);

        $.ajax(ajax).done(function (resp) {
          if (resp.success) {
            $input
                .val(resp.data)
                .prop('disabled', false)
                .attr('type', 'text');
          }

          $target.removeClass('loading').attr('aria-hide', 'false');
        });
      } else {
        $input
            .val(cypher)
            .prop('disabled', false)
            .attr('type', 'password');

        $target.attr('aria-hide', 'true');
      }
    }
  });

  $(document).ready(function () {
    $('[data-control="password-js"]').tifyPasswordJs();
  });
});