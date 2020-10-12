<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionTrash extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'title' => __('Mettre l\'élément à la corbeille', 'tify'),
            ],
            'content' => __('Corbeille', 'tify'),
        ]);
    }
}