<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Wordpress\Contracts\Query\QueryPost $item
 */
?>
<li data-control="metabox-postfeed.item">
    <?php $this->insert('item', compact('item')); ?>

    <div data-control="metabox-postfeed.item.tooltip">
        <?php $this->insert('item-info', compact('item')); ?>
    </div>
</li>