<?php declare(strict_types=1);

namespace tiFy\Contracts\Kernel;

/**
 * Interface ClassLoader
 * @package tiFy\Kernel
 *
 * @mixin \Composer\Autoload\ClassLoader
 */
interface ClassLoader
{
    /**
     * Déclaration d'un jeu de répertoire PSR-0|PSR-4 pour un espace de nom ou auto-inclusion de fichier.
     *
     * @param string $prefix Espace de nom de qualification.
     * @param array|string $paths Chemin(s) vers le(s) repertoire(s) de l'espace de nom.
     * @param string $type psr-4|psr-0|files|@todo classmap.
     *
     * @return $this
     */
    public function load(string $prefix, $paths, string $type = 'psr-4'): ClassLoader;
}