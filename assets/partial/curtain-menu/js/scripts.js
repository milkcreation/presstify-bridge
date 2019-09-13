/* global jQuery */
"use strict";

!(function ($) {
  $.widget('tify.tifyCurtainMenu', {
    widgetEventPrefix: 'curtain-menu:',
    id: undefined,
    options: {},
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this.level = 0;

      this.items = $('[data-control="curtain-menu.item"]', this.el);
      this.backs = $('[data-control="curtain-menu.back"]', this.el);

      this._initEvents();
    },
    _initEvents: function () {
      let self = this;

      this.items.each(function (i, j) {
        let subLevel = $('> [data-control="curtain-menu.panel"]', j);

        if (subLevel.length) {
          $('> [data-control="curtain-menu.nav"]', j).on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let level = $(this).closest('[data-control="curtain-menu.panel"]').data('level');

            if (self.level <= level) {
              self._open(subLevel);
            }
          });
        }
      });

      this.backs.each(function (i, j) {
        let curLevel = $(j).closest('[data-control="curtain-menu.panel"]');

        if (curLevel.length) {
          $(j).on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let level = curLevel.data('level');

            if (self.level <= level) {
              self._close(curLevel);
            }
          });
        }
      });
    },

    _open: function (subLevel) {
      ++this.level;
      subLevel.attr('aria-open', 'true');

      let parentLevel = subLevel.closest('li').closest('[data-control="curtain-menu.panel"]');
      if (parentLevel.length) {
        parentLevel.scrollTop(0);
      }
    },

    _close: function (curLevel) {
      --this.level;
      curLevel.attr('aria-open', 'false');
      //let parentLevel = curLevel.closest('li').closest('[data-control="curtain-menu.panel"]');
    }
  });

  $('[data-control="curtain-menu"]').tifyCurtainMenu();
})(jQuery, document, window);