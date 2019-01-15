jQuery(document).ready( function($){
    var group = [];
    $('[data-tify_control="image_lightbox"]').each(function(u,v){
        if(! $(this).data('group')) {
            $(this).tiFyImageLightbox($(this).data('options'));
        } else {
            var gp = $(this).data('group');
            if($.inArray(gp, group) === -1 ) {
                group.push(gp);
            }
        }
    });
    $.each(group, function(u,v){
        var o = $.parseJSON( $( '#tiFyControlImageLightbox-groupOption--'+v ).val() );
        $(document).tiFyImageLightbox(o, {group: '[data-tify_control="image_lightbox"][data-group="'+ v +'"]'})
    });
});