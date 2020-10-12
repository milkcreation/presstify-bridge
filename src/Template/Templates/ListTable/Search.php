<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\ParamsBag;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\Search as SearchContract;

class Search extends ParamsBag implements SearchContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

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
    public function defaults(): array
    {
        return [
            'attrs'  => [],
            'input'  => [],
            'submit' => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return $this->factory->param('search') &&
               ($this->factory->items()->exists() || $this->factory->request()->input('s'));
    }

    /**
     * @inheritDoc
     */
    public function parse(): SearchContract
    {
        parent::parse();

        $class = 'search-box';
        if ( ! $this->has('attrs.class')) {
            $this->set('attrs.class', $class);
        } elseif ($_class = $this->get('attrs.class')) {
            $this->set('attrs.class', sprintf($_class, $class));
        }

        $this->set('input', array_merge([
            'attrs' => [
                'id'   => $this->factory->name(),
                'type' => 'search',
            ],
            'name'  => 's',
            'value' => $this->factory->request()->input('s', ''),
        ], $this->get('input') ? : []));

        $this->set('submit', array_merge([
            'attrs'   => [
                'id'    => 'search-submit',
                'class' => 'button',
                'type'  => 'submit',
            ],
            'content' => $this->factory->label('search_item'),
            'tag'     => 'button'
        ], $this->get('submit') ? : []));

        if ($this->factory->ajax()) {
            $this->set('attrs.data-control', 'list-table.search');
            $this->set('submit.attrs.data-control', 'list-table.search.submit');
            $this->set('input.attrs.data-control', 'list-table.search.input');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return (string)$this->factory->viewer('search');
    }
}