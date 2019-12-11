<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Partial\{Partial as PartialContract, PartialFactory};

/**
 * @method static PartialFactory|null get(string $name, array|string|null $id = null, array $attrs = [])
 * @method static PartialContract register(string $name, PartialFactory $partial)
 * @method static PartialContract set(string|array $name, ?PartialFactory $partial = null)
 */
class Partial extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return PartialContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'partial';
    }
}