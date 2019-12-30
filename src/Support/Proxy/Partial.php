<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Partial\{Partial as PartialContract, PartialDriver};

/**
 * @method static PartialDriver|null get(string $name, array|string|null $id = null, array $attrs = [])
 * @method static PartialContract register(string $name, PartialDriver $partial)
 * @method static PartialContract set(string|array $name, ?PartialDriver $partial = null)
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