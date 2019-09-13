"use strict";

jQuery(document).ready(function($) {
    $(document).on('click', "[data-smooth-anchor]", function(e) {
        e.preventDefault();

        let target;

        if($($(this).data('smooth-anchor')).length){
            target = $(this).data('smooth-anchor');
        } else {
            target = '#'+ $(this).attr('href').split("#")[1];
        }

        if(!$(target).length) {
            return;
        }

        var offset = $(target).offset();

        // Options
        var addOffset = $(this).data('add-offset') ? $(this).data('add-offset') : -30;
        var speed = $(this).data('speed') ? $(this).data('speed') : 1500;
        var effect = $(this).data('effect') ? $(this).data('effect') : 'easeInOutExpo';

        $('html, body').animate({scrollTop: offset.top+addOffset}, speed, effect);

        return false;
    });
});