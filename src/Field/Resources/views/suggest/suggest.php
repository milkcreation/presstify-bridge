<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
<?php echo partial('tag', array_merge(
    $this->get('container', []), [
        'content' => $this->fetch('content', $this->all())
    ])
); ?>
<?php $this->after();