<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use tiFy\Contracts\Routing\Url;

interface FactoryUrl extends FactoryAwareTrait, Url
{
    /**
     * Url vers le controleur des requêtes HTTP ou XHR (selon le contexte).
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function action(bool $absolute = false): string;

    /**
     * Url d'affichage.
     *
     * @return string
     */
    public function display(): string;

    /**
     * Url vers le controleur des requêtes HTTP.
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function http(bool $absolute = false): string;

    /**
     * Définition du chemin des requêtes HTTP et XHR.
     *
     * @param string $base_path
     *
     * @return static
     */
    public function setBasePath(string $base_path): FactoryUrl;

    /**
     * Définition de l'url d'affichage.
     *
     * @param string $display_url
     *
     * @return static
     */
    public function setDisplayUrl(string $display_url): FactoryUrl;

    /**
     * Url vers le contrôleur des requêtes XHR.
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function xhr(bool $absolute = false): string;
}