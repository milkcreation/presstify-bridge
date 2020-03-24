<?php
/**
 * Zone de contenu du champ de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
echo ($field->get('label.position') === 'before')
    ? $this->fetch('field-label', compact('field')) . $field
    : $field. $this->fetch('field-label', compact('field'));