<?php

namespace tiFy\Support;

class ParamsBagCollection extends Collection
{
    /**
     * ParamsBagCollection constructor.
     *
     * @param array $items Liste des éléments
     *
     * @return void
     */
    public function __construct(array $items)
    {
        $this->set($items);
    }

    /**
     * @inheritdoc
     */
    public function walk($attrs, $key = null)
    {
        return $this->items[$key] = ParamsBag::createFromAttrs($attrs);
    }
}