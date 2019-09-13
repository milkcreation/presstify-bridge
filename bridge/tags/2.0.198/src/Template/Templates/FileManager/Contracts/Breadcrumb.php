<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager\Contracts;

use tiFy\Contracts\Template\FactoryAwareTrait;
use tiFy\Contracts\Support\Collection;

interface Breadcrumb extends Collection, FactoryAwareTrait
{
    /**
     * Résolution de sortie de la classe sous la forme d'une chaine de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Définition de la liste des éléments selon un chemin relatif.
     *
     * @param string|null $path Chemin relatif. Si null, utilise le cemin courant.
     *
     * @return Breadcrumb
     */
    public function setPath(?string $path = null): Breadcrumb;
}