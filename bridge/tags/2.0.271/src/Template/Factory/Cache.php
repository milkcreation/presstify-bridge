<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryCache as FactoryCacheContract;
use tiFy\Filesystem\StaticCacheManager;

class Cache extends StaticCacheManager implements FactoryCacheContract
{
    use FactoryAwareTrait;
}