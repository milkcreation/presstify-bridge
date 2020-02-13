<?php declare(strict_types=1);

namespace tiFy\Wordpress\Proxy;

use tiFy\Support\Proxy\Router as BaseRouter;
use tiFy\Wordpress\Contracts\Routing\Route as WpRoute;

/**
 * @method static WpRoute|null current()
 * @method static WpRoute delete(string $path, callable $handler)
 * @method static WpRoute get(string $path, callable $handler)
 * @method static WpRoute getNamedRoute(string $name)
 * @method static WpRoute head(string $path, callable $handler)
 * @method static WpRoute map(string $method, string $path, callable $handler)
 * @method static WpRoute patch(string $path, callable $handler)
 * @method static WpRoute post(string $path, callable $handler)
 * @method static WpRoute put(string $path, callable $handler)
 * @method static WpRoute options(string $path, callable $handler)
 * @method static WpRoute xhr(string $path, callable $handler, string $method = 'POST')
 */
class Router extends BaseRouter
{

}