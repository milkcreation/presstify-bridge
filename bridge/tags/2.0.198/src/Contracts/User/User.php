<?php

namespace tiFy\Contracts\User;

use Psr\Container\ContainerInterface;
use tiFy\User\Metadata\Metadata;
use tiFy\User\Metadata\Option;

interface User
{
    /**
     * Récupération de l'instance de l'injecteur de dépendances.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * Récupération de l'instance de traitement des métadonnées utilisateur.
     *
     * @return Metadata
     */
    public function meta(): Metadata;

    /**
     * Récupération de l'instance de traitement des options utilisateur.
     *
     * @return Option
     */
    public function option(): Option;

    /**
     * Récupération de l'instance de traitement des roles utilisateur.
     *
     * @return RoleManager
     */
    public function role(): RoleManager;

    /**
     * Récupération de l'instance de traitement des session utilisateur.
     *
     * @return SessionManager
     */
    public function session(): SessionManager;

    /**
     * Récupération de l'instance de traitement des session utilisateur.
     *
     * @return SigninManager
     */
    public function signin(): SigninManager;

    /**
     * Récupération de l'instance de traitement des session utilisateur.
     *
     * @return SignupManager
     */
    public function signup(): SignupManager;

    /**
     * Résolution d'un service fourni.
     *
     * @param string $alias Nom de qualification du service.
     *
     * @return mixed|object
     */
    public function resolve($alias);
}
