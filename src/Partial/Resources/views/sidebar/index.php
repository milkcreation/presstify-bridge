<?php
/**
 * Sidebar
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\PartialView $this
 * @var tiFy\Partial\Driver\Sidebar\SidebarItem[] $items
 */
?>
<?php $this->before(); ?>
    <div <?php echo $this->attrs(); ?>>

        <?php if ($toggle = $this->get('toggle')) : ?>
            <div class="Sidebar-sideToggle"><?php echo $toggle; ?></div>
        <?php endif; ?>

        <div class="Sidebar-panel">
            <?php !$this->get('header') ?: $this->insert('header', $this->all()); ?>

            <div class="Sidebar-body" data-control="sidebar.body">
                <?php if ($items = $this->get('items', [])) : ?>
                    <ul class="Sidebar-items">
                        <?php foreach ($items as $item) : ?>
                            <li <?php echo $this->htmlAttrs($item->get('attrs')); ?>><?php echo $item; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <?php !$this->get('footer') ?: $this->insert('footer', $this->all()); ?>
        </div>
    </div>
<?php $this->after();