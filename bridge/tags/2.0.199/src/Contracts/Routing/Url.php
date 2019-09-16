<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

interface Url
{
    /**
     * Récupération de l'url propre. Nettoyée de la liste des arguments à exclure par défaut.
     *
     * @return string
     */
    public function clean();

    /**
     * Liste des arguments à exclure de l'url propre.
     *
     * @return array
     */
    public function cleanArgs(): array;

    /**
     * Récupération de l'url courante. Sans les arguments de requête.
     *
     * @return string
     */
    public function current(): string;

    /**
     * Récupération de l'url courante au format décodée.
     *
     * @return string
     */
    public function decode(): string;

    /**
     * Récupération de l'url courante complète. Arguments de requête inclus.
     *
     * @return string
     */
    public function full(): string;

    /**
     * Récupération de la sous arborescence du chemin de l'url.
     *
     * @return string
     */
    public function rewriteBase(): string;

    /**
     * Récupération de l'url vers la racine.
     *
     * @param string $path Chemin relatif vers une ressource du site.
     *
     * @return string
     */
    public function root(string $path = ''): string;

    /**
     * Récupération d'une url agrémentée d'une liste d'arguments de requête.
     *
     * @param array $args Liste des arguments de requête à inclure.
     *
     * @return string
     */
    public function with(array $args): string;

    /**
     * Récupération d'une url nettoyée d'une liste d'arguments de requête.
     *
     * @param string[] $args Liste des arguments de requête à exclure.
     *
     * @return string
     */
    public function without(array $args): string;
}