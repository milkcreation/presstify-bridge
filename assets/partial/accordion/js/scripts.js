/* global tify */
'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';

jQuery(function ($) {
  $.widget('tify.tifyAccordion', {
    options: {
      multiple: false,
      triggered: false
    },
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this.id = $(this.el).data('id');

      this._initOptions();
      this._initTrigger();
      this._initOpened();
    },

    // INITIALISATION
    // -----------------------------------------------------------------------------------------------------------------
    // Initialisation des attributs de configuration.
    _initOptions: function () {
      $.extend(
          true,
          this.options,
          (tify[this.id] !== undefined && tify[this.id].options !== undefined) ? tify[this.id].options : {},
          this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
      );
    },
    // Initialisation du déclencheur.
    _initTrigger: function () {
      let self = this;

      $('.Accordion-item', this.el).each(function () {
        if ($('.Accordion-items', $(this)).length) {
          let $trigger = $('<span/>')
              .addClass('Accordion-itemTrigger')
              .data('control', 'accordion.item.trigger');

          if (self.option('triggered')) {
            $trigger.prependTo($('> .Accordion-itemContent', this));
          } else {
            $trigger.appendTo($('> .Accordion-itemContent', this));
          }

          self._onTriggerClick($trigger);
        }
      });
    },
    _initOpened: function () {
      $('.Accordion-items', this.el).each(function () {
        let $item = $('.Accordion-item[aria-open="true"]', $(this));

        if ($item.length) {
          let $closest = $(this).closest('.Accordion-item');

          if ($closest.length) {
            $closest.attr('aria-open', true);
          }

          $(this).css('max-height', '100%');

          $item.each(function () {
            let $subItems = $('> .Accordion-items', $item);

            if ($subItems.length) {
              $subItems.each(function () {
                $(this).css('max-height', '100%');
              });
            }
          });
        }
      });
    },

    // EVENTS
    // -----------------------------------------------------------------------------------------------------------------
    _onTriggerClick: function ($trigger) {
      let self = this;

      $trigger.click(function (e) {
        e.preventDefault();

        let $closest = $(this).closest('.Accordion-item'),
            $parents = $(this).parents('.Accordion-items');

        if (!self.option('multiple')) {
          $closest.siblings()
              .attr('aria-open', 'false')
              .children('.Accordion-items').css('max-height', 0);

          $closest.siblings()
              .children('.Accordion-items')
              .children('.Accordion-item')
              .attr('aria-open', 'false')
              .children('.Accordion-items').css('max-height', 0);
        }

        if ($closest.attr('aria-open') === 'true') {
          $('> .Accordion-items', $closest).css('max-height', 0);
          $closest.attr('aria-open', 'false');
        } else {
          let height = $('> .Accordion-items', $closest).prop('scrollHeight');
          $('> .Accordion-items', $closest).css('max-height', height);
          $closest.attr('aria-open', 'true');

          $parents.each(function () {
            let pheight = $(this).prop('scrollHeight');
            $(this).css('max-height', pheight + height);
          });
        }
      });
    }
  });

  $(document).ready(function() {
    $('[data-control="accordion"]').tifyAccordion();
  });
});