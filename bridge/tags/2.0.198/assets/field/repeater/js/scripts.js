/* global jQuery */
"use strict";

jQuery(function ($) {
    $.widget('tify.tifyRepeater', {
        widgetEventPrefix: 'repeater:',
        id: undefined,
        xhr: undefined,
        options: {
            classes: {
                listItem: 'FieldRepeater-item',
                listItemContent: 'FieldRepeater-itemContent',
                listItemRemove: 'FieldRepeater-itemRemove ThemeButton--remove',
                listItemSort: 'FieldRepeater-itemSort',
                listItems: 'FieldRepeater-items',
                trigger: 'FieldSelectJs-trigger'
            },
            removable: true,
            sortable: true
        },

        // INITIALISATION
        // -------------------------------------------------------------------------------------------------------------
        // Instanciation de l'élément.
        _create: function () {
            this.instance = this;

            this.el = this.element;

            this.id = this.el.data('id');

            this.flags = {
                isRemovable: true,
                isSortable: true
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
            this.flags.isRemovable = !!this.option('removable');
            this.flags.isSortable = !!this.option('sortable');
        },

        // Initialisation des agents de contrôle.
        _initControls: function () {
            this._initControlElement();
            this._initControlListItems();
            this._initControlTrigger();
        },

        // Initialisation du controleur principal.
        _initControlElement: function () {
            this.el.attr('aria-sortable', this.flags.isSortable);
            this.el.attr('aria-removable', this.flags.isRemovable);
        },

        // Initialisation du controleur principal.
        _initControlListItems: function () {
            let self = this;

            this.listItems = $('[data-control="repeater.items"]', this.el);

            if (!this.listItems.length) {
                this.listItems = $('<ul data-control="repeater.items"/>').appendTo(this.el);
            }
            this.listItems.addClass(this.option('classes.listItems'));

            this.listItem = $('[data-control="repeater.item"]', this.listItem);
            if (this.listItem.length) {
                this.listItem.each(function () {
                    self._setItem($(this));
                });
            }

            if (this.flags.isSortable) {
                this.option('sortable', $.extend(
                    {
                        handle: '[data-control="repeater.item.sort"]',
                        containment: this.listItems,
                        axis: 'Y',
                        update: function () {
                            //self._doSort();
                        },
                        start: function (e, ui) {
                            ui.placeholder.height(ui.item.height());
                        }
                    },
                    this.option('sortable')
                ));
                this.sortable = this.listItems.sortable(this.option('sortable'));
            }
        },

        // Intialisation du controleur de déclenchement de la création d'un nouvel élément.
        _initControlTrigger: function () {
            this.trigger = $('[data-control="repeater.trigger"]', this.el);
            this._onAdd();
        },

        // GETTER
        // -------------------------------------------------------------------------------------------------------------
        _getAddedIndex: function () {
            if (!$('[data-control="repeater.item"]', this.el).length) {
                return 0;
            } else {
                let indexes = [];
                $('[data-control="repeater.item"]', this.el).each(function() {
                    indexes.push($(this).data('index'));
                });
                return (Math.max(...indexes) + 1);
            }
        },

        // SETTER
        // -------------------------------------------------------------------------------------------------------------
        // Définition d'un élément.
        _setItem: function ($item) {
            $item
                .addClass(this.option('classes.listItem'))
                .find('[data-control="repeater.item.content"]')
                .addClass(this.option('classes.listItemContent'));

            if (this.flags.isRemovable) {
                let $itemRemover = $('[data-control="repeater.item.remove"]', $item);
                if (!$itemRemover.length) {
                    $itemRemover = $('<a href="#" data-control="repeater.item.remove"/>').appendTo($item);
                }
                $itemRemover.addClass(this.option('classes.listItemRemove'));

                this._onItemRemove($item);
            }

            if (this.flags.isSortable) {
                let $itemSorter = $('[data-control="repeater.item.sort"]', $item);
                if (!$itemSorter.length) {
                    $itemSorter = $('<span data-control="repeater.item.sort"/>').text('...').appendTo($item);
                }
                $itemSorter.addClass(this.option('classes.listItemSort'));
            }
        },

        // ACTIONS
        // -------------------------------------------------------------------------------------------------------------
        // Ajout d'un nouvel élément.
        _doAdd: function () {
            let self = this;

            if (this.xhr !== undefined) {
                return;
            }

            let $items = $('[data-control="repeater.items"]', this.el),
                ajax = $.extend(
                    true,
                    this.option('ajax'),
                    {
                        data: {
                            index: this._getAddedIndex(),
                            count: $('[data-control="repeater.item"]', this.el).length
                        }
                    }
                );

            this.xhr = $.ajax(ajax)
                .done(function (resp) {
                    if (!resp.success) {
                        alert(resp.data);
                    } else {
                        let $item = $(resp.data).appendTo($items);

                        self._setItem($item);

                        self._trigger('add', null, $item);
                    }
                })
                .always(function () {
                    self.xhr = undefined;
                });
        },

        // Suppression d'un élément.
        _doRemove: function ($item) {
            let self = this;

            $item.fadeOut(function () {
                $(this).remove();

                self._trigger('remove', null, $item);
            });
        },

        //EVENTS
        // -------------------------------------------------------------------------------------------------------------
        // Activation de l'agent de contrôle d'ajout d'un nouvel élément.
        _onAdd: function () {
            let self = this;

            this.trigger.on('click.repeater.trigger.' + this.instance.uuid, function (e) {
                e.stopPropagation();
                e.preventDefault();

                self._doAdd();
            });
        },

        // Activation de l'agent de contrôle de suppression d'un élément.
        _onItemRemove: function ($item) {
            let self = this;

            $('[data-control="repeater.item.remove"]', $item).on('click.repeater.item.remove', function (e) {
                e.preventDefault();

                self._doRemove($item);
            });
        }
    });

    $(document).ready(function ($) {
        $('[data-control="repeater"]').tifyRepeater();
    });
});