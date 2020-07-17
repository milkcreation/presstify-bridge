<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Field\{Field as FieldContract, FieldDriver};

/**
 * @method static FieldDriver|null get(string $name, array|string|null $id = null, array $attrs = [])
 * @method static FieldContract set(string $name, FieldDriver $field)
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