<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Wordpress\Contracts\Query\QueryPost $item
 */
?>
<div <?php echo $this->htmlAttrs($this->params('attrs', [])); ?>>
    <?php echo field('suggest', $this->params('suggest', [])); ?>

    <ul data-control="metabox-postfeed.items">
        <?php foreach ($this->params('items', []) as $item) : ?>
           <?php $this->insert('item-wrap', compact('item')); ?>
        <?php endforeach; ?>
    </ul>
</div>