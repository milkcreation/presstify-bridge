<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Contracts\Template\{FactoryDb, TemplateFactory};
use tiFy\Template\Templates\ListTable\{
    Factory as BaseFactory,
    Contracts\Item as BaseItem
};
use tiFy\Template\Templates\PostListTable\Contracts\{Db, Item, Factory as FactoryContract};

class Factory extends BaseFactory implements FactoryContract
{
    /**
     * Liste des fournisseurs de service.
     * @var string[]
     */
    protected $serviceProviders = [
        ServiceProvider::class,
    ];

    /**
     * {@inheritDoc}
     *
     * @return Db
     */
    public function db(): FactoryDb
    {
        return parent::db();
    }

    /**
     * @inheritDoc
     *
     * @return Item
     */
    public function item(): ?BaseItem
    {
        return parent::item();
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function prepare(): TemplateFactory
    {
        return parent::prepare();
    }
}