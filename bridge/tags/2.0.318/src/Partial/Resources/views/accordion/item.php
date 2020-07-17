<?php
/**
 * @var tiFy\Partial\PartialView $this
 * @var tiFy\Contracts\Partial\AccordionItem $item
 */
?>
<div <?php echo $this->htmlAttrs($item->get('attrs', [])); ?>>
    <?php echo str_repeat('<span class="Accordion-itemPad"></span>', $item->getDepth()); ?>
    <div class="Accordion-itemContentInner">
        <?php echo $item; ?>
    </div>
</div>