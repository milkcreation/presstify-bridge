<?php declare(strict_types=1);

namespace tiFy\Field\Driver\CheckboxCollection;

use Illuminate\Support\Collection;
use tiFy\Contracts\Field\{
    CheckboxChoice as CheckboxChoiceContract,
    CheckboxWalker as CheckboxWalkerContract,
    CheckboxCollection as CheckboxCollectionContract
};
use tiFy\Support\Arr;

class CheckboxWalker implements CheckboxWalkerContract
{
    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    private $built = false;

    /**
     * Instance du champ associé.
     * @var CheckboxCollectionContract
     */
    protected $field;

    /**
     * Liste des éléments.
     * @var CheckboxChoice[]
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $items Liste des éléments
     */
    public function __construct(array $items)
    {
        array_walk($items, function($item, $key) {
            $this->setItem($item, $key);
        });
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function build(): CheckboxWalkerContract
    {
        if (!$this->built) {
            if ($this->exists()) {
                foreach ($this->items as $item) {
                    $item->setWalker($this)->build()->setNameAttr($this->field->getName());
                }

                $this->registerChecked();
            }

            $this->built = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return !!$this->items;
    }

    /**
     * @inheritDoc
     */
    public function registerChecked(): CheckboxWalkerContract
    {
        $checked = $this->field->getValue();
        $collect = new Collection($this->items);

        if (!is_null($checked)) {
            $checked = Arr::wrap($checked);

            $collect->each(function (CheckboxChoice $item) use ($checked) {
                if (in_array($item->getCheckbox()->get('checked'), $checked)) {
                    $item->setChecked();
                }
            });
        } elseif ($default = $this->field->get('default')) {
            if ($default === true) {
                if (!$collect->first(function (CheckboxChoice $item) { return $item->isChecked(); })) {
                    if ($first = $collect->first()) {
                        $first->setChecked();
                    }
                }
            } else {
                $default = Arr::wrap($default);

                $collect->each(function (CheckboxChoice $item) use ($default) {
                    if (in_array($item->getCheckbox()->get('checked'), $default)) {
                        $item->setChecked();
                    }
                });
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->field->viewer('choices', ['items' => $this->items]);
    }

    /**
     * @inheritDoc
     */
    public function setField(CheckboxCollectionContract $field): CheckboxWalkerContract
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setItem($item, $key = null): CheckboxChoiceContract
    {
        if (!$item instanceof CheckboxChoiceContract) {
            $item = new CheckboxChoice($key, $item);
        }

        return $this->items[$key] = $item;
    }
}