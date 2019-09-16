<?php

namespace tiFy\Wordpress\Contracts;

interface PageHook
{
    /**
     * Récupération de la listes des classes de rappel des pages d'accroche déclarées.
     *
     * @return PageHookItem[]
     */
    public function all();

    /**
     * Récupération de la classe de rappel d'une page d'accroche déclarée.
     *
     * @param string $name Nom de qualification.
     *
     * @return null|PageHookItem
     */
    public function get($name);

    /**
     * Déclaration de page d'accroche.
     *
     * @param string|array $name Nom de qualification ou liste des pages à déclarer.
     * @param array $attrs Liste des attributs de configuration .
     *
     * @return static
     */
    public function set($name, $attrs = []);
}