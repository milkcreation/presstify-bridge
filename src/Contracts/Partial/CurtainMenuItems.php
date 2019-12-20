<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\Collection;

interface CurtainMenuItems extends Collection
{
    /**
     * Récupération de la liste des éléments associés à un parent.
     *
     * @param string|null $parent Nom de qualification du parent associé.
     *
     * @return CurtainMenuItem[]|array
     */
    public function getParentItems(?string $parent = null): array;

    /**
     * Préparation de l'instance.
     *
     * @param CurtainMenu $partial Instance du constructeur de portion d'affichage associé.
     *
     * @return static
     */
    public function prepare(CurtainMenu $partial): CurtainMenuItems;

    /**
     * Préparation de la liste des éléments.
     *
     * @param CurtainMenuItem[] $items Liste des éléments à traiter.
     * @param integer $depth Niveau de profondeur.
     * @param string|null $parent Élément parent.
     *
     * @return static
     */
    public function prepareItems(array $items = [], int $depth = 0, ?string $parent = null): CurtainMenuItems;
}