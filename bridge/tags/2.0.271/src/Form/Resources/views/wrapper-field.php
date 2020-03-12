<?php
/**
 * Champ de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
echo partial('tag', array_merge($field->get('wrapper', []), [
    'content' => $this->section('content')
]));