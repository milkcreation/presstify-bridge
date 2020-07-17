<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Container\ContainerInterface as Container;
use Psr\Http\{
    Message\ResponseInterface as PsrResponse,
    Message\ServerRequestInterface as PsrRequest,
    Server\RequestHandlerInterface
};
use tiFy\Contracts\Routing\{Emitter, Middleware};

abstract class BaseMiddleware implements Middleware
{
    /**
     * Instance de conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;

        $this->boot();
    }

    /**
     * @inheritDoc
     */
    public function boot(): void { }

    /**
     * @inheritDoc
     */
    public function process(PsrRequest $psrRequest, RequestHandlerInterface $handler): PsrResponse
    {
        return $handler->handle($psrRequest);
    }

    /**
     * @inheritDoc
     */
    public function emit(PsrResponse $psrResponse, Emitter $emitter): PsrResponse
    {
        return $emitter->handle($psrResponse);
    }
}