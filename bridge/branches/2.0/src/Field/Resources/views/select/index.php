<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
    <select <?php $this->attrs(); ?>>
        <?php echo $this->get('choices', ''); ?>
    </select>
<?php $this->after();