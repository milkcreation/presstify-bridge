<?php

namespace tiFy\Form;

use tiFy\Contracts\Form\FactoryField;
use tiFy\Contracts\Form\FieldController as FieldControllerContract;
use tiFy\Form\Factory\ResolverTrait;

class FieldController implements FieldControllerContract
{
    use ResolverTrait;

    /**
     * Liste des attributs de support des types de champs natifs.
     * @var array
     */
    protected $fieldSupports = [
        'button'              => ['request', 'wrapper'],
        'checkbox'            => ['checking', 'label', 'request', 'tabindex', 'wrapper', 'transport'],
        'checkbox-collection' => ['choices', 'label', 'request', 'tabindexes', 'transport', 'wrapper'],
        'datetime-js'         => ['label', 'request', 'tabindexes', 'transport', 'wrapper'],
        'hidden'              => ['request'],
        'label'               => ['wrapper'],
        'password'            => ['label', 'request', 'tabindex', 'wrapper'],
        'radio'               => ['label', 'request', 'tabindex', 'wrapper', 'transport'],
        'radio-collection'    => ['choices', 'label', 'request', 'tabindexes', 'transport', 'wrapper'],
        'repeater'            => ['label', 'request', 'tabindexes', 'transport', 'wrapper'],
        'select'              => ['choices', 'label', 'request', 'tabindex', 'wrapper', 'transport'],
        'select-js'           => ['choices', 'label', 'request', 'tabindex', 'wrapper', 'transport'],
        'submit'              => ['request', 'tabindex', 'wrapper'],
        'toggle-switch'       => ['request', 'tabindex', 'transport', 'wrapper'],
    ];

    /**
     * Nom de qualification (type).
     * @var string
     */
    protected $name = '';

    /**
     * Liste des propriétés de support.
     * @var array
     */
    protected $supports = ['label', 'request', 'tabindex', 'wrapper', 'transport'];

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     * @param FactoryField $field Instance du contrôleur de champ de formulaire associé.
     *
     * @void
     */
    public function __construct($name, FactoryField $field)
    {
        $this->name = $name;
        $this->field = $field;
        $this->form = $field->form();

        $this->boot();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function supports()
    {
        if (isset($this->fieldSupports[$this->field()->getType()])) {
            return $this->fieldSupports[$this->field()->getType()];
        } else {
            return $this->supports;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $args = array_merge($this->field()->getExtras(), [
            'name'  => $this->field()->getName(),
            'attrs' => $this->field()->get('attrs', [])
        ]);

        if($this->field()->supports('choices')) {
            $args['choices'] = $this->field()->get('choices', []);
        }

        $args['value'] = $this->field()->getValue();

        return field($this->field()->getType(), $args);
    }
}