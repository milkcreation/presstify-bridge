<?php

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\FactoryOptions;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Support\ParamsBag;

class Options extends ParamsBag implements FactoryOptions
{
    use ResolverTrait;

    /**
     * Liste des attributs de configuration.
     * @var array {
     *      @var string|bool $anchor Ancre de défilement verticale de la page web à la soumission du formulaire.
     *      @var string|callable $success_cb Méthode de rappel à l'issue d'un formulaire soumis avec succès.
     *                                       'form' affichera un nouveau formulaire.
     * }
     */
    protected $attributes = [
        'anchor'         => true,
        'success_cb'     => ''
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $options Liste des options associées au formulaire.
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct($options, FormFactory $form)
    {
        $this->form = $form;

        $this->set($options)->parse();
    }
}