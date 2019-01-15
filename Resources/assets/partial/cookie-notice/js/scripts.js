jQuery(document).ready(function($) {
    $(document).on('click', '[aria-control="notice"] [aria-toggle="accept"]', function(e){
        e.preventDefault();

        var $closest = $(this).closest('[aria-control="notice"]');

        var o = JSON.parse(decodeURIComponent($closest.data('options')));

        $closest.attr('aria-loading', 'true');
        $.post(
            tify_ajaxurl,
            {
                action: o.ajax_action,
                _ajax_nonce: o.ajax_nonce,
                cookie_name: o.cookie_name,
                cookie_hash: o.cookie_hash,
                cookie_expire: o.cookie_expire
            }
        )
            .done(function(resp) {
                $closest.attr('aria-loading', 'false');
            })
            .always(function () {
                $closest.attr('aria-hide', 'true');
            });
    });
});