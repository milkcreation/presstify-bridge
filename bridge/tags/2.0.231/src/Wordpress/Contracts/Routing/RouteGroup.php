<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Routing;

use tiFy\Contracts\Routing\RouteGroup as BaseRouteGroup;

interface RouteGroup extends BaseRouteGroup, WpQueryAwareTrait
{

}