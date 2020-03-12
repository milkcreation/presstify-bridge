<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Mail\Mailer as MailerContract;

/**
 * @method static void debug(array $params = [])
 * @method static bool send(array $params = [])
 *
 * @see \tiFy\Mail\Mailer
 */
class Mailer extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return MailerContract
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
        return 'mailer';
    }
}