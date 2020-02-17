<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionDeactivate extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'style' => 'color:#D98500;',
                'title' => __('Désactivation de l\'élément', 'tify'),
            ],
            'content' => __('Désactiver', 'tify'),
        ]);
    }
}