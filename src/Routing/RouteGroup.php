<?php declare(strict_types=1);

namespace tiFy\Routing;

use League\Route\RouteGroup as LeagueRouteGroup;
use tiFy\Contracts\Routing\RouteGroup as RouteGroupContract;
use tiFy\Routing\Concerns\{ContainerAwareTrait, MiddlewareAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait};

class RouteGroup extends LeagueRouteGroup implements RouteGroupContract
{
    use ContainerAwareTrait, MiddlewareAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait;

    /**
     * CONSTRUCTEUR.
     *
     * @param string prefix
     * @param callable $callback
     * @param $collection
     *
     * @return void
     */
    public function __construct(string $prefix, callable $callback, $collection)
    {
        parent::__construct($prefix, $callback, $collection);

        ($this->callback)($this);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(): void
    {

    }
}