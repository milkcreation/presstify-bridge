<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager\Contracts;

use tiFy\Contracts\Support\Collection;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface FileCollection extends Collection, FactoryAwareTrait
{
    /**
     * Résolution de sortie de la classe sous la forme d'une chaine de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Trie la liste des fichiers déclarés par répertoire.
     *
     * @return FileCollection
     */
    public function sortByDir(): FileCollection;
}