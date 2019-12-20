<?php
/**
 * Colonne "Case à coché" de la ligne de données de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var tiFy\Template\Templates\ListTable\Contracts\Columns $column
 * @var tiFy\Template\Templates\ListTable\Contracts\Item $item
 * @var string $content
 */
echo field('checkbox', [
    'name'  => "{$item->getKeyName()}[]",
    'value' => $item->getKeyValue()
]);