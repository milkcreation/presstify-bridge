<?php

namespace tiFy\Field\Driver\RadioCollection;

use Illuminate\Support\Arr;
use tiFy\Contracts\Field\RadioChoices as RadioChoicesContract;
use tiFy\Contracts\Field\RadioCollection;
use tiFy\Support\Collection;

class RadioChoices extends Collection implements RadioChoicesContract
{
    /**
     * Instance du champ associé.
     * @var RadioCollection
     */
    protected $field;

    /**
     * Liste des éléments.
     * @var RadioChoice[]
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
        return $this->field->viewer('choices', ['items' => $this->items]);
    }

    /**
     * {@inheritdoc}
     */
    public function setChecked($checked = null)
    {
        if (!is_null($checked)) {
            $checked = Arr::wrap($checked);

            $this->collect()->each(function (RadioChoice $item) use ($checked) {
                if (in_array($item->getValue(), $checked)) {
                    $item->setChecked();
                }
            });
        }

        if (!$this->collect()->first(function(RadioChoice $item) { return $item->isChecked(); })) {
            if ($first = $this->collect()->first()) {
                $first->setChecked();
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setField(RadioCollection $field)
    {
        if (!$this->field instanceof RadioCollection) {
            $this->field = $field;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function walk($item, $key = null)
    {
        if (!$item instanceof RadioChoice) {
            $item = new RadioChoice($key, $item);
        }

        return $this->items[$key] = $item;
    }
}