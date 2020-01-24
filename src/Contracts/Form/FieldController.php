<?php

namespace tiFy\Contracts\Form;

interface FieldController extends FactoryResolver
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
     * Liste des propriétés de support par défaut.
     *
     * @return array
     */
    public function supports();

    /**
     * Affichage.
     *
     * @return string
     */
    public function render();
}