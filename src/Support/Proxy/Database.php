<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder as Schema;
use Illuminate\Database\Query\Builder as Query;

/**
 * @method static void addConnection(array $config, string $name = 'default')
 * @method static Connection getConnection(string|null $name = null)
 * @method static Schema schema(string|null $connexion = null)
 * @method static Query table(string $table, string|null $connexion = null)
 */
class Database extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'database';
    }
}