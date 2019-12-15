<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface CurtainMenu extends PartialFactory
{
    /**
     * Traitement de la liste des éléments.
     *
     * @return static
     */
    public function parseItems(): PartialFactory;
}