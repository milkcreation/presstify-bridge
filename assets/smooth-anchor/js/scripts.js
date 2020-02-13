'use strict';

import jQuery from 'jquery';
import 'jquery.easing';

jQuery(function($) {
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

        let offset = $(target).offset(),
            addOffset = $(this).data('add-offset') ? $(this).data('add-offset') : -30,
            speed = $(this).data('speed') ? $(this).data('speed') : 1500,
            effect = $(this).data('effect') ? $(this).data('effect') : 'easeInOutExpo';

        $('html, body').animate({scrollTop: offset.top+addOffset}, speed, effect);

        return false;
    });
});