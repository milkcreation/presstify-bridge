<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager\Contracts;

use tiFy\Contracts\Template\FactoryAwareTrait;
use tiFy\Contracts\Support\ParamsBag;

interface IconSet extends FactoryAwareTrait, ParamsBag
{
    /**
     * Récupération de l'icône associé à un fichier.
     *
     * @param Fileinfo $file Instance du fichier.
     *
     * @return string
     */
    public function file(FileInfo $file): string;

    /**
     * Rendu d'affichage d'un icône.
     *
     * @param array $attrs Liste des attributs de rendu.
     *
     * @return string
     */
    public function render(array $attrs): string;
}