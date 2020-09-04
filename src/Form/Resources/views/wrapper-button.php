<?php
/**
 * Bouton de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\ButtonController $button
 */
echo partial('tag', array_merge($button->get('wrapper', []), [
    'content' => $this->section('content')
]));