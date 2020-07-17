<?php declare(strict_types=1);

namespace tiFy\Routing;

use League\Route\{Route as LeagueRoute,
    RouteCollectionInterface as LeagueRouteCollection,
    RouteGroup as LeagueRouteGroup};
use tiFy\Contracts\Routing\{RouteGroup as RouteGroupContract, Router as RouterContract};
use tiFy\Routing\Concerns\{ContainerAwareTrait, RegisterMapAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait};

class RouteGroup extends LeagueRouteGroup implements RouteGroupContract
{
    use ContainerAwareTrait, RegisterMapAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait;

    /**
     * Instance du contrôleur de routage.
     * @var RouterContract
     */
    protected $collection;

    /**
     * CONSTRUCTEUR.
     *
     * @param string prefix
     * @param callable $callback
     * @param LeagueRouteCollection $collection
     *
     * @return void
     */
    public function __construct(string $prefix, callable $callback, LeagueRouteCollection $collection)
    {
        parent::__construct($prefix, $callback, $collection);

        $this->setContainer($this->collection->getContainer());

        call_user_func($this->callback, $this);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(): void
    {

    }

    /**
     * {@inheritdoc}
     *
     * @return RouteGroupContract
     */
    public function map(string $method, string $path, $handler): LeagueRoute
    {
        $path = ($path === '/')
            ? $this->prefix
            : rtrim($this->prefix, '/') . sprintf('/%s', ltrim($path, '/'));

        $route = $this->collection->map($method, $path, $handler);

        $route->setParentGroup($this);

        if ($host = $this->getHost()) {
            $route->setHost($host);
        }

        if ($scheme = $this->getScheme()) {
            $route->setScheme($scheme);
        }

        if ($port = $this->getPort()) {
            $route->setPort($port);
        }

        if (is_null($route->getStrategy()) && !is_null($this->getStrategy())) {
            $route->setStrategy($this->getStrategy());
        }

        return $route;
    }
}