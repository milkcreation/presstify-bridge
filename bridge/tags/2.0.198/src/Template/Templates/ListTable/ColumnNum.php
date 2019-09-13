<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

class ColumnNum extends Column
{
    /**
     * @inheritDoc
     */
    public function canUseForPrimary(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'title' => __('#', 'tify')
        ];
    }

    /**
     * @inheritDoc
     */
    public function value(): string
    {
        if (!$item = $this->factory->item()) {
            return '';
        } else {
            $per_page = $this->factory->pagination()->getPerPage();
            $page = $this->factory->pagination()->getPage();

            return (string)($per_page*($page-1)+($item->getOffset()+1));
        }
    }
}