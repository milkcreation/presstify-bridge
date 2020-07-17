<?php
/**
 * @var tiFy\Partial\PartialView $this
 * @var tiFy\Partial\Driver\Accordion\AccordionWalker $items
 */
?>
<?php $this->before(); ?>
<nav <?php $this->attrs(); ?>>
    <?php if($items->exists()) echo $items; ?>
</nav>
<?php $this->after();