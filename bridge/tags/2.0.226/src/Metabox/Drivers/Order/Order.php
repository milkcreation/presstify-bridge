<?php declare(strict_types=1);

namespace tiFy\Metabox\Drivers\Order;

use tiFy\Metabox\MetaboxDriver;

class Order extends MetaboxDriver
{
    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = 'order';

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return [
            'attrs' => [
                'min' => -1
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'  => 'order',
            'title' => __('Ordre d\'affichage', 'tify')
        ]);
    }
}