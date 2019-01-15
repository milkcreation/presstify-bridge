jQuery(document).ready(function ($) {

    $(document).on('click', '[aria-control="crypted"] [aria-control="toggle"]', function (e) {
        e.preventDefault();

        var $closest = $(this).closest('[aria-control="crypted"]'),
            o = $.parseJSON(decodeURIComponent($closest.data('options'))),
            $input = $('[aria-control="input"]', $closest),
            cypher = $input.attr('aria-cypher');

        if ($closest.attr('aria-hide') == 'true') {
            $closest.addClass('loading');
            $input.prop('disabled', true);

            $.post(
                tify_ajaxurl,
                {
                    action: 'tify_field_crypted_decrypt',
                    _ajax_nonce : o._ajax_nonce,
                    cypher: cypher,
                })
                .done(function (resp) {
                    $input
                        .val(resp.data)
                        .prop('disabled', false)
                        .attr('type', 'text');
                    $closest
                        .removeClass('loading')
                        .attr('aria-hide', 'false');
                });
        } else {
            $input
                .val(cypher)
                .prop('disabled', false)
                .attr('type', 'password');

            $closest.attr('aria-hide', 'true');
        }
    });

    /**
     * SAISIE

    var xhr = undefined;
    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $(document).on('keyup change', '[data-tify_control_crypted_data="input"]', function (e) {
        e.preventDefault();

        if (xhr !== undefined)
            xhr.abort();

        var $closest = $(this).closest('[data-tify_control="crypted_data"]')
        value = $(this).val();

        $closest.addClass('load');
        delay(function () {
            xhr = $.post(
                tify_ajaxurl,
                {
                    action: 'tiFyControlCryptedData_encrypt',
                    value: value,
                    encrypt_cb: $closest.data('encrypt_cb'),
                    data: JSON.parse(decodeURIComponent($closest.data('transport')))
                },
                function (resp) {
                    $closest.removeClass('load');
                    $('.tiFyControlCryptedData-cypher', $closest).val(resp.data);
                });
        }, 300);

        return false;
    });
     */
    /**
     * GENERATEUR

    $(document).on('click', '[data-tify_control_crypted_data="generate"]', function (e) {
        e.preventDefault();

        var $closest = $(this).closest('[data-tify_control="crypted_data"]'),
            $input = $('.tiFyControlCryptedData-input', $closest);

        $closest.addClass('load');
        $input.prop('disabled', true);

        $.post(
            tify_ajaxurl,
            {
                action: 'tiFyControlCryptedData_generate',
                generate_cb: $closest.data('generate_cb'),
                data: JSON.parse(decodeURIComponent($closest.data('transport')))
            },
            function (resp) {
                $input
                    .val(resp.data)
                    .prop('disabled', false)
                    .attr('type', 'text');
                $closest.removeClass('masked load');

                $input.trigger('keyup');
            });
    });
     */
});
