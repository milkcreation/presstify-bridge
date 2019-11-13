'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'dropzone/dist/min/dropzone.min';
import 'presstify-framework/partial/notice/js/scripts';
import 'presstify-framework/partial/pdfviewer/js/scripts';

jQuery(function ($) {
  $.widget('tify.tifyFileManager', {
    widgetEventPrefix: 'file-manager:',
    id: undefined,
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
      this._initUploader();
      this._initPdfPreview();
      this._initEvents();
    },

    // INITIALISATION
    // -----------------------------------------------------------------------------------------------------------------
    // Initialisation des événements déclenchement.
    _initEvents: function () {
      this._on(this.el, {
        'click [data-control="file-manager.action.toggle"]': this._onActionToggle
      });

      this._on(this.el, {
        'click [data-control="file-manager.browser.browse"]': this._onBrowse
      });

      this._on(this.el, {
        'click [data-control="file-manager.action.get"]': this._onGet
      });

      this._on(this.el, {
        'submit [data-control="file-manager.action.delete.form"]': this._onDelete
      });

      this._on(this.el, {
        'submit [data-control="file-manager.action.create.form"]': this._onCreate
      });

      this._on(this.el, {
        'submit [data-control="file-manager.action.rename.form"]': this._onRename
      });

      this._on(this.el, {
        'click [data-toggle], change [data-toggle]': this._onToggle
      });

      this._on(this.el, {
        'click [data-control="file-manager.view.toggle"]': this._onViewToggle
      });

      this._on(this.el, {
        'file-manager:refresh': this._onRefresh
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
    _initUploader: function () {
      let self = this;

      $('[data-control="file-manager.action.upload.form"]').each(function () {
        let dropzoneControl = $(this).get(0).dropzone;

        if (dropzoneControl === undefined) {
          $(this).dropzone({
            url: self.option('ajax.url'),
            createImageThumbnails: false,
            success: function (res, resp) {
              self._doUpdateViews(resp.views || {});
              $(res.previewElement).addClass('dz-' + res.status);
              return true;
            }
          });
        }
      });
    },
    _initPdfPreview: function () {
      $('.FileManager-preview--pdf').tifyPdfPreview();
    },

    // ACTIONS
    // -----------------------------------------------------------------------------------------------------------------
    /**
     * Mise à jours des vues.
     * @param {{breadcrumb:string, content:string, sidebar:string}} views
     * @private
     */
    _doUpdateViews: function (views) {
      if (views.breadcrumb) {
        $('[data-control="file-manager.breadcrumb"]', self.el).replaceWith(views.breadcrumb);
      }
      if (views.content) {
        $('[data-control="file-manager.content.items"]', self.el).replaceWith(views.content);
      }
      if (views.sidebar) {
        $('[data-control="file-manager.sidebar"]', self.el).html(views.sidebar);
      }
      if (views.notice) {
        $('[data-control="file-manager.notice"]', self.el).html(views.notice);
      }
    },

    // EVENEMENTS
    // -----------------------------------------------------------------------------------------------------------------
    // Bascule de fenêtre d'action.
    _onActionToggle: function (e) {
      e.preventDefault();

      let self = this,
          el = e.currentTarget,
          action = $(el).data('action'),
          target = '[data-control="file-manager.action.' + action + '"]',
          form = '[data-control="file-manager.action.' + action + '.form"]';

      $(target).toggle();
      if ($(target).is(':visible')) {
        $('[data-action="' + action + '"]', self.el).each(function () {
          $(this).attr('aria-visible', true);
        });
      } else {
        if ($(el).data('reset') === true) {
          $(form).trigger('reset');

          if (action === 'rename') {
            $('.FileManager-actionFormExt').show();
          }
        }
        $('[data-action="' + action + '"]', self.el).each(function () {
          $(this).attr('aria-visible', false);
        });
      }
    },
    // Au clic sur un dossier de l'explorateur de fichier
    _onBrowse: function (e) {
      e.preventDefault();

      let self = this,
          el = e.currentTarget,
          closest = $(el).closest('[data-control="file-manager.browser.item"]'),
          list = $('[data-control="file-manager.browser.items"]', closest),
          ajax = $.extend(self.option('ajax'), {data: {path: $(el).data('path'), action: 'browse'}});

      if ($(el).attr('selected') === true) {
        $(el).attr('selected', false);
      } else {
        $(el).attr('selected', true);

        $.ajax(ajax)
            .done(function (resp) {
              if (list.length) {
                list.replaceWith(resp.views.files);
              } else {
                closest.append(resp.views.files);
              }
            });
      }
    },
    // Au clic sur un élément (fichier ou dossier) de la vue.
    _onGet: function (e) {
      e.preventDefault();

      let self = this,
          el = e.currentTarget,
          ajax = $.extend(self.option('ajax'), {data: {path: $(el).data('path'), action: 'get'}});

      $(el).closest('[data-control="file-manager.content.item"]').addClass('selected')
          .siblings().removeClass('selected');

      $.ajax(ajax)
          .done(function (resp) {
            self._doUpdateViews(resp.views || {});
            self._trigger('refresh');
          });
    },
    // À la soumission de formulaire de suppression.
    _onDelete: function (e) {
      e.preventDefault();

      let self = this,
          el = e.currentTarget,
          ajax = $.extend(self.option('ajax'), {data: 'action=delete&' + $(el).serialize()});

      $.ajax(ajax)
          .done(function (resp) {
            self._doUpdateViews(resp.views || {});
            self._trigger('refresh');
          });
    },
    // À la soumission de formulaire de création de nouveau répertoire.
    _onCreate: function (e) {
      e.preventDefault();

      let self = this,
          el = e.currentTarget,
          ajax = $.extend(self.option('ajax'), {data: 'action=create&' + $(el).serialize()});

      $.ajax(ajax)
          .done(function (resp) {
            self._doUpdateViews(resp.views || {});
            self._trigger('refresh');
          });
    },
    // À la soumission de formulaire de création de renommage.
    _onRename: function (e) {
      e.preventDefault();

      let self = this,
          el = e.currentTarget,
          ajax = $.extend(self.option('ajax'), {data: 'action=rename&' + $(el).serialize()});

      $.ajax(ajax)
          .done(function (resp) {
            self._doUpdateViews(resp.views || {});
            self._trigger('refresh');
          });
    },
    // Rafraichissement de l'interface.
    _onRefresh: function () {
      this._initUploader();
      this._initPdfPreview();
    },
    // Bascule d'affichage.
    _onToggle: function (e) {
      let el = e.currentTarget;

      $($(el).data('toggle')).toggle();
    },
    // Bascule de la vue fichier.
    _onViewToggle: function (e) {
      let el = e.currentTarget,
          view = $(el).data('view');

      $(el).parent().addClass('selected').siblings().removeClass('selected');
      $('[data-control="file-manager.content"]').attr('data-view', view);
    },
  });

  $(document).ready(function () {
    $('[data-control="file-manager"]').tifyFileManager();
  });
});