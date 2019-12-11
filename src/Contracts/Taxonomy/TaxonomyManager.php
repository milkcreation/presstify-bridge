<?php declare(strict_types=1);

namespace tiFy\Contracts\Taxonomy;

use tiFy\Contracts\Support\Manager;

interface TaxonomyManager extends Manager
{
    /**
     * Récupération de l'instance du controleur de metadonnées de terme.
     *
     * @return TaxonomyTermMeta|null
     */
    public function term_meta(): ?TaxonomyTermMeta;

    /**
     * Résolution d'un service fourni par le gestionnaire.
     *
     * @param string $alias Nom de qualification du service.
     *
     * @return object
     */
    public function resolve(string $alias);
}