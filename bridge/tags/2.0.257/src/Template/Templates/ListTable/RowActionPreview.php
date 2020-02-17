<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionPreview extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'title' => __('Prévisualisation de l\'élément', 'tify'),
            ],
            'content' => __('Prévisualisation', 'tify'),
        ]);
    }
}