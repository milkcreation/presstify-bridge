/* globals wp */

"use strict";

jQuery(function ($) {
    $.widget('tify.tifyMetaboxFileshare', {
        widgetEventPrefix: 'metabox-fileshare:',
        id: undefined,
        xhr: undefined,
        options: {
            classes: {
                listItem: 'MetaboxFileshare-item',
                listItemRemove: 'MetaboxFileshare-itemRemove ThemeButton--remove',
                listItemSort: 'MetaboxFileshare-itemSort',
                listItems: 'MetaboxFileshare-items',
                trigger: 'MetaboxFileshare-trigger button-secondary'
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

            this.listItems = $('[data-control="metabox-fileshare.items"]', this.el);

            if (!this.listItems.length) {
                this.listItems = $('<ul data-control="metabox-fileshare.items"/>').appendTo(this.el);
            }
            this.listItems.addClass(this.option('classes.listItems'));

            this.listItem = $('[data-control="metabox-fileshare.item"]', this.listItem);
            if (this.listItem.length) {
                this.listItem.each(function () {
                    self._setItem($(this));
                });
            }

            if (this.flags.isSortable) {
                this.option('sortable', $.extend(
                    {
                        handle: '[data-control="metabox-fileshare.item.sort"]',
                        containment: this.listItems,
                        axis: 'Y',
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
            this.trigger = $('[data-control="metabox-fileshare.trigger"]', this.el)
                .addClass(this.option('classes.trigger'));
            this._onCreate();
        },

        // SETTER
        // -------------------------------------------------------------------------------------------------------------
        // Définition d'un élément.
        _setItem: function ($item) {
            $item
                .addClass(this.option('classes.listItem'));

            if (this.flags.isRemovable) {
                let $itemRemover = $('[data-control="metabox-fileshare.item.remove"]', $item);
                if (!$itemRemover.length) {
                    $itemRemover = $('<a href="#" data-control="metabox-fileshare.item.remove"/>').appendTo($item);
                }
                $itemRemover.addClass(this.option('classes.listItemRemove'));

                this._onItemRemove($item);
            }

            if (this.flags.isSortable) {
                let $itemSorter = $('[data-control="metabox-fileshare.item.sort"]', $item);
                if (!$itemSorter.length) {
                    $itemSorter = $('<span data-control="metabox-fileshare.item.sort"/>').text('...').appendTo($item);
                }
                $itemSorter.addClass(this.option('classes.listItemSort'));
            }

        },

        // ACTIONS
        // -------------------------------------------------------------------------------------------------------------
        // Création d'un nouvel élément.
        _doCreate: function () {
            let self = this;

            if (this.xhr !== undefined) {
                return;
            }

            /*
            var item_name = $(this).data('item_name');
            var target = $(this).data('target');
            var max = $(this).data('max');

            if (max > 0 && $('li', target).length >= max) {
                alert('Nombre maximum de fichier atteint');
                return false;
            }
            */

            let args = this.option('wp_media');

            this.wpmedia = wp.media.frames.tify_taboox_fileshare_frame = wp.media(args);

            this.wpmedia.on('select', function () {
                let selection = self.wpmedia.state().get('selection');
                selection.map(function (attachment) {

                    let $items = $('[data-control="metabox-fileshare.items"]', self.el),
                        index = $('[data-control="metabox-fileshare.item"]', self.el).length,
                        item = attachment.toJSON(),
                        ajax = $.extend(true, self.option('ajax'), {data : {index: index, value:item.id}});

                    self.xhr = $.ajax(ajax)
                        .done(function (resp) {
                            if (!resp.success) {
                                alert(resp.data);
                            } else {
                                let $item = $(resp.data).appendTo($items);

                                self._setItem($item);

                                self._trigger('create', null, $item);
                            }
                        })
                        .always(function () {
                            self.xhr = undefined;
                        });
                });
            });

            this.wpmedia.open();
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
        // Activation de l'agent de contrôle de création d'un nouvel élément.
        _onCreate: function () {
            let self = this;

            this.trigger.on('click.metabox-fileshare.trigger.' + this.instance.uuid, function (e) {
                e.stopPropagation();
                e.preventDefault();

                self._doCreate();
            });
        },

        // Activation de l'agent de contrôle de suppression d'un élément.
        _onItemRemove: function ($item) {
            let self = this;

            $('[data-control="metabox-fileshare.item.remove"]', $item).on(
                'click.metabox-fileshare.item.remove', function (e) {
                    e.preventDefault();

                    self._doRemove($item);
                }
            );
        }
    });

    $(document).ready(function ($) {
        $('[data-control="metabox-fileshare"]').tifyMetaboxFileshare();
    });
});