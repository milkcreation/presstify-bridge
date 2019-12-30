<?php
/**
 * Table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
?>
<?php $this->insert('tablenav', ['which' => 'top']); ?>
<table <?php echo $this->htmlAttrs($this->param('table.attrs')); ?>>
    <?php $this->insert('thead'); ?>
    <?php $this->insert('tbody'); ?>
    <?php $this->insert('tfoot'); ?>
</table>
<?php $this->insert('tablenav', ['which' => 'bottom']);