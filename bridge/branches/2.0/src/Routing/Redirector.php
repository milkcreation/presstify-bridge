<?php declare(strict_types=1);

namespace tiFy\Routing;

use tiFy\Http\RedirectResponse as HttpRedirect;
use tiFy\Contracts\Http\Response as HttpResponse;
use tiFy\Contracts\Routing\{Redirector as RedirectorContract, Router};
use tiFy\Support\Proxy\Url;

class Redirector implements RedirectorContract
{
    /**
     * Instance du gestion de routage
     * @var Router
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param Router $manager Instance du gestion de routage
     *
     * @return void
     */
    public function __construct(Router $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function to(string $path, int $status = 302, array $headers = []): HttpResponse
    {
        $path = empty($path) || $path === '/' ? (string)Url::root() : $path;

        return new HttpRedirect($path, $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function route(string $name, array $parameters = [], int $status = 302, array $headers = []): HttpResponse
    {
        return $this->to($this->manager->url($name, $parameters), $status, $headers);
    }
}