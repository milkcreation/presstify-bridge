<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\{
    Contracts\Builder as BaseBuilderContract,
    Contracts\RowAction as BaseRowActionContract,
    ServiceProvider as BaseServiceProvider
};
use tiFy\Template\Templates\PostListTable\Contracts\{
    Db,
    Item,
    Params,
    DbBuilder,
};

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Instance du gabarit d'affichage.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function registerFactoryBuilder(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('builder'), function () {
            $ctrl = $this->factory->provider('builder');

            if ($this->factory->db()) {
                $ctrl = $ctrl instanceof DbBuilder
                    ? $ctrl
                    : $this->getContainer()->get(DbBuilder::class);
            } else {
                $ctrl = $ctrl instanceof BaseBuilderContract
                    ? $ctrl
                    : $this->getContainer()->get(BaseBuilderContract::class);
            }

            $attrs = $this->factory->param('query_args', []);

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : []);
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryColumns(): void
    {
        parent::registerFactoryColumns();

        $this->getContainer()->add(
            $this->getFactoryAlias('column.post_title'),
            function (string $name, array $attrs = []) {
                return (new ColumnPostTitle())
                    ->setTemplateFactory($this->factory)
                    ->setName($name)
                    ->set($attrs)->parse();
            });

        $this->getContainer()->add($this->getFactoryAlias('column.post_type'),
            function (string $name, array $attrs = []) {
                return (new ColumnPostType())
                    ->setTemplateFactory($this->factory)
                    ->setName($name)
                    ->set($attrs)->parse();
            });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryDb(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('db'), function () {
            $ctrl = $this->factory->provider('db');
            $ctrl = $ctrl instanceof Db
                ? $ctrl
                : $this->getContainer()->get(Db::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * D??claration du controleur d'un ??l??ment.
     *
     * @return void
     */
    public function registerFactoryItem(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('item'), function () {
            $ctrl = $this->factory->provider('item');
            $ctrl = $ctrl instanceof Item
                ? clone $ctrl
                : $this->getContainer()->get(Item::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryLabels(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('labels'), function () {
            return (new Labels())
                ->setTemplateFactory($this->factory)
                ->setName($this->factory->name())
                ->set($this->factory->get('labels', []))
                ->parse();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryParams(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('params'), function () {
            $ctrl = $this->factory->provider('params');
            $ctrl = $ctrl instanceof Params
                ? $ctrl
                : $this->getContainer()->get(Params::class);

            $attrs = $this->factory->get('params', []);

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : [])->parse();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryRowActions(): void
    {
        parent::registerFactoryRowActions();

        $this->getContainer()->add($this->getFactoryAlias('row-action.edit'), function (): BaseRowActionContract {
            $ctrl = $this->factory->provider('row-action.edit');
            $ctrl = $ctrl instanceof BaseRowActionContract
                ? clone $ctrl
                : new RowActionEdit();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.show'), function (): BaseRowActionContract {
            $ctrl = $this->factory->provider('row-action.show');
            $ctrl = $ctrl instanceof BaseRowActionContract
                ? clone $ctrl
                : new RowActionShow();

            return $ctrl->setTemplateFactory($this->factory);
        });
    }
    /**
     * @inheritDoc
     */
    public function registerFactoryViewFilters(): void
    {
        parent::registerFactoryViewFilters();

        $this->getContainer()->add($this->getFactoryAlias('view-filter.all'),
            function (string $name, array $attrs = []) {
                return (new ViewFilterAll())
                    ->setTemplateFactory($this->factory)
                    ->setName($name)->set($attrs)->parse();
            });

        $this->getContainer()->add($this->getFactoryAlias('view-filter.publish'),
            function (string $name, array $attrs = []) {
                return (new ViewFilterPublish())
                    ->setTemplateFactory($this->factory)
                    ->setName($name)->set($attrs)->parse();
            });

        $this->getContainer()->add($this->getFactoryAlias('view-filter.trash'),
            function (string $name, array $attrs = []) {
                return (new ViewFilterTrash())
                    ->setTemplateFactory($this->factory)
                    ->setName($name)->set($attrs)->parse();
            });
    }
}