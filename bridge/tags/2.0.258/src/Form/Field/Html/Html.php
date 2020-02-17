<?php

namespace tiFy\Form\Field\Html;

use tiFy\Form\FieldController;
use Closure;

class Html extends FieldController
{
    /**
     * Liste des propriétés de formulaire supportées.
     * @var array
     */
    protected $supports = [];

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $value = $this->field()->getValue();

        return ! $value instanceof Closure ? (string)$value : call_user_func($value);
    }
}