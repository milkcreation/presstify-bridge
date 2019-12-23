<?php
/**
 * Entête de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var tiFy\Template\Templates\ListTable\Contracts\Column $column
 */
?>
<thead>
    <tr><?php foreach($this->columns() as $column) : echo $column->header(); endforeach; ?></tr>
</thead>