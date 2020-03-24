<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Template\Factory\ServiceProvider as BaseServiceProvider;
use tiFy\Template\Templates\ListTable\Contracts\{
    Actions as ActionsContract,
    Ajax as AjaxContract,
    Builder,
    BulkAction,
    BulkActions,
    Column,
    Columns,
    DbBuilder,
    Extra,
    Extras,
    HttpXhrController,
    Item,
    Items,
    Pagination,
    Params,
    RowAction,
    RowActions,
    Search,
    ViewFilter,
    ViewFilters
};
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Support\Proxy\View as ProxyView;

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
    public function registerFactories(): void
    {
        parent::registerFactories();

        $this->registerFactoryAjax();
        $this->registerFactoryBulkActions();
        $this->registerFactoryColumns();
        $this->registerFactoryExtras();
        $this->registerFactoryItem();
        $this->registerFactoryItems();
        $this->registerFactoryPagination();
        $this->registerFactoryRowActions();
        $this->registerFactorySearch();
        $this->registerFactoryViewFilters();
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryActions(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('actions'), function (): ActionsContract {
            $ctrl = $this->factory->provider('actions');
            $ctrl = $ctrl instanceof ActionsContract
                ? $ctrl
                : $this->getContainer()->get(ActionsContract::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de gestion de la table en ajax.
     *
     * @return void
     */
    public function registerFactoryAjax(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('ajax'), function () {
            if ($attrs = $this->factory->param('ajax')) {
                $ajax = $this->factory->provider('ajax');
                $ajax = $ajax instanceof AjaxContract
                    ? $ajax
                    : $this->getContainer()->get(AjaxContract::class);

                if (is_string($attrs)) {
                    $attrs = [
                        'url'      => $attrs,
                        'dataType' => 'json',
                        'type'     => 'POST',
                    ];
                }

                return $ajax->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : []);
            } else {
                return null;
            }
        });
    }

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
                $ctrl = $ctrl instanceof Builder
                    ? $ctrl
                    : $this->getContainer()->get(Builder::class);
            }

            $attrs = $this->factory->param('query_args', []);

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : []);
        });
    }

    /**
     * Déclaration des controleurs d'actions groupées.
     *
     * @return void
     */
    public function registerFactoryBulkActions(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('bulk-actions'), function () {
            $ctrl = $this->factory->provider('bulk-actions');
            $ctrl = $ctrl instanceof BulkActions
                ? $ctrl
                : $this->getContainer()->get(BulkActions::class);

            $attrs = $this->factory->param('bulk-actions', []);

            return $ctrl->setTemplateFactory($this->factory)->parse(is_array($attrs) ? $attrs : []);
        });

        $this->getContainer()->add($this->getFactoryAlias('bulk-action'), function (string $name, array $attrs = []) {
            $ctrl = $this->factory->provider('bulk-action');
            $ctrl = $ctrl instanceof BulkAction
                ? clone $ctrl
                : $this->getContainer()->get(BulkAction::class, [$name, $attrs]);

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('bulk-action.trash'),
            function (string $name, array $attrs = []) {
                return new BulkActionTrash($name, $attrs);
            });
    }

    /**
     * Déclaration des controleurs de colonnes de la table.
     *
     * @return void
     */
    public function registerFactoryColumns(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('columns'), function () {
            $ctrl = $this->factory->provider('columns');
            $ctrl = $ctrl instanceof Columns
                ? $ctrl
                : $this->getContainer()->get(Columns::class);

            if ($this->factory->param()->has('columns')) {
                $columns = $this->factory->param('columns', []);
            } elseif ($this->factory->db()) {
                $columns = $this->factory->db()->getColumns();
            } else {
                $columns = [];
            }

            return $ctrl->setTemplateFactory($this->factory)->parse($columns);
        });

        $this->getContainer()->add($this->getFactoryAlias('column'), function (string $name, array $attrs = []) {
            $ctrl = $this->factory->provider('column');
            $ctrl = $ctrl instanceof Column
                ? clone $ctrl
                : $this->getContainer()->get(Column::class);

            return $ctrl->setTemplateFactory($this->factory)->setName($name)->set($attrs)->parse();
        });

        $this->getContainer()->add($this->getFactoryAlias('column.cb'), function (string $name, array $attrs = []) {
            return (new ColumnCb())->setTemplateFactory($this->factory)->setName($name)->set($attrs)->parse();
        });

        $this->getContainer()->add($this->getFactoryAlias('column.num'), function (string $name, array $attrs = []) {
            return (new ColumnNum())->setTemplateFactory($this->factory)->setName($name)->set($attrs)->parse();
        });
    }

    /**
     * Déclaration des controleurs de navigation complémentaires.
     *
     * @return void
     */
    public function registerFactoryExtras(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('extras'), function (): Extras {
            $ctrl = $this->factory->provider('extras');
            $ctrl = $ctrl instanceof Extras
                ? $ctrl
                : $this->getContainer()->get(Extras::class);

            return $ctrl->setTemplateFactory($this->factory)->set($this->factory->param('extras', []));
        });

        $this->getContainer()->add($this->getFactoryAlias('extra'), function (): Extra {
            $ctrl = $this->factory->provider('extra');
            $ctrl = $ctrl instanceof Extra
                ? clone $ctrl
                : $this->getContainer()->get(Extra::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryHttpXhrController(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('xhr'), function () {
            $ctrl = $this->factory->provider('xhr');
            $ctrl = $ctrl instanceof HttpXhrController
                ? clone $ctrl
                : $this->getContainer()->get(HttpXhrController::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur d'un élément.
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
     * Déclaration des controleurs d'éléments.
     *
     * @return void
     */
    public function registerFactoryItems(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('items'), function () {
            $ctrl = $this->factory->provider('items');

            $ctrl = $ctrl instanceof Items
                ? $ctrl
                : $this->getContainer()->get(Items::class);

            if (($primary_key = $this->factory->param('primary_key'))) {
                $ctrl->setPrimaryKey((string)$primary_key);
            } elseif ($db = $this->factory->db()) {
                $ctrl->setPrimaryKey($db->getKeyName());
            }

            return $ctrl->setTemplateFactory($this->factory)->set($this->factory->get('items', []));
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
     * Déclaration du controleur de pagination.
     *
     * @return void
     */
    public function registerFactoryPagination(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('pagination'), function () {
            $ctrl = $this->factory->provider('pagination');
            $ctrl = $ctrl instanceof Pagination
                ? $ctrl
                : $this->getContainer()->get(Pagination::class);

            $attrs = $this->factory->param('pagination', []);

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : []);
        });
    }

    /**
     * Déclaration des controleurs d'action sur une ligne d'élément.
     *
     * @return void
     */
    public function registerFactoryRowActions(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('row-actions'), function () {
            $ctrl = $this->factory->provider('row-actions');
            $ctrl = $ctrl instanceof RowActions
                ? $ctrl
                : $this->getContainer()->get(RowActions::class);

            $attrs = $this->factory->param('row-actions', []);

            return $ctrl->setTemplateFactory($this->factory)->parse(is_array($attrs) ? $attrs : []);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : $this->getContainer()->get(RowAction::class);

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.activate'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.activate');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionActivate();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.deactivate'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.deactivate');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionDeactivate();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.delete'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.delete');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionDelete();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.duplicate'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.duplicate');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionDuplicate();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.edit'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.edit');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionEdit();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.preview'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.preview');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionPreview();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.show'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.show');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionShow();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.trash'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.trash');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionTrash();

            return $ctrl->setTemplateFactory($this->factory);
        });

        $this->getContainer()->add($this->getFactoryAlias('row-action.untrash'), function (): RowAction {
            $ctrl = $this->factory->provider('row-action.untrash');
            $ctrl = $ctrl instanceof RowAction
                ? clone $ctrl
                : new RowActionUntrash();

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleurs de gestion du formulaire de recherche.
     *
     * @return void
     */
    public function registerFactorySearch(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('search'), function () {
            $ctrl = $this->factory->provider('search');
            $ctrl = $ctrl instanceof Search
                ? $ctrl
                : $this->getContainer()->get(Search::class);

            $attrs = $this->factory->param('search', []);

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : [])->parse();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryViewer(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('viewer'), function () {
            $params = $this->factory->get('viewer', []);

            if ( ! $params instanceof ViewEngine) {
                $viewer = ProxyView::getPlatesEngine(array_merge([
                    'directory' => template()->resourcesDir('/views/list-table'),
                    'factory'   => View::class
                ], $params));
            } else {
                $viewer = $params;
            }

            $viewer->params(['template' => $this->factory]);

            return $viewer;
        });
    }

    /**
     * Déclaration des controleurs de filtres de la vue.
     *
     * @return void
     */
    public function registerFactoryViewFilters(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('view-filters'), function () {
            $ctrl = $this->factory->provider('view-filters');
            $ctrl = $ctrl instanceof ViewFilters
                ? $ctrl
                : $this->getContainer()->get(ViewFilters::class);

            $attrs = $this->factory->param('view-filters', []);

            return $ctrl->setTemplateFactory($this->factory)->parse(is_array($attrs) ? $attrs : []);
        });

        $this->getContainer()->add($this->getFactoryAlias('view-filter'), function (string $name, array $attrs = []) {
            $ctrl = $this->factory->provider('view-filter');
            $ctrl = $ctrl instanceof ViewFilter
                ? clone $ctrl
                : $this->getContainer()->get(ViewFilter::class);

            return $ctrl->setTemplateFactory($this->factory)->setName($name)->set($attrs)->parse();
        });
    }
}