<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

interface Url extends UrlFactory
{
    /**
     * Récupération de l'url de requête courante.
     *
     * @param boolean $full Activation de l'inclusion des arguments de requête.
     *
     * @return UrlFactory
     */
    public function current(bool $full = true): UrlFactory;

    /**
     * Récupération du chemin relatif vers une ressource du site.
     *
     * @param string $url Url de la ressource
     *
     * @return string|null
     */
    public function rel(string $url): ?string;

    /**
     * Récupération de la sous arborescence du chemin de l'url.
     *
     * @return string
     */
    public function rewriteBase(): string;

    /**
     * Récupération de l'url vers la racine du site.
     *
     * @param string $path Chemin relatif vers une ressource du site.
     *
     * @return UrlFactory
     */
    public function root(string $path = ''): UrlFactory;
}