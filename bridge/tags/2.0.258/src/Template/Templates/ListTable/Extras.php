<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\Collection;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{
    Extra as ExtraContract,
    Extras as ExtrasContract
};

class Extras extends Collection implements ExtrasContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Liste des actions groupées.
     * @var array|ExtraContract[]
     */
    protected $items = [];

    /**
     * Position de l'interface de navigation.
     * @var string
     */
    protected $which = 'top';

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
    public function render(): string
    {
        $output = '';

        $items = $this->collect()->sortBy('order')->filter(function (ExtraContract $item) {
            return in_array($this->which, $item->which());
        });

        foreach($items as $item) {
            $output .= (string)$item;
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function setWhich(string $which): ExtrasContract
    {
        $this->which = $which;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk($extra, $key = null): ?ExtraContract
    {
        if (!$extra instanceof ExtraContract) {
            if (is_string($extra)) {
                $name = $extra;
                $attrs = [];
            } elseif (is_array($extra)) {
                $name = $key;
                $attrs = $extra;
            } else {
                return null;
            }

            $alias = $this->factory->bound("extra.{$name}")
                ? "extra.{$name}"
                : 'extra';

            /** @var ExtraContract $extra */
            $extra = $this->factory->resolve($alias);
            $extra->set($attrs);
        }

        return $this->items[$key] = $extra->parse();
    }
}