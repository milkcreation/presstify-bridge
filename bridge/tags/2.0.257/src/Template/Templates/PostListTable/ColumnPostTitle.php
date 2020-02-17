<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\Column as BaseColumn;

class ColumnPostTitle extends BaseColumn
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'title' => __('Titre', 'tify')
        ];
    }

    /**
     * @inheritDoc
     */
    public function value(): string
    {
        return ($item = $this->factory->item())
            ? "<strong>{$item['post_title']}</strong>"
            : '';
    }
}