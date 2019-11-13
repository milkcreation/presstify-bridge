<?php declare(strict_types=1);

namespace tiFy\Cache;

use Exception;
use Illuminate\Database\{
    ConnectionInterface as DbConnection,
    PostgresConnection,
    Query\Builder as QueryBuilder,
    Schema\Blueprint,
    Schema\Builder as SchemaBuilder
};
use tiFy\Contracts\Cache\DatabaseStore as DatabaseStoreContract;
use tiFy\Support\Str;

class DatabaseStore extends AbstractStore implements DatabaseStoreContract
{
    /**
     * Nom de qualification de la table par défaut.
     * @var string
     */
    protected static $defaultTable = 'cache';

    /**
     * Instance de la connexion à la base de données.
     * @var DbConnection|null
     */
    protected $connection;

    /**
     * Instance du constructeur de requête de la table de base de données en cache.
     * @var QueryBuilder|null
     */
    protected $table;

    /**
     * CONSTRUCTEUR
     *
     * @param DbConnection $connection Instance de la connexion à la base de données.
     *
     * @return void
     */
    public function __construct(DbConnection $connection)
    {
        $this->setConnection($connection);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        $prefixed = $this->prefix.$key;
        $cache = $this->table()->where('key', '=', $prefixed)->first();

        if (is_null($cache)) {
            return null;
        }
        $cache = is_array($cache) ? (object) $cache : $cache;

        if ($this->currentTime() >= $cache->expiration) {
            $this->forget($key);
            return null;
        }

        return $this->unserialize($cache->value);
    }

    /**
     * @inheritDoc
     */
    public function connection(): DbConnection
    {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return !!$this->table()->delete();
    }

    /**
     * @inheritDoc
     */
    public function forever(string $key, $value): bool
    {
        return $this->put($key, $value, 3600 * 24 * 365 * 10);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return !!$this->table()->where('key', '=', $this->prefix.$key)->delete();
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, $value, int $seconds): bool
    {
        $key = $this->prefix.$key;
        $value = $this->serialize($value);
        $expiration = $this->currentTime() + $seconds;

        try {
            return $this->table()->insert(compact('key', 'value', 'expiration'));
        } catch (Exception $e) {
            $res = $this->table()->where('key', $key)->update(compact('value', 'expiration'));
            return  $res > 0;
        }
    }

    /**
     * @inheritDoc
     */
    public function serialize($value): string
    {
        $result = serialize($value);

        if ($this->connection instanceof PostgresConnection && Str::contains($result, "\0")) {
            $result = base64_encode($result);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function table(): QueryBuilder
    {
        if (is_null($this->table)) {
            $this->setTable();
        }
        return clone $this->table;
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $value)
    {
        if ($this->connection instanceof PostgresConnection && ! Str::contains($value, [':', ';'])) {
            $value = base64_decode($value);
        }

        return unserialize($value);
    }

    /**
     * @inheritDoc
     */
    public function setConnection(DbConnection $connection): DatabaseStoreContract
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTable(?string $table = null): DatabaseStoreContract
    {
        $table = !is_null($table) ? $table : self::$defaultTable;

        /** @var SchemaBuilder $schema */
        $schema = $this->connection->getSchemaBuilder();

        if (!$schema->hasTable($table)) {
            $schema->create($table, function (Blueprint $table) {
                $table->string('key')->unique();
                $table->longText('value');
                $table->integer('expiration');
            });
        }

        $this->table = $this->connection->table($table);

        return $this;
    }
}