<?php declare(strict_types=1);

namespace tiFy\Routing;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Middleware\MiddlewareAwareTrait;
use Psr\Http\Message\ResponseInterface as Response;
use tiFy\Contracts\Routing\{
    Emitter as EmitterContract,
    Middleware as MiddlewareContract,
    Router as RouterContract
};
use tiFy\Http\Response as HttpResponse;

class Emitter extends SapiEmitter implements EmitterContract
{
    use MiddlewareAwareTrait;

    /**
     * Instance du gestionnaire de routage.
     * @var RouterContract
     */
    protected $router;

    /**
     * CONSTRUCTEUR
     *
     * @param RouterContract|null $router
     *
     * @return void
     */
    public function __construct(?RouterContract $router = null)
    {
        if (!is_null($router)) {
            $this->setRouter($router);
        }
    }

    /**
     * @inheritDoc
     */
    public function handle(Response $psrResponse) : Response
    {
        /** @var MiddlewareContract|null $middleware */
        $middleware = $this->shiftMiddleware();

        if (is_null($middleware)) {
            return $psrResponse;
        }

        return $middleware->emit($psrResponse, $this) ? : $psrResponse;
    }

    /**
     * @inheritDoc
     */
    public function send(Response $psrResponse): Response
    {
        if ($dispatched = $this->router->getResponse()) {
            $additionnalHeaders = $dispatched->getHeaders() ?: [];
        }

        if (!empty($additionnalHeaders)) {
            foreach ($additionnalHeaders as $name => $value) {
                $psrResponse->withAddedHeader($name, $value);
            }
        }

        $this->middlewares($this->router->getMiddlewareStack());

        if ($route = $this->router->current()) {
            if ($group = $route->getParentGroup()) {
                $this->middlewares((array)$group->getMiddlewareStack());
            }

            $this->middlewares($route->getMiddlewareStack());
        }

        $psrResponse = $this->handle($psrResponse);

        events()->trigger('router.emit.response', [$psrResponse]);

        HttpResponse::createFromPsr($psrResponse)->send();

        return $psrResponse;
    }

    /**
     * @inheritDoc
     */
    public function setRouter(RouterContract $router): EmitterContract
    {
        $this->router = $router;

        return $this;
    }
}
