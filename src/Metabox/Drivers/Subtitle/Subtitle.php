<?php declare(strict_types=1);

namespace tiFy\Metabox\Drivers\Subtitle;

use tiFy\Metabox\MetaboxDriver;
use tiFy\Support\Proxy\Field;

class Subtitle extends MetaboxDriver
{
    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = 'subtitle';

    /**
     * @inheritDoc
     */
    public function content(): string
    {
        return (string)Field::get('text', array_merge($this->params(), [
            'name'  => $this->name(),
            'value' => $this->value()
        ]));
    }

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return [
            'attrs'   => [
                'class'       => 'widefat',
                'placeholder' => __('Sous-titre', 'tify'),
                'style'       => 'margin-top:10px;margin-bottom:20px;background-color:#fff;font-size:1.4em;' .
                    'height:1.7em;line-height:100%;margin:10 0 15px;outline:0 none;padding:3px 8px;width:100%;'
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'    => 'subtitle',
            'title'   => __('Sous-titre', 'tify')
        ]);
    }
}