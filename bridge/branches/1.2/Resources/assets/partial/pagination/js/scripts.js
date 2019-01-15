/**
 * @see https://learn.jquery.com/plugins/stateful-plugins-with-widget-factory/
 * @see https://api.jqueryui.com/jquery.widget
 * @see https://blog.rodneyrehm.de/archives/11-jQuery-Hooks.html
 */
!(function ($, doc, win) {
    $.widget(
        'tify.tifyPagination', {
            // Définition des options par défaut
            options: {
                handler: '',
                target: ''
            },

            // Instanciation de l'élément
            _create: function () {
                var self = this;

                // Définition de l'alias court du controleur d'affichage
                this.el = this.element;

                // Initialisation des attributs de configuration du controleur
                this._initOptions();

                this.handler = $(this.options.container_id);
                this.target = !this.options.target ? this.handler.prev() : $(this.options.target);
                this.xhr = undefined;

                $(document).on('click', self.handler, function (e) {
                    if ($(this).hasClass('tiFyCoreControl-ScrollPaginate--complete')) {
                        return false;
                    }

                    var o = self.options,
                        offset = $('> *', self.target).length;

                    self.target.addClass('tiFyCoreControl-ScrollPaginateLoading tiFyCoreControl-ScrollPaginateLoading--target');
                    $(this).addClass('tiFyCoreControl-ScrollPaginateLoading tiFyCoreControl-ScrollPaginateLoading--handler');

                    self.target.trigger('tify_control.scroll_paginate.loading', $(this));

                    self.xhr = $.post(
                        tify_ajaxurl,
                        {
                            action: o.ajax_action,
                            _ajax_nonce: o.ajax_nonce,
                            options: o,
                            offset: offset
                        }
                    )
                        .done(function(data, textStatus, jqXHR){
                            self.target.append(data.html);
                            self.target.trigger('tify_control.scroll_paginate.item_added', data.html);

                            if (data.complete) {
                                self.target.addClass('tiFyCoreControl-ScrollPaginateComplete tiFyCoreControl-ScrollPaginateComplete--target');
                                $(this).addClass('tiFyCoreControl-ScrollPaginateComplete tiFyCoreControl-ScrollPaginateComplete--handler');
                            }
                        })
                        .then(function(data, textStatus, jqXHR ){
                            self.target.removeClass('tiFyCoreControl-ScrollPaginateLoading tiFyCoreControl-ScrollPaginateLoading--target');
                            $(this).removeClass('tiFyCoreControl-ScrollPaginateLoading tiFyCoreControl-ScrollPaginateLoading--handler');

                            self.target.trigger('tify_control.scroll_paginate.loaded', $(this));

                            self.xhr = undefined;
                        });
                });
                this._listenEvents();
            },
            _initOptions: function () {
                if (this.el.data('options')) {
                    $.extend(
                        this.options,
                        $.parseJSON(decodeURIComponent(this.el.data('options')))
                    );
                }
            },
            _isScrolledIntoView: function () {
                var offset = this.element.offset();

                if (!offset) {
                    return false;
                }

                var lBound = this.window.scrollTop(),
                    uBound = lBound + this.window.height(),
                    top = offset.top,
                    bottom = top + this.element.outerHeight(true);

                return (top > lBound && top < uBound)
                    || (bottom > lBound && bottom < uBound)
                    || (lBound >= top && lBound <= bottom)
                    || (uBound >= top && uBound <= bottom);
            },
            _listenEvents: function() {
                this._on(this.document, {
                    scroll: function(event) {
                        if ((this.xhr === undefined) && !this.element.hasClass('tiFyCoreControl-ScrollPaginateComplete') && this._isScrolledIntoView()) {
                            this.element.trigger('click');
                        }
                    }
                });
            }
    });
})(jQuery, document, window);

jQuery(document).ready(function($){
    $('[aria-control="scroll_paginate"]').tifyPagination();
});