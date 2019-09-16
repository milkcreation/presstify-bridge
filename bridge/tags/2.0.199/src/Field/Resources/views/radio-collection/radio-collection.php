<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>

<nav <?php $this->attrs(); ?>>
    <?php echo $this->get('choices'); ?>
</nav>

<?php $this->after();