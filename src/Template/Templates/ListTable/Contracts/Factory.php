<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Template\{FactoryAjax, FactoryBuilder};
use tiFy\Contracts\Template\TemplateFactory;

interface Factory extends TemplateFactory
{
    /**
     * {@inheritDoc}
     *
     * @return Ajax|null
     */
    public function ajax(): ? FactoryAjax;

    /**
     * Récupération de l'instance du controleur d'actions groupées.
     *
     * @return BulkActions
     */
    public function bulkActions(): BulkActions;

    /**
     * Récupération de l'instance du controleur des colonnes.
     *
     * @return Columns|Columns[]
     */
    public function columns(): Columns;

    /**
     * Récupération de l'instance du gestionnaire d'élément de navigation complémentaire.
     *
     * @param string $which top|bottom
     *
     * @return Extras|Extra[]
     */
    public function extras(string $which = 'top'): Extras;

    /**
     * Récupération d'une instance d'élément à afficher dans une boucle d'itération.
     *
     * @return Item|null
     */
    public function item(): ?Item;

    /**
     * Récupération d'une instance de la liste des éléments à afficher.
     *
     * @return Items|Item[]
     */
    public function items(): Items;

    /**
     * Récupération de la classe de rappel de traitement de la pagination.
     *
     * @return Pagination
     */
    public function pagination(): Pagination;

    /**
     * {@inheritDoc}
     *
     * @return Builder|DbBuilder
     */
    public function builder(): FactoryBuilder;

    /**
     * Récupération de l'instance du controleur des actions sur un élément.
     *
     * @return RowActions
     */
    public function rowActions(): RowActions;

    /**
     * Récupération de l'instance du controleur du formulaire de recherche.
     *
     * @return Search
     */
    public function search(): Search;

    /**
     * Récupération de l'instance du controleur des filtres de la vue.
     *
     * @return ViewFilters
     */
    public function viewFilters(): ViewFilters;
}