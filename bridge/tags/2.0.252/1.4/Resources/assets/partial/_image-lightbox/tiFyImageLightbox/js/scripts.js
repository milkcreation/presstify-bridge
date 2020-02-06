!(function ($, doc, win) {
    $.widget("tify.tiFyImageLightbox", {
        /**
         * Options par défaut.
         */
        options: {
            group: '',
            theme: 'dark',
            overlay: true,
            spinner: true,
            close_button: true,
            caption: true,
            navigation: true,
            tabs: true,
            keyboard: true,
            overlay_close: false,
            animation_speed: 250
        },
        /**
         * Création du widget.
         * @private
         */
        _create: function () {
            var self = this;

            if (this.options.group) {
                this.items  = $(this.options.group, this.element);
                if (!this.items.length) {
                    return null;
                }
            } else {
                this.items = this.element;
            }

            var $selectors = this.items.filter(function () {
                return $(this).attr('href').match(/\.(jpe?g|png|gif)/i);
            });

            var opts = {
                selector: 'id="tiFyImageLightbox"',
                enableKeyboard: this.options.keyboard,
                quitOnDocClick: this.options.overlay_close,
                animationSpeed: parseInt(this.options.animation_speed),
                onStart: function () {
                    if (self.options.overlay) {
                        self._overlayOn();
                    }
                    if (self.options.close_button) {
                        self._closeButtonOn(instance);
                    }
                    if (self.options.navigation) {
                        self._arrowsOn(instance);
                    }
                    if (self.options.tabs) {
                        self._navigationOn(instance);
                    }
                },
                onEnd: function () {
                    if (self.options.overlay) {
                        self._overlayOff();
                    }
                    if (self.options.caption) {
                        self._captionOff();
                    }
                    if (self.options.close_button) {
                        self._closeButtonOff();
                    }
                    if (self.options.navigation) {
                        self._arrowsOff();
                    }
                    if (self.options.spinner) {
                        self._activityIndicatorOff();
                    }
                    if (self.options.tabs) {
                        self._navigationOff();
                    }
                },
                onLoadStart: function () {
                    if (self.options.caption) {
                        self._captionOff();
                    }
                    if (self.options.spinner) {
                        self._activityIndicatorOn();
                    }
                },
                onLoadEnd: function () {
                    if (self.options.caption) {
                        self._captionOn(instance);
                    }
                    if (self.options.spinner) {
                        self._activityIndicatorOff();
                    }
                    if (self.options.navigation) {
                        $('.tiFyImageLightbox-arrow').css('display', 'block');
                    }
                    if (self.options.tabs) {
                        self._navigationUpdate();
                    }
                }
            };
            var instance = $selectors.imageLightbox(opts);
        },
        /**
         * Chargement de la pop-in image.
         * @private
         */
        _activityIndicatorOn: function () {
            $('<div id="tiFyImageLightbox-loading" class=\"tiFyImageLightbox-loading tiFyImageLightbox-loading' + this.options.theme + '\"><div></div></div>').appendTo('body');
        },
        /**
         * Post-chargement de la pop-in image.
         * @private
         */
        _activityIndicatorOff: function () {
            $('#tiFyImageLightbox-loading').remove();
        },
        /**
         * Chargement de l'overlay.
         * @private
         */
        _overlayOn: function () {
            $('<div id="tiFyImageLightbox-overlay" class="tiFyImageLightbox-overlay tiFyImageLightbox-overlay--' + this.options.theme + '"></div>').appendTo('body');
        },
        /**
         * Post-chargement de l'overlay.
         * @private
         */
        _overlayOff: function () {
            $('#tiFyImageLightbox-overlay').remove();
        },
        /**
         * Fermeture de la pop-in image.
         * @private
         */
        _closeButtonOn: function (instance) {
            $('<button type="button" id="tiFyImageLightbox-close" class="tiFyImageLightbox-close tiFyImageLightbox-close--' + this.options.theme + '" title="Close"></button>').appendTo('body').on('click touchend', function () {
                $(this).remove();
                instance.quitImageLightbox();
                return false;
            });
        },
        /**
         * Post-fermeture de la pop-in image.
         * @private
         */
        _closeButtonOff: function () {
            $('#tiFyImageLightbox-close').remove();
        },
        /**
         * Chargement de la légende d'une image.
         * @param instance
         * @private
         */
        _captionOn: function (instance) {
            var current = this.items.filter('[href="' + $('#tiFyImageLightbox').attr('src') + '"]');
            var caption = '';
            if (caption = current.data('caption')) {
            } else if (caption = $('img', current).attr('alt')) {
            } else if (caption = $(current).attr('title')) {
            }

            if (caption) {
                $('<div id="tiFyImageLightbox-caption" class="tiFyImageLightbox-caption tiFyImageLightbox-caption--' + this.options.theme + '">' + caption + '</div>').appendTo('body');
            }
        },
        /**
         * Post-chargement de la légende d'une image.
         * @private
         */
        _captionOff: function () {
            $('#tiFyImageLightbox-caption').remove();
        },
        /**
         * Chargement de la navigation tabs.
         * @param instance
         * @private
         */
        _navigationOn: function (instance) {
            if (instance.length < 2) {
                return;
            }

            var selector = this.items;
            var nav = $('<div id="tiFyImageLightbox-nav" class="tiFyImageLightbox-nav tiFyImageLightbox-nav' + this.options.theme + '"></div>');
            for (var i = 0; i < instance.length; i++) {
                nav.append('<button type="button"></button>');
            }

            nav.appendTo('body');
            nav.on('click touchend', function () {
                return false;
            });

            var navItems = nav.find('button');
            navItems.on('click touchend', function () {
                var $this = $(this);
                if (selector.eq($this.index()).attr('href') != $('#tiFyImageLightbox').attr('src')) {
                    instance.switchImageLightbox($this.index());
                }

                navItems.removeClass('active');
                navItems.eq($this.index()).addClass('active');

                return false;
            })
                .on('touchend', function () {
                    return false;
                });
        },
        /**
         * Mise à jour de la navigation tabs.
         * @private
         */
        _navigationUpdate: function () {
            var items = $('#tiFyImageLightbox-nav button');
            items.removeClass('active');

            var current = this.items.filter('[href="' + $('#tiFyImageLightbox').attr('src') + '"]');

            items.eq(this.items.index(current)).addClass('active');
        },
        /**
         * Désactivation de la navigation tabs.
         * @private
         */
        _navigationOff: function () {
            $('#tiFyImageLightbox-nav').remove();
        },
        /**
         * Chargement de la navigation suivant/précédent.
         * @param instance
         * @private
         */
        _arrowsOn: function (instance) {
            if (instance.length < 2) {
                return;
            }

            var selector = this.items;
            var $arrows = $('<button type="button" class="tiFyImageLightbox-arrow tiFyImageLightbox-arrow--left tiFyImageLightbox-arrow--' + this.options.theme + '"></button><button type="button" class="tiFyImageLightbox-arrow tiFyImageLightbox-arrow--right tiFyImageLightbox-arrow--' + this.options.theme + '"></button>');

            $arrows.appendTo('body');

            $arrows.on('click touchend', function (e) {
                e.preventDefault();

                var $this = $(this),
                    $target = selector.filter('[href="' + $('#tiFyImageLightbox').attr('src') + '"]'),
                    index = selector.index($target);

                if ($this.hasClass('tiFyImageLightbox-arrow--left')) {
                    index = index - 1;
                    if (!selector.eq(index).length)
                        index = selector.length;
                } else {
                    index = index + 1;
                    if (!selector.eq(index).length)
                        index = 0;
                }

                instance.switchImageLightbox(index);
                return false;
            });
        },
        /**
         * Désactivation de la navigation suivant/précédent.
         * @private
         */
        _arrowsOff: function () {
            $('.tiFyImageLightbox-arrow').remove();
        }
    });
})(jQuery, document, window);
