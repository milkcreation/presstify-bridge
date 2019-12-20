<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Cookie\Cookie as CookieContract;

/**
 * @method static CookieContract instance(string $alias, string|array|null $attrs = null)
 */
class Cookie extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'cookie';
    }
}