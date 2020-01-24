<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionDelete extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'style' => 'color:#a00;',
                'title' => __('Suppression définitive de l\'élément', 'tify'),
            ],
            'content' => __('Supprimer définitivement', 'tify'),
        ]);
    }
}