jQuery(document).ready(function ($) {
    // Désactivation des actions de masquage des colonnes natif de Wordpress
    $('.hide-column-tog').unbind();

    /** DEBUG
    $.post(
        tify.ajax_url,
        {'action' : tify.dataTables.viewName + '_get_items'}
    ).done(function(resp)  {
        console.log(resp);
    });
     */

    $.extend(
        $.fn.dataTable.defaults,
        {
            // Liste des colonnes
            columns: tify.dataTables.columns,
            // Nombre d'éléments par page
            iDisplayLength: parseInt(tify.dataTables.per_page),
            // Tri par défaut
            order: [],
            // Traduction
            language: tify.dataTables.language,
            // Interface
            dom: 'rt'
        }
    );

    var $table = $('.wp-list-table'),
        filters = {},
        o = {
            // Activation de l'indicateur de chargement 
            processing: true,
            // Activation du chargement Ajax
            serverSide: true,
            // Désactivation du chargement Ajax à l'initialisation 
            deferLoading: [tify.dataTables.total_items, tify.dataTables.per_page],
            // Attributs de la requête de traitement Ajax
            ajax:
                {
                    url: tify.ajax_url,
                    data: function (d) {
                        d = $.extend(d, filters, {action: tify.dataTables.viewName + '_get_items'});
                        /**
                         * Ajout dynamique d'arguments passés dans la requête ajax de récupération d'éléments
                         * @see tiFy\Core\Templates\Admin\Model\AjaxListTable\AjaxListTable::hidden_fields();
                         * $( '#ajaxDatatablesData' ).val( encodeURIComponent( JSON.stringify( resp.data ) ) );
                         */
                        if ($('#ajaxDatatablesData').val()) {
                            var ajax_data = JSON.parse(decodeURIComponent($('#ajaxDatatablesData').val()));
                            d = $.extend(d, ajax_data);
                        }
                        return d;
                    },
                    dataType: 'json',
                    method: 'GET',
                    dataSrc: function (json) {
                        if (!$('.search-box').length) {
                            $(json.search_form).insertBefore('.tablenav.top');
                        }
                        $('.tablenav-pages').each(function () {
                            $(this).replaceWith(json.pagination);
                        });
                        return json.data;
                    }
                },
            // Au moment du traitement
            drawCallback: function (settings) {
                /** DEBUG
                    var dataTable = this.api();
                    console.log(dataTable.params());
                 */
            },
            // Initialisation
            initComplete: function (settings, json) {
                var dataTable = this.api();

                dataTable.columns().every(function () {
                    var column = this;
                    if (column.visible()) {
                        return;
                    };
                });

                /*$.each(this.api().columns().visible(), function (u, v) {
                    var name = settings()[0].aoColumns[u].name;
                    $('.hide-column-tog[name="' + name + '-hide"]').prop('checked', v);
                });*/

                // Affichage/Masquage des colonnes
                $('.hide-column-tog').change(function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    var column = dataTable.column($this.val() + ':name');
                    column.visible(!column.visible());

                    return false;
                });

                // Soumission du formulaire
                $('form#adv-settings').submit(function (e) {
                    e.preventDefault();

                    var value = parseInt($('.screen-per-page', $(this)).val())

                    $.post(
                        tify.ajax_url,
                        {
                            action: tify.dataTables.action_prefix + '_per_page',
                            per_page: value
                        }
                    ).
                        done(function () {
                            $('#show-settings-link').trigger('click');
                        });

                    dataTable
                        .page
                        .len(value)
                        .draw();

                    return false;
                });

                // Filtrage
                $('#table-filter').submit(function (e) {
                    e.preventDefault();

                    filters = {};

                    $.each($(this).serializeArray(), function (u, v) {
                        if ((v.name === '_wpnonce') || (v.name === '_wp_http_referer') || (v.name === 's') || (v.name === 'paged'))
                            return true;
                        filters[v.name] = v.value;
                    });

                    dataTable
                        .draw(true);

                    return false;
                });

                // Pagination
                $(document).on('click', '.tablenav-pages a', function (e) {
                    e.preventDefault();
                    
                    var page = 0;
                    if ($(this).hasClass('next-page')) {
                        page = 'next';
                    } else if ($(this).hasClass('prev-page')) {
                        page = 'previous';
                    } else if ($(this).hasClass('first-page')) {
                        page = 'first';
                    } else if ($(this).hasClass('last-page')) {
                        page = 'last';
                    }

                    dataTable
                        .page(page)
                        .draw('page');

                    return false;
                });

                // Champ de recherche
                $(document).on('click', '.search-box #search-submit', function (e) {
                    e.preventDefault();

                    var value = $(this).prev().val();

                    dataTable
                        .search(value)
                        .draw();

                    return false;
                });
            }
        };
    o = $.extend(o, tify.dataTables.options);

    $table.dataTable(o);
});