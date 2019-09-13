<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Contracts\Template\FactoryDb;
use tiFy\Support\Collection;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{Items as ItemsContract, Item as ItemContract};

class Items extends Collection implements ItemsContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Indice de l'élément courant.
     * @var int
     */
    protected $offset = 0;

    /**
     * Liste des éléments.
     * @var array|ItemContract[]
     */
    protected $items = [];

    /**
     * Nom de qualification de la colonne de données primaire.
     * @var string
     */
    protected $primaryKey;

    /**
     * @inheritDoc
     */
    public function primaryKey(): ?string
    {
        return $this->primaryKey;
    }

    /**
     * @inheritDoc
     */
    public function setItem($item): ?ItemContract
    {
        if ($item instanceof FactoryDb) {
            $item = $item->attributesToArray();
        } elseif (is_object($item)) {
            $item = get_object_vars($item);
        }

        /** @var Item $item */
        $item = $this->factory->resolve('item')->set((array)$item);
        if (is_null($this->primaryKey())) {
            $this->setPrimaryKey((string)current($item->keys()));
        }

        return $item->has($this->primaryKey()) ? $item : null;
    }

    /**
     * @inheritDoc
     */
    public function setPrimaryKey(string $primaryKey): ItemsContract
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk($item, $key = null): ?ItemContract
    {
        if ($item = $this->setItem($item)) {
            $this->items[] = $item->setOffset(is_numeric($key) ? $key : $this->offset)->parse();
            $this->offset++;

            return $item;
        } else {
            return null;
        }
    }
}