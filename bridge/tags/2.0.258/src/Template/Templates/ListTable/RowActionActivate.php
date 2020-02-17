<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionActivate extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(),[
            'attrs'   => [
                'style' => 'color:#006505;',
                'title'   => __('Activation de l\'élément', 'tify'),
            ],
            'content' => __('Activer', 'tify'),
        ]);
    }
}