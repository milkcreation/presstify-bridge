<?php
/**
 * Liste des actions groupés.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var string $which top|bottom.
 */
?>
<?php if ($this->items()->exists()) : ?>
    <div class="alignleft actions bulkactions">
        <?php echo $this->bulkActions()->which($which??'top'); ?>
    </div>
<?php endif;