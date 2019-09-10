<?php declare(strict_types=1);

namespace tiFy\Contracts\User;

use tiFy\Contracts\Support\Manager;

/**
 * Interface RoleManager
 * @package tiFy\User\Role
 */
interface RoleManager extends Manager
{
    /**
     * Récupération d'une instance de rôle déclaré.
     *
     * @param string $name Nom de qualification du rôle.
     *
     * @return null|RoleFactory
     */
    public function get(...$args): ?RoleFactory;

    /**
     * Définition d'un rôle.
     *
     * @param string $name Nom de qualification.
     * @param array $args Liste des arguments dynamiques de définition.
     *
     * @return static
     */
    public function register($name, ...$args): RoleManager;

    /**
     * Déclaration d'un rôle.
     *
     * @param string|int|array $key Indice de qualification du rôle ou tableau associatif de la liste des rôles.
     * @param array|RoleFactory $value Liste des attributs de configuration.
     *
     * @return static
     */
    public function set($key, $value = null): RoleManager;
}