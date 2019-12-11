<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Contracts\Template\FactoryBuilder as FactoryBuilderContract;
use tiFy\Template\Factory\Builder as BaseBuilder;
use tiFy\Template\Templates\ListTable\Contracts\{Builder as BuilderContract, Item};

class Builder extends BaseBuilder implements BuilderContract
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
    public function fetchItems(): BuilderContract
    {
        $this->parse();

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return BuilderContract
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