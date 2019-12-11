<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Field\{Field as FieldContract, FieldFactory};

/**
 * @method static FieldFactory|null get(string $name, array|string|null $id = null, array $attrs = [])
 * @method static FieldContract set(string $name, FieldFactory $field)
 */
class Field extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return FieldContract
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
        return 'field';
    }
}