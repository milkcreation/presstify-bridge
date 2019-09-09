"use strict";

jQuery(document).ready(function ($) {
    let $slider = $('[data-control="slider"]');

    $slider
        .on('init', function () {
            $(this).addClass('is-ready');
        })
        .each(function () {
            $(this).slick();
        });
});