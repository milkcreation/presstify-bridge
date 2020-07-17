<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface CurtainMenu extends PartialDriver
{
    /**
     * Traitement de la liste des éléments.
     *
     * @return static
     */
    public function parseItems(): PartialDriver;
}