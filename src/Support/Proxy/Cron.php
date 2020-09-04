<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

class Cron extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'cron';
    }
}