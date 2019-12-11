<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing;

use tiFy\Wordpress\Contracts\Routing\Route as RouteContract;
use tiFy\Wordpress\Routing\Concerns\WpQueryAwareTrait;
use tiFy\Routing\Route as BaseRoute;

class Route extends BaseRoute implements RouteContract
{
    use WpQueryAwareTrait;
}