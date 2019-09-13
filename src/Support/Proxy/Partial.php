<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Partial\{Partial as PartialContract, PartialFactory};

/**
 * @method static PartialFactory|null get(string $name, array|string|null $id = null, array $attrs = [])
 * @method static PartialContract set(string $name, PartialFactory $partial)
 */
class Partial extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'partial';
    }
}