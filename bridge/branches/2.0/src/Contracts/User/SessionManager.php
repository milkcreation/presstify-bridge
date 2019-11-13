<?php

namespace tiFy\Contracts\User;

use tiFy\Contracts\Db\DbFactory;

/**
 * Interface SessionManager
 *
 * @package tiFy\Contracts\User
 *
 * @see https://github.com/kloon/woocommerce-large-sessions
 */
interface SessionManager
{
    /**
     * Récupération d'une session.
     *
     * @param string $name Nom de qualification de la session.
     *
     * @return SessionStore|null
     */
    public function get(string $name): ?SessionStore;

    /**
     * Récupération de la base de données
     *
     * @return DbFactory
     *
     * @throws \Exception
     */
    public function getDb(): DbFactory;

    /**
     * Déclaration d'une session de stockage des données.
     *
     * @param string $name Nom de qualification de la session.
     * @param array $attrs Attributs de configuration.
     *
     * @return static
     */
    public function register(string $name, array $attrs = []): ?SessionManager;

    /**
     * Définition d'une session de stockage des données.
     *
     * @param SessionStore $factory
     * @param string|null $name
     *
     * @return static
     */
    public function set(SessionStore $factory, ?string $name = null): ?SessionManager;
}