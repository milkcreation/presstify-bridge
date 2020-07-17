<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use tiFy\Contracts\Filesystem\StaticCacheManager;

interface FactoryCache extends FactoryAwareTrait, StaticCacheManager
{

}