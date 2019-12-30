<?php
/**
 * Pied de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 * @var tiFy\Template\Templates\ListTable\Contracts\Column $column
 */
?>
<tfoot>
    <tr><?php foreach ($this->columns() as $column) : echo $column->header(false); endforeach; ?></tr>
</tfoot>