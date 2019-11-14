<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Cache\Store;

/**
 * @method static boolean has(string $key)
 * @method static boolean forget(string $key)
 * @method static mixed get(string $key)
 * @method static mixed put(string $key, $value, int $seconds)
 * @method static Store store(string|null $name = null)
 */
class Cache extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'cache';
    }
}