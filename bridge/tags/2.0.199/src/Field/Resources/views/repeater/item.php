<?php
/**
 * @var tiFy\Field\FieldView $this
 */
echo field('text', [
    'name'  => "{$this->getName()}[{$this->get('index')}]",
    'value' => $this->get('value'),
]);