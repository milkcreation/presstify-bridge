<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Taxonomy\{Taxonomy as TaxonomyContract, TaxonomyFactory, TaxonomyTermMeta};

/**
 * @method static TaxonomyFactory|null get(string $name)
 * @method static TaxonomyTermMeta meta()
 * @method static TaxonomyFactory register(string $name, array $args = [])
 */
class Taxonomy extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return TaxonomyContract
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
        return 'taxonomy';
    }
}