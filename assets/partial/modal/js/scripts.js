"use strict";

jQuery(document).ready(function ($) {
    $('[data-control="modal"]')
        .modal()
        .on('shown.bs.modal', function () {
            var $modal = $(this);
            var o = $.parseJSON(decodeURIComponent($modal.data('options')));

            if (tify[o.id] === undefined){
                tify[o.id] = {};
            }

            if ($('.modal-content', $modal).length) {
                tify[o.id].original = $('.modal-content', $modal).html();
            }

            if (o.ajax) {
                if (tify[o.id].content === undefined) {
                    tify[o.id].content = resp;
                    $.post(tify.ajax_url, o.ajax, function(resp) {$('.modal-content', $modal).$modal.html(resp);});
                } else {
                    $('.modal-content', $modal).html(tify[o.id].content);
                }
            }
        })
        .on('hidden.bs.modal', function (){
            var $modal = $(this);
            var o = $.parseJSON(decodeURIComponent($modal.data('options')));

            if (o.ajax) {
                if (tify[o.id].original !== undefined) {
                    $('.modal-content', $modal).html(tify[o.id].original);
                } else {
                    $('.modal-content', $modal).empty();
                }
            }
        });

    $(document).on('click', '[data-control="modal-trigger"]', function (e) {
        e.preventDefault();

        $($(this).data('target') + '[data-control="modal"]').modal('show');
    });
});
