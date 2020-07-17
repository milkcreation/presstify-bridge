<?php
/**
 * Vue ListTable.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
?>
<div class="wrap">
    <div <?php echo $this->htmlAttrs($this->param('attrs', [])); ?>>
        <?php $this->insert('header'); ?>
        <?php $this->insert('view-filters'); ?>

        <form method="get" action="<?php echo $this->url()->display(); ?>">
            <?php if ($this->search()->exists()) : ?>
                <?php $this->insert('search'); ?>
            <?php endif; ?>

            <?php foreach ($this->form()->getHidden() as $key => $value) : ?>
                <?php echo field('hidden', [
                    'name'  => $key,
                    'value' => $value,
                ]); ?>
            <?php endforeach; ?>

            <?php $this->insert('table'); ?>
        </form>
    </div>
</div>