<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\Column as BaseColumn;

class ColumnPostType extends BaseColumn
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'title' => __('Type', 'tify')
        ];
    }

    /**
     * @inheritDoc
     */
    public function value(): string
    {
        if ($item = $this->factory->item()) {
            return ($postType = post_type($item['post_type']))
                ? $postType->label('singular_name')
                : "{$item['post_type']}";
        } else {
            return '';
        }
    }
}