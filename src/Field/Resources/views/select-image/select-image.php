<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
<?php echo field('select-js', [
    'name'    => $this->getName(),
    'value'   => $this->getValue(),
    'attrs'   => $this->get('attrs', []),
    'choices' => $this->get('choices', []),
    'classes' => [
        'picker'        => '%s FieldSelectImage-picker',
        'pickerItem'    => '%s FieldSelectImage-pickerItem',
        'selectionItem' => '%s FieldSelectImage-selectionItem',
    ],
]); ?>
<?php $this->after();