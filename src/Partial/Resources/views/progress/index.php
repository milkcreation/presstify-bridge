<?php
/**
 * Progress
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php echo $this->before(); ?>
    <div <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
        <?php if ($this->get('meter')) : ?>
            <div data-control="progress.meter">
                <?php echo partial('tag', $this->get('meter-bar')); ?>
                <?php echo partial('tag', $this->get('meter-label')); ?>
            </div>
        <?php endif; ?>
    </div>
<?php echo $this->after();