<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Pagination extends PartialDriver
{
    /**
     * Récupération d'un séparateur de nombre.
     *
     * @param array $numbers Liste des numéros de page existants.
     *
     * @return void
     */
    public function ellipsis(array &$numbers): void;

    /**
     * Boucle de récupération des numéros de page.
     *
     * @param array $numbers Liste des numéros de page existants.
     * @param int $start Démarrage de la boucle de récupération.
     * @param int $end Fin de la boucle de récupération.
     *
     * @return void
     */
    public function numLoop(array &$numbers, int $start, int $end): void;

    /**
     * Traitement de la liste des liens.
     *
     * @return static
     */
    public function parseLinks(): PartialDriver;

    /**
     * Traitement de la liste des numéros de page.
     *
     * @return static
     */
    public function parseNumbers(): PartialDriver;

    /**
     * Traitement de l'instance du gestionnaire de requête de récupération des éléments.
     *
     * @return static
     */
    public function parseQuery(): PartialDriver;

    /**
     * Traitement des arguments d'url.
     *
     * @return static
     */
    public function parseUrl(): PartialDriver;

    /**
     * Récupération de l'instance du gestionnaire de requête de récupération des arguments de pagination.
     *
     * @return PaginationQuery|null
     */
    public function query(): ?PaginationQuery;
}