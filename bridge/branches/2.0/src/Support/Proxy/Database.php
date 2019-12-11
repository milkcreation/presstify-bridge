<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Illuminate\Database\{Connection, Schema\Builder as Schema, Query\Builder as Query};
use tiFy\Contracts\Database\Database as DatabaseContract;

/**
 * @method static void addConnection(array $config, string $name = 'default')
 * @method static Connection getConnection(string|null $name = null)
 * @method static Schema schema(string|null $connexion = null)
 * @method static Query table(string $table, string|null $connexion = null)
 */
class Database extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return DatabaseContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'database';
    }
}