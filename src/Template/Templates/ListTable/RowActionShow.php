<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionShow extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'target' => '_blank',
                'title' => __('Afficher l\'élément', 'tify'),
            ],
            'content' => __('Afficher', 'tify'),
            'url'     => '',
            'xhr'     => false,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return !is_null($this->url);
    }
}