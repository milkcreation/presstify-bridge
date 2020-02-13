/* global tify */
'use strict';

import jQuery from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/widgets/sortable';

jQuery(function ($) {
    // Attribution de la valeur à l'élément.
    let _hook = $.valHooks.div;

    $.valHooks.div = {
        get: function (elem) {
            if (typeof $(elem).tifySelectJs('instance') === 'undefined') {
                return _hook && _hook.get && _hook.get(elem) || undefined;
            }
            return $(elem).data('value');
        },
        set: function (elem, value) {
            if (typeof $(elem).tifySelectJs('instance') === 'undefined') {
                return _hook && _hook.set && _hook.set(elem, value) || undefined;
            }
            $(elem).data('value', value);
        }
    };

    $.widget('tify.tifySelectJs', {
        widgetEventPrefix: 'select-js:',
        id: undefined,
        options: {
            autocomplete: false,
            ajax: {},
            classes: {
                autocompleteInput: 'FieldSelectJs-autocomplete',
                handler: 'FieldSelectJs-handler',
                picker: 'FieldSelectJs-picker',
                pickerFilter: 'FieldSelectJs-pickerFilter',
                pickerLoader: 'FieldSelectJs-pickerLoader',
                pickerItem: 'FieldSelectJs-pickerItem',
                pickerItems: 'FieldSelectJs-pickerItems',
                pickerMore: 'FieldSelectJs-pickerMore',
                selection: 'FieldSelectJs-selection',
                selectionItem: 'FieldSelectJs-selectionItem',
                selectionItemRemove: 'FieldSelectJs-selectionItemRemove',
                selectionItemSort: 'FieldSelectJs-selectionItemSort',
                trigger: 'FieldSelectJs-trigger',
                triggerHandler: 'FieldSelectJs-triggerHandler'
            },
            disabled: false,
            duplicate: false,
            max: -1,
            multiple: false,
            picker: {
                appendTo: '',
                attrs: [],
                delta: {
                    top: 0,
                    left: 0,
                    width: 0
                },
                filter: false,
                loader: '',
                more: '+',
                placement: 'clever'
            },
            removable: true,
            sortable: {},
            trigger: true
        },

        // Instanciation de l'élément.
        _create: function () {
            this.instance = this;

            this.el = this.element;

            this.id = this.el.data('id');

            this.flags = {
                hasAutocomplete: false,
                hasFilter: false,
                hasSelection: false,
                ajaxQuery: false,
                hasTrigger: true,
                isComplete: false,
                isDisabled: true,
                isDuplicable: false,
                isMultiple: false,
                isOpen: false,
                isRemovable: true,
                isSortable: false,
                max: -1,
                mustEnabled: true,
                onAutocomplete: false,
                page: 1,
                cache: undefined
            };

            this.items = [];

            this.selected = [];

            this._initOptions();
            this._initFlags();
            this._initControls();
            this._initItems();

            if (this.flags.isDisabled) {
                this._doDisable();
            } else {
                this._doEnable();
            }
            this._trigger('loaded');
        },

        // INITIALISATION
        // -------------------------------------------------------------------------------------------------------------
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
            this.flags.hasAutocomplete = !!this.option('autocomplete');
            this.flags.ajaxQuery = (this.option('ajax') !== false);
            this.flags.hasTrigger = !!this.option('trigger');
            this.flags.isDisabled = !!this.option('disabled');
            this.flags.isMultiple = !!this.option('multiple');
            this.flags.isDuplicable = !!(this.option('duplicate') && this.flags.isMultiple);
            this.flags.isRemovable = !!(this.option('removable') && this.flags.isMultiple);
            this.flags.isSortable = (this.option('sortable') !== false) && this.flags.isMultiple;
            this.flags.max = this.flags.isMultiple ? parseInt(this.option('max')) : -1;
        },

        // Initialisation des agents de contrôle.
        _initControls: function () {
            this._initControlElement();
            this._initControlHandler();
            this._initControlTrigger();
            this._initControlPicker();
            this._initControlSelection();
            if (this.flags.hasAutocomplete) {
                this._initControlAutocomplete();
            }
        },

        // Initialisation du controleur d'autocompletion.
        _initControlAutocomplete: function () {
            this.autocompleteInput = $('<input data-control="select-js.autocomplete" autocomplete="off"/>')
                .prependTo(this.trigger)
                .addClass(this.option('classes.autocompleteInput'));

            if (typeof this.option('autocomplete') !== 'object') {
                this.option('autocomplete', {});
            }

            this.flags.hasAutocomplete = true;
            this.el.attr('aria-autocomplete', true);

            if (this.flags.isMultiple) {
                this.selection.insertAfter(this.trigger);
            }
        },

        // Initialisation du controleur principal.
        _initControlElement: function () {
            this.el.attr('aria-selection', this.flags.hasSelection);
            this.el.attr('aria-multiple', this.flags.isMultiple);
            this.el.attr('aria-sortable', this.flags.isSortable);
            this.el.attr('aria-duplicable', this.flags.isDuplicable);
            this.el.attr('aria-arrow', this.flags.hasTrigger);
        },

        // Initialisation du controleur de traitement.
        _initControlHandler: function () {
            this.handler = $('[data-control="select-js.handler"]', this.el)
                .addClass(this.option('classes.handler'));
        },

        // Initialisation du controleur de la liste de selection.
        _initControlPicker: function () {
            this.picker = $('[data-control="select-js.picker"]', this.el);

            if (!this.picker.length) {
                this.picker = $('<div data-control="select-js.picker"/>').appendTo(this.el);
            }

            this.picker
                .addClass(this.option('classes.picker'))
                .attr('aria-multiple', this.flags.isMultiple)
                .attr('aria-duplicable', this.flags.isDuplicable);

            this.pickerItems = $('[data-control="select-js.picker.items"]', this.picker);

            if (!this.pickerItems.length) {
                this.pickerItems = $('<ul data-control="select-js.picker.items"/>')
                    .appendTo(this.picker);
            }

            this.pickerItems.addClass(this.option('classes.pickerItems'));

            let $appendTo = $(this.option('picker.appendTo'));
            if (!$appendTo.length) {
                $appendTo = $('body');
            }
            this.picker
                .appendTo($appendTo);

            if (this.option('picker.filter')) {
                this.flags.hasFilter = true;
                this.picker.attr('aria-filter', true);

                this.pickerFilter = $('<input data-control="select-js.picker.filter" autocomplete="off"/>')
                    .prependTo(this.picker)
                    .addClass(this.option('classes.pickerFilter'));
            }

            this.pickerLoader = $('<div data-control="select-js.picker.loader"/>')
                .html(this.option('picker.loader'))
                .prependTo(this.picker)
                .addClass(this.option('classes.pickerLoader'));

            if (this.flags.ajaxQuery) {
                this.pickerMore = $('<a href="#" data-control="select-js.picker.more"/>')
                    .html(this.option('picker.more'))
                    .prependTo(this.picker)
                    .addClass(this.option('classes.pickerMore'));
            }
        },

        // Initialisation du controleur de liste des éléments sélectionnés.
        _initControlSelection: function () {
            let self = this;

            this.selection = $('[data-control="select-js.selection"]', this.el);

            if (!this.selection.length) {
                this.selection = $('<ul data-control="select-js.selection"/>');
            }

            this.selection.appendTo(this.trigger)
                .addClass(this.option('classes.selection'));

            if (this.flags.isSortable) {
                let sortable = $.extend(
                    {
                        handle: '[data-control="select-js.selection.item.sort"]',
                        containment: 'parent',
                        update: function () {
                            self._doSort();
                        }
                    },
                    this.option('sortable')
                );
                this.option('sortable', sortable);

                this.sortable = this.selection.sortable(sortable);
            } else {
                this.sortable = undefined;
            }
        },

        // Initialisation du controleur de déclenchement d'affichage de la liste selection.
        _initControlTrigger: function () {
            this.trigger = $('<div data-control="select-js.trigger"/>')
                .appendTo(this.el)
                .addClass(this.option('classes.trigger'));

            this.triggerHandler = $('<a href="#" data-control="select-js.trigger.handler"/>')
                .appendTo(this.trigger)
                .addClass(this.option('classes.triggerHandler'));
        },

        // Initialisation des éléments de listes.
        _initItems: function () {
            let self = this,
                $handlerItems = $('option', this.handler);

            if ($handlerItems.length) {

                let selected = [];
                $handlerItems.each(function (index) {
                    if ($(this).is(':selected')) {
                        selected.push($(this).val());
                    }

                    self._setItem(index, $(this).val(), $(this).text());
                });
                this._handlerFlushItems();
                this._selectionFlushItems();

                selected.forEach(function (value) {
                    self._selectedAdd(value);
                });
            } else {
                let items = tify[this.id].items || [],
                    isSelected = false;

                items.forEach(function (item, index) {
                    let value = item.value.toString();

                    if (Object.values(item.attrs).indexOf('selected') >= 0) {
                        if (self.flags.isMultiple) {
                            self._selectedAdd(value);
                        } else if (!isSelected) {
                            isSelected = true;
                            self._selectedSet(value);
                        }
                    }

                    self._setItem(index, value, item.content, item.selection, item.picker);
                });
            }

            this.items.forEach(function (item, index) {
                self._pickerAddItem(index);

                if (self._selectedHas(item.value)) {
                    self._handlerAddItem(index);
                    self._selectionAddItem(index);
                    self._pickerAddSelected(index);
                }
            });

            this.flags.mustEnabled = false;

            this.handler.val(this.selected);
            this.el.data('value', this.selected);
        },

        // SETTER
        // -------------------------------------------------------------------------------------------------------------
        // Définition d'un élément du controleur de traitement.
        _setItem: function (index, value, content, selection, picker) {
            let item = {
                content: content,
                handler: undefined,
                index: index,
                picker: undefined,
                selection: undefined,
                value: value
            };
            item.handler = this._setItemHandler(undefined, item);
            item.picker = this._setItemPicker(picker, item);
            item.selection = this._setItemSelection(selection, item);

            this.items[index] = item;
        },

        // Définition d'un élément du controleur de traitement.
        _setItemHandler: function (content, item) {
            return $('<option/>')
                .attr('value', item.value)
                .attr('data-index', item.index)
                .text(content ? content : item.content);
        },

        // Définition d'un élément du controleur de la liste de selection.
        _setItemPicker: function (content, item) {
            let $itemPicker = $('[data-control="select-js.picker.item"][data-value="' + item.value + '"]', this.picker);

            if (!$itemPicker.length) {
                $itemPicker = $('<li data-control="select-js.picker.item"/>')
                    .attr('data-value', item.value)
                    .html(content ? content : item.content);
            }

            return $itemPicker
                .attr('data-index', item.index)
                .addClass(this.option('classes.pickerItem'));
        },

        // Définition d'un élément du controleur de la liste des éléments sélectionnés.
        _setItemSelection: function (content, item) {
            let $selectionItem = $(
                '[data-control="select-js.selection.item"][data-value="' + item.value + '"]',
                this.selection
            );

            if (!$selectionItem.length) {
                $selectionItem = $('<li data-control="select-js.selection.item"/>')
                    .attr('data-value', item.value)
                    .html(content ? content : item.content);
            }

            $selectionItem
                .attr('data-index', item.index)
                .attr('aria-removable', this.flags.isRemovable)
                .attr('aria-sortable', this.flags.isSortable)
                .addClass(this.option('classes.selectionItem'));

            if (this.flags.isRemovable) {
                $('<a href="#" data-control="select-js.selection.item.remove"/>')
                    .appendTo($selectionItem)
                    .addClass(this.option('classes.selectionItemRemove'))
                    .text('×');
            }

            if (this.flags.isSortable) {
                if (this.option('sortable.handle')) {
                    $('<span data-control="select-js.selection.item.sort"/>')
                        .appendTo($selectionItem)
                        .addClass(this.option('classes.selectionItemSort'))
                        .text('...');
                }
            }

            return $selectionItem;
        },

        // Définition de la requête de récupération des éléments complète.
        _setQueryItemsComplete: function () {
            this.flags.isComplete = true;
            this.picker.attr('aria-complete', true);
        },

        // GETTER
        // -------------------------------------------------------------------------------------------------------------
        // Récupération de la liste des éléments.
        _getItems: function () {
            return this.items;
        },

        // Récupération d'un élément.
        _getItem: function (index) {
            return this.items[index];
        },

        // Récupération de l'indice d'un élément selon sa valeur
        _getItemIndex: function (value) {
            let index = this.items.findIndex(function (item) {
                return item.value === value;
            });

            return (index > -1) ? index : undefined;
        },

        // Arguments de requête Ajax de récupération des éléments.
        _getQueryArgs: function () {
            return {
                page: this.flags.page,
                per_page: this.option('ajax.data.args.per_page') || 20,
                term: this.flags.hasAutocomplete ? this.autocompleteInput.val().toString() : ''
            };
        },

        // MODIFIER
        // -------------------------------------------------------------------------------------------------------------
        // Ajout d'un élément au controleur de traitement.
        _handlerAddItem(index) {
            if (!this.handler.find(this.items[index].handler).length) {
                this.items[index].handler.appendTo(this.handler);
            }
        },

        // Suppression d'un élément du controleur de traitement.
        _handlerFlushItems() {
            this.handler.empty();
        },

        // Suppression d'un élément du controleur de traitement.
        _handlerRemoveItem(index) {
            this.items[index].handler.remove();
        },

        // Ajout d'un élement à la liste de sélection.
        _pickerAddItem(index) {
            let $pickerItem = this.pickerItems.find(this.items[index].picker);

            if (!$pickerItem.length) {
                $pickerItem = this.items[index].picker.appendTo(this.pickerItems);
            }

            this._onPickerItemClick($pickerItem);

        },

        // Ajout d'une selection à la liste de sélection.
        _pickerAddSelected(index) {
            this.items[index].picker.attr('aria-selected', true);
        },

        // Désactivation d'un élément de la liste de sélection.
        _pickerDisableItem(index) {
            if (this.pickerItems.find(this.items[index].picker).length) {
                this._offPickerItemClick(this.items[index].picker);
            }
        },

        // Suppression d'une selection à la liste de sélection.
        _pickerRemoveSelected(index) {
            this.items[index].picker.attr('aria-selected', false);
        },

        // Vidage de l'ensemble des sélections de la liste de sélection.
        _pickerFlushSelected() {
            $.each(this.items, function (u, v) {
                v.picker.attr('aria-selected', false);
            });
        },

        // Ajout de la sélection d'un élement.
        _selectedAdd: function (value) {
            let index = this.selected.indexOf(value.toString());

            if (index === -1) {
                this.selected.push(value.toString());
            }
        },

        // Vidage de la liste des éléments selectionnés.
        _selectedFlush: function () {
            this.selected = [];
        },

        // Vérification si un élément est selectionné.
        _selectedHas: function (value) {
            return this.selected.indexOf(value.toString()) !== -1;
        },

        // Vérification si le nombre maximum de valeur est atteint
        _selectedMaxAttempt: function () {
            return ((this.flags.max > 0) && (this.flags.max < this.selected.length));
        },

        // Définition de la liste des éléments selectionnés.
        _selectedSet: function (value) {
            this.selected = value.toString().split(',');
        },

        // Mise à jour de la sélection d'un élement.
        _selectedUpdate: function (value) {
            let index = this.selected.indexOf(value.toString());

            if (index === -1) {
                this.selected.push(value.toString());
                return 1;
            } else {
                this.selected.splice(index, 1);
                return 0;
            }
        },

        // Suppression de la sélection d'un élement.
        _selectedRemove: function (value) {
            let index = this.selected.indexOf(value.toString());

            if (index > -1) {
                this.selected.splice(index, 1);
            }
        },

        // Ajout d'une selection à la liste des éléments sélectionnés.
        _selectionAddItem(index) {
            if (!this.selection.find(this.items[index].selection).length) {
                let $item = this.items[index].selection.appendTo(this.selection).attr('aria-selected', true);

                if (this.flags.isRemovable) {
                    this._onSelectionItemRemoveClick($item);
                }

                if (!this.flags.isMultiple && this.flags.hasAutocomplete) {
                    this.autocompleteInput.val($('<span/>').html(this.items[index].content).text());
                }
            }
        },

        // Désactivation d'une selection à la liste des éléments sélectionnés.
        _selectionDisableItem(index) {
            if (this.selection.find(this.items[index].selection).length) {
                if (this.flags.isRemovable) {
                    this._offSelectionItemRemoveClick(this.items[index].selection);
                }
            }
        },

        // Vidage de l'ensemble des sélections de la liste des éléments sélectionnés.
        _selectionFlushItems() {
            this.selection.empty();
        },

        // Suppression d'une selection de la liste des éléments sélectionnés.
        _selectionRemoveItem(index) {
            this.items[index].selection.remove();
        },

        // ACTIONS
        // -------------------------------------------------------------------------------------------------------------
        // Abandon de la requête de récupération Ajax.
        _doAjaxAbort: function () {
            if (this.xhr !== undefined) {
                this.xhr.abort();
            }
        },

        // Récupération de la liste des éléments via Ajax.
        _doAjaxQuery: function () {
            let self = this;

            if (this.flags.ajaxQuery && !this.flags.isComplete) {
                this._doAjaxAbort();

                this._doPickerLoaderShow();

                this.option(
                    'ajax.data.args',
                    $.extend(
                        this.option('ajax.data.args') || {},
                        this._getQueryArgs()
                    )
                );

                this.xhr = $.ajax(this.option('ajax'))
                    .done(function (data) {
                        if (data.length) {
                            $.each(data, function (u, attrs) {
                                let value = attrs.value.toString(),
                                    index = self._getItemIndex(value) || self.items.length;

                                if (self.items.length === index) {
                                    self._setItem(
                                        index,
                                        attrs.value.toString(),
                                        attrs.content,
                                        attrs.selection,
                                        attrs.picker
                                    );
                                }
                                self._pickerAddItem(index);
                            });

                            if (data.length < self.option('ajax.data.args.per_page')) {
                                self._setQueryItemsComplete();
                                self._offPickerMoreQueryItems();
                            } else {
                                self._onPickerMoreQueryItems();
                                self._doPageIncrease();
                            }
                        } else {
                            self._setQueryItemsComplete();
                            self._offPickerMoreQueryItems();
                        }
                    }).always(function () {
                        self._doPickerLoaderHide();
                        self._doAjaxAbort();
                    });
            }
        },

        // Récupération des données en cache
        _doCacheRestore: function () {
            if (this.flags.cache !== undefined) {
                this.flags.isComplete = this.flags.cache.complete;
                this.flags.page = this.flags.cache.page;
                this.picker.attr('aria-complete', this.flags.isComplete);
            }
        },

        // Modification d'un élément dans la liste de selection.
        _doChange: function (index) {
            let item = this._getItem(index);

            if (this.flags.isMultiple) {
                if (this._selectedUpdate(item.value)) {
                    if (this._selectedMaxAttempt()) {
                        this._selectedRemove(item.value);
                        return alert(this.option('errors.max_attempt') || 'value max attempt.');
                    } else {
                        this._handlerAddItem(item.index);
                        this._selectionAddItem(item.index);
                        this._pickerAddSelected(item.index);
                    }
                } else {
                    this._handlerRemoveItem(item.index);
                    this._selectionRemoveItem(item.index);
                    this._pickerRemoveSelected(item.index);
                }
            } else {
                this._selectedSet(item.value);
                this._handlerFlushItems();
                this._handlerAddItem(item.index);
                this._selectionFlushItems();
                this._selectionAddItem(item.index);
                this._pickerFlushSelected();
                this._pickerAddSelected(item.index);

                this._doClose();
            }
            this._doPickerPosition();
            this._doHighlight(item.value);

            this.handler.val(this.selected);
            this.el.data('value', this.selected);

            this._trigger('change', null, item);
        },

        // Fermeture de la liste de selection.
        _doClose: function () {
            this._offOutsideClick();

            this.flags.isOpen = false;
            this.el.attr('aria-open', false);
            this.picker.attr('aria-open', false);
        },

        // Désactivation du controleur.
        _doDisable: function () {
            let self = this;

            this.flags.isDisabled = true;
            this.el.attr('aria-disabled', true);
            this.handler.prop('disabled', true);

            this._offTriggerHandlerClick();

            this.items.forEach(function (item, index) {
                self._pickerDisableItem(index);
                self._selectionDisableItem(index);
            });

            if (this.flags.isSortable) {
                this.selection.sortable('disable');
            }
        },

        // Activation du controleur.
        _doEnable: function () {
            let self = this;

            this.flags.isDisabled = false;
            this.el.attr('aria-disabled', false);
            this.handler.prop('disabled', false);

            this._onTriggerHandlerClick();

            if (this.flags.mustEnabled) {
                this.items.forEach(function (item, index) {
                    self._pickerAddItem(index);

                    if (self._selectedHas(item.value)) {
                        self._handlerAddItem(index);
                        self._selectionAddItem(index);
                        self._pickerAddSelected(index);
                    }
                });

                if (this.flags.isSortable) {
                    this.selection.sortable('enable');
                }
            }

            if (this.flags.hasFilter) {
                this._onPickerFilterKeyup();
            }

            if (this.flags.hasAutocomplete) {
                this._onAutocomplete();
            }
        },

        // Mise en avant des éléments dans la liste des éléments sélectionnés.
        _doHighlight: function (value) {
            $('[data-control="select-js.selection.item"][data-value="' + value + '"]', this.selection)
                .attr('aria-highlight', true)
                .one(
                    'webkitAnimationEnd oanimationend msAnimationEnd animationend',
                    function () {
                        $(this).attr('aria-highlight', false);
                    }
                );
        },

        // Augmentation de la pagination.
        _doPageIncrease: function () {
            this.flags.page++;
        },

        // Masquage de l'indicateur de préchargement.
        _doPickerLoaderHide: function () {
            this.picker.attr('aria-loader', false);
        },

        // Affichage de l'indicateur de préchargement.
        _doPickerLoaderShow: function () {
            this.picker.attr('aria-loader', true);
        },

        // Positionnement de la liste de selection dans le DOM.
        _doPickerPosition: function () {
            let offset = {},
                $base = this.el, // this.el || this.trigger
                placement = this.option('picker.placement');

            $.extend(
                offset,
                $base.offset(),
                {width: $base.outerWidth()}
            );

            if (this.option('picker.delta.top')) {
                offset.top += this.option('picker.delta.top');
            }
            if (this.option('picker.delta.left')) {
                offset.left += this.option('picker.delta.left');
            }
            if (this.option('picker.delta.width')) {
                offset.width += this.option('picker.delta.width');
            }

            // @todo Adminbar test
            // let $html = $('html');
            //offset.top += $html.outerHeight(true) - $html.height();

            if (placement === 'clever') {
                placement = (
                    (this.window.outerHeight() + this.window.scrollTop()) < offset.top + this.picker.outerHeight()
                ) ? 'top' : 'bottom';
            }

            let borderDelta = ($base.outerHeight() - $base.innerHeight()) / 2;

            switch (placement) {
                case 'top' :
                    offset.top -= this.picker.outerHeight() - borderDelta;
                    break;
                case 'bottom' :
                    offset.top += $base.outerHeight() - borderDelta;
                    break;
            }

            this.picker.css(offset);
        },

        // Ouverture de la liste de selection.
        _doOpen: function () {
            this.flags.isOpen = true;
            this.el.attr('aria-open', true);
            this.picker.attr('aria-open', true);

            this._onOutsideClick();

            this._doPickerPosition();

            if (this.flags.ajaxQuery && !this.flags.onAutocomplete) {
                this._doAjaxQuery();
            }

            if (this.flags.hasFilter) {
                this.pickerFilter.focus();
            }
        },

        // Suppression d'un élément de la liste de selection.
        _doRemove: function (index) {
            let item = this._getItem(index);

            this._selectedRemove(item.value);
            this._handlerRemoveItem(item.index);
            this._selectionRemoveItem(item.index);
            this._pickerRemoveSelected(item.index);

            this.handler.val(this.selected);
            this.el.data('value', this.selected);
        },

        // Réordonnancement d'un élément de la liste de selection.
        _doSort: function () {
            let self = this;

            self._handlerFlushItems();
            $('[data-control="select-js.selection.item"]', self.selection).each(function () {
                self._handlerAddItem($(this).data('index'));
            });
        },

        //EVENTS
        // -------------------------------------------------------------------------------------------------------------
        // Activation de la saisie par autocompletion.
        _onAutocomplete: function () {
            let self = this;

            this.autocompleteInput
                .focus(function () {
                    $(this).on('keyup.select-js.autocomplete.' + self.instance.uuid, function () {
                        if (!self.flags.isMultiple) {
                            self._handlerFlushItems();
                            self._selectedFlush();
                        }

                        if ($(this).val()) {
                            if (self.flags.cache === undefined) {
                                self.flags.cache = {complete: self.flags.isComplete, page: self.flags.page};
                            }
                            self.flags.isComplete = false;
                            self.flags.page = 1;
                            self.flags.onAutocomplete = true;

                            self.pickerItems.empty();

                            if (self.timeout !== undefined) {
                                clearTimeout(self.timeout);
                            }

                            self._doAjaxAbort();

                            self.timeout = setTimeout(function () {
                                self._doOpen();
                                self._doAjaxQuery();
                                self.xhr.done(function (data) {
                                    if (!data.length) {
                                        self._doClose();
                                    }
                                });
                            }, 1000);
                        } else {
                            self._doClose();
                            self._doAjaxAbort();
                            self._doPickerLoaderHide();
                            self.flags.onAutocomplete = false;
                            self._doCacheRestore();

                            self.items.forEach(function (item) {
                                self._pickerAddItem(item.index);
                            });
                        }
                    });
                })
                .focusout(function () {
                    self._doPickerLoaderHide();
                    self._doCacheRestore();

                    $(this).off('keyup.select-js.autocomplete.' + self.instance.uuid);
                });
        },

        // Activation du filtrage de la liste de selection.
        _onPickerFilterKeyup: function () {
            let self = this;

            this.pickerFilter.on('keyup.select-js.picker.filter.' + this.instance.uuid, function () {
                let term = $(this).val().toString(),
                    regex = new RegExp(term, 'i');

                $('[data-control="select-js.picker.item"]', self.pickerItems).each(function () {
                    if ($(this).data('value').toString().match(regex) || $(this).html().match(regex)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        },

        // Activation du clic sur les élements de la liste de sélection.
        _onPickerItemClick: function ($pickerItem) {
            let self = this;

            $pickerItem.on(
                'click.select-js.picker.item.' + this.instance.uuid,
                function (e) {

                    if ($(this).is(':not([aria-disabled="true"])')) {
                        e.preventDefault();

                        $(this).attr('aria-highlight', true)
                            .one(
                                'webkitAnimationEnd oanimationend msAnimationEnd animationend',
                                function () {
                                    $(this).attr('aria-highlight', false);
                                }
                            );
                        self._doChange($(this).data('index'));
                    }
                }
            );
        },

        // Désactivation du clic sur les élements de la liste de sélection.
        _offPickerItemClick: function ($pickerItem) {
            $pickerItem.off('click.select-js.picker.item.' + this.instance.uuid);
        },

        // Activation de la récupération d'éléments supplémentaires dans la liste de selection.
        _onPickerMoreQueryItems: function () {
            let self = this;

            this.pickerItems.on('scroll.select-js.picker.items.' + this.instance.uuid, function () {
                let top = $(this).prop('scrollHeight') - $(this).innerHeight() - $(this).scrollTop();
                if (top < 20) {
                    self._doAjaxQuery();
                }
            });

            this.pickerMore.on('click.select-js.picker.more.' + this.instance.uuid, function (e) {
                e.preventDefault();

                self._doAjaxQuery();
            });
        },

        // Désactivation de la récupération d'éléments supplémentaires dans la liste de selection.
        _offPickerMoreQueryItems: function () {
            this.pickerItems.off('scroll.select-js.picker.items.' + this.instance.uuid);
            this.pickerMore.off('click.select-js.picker.more.' + this.instance.uuid);
        },

        // Activation de la suppression d'un éléments séléctionnés.
        _onSelectionItemRemoveClick: function ($selectionItem) {
            let self = this;

            $selectionItem.find('[data-control="select-js.selection.item.remove"]').on(
                'click.select-js.selection.item.remove.' + this.instance.uuid, function (e) {
                    e.preventDefault();

                    self._doRemove($(this).closest('[data-control="select-js.selection.item"]').data('index'));
                });
        },

        // Désactivation de la suppression d'un éléments séléctionnés.
        _offSelectionItemRemoveClick: function ($selectionItem) {
            $selectionItem.find('[data-control="select-js.selection.item.remove"]').off(
                'click.select-js.selection.item.remove.' + this.instance.uuid
            );
        },

        // Activation du clic sur le controleur d'affichage de la liste de sélection.
        _onTriggerHandlerClick: function () {
            let self = this;

            this.triggerHandler.on('click.select-js.trigger.handler.' + this.instance.uuid, function (e) {
                e.preventDefault();

                if (self.flags.isOpen) {
                    self._doClose();
                } else {
                    self._doOpen();
                }
            });
        },

        // Désactivation du clic sur le controleur d'affichage de la liste de sélection.
        _offTriggerHandlerClick: function () {
            this.triggerHandler.off('click.select-js.trigger.handler.' + this.instance.uuid);
        },

        // Activation du clic en dehors de la liste de sélection.
        _onOutsideClick: function () {
            let self = this;

            this.document.on('click.select-js.outside.' + this.instance.uuid, function (e) {
                if (!$(e.target).closest(self.el).length && !$(e.target).closest(self.picker).length) {
                    self._doClose();
                }
            });
        },

        // Désactivation du clic en dehors de la liste de sélection.
        _offOutsideClick: function () {
            this.document.off('click.select-js.outside.' + this.instance.uuid);
        },

        // ACCESSEURS
        // -------------------------------------------------------------------------------------------------------------
        /**
         * Ajout d'une valeur à la liste de selection.
         *
         * @param value
         *
         * @uses $(selector).tifySelectJs('change', {value});
         */
        change: function (value) {
            let index = this._getItemIndex(value);

            if (index !== undefined) {
                this._doChange(index);
            }
        },

        /**
         * Fermeture de la liste de selection.
         *
         * @uses $(selector).tifySelectJs('close');
         */
        close: function () {
            this._doClose();
        },

        /**
         * Destruction du controleur.
         *
         * @uses $(selector).tifySelectJs('destroy');
         */
        destroy: function () {
            this.el.remove();
            this.picker.remove();
        },

        /**
         * Désactivation du controleur.
         *
         * @uses $(selector).tifySelectJs('disable');
         */
        disable: function () {
            this._doDisable();
        },

        /**
         * Activation du controleur.
         *
         * @uses $(selector).tifySelectJs('enable');
         */
        enable: function () {
            this._doEnable();
        },

        /**
         * Ouverture de la liste de selection.
         *
         * @uses $(selector).tifySelectJs('open');
         */
        open: function () {
            this._doOpen();
        },

        /**
         * Récupération de l'instance du widget d'ordonnancement des éléments.
         *
         * @uses $(selector).tifySelectJs('sortable');
         */
        sortable: function () {
            return this.sortable;
        }
    });

    $(document).ready(function ($) {
        $('[data-control="select-js"]').tifySelectJs();

        $.tify.observe('[data-control="select-js"]', function (i, target) {
            $(target).tifySelectJs();
        });
    });
});