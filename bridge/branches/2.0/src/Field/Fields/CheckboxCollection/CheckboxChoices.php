<?php

namespace tiFy\Field\Fields\CheckboxCollection;

use Illuminate\Support\Arr;
use tiFy\Contracts\Field\CheckboxChoices as CheckboxChoicesContract;
use tiFy\Contracts\Field\CheckboxCollection;
use tiFy\Support\Collection;

class CheckboxChoices extends Collection implements CheckboxChoicesContract
{
    /**
     * Instance du champ associé.
     * @var CheckboxCollection
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
     * @param string $name Nom de soumission de l'élément dans la requête de traitement.
     * @param mixed $checked Liste des éléments selectionnés.
     */
    public function __construct($items, $name, $checked = null)
    {
        array_walk($items, function($item, $key) use ($name) {
            $this->walk($item, $key)->setName($name);
        });

        $this->setChecked($checked);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->field->viewer()->make('choices', ['items' => $this->items]);
    }

    /**
     * {@inheritdoc}
     */
    public function setChecked($checked = null)
    {
        if (!is_null($checked)) {
            $checked = Arr::wrap($checked);

            $this->collect()->each(function (CheckboxChoice $item) use ($checked) {
                if (in_array($item->getValue(), $checked)) {
                    $item->setChecked();
                }
            });
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setField(CheckboxCollection $field)
    {
        if (!$this->field instanceof CheckboxCollection) {
            $this->field = $field;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function walk($item, $key = null)
    {
        if (!$item instanceof CheckboxChoice) {
            $item = new CheckboxChoice($key, $item);
        }

        return $this->items[$key] = $item;
    }
}