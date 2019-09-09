<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial;

use tiFy\Contracts\Partial\{
    Accordion as AccordionContract,
    Breadcrumb as BreadcrumbContract,
    CookieNotice as CookieNoticeContract,
    CurtainMenu as CurtainMenuContract,
    Dropdown as DropdownContract,
    Holder as HolderContract,
    Modal as ModalContract,
    Notice as NoticeContract,
    Pagination as PaginationContract,
    Partial as Manager,
    Sidebar as SidebarContract,
    Slider as SliderContract,
    Spinner as SpinnerContract,
    Tab as TabContract,
    Table as TableContract
};
use tiFy\Wordpress\Partial\Partials\{
    Accordion\Accordion,
    Breadcrumb\Breadcrumb,
    CookieNotice\CookieNotice,
    CurtainMenu\CurtainMenu,
    Dropdown\Dropdown,
    Holder\Holder,
    Modal\Modal,
    Notice\Notice,
    Pagination\Pagination,
    Sidebar\Sidebar,
    Slider\Slider,
    Spinner\Spinner,
    Tab\Tab,
    Table\Table,
};

class Partial
{
    /**
     * Instance du gestionnaire des portions d'affichage.
     * @var Manager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR
     *
     * @param Manager $manager Instance du gestionnaire des portions d'affichage.
     *
     * @return void
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;

        $this->registerOverride();

        $this->manager->registerDefaults();
    }

    /**
     * Déclaration des controleurs de surchage des portions d'affichage.
     *
     * @return void
     */
    public function registerOverride(): void
    {
        app()->add(AccordionContract::class, function () {
            return new Accordion();
        });

        app()->add(BreadcrumbContract::class, function () {
            return new Breadcrumb();
        });

        app()->add(CookieNoticeContract::class, function () {
            return new CookieNotice();
        });

        app()->add(CurtainMenuContract::class, function () {
            return new CurtainMenu();
        });

        app()->add(DropdownContract::class, function () {
            return new Dropdown();
        });

        app()->add(HolderContract::class, function () {
            return new Holder();
        });

        app()->add(ModalContract::class, function () {
            return new Modal();
        });

        app()->add(NoticeContract::class, function () {
            return new Notice();
        });

        app()->add(PaginationContract::class, function () {
            return new Pagination();
        });

        app()->add(SidebarContract::class, function () {
            return new Sidebar();
        });

        app()->add(SliderContract::class, function () {
            return new Slider();
        });

        app()->add(SpinnerContract::class, function () {
            return new Spinner();
        });

        app()->add(TabContract::class, function () {
            return new Tab();
        });

        app()->add(TableContract::class, function () {
            return new Table();
        });
    }
}