<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Support\Collection;

interface FactoryGroups extends FactoryResolver, Collection
{
    /**
     * Récupération de la liste des groupes rangés par parent.
     *
     * @return FactoryGroup[]|null
     */
    public function getGrouped(string $parent = ''): ?iterable;

    /**
     * Récupération de l'indice incrémenté de qualification d'un élément.
     *
     * @return int
     */
    public function getIncreasedIndex(): int;

    /**
     * Préparation de la liste des groupes.
     *
     * @return static
     */
    public function prepare(): FactoryGroups;
}