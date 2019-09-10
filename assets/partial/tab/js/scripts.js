"use strict";

jQuery(function ($) {
    $.widget('tify.tifyTab', {
        widgetEventPrefix: 'tab:',
        id: undefined,
        xhr: undefined,
        options: {},

        // INITIALISATION
        // -------------------------------------------------------------------------------------------------------------
        // Instanciation de l'élément.
        _create: function () {
            let self = this;

            this.instance = this;

            this.el = this.element;

            this._activeRecursive(this.el);

            $('[data-control="tab.nav.link"][aria-selected="false"]').click(function() {
                self._activeRecursive($($(this).attr('href')));
            });
        },

        // Activation récursif d'éléments.
        _activeRecursive: function($container) {
            let $nav;

            if (!$('[data-control="tab.nav.link"][aria-selected="true"]', $container).length) {
                $nav = $('[data-control="tab.nav"]:first [data-control="tab.nav.link"]:first', $container);
            } else {
                $nav = $('[data-control="tab.nav.link"][aria-selected="true"]', $container);
            }
            if ($nav.length) {
                this._activeRecursive($($nav.trigger('click').attr('href')));
            }
        }
    });
});
jQuery(document).ready(function ($) {
    $('[data-control="tab"]').tifyTab();
});