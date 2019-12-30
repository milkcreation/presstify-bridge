<?php declare(strict_types=1);

namespace tiFy\Support;

use tiFy\Contracts\Support\ParamsBag as ParamsBagContract;

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
    public function walk($attrs, $key = null): ParamsBagContract
    {
        return $this->items[$key] = (new ParamsBag())->set($attrs);
    }
}