<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Kernel\ParamsBag;

interface ButtonController extends ParamsBag
{
    /**
     * Résolution de sortie de l'affichage du contrôleur.
     *
     * @return string
     */
    public function __toString();

    /**
     * Initialisation du contrôleur.
     *
     * @return void
     */
    public function boot();

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération de l'ordre d'affichage.
     *
     * @return int
     */
    public function getPosition();

    /**
     * Vérification d'existance d'encapsuleur HTML.
     *
     * @return boolean
     */
    public function hasWrapper();

    /**
     * Affichage.
     *
     * @return string
     */
    public function render();
}