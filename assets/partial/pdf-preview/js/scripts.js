"use strict";

// Instanciation de pdf.js
import pdfjs from 'pdfjs-dist';

pdfjs.GlobalWorkerOptions.workerSrc = './node_modules/pdfjs-dist/build/pdf.worker.js';

jQuery(function ($) {
  $.widget('tify.tifyPdfPreview', {
    widgetEventPrefix: 'pdf-preview:',
    id: undefined,
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initEvents();
    },

    // INITIALISATION
    // -----------------------------------------------------------------------------------------------------------------
    // Initialisation des événements déclenchement.
    _initEvents: function () {
      this.pdfDoc = null;
      this.pageNum = 1;
      this.pageRendering = false;
      this.pageNumPending = null;

      this._doLoad();

      this._on(this.el, {
        'click [data-control="pdf-preview.nav.next"]': this._onNavNext
      });

      this._on(this.el, {
        'click [data-control="pdf-preview.nav.prev"]': this._onNavPrev
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
    // ACTION
    // -----------------------------------------------------------------------------------------------------------------
    // Chargement initial.
    _doLoad: function () {
      let self = this;

      pdfjs.getDocument($(self.el).data('src')).promise.then(function (pdf) {
        self.pdfDoc = pdf;

        $('[data-control="pdf-preview.page.total"]', self.el).text(self.pdfDoc.numPages);

        self._doRenderPage(self.pageNum);
      });
    },

    // Affichage d'une page.
    _doRenderPage: function (num) {
      let self = this;

      self.pageRendering = true;

      self.pdfDoc.getPage(num).then(function (pdfPage) {
        let viewport = pdfPage.getViewport(1.0),
            canvas = $('[data-control="pdf-preview.view"]', self.el).get(0),
            context = canvas.getContext('2d');

        canvas.width = viewport.width || viewport.viewBox[2];
        canvas.height = viewport.height || viewport.viewBox[3];

        let renderTask = pdfPage.render({
          canvasContext: context,
          viewport: viewport
        });

        renderTask.promise.then(function () {
          self.pageRendering = false;
          if (self.pageNumPending !== null) {
            self._doRenderPage(self.pageNumPending);
            self.pageNumPending = null;
          }
        });

        $('[data-control="pdf-preview.page.num"]', self.el).text(num);
        $('[data-control="pdf-preview.page"]', self.el).attr('aria-visible', true);
      }, function (reason) {
        console.error(reason);
      });
    },

    // Mise en file de l'affichage d'une page.
    _doQueueRenderPage: function (num) {
      if (this.pageRendering) {
        this.pageNumPending = num;
      } else {
        this._doRenderPage(num);
      }
    },

    // EVENEMENTS
    // -----------------------------------------------------------------------------------------------------------------
    // Navigation vers la page suivante.
    _onNavNext: function (e) {
      e.preventDefault();

      if (this.pageNum >= this.pdfDoc.numPages) {
        return;
      }
      this.pageNum++;
      this._doQueueRenderPage(this.pageNum);
    },

    // Navigation vers la page précédente.
    _onNavPrev: function (e) {
      e.preventDefault();

      if (this.pageNum <= 1) {
        return;
      }
      this.pageNum--;
      this._doQueueRenderPage(this.pageNum);
    }
  });

  $(document).ready(function ($) {
    $('[data-control="pdf-preview"]').tifyPdfPreview();
  });
});