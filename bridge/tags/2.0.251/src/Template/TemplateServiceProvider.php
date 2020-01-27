<?php declare(strict_types=1);

namespace tiFy\Template;

use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Template\TemplateFactory as TemplateFactoryContract;
use tiFy\Template\Templates\FileManager\Contracts\{
    Ajax as FileManagerAjaxContract,
    Breadcrumb as FileManagerBreadcrumbContract,
    Cache as FileManagerCacheContract,
    FileCollection as FileManagerFileCollectionContract,
    FileInfo as FileManagerFileInfoContract,
    FileManager as FileManagerContract,
    Filesystem as FileManagerFilesystemContract,
    FileTag as FileManagerFileTagContract,
    HttpController as FileManagerHttpControllerContract,
    HttpXhrController as FileManagerHttpXhrControllerContract,
    IconSet as FileManagerIconSetContract,
    Params as FileManagerParamsContract,
    Sidebar as FileManagerSidebarContract};
use tiFy\Template\Templates\ListTable\Contracts\{
    Actions as ListTableActionsContract,
    Ajax as ListTableAjaxContract,
    Assets as ListTableAssetsContract,
    Builder as ListTableBuilderContract,
    BulkAction as ListTableBulkActionContract,
    BulkActions as ListTableBulkActionsContract,
    Column as ListTableColumnContract,
    Columns as ListTableColumnsContract,
    Extra as ListTableExtraContract,
    Extras as ListTableExtrasContract,
    DbBuilder as ListTableDbBuilderContract,
    HttpXhrController as ListTableHttpXhrControllerContract,
    Item as ListTableItemContract,
    Items as ListTableItemsContract,
    Pagination as ListTablePaginationContract,
    Params as ListTableParamsContract,
    RowAction as ListTableRowActionContract,
    RowActions as ListTableRowActionsContract,
    Search as ListTableSearchContract,
    ViewFilter as ListTableViewFilterContract,
    ViewFilters as ListTableViewFiltersContract};
use tiFy\Template\Templates\PostListTable\Contracts\{
    DbBuilder as PostListTableDbBuilderContract,
    Db as PostListTableDbContract,
    Item as PostListTableItemContract,
    Params as PostListTableParamsContract};
use tiFy\Template\Templates\UserListTable\Contracts\{
    DbBuilder as UserListTableDbBuilderContract,
    Db as UserListTableDbContract,
    Item as UserListTableItemContract};
use tiFy\Template\Templates\FileManager\{
    Ajax as FileManagerAjax,
    Breadcrumb as FileManagerBreadcrumb,
    Cache as FileManagerCache,
    FileCollection as FileManagerFileCollection,
    FileInfo as FileManagerFileInfo,
    Filesystem as FileManagerFilesystem,
    FileTag as FileManagerFileTag,
    HttpController as FileManagerHttpController,
    HttpXhrController as FileManagerHttpXhrController,
    IconSet as FileManagerIconSet,
    Params as FileManagerParams,
    Sidebar as FileManagerSidebar};
use tiFy\Template\Templates\ListTable\{
    Actions as ListTableActions,
    Ajax as ListTableAjax,
    Assets as ListTableAssets,
    Builder as ListTableBuilder,
    BulkAction as ListTableBulkAction,
    BulkActions as ListTableBulkActions,
    Column as ListTableColumn,
    Columns as ListTableColumns,
    Extra as ListTableExtra,
    Extras as ListTableExtras,
    DbBuilder as ListTableDbBuilder,
    HttpXhrController as ListTableHttpXhrController,
    Item as ListTableItem,
    Items as ListTableItems,
    Pagination as ListTablePagination,
    Params as ListTableParams,
    RowAction as ListTableRowAction,
    RowActions as ListTableRowActions,
    Search as ListTableSearch,
    ViewFilter as ListTableViewFilter,
    ViewFilters as ListTableViewFilters};
use tiFy\Template\Templates\PostListTable\{
    DbBuilder as PostListTableDbBuilder,
    Db as PostListTableDb,
    Item as PostListTableItem,
    Params as PostListTableParams};
use tiFy\Template\Templates\UserListTable\{
    DbBuilder as UserListTableDbBuilder,
    Db as UserListTableDb,
    Item as UserListTableItem};

class TemplateServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'template',
        TemplateFactoryContract::class,
        // FileManager
        FileManagerAjaxContract::class,
        FileManagerBreadcrumbContract::class,
        FileManagerCacheContract::class,
        FileManagerFileCollectionContract::class,
        FileManagerFileInfoContract::class,
        FileManagerFilesystemContract::class,
        FileManagerFileTagContract::class,
        FileManagerHttpControllerContract::class,
        FileManagerHttpXhrControllerContract::class,
        FileManagerIconSetContract::class,
        FileManagerParamsContract::class,
        FileManagerSidebarContract::class,
        // ListTable
        ListTableActionsContract::class,
        ListTableAjaxContract::class,
        ListTableAssetsContract::class,
        ListTableBulkActionContract::class,
        ListTableBulkActionsContract::class,
        ListTableColumnContract::class,
        ListTableColumnsContract::class,
        ListTableExtraContract::class,
        ListTableExtrasContract::class,
        ListTableHttpXhrControllerContract::class,
        ListTableItemContract::class,
        ListTableItemsContract::class,
        ListTablePaginationContract::class,
        ListTableParamsContract::class,
        ListTableDbBuilderContract::class,
        ListTableRowActionContract::class,
        ListTableRowActionsContract::class,
        ListTableSearchContract::class,
        ListTableViewFilterContract::class,
        ListTableViewFiltersContract::class,
        // PostTable
        PostListTableDbContract::class,
        PostListTableItemContract::class,
        PostListTableParamsContract::class,
        PostListTableDbBuilderContract::class,
        // UserTable
        UserListTableDbContract::class,
        UserListTableItemContract::class,
        UserListTableDbBuilderContract::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('template', function () {
            return new TemplateManager($this->getContainer());
        });

        $this->getContainer()->add(TemplateFactoryContract::class, function () {
            return new TemplateFactory();
        });

        $this->registerFileManager();
        $this->registerListTable();
        $this->registerPostListTable();
        $this->registerUserListTable();
    }

    /**
     * @inheritDoc
     */
    public function registerFileManager(): void
    {
        $this->getContainer()->add(FileManagerAjaxContract::class, function () {
            return new FileManagerAjax();
        });

        $this->getContainer()->add(FileManagerBreadcrumbContract::class, function () {
            return new FileManagerBreadcrumb();
        });

        $this->getContainer()->add(FileManagerCacheContract::class, function () {
            return new FileManagerCache();
        });

        $this->getContainer()->add(FileManagerFileCollectionContract::class, function () {
            return new FileManagerFileCollection();
        });

        $this->getContainer()->add(FileManagerFileInfoContract::class, function (array $infos) {
            return new FileManagerFileInfo($infos);
        });

        $this->getContainer()->add(FileManagerFilesystemContract::class, function (FileManagerContract $factory) {
            return FileManagerFilesystem::createFromFactory($factory);
        });

        $this->getContainer()->add(FileManagerFileTagContract::class, function () {
            return new FileManagerFileTag();
        });

        $this->getContainer()->add(FileManagerHttpControllerContract::class, function () {
            return new FileManagerHttpController();
        });

        $this->getContainer()->add(FileManagerHttpXhrControllerContract::class, function () {
            return new FileManagerHttpXhrController();
        });

        $this->getContainer()->add(FileManagerIconSetContract::class, function () {
            return new FileManagerIconSet();
        });

        $this->getContainer()->add(FileManagerParamsContract::class, function () {
            return new FileManagerParams();
        });

        $this->getContainer()->add(FileManagerSidebarContract::class, function () {
            return new FileManagerSidebar();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerListTable(): void
    {
        $this->getContainer()->add(ListTableActionsContract::class, function () {
            return new ListTableActions();
        });

        $this->getContainer()->add(ListTableAjaxContract::class, function () {
            return new ListTableAjax();
        });

        $this->getContainer()->add(ListTableAssetsContract::class, function () {
            return new ListTableAssets();
        });

        $this->getContainer()->add(ListTableBuilderContract::class, function () {
            return new ListTableBuilder();
        });

        $this->getContainer()->add(ListTableBulkActionContract::class, function (string $name, array $attrs = []) {
            return new ListTableBulkAction($name, $attrs);
        });

        $this->getContainer()->add(ListTableBulkActionsContract::class, function () {
            return new ListTableBulkActions();
        });

        $this->getContainer()->add(ListTableColumnContract::class, function () {
            return new ListTableColumn();
        });

        $this->getContainer()->add(ListTableColumnsContract::class, function () {
            return new ListTableColumns();
        });

        $this->getContainer()->add(ListTableExtraContract::class, function () {
            return new ListTableExtra();
        });

        $this->getContainer()->add(ListTableExtrasContract::class, function () {
            return new ListTableExtras();
        });

        $this->getContainer()->add(ListTableDbBuilderContract::class, function () {
            return new ListTableDbBuilder();
        });

        $this->getContainer()->add(ListTableHttpXhrControllerContract::class, function () {
            return new ListTableHttpXhrController();
        });

        $this->getContainer()->add(ListTableItemContract::class, function () {
            return new ListTableItem();
        });

        $this->getContainer()->add(ListTableItemsContract::class, function () {
            return new ListTableItems();
        });

        $this->getContainer()->add(ListTablePaginationContract::class, function () {
            return new ListTablePagination();
        });

        $this->getContainer()->add(ListTableParamsContract::class, function () {
            return new ListTableParams();
        });

        $this->getContainer()->add(ListTableRowActionContract::class, function () {
            return new ListTableRowAction();
        });

        $this->getContainer()->add(ListTableRowActionsContract::class, function () {
            return new ListTableRowActions();
        });

        $this->getContainer()->add(ListTableSearchContract::class, function () {
            return new ListTableSearch();
        });

        $this->getContainer()->add(ListTableViewFilterContract::class, function () {
            return new ListTableViewFilter();
        });

        $this->getContainer()->add(ListTableViewFiltersContract::class, function () {
            return new ListTableViewFilters();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerPostListTable(): void
    {
        $this->getContainer()->add(PostListTableDbContract::class, function () {
            return new PostListTableDb();
        });

        $this->getContainer()->add(PostListTableDbBuilderContract::class, function () {
            return new PostListTableDbBuilder();
        });

        $this->getContainer()->add(PostListTableItemContract::class, function () {
            return new PostListTableItem();
        });

        $this->getContainer()->add(PostListTableParamsContract::class, function () {
            return new PostListTableParams();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerUserListTable(): void
    {
        $this->getContainer()->add(UserListTableDbContract::class, function () {
            return new UserListTableDb();
        });

        $this->getContainer()->add(UserListTableDbBuilderContract::class, function () {
            return new UserListTableDbBuilder();
        });

        $this->getContainer()->add(UserListTableItemContract::class, function () {
            return new UserListTableItem();
        });
    }
}