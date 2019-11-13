<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\Str;
use tiFy\Template\Factory\Params as BaseParams;
use tiFy\Template\Templates\ListTable\Contracts\Params as ParamsContract;

class Params extends BaseParams implements ParamsContract
{
    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritdoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'                      => [],
            'edit_base_uri'              => '',
            'bulk-actions'               => [],
            //'columns'                    => [],
            'column_primary'             => '',
            'primary_key'                => '',
            'search'                     => true,
            'table'                      => [],
            'view-filters'               => [],
            'row-actions'                => [],
            'row_actions_always_visible' => false
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parse(): Params
    {
        parent::parse();

        $base = 'ListTable';

        $containerClass = "{$base} {$base}--" . Str::camel($this->factory->name());
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $containerClass);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $containerClass));
        }

        if (!$this->get('attrs.class')) {
            $this->forget('attrs.class');
        }

        $tableClass = trim(sprintf(
            $this->get('table.attrs.class', '%s'), 'wp-list-table widefat fixed striped ' . $this->get('plural'))
        );
        $this->set('table.attrs.class', $tableClass);

        return $this;
    }
}