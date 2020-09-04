<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\ViewFilter as BaseViewFilter;

class ViewFilterTrash extends BaseViewFilter
{
    /**
     * @inheritDoc
     */
    public function defaults() : array
    {
        if ($builder = $this->factory->builder()) {
            $builder->remove(['post_status']);

            $count = $builder->queryWhere()->where('post_status', 'trash')->count();
        } else {
            $count = 0;
        }

        return [
            'content'     => __('Corbeille', 'tify'),
            'count_items' => $count,
            'hide_empty'  => true,
            'show_count'  => true,
            'query_args'  => ['post_status' => 'trash'],
            'current'     => $this->factory->request()->input('post_status') === 'trash'
        ];
    }
}