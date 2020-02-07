'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/modal';
import 'presstify-framework/observer/js/scripts';

jQuery(function ($) {
  $.widget('tify.tifyModal', {
    widgetEventPrefix: 'modal:',
    options: {
      classes: {
        bkclose: 'modal-backdrop-close',
        body: 'modal-body',
        close: 'modal-close',
        content: 'modal-content',
        dialog: 'modal-dialog',
        footer: 'modal-footer',
        header: 'modal-header',
        spinner: 'modal-spinner'
      }
    },
    control: {
      bkclose: 'modal.backdrop.close',
      body: 'modal.content.body',
      close: 'modal.close',
      content: 'modal.content',
      dialog: 'modal.dialog',
      footer: 'modal.content.footer',
      header: 'modal.content.header',
      spinner: 'modal.spinner'
    },

    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this.flags = {
        hasBkclose: true,
        hasBody: true,
        hasClose: true,
        hasFooter: true,
        hasHeader: true,
        isAjax: true,
        isAnimated: true
      };

      this._initOptions();
      this._initFlags();
      this._initControls();
      this._initEvents();
    },
    // INTIALISATIONS.
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
      this.flags.hasBkclose = !!this.option('backdrop-close');
      this.flags.hasBody = !!this.option('body');
      this.flags.hasClose = !!this.option('close');
      this.flags.hasFooter = !!this.option('footer');
      this.flags.hasHeader = !!this.option('header');
      this.flags.isAjax = !!this.option('ajax');
      this.flags.isAnimated = !!this.option('animated');
    },
    // Initialisation des agents de contrôle.
    _initControls: function () {
      let self = this;

      this.el.addClass('modal').attr('role', 'dialog');

      if (this.flags.isAnimated) {
        this.el.addClass('fade');
      }

      this.dialog = $('> [data-control="' + this.control.dialog + '"]', this.el);
      if (!this.dialog.length) {
        this.dialog = $('<div data-control="' + this.control.dialog + '"/>').appendTo(this.el);
      }
      this.dialog.addClass(this.option('classes.dialog')).attr('role', 'document');
      if (this.option('size')) {
        this.dialog.addClass(this.option('size'));
      }

      if (this.flags.hasBkclose) {
        let $bkClose = $('> [data-control="' + this.control.bkclose + '"]', this.el);
        if (!$bkClose.length) {
          $bkClose = $('<button type="button" data-control="' + this.control.bkclose + '"/>').appendTo(this.el);
        }
        $bkClose.addClass(this.option('classes.bkclose'));
      }

      if (this.flags.hasClose) {
        let $close = $('> [data-control="' + this.control.close + '"]', this.dialog);
        if (!$close.length) {
          $close = $('<button type="button" data-control="' + this.control.close + '"/>').appendTo(this.dialog);
        }
        $close.addClass(this.option('classes.close'));
      }

      this.content = $('> [data-control="' + this.control.content + '"]', this.dialog);
      if (!this.content.length) {
        this.content = $('<div data-control="' + this.control.content + '"/>').appendTo(this.dialog);
      }
      this.content.addClass(this.option('classes.content'));

      if (this.flags.hasHeader) {
        let $header = $('> [data-control="' + this.control.header + '"]', this.content);
        if (!$header.length) {
          $header = $('<div data-control="' + this.control.header + '"/>').appendTo(this.content);
        }
        $header.addClass(this.option('classes.header'));
      }

      if (this.flags.hasBody) {
        let $body = $('> [data-control="' + this.control.body + '"]', this.content);
        if (!$body.length) {
          $body = $('<div data-control="' + this.control.body + '"/>').appendTo(this.content);
        }
        $body.addClass(this.option('classes.body'));
      }

      if (this.flags.hasFooter) {
        let $footer = $('> [data-control="' + this.control.footer + '"]', this.content);
        if (!$footer.length) {
          $footer = $('<div data-control="' + this.control.footer + '"/>').appendTo(this.content);
        }
        $footer.addClass(this.option('classes.footer'));
      }

      this.spin = $('> [data-control="' + this.control.spinner + '"]', this.dialog);
      if (!this.spin.length) {
        this.spin = $('<span data-control="' + this.control.spinner + '"/>').appendTo(this.dialog);
      }
      this.spin.addClass(this.option('classes.spinner')).attr('aria-hide', 'true');

      this.el.modal();

      // Délégation d'appel des événements de la modal.
      // @see https://getbootstrap.com/docs/4.0/components/modal/#events
      // ex. $('[data-control="modal"]').on('modal:show', function (event) {
      //    console.log(e);
      // });
      let events = ['show', 'shown', 'hide', 'hidden'];

      events.forEach(function (eventname) {
        self.el.on(eventname + '.bs.modal', function () {
          self._trigger(eventname);
        });
      });

      if (this.flags.isAjax) {
        let ajax = this.option('ajax');

        this.originalHtml = this.content.html();

        this.el
            .on('shown.bs.modal', function () {
              if (self.ajaxHtml === undefined) {
                self.spinner('show');
                $.ajax(ajax)
                    .done(function (resp) {
                      if (typeof resp === 'string') {
                        self.ajaxHtml = resp;
                      } else if (typeof resp === 'object') {
                        self.ajaxHtml = resp.data || undefined;
                      }
                      self.content.html(self.ajaxHtml);
                    })
                    .always(function () {
                      self.spinner('hide');
                    });
              } else {
                self.content.html(self.ajaxHtml);
              }
            })
            .on('hidden.bs.modal', function () {
              self.content.html(self.originalHtml);
            });
      }
    },
    // Initialisation des événements.
    _initEvents: function () {
      this._on(this.el, {'click [data-control="modal.open"]': this._onClickOpen});
      this._on(this.el, {'click [data-control="modal.close"]': this._onClickClose});
      this._on(this.el, {'click [data-control="modal.backdrop.close"]': this._onClickClose});
    },
    // EVENENEMENTS.
    // -----------------------------------------------------------------------------------------------------------------
    // Clique sur le bouton d'ouverture
    _onClickOpen: function (e) {
      e.preventDefault();
      this.open();
    },
    // Clique sur le bouton de fermeture
    _onClickClose: function (e) {
      e.preventDefault();
      this.close();
    },
    // ACCESSEURS.
    // -----------------------------------------------------------------------------------------------------------------
    // Modification du contenu du corps.
    body: function (html) {
      let $body = $('> [data-control="' + this.control.body + '"]', this.content);

      return html === undefined ? $body.html() : $body.html(html);
    },
    // Modification du contenu du corps.
    header: function (html) {
      $('> [data-control="' + this.control.header + '"]', this.content).html(html);
    },
    // Modification du contenu du corps.
    footer: function (html) {
      $('> [data-control="' + this.control.footer + '"]', this.content).html(html);
    },
    // Fermeture de la modale.
    close: function () {
      this.el.modal('hide');
    },
    // Ouverture de la modale.
    open: function () {
      this.el.modal('show');
    },
    // Bascule d'affichage de la modale.
    toggle: function () {
      this.el.modal('toggle');
    },
    // Mise à jour de la position de la modale.
    update: function () {
      this.el.modal('handleUpdate');
    },
    // Destruction de la modale.
    destroy: function () {
      this.el.modal('dispose');
    },
    /**
     * Indicateur de chargement
     * @var string status hide|show|toggle
     */
    spinner: function (status = 'toggle') {
      switch (status) {
        default:
          if (this.spin.attr('aria-hide') === 'true') {
            this.spin.attr('aria-hide', 'false');
          } else {
            this.spin.attr('aria-hide', 'true');
          }
          break;
        case 'hide' :
          this.spin.attr('aria-hide', 'true');
          break;
        case 'show' :
          this.spin.attr('aria-hide', 'false');
          break;
      }
    }
  });

  $.widget('tify.tifyModalTrigger', {
    widgetEventPrefix: 'modal-trigger:',

    // Instanciation de l'élément.
    _create: function () {
      this.el = this.element;

      this._on(this.el, { 'click': function (e) {
          e.preventDefault();

          let self = this,
              $el = $(self.el),
              $target = $('[data-id="' + self.el.data('target') + '"]');

          if (!$el.hasClass('show')) {
            $el.addClass('show');

            if ($target.length) {
              $target.on('modal:hide', function () {
                $el.removeClass('show');
              }).tifyModal('open');
            }
          }
        }
      });
    }
  });

  $(document).ready(function () {
    $('[data-control="modal"]').tifyModal();
    $.tify.observe('[data-control="modal"]', function (i, target) {
      $(target).tifyModal();
    });

    $('[data-control="modal.trigger"]').tifyModalTrigger();
    $.tify.observe('[data-control="modal.trigger"]', function (i, target) {
      $(target).tifyModalTrigger();
    });
  });
});