<?php
/**
 * @var tiFy\Partial\PartialView $this
 * @var tiFy\Contracts\Partial\CurtainMenuItem[] $items Liste des éléments.
 * @var int $depth Niveau de profondeur de la liste des éléments.
 * @var tiFy\Contracts\Partial\CurtainMenuItem $parent
 */
?>
<?php if ($items = $this->get('items')) : ?>
    <div class="CurtainMenu-panel"
         data-control="curtain-menu.panel"
         data-level="<?php echo $depth; ?>"
         aria-open="<?php echo !$depth ? 'true' : 'false'; ?>"
    >
        <div class="CurtainMenu-panelWrapper">
            <div class="CurtainMenu-panelContainer">
                <?php if ($parent = $this->get('parent')) : ?>
                    <?php echo $this->insert('parent-title', compact('parent')); ?>

                    <?php echo $this->insert('parent-back', compact('parent')); ?>
                <?php endif; ?>

                <ul class="CurtainMenu-items CurtainMenu-items--<?php echo $this->get('depth'); ?>"
                    data-control="curtain-menu.items">
                    <?php foreach ($items as $item) : ?>
                        <li <?php echo $item->getAttrs(); ?>>
                            <?php echo $this->insert('item-nav', compact('item')); ?>

                            <?php echo $this->insert('items', [
                                'depth'  => $item->getDepth(),
                                'items'  => $item->getChilds(),
                                'parent' => $item,
                            ]); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif;