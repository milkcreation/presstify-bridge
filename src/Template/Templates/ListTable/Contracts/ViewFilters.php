<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Support\Collection;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface ViewFilters extends Collection, FactoryAwareTrait
{
    /**
     * Récupération de la liste des filtres.
     *
     * @return array|ViewFilter[]
     */
    public function all();

    /**
     * Traitement de la liste des filtres.
     *
     * @param array $filters Liste des filtres.
     *
     * @return static
     */
    public function parse(array $filters = []): ViewFilters;
}