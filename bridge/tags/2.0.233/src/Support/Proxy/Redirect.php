<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Psr\Http\Message\ResponseInterface as Response;
use tiFy\Contracts\Http\RedirectResponse as RedirectContract;

/**
 * @method static Response to(string $path, int $status = 302, array $headers = [])
 * @method static Response route(string $name, array $parameters = [], int $status = 302, array $headers = [])
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