<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\ViewFilter as BaseViewFilter;

class ViewFilterAll extends BaseViewFilter
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        if ($builder = $this->factory->builder()) {
            $builder->remove(['post_status']);

            $count = $builder->queryWhere()->whereIn('post_status', [
                'publish',
                'pending',
                'draft',
                'future',
                'private',
                'inherit',
            ])->count();
        } else {
            $count = 0;
        }

        return [
            'content'           => __('Tous', 'tify'),
            'count_items'       => $count,
            'show_count'        => true,
            'remove_query_args' => ['post_status'],
            'current'           => !$this->factory->request()->input('post_status', ''),
        ];
    }
}