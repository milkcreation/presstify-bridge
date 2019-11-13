'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/widgets/progressbar';

jQuery(function ($) {
  // Attribution de la valeur à l'élément.
  let _hook = $.valHooks.div;

  $.valHooks.div = {
    get: function (elem) {
      if (typeof $(elem).tifyProgress('instance') === 'undefined') {
        return _hook && _hook.get && _hook.get(elem) || undefined;
      }
      return $(elem).data('value');
    },
    set: function (elem, value) {
      if (typeof $(elem).tifyProgress('instance') === 'undefined') {
        return _hook && _hook.set && _hook.set(elem, value) || undefined;
      }
      $(elem).data('value', value);
    }
  };

  $.widget('tify.tifyProgress', {
    widgetEventPrefix: 'progress:',
    id: undefined,
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;
      this.meter = {bar: undefined, label: undefined};

      this._initOptions();
      this._initMeter();
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
    // Initialisation du pilote de barre de progression.
    _initMeter: function () {
      let self = this,
          label = this.meter.label || undefined,
          bar = this.meter.bar || undefined,
          o = self.option();

      if (label === undefined) {
        if ($('[data-control="progress.meter.label"]', this.el).length) {
          this.meter.label = $('[data-control="progress.meter.label"]', this.el);
        }
      }

      if (bar === undefined) {
        this.meter.bar = $('[data-control="progress.meter.bar"]', this.el).progressbar(o || {});
        this.meter.bar
            .on('progressbarchange', function () {
              let max = parseInt($(this).attr('aria-valuemax')),
                  value = parseInt($(this).attr('aria-valuenow'));

              self.el.data('value', value);

              if (self.option('label') === 'auto') {
                self.meter.label.text(((value / max) * 100).toFixed(2) + '%');
              }
            });
      }
    },
    // Initialisation des événements.
    _initEvents: function () {
      let self = this;

      // Délégation d'appel des événements de la barre de progression.
      // @see https://api.jqueryui.com/progressbar/#events
      // ex. $('[data-control="progress"]').on('progress:change', function (event, ui) {
      //    console.log(resp);
      // });
      if (this.meter.bar !== undefined) {
        let events = ['change', 'complete', 'create'];
        events.forEach(function (eventname) {
          self.meter.bar.on('progressbar' + eventname, function (event, ui) {
            self._trigger(eventname, event, ui);
          });
        });
      }
    },
    // ACCESSEURS.
    // -----------------------------------------------------------------------------------------------------------------
    /**
     * Décrémentation de l'indicateur.
     *
     * @uses $('[data-control="progress"]').tifyProgress('decrement');
     *
     * @return this
     */
    decrement: function () {
      if (this.meter.bar !== undefined) {
        let value = this.meter.bar.progressbar('value');
        this.value(--value);
      }
      return this.instance;
    },
    /**
     * Incrémentation de l'indicateur.
     *
     * @uses $('[data-control="progress"]').tifyProgress('increment');
     */
    increment: function () {
      if (this.meter.bar !== undefined) {
        let value = this.meter.bar.progressbar('value');
        this.value(++value);
      }
      return this.instance;
    },
    /**
     * Définition de l'intitulé de l'indicateur.
     *
     * @param {string} label
     *
     * @uses $('[data-control="progress"]').tifyProgress('label', {label});
     */
    label: function (label) {
      if (this.meter.label !== undefined) {
        this.option('label', 'fixed');

        this.meter.label.text(label);
      }
      return this.instance;
    },
    /**
     * Définition de la valeur de maximale de l'indicateur.
     *
     * @param {number} max
     *
     * @uses $('[data-control="progress"]').tifyProgress('max', {max});
     */
    max: function (max) {
      if (this.meter.bar !== undefined) {
        this.meter.bar.progressbar('option', 'max', max);
      }
      return this.instance;
    },
    /**
     * Définition de la valeur de progression de l'indicateur.
     *
     * @param {number} value
     *
     * @uses $('[data-control="progress"]').tifyProgress('value', {value});
     */
    value: function (value) {
      if (this.meter.bar !== undefined) {
        this.meter.bar.progressbar('value', value);
      }
      return this.instance;
    },
    /**
     * Réinitialisation de la valeur de progression de l'indicateur.
     *
     * @uses $('[data-control="progress"]').tifyProgress('reset');
     */
    reset: function () {
      return this.value(0);
    },
  });

  $(document).ready(function () {
    $('[data-control="progress"]').tifyProgress();
  });
});