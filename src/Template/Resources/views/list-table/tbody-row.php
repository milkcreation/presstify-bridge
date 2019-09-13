<?php
/**
 * Ligne de données de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var tiFy\Template\Templates\ListTable\Contracts\Column $column
 * @var tiFy\Template\Templates\ListTable\Contracts\Item $item
 */
?>
<tr>
    <?php foreach ($this->columns() as $column) : ?>
        <?php echo partial('tag', [
            'attrs'   => $column->get('attrs', []),
            'content' => (string)$column,
            'tag'     => $column->get('tag', 'td'),
        ]); ?>
    <?php endforeach; ?>
</tr>