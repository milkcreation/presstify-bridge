<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Log\{Logger, LogManager};

/**
 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static Logger channel(?string $channel = null)
 * @method static void critical(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void log($level, string $message, array $context = [])
 * @method static Logger|null registerChannel(string $name, array $params = [])
 */
class Log extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return LogManager|Logger
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
        return 'log';
    }
}