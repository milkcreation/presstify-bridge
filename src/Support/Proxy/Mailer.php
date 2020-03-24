<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Mail\{Mail, Mailer as MailerContract};

/**
 * @method static Mail create(Mail|array|null $params = null)
 * @method static void debug(Mail|array|null $params = null)
 * @method static bool send(Mail|array|null $params = null)
 *
 * @see \tiFy\Mail\Mailer
 *
 * @deprecated
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