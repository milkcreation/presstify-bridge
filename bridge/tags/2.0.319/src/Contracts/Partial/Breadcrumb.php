<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Breadcrumb extends PartialDriver
{
    /**
     * Ajout de la déclaration d'un élément.
     *
     * @param string|array|object $item
     *
     * @return int|null
     */
    public function add($item): ?int;

    /**
     * Ajout de la déclaration d'un élément (alias).
     * @see static::add()
     *
     * @param string|array|object $item
     *
     * @return int|null
     */
    public function append($item): ?int;

    /**
     * Récupération de l'instance du gestion de collection d'éléments.
     *
     * @return BreadcrumbCollection
     */
    public function collection(): BreadcrumbCollection;

    /**
     * Désactivation de l'affichage.
     *
     * @return static
     */
    public function disable(): Breadcrumb;

    /**
     * Activation de l'affichage.
     *
     * @return static
     */
    public function enable(): Breadcrumb;


    /**
     * Réinitialisation de la liste des éléments déclarés.
     *
     * @return static
     */
    public function flush(): Breadcrumb;

    /**
     * Récupération de l'instance principale.
     *
     * @return Breadcrumb
     */
    public function main(): Breadcrumb;

    /**
     * Ajout de la déclaration d'un élément au début de la liste.
     *
     * @param string|array|object $item
     *
     * @return int|null
     */
    public function prepend($item): ?int;

    /**
     * Vérification d'activation.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Insertion d'un élément déclaré à une position déterminée.
     *
     * @param int $position
     * @param string|array|object $item
     *
     * @return int|null
     */
    public function insert(int $position, $item): ?int;

    /**
     * Déplace un élément déclaré vers une position déterminée.
     *
     * @param int $from Position actuelle de l'élément à déplacer.
     * @param int $to Position attendue de l'élément.
     *
     * @return int|null
     */
    public function move(int $from, int $to): ?int;

    /**
     * Supprime un élément déclaré selon sa position.
     *
     * @param int $position
     * @param string|array|object $item
     *
     * @return Breadcrumb
     */
    public function remove(int $position): Breadcrumb;

    /**
     * Remplace un élément déclaré par un autre selon une position déterminée.
     *
     * @param int $position
     * @param string|array|object $item
     *
     * @return int|null
     */
    public function replace(int $position, $item): ?int;
}