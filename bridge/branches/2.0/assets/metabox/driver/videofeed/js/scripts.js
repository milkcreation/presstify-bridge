'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/widgets/sortable';
import 'presstify-framework/field/media-image/js/scripts';
import 'presstify-framework/partial/media-library/js/scripts';

jQuery(function ($) {
  $.widget('tify.tifyMetaboxVideofeed', {
    widgetEventPrefix: 'metabox-videofeed:',
    xhr: undefined,
    options: {
      classes: {
        addnew: 'MetaboxVideofeed-addnew',
        down: 'MetaboxVideofeed-itemSortDown',
        input: 'MetaboxVideofeed-itemInput',
        item: 'MetaboxVideofeed-item',
        items: 'MetaboxVideofeed-items',
        library: 'MetaboxVideofeed-itemLibrary',
        order: 'MetaboxVideofeed-itemOrder',
        remove: 'MetaboxVideofeed-itemRemove',
        sort: 'MetaboxVideofeed-itemSortHandler',
        up: 'MetaboxVideofeed-itemSortUp',
      },
      removable: true,
      sortable: true
    },
    control: {
      addnew: 'metabox-videofeed.addnew',
      down: 'metabox-videofeed.item.down',
      input: 'metabox-videofeed.item.input',
      item: 'metabox-videofeed.item',
      items: 'metabox-videofeed.items',
      library: 'metabox-videofeed.item.library',
      order: 'metabox-videofeed.item.order',
      remove: 'metabox-videofeed.item.remove',
      sort: 'metabox-videofeed.item.sort',
      up: 'metabox-videofeed.item.up',
    },
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this.flags = {
        isLibrary: true,
        isRemovable: true,
        isSortable: true
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
      this.flags.isLibrary = !!this.option('library');
      this.flags.isRemovable = !!this.option('removable');
      this.flags.isSortable = !!this.option('sortable');
    },
    // Initialisation des agents de contrôle.
    _initControls: function () {
      this._initElementControls();
      this._initItemControls();
    },
    // Initialisation du controleur principal.
    _initElementControls: function () {
      this.el.attr('aria-sortable', this.flags.isSortable);
      this.el.attr('aria-removable', this.flags.isRemovable);

      let addnew = $('[data-control="' + this.control.addnew + '"]', this.el);
      if (addnew.length) {
        addnew.addClass(this.option('classes.addnew'));
      }
    },
    // Initialisation du controleur principal.
    _initItemControls: function () {
      let self = this;

      this.items = $('[data-control="' + this.control.items + '"]', this.el);
      if (!this.items.length) {
        this.items = $('<ul data-control="' + this.control.items + '"/>').appendTo(this.el);
      }
      this.items.addClass(this.option('classes.items'));

      let exists = $('[data-control="' + this.control.item + '"]', this.items);
      if (exists.length) {
        exists.each(function () {
          self._setItem($(this));
        });
      }

      if (this.flags.isSortable) {
        this.option('sortable', $.extend({
          handle: '[data-control="' + this.control.sort + '"]',
          containment: this.items,
          axis: 'Y',
          start: function (e, ui) {
            ui.placeholder.height(ui.item.height());
          }
        }, this.option('sortable'), {
          update: function (event, ui) {
            self._doUpdateOrders();

            self._trigger('sort', null, ui.item);
          }
        }));

        this.sortable = this.items.sortable(this.option('sortable'));
      }
    },
    // Initialisation des événements déclenchement.
    _initEvents: function () {
      this._on(this.el, {'click [data-control="metabox-videofeed.addnew"]': this._onAddnewItem});
      this._on(this.el, {'click [data-control="metabox-videofeed.item.library"]': this._onGetLibraryItem});
      this._on(this.el, {'click [data-control="metabox-videofeed.item.down"]': this._onMoveDownItem});
      this._on(this.el, {'click [data-control="metabox-videofeed.item.up"]': this._onMoveUpItem});
      this._on(this.el, {'click [data-control="metabox-videofeed.item.remove"]': this._onRemoveItem});
    },
    // EVENEMENTS.
    // -----------------------------------------------------------------------------------------------------------------
    // Ajout d'un élément.
    _onAddnewItem: function (e) {
      e.preventDefault();

      if (this.xhr === undefined) {
        let self = this,
            index = $('[data-control="' + this.control.item + '"]', self.el).length,
            ajax = $.extend(true, {}, self.option('ajax') || {}, {data: {index: index}});

        this.xhr = $.ajax(ajax)
            .done(function (resp) {
              if (!resp.success) {
                alert(resp.data);
              } else {
                let $item = self._setItem($(resp.data).appendTo(self.items));

                self._trigger('add', null, $item);
              }
            })
            .always(function () {
              self.xhr = undefined;
            });
      }
    },
    // Récupération de la vidéo d'un élément depuis la médiathèque.
    _onGetLibraryItem: function (e) {
      e.preventDefault();

      let self = this,
          $item = $(e.target).closest('[data-control="' + this.control.item + '"]'),
          library = $(e.target).tifyMediaLibrary({multiple: false, library: {type: 'video'}});

      $(e.target).on('media-library:select', function (e, selection) {
        let video = selection[0] || {};

        $('[data-control="' + self.control.input + '"]', $item).val(video.url);
      });

      library.tifyMediaLibrary('open');
    },
    // Déplacement d'un élément vers le bas.
    _onMoveDownItem: function (e) {
      e.preventDefault();

      let $item = $(e.target).closest('[data-control="' + this.control.item + '"]'),
          $after = $item.next();

      if ($after.length) {
        $item.insertAfter($after);
        this._doUpdateOrders();

        this._trigger('down', null, $item);
      }
    },
    // Déplacement d'un élément vers le haut.
    _onMoveUpItem: function (e) {
      e.preventDefault();

      let $item = $(e.target).closest('[data-control="' + this.control.item + '"]'),
          $before = $item.prev();

      if ($before.length) {
        $item.insertBefore($before);
        this._doUpdateOrders();

        this._trigger('up', null, $item);
      }
    },
    // Suppression d'un élément.
    _onRemoveItem: function (e) {
      e.preventDefault();

      let self = this,
          $item = $(e.target).closest('[data-control="' + this.control.item + '"]');

      $item.fadeOut(function () {
        $(this).remove();
        self._doUpdateOrders();

        self._trigger('remove', null, $item);
      });
    },
    // ACTIONS
    // -----------------------------------------------------------------------------------------------------------------
    // Mise à jour des indicateurs d'ordre d'affichage.
    _doUpdateOrders: function () {
      $('[data-control="' + this.control.order + '"]', this.el).each(function (i) {
        $(this).val(i + 1);
      });
    },
    // DEFINITIONS.
    // -----------------------------------------------------------------------------------------------------------------
    // Définition d'un élément.
    _setItem: function ($item) {
      $item.addClass(this.option('classes.item'));

      let $input = $('[data-control="' + this.control.input + '"]', $item);
      if (!$input.length) {
        $input = $('<textarea data-control="' + this.control.input + '"/>').appendTo($item);
      }
      if ($input.attr('name') === undefined) {
        $input.attr('name', this.option('name'));
      }
      $input.addClass(this.option('classes.input'));

      if (this.flags.isRemovable) {
        let $remove = $('[data-control="' + this.control.remove + '"]', $item);
        if (!$remove.length) {
          $remove = $('<a href="#" data-control="' + this.control.remove + '"/>').appendTo($item);
        }
        $remove.addClass(this.option('classes.remove'));
      }

      if (this.flags.isSortable) {
        let $down = $('[data-control="' + this.control.down + '"]', $item),
            $order = $('[data-control="' + this.control.order + '"]', $item),
            $sort = $('[data-control="' + this.control.sort + '"]', $item),
            $up = $('[data-control="' + this.control.up + '"]', $item);

        if (!$sort.length) {
          $sort = $('<span data-control="' + this.control.sort + '"/>').appendTo($item);
        }
        $sort.addClass(this.option('classes.sort'));

        if (!$order.length) {
          $order = $('<input type="text" value="' + ($item.index() + 1) + '" ' +
              'size="1" readonly data-control="' + this.control.order + '"/>').appendTo($item);
        }
        $order.addClass(this.option('classes.order'));

        if (!$up.length) {
          $up = $('<a href="#" data-control="' + this.control.up + '"/>').appendTo($item);
        }
        $up.addClass(this.option('classes.up'));

        if (!$down.length) {
          $down = $('<a href="#" data-control="' + this.control.down + '"/>').appendTo($item);
        }
        $down.addClass(this.option('classes.down'));
      }

      if (this.flags.isLibrary) {
        let $library = $('[data-control="' + this.control.library + '"]', $item);
        if (!$library.length) {
          $library = $('<button data-control="' + this.control.library + '"/>').appendTo($item);
        }
        $library.addClass(this.option('classes.library'));
      }

      return $item;
    }
  });

  $(document).ready(function () {
    $('[data-control="metabox-videofeed"]').tifyMetaboxVideofeed();
  });
});