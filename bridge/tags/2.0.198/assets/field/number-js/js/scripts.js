/* global jQuery */
"use strict";

jQuery(function ($) {
    // Attribution de la valeur à l'élément.
    let _hook = $.valHooks.div;

    $.valHooks.div = {
        get: function (elem) {
            if (typeof $(elem).tifyNumberJs('instance') === 'undefined') {
                return _hook && _hook.get && _hook.get(elem) || undefined;
            }
            return $(elem).data('value');
        },
        set: function (elem, value) {
            if (typeof $(elem).tifyNumberJs('instance') === 'undefined') {
                return _hook && _hook.set && _hook.set(elem, value) || undefined;
            }
            $(elem).data('value', value);
        }
    };

    $.widget('tify.tifyNumberJs', {
        widgetEventPrefix: 'number-js:',
        id: undefined,
        options: {},
        // Instanciation de l'élément.
        _create: function () {
            this.instance = this;

            this.el = this.element;

            this._initOptions();
            this._initUiSpinner();
            this._initEvents();
        },
        // Initialisation des attributs de configuration.
        _initOptions: function () {
            $.extend(
                true,
                this.options,
                this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
            );
        },
        // Initialisation du widget jQueryUi associé.
        _initUiSpinner: function () {
            let self = this,
                exists = this.uispinner || undefined,
                o = this.option('spinner') || {};

            if (exists === undefined) {
                this.uispinner = $('[data-control="number-js.input"]', this.el).spinner(o);

                this.uispinner
                    .on('spin', function (event, ui) {
                        self.el.data('value', ui.value);
                    });
            }
        },
        // Initialisation des événements.
        _initEvents: function () {
            let self = this;

            // Délégation d'appel des événements d'autaocomplete.
            // @see https://api.jqueryui.com/spinner/#events
            // ex. $('[data-control="number-js"]').on('number-js:change', function (e, event, ui) {
            //    console.log(ui);
            // });
            if (this.uispinner !== undefined) {
                let events = [
                    'change', 'create', 'start', 'stop'
                ];
                events.forEach(function (eventname) {
                    self.uispinner.on('spin' + eventname, function (e) {
                        self._trigger(eventname, e, arguments);
                    });
                });
                self.uispinner.on('spin', function (e) {
                    self._trigger('spin', e, arguments);
                });
            }
        },
    });

    $(document).ready(function ($) {
        $('[data-control="number-js"]').tifyNumberJs();
    });
});