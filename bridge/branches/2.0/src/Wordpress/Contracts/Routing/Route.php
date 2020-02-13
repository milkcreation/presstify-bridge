<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Routing;

use League\Route\Route as LeagueRoute;
use tiFy\Contracts\Routing\Route as BaseRoute;

interface Route extends BaseRoute, WpQueryAwareTrait
{
    /**
     * @inheritDoc
     *
     * @return static
     */
    public function map(string $method, string $path, $handler) : LeagueRoute;

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function get($path, $handler);

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function post($path, $handler);

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function put($path, $handler);

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function patch($path, $handler);

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function delete($path, $handler);

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function head($path, $handler);

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function options($path, $handler);
}