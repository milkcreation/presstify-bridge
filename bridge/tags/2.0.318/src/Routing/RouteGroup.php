<?php declare(strict_types=1);

namespace tiFy\Routing;

use League\Route\RouteGroup as LeagueRouteGroup;
use tiFy\Contracts\Routing\RouteGroup as RouteGroupContract;
use tiFy\Routing\Concerns\{ContainerAwareTrait, MiddlewareAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait};

class RouteGroup extends LeagueRouteGroup implements RouteGroupContract
{
    use ContainerAwareTrait, MiddlewareAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait;
}