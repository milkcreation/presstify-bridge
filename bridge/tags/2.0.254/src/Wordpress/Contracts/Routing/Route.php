<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Routing;

use tiFy\Contracts\Routing\Route as BaseRoute;

interface Route extends BaseRoute, WpQueryAwareTrait
{

}