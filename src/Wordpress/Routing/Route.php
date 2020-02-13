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
     * @return self
     */
    public function map(string $method, string $path, $handler) : LeagueRoute
    {
        return parent::map($method, $path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return self
     */
    public function get($path, $handler)
    {
        return parent::get($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return self
     */
    public function post($path, $handler)
    {
        return parent::post($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return self
     */
    public function put($path, $handler)
    {
        return parent::put($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return self
     */
    public function patch($path, $handler)
    {
        return parent::patch($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return self
     */
    public function delete($path, $handler)
    {
        return parent::delete($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return self
     */
    public function head($path, $handler)
    {
        return parent::head($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return self
     */
    public function options($path, $handler)
    {
        return parent::options($path, $handler);
    }
}