<?php declare(strict_types=1);

namespace tiFy\Routing\Strategy;

use League\Route\Strategy\ApplicationStrategy;
use League\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response as SfResponse;
use tiFy\Contracts\Routing\Route as RouteContract;
use tiFy\Contracts\View\ViewController;
use tiFy\Http\Response;

class App extends ApplicationStrategy
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        $this->addDefaultResponseHeader('content-type', 'text/html');
    }

    /**
     * @inheritDoc
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        /** @var RouteContract $route */
        $route->setCurrent();

        $controller = $route->getCallable($this->getContainer());

        $args = array_values($route->getVars());
        array_push($args, $request);
        $resolved = $controller(...$args);

        if ($resolved instanceof ViewController) {
            $response = Response::create((string)$resolved);
        } elseif ($resolved instanceof ResponseInterface) {
            $response = Response::createFromPsr($resolved);
        } elseif ($resolved instanceof SfResponse) {
            $response = $resolved;
        } else {
            $response = Response::create((string)$resolved);
        }

        return $this->applyDefaultResponseHeaders(Response::convertToPsr($response));
    }
}