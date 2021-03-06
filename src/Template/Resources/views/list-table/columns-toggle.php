<?php
/**
 * Interface de bascule d'affichage des colonnes de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 * @var tiFy\Template\Templates\ListTable\Contracts\Columns $cols
 * @var tiFy\Template\Templates\ListTable\Contracts\Column $col
 */
foreach ($this->columns()->getHideable() as $col) {
    echo field('checkbox', [
        'after' => field('label', [
            'content' => $col->getTitle(),
            'attrs'   => [
                'for' => 'ListTable-columnToggle--' . $col->getName()
            ]
        ]),
        'value' => !$col->isHidden() ? $col->getName() : null,
        'attrs' => [
            'id'           => 'ListTable-columnToggle--' . $col->getName(),
            'data-control' => 'list-table.column.toggle'
        ],
        'checked' => $col->getName()
    ]);
}