<?php declare(strict_types=1);

namespace tiFy\Metabox\Driver\Excerpt;

use tiFy\Metabox\MetaboxDriver;

class Excerpt extends MetaboxDriver
{
    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = 'excerpt';

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'  => 'excerpt',
            'title' => __('Extrait', 'tify')
        ]);
    }
}