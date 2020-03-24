<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Table;

use tiFy\Contracts\Partial\{PartialDriver as PartialDriverContract, Table as TableContract};
use tiFy\Partial\PartialDriver;

class Table extends PartialDriver implements  TableContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var bool $header Activation de l'entête de table.
     *      @var bool $footer Activation du pied de table.
     *      @var string[] $columns Intitulé des colonnes.
     *      @var array[] $datas Données de la table.
     *      @var string $none Intitulé de la table lorsque la table ne contient aucune donnée.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'viewer'  => [],
            'header'  => true,
            'footer'  => true,
            'columns' => [
                'Lorem',
                'Ipsum',
            ],
            'datas'   => [
                [
                    'lorem dolor',
                    'ipsum dolor',
                ],
                [
                    'lorem amet',
                    'ipsum amet',
                ],
            ],
            'none'    => __('Aucun élément à afficher dans le tableau', 'tify'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $this->set('count', count($this->get('columns', [])));

        return $this;
    }
}