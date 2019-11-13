<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

class Column extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'column';
    }
}