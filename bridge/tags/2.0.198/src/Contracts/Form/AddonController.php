<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Kernel\ParamsBag;

interface AddonController extends ParamsBag
{
    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot();

    /**
     * Liste des attributs de configuration par défaut des champs du formulaire associé.
     *
     * @return array
     */
    public function defaultsFieldOptions();

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName();
}