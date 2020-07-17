<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Contracts\Template\FactoryBuilder as FactoryBuilderContract;
use tiFy\Template\Factory\DbBuilder as BaseDbBuilder;
use tiFy\Template\Templates\ListTable\Contracts\{DbBuilder as DbBuilderContract, Item};

class DbBuilder extends BaseDbBuilder implements DbBuilderContract
{
    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function getItem(string $key): ?Item
    {
        $this->parse();

        return null;
    }

    /**
     * @inheritDoc
     */
    public function fetchItems(): DbBuilderContract
    {
        $this->parse();

        $this->querySearch();
        $this->queryWhere();
        $this->queryOrder();

        $total = $this->query()->count();

        if ($total < $this->getPerPage()) {
            $this->setPage(1);
        }

        $this->queryLimit();

        $items = $this->query()->get();

        $this->factory->items()->set($items);

        if ($count = $items->count()) {
            $this->factory->pagination()
                ->setCount($count)
                ->setCurrentPage($this->getPage())
                ->setPerPage($this->getPerPage())
                ->setLastPage((int)ceil($total / $this->getPerPage()))
                ->setTotal($total)
                ->parse();
        }

        $this->resetQuery();

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return DbBuilderContract
     */
    public function parse(): FactoryBuilderContract
    {
        parent::parse();

        if ($this->factory->ajax() && $this->pull('draw', 0)) {
            $this
                ->setSearch((string)$this->get('search.value', $this->getSearch()))
                ->setPerPage((int)$this->pull('length', $this->getPerPage()))
                ->setPage((int)ceil(($this->pull('start') / $this->getPerPage()) + 1));

            $this->pull('columns');
            $this->pull('search');
            $this->pull('action');
        }

        return $this;
    }
}