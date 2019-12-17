<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager\Contracts;

use tiFy\Contracts\Template\FactoryAwareTrait;

interface FileTag extends FactoryAwareTrait
{
    /**
     * Récupération des mots clefs associés au fichier.
     *
     * @return array
     */
    public function get(): array;

    /**
     * Vérification d'existance de mots clefs.
     *
     * @param string|array $tag Mot(s) clef(s) à vérifier.
     *
     * @return boolean
     */
    public function has($tag): bool;

    /**
     * Traitement de la liste des mot clef d'un fichier.
     *
     * @return $this
     */
    public function parse(): FileTag;

    /**
     * Définition de mots clefs.
     *
     * @param string|string[]
     *
     * @return $this
     */
    public function set($tag): FileTag;

    /**
     * Définition du fichier associé.
     *
     * @param FileInfo $file
     *
     * @return $this
     */
    public function setFile(FileInfo $file): FileTag;

    /**
     * Réinitialisation de la liste des mots clefs.
     *
     * @return $this
     */
    public function reset(): FileTag;
}