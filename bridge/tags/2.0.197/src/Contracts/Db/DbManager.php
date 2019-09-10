<?php

namespace tiFy\Contracts\Db;

interface DbManager
{
    /**
     * Récupération d'un controleur de base de données.
     *
     * @param string $name Nom de qualification du controleur de base de données.
     *
     * @return null|DbFactory
     */
    public function get($name);

    /**
     * Déclaration d'un controleur de base de données.
     *
     * @param string $name Nom de qualification du controleur de base de données.
     * @param array $attrs Attributs de configuration de la base de données.
     *
     * @return DbFactory
     */
    public function register($name, $attrs = []);

    /**
     * Définition d'un controleur de base de données.
     *
     * @param string $name Nom de qualification du controleur de base de données.
     * @param DbFactory $factory Instance de controleur de base de données.
     *
     * @return DbFactory
     */
    public function set($name, DbFactory $factory);
}