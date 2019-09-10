<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

class Database extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'database';
    }
}