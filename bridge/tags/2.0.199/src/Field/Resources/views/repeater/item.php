<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php echo field('text', [
    'name'  => "{$this->getName()}[{$this->get('index')}]",
    'value' => $this->get('value'),
    'attrs' => [
        'class' => 'widefat',
    ],
]);