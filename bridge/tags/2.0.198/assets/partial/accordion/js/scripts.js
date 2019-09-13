"use strict";

!(function ($) {
    $.widget('tify.tifyaccordion', {
        options: {
            multiple: false,
            triggered: false
        },
        // Instanciation de l'élément.
        _create: function () {
            this.instance = this;

            this.el = this.element;

            this.id = $(this.el).data('id');

            this._initOptions();

            this._initElement();

            this._initTrigger();

            this._initOpened();
        },

        // INITIALISATION
        // -------------------------------------------------------------------------------------------------------------
        // Initialisation des attributs de configuration.
        _initOptions: function () {
            $.extend(
                true,
                this.options,
                (tify[this.id] !== undefined && tify[this.id].options !== undefined) ? tify[this.id].options : {},
                this.el.data('options') && $.parseJSON(decodeURIComponent(this.el.data('options'))) || {}
            );
        },

        _initElement: function () {

        },
        _initTrigger: function () {
            let self = this;

            $('[data-control="accordion.item"]:has( > [data-control="accordion.items"])', this.el).each(function () {

                let $trigger = $('<span/>')
                    .addClass('Accordion-itemTrigger')
                    .data('control', 'accordion.item.trigger');

                if (self.option('triggered')) {
                    $trigger.prependTo($('> [data-control="accordion.item.content"]', this));
                } else {
                    $trigger.appendTo($('> [data-control="accordion.item.content"]', this));
                }

                self._onTriggerClick($trigger);
            });
        },
        _initOpened: function () {
            $('[data-control="accordion.items"]:has(> [data-control="accordion.item"][aria-open="true"])', this.el).each(function () {
                $(this).css('max-height', '100%');
                $('> [data-control="accordion.item"][aria-open="true"] > [data-control="accordion.items"]', this).each(function () {
                    $(this).css('max-height', '100%');
                });
            });
        },

        //EVENTS
        // -------------------------------------------------------------------------------------------------------------
        _onTriggerClick: function ($trigger) {
            let self = this;

            $trigger.click(function (e) {
                e.preventDefault();

                var $closest = $(this).closest('[data-control="accordion.item"]');
                var $parents = $(this).parents('[data-control="accordion.items"]');

                if (!self.option('multiple')) {
                    $closest.siblings()
                        .attr('aria-open', 'false')
                        .children('[data-control="accordion.items"]').css('max-height', 0);

                    $closest.siblings()
                        .children('[data-control="accordion.items"]')
                        .children('[data-control="accordion.item"]')
                        .attr('aria-open', 'false')
                        .children('[data-control="accordion.items"]').css('max-height', 0);
                }

                if ($closest.attr('aria-open') === 'true') {
                    $('> [data-control="accordion.items"]', $closest).css('max-height', 0);
                    $closest.attr('aria-open', 'false');
                } else {
                    var height = $('> [data-control="accordion.items"]', $closest).prop('scrollHeight');
                    $('> [data-control="accordion.items"]', $closest).css('max-height', height);
                    $closest.attr('aria-open', 'true');

                    $parents.each(function () {
                        var pheight = $(this).prop('scrollHeight');
                        $(this).css('max-height', pheight + height);
                    });
                }
            });
        }
    });

    $(document).ready(function ($) {
        $('[data-control="accordion"]').tifyaccordion();
    });
})(jQuery);