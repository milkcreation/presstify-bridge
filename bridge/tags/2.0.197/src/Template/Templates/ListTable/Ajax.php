<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\ParamsBag;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{Ajax as AjaxContract, Column};

class Ajax extends ParamsBag implements AjaxContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function defaults()
    {
        return [
            'ajax'        => [
                'url'      => $this->factory->baseUrl() . '/xhr',
                'dataType' => 'json',
                'type'     => 'POST',
            ],
            'data'        => [],
            'columns'     => $this->getColumns(),
            'language'    => $this->getLanguage(),
            'options'     => [
                'pageLength' => $this->factory->pagination()->getPerPage(),
            ],
            'total_items' => $this->factory->pagination()->getTotal(),
            'total_pages' => $this->factory->pagination()->getLastPage(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse()
    {
        parent::parse();

        $this->set('options', $this->parseOptions($this->get('options', [])));

        $this->factory->param()->set([
            'attrs.data-control'       => 'list-table',
            'attrs.data-options'       => $this->all(),
            'table.attrs.data-control' => 'list-table.table',
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        $cols = [];
        foreach ($this->factory->columns() as $name => $c) {
            /** @var Column $c */
            array_push($cols, [
                'data'      => $c->getName(),
                'name'      => $c->getName(),
                'title'     => $c->getTitle(),
                'orderable' => false,
                'visible'   => $c->isVisible(),
            ]);
        }
        return $cols;
    }

    /**
     * @inheritDoc
     */
    public function getLanguage(): array
    {
        return [
            'processing'     => __('Traitement en cours...', 'tify'),
            'search'         => __('Rechercher&nbsp;:', 'tify'),
            'lengthMenu'     => __('Afficher _MENU_ &eacute;l&eacute;ments', 'tify'),
            'info'           => __('Affichage de l\'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ ' .
                '&eacute;l&eacute;ments', 'tify'),
            'infoEmpty'      => __('Affichage de l\'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments', 'tify'),
            'infoFiltered'   => __('(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)', 'tify'),
            'infoPostFix'    => __('', 'tify'),
            'loadingRecords' => __('Chargement en cours...', 'tify'),
            'zeroRecords'    => __('Aucun &eacute;l&eacute;ment &agrave; afficher', 'tify'),
            'emptyTable'     => __('Aucune donnée disponible dans le tableau', 'tify'),
            'paginate'       => [
                'first'    => __('Premier', 'tify'),
                'previous' => __('Pr&eacute;c&eacute;dent', 'tify'),
                'next'     => __('Suivant', 'tify'),
                'last'     => __('Dernier', 'tify'),
            ],
            'aria'           => [
                'sortAscending'  => __(': activer pour trier la colonne par ordre croissant', 'tify'),
                'sortDescending' => __(': activer pour trier la colonne par ordre décroissant', 'tify'),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parseOptions(array $options = []): array
    {
        return array_diff_key((is_array($options) ? $options : []), array_flip([
            'ajax',
            'drawCallback',
            'deferLoading',
            'initComplete',
            'processing',
            'serverSide',
        ]));
    }
}