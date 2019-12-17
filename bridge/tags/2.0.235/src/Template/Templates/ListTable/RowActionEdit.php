<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class RowActionEdit extends RowAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'attrs'   => [
                'target' => '_blank',
                'title' => __('Modification de l\'élément', 'tify'),
            ],
            'content' => __('Modifier', 'tify'),
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