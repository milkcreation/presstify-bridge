<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Database\Schema\Builder as SchemaBuilder;

/**
 *
 */
class Schema extends AbstractProxy
{
    public static function getInstance()
    {
        return static::$container->get('database')->connection()->getSchemaBuilder();
    }
}