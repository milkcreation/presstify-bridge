/* global jQuery */
"use strict";

jQuery(function ($) {
  // Attribution de la valeur à l'élément.
  let _hook = $.valHooks.span;

  $.valHooks.span = {
    get: function (elem) {
      if (typeof $(elem).tifySuggest('instance') === 'undefined') {
        return _hook && _hook.get && _hook.get(elem) || undefined;
      }
      return $(elem).data('value');
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
    id: undefined,
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initAutocomplete();
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
    // Initialisation du pilote de téléchargement.
    _initAutocomplete: function () {
      let self = this,
          ajax = this.option('ajax') || undefined,
          exists = this.uiautocomplete || undefined,
          o = self.option('autocomplete');

      if (exists === undefined) {
        if (ajax && !o.source) {
          $.extend(o, {
            source: function (request, response) {
              ajax = $.extend(true, ajax, {data: {_term: request.term}});

              self.el.attr('aria-loaded', 'true');

              $.ajax(ajax).done(function (resp) {
                if (resp.success) {
                  response(resp.data.items || []);
                }
              }).always(function () {
                self.el.attr('aria-loaded', 'false');
              });
            }
          });
        }

        this.uiautocomplete = $('[data-control="suggest.input"]', this.el).autocomplete(o || {});

        let handler = this.uiautocomplete.data('ui-autocomplete');

        handler._renderMenu = function (ul, items) {
          let that = this;
          $.each(items, function (index, item) {
            that._renderItemData(ul, item, index);
          });
          ul.addClass(o.classes.picker);
        };

        handler._renderItemData = function (ul, item, index) {
          let render = (item.render !== undefined) ? item.render : item.label;
              item.value = $('<div/>').html(item.value).text();

          return $("<li>")
              .attr("data-index", index)
              .attr("data-value", item.value)
              .addClass(o.classes['picker-item'] + ' ' + o.classes['picker-item'] + '--' + index)
              .append(render)
              .appendTo(ul)
              .data("ui-autocomplete-item", item);
        };

        this.uiautocomplete
            .on('autocompleteselect', function (event, ui) {
              let $alt = $('[data-control="suggest.alt"]', self.el);

              if ($alt.length) {
                $alt.val(ui.item.alt || ui.item.value);
                self.el.data('value', ui.item.alt || ui.item.value);
              } else {
                self.el.data('value', ui.item.value);
              }
            })
            .on('autocompletefocus', function (event) {
              event.preventDefault();
            });
      }
    },
    // Initialisation des événements.
    _initEvents: function () {
      let self = this;

      // Délégation d'appel des événements d'autaocomplete.
      // @see https://api.jqueryui.com/autocomplete/#events
      // ex. $('[data-control="suggest"]').on('suggest:select', function (e, file, resp) {
      //    console.log(resp);
      // });
      if (this.uiautocomplete !== undefined) {
        let events = [
          'change', 'close', 'create', 'focus', 'open', 'response', 'search', 'select'
        ];

        events.forEach(function (eventname) {
          self.uiautocomplete.on('autocomplete' + eventname, function (e) {
            self._trigger(eventname, e, arguments);
          });
        });
      }
    },
  });

  $(document).ready(function ($) {
    $(document).on('focus', '[data-control="suggest"]', function () {
      $(this).tifySuggest();
    });
  });
});