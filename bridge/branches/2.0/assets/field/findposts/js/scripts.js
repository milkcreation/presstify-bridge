'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import Mustache from 'mustache';

jQuery(function ($) {
  $.widget('tify.tifyFindposts', {
    widgetEventPrefix: 'findposts:',
    options: {},
    // Instanciation de l'élément.
    _create: function () {
      this.instance = this;

      this.el = this.element;

      this._initOptions();
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
    // Initialisation des agents de contrôle.
    _initControls: function () {
      this.id = this.option('uniqid');

      this.wrapper = this.el.parent();

      this.opener = $('[data-control="findposts.opener"]', this.wrapper);

      this.modal = $('[data-control="findposts.modal"]', this.wrapper).appendTo('body');
      this.close = $('[data-control="findposts.modal.close"]', this.modal);
      this.form = $('[data-control="findposts.modal.form"]', this.modal);
      if (!this.form.length) {
        $('.find-box-search', this.modal).wrap('<form data-control="findposts.modal.form"/>');
        this.form = $('[data-control="findposts.modal.form"]', this.modal);
      }
      this.response = $('[data-control="findposts.modal.response"]', this.modal);
      this.spinner = $('[data-control="findposts.modal.spinner"]', this.modal);
      this.select = $('[data-control="findposts.modal.select"]', this.modal);

      this.tmpl = $('[data-control="findposts.tmpl"]', this.wrapper);
    },
    // Initialisation des événements.
    _initEvents: function () {
      this._on(this.opener, {'click': this._doOpen});
      this._on(this.close, {'click': this._doClose});
      this._on(this.form, {'submit': this._doSearch});
      this._on(this.select, {'click': this._doSelect});
      this._on(this.response, {'click tr': function (e) {
          $(e.target).closest('tr').find('.found-radio > input').prop('checked', true);
      }});
    },
    // EVENEMENTS
    // -----------------------------------------------------------------------------------------------------------------
    // Fermeture de la fenêtre de sélection.
    _doClose: function (e) {
      e.preventDefault();

      this.response.html('');
      this.modal.hide();
      this.overlay.hide();
    },
    // Ouverture de la fenêtre de sélection.
    _doOpen: function (e) {
      e.preventDefault();

      this._doOverlayShow(e);

      this.modal.show();

      this._doSearch(e);

      return false;
    },
    // Affichage du fond transparent.
    _doOverlayShow: function (e) {
      e.preventDefault();

      let self = this;

      if (!this.overlay) {
        this.overlay = $('<div class="ui-find-overlay"></div>').appendTo('body');
        this.overlay.on('click', function () {
          self._doClose(e);
        });
      }

      this.overlay.show();
    },
    // Lancement de la recherche.
    _doSearch: function (e) {
      e.preventDefault();

      let self = this,
          ajax = $.extend(true, {}, this.option('ajax') || {}, {data: this.form.serialize()});

      this.spinner.show();

      $.ajax(ajax)
          .always(function () {
            self.spinner.hide();
          })
          .done(function (resp) {
            if (!resp.success) {
              self.response.text(resp.data);
            } else {
              let tmpl = self.tmpl.html();

              Mustache.parse(tmpl);

              self.response.html(Mustache.render(tmpl, {'posts': resp.data}));
            }
          })
          .fail(function () {
            self.response.text('Oops !');
          });
    },
    // Sélection de l'élément.
    _doSelect: function (e) {
        e.preventDefault();

      let self = this,
          $checked = $('.found-posts .found-radio > input:checked', this.response);

        if ($checked.length) {
          let value = $checked.data('value') || $checked.val();

          self.el.val(value);
        }

        this._doClose(e);

        return false;
    }
  });

  $(document).ready(function ($) {
    $('[data-control="findposts"]').tifyFindposts();

    $.tify.observe('[data-control="findposts"]', function (i, target) {
      $(target).tifyFindposts();
    });
  });
});

/*scripts = {
    open: function (af_name, af_val) {
        if (af_name && af_val) {
            $('#affected').attr('name', af_name).val(af_val);
        }

        $('#find-posts-input').focus().keyup(function (event) {
            if (event.which === 27) {
                scripts.close();
            }
        });

        return false;
    },
};

$(document).ready(function () {
    $('#find-posts-submit').on('click', function (event) {
        if (!$('#find-posts-response input[type="radio"]:checked').length) {
            event.preventDefault();
        }
    });
    $('#find-posts .find-box-search :input').keypress(function (event) {
        if (13 === event.which) {
            scripts.send();
            return false;
        }
    });
    $('#doaction, #doaction2').on('click',function (event) {
        $('select[name^="action"]').each(function () {
            if ($(this).val() === 'attach') {
                event.preventDefault();
                scripts.open();
            }
        });
    });
});

$(document).ready(function () {
    $(document).on('click', '[data-control="findposts"] > button', function() {
        scripts
            .open('target', '#' + $('> input[type="text"]', $(this).closest('[data-control="findposts"]'))
            .attr('id'));
    });
}); */