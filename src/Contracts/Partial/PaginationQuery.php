<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\Collection as CollectionContract;

interface PaginationQuery extends CollectionContract
{
    /**
     * Récupération du numéro de la page courante.
     *
     * @return int
     */
    public function getPage(): int;

    /**
     * Récupération du nombre total de page.
     *
     * @return int
     */
    public function getTotalPage(): int;

    /**
     * Définition des arguments de pagination.
     *
     * @return static
     */
    public function setPagination(): PaginationQuery;
}