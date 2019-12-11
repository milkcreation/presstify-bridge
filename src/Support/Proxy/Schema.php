<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder as SchemaBuilder;

/**
 * @method static void blueprintResolver(Closure $resolver)
 * @method static void create(string $table, Closure $callback)
 * @method static void drop(string $table)
 * @method static void dropAllTables()
 * @method static void dropAllTypes()
 * @method static void dropAllViews()
 * @method static void dropIfExists($table)
 * @method static boolean disableForeignKeyConstraints()
 * @method static boolean enableForeignKeyConstraints()
 * @method static array getColumnListing(string $table)
 * @method static string getColumnType(string $table, string $column)
 * @method static Connection getConnection()
 * @method static boolean hasTable(string $table)
 * @method static boolean hasColumn(string $table, string $column)
 * @method static boolean hasColumns(string $table, array $columns)
 * @method static void rename(string $from, string $to)
 * @method static SchemaBuilder setConnection(Connection $connection)
 * @method static void table(string $table, Closure $callback)
 */
class Schema extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return SchemaBuilder
     */
    public static function connexion(string $name = 'default')
    {
        return static::$container->get('database')->connection($name)->getSchemaBuilder();
    }

    /**
     * {@inheritDoc}
     *
     * @return SchemaBuilder
     */
    public static function getInstance()
    {
        return static::connexion();
    }
}