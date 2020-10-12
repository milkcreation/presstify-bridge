<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Console\{Console as ConsoleContract, Command, CommandStack};

/**
 * @method static Command add(Command $command)
 * @method static CommandStack stack(string $name, string[] $commands)
 */
class Console extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return ConsoleContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'console';
    }
}