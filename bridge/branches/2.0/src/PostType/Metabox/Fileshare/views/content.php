<?php
/**
 * @var tiFy\View\ViewController $this
 */
?>
<div <?php echo $this->htmlAttrs($this->get('attrs', [])); ?> data-control="metabox-fileshare">
    <ul data-control="metabox-fileshare.items">
        <?php foreach ($this->get('items', []) as $item) : ?>
            <?php $this->insert('item-wrap', $item); ?>
        <?php endforeach; ?>
    </ul>

    <?php $this->insert('button', $this->all()); ?>
</div>
