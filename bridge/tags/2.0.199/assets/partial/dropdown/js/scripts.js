"use strict";

jQuery(function ($) {
    $.widget('tify.tifyDropdown', {
        widgetEventPrefix: 'dropdown:',
        id: undefined,
        xhr: undefined,
        options: {
            classes: {
                button: 'PartialDropdown-button',
                listItems: 'PartialDropdown-items',
                item: 'PartialDropdown-item'
            },
            open: false,
            triggered: false
        },

        // INITIALISATION
        // -------------------------------------------------------------------------------------------------------------
        // Instanciation de l'élément.
        _create: function () {
            this.instance = this;

            this.el = this.element;

            this.id = this.el.data('id');

            this.flags = {
                isOpen: false,
                hasTrigger: false
            };

            this._initOptions();
            this._initFlags();
            this._initControls();
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
            this.flags.isOpen = !!this.option('open');
            this.flags.hasTrigger = !!this.option('trigger');
        },

        // Initialisation des agents de contrôle.
        _initControls: function () {
            this._initControlElement();
            this._initControlButton();
            this._initControlItems();
        },

        // Initialisation du bouton
        _initControlElement: function () {
            this.el.attr('aria-open', this.flags.isOpen);
        },

        // Initialisation du bouton
        _initControlButton: function () {
            this.button = $('[data-control="dropdown.button"]', this.el);

            if (!this.button.length) {
                this.button = $('<button data-control="dropdown.button"/>').prependTo(this.el);
            }
            this.button.addClass(this.option('classes.button'));

            if (!this.flags.hasTrigger) {
                this.trigger = this.button;
            } else {
                this.trigger = $('[data-control="dropdown.trigger"]');
                if (!this.trigger.length) {
                    this.trigger = $('<span data-control="dropdown.trigger"/>').appendTo(this.button);
                }
            }
            this.trigger.attr('data-toggle', 'dropdown');

            this._onTriggerClick();
            this._onOutsideClick();
        },

        // Initialisation du bouton
        _initControlItems: function () {
            let self = this;

            this.listItems = $('[data-control="dropdown.items"]', this.el);

            if (!this.listItems.length) {
                this.listItems = $('<ul data-control="dropdown.items"/>').appendTo(this.el);
            }
            this.listItems.addClass(this.option('classes.listItems'));

            $('[data-control="dropdown.item"]').each(function(){
                $(this).addClass(self.option('classes.item'));
            });
        },

        // ACTIONS
        // -------------------------------------------------------------------------------------------------------------
        /**
         * Bascule
         * @param action. open|close
         */
        _doToggle: function (action) {
            this.el.attr('aria-open', this.flags.isOpen = (action === 'open'));
            this._trigger('toggle');
            this._trigger(action);
        },

        //EVENTS
        // -------------------------------------------------------------------------------------------------------------
        // Activation au clic sur le bouton de bascule.
        _onTriggerClick: function () {
            let self = this;

            this.trigger.on('click.dropdown.trigger.' + this.instance.uuid, function(e) {
                e.preventDefault();

                self._doToggle((self.el.attr('aria-open') === 'true' ? 'close' : 'open'));
            });
        },

        // Activation du clic en dehors de l'élément.
        _onOutsideClick: function () {
            let self = this;

            this.document.on('click.dropdown.outside.' + this.instance.uuid, function (e) {
                if (!$(e.target).closest(self.el).length && !$(e.target).closest(self.listItems).length) {
                    self._doToggle('close');
                }
            });
        },
    });

    $(document).ready(function ($) {
        $('[data-control="dropdown"]').tifyDropdown();
    });
});