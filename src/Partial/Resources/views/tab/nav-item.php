<?php
/**
 * Tab - Onglet de navigation
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Partials\Tab\TabView $this
 * @var tiFy\Contracts\Partial\TabItem $item
 */
?>
<a <?php echo $item->getNavAttrs(); ?>>
    <?php echo $item->getTitle(); ?>
</a>
