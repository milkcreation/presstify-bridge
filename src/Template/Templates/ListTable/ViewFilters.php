<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\Collection;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{ViewFilter as ViewFilterContract, ViewFilters as ViewFiltersContract};

class ViewFilters extends Collection implements ViewFiltersContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Liste des filtres.
     * @var array|ViewFilterContract[]
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function parse(array $filters = []): ViewFiltersContract
    {
        if ($filters) {
            foreach ($filters as $name => $attrs) {
                if (is_numeric($name)) {
                    $name = $attrs;
                    $attrs = [];
                } elseif (is_string($attrs)) {
                    $attrs = ['content' => $attrs];
                } elseif ($attrs instanceof ViewFilterContract) {
                    $this->items[$name] = $attrs->setTemplateFactory($this->factory)->setName($name)->parse();
                    continue;
                }

                $alias = $this->factory->bound("view-filter.{$name}")
                    ? "view-filter.{$name}"
                    : 'view-filter';

                $this->items[$name] = $this->factory->resolve($alias, [$name, $attrs]);
            }

            $this->items = array_filter($this->items, function ($value) {
                return (string)$value !== '';
            });
        }

        return $this;
    }
}