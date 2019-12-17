<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionUntrash extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'title' => __('Restauration de l\'élément', 'tify'),
            ],
            'content' => __('Restaurer', 'tify'),
        ]);
    }
}