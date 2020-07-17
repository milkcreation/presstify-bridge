<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\{Http\RedirectResponse, Routing\Redirector};

/**
 * @method static RedirectResponse to(string $path, int $status = 302, array $headers = [])
 * @method static RedirectResponse route(string $name, array $parameters = [], int $status = 302, array $headers = [])
 */
class Redirect extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return Redirector
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
        return 'redirect';
    }
}