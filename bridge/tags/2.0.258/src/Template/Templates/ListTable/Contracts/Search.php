<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface Search extends FactoryAwareTrait, ParamsBag
{
    /**
     * Résolution de sortie d'une instance de la classe sous la forme d'une chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Vérification d'affichage du formulaire de recherche.
     *
     * @return boolean
     */
    public function exists(): bool;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;
}