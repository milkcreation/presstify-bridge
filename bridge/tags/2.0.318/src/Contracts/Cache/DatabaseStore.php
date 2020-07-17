<?php declare(strict_types=1);

namespace tiFy\Contracts\Cache;

use Illuminate\Database\{
    ConnectionInterface as DbConnection,
    Query\Builder as QueryBuilder
};

interface DatabaseStore extends Store
{
    /**
     * Récupération de l'instance de la connexion à la base de données..
     *
     * @return DbConnection
     */
    public function connection(): DbConnection;

    /**
     * Récupération de l'instance du constructeur de requête de la table de base de données en cache.
     *
     * @return QueryBuilder
     */
    public function table(): QueryBuilder;

    /**
     * Définition de l'instance de la connexion à la base de données..
     *
     * @param DbConnection $connection
     *
     * @return static
     */
    public function setConnection(DbConnection $connection): DatabaseStore;

    /**
     * Définition de l'instance du constructeur de requête de la table de base de données en cache.
     *
     * @param string|null $table Nom de qualification de la table.
     *
     * @return static
     */
    public function setTable(?string $table = null): DatabaseStore;
}