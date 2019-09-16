<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\ViewFilter as BaseViewFilter;

class ViewFilterPublish extends BaseViewFilter
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        if ($builder = $this->factory->builder()) {
            $builder->remove(['post_status']);

            $count = $builder->queryWhere()->where('post_status', 'publish')->count();
        } else {
            $count = 0;
        }

        return [
            'content'     => _n('Publié', 'Publiés', ($count > 1 ? 2 : 1), 'tify'),
            'count_items' => $count,
            'show_count'  => true,
            'query_args'  => ['post_status' => 'publish'],
            'current'     => $this->factory->request()->input('post_status') === 'publish'
        ];
    }
}