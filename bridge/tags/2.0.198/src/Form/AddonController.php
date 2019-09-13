<?php

namespace tiFy\Form;

use tiFy\Contracts\Form\AddonController as AddonControllerContract;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Form\Factory\ResolverTrait;
use tiFy\Kernel\Params\ParamsBag;

class AddonController extends ParamsBag implements AddonControllerContract
{
    use ResolverTrait;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     * @param FormFactory $form Formulaire associé.
     *
     * @return void
     */
    public function __construct($name, $attrs, FormFactory $form)
    {
        $this->name = $name;
        $this->form = $form;

        parent::__construct($attrs);

        $this->boot();
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
    public function defaultsFieldOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
}