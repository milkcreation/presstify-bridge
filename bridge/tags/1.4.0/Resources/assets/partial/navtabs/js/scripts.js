jQuery(document).ready(function ($) {
    /**
     * Sauvegarde de l'onglet courant
     */
    $(document).on('click', '[aria-control="navtabs"] li:not(.active) > a[data-toggle="tab"]', function (e) {
        var key = $(this).data('key');

        $.ajax({
            'url':      tify_ajaxurl,
            'type' :    'POST',
            'data' :    {
                action :        'tify_partial_navtabs',
                key :           key,
                _ajax_nonce :   tiFyPartialNavtabs._ajax_nonce
            }
        });
    });

    var
    // Affichage recursif des onglets enfants
    tabShowRecursiveChild = function($tab)
    {
        var id = $tab.attr('href'),
            $tabs = ($tab.closest('[aria-control="navtabs"]')),
            $child = $(id +'.tab-pane .nav li:first-child:not(.active) > a[data-toggle="tab"]', $tabs);

        if ($child.length) {
            $child.tab('show');
            tabShowRecursiveChild($child);
        }
    },
    // Affichage recursif des onglets parents
    tabShowRecursiveParent = function($tab)
    {
        var $tabs = ($tab.closest('[aria-control="navtabs"]')),
            parent_id = $tab.closest('.tab-pane').attr('id'),
            $parent = $('.nav > li:not(.active) > a[data-toggle="tab"][href="#' + parent_id + '"]', $tabs);

        if ($parent.length) {
            $parent.tab('show');
            tabShowRecursiveParent($parent);
        }
    }

    /**
     * Affichage de l'onglet courant
     */
    $('[aria-control="navtabs"] > .nav > li:not(:has(a[data-toggle="tab"].current)) > a[data-toggle="tab"]').each(function() {
        tabShowRecursiveChild($(this));
    });

    if ($('a[data-toggle="tab"].current', '[aria-control="navtabs"]').length) {
        var $current = $('a[data-toggle="tab"].current', '[data-tify_control="tabs"]');

        $current.tab('show');
        tabShowRecursiveChild($current);
        tabShowRecursiveParent($current);
    } else {
        var $current = $('[aria-control="navtabs"] > .nav > li:first-child > a[data-toggle="tab"]');
        $current.addClass('current');
        $current.tab('show');
        tabShowRecursiveChild($current);
    }
});