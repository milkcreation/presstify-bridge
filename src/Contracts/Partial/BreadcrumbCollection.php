<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface BreadcrumbCollection
{
    /**
     * Ajout de la déclaration d'un élément.
     *
     * @param string $render Rendu de l'élément.
     * @param int|null $position Position de l'élément.
     * @param array $wrapper Liste des attribut de configuration de l'encapsulation.
     *
     * @return int
     */
    public function add(string $render, ?int $position = null, array $wrapper = []): int;

    /**
     * Récupération de la liste des éléments déclarés.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Suppression de la liste complète des éléments déclarés|Suppression d'un élément déclaré selon sa position.
     *
     * @param int|null $position
     *
     * @return BreadcrumbCollection
     */
    public function clear(?int $position = null): BreadcrumbCollection;

    /**
     * Retrouve les portions du fil d'ariane basés sur la liste des éléments déclarés.
     *
     * @return array
     */
    public function fetch(): array;

    /**
     * Récupération de la déclaration d'un élément selon sa position.
     *
     * @param int $position
     *
     * @return array|null
     */
    public function get(int $position): ?array;

    /**
     * Récupération du rendu d'un élement.
     *
     * @param string|null $content Définition du contenu.
     * @param string|null $url Définition de l'url.
     * @param array $attrs Liste des attributs HTML de l'élément.
     *
     * @return string
     */
    public function getRender(string $content, ?string $url = null,  array $attrs = []) : string;

    /**
     * Récupération d'une url.
     *
     * @param bool|string $url Définition de l'url.
     * @param string|null $default Définition de la valeur de retour par défaut.
     *
     * @return string|null
     */
    public function getUrl($url, ?string $default = '#'): ?string;

    /**
     * Test d'existance d'un élément déclaré selon sa position.
     *
     * @param int $position
     *
     * @return string
     */
    public function has(int $position): bool;

    /**
     * Instance du pilote de fil d'ariane.
     *
     * @return Breadcrumb
     */
    public function manager(): Breadcrumb;

    /**
     * Déplace un élément déclaré vers une position déterminée.
     *
     * @param int $from Position actuelle de l'élément à déplacer.
     * @param int $to Position attendue de l'élément.
     *
     * @return int|null
     */
    public function move(int $from, int $to): ?int;
}