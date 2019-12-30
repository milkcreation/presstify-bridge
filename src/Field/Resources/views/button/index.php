<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
<?php echo partial('tag', [
    'tag'     => 'button',
    'attrs'   => $this->get('attrs', []),
    'content' => $this->get('content', ''),
]); ?>
<?php $this->after();