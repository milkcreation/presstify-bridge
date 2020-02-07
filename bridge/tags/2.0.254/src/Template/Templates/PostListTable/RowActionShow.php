<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\{Contracts\Item as BaseItem, RowActionShow as BaseRowActionShow};
use tiFy\Template\Templates\PostListTable\Contracts\Item;
use tiFy\Support\Proxy\Url;

class RowActionShow extends BaseRowActionShow
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'title' => __('Afficher l\'élément', 'tify'),
            ],
            'content' => __('Afficher', 'tify'),
            'url'     => function (BaseItem $item) {
                /** @var Item $item */
                return Url::set($item->getPermalink());
            }
        ]);
    }
}