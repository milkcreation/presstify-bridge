<?php
/**
 * Tab - Liste des onglets de navigation.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Partials\Tab\TabView $this
 * @var tiFy\Contracts\Partial\TabItem[] $items
 * @var int $depth
 */
?>
<ul class="nav Tab-nav <?php echo 'Tab-nav--'. $this->getTabStyle($depth); ?>" data-control="tab.nav" role="tablist">
    <?php foreach ($this->get('items', []) as $item) : ?>
        <li class="Tab-navItem" data-control="tab.nav.item"><?php $this->insert('nav-item', compact('item')); ?></li>
    <?php endforeach; ?>
</ul>