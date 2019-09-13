<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\View\ViewController;

interface FactoryView extends ViewController
{
    /**
     * Translation d'appel des méthodes de l'application associée.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     */
    public function __call($name, $arguments);

    /**
     * Post-affichage.
     *
     * @return string
     */
    public function after();

    /**
     * Pré-affichage.
     *
     * @return string
     */
    public function before();

    /**
     * Récupération de l'instance du contrôleur de formulaire.
     *
     * @return FormFactory
     */
    public function form();
}