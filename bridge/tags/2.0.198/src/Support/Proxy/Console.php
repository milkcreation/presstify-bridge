<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

class Console extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'console';
    }
}