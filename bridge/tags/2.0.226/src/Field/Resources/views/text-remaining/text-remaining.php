<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
<?php echo partial('tag', [
    'tag'     => $this->get('tag'),
    'content' => $this->get('content', ''),
    'attrs'   => $this->get('attrs', []),
]); ?>
<?php $this->after();