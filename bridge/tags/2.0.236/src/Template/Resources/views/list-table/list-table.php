<?php
/**
 * Vue ListTable.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 */
?>
<div class="wrap">
    <div <?php echo $this->htmlAttrs($this->param('attrs', [])); ?>>
        <?php $this->insert('header'); ?>
        <?php $this->insert('view-filters'); ?>
        <form method="get">
            <?php if ($this->search()->exists()) : ?>
                <?php $this->insert('search'); ?>
            <?php endif; ?>
            <?php $this->insert('table'); ?>
        </form>
    </div>
</div>