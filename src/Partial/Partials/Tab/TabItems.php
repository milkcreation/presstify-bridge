<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Tab;

use Illuminate\Support\Collection as LaraCollection;
use tiFy\Contracts\Partial\{
    Tab,
    TabItem as TabItemContract,
    TabItems as TabItemsContract
};
use tiFy\Support\Collection;

class TabItems extends Collection implements TabItemsContract
{
    /**
     * Valeur incrémentale de l'indice de qualification d'un élément.
     * @var int
     */
    protected $_itemIndex = 0;

    /**
     * Nom de qualification de l'élément actif.
     * @var string
     */
    protected $active;

    /**
     * Instance du constructeur associé.
     * @var Tab
     */
    protected $factory;

    /**
     * Liste des éléments par groupe.
     * @var TabItemContract[][]
     */
    protected $grouped = [];

    /**
     * Liste des éléments.
     * @var TabItemContract[]
     */
    protected $items = [];

    /**
     * Liste de déclaration d'éléments.
     * @var array
     */
    protected $registered = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param TabItemContract[]|array $items Liste des éléments ou Liste d'attributs d'élements.
     * @param mixed $active Nom de qualification de l'élément actif.
     *
     * @return void
     */
    public function __construct($items, $active = null)
    {
        $this->active = $active;
        $this->registered = $items;
    }

    /**
     * {@inheritdoc}
     *
     * @return TabItemContract|null
     */
    public function get($name): ?TabItemContract
    {
        return parent::get($name);
    }

    /**
     * @inheritdoc
     */
    public function getGrouped(string $parent = ''): iterable
    {
        return $this->grouped[$parent] ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getIndex(): int
    {
        return $this->factory->getIndex();
    }

    /**
     * @inheritdoc
     */
    public function getItemIndex(): int
    {
        return $this->_itemIndex++;
    }

    /**
     * @inheritdoc
     */
    public function prepare(Tab $factory): TabItemsContract
    {
        if (!$this->factory instanceof Tab) {
            $this->factory = $factory;
        }

        $this->set($this->registered);
        $this->parseRecursiveItems($this->items);

        $this->grouped = $this->collect()->groupBy('parent');
        foreach ($this->grouped as $group => $tabs) {
            /** @var LaraCollection $tabs */
            $max = $tabs->max(function (TabItemContract $tab) { return $tab['position']; }) ? : 0;
            $pad = 0;

            $this->grouped[$group] = $tabs->each(function (TabItemContract $tab) use (&$pad, $max) {
                $tab['position'] = is_numeric($tab['position']) ? $tab['position'] : ++$pad + $max;
            })->sortBy('position')->values();
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function parseRecursiveItems(array $items, int $depth = 0, string $parent = ''): void
    {
        foreach ($items as $item) {
            /* @var TabItemContract $item */
            if ($parent !== (string)$item->getParent()) {
                continue;
            } else {
                $item->setDepth($depth)->parse()->setActivation($this->active);
                $this->parseRecursiveItems($items, ($depth + 1), $item->getName());
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function walk($item, $key = null): TabItemContract
    {
        if (!$item instanceof TabItemContract) {
            $item = (new TabItem())->set((array)$item);
        }
        $item->prepare($this);

        return $this->items[$item->getName()] = $item;
    }
}