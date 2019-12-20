<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\Collection;

interface TabItems extends Collection
{
    /**
     * Récupération de la liste des éléments d'un groupe.
     *
     * @param string $parent Nom de qualification du parent.
     *
     * @return TabItem[][]
     */
    public function getGrouped(string $parent = ''): iterable;

    /**
     * Récupération l'indice du controleur.
     *
     * @return int
     */
    public function getIndex(): int;

    /**
     * Récupération de la valeur incrémentale de l'indice d'un élément.
     *
     * @return int
     */
    public function getItemIndex(): int;

    /**
     * Préparation du controleur.
     *
     * @param Tab $factory
     *
     * @return static
     */
    public function prepare(Tab $factory): TabItems;

    /**
     * Traitement récursif de la liste des éléments.
     *
     * @param TabItem[] $items Liste des éléments à traité.
     * @param int $depth Niveau de profondeur.
     * @param string $parent Nom de qualification de l'élément parent.
     *
     * @return void
     */
    public function parseRecursiveItems(array $items, int $depth = 0, string $parent = ''): void;
}