'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/position';
import 'jquery-ui/ui/widgets/menu';
import 'jquery-ui/ui/widgets/autocomplete';

jQuery(function ($) {
  // Attribution de la valeur à l'élément.
  let _hook = $.valHooks.input;

  $.valHooks.input = {
    get: function (elem) {
      if (typeof $(elem).tifySuggest('instance') === 'undefined') {
        return _hook && _hook.get && _hook.get(elem) || undefined;
      }
      return $(elem).data('value') || $(elem).prop('value');
    },
    set: function (elem, value) {
      if (typeof $(elem).tifySuggest('instance') === 'undefined') {
        return _hook && _hook.set && _hook.set(elem, value) || undefined;
      }
      $(elem).data('value', value);
    }
  };

  $.widget('tify.tifySuggest', {
    widgetEventPrefix: 'suggest:',
    options: {
      classes: {
        alt: 'FieldSuggest-alt',
        input: 'FieldSuggest-input',
        item: 'FieldSuggest-pickerItem',
        items: 'FieldSuggest-picker',
        reset: 'FieldSuggest-reset',
        spinner: 'FieldSuggest-spinner',
        wrap: 'FieldSuggest-wrap',
      },
    },
    control: {
      alt: 'suggest.alt',
      input: 'suggest',
      reset: 'suggest.reset',
      spinner: 'suggest.spinner',
      wrap: 'suggest.wrap',
    },
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this.flags = {
        isAlt: true,
        isReset: true,
        isSpinner: true
      };

      this._initOptions();
      this._initFlags();
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
    // Initialisation des indicateurs d'état.
    _initFlags: function () {
      this.flags.isAlt = !!this.option('alt');
      this.flags.isReset = !!this.option('reset');
      this.flags.isSpinner = !!this.option('spinner');
    },
    // Initialisation des agents de contrôle.
    _initControls: function () {
      let self = this,
          ajax = this.option('ajax') || undefined,
          o = self.option('autocomplete');

      this.wrap = $(this.el).closest('[data-control="' + this.control.wrap + '"]');
      if (!this.wrap.length) {
        this.wrap = $(this.el).wrap('<div data-control="' + this.control.wrap + '"/>').parent();
      }
      this.wrap.addClass(this.option('classes.wrap')).attr('aria-loaded', 'false').attr('aria-selected', 'false');

      if (this.flags.isAlt) {
        this.alt = $('[data-control="' + this.control.alt + '"]', this.wrap);
        if (!this.alt.length) {
          this.alt = $('<input type="hidden" data-control="' + this.control.alt + '"/>').appendTo(this.wrap);
        }

        let name = this.el.attr('name') || undefined;
        if (name !== undefined) {
          this.alt.attr('name', name);
        }

        this.alt.addClass(this.option('classes.alt'));
      }

      if (this.flags.isReset) {
        let $reset = $('[data-control="' + this.control.reset + '"]', this.wrap);
        if (!$reset.length) {
          $reset = $('<span data-control="' + this.control.reset + '"/>').appendTo(this.wrap);
        }
        $reset.addClass(this.option('classes.reset'));
      }

      if (this.flags.isSpinner) {
        let $spinner = $('[data-control="' + this.control.spinner + '"]', this.wrap);
        if (!$spinner.length) {
          $spinner = $('<span data-control="' + this.control.spinner + '"/>').appendTo(this.wrap);
        }
        $spinner.addClass(this.option('classes.spinner'));
      }

      if (this.uiautocomplete === undefined) {
        if (ajax && !o.source) {
          $.extend(o, {
            source: function (request, response) {
              ajax = $.extend(true, ajax, {data: {_term: request.term}});

              self.wrap.attr('aria-loaded', 'true').attr('aria-selected', 'false');

              $.ajax(ajax).done(function (resp) {
                if (resp.success) {
                  response(resp.data.items || []);
                }
              }).always(function () {
                self.wrap.attr('aria-loaded', 'false');
              });
            }
          });
        }

        this.uiautocomplete = this.el.autocomplete(o || {});

        let handler = this.uiautocomplete.data('ui-autocomplete');
        handler._renderMenu = function (ul, items) {
          let that = this;
          $.each(items, function (index, item) {
            that._renderItemData(ul, item, index);
          });
          ul.addClass(self.option('classes.items'));
        };

        handler._renderItemData = function (ul, item, index) {
          let render = (item.render !== undefined) ? item.render : item.label;
          item.value = $('<div/>').html(item.value).text();

          return $("<li>")
              .attr("data-index", index)
              .attr("data-value", item.value)
              .addClass(self.option('classes.item') + ' ' + self.option('classes.item') + '--' + index)
              .append(render)
              .appendTo(ul)
              .data("ui-autocomplete-item", item);
        };

        this.uiautocomplete
            .on('autocompletesearch', function () {
              self.resetted = false;
            })
            .on('autocompleteselect', function (event, ui) {
              if (self.resetted === false) {
                self.value(self.flags.isAlt ? ui.item.alt || ui.item.value : ui.item.value);
              }
            })
            .on('autocompletefocus', function (event) {
              event.preventDefault();
            });

        // Délégation d'appel des événements d'autocomplete.
        // @see https://api.jqueryui.com/autocomplete/#events
        // ex. $('[data-control="suggest"]').on('suggest:select', function (e, file, resp) {
        //    console.log(resp);
        // });
        let events = ['change', 'close', 'create', 'focus', 'open', 'response', 'search', 'select'];

        events.forEach(function (eventname) {
          self.uiautocomplete.on('autocomplete' + eventname, function (e, ui) {
            self._trigger(eventname, e, ui);
          });
        });
      }
    },
    // Initialisation des événements déclenchement.
    _initEvents: function () {
      this._on(this.wrap, {'click [data-control="suggest.reset"]': this.reset});
      this._on(this.uiautocomplete, {'keyup': this._onKeyUp});
    },
    // EVENEMENTS.
    // -----------------------------------------------------------------------------------------------------------------
    _onKeyUp: function () {
      if (this.wrap.attr('aria-selected') === 'true') {
        this.reset();
      }
    },
    // ACCESSEURS.
    // -----------------------------------------------------------------------------------------------------------------
    // Suppression de la valeur.
    reset: function () {
      this.resetted = true;

      this.value('');
    },
    // Définition ou récupération de la valeur.
    value: function (value) {
      if (value !== undefined) {
        this.el.prop('value', value);
        this.el.data('value', value);

        if (this.flags.isAlt) {
          this.alt.val(value);
        }

        if (value) {
          this.wrap.attr('aria-selected', 'true');
        } else {
          this.wrap.attr('aria-selected', 'false');
        }
      }

      return this.el.data('value');
    }
  });

  $(document).ready(function () {
    $('[data-control="suggest"]').tifySuggest();

    $(document).on('focus', '[data-control="suggest"]', function () {
      $(this).tifySuggest();
    });
  });
});