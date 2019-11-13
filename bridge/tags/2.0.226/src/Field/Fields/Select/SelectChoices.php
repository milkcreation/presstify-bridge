<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Select;

use Illuminate\Support\Arr;
use tiFy\Contracts\Field\SelectChoice as SelectChoiceContract;
use tiFy\Contracts\Field\SelectChoices as SelectChoicesContract;
use tiFy\Support\Collection;

class SelectChoices extends Collection implements SelectChoicesContract
{
    /**
     * Liste des éléments.
     * @var SelectChoiceContract[]
     */
    protected $items = [];

    /**
     * Liste des éléments sélectionnés.
     * @var array
     */
    protected $selected = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $items
     * @param mixed $selected Liste des éléments selectionnés
     */
    public function __construct($items = [], $selected = null)
    {
        foreach($items as $name => $attrs) {
            $this->map($name, $attrs);
        }
        $this->selected = Arr::wrap($selected);
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritdoc
     */
    public function map($name, $attrs, $parent = null): SelectChoicesContract
    {
        if (is_scalar($attrs)) {
            $this->walk(['content' => (string)$attrs, 'parent' => $parent], $name);
        } elseif (is_array($attrs)) {
            $this->walk(['content' => $name, 'group' => true, 'parent' => $parent], $name);
            foreach ($attrs as $_name => $_attrs) {
                $this->map($_name, $_attrs, $name);
            }
        } else {
            $this->walk($attrs, $name);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function render(): string
    {
        return $this->walker($this->items);
    }

    /**
     * @inheritdoc
     */
    public function walker($items = [], $depth = 0, $parent = null): string
    {
        $output = "";
        foreach ($items as $item) {
            /** @var SelectChoiceContract $item */
            if ($item->getParent() !== $parent) {
                continue;
            } else {
                $item->setDepth($depth)->parse()->setSelected($this->selected);

                $output .= $item->tagOpen();
                $output .= $item->tagContent();
                $output .= $this->walker($items, ($depth + 1), $item->getName());
                $output .= $item->tagClose();
            }
        }
        return $output;
    }

    /**
     * @inheritdoc
     */
    public function walk($item, $name = null): SelectChoiceContract
    {
        if (!$item instanceof SelectChoice) {
            $item = new SelectChoice($name, $item);
        }
        return $this->items[] = $item;
    }
}