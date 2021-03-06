<?php declare(strict_types=1);

namespace tiFy\Form\Field\Recaptcha;

use tiFy\Api\Recaptcha\Recaptcha as ApiRecaptcha;
use tiFy\Contracts\Form\FactoryField;
use tiFy\Form\FieldController;
use tiFy\Support\Proxy\Field;

class Recaptcha extends FieldController
{
    /**
     * Liste des attributs de support.
     * @var array
     */
    protected $supports = ['label', 'request', 'wrapper'];

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->events()->listen('request.validate.field.recaptcha', [$this, 'onRequestValidationField']);
    }

    /**
     * Contrôle d'intégrité des champs.
     *
     * @param FactoryField $field Instance du controleur de champ associé.
     *
     * @return void
     */
    public function onRequestValidationField(FactoryField $field)
    {
        if (!ApiRecaptcha::instance()->validation()) {
            $this->notices()->add(
                'error',
                __('La saisie de la protection antispam est incorrecte.', 'tify'),
                [
                    'field' => $field->getSlug(),
                ]
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        return Field::get('recaptcha', array_merge($this->field()->getExtras(), [
            'name'  => $this->field()->getName(),
            'attrs' => array_merge([
                'id' => preg_replace('/-/', '_', sanitize_key($this->form()->name()))
            ], $this->field()->get('attrs', []))
        ]))->render();
    }
}