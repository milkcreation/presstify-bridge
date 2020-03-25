<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\Collection;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{RowAction as RowActionContract, RowActions as RowActionsContract};

class RowActions extends Collection implements RowActionsContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Liste des instances des actions déclarées.
     * @var RowActionContract[]|array
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function isAlwaysVisible(): bool
    {
        return (bool)$this->factory->param('row_actions_always_visible', true);
    }

    /**
     * @inheritDoc
     */
    public function parse(array $row_actions = []): RowActionsContract
    {
        if ($row_actions) {
            foreach ($row_actions as $name => $attrs) {
                if (is_numeric($name)) {
                    $name = $attrs;
                    $attrs = [];
                } elseif (is_string($attrs)) {
                    $attrs = ['content' => $attrs];
                }

                $alias = $this->factory->bound("row-action.{$name}")
                    ? "row-action.{$name}"
                    : 'row-action';

                /** @var RowAction $row_action */
                $row_action = $this->factory->resolve($alias);

                $this->items[$name] = $row_action->setName($name)->set($attrs);
            }

            $this->items = array_filter($this->items, function ($value) {
                return (string)$value !== '';
            });
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $output = '';
        $output .= "<div class=\"" . ($this->isAlwaysVisible() ? 'row-actions visible' : 'row-actions') . "\">";
        /**  @var RowAction $r */
        foreach ($this->all() as $r) {
            if ($r->parse()->isAvailable()) {
                $output .= "<span class=\"{$r->getName()}\">{$r}</span>";
            }
        }
        $output .= "</div>";

        return $output;
    }
}