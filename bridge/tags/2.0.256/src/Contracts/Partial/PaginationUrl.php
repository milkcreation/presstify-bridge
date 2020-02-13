<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface PaginationUrl
{
    /**
     * Récupération du lien vers une page via son numéro.
     *
     * @param int $num Numéro de la page.
     *
     * @return string
     */
    public function page(int $num): string;
}