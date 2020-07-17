<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Templates\ListTable\Params as BaseParams;

class Params extends BaseParams
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        $defaults = array_merge(parent::defaults(), [
            'columns'      => [
                'cb',
                'post_title',
                'post_type',
                'post_date' => __('Date', 'tify'),
            ],
            'view-filters' => [
                'all',
                'publish',
                'trash'
            ],
            'query_args'   => [
                'post_type'   => array_diff(
                    array_keys(get_post_types()),
                    [
                        'attachment',
                        'custom_css',
                        'customize_changeset',
                        'nav_menu_item',
                        'oembed_cache',
                        'revision',
                        'user_request'
                    ]
                ),
                'post_status' => ['publish', 'pending', 'draft', 'future', 'private', 'inherit'],
            ],
        ]);

        if ($this->factory->request()->input('status') !== 'trash') {
            $defaults['bulk-actions'] = ['trash' => __('Déplacer dans la corbeille', 'tify')];
            $defaults['row-actions'] = ['edit', 'trash'];
        } else {
            $defaults['bulk-actions'] = ['untrash', 'delete'];
            $defaults['row-actions'] = ['untrash', 'delete'];
        }

        return $defaults;
    }
}