<?php
/**
 * Encapsuleur de l'etiquette de champ de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
?>
<?php
echo partial('tag', array_merge(
    $field->get('label.wrapper', []),
    ['content' => $this->section('content')]
));