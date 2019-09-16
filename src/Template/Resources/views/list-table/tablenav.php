<?php
/**
 * Interface de navigation de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var string $which top|bottom.
 */
?>
<div class="tablenav <?php echo esc_attr($which??'top'); ?>">
    <?php $this->insert('bulk-actions', compact('which')); ?>
    <?php $this->insert('pagination', compact('which')); ?>
    <br class="clear" />
</div>