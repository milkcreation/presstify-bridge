<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing;

use tiFy\Wordpress\Contracts\Routing\RouteGroup as RouteGroupContract;
use tiFy\Wordpress\Routing\Concerns\WpQueryAwareTrait;
use tiFy\Routing\RouteGroup as BaseRouteGroup;

class RouteGroup extends BaseRouteGroup implements RouteGroupContract
{
    use WpQueryAwareTrait;
}