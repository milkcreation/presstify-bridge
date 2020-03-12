<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Template\FactoryAwareTrait;
use tiFy\Support\Traits\PaginationAwareTrait;

/**
 * @mixin PaginationAwareTrait
 */
interface Pagination extends FactoryAwareTrait, ParamsBag
{
    /**
     * Récupération de l'emplacement d'affichage de l'interface de pagination.
     *
     * @return string
     */
    public function getWhich(): string;

    /**
     * Vérification de la désactivation du lien vers la première page.
     *
     * @return boolean
     */
    public function isDisableFirst(): bool;

    /**
     * Vérification de la désactivation du lien vers la dernière page.
     *
     * @return boolean
     */
    public function isDisableLast(): bool;

    /**
     * Vérification de la désactivation du lien vers la page suivante.
     *
     * @return boolean
     */
    public function isDisableNext(): bool;

    /**
     * Vérification de la désactivation du lien vers la page précédente.
     *
     * @return boolean
     */
    public function isDisablePrev(): bool;

    /**
     * Récupération de l'url vers une page.
     *
     * @param int $page Numéro de la page.
     *
     * @return string
     */
    public function pagedUrl(int $page): string;

    /**
     * {@inheritDoc}
     *
     * @return Pagination
     */
    public function parse(): Pagination;

    /**
     * Traitement des attributs de configuration de la première page.
     *
     * @return Pagination
     */
    public function parseFirst(): Pagination;

    /**
     * Traitement des attributs de configuration de la dernière page.
     *
     * @return Pagination
     */
    public function parseLast(): Pagination;

    /**
     * Traitement des attributs de configuration de la page suivante.
     *
     * @return Pagination
     */
    public function parseNext(): Pagination;

    /**
     * Traitement des attributs de configuration de la page précédente.
     *
     * @return Pagination
     */
    public function parsePrev(): Pagination;

    /**
     * Définition de l'emplacement d'affichage.
     *
     * @param string $which top|bottom
     *
     * @return static
     */
    public function setWhich(string $which): Pagination;

    /**
     * Récupération de l'url de la page courante sans l'argument de pagination.
     *
     * @return string
     */
    public function unpagedUrl(): string;
}