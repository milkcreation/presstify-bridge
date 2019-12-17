<?php declare(strict_types=1);

namespace tiFy\Template\Templates\UserListTable;

use tiFy\Template\Templates\ListTable\{
    Contracts\Item as BaseItem,
    Factory as BaseFactory
};
use tiFy\Template\Templates\UserListTable\Contracts\Factory as FactoryContract;

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
     * @inheritDoc
     *
     * @return Item
     */
    public function item(): ?BaseItem
    {
        return parent::item();
    }
}