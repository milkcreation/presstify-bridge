<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Template\FactoryDb;
use tiFy\Contracts\Support\Collection as Collection;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface Items extends FactoryAwareTrait, Collection
{
    /**
     * Récupération de la colonne de clé primaire.
     *
     * @return string|null
     */
    public function primaryKey(): ?string;

    /**
     * Définition d'un élément.
     *
     * @param FactoryDb|object|array $item
     *
     * @return Item|null
     */
    public function setItem($item): ?Item;

    /**
     * Définition de la colonne de clé primaire.
     *
     * @param string $primaryKey
     *
     * @return static
     */
    public function setPrimaryKey(string $primaryKey): Items;
}