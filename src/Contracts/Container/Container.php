<?php

namespace tiFy\Contracts\Container;

use League\Container\ContainerInterface as LeagueContainerInterface;

interface Container extends LeagueContainerInterface
{
    /**
     * Récupération de la liste des fournisseurs de services ou services indépendants déclaré.
     *
     * @return array
     */
    public function getServiceProviders();

    /**
     * Vérification d'existance de paramètres.
     * {@internal Assure la compatibilité avec le conteneur d'injection de Symfony.}
     *
     * @return bool
     */
    public function hasParameter();

    /**
     * Résolution de service fournis par le conteneur d'injection de dépendances.
     *
     * @param string $alias Nom de qualification du service fournis.
     * @param array $args Liste des variables passées en arguments pour résoudre le service.
     *
     * @return mixed
     */
    public function get($alias, array $args = []);
}