<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Dropdown;

use tiFy\Contracts\Partial\{
    Dropdown,
    DropdownItem as DropdownItemContract,
    DropdownItems as DropdownItemsContract
};
use tiFy\Support\Collection;

class DropdownItems extends Collection implements DropdownItemsContract
{
    /**
     * Instance du controleur d'affichage associé.
     * @var Dropdown
     */
    protected $partial;

    /**
     * Liste des éléments.
     * @var DropdownItem[]|array
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $items Liste des éléments.
     *
     * @return void
     */
    public function __construct(array $items)
    {
        array_walk($items, [$this, 'walk']);
    }

    /**
     * Définition du controleur de controleur d'affichage associé.
     *
     * @param Dropdown $partial Controleur d'affichage associé.
     *
     * @return static
     */
    public function setPartial(Dropdown $partial)
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk($item, $key = null): DropdownItemContract
    {
        if(!$item instanceof DropdownItemContract) {
            $item = new DropdownItem($key, $item);
        }

        return $this->items[$key] = $item;
    }
}