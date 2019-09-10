<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\Collection;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{Column, Columns as ColumnsContract};

class Columns extends Collection implements ColumnsContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Liste des colonnes.
     * @var Column[]
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function countVisible(): int
    {
        return count($this->getVisible());
    }

    /**
     * @inheritDoc
     */
    public function get($key): ?Column
    {
        return parent::get($key);
    }

    /**
     * @inheritDoc
     */
    public function getHideable(): iterable
    {
        return $this->collect()
            ->filter(function (Column $item) {
                return $item->get('hideable') === true;
            });
    }

    /**
     * @inheritDoc
     */
    public function getHidden(): array
    {
        return $this->collect()
            ->filter(function (Column $item) {
                return $item->isHidden();
            })
            ->pluck('name', null)
            ->all();
    }

    /**
     * @inheritDoc
     */
    public function getPrimary(): string
    {
        if (
            ($column_primary = $this->factory->param('column_primary', '')) &&
            ($column = $this->get($column_primary)) &&
            $column->canUseForPrimary()
        ) {
            return (string)$column_primary;
        } else {
            return $this->collect()->first(function (Column $item) {
                return $item->canUseForPrimary();
            })->getName();
        }
    }

    /**
     * @inheritDoc
     */
    public function getSortable(): array
    {
        return $this->collect()
            ->filter(function (Column $item) {
                return $item->isSortable();
            })
            ->pluck('sortable', 'name')
            ->all();
    }

    /**
     * @inheritDoc
     */
    public function getVisible(): array
    {
        return $this->collect()
            ->filter(function (Column $item) {
                return !$item->isHidden();
            })
            ->pluck('name', null)
            ->all();
    }

    /**
     * @inheritDoc
     */
    public function parse(array $columns = []): ColumnsContract
    {
        foreach ($columns as $name => $attrs) {
            if (is_numeric($name)) {
                $name = $attrs;
                $attrs = [];
            } elseif (is_string($attrs)) {
                $attrs = ['title' => $attrs];
            }

            $alias = $this->factory->bound("column.{$name}")
                ? "column.{$name}"
                : 'column';

            $this->items[$name] = $this->factory->resolve($alias, [$name, $attrs]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function renderToggle(): string
    {
        return ($cols = $this->getHideable()) ? (string)$this->factory->viewer('columns-toggle', compact('cols')) : '';
    }
}