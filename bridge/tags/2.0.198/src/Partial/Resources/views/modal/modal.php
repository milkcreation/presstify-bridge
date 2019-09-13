<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>

<div <?php $this->attrs(); ?>>
    <?php echo $this->get('backdrop_close', ''); ?>

    <div class="modal-dialog <?php echo $this->get('size'); ?>" role="document">
        <div class="modal-content">
            <?php echo $this->get('header', ''); ?>
            <?php echo $this->get('body', ''); ?>
            <?php echo $this->get('footer', ''); ?>
        </div>
    </div>
</div>

<?php $this->after();