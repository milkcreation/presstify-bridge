<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>
    <div <?php $this->attrs(); ?>>
        <?php echo $this->get('backdrop_close', ''); ?>

        <div data-control="modal.dialog" class="<?php echo $this->get('size'); ?>">
            <?php if ($close = $this->get('close', '')): ?>
                <button type="button" data-control="modal.close"><?php echo $close; ?></button>
            <?php endif; ?>

            <div data-control="modal.content">
                <?php if (is_string($this->get('content'))) : ?>
                    <?php echo $this->get('content'); ?>
                <?php else : ?>
                    <?php $this->insert('content', $this->all()); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $this->after();