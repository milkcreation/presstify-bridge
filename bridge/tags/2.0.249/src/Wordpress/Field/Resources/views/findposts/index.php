<?php
/**
 * Field Findposts.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>

<div data-control="findposts">
    <?php echo field('text', [
        'attrs' => $this->get('attrs', [])
    ]); ?>

    <?php echo field('button'); ?>
</div>

<?php $this->after();