<?php
/**
 * @var tiFy\Partial\PartialView $this
 * @var tiFy\Contracts\Partial\CurtainMenuItems $items
 */
?>
<?php $this->before(); ?>
    <div <?php $this->attrs(); ?>>
        <nav class="CurtainMenu-nav">
            <?php $this->insert('items', [
                'items' => $items->getParentItems(null),
                'depth' => 0,
                'parent' => null
            ]); ?>
        </nav>
    </div>
<?php $this->after();