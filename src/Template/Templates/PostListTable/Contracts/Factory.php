<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable\Contracts;

use tiFy\Contracts\Template\{FactoryDb, TemplateFactory};
use tiFy\Template\Templates\ListTable\Contracts\{Item as BaseItem, Factory as BaseFactory};

interface Factory extends BaseFactory
{
    /**
     * {@inheritDoc}
     *
     * @return Db
     */
    public function db(): FactoryDb;

    /**
     * @inheritDoc
     *
     * @return Item
     */
    public function item(): ?BaseItem;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function prepare(): TemplateFactory;
}