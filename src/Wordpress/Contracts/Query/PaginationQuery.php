<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Query;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Support\Traits\PaginationAwareTrait;

/**
 * @mixin PaginationAwareTrait
 */
interface PaginationQuery extends ParamsBag
{
    /**
     * Résolution de sortie de la classe sous forme de chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * {@inheritDoc}
     *
     * @return PaginationQuery
     */
    public function parse(): PaginationQuery;

    /**
     * Affichage de l'interface de pagination.
     *
     * @return string
     */
    public function render(): string;
}