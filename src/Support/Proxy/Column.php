<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Column\{Column as ColumnContract, ColumnItem as ColumnDriverContract};

/**
 * @method static add(string $screen, string $name, array|ColumnDriverContract $attrs)
 * @method static stack(string $screen, array[]|ColumnDriverContract[] $attrs)
 */
class Column extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return ColumnContract
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
        return 'column';
    }
}