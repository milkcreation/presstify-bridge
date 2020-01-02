<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Http\RedirectResponse as RedirectContract;

/**
 * @method static RedirectContract to(string $path, int $status = 302, array $headers = [])
 * @method static RedirectContract route(string $name, array $parameters = [], int $status = 302, array $headers = [])
 */
class Redirect extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return RedirectContract
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