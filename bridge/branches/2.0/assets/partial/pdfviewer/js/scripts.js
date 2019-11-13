'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import pdfjs from 'pdfjs-dist';
import 'presstify-framework/observer/js/scripts';

/**
 * @typedef {Object} pdfjs
 * @typedef {Object} pdfjs.GlobalWorkerOptions
 * @property {string} pdfjs.GlobalWorkerOptions.workerSrc
 */
pdfjs.GlobalWorkerOptions.workerSrc = './node_modules/pdfjs-dist/build/pdf.worker.js';

jQuery(function ($) {
  $.widget('tify.tifyPdfviewer', {
    widgetEventPrefix: 'pdfviewer:',
    options: {
      classes: {
        body: 'Pdfviewer-contentBody',
        canvas: 'Pdfviewer-canvas',
        content: 'Pdfviewer-content',
        current: 'Pdfviewer-navCurrent',
        first: 'Pdfviewer-navLink Pdfviewer-navLink--first',
        footer: 'Pdfviewer-contentFooter',
        header: 'Pdfviewer-contentHeader',
        last: 'Pdfviewer-navLink Pdfviewer-navLink--last',
        nav: 'Pdfviewer-nav',
        next: 'Pdfviewer-navLink Pdfviewer-navLink--next',
        prev: 'Pdfviewer-navLink Pdfviewer-navLink--prev',
        status: 'Pdfviewer-navStatus',
        total: 'Pdfviewer-navTotal',
        spinner: 'Pdfviewer-spinner'
      }
    },
    control: {
      body: 'pdfviewer.content.body',
      canvas: 'pdfviewer.canvas',
      content: 'pdfviewer.content',
      current: 'pdfviewer.nav.current',
      first: 'pdfviewer.nav.first',
      footer: 'pdfviewer.content.footer',
      header: 'pdfviewer.content.header',
      last: 'pdfviewer.nav.last',
      nav: 'pdfviewer.nav',
      next: 'pdfviewer.nav.next',
      prev: 'pdfviewer.nav.prev',
      status: 'pdfviewer.nav.status',
      total: 'pdfviewer.nav.total',
      spinner: 'pdfviewer.spinner'
    },
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this.flags = {
        hasContentHeader: false,
        hasContentFooter: false,
        hasNavFirst: true,
        hasNavPrev: true,
        hasNavNext: true,
        hasNavLast: true,
        hasNavStatus: true,
        hasSpinner: true
      };

      this.pdfDoc = undefined;
      this.pageNum = 1;
      this.pageRendering = false;
      this.pageNumPending = null;
      this.total = 0;

      this._initOptions();
      this._initFlags();
      this._initControls();
      this._initEvents();
    },
    // INITIALISATION.
    // -----------------------------------------------------------------------------------------------------------------
    // Initialisation des événements déclenchement.
    _initEvents: function () {
      this._on(this.el, {
        'click [data-control="pdfviewer.nav.first"]:not([aria-disabled="true"])': this._onClickNavFirst
      });
      this._on(this.el, {
        'click [data-control="pdfviewer.nav.last"]:not([aria-disabled="true"])': this._onClickNavLast
      });
      this._on(this.el, {
        'click [data-control="pdfviewer.nav.next"]:not([aria-disabled="true"])': this._onClickNavNext
      });
      this._on(this.el, {
        'click [data-control="pdfviewer.nav.prev"]:not([aria-disabled="true"])': this._onClickNavPrev
      });
    },
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
      this.flags.hasContentHeader = !!this.option('content.header');
      this.flags.hasContentFooter = !!this.option('content.footer');
      this.flags.hasNavFirst = !!this.option('nav.first');
      this.flags.hasNavPrev = !!this.option('nav.prev');
      this.flags.hasNavNext = !!this.option('nav.next');
      this.flags.hasNavLast = !!this.option('nav.last');
      this.flags.hasNavStatus = !!this.option('nav.status');
      this.flags.hasSpinner = !!this.option('spinner');
    },
    // Initialisation des agents de contrôle.
    _initControls: function () {
      if (this.flags.hasSpinner) {
        let $spinner = $('[data-control="' + this.control.spinner + '"]', this.el);
        if (!$spinner.length) {
          $spinner = $('<div data-control="' + this.control.spinner + '"/>')
              .appendTo(this.el).html('<span class="ThemeSpinner"/>');
        }
        $spinner.addClass(this.option('classes.spinner'));
        this._doSpinnerDisplay('hide');
      }

      let $content = $('[data-control="' + this.control.content + '"]', this.el);
      if (!$content.length) {
        $content = $('<div data-control="' + this.control.content + '"/>').appendTo(this.el);
      }
      $content.addClass(this.option('classes.content'));

      if (this.flags.hasContentHeader) {
        let $header = $('> [data-control="' + this.control.header + '"]', $content);
        if (!$header.length) {
          $header = $('<div data-control="' + this.control.header + '"/>').appendTo($content);
        }
        $header.addClass(this.option('classes.header'));
      }

      let $body = $('> [data-control="' + this.control.body + '"]', $content);
      if (!$body.length) {
        $body = $('<div data-control="' + this.control.body + '"/>').appendTo($content);
      }
      $body.addClass(this.option('classes.body'));

      let $canvas = $('> [data-control="' + this.control.canvas + '"]', $body);
      if (!$canvas.length) {
        $canvas = $('<canvas data-control="' + this.control.canvas + '"/>').appendTo($body);
      }
      $canvas.addClass(this.option('classes.canvas'));

      if (this.flags.hasContentFooter) {
        let $footer = $('> [data-control="' + this.control.footer + '"]', $content);
        if (!$footer.length) {
          $footer = $('<div data-control="' + this.control.footer + '"/>').appendTo($content);
        }
        $footer.addClass(this.option('classes.footer'));
      }

      this.nav = $('[data-control="' + this.control.nav + '"]', this.el);
      if (!this.nav.length) {
        this.nav = $('<div data-control="' + this.control.nav + '"/>').appendTo(this.el);
      }
      this.nav.addClass(this.option('classes.nav')).attr('aria-hide', 'true');

      if (this.flags.hasNavFirst) {
        let $first = $('[data-control="' + this.control.first + '"]', this.nav);
        if (!$first.length) {
          $first = $('<span data-control="' + this.control.first + '"/>').appendTo(this.nav).html('&laquo;');
        }
        $first.addClass(this.option('classes.first'));
      }

      if (this.flags.hasNavNext) {
        let $prev = $('[data-control="' + this.control.prev + '"]', this.nav);
        if (!$prev.length) {
          $prev = $('<span data-control="' + this.control.prev + '"/>').appendTo(this.nav).html('&lsaquo;');
        }
        $prev.addClass(this.option('classes.prev'));
      }

      if (this.flags.hasNavStatus) {
        let $status = $('[data-control="' + this.control.status + '"]', this.nav);
        if (!$status.length) {
          $status = $('<span data-control="' + this.control.status + '"/>').appendTo(this.nav);

          let $current = $('<span data-control="' + this.control.current + '"/>')
                  .addClass(this.option('classes.current')),
              $total = $('<span data-control="' + this.control.total + '"/>').addClass(this.option('classes.total'));
          $status.append($current).append('/').append($total);
        }
        $status.addClass(this.option('classes.status'));
      }

      if (this.flags.hasNavNext) {
        let $next = $('[data-control="' + this.control.next + '"]', this.nav);
        if (!$next.length) {
          $next = $('<span data-control="' + this.control.next + '"/>').appendTo(this.nav).html('&rsaquo;');
        }
        $next.addClass(this.option('classes.next'));
      }

      if (this.flags.hasNavLast) {
        let $last = $('[data-control="' + this.control.last + '"]', this.nav);
        if (!$last.length) {
          $last = $('<span data-control="' + this.control.last + '"/>').appendTo(this.nav).html('&raquo;');
        }
        $last.addClass(this.option('classes.last'));
      }

      if (!this.option('defer')) {
        this._doDocumentGet();
      }
    },
    // ACTIONS.
    // -----------------------------------------------------------------------------------------------------------------
    // Chargement du document PDF.
    _doDocumentGet: function (page) {
      let self = this,
          src = this.option('src') || undefined;

      if (src !== undefined) {
        this._doSpinnerDisplay('show');

        pdfjs.getDocument(src).promise.then(function (pdf) {
          self.pdfDoc = pdf;
          self.total = pdf.numPages;
          self._doPageRender(page ? page : self.pageNum);
        });
      }
    },
    // Mise à jour de la pagination.
    _doNavUpdate: function () {
      if (this.total <= 1) {
        this.nav.attr('aria-hide', 'true');
      } else {
        this.nav.attr('aria-hide', 'false');
      }

      $('[data-control="' + this.control.current + '"]', this.nav).text(this.pageNum);
      $('[data-control="' + this.control.total + '"]', this.nav).text(this.total);

      if (this.pageNum <= 2) {
        $('[data-control="' + this.control.first + '"]', this.nav).attr('aria-disabled', 'true');
      } else {
        $('[data-control="' + this.control.first + '"]', this.nav).attr('aria-disabled', 'false');
      }

      if (this.pageNum <= 1) {
        $('[data-control="' + this.control.prev + '"]', this.nav).attr('aria-disabled', 'true');
      } else {
        $('[data-control="' + this.control.prev + '"]', this.nav).attr('aria-disabled', 'false');
      }

      if (this.pageNum >= this.total) {
        $('[data-control="' + this.control.next + '"]', this.nav).attr('aria-disabled', 'true');
      } else {
        $('[data-control="' + this.control.next + '"]', this.nav).attr('aria-disabled', 'false');
      }

      if (this.pageNum + 1 >= this.total) {
        $('[data-control="' + this.control.last + '"]', this.nav).attr('aria-disabled', 'true');
      } else {
        $('[data-control="' + this.control.last + '"]', this.nav).attr('aria-disabled', 'false');
      }
    },
    // Mise en file de l'affichage d'une page.
    _doPageQueueRender: function (num) {
      if (this.pageRendering) {
        this.pageNumPending = num;
      } else {
        this._doPageRender(num);
      }
    },
    // Affichage d'une page.
    _doPageRender: function (num) {
      let self = this;

      this._doSpinnerDisplay('show');
      self.pageRendering = true;

      self.pdfDoc.getPage(num).then(function (pdfPage) {
        let viewport = pdfPage.getViewport({scale: 1}),
            canvas = $('[data-control="' + self.control.canvas + '"]', self.el).get(0),
            context = canvas.getContext('2d');

        canvas.width = viewport.width || viewport.viewBox[2];
        canvas.height = viewport.height || viewport.viewBox[3];

        let renderTask = pdfPage.render({
          canvasContext: context,
          viewport: viewport
        });

        renderTask.promise.then(function () {
          self.pageRendering = false;
          self._doSpinnerDisplay('hide');
          if (self.pageNumPending !== null) {
            self._doPageRender(self.pageNumPending);
            self.pageNumPending = null;
          }
        });

        self._doNavUpdate();
      }, function (reason) {
        console.error(reason);
      });
    },
    // Affichage/Masquage de l'indicateur de chargement.
    _doSpinnerDisplay: function (display = 'toggle') {
      switch (display) {
        default:
          if ($('[data-control="' + this.control.spinner + '"]', this.el).attr('aria-hide') === 'false') {
            this._doSpinnerDisplay('hide');
          } else {
            this._doSpinnerDisplay('show');
          }
          break;
        case 'hide' :
          $('[data-control="' + this.control.spinner + '"]', this.el).attr('aria-hide', 'true');
          break;
        case 'show' :
          $('[data-control="' + this.control.spinner + '"]', this.el).attr('aria-hide', 'false');
          break;
      }
    },
    // EVENEMENTS.
    // -----------------------------------------------------------------------------------------------------------------
    // Navigation vers la première page.
    _onClickNavFirst: function (e) {
      e.preventDefault();

      this.first();
    },
    // Navigation vers la derniere page.
    _onClickNavLast: function (e) {
      e.preventDefault();

      this.last();
    },
    // Navigation vers la page suivante.
    _onClickNavNext: function (e) {
      e.preventDefault();

      this.next();
    },
    // Navigation vers la page précédente.
    _onClickNavPrev: function (e) {
      e.preventDefault();

      this.prev();
    },
    // ACCESSEURS.
    // -----------------------------------------------------------------------------------------------------------------
    // Chargement de la dernière page.
    first: function () {
      if (this.pageNum === 1) {
        return;
      }

      this._doPageQueueRender(this.pageNum = 1);
    },
    // Chargement de la dernière page.
    last: function () {
      if (this.pageNum === this.total) {
        return;
      }

      this._doPageQueueRender(this.pageNum = this.total);
    },
    // Chargement de la page suivante.
    next: function () {
      if (this.pageNum >= this.total) {
        return;
      }

      this._doPageQueueRender(++this.pageNum);
    },
    // Chargement d'une page.
    load: function (page) {
      if (this.pdfDoc === undefined) {
        this._doDocumentGet(page);
      } else {
        this._doPageQueueRender(page ? page : this.pageNum);
      }
    },
    // Chargement de la page précédente.
    prev: function () {
      if (this.pageNum <= 1) {
        return;
      }

      this._doPageQueueRender(--this.pageNum);
    },
  });

  $(document).ready(function () {
    $('[data-control="pdfviewer"]').tifyPdfviewer();

    $.tify.observe('[data-control="pdfviewer"]', function (i, target) {
      $(target).tifyPdfviewer();
    });
  });
});