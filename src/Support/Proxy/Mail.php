<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Mail\{Mail as MailContract, Mailer as MailerContract};

/**
 * @method static MailContract create(MailContract|array|null $params = null)
 * @method static void debug(MailContract|array|null $params = null)
 * @method static bool send(MailContract|array|null $params = null)
 *
 * @see \tiFy\Mail\Mailer
 */
class Mail extends AbstractProxy
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