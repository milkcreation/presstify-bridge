<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Pagination;

use tiFy\Partial\PartialView;

class PaginationView extends PartialView
{
    /**
     * Récupération de la page courante.
     *
     * @return mixed
     */
    public function getPage()
    {
        return $this->get('query')->getPage();
    }

    /**
     * Récupération du nombre total de page.
     *
     * @return mixed
     */
    public function getTotalPage()
    {
        return $this->get('query')->getTotalPage();
    }
}