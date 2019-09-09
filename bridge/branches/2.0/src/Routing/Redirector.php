<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Http\Message\ResponseInterface as Response;
use tiFy\Http\RedirectResponse;
use tiFy\Contracts\Routing\Redirector as RedirectorContract;
use tiFy\Contracts\Routing\Router;

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
    public function to(string $path, int $status = 302, array $headers = []): ?Response
    {
        return RedirectResponse::createPsr($path, $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function route(string $name, array $parameters = [], int $status = 302, array $headers = []): ?Response
    {
        return $this->to($this->manager->url($name, $parameters), $status, $headers);
    }
}