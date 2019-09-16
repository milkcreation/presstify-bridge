<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionDuplicate extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'title' => __('Duplication de l\'élément', 'tify'),
            ],
            'content' => __('Dupliquer', 'tify'),
        ]);
    }
}