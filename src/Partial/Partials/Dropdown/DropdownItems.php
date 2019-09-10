<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Dropdown;

use tiFy\Contracts\Partial\Dropdown;
use tiFy\Contracts\Partial\DropdownItem as DropdownItemContract;
use tiFy\Contracts\Partial\DropdownItems as DropdownItemsContract;
use tiFy\Kernel\Collection\Collection;

class DropdownItems extends Collection implements DropdownItemsContract
{
    /**
     * Instance du controleur d'affichage associé.
     * @var Dropdown
     */
    protected $partial;

    /**
     * Liste des éléments.
     * @var array
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
        array_walk($items, [$this, 'wrap']);
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
     * @inheritdoc
     */
    public function wrap($item, $key = null)
    {
        if(!$item instanceof DropdownItemContract) :
            $item = new DropdownItem($key, $item);
        endif;

        $this->items[$key] = $item;
    }
}