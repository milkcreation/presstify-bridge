'use strict';

import jQuery from 'jquery';
let inViewport = require('presstify-framework/in-viewport/js/scripts');

jQuery(function ($) {
  let animScroll = function () {
        $('[data-anim].anim-scroll:not(.animated)').each(function () {
          let $target = getScrollTarget($(this));

          if (isScrollTarget($target)) {
            $(this).addClass('animated' + ' ' + $(this).data('anim'));
          }
        });
      },
      getScrollTarget = function ($el) {
        if (typeof $el.data('anim-target') === 'string') {
          if ($($el.data('anim-target')).length()) {
            return $($el.data('anim-target'));
          }
        } else if (typeof $el.data('anim-target') === 'number') {
          return $el.data('anim-target');
        } else {
          return $el;
        }
      },
      isScrollTarget = function ($el) {
        let value;

        switch (typeof $el) {
          case 'object':
            value = inViewport($el);
            break;
          case 'number' :
            value = ($(window).scrollTop() >= $el);
            break;
          default:
            value = false;
            break;
        }
        return value;
      };

  $(window)
      .on('scroll', animScroll)
      .on('load', function () {
        $('[data-anim]:not(.anim-scroll)').each(function () {
          $(this).addClass('animated' + ' ' + $(this).data('anim'));
        });

        animScroll();
      });

  $('[data-anim]').on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
    $(this).removeClass($(this).data('anim'));
  });
});