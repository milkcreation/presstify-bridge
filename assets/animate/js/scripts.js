'use strict';

import jQuery from 'jquery';

jQuery(function ($) {
  function inViewport($target, threshold = 0) {
    let offset = $target.offset();

    if (!offset) {
      return false;
    }

    let lBound = $(window).scrollTop(),
        uBound = lBound + $(window).height(),
        top = offset.top + threshold,
        bottom = top + $target.outerHeight(true);

    return (top > lBound && top < uBound) ||
        (bottom > lBound && bottom < uBound) ||
        (lBound >= top && lBound <= bottom) ||
        (uBound >= top && uBound <= bottom);
  }

  function getScrollTarget($el) {
    if (typeof $el.data('anim-target') === 'string') {
      if ($($el.data('anim-target')).length()) {
        return $($el.data('anim-target'));
      }
    } else if (typeof $el.data('anim-target') === 'number') {
      return $el.data('anim-target');
    } else {
      return $el;
    }
  }

  function isScrollTarget($el) {
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
  }

  /** Lancement des animations au scroll */
  $(window).scroll(function () {
    $('[data-anim].anim-scroll:not(.animated)').each(function () {
      let $target = getScrollTarget($(this));

      if (isScrollTarget($target)) {
        $(this).addClass('animated' + ' ' + $(this).data('anim'));
      }
    });
  });

  $(window).load(function () {
    $('[data-anim]:not(.anim-scroll)').each(function () {
        $(this).addClass('animated' + ' ' + $(this).data('anim'));
    });
  });

  $('[data-anim]').on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
    $(this).removeClass($(this).data('anim'));
  });

  /* Initialisation du lancement des animations au scroll */
  /*$(window).load( function(){
      $(this).trigger( 'scroll' );
  });*/
});