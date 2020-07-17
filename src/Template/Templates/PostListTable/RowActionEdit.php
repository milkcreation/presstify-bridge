<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\{
    Contracts\Item as BaseItem,
    RowActionEdit as BaseRowActionEdit
};
use tiFy\Template\Templates\PostListTable\Contracts\Item;
use tiFy\Support\Proxy\Url;

class RowActionEdit extends BaseRowActionEdit
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'title' => __('Modification de l\'élément', 'tify'),
            ],
            'content' => __('Modifier', 'tify'),
            'url'     => function (BaseItem $item) {
                /** @var Item $item */
                return Url::set($item->getEditUrl());
            },
        ]);
    }
}