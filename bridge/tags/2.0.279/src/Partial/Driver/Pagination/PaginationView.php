<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\PaginationQuery;
use tiFy\Partial\PartialView;

/**
 * @method PaginationQuery query()
 */
class PaginationView extends PartialView
{
    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        array_push($this->mixins, 'query');

        return parent::__call($method, $parameters);
    }

    /**
     * Récupération de la page courante.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->query()->getCurrentPage();
    }

    /**
     * Récupération de la dernière page.
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->query()->getLastPage();
    }
}