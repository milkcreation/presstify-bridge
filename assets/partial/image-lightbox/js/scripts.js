"use strict";

jQuery(document).ready(function ($) {
    let attrs = {};
    $('[data-control="image-lightbox.item"]').each(function(){
        let grp = $(this).attr('data-group');
        if (attrs[grp] === undefined) {
            let $c = $(this).closest('[data-control="image-lightbox"]'),
                o = $c.length && $c.attr('data-options') && $.parseJSON(decodeURIComponent($c.data('options'))) || {};

            attrs[grp] = $( "[data-group='"+ grp +"']" ).imageLightbox(o);
        }
    });
});