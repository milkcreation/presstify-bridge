<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Routing;

interface WpQueryAwareTrait
{
    /**
     * Vérification de l'activation de la requête de récupération des éléments native de Wordpress.
     *
     * @param boolean $active
     *
     * @return static
     */
    public function isWpQuery(): bool;

    /**
     * Définition de l'activation de la requête de récupération des éléments native de Wordpress.
     *
     * @param boolean $active
     *
     * @return static
     */
    public function setWpQuery(bool $active = false): WpQueryAwareTrait;
}