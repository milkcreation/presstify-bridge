<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Closure;
use Psr\Http\Message\{ResponseInterface as Response};
use Psr\Http\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response as SfResponse;
use tiFy\Contracts\Routing\{
    Route as RouteContract,
    RouteGroup as RouteGroupContract,
    Router as RouterContract
};
use tiFy\Routing\BaseController;
/**
 * @method static array all()
 * @method static int count()
 * @method static RouteContract|null current()
 * @method static string|null currentRouteName()
 * @method static RouteContract delete(string $path, callable $handler)
 * @method static Response emit(Response|SfResponse $response)
 * @method static bool exists()
 * @method static RouteContract get(string $path, callable $handler)
 * @method static ContainerInterface getContainer()
 * @method static string|array|Closure|callable|BaseController|null getNamedController(string $name)
 * @method static MiddlewareInterface|null getNamedMiddleware(string $name)
 * @method static RouteContract getNamedRoute(string $name)
 * @method static Response getResponse()
 * @method static RouteGroupContract group(string $prefix, callable $group)
 * @method static RouteContract head(string $path, callable $handler)
 * @method static bool hasCurrent()
 * @method static bool hasNamedRoute(string $name)
 * @method static bool isCurrentNamed(string $name)
 * @method static RouteContract map(string $method, string $path, callable $handler)
 * @method static RouterContract middleware(MiddlewareInterface $middleware)
 * @method static RouteContract patch(string $path, callable $handler)
 * @method static RouteContract post(string $path, callable $handler)
 * @method static RouteContract put(string $path, callable $handler)
 * @method static RouteContract options(string $path, callable $handler)
 * @method static BaseController registerController(string $name, string|array|Closure|callable|BaseController $controller)
 * @method static MiddlewareInterface registerMiddleware(string $name, MiddlewareInterface $middleware)
 * @method static RouteContract setControllerStack(string[]|array[]|Closure[]|callable[]|BaseController[] $controllers)
 * @method static RouteContract setMiddlewareStack(MiddlewareInterface[] $middlewares)
 * @method static RouteContract setPrefix(?string $prefix)
 * @method static string url(string $name, array $parameters = [], bool $absolute = true)
 * @method static RouteContract xhr(string $path, callable $handler, string $method = 'POST')
 */
class Router extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return RouterContract
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
        return 'router';
    }
}