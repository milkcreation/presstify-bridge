<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class BulkActionTrash extends BulkAction
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'content' => __('Mettre à la corbeille', 'tify')
        ]);
    }
}