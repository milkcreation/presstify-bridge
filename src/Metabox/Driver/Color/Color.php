<?php declare(strict_types=1);

namespace tiFy\Metabox\Driver\Color;

use tiFy\Metabox\MetaboxDriver;

class Color extends MetaboxDriver
{
    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = 'color';

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'  => 'color',
            'title' => __('Couleur', 'tify')
        ]);
    }
}