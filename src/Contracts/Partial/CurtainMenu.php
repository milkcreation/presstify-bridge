<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface CurtainMenu extends PartialFactory
{
    /**
     * Traitement de la liste des éléments.
     *
     * @return void
     */
    public function parseItems(): PartialFactory;
}