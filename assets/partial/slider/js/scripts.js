'use strict';

import jQuery from 'jquery';
import 'slick-carousel/slick/slick';

jQuery(function ($) {
    $('[data-control="slider"]')
      .on('init', function () {
        $(this).addClass('is-ready');
      })
      .each(function () {
        $(this).slick();
      });
});
