<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Console\Command;

/**
 * @method static Command add(Command $command)
 */
class Console extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'console';
    }
}