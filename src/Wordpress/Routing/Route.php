<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing;

use League\Route\Route as LeagueRoute;
use tiFy\Wordpress\Contracts\Routing\Route as RouteContract;
use tiFy\Wordpress\Routing\Concerns\WpQueryAwareTrait;
use tiFy\Routing\Route as BaseRoute;

class Route extends BaseRoute implements RouteContract
{
    use WpQueryAwareTrait;

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function map(string $method, string $path, $handler): LeagueRoute
    {
        return parent::map($method, $path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function get($path, $handler): LeagueRoute
    {
        return parent::get($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function post($path, $handler): LeagueRoute
    {
        return parent::post($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function put($path, $handler): LeagueRoute
    {
        return parent::put($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function patch($path, $handler): LeagueRoute
    {
        return parent::patch($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function delete($path, $handler): LeagueRoute
    {
        return parent::delete($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function head($path, $handler): LeagueRoute
    {
        return parent::head($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function options($path, $handler): LeagueRoute
    {
        return parent::options($path, $handler);
    }
}