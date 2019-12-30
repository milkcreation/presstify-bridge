<?php
/**
 * Liste des actions groupés.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 * @var string $which top|bottom.
 */
?>
<?php if ($this->items()->exists() && ($bulks = (string)$this->bulkActions()->which($which??'top'))) : ?>
    <div class="alignleft actions bulkactions">
        <?php echo $bulks; ?>
    </div>
<?php endif;