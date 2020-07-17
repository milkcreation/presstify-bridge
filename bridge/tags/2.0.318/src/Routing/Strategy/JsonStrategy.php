<?php declare(strict_types=1);

namespace tiFy\Routing\Strategy;

use League\Route\Strategy\JsonStrategy as BaseJsonStrategy;
use League\Route\Route;
use Psr\Http\Message\{ResponseInterface as PsrResponse, ServerRequestInterface as PsrRequest};
use Symfony\Component\HttpFoundation\Response as SfResponse;
use tiFy\Contracts\Routing\Route as RouteContract;
use tiFy\Http\Response;

class JsonStrategy extends BaseJsonStrategy
{
    /**
     * @inheritdoc
     */
    public function invokeRouteCallable(Route $route, PsrRequest $request): PsrResponse
    {
        /** @var RouteContract $route */
        $route->setCurrent();

        $controller = $route->getCallable($this->getContainer());

        $args = array_values($route->getVars());
        array_push($args, $request);
        $response = $controller(...$args);

        if ($response instanceof SfResponse) {
            $response = Response::convertToPsr($response);
        } elseif (!$response instanceof PsrResponse) {
            $response = ($this->isJsonEncodable($response))
                ? Response::create(json_encode($response))->psr() : (new Response())->psr();
        }

        return $this->applyDefaultResponseHeaders($response);
    }
}