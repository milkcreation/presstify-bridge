"use strict";

jQuery(function ($) {
  $.widget('tify.tifyMetaboxRelatedPosts', {
    widgetEventPrefix: 'metabox-related-posts:',
    options: {},

    // INITIALISATION
    // -------------------------------------------------------------------------------------------------------------
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initSuggest();
      this._initSortable();
      this._initItems();
    },

    // Initialisation des attributs de configuration.
    _initOptions: function () {
      $.extend(
          true,
          this.options,
          this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
      );
    },

    // Initialisation de l'ordonnancement des éléments.
    _initSuggest: function () {
      let self = this;

      $('[data-control="suggest"]', this.el).on('suggest:select', function () {
        let o = $.extend(true, self.option('ajax') || {}, {
          data: {
            index: $('[data-control="metabox.related-posts.item"]', self.el).length,
            post_id: $(this).val()
          }
        });

        $.ajax(o).done(function (resp) {
          if (resp.success) {
            let $item = $(resp.data).appendTo($('[data-control="metabox.related-posts.items"]', self.el));
            self._setItem($item);
          }
        });
      });
    },

    // Initialisation de l'ordonnancement des éléments.
    _initSortable: function () {
      let self = this,
          o = $.extend({
            axis: 'y',
            handle: '[data-control="metabox.related-posts.item.sort"]'
          }, self.option('sortable') || {}, {
            update: function (event, ui) {
              self._doUpdateItemsOrder();
            }
          });

      $('[data-control="metabox.related-posts.items"]', self.el).sortable(o).disableSelection();
    },

    // Initialisation de la liste des éléments.
    _initItems: function () {
      let self = this;

      $('[data-control="metabox.related-posts.item"]', self.el).each(function () {
        self._setItem($(this));
      });
    },

    // SETTER
    // -------------------------------------------------------------------------------------------------------------
    // Définition d'un élément.
    _setItem: function ($item) {
      let self = this;

      self._onItemMetasToggle($item);
      self._onItemRemove($item);
    },

    // ACTIONS
    // -------------------------------------------------------------------------------------------------------------
    // Mise à jour des indicateurs d'ordre d'affichage.
    _doUpdateItemsOrder: function () {
      let self = this;

      $('[data-control="metabox.related-posts.item.order"]', self.el).each(function (i) {
        $(this).val(i + 1);
      });
    },

    // EVENTS
    // -------------------------------------------------------------------------------------------------------------
    // Bascule d'affichage des métadonnées.
    _onItemMetasToggle: function ($item) {
      $('[data-control="metabox.related-posts.item.metas-toggle"]', $item).on(
          'mouseenter mouseleave',
          function (e) {
            e.preventDefault();

            if ($item.attr('aria-metas') === 'true') {
              $item.attr('aria-metas', 'false')
            } else {
              $item.attr('aria-metas', 'true')
            }
          });
    },
    // Suppression d'un élément.
    _onItemRemove: function ($item) {
      let self = this;

      $('[data-control="metabox.related-posts.item.remove"]', $item).on('click', function (e) {
        e.preventDefault();

        $item.fadeOut(function () {
          $(this).remove();
          self._doUpdateItemsOrder();
        });
      });
    },
  });
});

jQuery(document).ready(function ($) {
  $('[data-control="metabox.related-posts"]').tifyMetaboxRelatedPosts();
});