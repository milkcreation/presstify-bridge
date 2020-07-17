<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Contracts\Template\{FactoryAjax, FactoryBuilder, TemplateFactory as TemplateFactoryContract};
use tiFy\Template\TemplateFactory as BaseFactory;
use tiFy\Template\Templates\ListTable\Contracts\{
    Ajax,
    Builder,
    BulkActions,
    Columns,
    DbBuilder,
    Extras,
    Item,
    Items,
    Factory as FactoryContract,
    Pagination,
    RowActions,
    Search,
    ViewFilters
};

class Factory extends BaseFactory implements FactoryContract
{
    /**
     * Liste des fournisseurs de services.
     * @var string[]
     */
    protected $serviceProviders = [
        ServiceProvider::class,
    ];

    /**
     * {@inheritDoc}
     *
     * @return Ajax|null
     */
    public function ajax(): ?FactoryAjax
    {
        return parent::ajax();
    }

    /**
     * {@inheritDoc}
     *
     * @return Builder|DbBuilder
     */
    public function builder(): FactoryBuilder
    {
        return parent::builder();
    }

    /**
     * @inheritDoc
     */
    public function bulkActions(): BulkActions
    {
        return $this->resolve('bulk-actions');
    }

    /**
     * @inheritDoc
     */
    public function columns(): Columns
    {
        return $this->resolve('columns');
    }

    /**
     * @inheritDoc
     */
    public function extras(string $which = 'top'): Extras
    {
        return $this->resolve('extras')->setWhich($which);
    }

    /**
     * @inheritDoc
     */
    public function item(): ?Item
    {
        return $this->items()->current();
    }

    /**
     * @inheritDoc
     */
    public function items(): Items
    {
        return $this->resolve('items');
    }

    /**
     * @inheritDoc
     */
    public function pagination(): Pagination
    {
        return $this->resolve('pagination');
    }

    /**
     * {@inheritDoc}
     *
     * @return FactoryContract
     */
    public function proceed(): TemplateFactoryContract
    {
        $this->builder()->fetchItems();

        if (!$this->items()->exists()) {
            return $this;
        } elseif ($ajax = $this->ajax()) {
            $ajax->parse();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return $this->viewer('list-table');
    }

    /**
     * @inheritDoc
     */
    public function rowActions(): RowActions
    {
        return $this->resolve('row-actions');
    }

    /**
     * @inheritDoc
     */
    public function search(): Search
    {
        return $this->resolve('search');
    }

    /**
     * @inheritDoc
     */
    public function viewFilters(): ViewFilters
    {
        return $this->resolve('view-filters');
    }
}