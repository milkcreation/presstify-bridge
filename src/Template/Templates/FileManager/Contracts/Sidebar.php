<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager\Contracts;

use tiFy\Contracts\Template\FactoryAwareTrait;

interface Sidebar extends FactoryAwareTrait
{
    /**
     * Résolution de sortie de la classe sous forme d'une chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string;
}