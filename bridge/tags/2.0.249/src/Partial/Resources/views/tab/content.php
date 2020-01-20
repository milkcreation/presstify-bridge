<?php
/**
 * Tab - Liste des contenus.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Driver\Tab\TabView $this
 * @var tiFy\Contracts\Partial\TabItem[] $items
 * @var tiFy\Contracts\Partial\TabItem $item
 * @var int $depth
 */
?>
<div class="Tab-content <?php echo 'Tab-content--' . $this->getTabStyle($depth); ?>" data-control="tab.content">
    <?php foreach ($this->get('items', []) as $item) : ?>
        <div <?php echo $item->getContentAttrs(); ?>>
            <?php if ($childs = $item->getChilds()) : ?>
                <?php $this->insert('nav', ['depth' => $item->getDepth() + 1, 'items' => $childs]); ?>
                <?php $this->insert('content', ['depth' => $item->getDepth() + 1, 'items' => $childs]); ?>
            <?php else : ?>
                <?php $this->insert('content-item', compact('item')); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>